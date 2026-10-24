<?php

namespace App\Services;

use App\Models\RawMaterialLot;
use App\Models\PackagingMaterialLot;
use App\Models\MaterialIssueOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryService
{
    /**
     * Issue raw material to production line with atomic stock deduction
     */
    public function issueRawMaterial(int $lotId, float $quantity, string $line, string $issuedBy, string $receivedBy, ?string $notes = null): MaterialIssueOrder
    {
        return DB::transaction(function () use ($lotId, $quantity, $line, $issuedBy, $receivedBy, $notes) {
            $lot = RawMaterialLot::lockForUpdate()->findOrFail($lotId);

            if ($lot->available_quantity < $quantity) {
                throw ValidationException::withMessages([
                    'quantity' => "الكمية المطلوبة ({$quantity} {$lot->unit}) أكبر من الرصيد المتاح ({$lot->available_quantity} {$lot->unit}) للتشغيلة {$lot->lot_number}",
                ]);
            }

            if ($lot->status !== 'approved') {
                throw ValidationException::withMessages([
                    'lot_id' => "لا يمكن صرف الخامة {$lot->lot_number} لأن حالتها ({$lot->status}) وليست معتمدة من الجودة.",
                ]);
            }

            $oldAvailable = $lot->available_quantity;
            $lot->available_quantity -= $quantity;
            $lot->in_production_quantity += $quantity;
            if ($lot->available_quantity <= 0) {
                $lot->status = 'consumed';
            }
            $lot->save();

            $issueCount = MaterialIssueOrder::count() + 1;
            $issueNumber = 'ISS-' . date('Ymd') . '-' . str_pad($issueCount, 3, '0', STR_PAD_LEFT);

            $order = MaterialIssueOrder::create([
                'issue_number' => $issueNumber,
                'issue_date' => now()->toDateString(),
                'raw_material_lot_id' => $lot->id,
                'quantity' => $quantity,
                'unit' => $lot->unit,
                'target_line' => $line,
                'issued_by' => $issuedBy,
                'received_by' => $receivedBy,
                'status' => 'completed',
                'notes' => $notes,
                'created_by' => auth()->id(),
            ]);

            AuditService::log('ISSUE', 'RawMaterialLot', (string)$lot->id, [
                'available_quantity' => $oldAvailable,
            ], [
                'available_quantity' => $lot->available_quantity,
                'issue_number' => $issueNumber,
                'quantity_issued' => $quantity,
            ]);

            return $order;
        });
    }

    /**
     * Consume raw material lot directly for a production batch
     */
    public function consumeRawMaterialForBatch(int $lotId, float $quantity): void
    {
        $lot = RawMaterialLot::lockForUpdate()->findOrFail($lotId);

        if ($lot->available_quantity < $quantity) {
            throw ValidationException::withMessages([
                'quantity' => "الرصيد المتاح من لوت المادة الخام {$lot->lot_number} غير كافٍ.",
            ]);
        }

        $lot->available_quantity -= $quantity;
        if ($lot->available_quantity <= 0) {
            $lot->status = 'consumed';
        }
        $lot->save();
    }

    /**
     * Consume packaging material lot for a production batch
     */
    public function consumePackagingForBatch(int $lotId, float $quantity): void
    {
        $lot = PackagingMaterialLot::lockForUpdate()->findOrFail($lotId);

        if ($lot->available_quantity < $quantity) {
            throw ValidationException::withMessages([
                'quantity' => "الرصيد المتاح من لوت مادة التعبئة {$lot->lot_number} غير كافٍ.",
            ]);
        }

        $lot->available_quantity -= $quantity;
        $lot->save();
    }
}
