<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RawMaterial;
use App\Models\RawMaterialLot;
use App\Models\Supplier;
use App\Services\AuditService;

class RawMaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = RawMaterial::with(['supplier', 'lots']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('name_ar', 'like', "%{$s}%")
                  ->orWhere('code', 'like', "%{$s}%");
            });
        }

        $materials = $query->latest()->paginate(10)->withQueryString();
        $lots = RawMaterialLot::with(['rawMaterial', 'supplier'])->latest()->take(10)->get();

        return view('raw_materials.index', compact('materials', 'lots'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->get();
        return view('raw_materials.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'code' => 'required|string|unique:raw_materials,code',
            'material_type' => 'nullable|string',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'unit' => 'required|string',
            'minimum_stock' => 'nullable|numeric|min:0',
            'specification' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        $material = RawMaterial::create($validated);

        AuditService::log('CREATE', 'RawMaterial', (string)$material->id, null, $material->toArray());

        return redirect()->route('raw-materials.index')
            ->with('success', 'تم إضافة المادة الخام بنجاح.');
    }

    public function show(RawMaterial $rawMaterial)
    {
        $rawMaterial->load(['supplier', 'lots.documents', 'lots.batchMaterials.batch']);

        return view('raw_materials.show', compact('rawMaterial'));
    }
}
