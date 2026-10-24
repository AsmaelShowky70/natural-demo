<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReceivingRecord;
use App\Models\ReceivingItem;
use App\Models\Supplier;
use App\Models\RawMaterial;
use App\Models\RawMaterialLot;
use App\Models\PackagingMaterial;
use App\Models\PackagingMaterialLot;
use App\Models\QcNotification;
use App\Services\AuditService;
use Illuminate\Support\Facades\DB;

class ReceivingController extends Controller
{
    public function index(Request $request)
    {
        $query = ReceivingRecord::with(['supplier', 'items']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('receiving_number', 'like', "%{$s}%")
                  ->orWhere('vehicle_number', 'like', "%{$s}%")
                  ->orWhere('driver_name', 'like', "%{$s}%");
            });
        }

        $records = $query->latest()->paginate(10)->withQueryString();

        return view('receiving.index', compact('records'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->get();
        $rawMaterials = RawMaterial::where('is_active', true)->get();
        $packagingMaterials = PackagingMaterial::where('is_active', true)->get();

        return view('receiving.create', compact('suppliers', 'rawMaterials', 'packagingMaterials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'receiving_type' => 'required|in:raw_material,packaging_material',
            'receiving_date' => 'required|date',
            'vehicle_number' => 'nullable|string',
            'driver_name' => 'nullable|string',
            'received_by' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|numeric',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit' => 'required|string',
            'items.*.supplier_lot_number' => 'nullable|string',
            'items.*.production_date' => 'nullable|date',
            'items.*.expiry_date' => 'nullable|date',
        ]);

        $record = DB::transaction(function () use ($validated, $request) {
            $count = ReceivingRecord::count() + 1;
            $receivingNumber = 'GRN-' . str_pad($count, 5, '0', STR_PAD_LEFT);

            $grn = ReceivingRecord::create([
                'receiving_number' => $receivingNumber,
                'receiving_type' => $validated['receiving_type'],
                'supplier_id' => $validated['supplier_id'],
                'receiving_date' => $validated['receiving_date'],
                'vehicle_number' => $validated['vehicle_number'] ?? null,
                'driver_name' => $validated['driver_name'] ?? null,
                'received_by' => $validated['received_by'] ?? auth()->user()->name,
                'status' => 'completed',
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            foreach ($validated['items'] as $item) {
                $qty = (float)$item['quantity'];
                $dateFormatted = date('ymd', strtotime($validated['receiving_date']));

                if ($validated['receiving_type'] === 'raw_material') {
                    $material = RawMaterial::findOrFail($item['material_id']);
                    $matPrefix = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $material->code), 0, 4));
                    $lotCount = RawMaterialLot::whereDate('receiving_date', $validated['receiving_date'])->count() + 1;
                    $lotNumber = "{$matPrefix}-{$dateFormatted}-" . str_pad($lotCount, 3, '0', STR_PAD_LEFT);
                    $barcode = "BAR-RM-{$lotNumber}";

                    $lot = RawMaterialLot::create([
                        'lot_number' => $lotNumber,
                        'barcode' => $barcode,
                        'raw_material_id' => $material->id,
                        'supplier_id' => $validated['supplier_id'],
                        'supplier_lot_number' => $item['supplier_lot_number'] ?? null,
                        'receiving_date' => $validated['receiving_date'],
                        'production_date' => $item['production_date'] ?? null,
                        'expiry_date' => $item['expiry_date'] ?? null,
                        'quantity' => $qty,
                        'available_quantity' => $qty,
                        'unit' => $item['unit'],
                        'status' => 'pending', // Pending QC
                        'created_by' => auth()->id(),
                    ]);

                    ReceivingItem::create([
                        'receiving_record_id' => $grn->id,
                        'raw_material_id' => $material->id,
                        'lot_number' => $lotNumber,
                        'supplier_lot_number' => $item['supplier_lot_number'] ?? null,
                        'quantity' => $qty,
                        'unit' => $item['unit'],
                        'production_date' => $item['production_date'] ?? null,
                        'expiry_date' => $item['expiry_date'] ?? null,
                        'qc_status' => 'pending',
                        'accepted_quantity' => 0,
                    ]);

                    QcNotification::create([
                        'type' => 'incoming_raw',
                        'title' => "توريد خام جديد يتطلب فحص الجودة: {$lotNumber}",
                        'title_ar' => "توريد خام جديد يتطلب فحص الجودة: {$lotNumber}",
                        'message' => "تم استلام الشحنة {$lotNumber} من الخامة {$material->name_ar} بموجب سند {$receivingNumber} ويرجى إجراء فحص الرطوبة والمطابقة.",
                        'message_ar' => "تم استلام الشحنة {$lotNumber} من الخامة {$material->name_ar} بموجب سند {$receivingNumber} ويرجى إجراء فحص الرطوبة والمطابقة.",
                        'target_id' => (string)$lot->id,
                        'batch_or_lot_number' => $lotNumber,
                        'status' => 'pending',
                    ]);
                } else {
                    $pkgMaterial = PackagingMaterial::findOrFail($item['material_id']);
                    $pkgPrefix = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $pkgMaterial->code), 0, 4));
                    $lotCount = PackagingMaterialLot::whereDate('receiving_date', $validated['receiving_date'])->count() + 1;
                    $lotNumber = "{$pkgPrefix}-{$dateFormatted}-" . str_pad($lotCount, 3, '0', STR_PAD_LEFT);
                    $barcode = "BAR-PM-{$lotNumber}";

                    $pkgLot = PackagingMaterialLot::create([
                        'lot_number' => $lotNumber,
                        'barcode' => $barcode,
                        'packaging_material_id' => $pkgMaterial->id,
                        'supplier_id' => $validated['supplier_id'],
                        'supplier_lot_number' => $item['supplier_lot_number'] ?? null,
                        'receiving_date' => $validated['receiving_date'],
                        'quantity' => $qty,
                        'available_quantity' => $qty,
                        'unit' => $item['unit'],
                        'status' => 'pending',
                        'created_by' => auth()->id(),
                    ]);

                    ReceivingItem::create([
                        'receiving_record_id' => $grn->id,
                        'packaging_material_id' => $pkgMaterial->id,
                        'lot_number' => $lotNumber,
                        'supplier_lot_number' => $item['supplier_lot_number'] ?? null,
                        'quantity' => $qty,
                        'unit' => $item['unit'],
                        'qc_status' => 'pending',
                        'accepted_quantity' => 0,
                    ]);
                }
            }

            AuditService::log('CREATE', 'ReceivingRecord', (string)$grn->id, null, $grn->toArray());

            return $grn;
        });

        return redirect()->route('receiving.show', $record)
            ->with('success', 'تم حفظ سند الاستلام وتوليد أرقام اللوتات وملصقات الباركود وإرسال إشعار فوري لمختبر الجودة.');
    }

    public function show(ReceivingRecord $receiving)
    {
        $receiving->load(['supplier', 'items.rawMaterial', 'items.packagingMaterial', 'documents']);

        return view('receiving.show', compact('receiving'));
    }
}
