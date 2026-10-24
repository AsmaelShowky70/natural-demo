<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PackagingMaterial;
use App\Models\PackagingMaterialLot;
use App\Models\Supplier;
use App\Services\AuditService;

class PackagingMaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = PackagingMaterial::with(['supplier', 'lots']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('name_ar', 'like', "%{$s}%")
                  ->orWhere('code', 'like', "%{$s}%");
            });
        }

        $materials = $query->latest()->paginate(10)->withQueryString();
        $lots = PackagingMaterialLot::with(['packagingMaterial', 'supplier'])->latest()->take(10)->get();

        return view('packaging.index', compact('materials', 'lots'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->get();
        return view('packaging.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'code' => 'required|string|unique:packaging_materials,code',
            'material_type' => 'nullable|string',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'unit' => 'required|string',
            'minimum_stock' => 'nullable|numeric|min:0',
            'specification' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        $material = PackagingMaterial::create($validated);

        AuditService::log('CREATE', 'PackagingMaterial', (string)$material->id, null, $material->toArray());

        return redirect()->route('packaging.index')
            ->with('success', 'تم إضافة مادة التعبئة والتغليف بنجاح.');
    }
}
