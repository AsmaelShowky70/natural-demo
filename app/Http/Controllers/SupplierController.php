<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Services\AuditService;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::withCount(['rawMaterials', 'rawMaterialLots']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('name_ar', 'like', "%{$s}%")
                  ->orWhere('code', 'like', "%{$s}%")
                  ->orWhere('contact_person', 'like', "%{$s}%");
            });
        }

        $suppliers = $query->latest()->paginate(10)->withQueryString();

        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'code' => 'required|string|unique:suppliers,code',
            'contact_person' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'country' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        $supplier = Supplier::create($validated);

        AuditService::log('CREATE', 'Supplier', (string)$supplier->id, null, $supplier->toArray());

        return redirect()->route('suppliers.show', $supplier)
            ->with('success', 'تم تسجيل المورد بنجاح في سجلات الشركة.');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load(['rawMaterials', 'rawMaterialLots.rawMaterial', 'receivingRecords' => function ($q) {
            $q->latest()->take(5);
        }]);

        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'code' => "required|string|unique:suppliers,code,{$supplier->id}",
            'contact_person' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'country' => 'nullable|string',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $old = $supplier->toArray();
        $supplier->update($validated);

        AuditService::log('UPDATE', 'Supplier', (string)$supplier->id, $old, $supplier->toArray());

        return redirect()->route('suppliers.show', $supplier)
            ->with('success', 'تم تحديث بيانات المورد بنجاح.');
    }
}
