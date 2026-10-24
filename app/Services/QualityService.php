<?php

namespace App\Services;

use App\Models\QualityInspection;
use App\Models\QualityInspectionResult;
use App\Models\ProductionBatch;
use App\Models\RawMaterialLot;
use App\Models\PackagingMaterialLot;
use App\Models\QcNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class QualityService
{
    /**
     * Create inspection with individual test parameters
     */
    public function recordInspection(array $data, array $parameters = []): QualityInspection
    {
        return DB::transaction(function () use ($data, $parameters) {
            $count = QualityInspection::count() + 1;
            $data['inspection_number'] = 'QC-' . date('Y') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            $inspection = QualityInspection::create($data);

            foreach ($parameters as $param) {
                if (!empty($param['parameter'])) {
                    QualityInspectionResult::create([
                        'quality_inspection_id' => $inspection->id,
                        'parameter' => $param['parameter'],
                        'specification' => $param['specification'] ?? null,
                        'actual_result' => $param['actual_result'] ?? null,
                        'unit' => $param['unit'] ?? null,
                        'result' => $param['result'] ?? 'pass',
                        'notes' => $param['notes'] ?? null,
                    ]);
                }
            }

            // If this inspection is for a raw material lot
            if ($inspection->raw_material_lot_id) {
                $lot = RawMaterialLot::find($inspection->raw_material_lot_id);
                if ($lot) {
                    if ($inspection->overall_result === 'pass') {
                        $lot->status = 'approved';
                    } elseif ($inspection->overall_result === 'fail') {
                        $lot->status = 'rejected';
                    } else {
                        $lot->status = 'quarantine';
                    }
                    if ($inspection->moisture_percentage) {
                        $lot->moisture_percentage = $inspection->moisture_percentage;
                    }
                    $lot->save();
                }
            }

            // If for packaging material lot
            if ($inspection->packaging_material_lot_id) {
                $pkgLot = PackagingMaterialLot::find($inspection->packaging_material_lot_id);
                if ($pkgLot) {
                    $pkgLot->status = ($inspection->overall_result === 'pass') ? 'approved' : 'rejected';
                    $pkgLot->save();
                }
            }

            // If for production batch
            if ($inspection->production_batch_id) {
                $batch = ProductionBatch::find($inspection->production_batch_id);
                if ($batch) {
                    if ($inspection->overall_result === 'pass') {
                        $batch->qc_status = 'passed';
                        $batch->final_release_status = 'released';
                        $batch->batch_status = 'released';
                        $batch->transferred_to_warehouse = true;
                    } elseif ($inspection->overall_result === 'fail') {
                        $batch->qc_status = 'failed';
                        $batch->final_release_status = 'rejected';
                        $batch->batch_status = 'rejected';
                    } else {
                        $batch->qc_status = 'on_hold';
                        $batch->final_release_status = 'on_hold';
                        $batch->batch_status = 'on_hold';
                    }
                    $batch->save();
                }
            }

            // Dismiss pending QC notification if exists
            QcNotification::where('target_id', (string)($data['production_batch_id'] ?? $data['raw_material_lot_id'] ?? ''))
                ->where('status', 'pending')
                ->update(['status' => 'inspected']);

            AuditService::log('CREATE', 'QualityInspection', (string)$inspection->id, null, $inspection->toArray());

            return $inspection;
        });
    }

    /**
     * Release a batch officially
     */
    public function releaseBatch(ProductionBatch $batch): void
    {
        if ($batch->qc_status !== 'passed') {
            throw ValidationException::withMessages([
                'qc_status' => "لا يمكن الإفراج عن الدفعة {$batch->batch_id} لأن نتائج فحص الجودة لم تجتز المعايير بنجاح (الحالة: {$batch->qc_status}).",
            ]);
        }

        $batch->batch_status = 'released';
        $batch->final_release_status = 'released';
        $batch->transferred_to_warehouse = true;
        $batch->save();

        AuditService::log('RELEASE', 'ProductionBatch', (string)$batch->id, ['status' => 'pending'], ['status' => 'released']);
    }
}
