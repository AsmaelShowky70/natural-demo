<?php

namespace App\Services;

use App\Models\ProductionBatch;
use App\Models\ProductionBatchMaterial;
use App\Models\ProductionBatchPackaging;
use App\Models\Product;
use App\Models\QcNotification;
use Illuminate\Support\Facades\DB;

class BatchService
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Generate unique Batch Code
     */
    public function generateBatchId(Product $product, string $shift, string $date): string
    {
        $dateFormatted = date('ymd', strtotime($date));
        $prefix = strtoupper(substr($product->sku ?? $product->product_code, 0, 4));
        $countToday = ProductionBatch::whereDate('production_date', $date)->count() + 1;
        $seq = str_pad($countToday, 3, '0', STR_PAD_LEFT);

        return "{$prefix}-{$dateFormatted}-{$shift}-{$seq}";
    }

    /**
     * Create a new production batch with consumed lots and transactional safety
     */
    public function createBatch(array $data, array $rawLots = [], array $packagingLots = []): ProductionBatch
    {
        return DB::transaction(function () use ($data, $rawLots, $packagingLots) {
            $product = Product::findOrFail($data['product_id']);

            if (empty($data['batch_id'])) {
                $data['batch_id'] = $this->generateBatchId($product, $data['shift'] ?? 'A', $data['production_date'] ?? date('Y-m-d'));
            }

            $data['finished_goods_barcode'] = 'FG-' . $data['batch_id'];

            // Calculate yield and scrap
            $planned = (float)($data['planned_quantity'] ?? 0);
            $actual = (float)($data['actual_quantity'] ?? 0);
            $waste = (float)($data['waste_quantity'] ?? 0);

            if ($planned > 0 && $actual > 0) {
                $data['productivity_yield'] = round(($actual / $planned) * 100, 2);
            }
            if ($actual + $waste > 0 && $waste > 0) {
                $data['scrap_percentage'] = round(($waste / ($actual + $waste)) * 100, 2);
            }

            $batch = ProductionBatch::create($data);

            // Deduct raw material lots and associate
            foreach ($rawLots as $item) {
                if (!empty($item['lot_id']) && !empty($item['quantity'])) {
                    $qty = (float)$item['quantity'];
                    $this->inventoryService->consumeRawMaterialForBatch((int)$item['lot_id'], $qty);

                    ProductionBatchMaterial::create([
                        'production_batch_id' => $batch->id,
                        'raw_material_lot_id' => $item['lot_id'],
                        'quantity_used' => $qty,
                        'unit' => $item['unit'] ?? 'KG',
                        'notes' => $item['notes'] ?? null,
                    ]);
                }
            }

            // Deduct packaging lots and associate
            foreach ($packagingLots as $item) {
                if (!empty($item['lot_id']) && !empty($item['quantity'])) {
                    $qty = (float)$item['quantity'];
                    $this->inventoryService->consumePackagingForBatch((int)$item['lot_id'], $qty);

                    ProductionBatchPackaging::create([
                        'production_batch_id' => $batch->id,
                        'packaging_material_lot_id' => $item['lot_id'],
                        'quantity_used' => $qty,
                        'unit' => $item['unit'] ?? 'PCS',
                        'notes' => $item['notes'] ?? null,
                    ]);
                }
            }

            // Create notification for Quality Control
            QcNotification::create([
                'type' => 'finished_goods',
                'title' => "دفعة إنتاج جديدة جاهزة للفحص: {$batch->batch_id}",
                'title_ar' => "دفعة إنتاج جديدة جاهزة للفحص: {$batch->batch_id}",
                'message' => "تم الانتهاء من تشغيل الدفعة {$batch->batch_id} لمنتج {$product->name_ar} ويرجى إجراء فحص الجودة النهائي.",
                'message_ar' => "تم الانتهاء من تشغيل الدفعة {$batch->batch_id} لمنتج {$product->name_ar} ويرجى إجراء فحص الجودة النهائي.",
                'target_id' => (string)$batch->id,
                'batch_or_lot_number' => $batch->batch_id,
                'status' => 'pending',
            ]);

            AuditService::log('CREATE', 'ProductionBatch', (string)$batch->id, null, $batch->toArray());

            return $batch;
        });
    }
}
