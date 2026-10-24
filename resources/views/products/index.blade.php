@extends('layouts.app')

@section('title', 'دليل المنتجات والوصفات (BOM)')
@section('page_title', 'دليل المنتجات وقوائم المواد ووصفات التصنيع (Products & BOM)')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Top Action Card -->
    <div class="premium-card p-4">
        <div class="row g-3 align-items-center justify-content-between">
            <div class="col-lg-7">
                <form action="{{ route('products.index') }}" method="GET" class="d-flex flex-wrap gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" style="max-width: 250px;" placeholder="بحث باسم المنتج أو الكود أو SKU..." value="{{ request('search') }}">
                    <select name="category_id" class="form-select form-select-sm" style="max-width: 180px;">
                        <option value="">كافة التصنيفات</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name_ar ?? $cat->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-search"></i> بحث</button>
                    @if(request()->hasAny(['search', 'category_id']))
                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">إلغاء</a>
                    @endif
                </form>
            </div>
            <div class="col-lg-5 text-lg-end">
                <a href="{{ route('products.create') }}" class="btn btn-sm btn-ns-primary">
                    <i class="bi bi-plus-circle me-1"></i> إضافة منتج جديد ووصفة تشغيل (BOM)
                </a>
            </div>
        </div>
    </div>

    <!-- Products Grid / Table Card -->
    <div class="premium-card">
        <div class="card-header-clean bg-light bg-opacity-50">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-box-seam text-success me-2"></i>كتالوج المنتجات الغذائية وقوائم المواد المعتمدة</h6>
            <span class="badge bg-light text-dark border">إجمالي {{ $products->total() }} منتج</span>
        </div>
        <div class="table-responsive">
            <table class="table table-premium mb-0">
                <thead>
                    <tr>
                        <th>كود المنتج (Code)</th>
                        <th>رمز التخزين (SKU)</th>
                        <th>اسم المنتج (عربي / English)</th>
                        <th>التصنيف</th>
                        <th>الحجم والعبوة</th>
                        <th>وصفة التصنيع المعتمدة (BOM)</th>
                        <th>المكونات الرئيسية ونسبها</th>
                        <th>الحالة</th>
                        <th class="text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                    <tr>
                        <td class="fw-bold en-font text-dark">{{ $p->product_code }}</td>
                        <td><span class="badge bg-dark rounded-pill en-font px-2">{{ $p->sku }}</span></td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $p->name_ar }}</div>
                            <small class="text-muted">{{ $p->name }}</small>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $p->category?->name_ar ?? $p->category?->name }}</span></td>
                        <td>{{ $p->size ?? '-' }} ({{ $p->unit }})</td>
                        <td>
                            @if($p->activeBom)
                                <div class="small fw-semibold text-success">
                                    <i class="bi bi-check-circle me-1"></i> {{ $p->activeBom->name_ar ?? $p->activeBom->name }}
                                </div>
                                <small class="text-muted">إصدار V{{ $p->activeBom->version }}</small>
                            @else
                                <span class="badge bg-warning-subtle text-warning">لا توجد وصفة</span>
                            @endif
                        </td>
                        <td>
                            @if($p->activeBom && $p->activeBom->items)
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($p->activeBom->items as $bi)
                                    <span class="badge bg-light text-dark border py-1" style="font-size: 0.72rem;">
                                        {{ $bi->rawMaterial?->name_ar }}: {{ $bi->percentage ? $bi->percentage . '%' : $bi->quantity . 'KG' }}
                                    </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            @if($p->is_active)
                                <span class="badge bg-success-subtle text-success">نشط</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">معطل</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('products.show', $p) }}" class="btn btn-light border" title="معاينة تفاصيل المنتج والوصفة">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('products.edit', $p) }}" class="btn btn-light border" title="تعديل">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">لا توجد منتجات مسجلة تطابق شروط البحث.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
        <div class="p-3 border-top">
            {{ $products->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
