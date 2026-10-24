<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockAuditRecord;
use App\Models\StockAuditItem;
use App\Models\RawMaterialLot;
use App\Models\PackagingMaterialLot;
use App\Models\ProductionBatch;
use App\Services\AuditService;
use Illuminate\Support\Facades\DB;

class StockAuditController extends Controller
{
    public function index()
    {
        $records = StockAuditRecord::with('items')->latest('audit_date')->paginate(10);
        return view('stock_audit.index', compact('records'));
    }

    public function create(Request $request)
    {
        $type = $request->query('warehouse_type', 'raw_materials');

        if ($type === 'raw_materials') {
            $items = RawMaterialLot::with('rawMaterial')->where('available_quantity', '>', 0)->get()->map(function ($lot) {
                return [
                    'lot_number' => $lot->lot_number,
                    'item_code' => $lot->rawMaterial?->code,
                    'item_name' => $lot->rawMaterial?->name_ar ?? $lot->rawMaterial?->name,
                    'system_quantity' => $lot->available_quantity,
                    'unit' => $lot->unit,
                ];
            });
        } elseif ($type === 'packaging') {
            $items = PackagingMaterialLot::with('packagingMaterial')->where('available_quantity', '>', 0)->get()->map(function ($lot) {
                return [
                    'lot_number' => $lot->lot_number,
                    'item_code' => $lot->packagingMaterial?->code,
                    'item_name' => $lot->packagingMaterial?->name_ar ?? $lot->packagingMaterial?->name,
                    'system_quantity' => $lot->available_quantity,
                    'unit' => $lot->unit,
                ];
            });
        } else {
            $items = ProductionBatch::with('product')->where('cartons_produced', '>', 0)->get()->map(function ($batch) {
                return [
                    'lot_number' => $batch->batch_id,
                    'item_code' => $batch->product?->product_code,
                    'item_name' => $batch->product?->name_ar ?? $batch->product?->name,
                    'system_quantity' => $batch->cartons_produced,
                    'unit' => 'Carton',
                ];
            });
        }

        return view('stock_audit.create', compact('type', 'items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'audit_date' => 'required|date',
            'auditor_name' => 'required|string',
            'warehouse_type' => 'required|in:raw_materials,packaging,finished_goods',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.lot_number' => 'required|string',
            'items.*.item_code' => 'required|string',
            'items.*.item_name' => 'required|string',
            'items.*.system_quantity' => 'required|numeric',
            'items.*.physical_quantity' => 'required|numeric|min:0',
            'items.*.unit' => 'required|string',
        ]);

        $record = DB::transaction(function () use ($validated) {
            $count = StockAuditRecord::count() + 1;
            $auditNumber = 'AUD-' . date('Ymd') . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

            $audit = StockAuditRecord::create([
                'audit_number' => $auditNumber,
                'audit_date' => $validated['audit_date'],
                'auditor_name' => $validated['auditor_name'],
                'warehouse_type' => $validated['warehouse_type'],
                'status' => 'completed',
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            foreach ($validated['items'] as $item) {
                $sys = (float)$item['system_quantity'];
                $phy = (float)$item['physical_quantity'];
                $variance = round($phy - $sys, 3);

                StockAuditItem::create([
                    'stock_audit_record_id' => $audit->id,
                    'lot_number' => $item['lot_number'],
                    'item_code' => $item['item_code'],
                    'item_name' => $item['item_name'],
                    'system_quantity' => $sys,
                    'physical_quantity' => $phy,
                    'variance' => $variance,
                    'unit' => $item['unit'],
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            AuditService::log('CREATE', 'StockAuditRecord', (string)$audit->id, null, $audit->toArray());

            return $audit;
        });

        return redirect()->route('stock-audit.show', $record)
            ->with('success', "تم تسجيل نتائج الجرد الفعلي للمستودع برقم {$record->audit_number} واحتساب الفروقات بنجاح.");
    }

    public function show(StockAuditRecord $stockAudit)
    {
        $stockAudit->load('items');
        return view('stock_audit.show', ['record' => $stockAudit]);
    }
}
