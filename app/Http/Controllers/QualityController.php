<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QualityInspection;
use App\Models\ProductionBatch;
use App\Models\RawMaterialLot;
use App\Models\PackagingMaterialLot;
use App\Models\QcNotification;
use App\Services\QualityService;

class QualityController extends Controller
{
    protected QualityService $qualityService;

    public function __construct(QualityService $qualityService)
    {
        $this->qualityService = $qualityService;
    }

    public function index(Request $request)
    {
        $query = QualityInspection::with(['batch.product', 'rawMaterialLot.rawMaterial', 'results']);

        if ($request->filled('result')) {
            $query->where('overall_result', $request->result);
        }

        if ($request->filled('target')) {
            $query->where('inspection_target', $request->target);
        }

        $inspections = $query->latest('inspection_date')->paginate(10)->withQueryString();
        $pendingNotifications = QcNotification::where('status', 'pending')->latest()->get();

        return view('quality.index', compact('inspections', 'pendingNotifications'));
    }

    public function create(Request $request)
    {
        $target = $request->query('target', 'finished_goods');
        $targetId = $request->query('target_id');

        $batches = ProductionBatch::whereIn('batch_status', ['in_production', 'qc_pending', 'completed', 'on_hold'])->get();
        $rawLots = RawMaterialLot::whereIn('status', ['pending', 'quarantine'])->with('rawMaterial')->get();
        $pkgLots = PackagingMaterialLot::where('status', 'pending')->with('packagingMaterial')->get();

        return view('quality.create', compact('target', 'targetId', 'batches', 'rawLots', 'pkgLots'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'inspection_target' => 'required|in:finished_goods,raw_material,packaging',
            'production_batch_id' => 'nullable|exists:production_batches,id',
            'raw_material_lot_id' => 'nullable|exists:raw_material_lots,id',
            'packaging_material_lot_id' => 'nullable|exists:packaging_material_lots,id',
            'inspection_date' => 'required|date',
            'inspector' => 'required|string',
            'inspection_type' => 'required|string',
            'overall_result' => 'required|in:pass,fail,hold',
            'moisture_percentage' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'parameters' => 'nullable|array',
        ]);

        $validated['approval_status'] = ($validated['overall_result'] === 'pass') ? 'approved' : (($validated['overall_result'] === 'fail') ? 'rejected' : 'on_hold');
        $validated['created_by'] = auth()->id();

        $inspection = $this->qualityService->recordInspection($validated, $request->input('parameters', []));

        return redirect()->route('quality.show', $inspection)
            ->with('success', "تم تسجيل نتائج الفحص المخبري رقم {$inspection->inspection_number} وتحديث حالة الاعتماد رسمياً.");
    }

    public function show(QualityInspection $quality)
    {
        $quality->load(['batch.product', 'rawMaterialLot.rawMaterial', 'packagingMaterialLot.packagingMaterial', 'results']);

        return view('quality.show', ['inspection' => $quality]);
    }

    public function releaseBatch(ProductionBatch $batch)
    {
        $this->qualityService->releaseBatch($batch);

        return redirect()->back()
            ->with('success', "تم اعتماد الإفراج النهائي عن الدفعة {$batch->batch_id} وترحيل رصيدها إلى مخزن المنتج النهائي المتاح للبيع والشحن.");
    }
}
