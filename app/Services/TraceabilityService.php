<?php

namespace App\Services;

use App\Models\ProductionBatch;
use App\Models\RawMaterialLot;
use App\Models\Document;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TraceabilityService
{
    /**
     * Backward Traceability: From Finished Batch / QR to Farm/Supplier
     */
    public function getBackwardTraceability(string $batchCode): array
    {
        $batch = ProductionBatch::with([
            'product.category',
            'bom.items.rawMaterial',
            'materials.rawMaterialLot.rawMaterial',
            'materials.rawMaterialLot.supplier',
            'materials.rawMaterialLot.documents',
            'packagings.packagingMaterialLot.packagingMaterial',
            'packagings.packagingMaterialLot.supplier',
            'inspections.results',
            'documents',
        ])
        ->where('batch_id', $batchCode)
        ->orWhere('finished_goods_barcode', $batchCode)
        ->orWhere('id', $batchCode)
        ->first();

        if (!$batch) {
            throw new ModelNotFoundException("لم يتم العثور على أي دفعة مطابقة للكود: {$batchCode}");
        }

        $rawMaterialsDetailed = [];
        foreach ($batch->materials as $mat) {
            $lot = $mat->rawMaterialLot;
            $rawMaterialsDetailed[] = [
                'lot_number' => $lot?->lot_number,
                'material_name' => $lot?->rawMaterial?->name,
                'material_name_ar' => $lot?->rawMaterial?->name_ar,
                'supplier_name' => $lot?->supplier?->name,
                'supplier_name_ar' => $lot?->supplier?->name_ar,
                'supplier_lot_number' => $lot?->supplier_lot_number,
                'receiving_date' => $lot?->receiving_date?->format('Y-m-d'),
                'expiry_date' => $lot?->expiry_date?->format('Y-m-d'),
                'quantity_used' => $mat->quantity_used,
                'unit' => $mat->unit,
                'moisture_percentage' => $lot?->moisture_percentage,
                'qc_status' => $lot?->status,
                'coa_documents' => $lot?->documents ?? [],
            ];
        }

        $packagingDetailed = [];
        foreach ($batch->packagings as $pkg) {
            $pkgLot = $pkg->packagingMaterialLot;
            $packagingDetailed[] = [
                'lot_number' => $pkgLot?->lot_number,
                'material_name' => $pkgLot?->packagingMaterial?->name,
                'material_name_ar' => $pkgLot?->packagingMaterial?->name_ar,
                'supplier_name' => $pkgLot?->supplier?->name,
                'quantity_used' => $pkg->quantity_used,
                'unit' => $pkg->unit,
                'status' => $pkgLot?->status,
            ];
        }

        return [
            'batch' => $batch,
            'product' => $batch->product,
            'raw_materials' => $rawMaterialsDetailed,
            'packaging_materials' => $packagingDetailed,
            'quality_inspections' => $batch->inspections,
            'documents' => $batch->documents,
        ];
    }

    /**
     * Forward Traceability: From Raw Material Lot to all finished batches & markets
     */
    public function getForwardTraceability(string $lotNumber): array
    {
        $lot = RawMaterialLot::with([
            'rawMaterial',
            'supplier',
            'batchMaterials.batch.product',
            'inspections.results',
            'documents',
        ])
        ->where('lot_number', $lotNumber)
        ->orWhere('id', $lotNumber)
        ->first();

        if (!$lot) {
            throw new ModelNotFoundException("لم يتم العثور على لوت الخامة: {$lotNumber}");
        }

        $affectedBatches = [];
        $totalKgConsumed = 0;
        $totalCartons = 0;

        foreach ($lot->batchMaterials as $bm) {
            $b = $bm->batch;
            if ($b) {
                $totalKgConsumed += (float)$bm->quantity_used;
                $totalCartons += (int)$b->cartons_produced;

                $affectedBatches[] = [
                    'batch_id' => $b->batch_id,
                    'product_name' => $b->product?->name,
                    'product_name_ar' => $b->product?->name_ar,
                    'production_date' => $b->production_date?->format('Y-m-d'),
                    'quantity_used' => $bm->quantity_used,
                    'unit' => $bm->unit,
                    'cartons_produced' => $b->cartons_produced,
                    'batch_status' => $b->batch_status,
                    'qc_status' => $b->qc_status,
                    'final_release_status' => $b->final_release_status,
                ];
            }
        }

        return [
            'lot' => $lot,
            'material' => $lot->rawMaterial,
            'supplier' => $lot->supplier,
            'total_quantity' => $lot->quantity,
            'available_quantity' => $lot->available_quantity,
            'total_consumed' => $totalKgConsumed,
            'total_cartons_affected' => $totalCartons,
            'batches' => $affectedBatches,
            'inspections' => $lot->inspections,
        ];
    }

    /**
     * Recall Simulator: Fast Impact Assessment for contaminated supplier lots
     */
    public function simulateRecall(string $lotNumber): array
    {
        $trace = $this->getForwardTraceability($lotNumber);

        $severity = 'LOW';
        if ($trace['total_cartons_affected'] > 500) {
            $severity = 'CRITICAL';
        } elseif ($trace['total_cartons_affected'] > 100) {
            $severity = 'HIGH';
        } elseif ($trace['total_cartons_affected'] > 0) {
            $severity = 'MEDIUM';
        }

        return [
            'severity' => $severity,
            'lot_number' => $trace['lot']->lot_number,
            'material_name' => $trace['material']->name_ar ?? $trace['material']->name,
            'supplier_name' => $trace['supplier']->name_ar ?? $trace['supplier']->name,
            'receiving_date' => $trace['lot']->receiving_date?->format('Y-m-d'),
            'total_received_kg' => $trace['total_quantity'],
            'total_consumed_kg' => $trace['total_consumed'],
            'remaining_stock_kg' => $trace['available_quantity'],
            'total_batches_count' => count($trace['batches']),
            'total_cartons_in_market' => $trace['total_cartons_affected'],
            'affected_batches' => $trace['batches'],
            'recommended_action' => ($severity === 'CRITICAL' || $severity === 'HIGH')
                ? 'إيقاف فوري لكافة دفعات الإنتاج المرتبطة واستدعاء فوري للكراتين المطروحة في نقاط التوزيع وسحب رصيد المستودع المتبقي إلى الحجر الصحي (Quarantine).'
                : 'حجز الرصيد المتبقي وفحص العينات المرجعية والتنسيق مع إدارة الجودة.',
        ];
    }
}
