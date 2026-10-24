<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\RawMaterial;
use App\Models\ProductBom;
use App\Models\ProductBomItem;
use App\Services\AuditService;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'activeBom.items']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('name_ar', 'like', "%{$s}%")
                  ->orWhere('sku', 'like', "%{$s}%")
                  ->orWhere('product_code', 'like', "%{$s}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('product_category_id', $request->category_id);
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = ProductCategory::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = ProductCategory::where('is_active', true)->get();
        $rawMaterials = RawMaterial::where('is_active', true)->get();
        return view('products.create', compact('categories', 'rawMaterials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'product_code' => 'required|string|unique:products,product_code',
            'product_category_id' => 'required|exists:product_categories,id',
            'product_type' => 'nullable|string',
            'size' => 'nullable|string',
            'unit' => 'required|string',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        $product = Product::create($validated);

        // If initial BOM items provided
        if ($request->has('bom_items')) {
            $bom = ProductBom::create([
                'product_id' => $product->id,
                'version' => 1,
                'name' => "Standard Recipe - {$product->name}",
                'name_ar' => "وصفة التشغيل الأساسية - {$product->name_ar}",
                'effective_date' => now()->toDateString(),
                'is_active' => true,
                'created_by' => auth()->id(),
            ]);

            foreach ($request->bom_items as $item) {
                if (!empty($item['raw_material_id']) && !empty($item['quantity'])) {
                    ProductBomItem::create([
                        'product_bom_id' => $bom->id,
                        'raw_material_id' => $item['raw_material_id'],
                        'quantity' => $item['quantity'],
                        'unit' => $item['unit'] ?? 'KG',
                        'percentage' => $item['percentage'] ?? null,
                    ]);
                }
            }
        }

        AuditService::log('CREATE', 'Product', (string)$product->id, null, $product->toArray());

        return redirect()->route('products.show', $product)
            ->with('success', 'تم إضافة المنتج والوصفة بنجاح إلى كتالوج الشركة.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'boms.items.rawMaterial', 'batches' => function ($q) {
            $q->latest()->take(10);
        }]);

        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::where('is_active', true)->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'sku' => "required|string|unique:products,sku,{$product->id}",
            'product_code' => "required|string|unique:products,product_code,{$product->id}",
            'product_category_id' => 'required|exists:product_categories,id',
            'product_type' => 'nullable|string',
            'size' => 'nullable|string',
            'unit' => 'required|string',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $old = $product->toArray();
        $product->update($validated);

        AuditService::log('UPDATE', 'Product', (string)$product->id, $old, $product->toArray());

        return redirect()->route('products.show', $product)
            ->with('success', 'تم تحديث بيانات المنتج بنجاح.');
    }
}
