<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductionBatch;
use App\Models\Product;
use App\Models\RawMaterialLot;
use App\Models\PackagingMaterialLot;
use App\Services\BatchService;
use App\Services\AuditService;
use App\Services\BarcodeService;

class ProductionBatchController extends Controller
{
    protected BatchService $batchService;

    public function __construct(BatchService $batchService)
    {
        $this->batchService = $batchService;
    }

    public function index(Request $request)
    {
        $query = ProductionBatch::with(['product.category', 'inspections']);

        if ($request->filled('status')) {
            $query->where('batch_status', $request->status);
        }

        if ($request->filled('shift')) {
            $query->where('shift', $request->shift);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('batch_id', 'like', "%{$s}%")
                  ->orWhere('finished_goods_barcode', 'like', "%{$s}%")
                  ->orWhere('production_supervisor', 'like', "%{$s}%");
            });
        }

        $batches = $query->latest('production_date')->latest('id')->paginate(10)->withQueryString();

        return view('production.index', compact('batches'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->with('activeBom.items.rawMaterial')->get();
        $rawLots = RawMaterialLot::with(['rawMaterial', 'supplier'])
            ->where('status', 'approved')
            ->where('available_quantity', '>', 0)
            ->get();
        $packagingLots = PackagingMaterialLot::with(['packagingMaterial', 'supplier'])
            ->where('status', 'approved')
            ->where('available_quantity', '>', 0)
            ->get();

        return view('production.create', compact('products', 'rawLots', 'packagingLots'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_bom_id' => 'nullable|exists:product_boms,id',
            'production_date' => 'required|date',
            'shift' => 'required|in:A,B,C,Night',
            'production_line' => 'required|string',
            'production_supervisor' => 'required|string',
            'planned_quantity' => 'required|numeric|min:1',
            'actual_quantity' => 'nullable|numeric|min:0',
            'cartons_produced' => 'nullable|integer|min:0',
            'units_per_carton' => 'nullable|integer|min:1',
            'waste_quantity' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'raw_lots' => 'nullable|array',
            'packaging_lots' => 'nullable|array',
        ]);

        $validated['batch_status'] = ($request->filled('actual_quantity') && $request->actual_quantity > 0)
            ? 'qc_pending'
            : 'in_production';
        $validated['created_by'] = auth()->id();

        $batch = $this->batchService->createBatch(
            $validated,
            $request->input('raw_lots', []),
            $request->input('packaging_lots', [])
        );

        return redirect()->route('production.show', $batch)
            ->with('success', "تم إنشاء أمر تشغيل الدفعة {$batch->batch_id} وخصم الخامات ومواد التغليف وإرسال إشعار فحص للجودة.");
    }

    public function show(ProductionBatch $batch)
    {
        $batch->load([
            'product.category',
            'bom.items.rawMaterial',
            'materials.rawMaterialLot.rawMaterial',
            'materials.rawMaterialLot.supplier',
            'packagings.packagingMaterialLot.packagingMaterial',
            'inspections.results',
            'documents',
        ]);

        $barcodeSvg = BarcodeService::generateBarcodeSvg($batch->batch_id, 55);
        $publicTraceUrl = route('trace.public', $batch->batch_id);
        $qrSvg = BarcodeService::generateQrSvg($publicTraceUrl, 160);

        return view('production.show', compact('batch', 'barcodeSvg', 'qrSvg', 'publicTraceUrl'));
    }

    public function updateActuals(Request $request, ProductionBatch $batch)
    {
        $validated = $request->validate([
            'actual_quantity' => 'required|numeric|min:0',
            'cartons_produced' => 'required|integer|min:0',
            'waste_quantity' => 'nullable|numeric|min:0',
            'batch_status' => 'required|string',
        ]);

        $actual = (float)$validated['actual_quantity'];
        $waste = (float)($validated['waste_quantity'] ?? 0);
        $planned = (float)$batch->planned_quantity;

        $yield = ($planned > 0) ? round(($actual / $planned) * 100, 2) : 100;
        $scrap = ($actual + $waste > 0) ? round(($waste / ($actual + $waste)) * 100, 2) : 0;

        $batch->update([
            'actual_quantity' => $actual,
            'cartons_produced' => $validated['cartons_produced'],
            'waste_quantity' => $waste,
            'productivity_yield' => $yield,
            'scrap_percentage' => $scrap,
            'batch_status' => $validated['batch_status'],
            'updated_by' => auth()->id(),
        ]);

        AuditService::log('UPDATE_ACTUALS', 'ProductionBatch', (string)$batch->id, null, $batch->toArray());

        return redirect()->route('production.show', $batch)
            ->with('success', 'تم تحديث مخرجات الإنتاج الفعلي ونسبة الإنتاجية والهالك بنجاح.');
    }

    public function printTraveler(ProductionBatch $batch)
    {
        $batch->load([
            'product.category',
            'materials.rawMaterialLot.rawMaterial',
            'materials.rawMaterialLot.supplier',
            'packagings.packagingMaterialLot.packagingMaterial',
            'inspections.results',
        ]);

        $barcodeSvg = BarcodeService::generateBarcodeSvg($batch->batch_id, 60);
        $publicTraceUrl = route('trace.public', $batch->batch_id);
        $qrSvg = BarcodeService::generateQrSvg($publicTraceUrl, 130);

        return view('production.traveler', compact('batch', 'barcodeSvg', 'qrSvg', 'publicTraceUrl'));
    }
}
