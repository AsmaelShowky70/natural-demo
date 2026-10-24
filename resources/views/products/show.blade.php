@extends('layouts.app')

@section('title', 'منتج ' . $product->name_ar)
@section('page_title', 'بيانات المنتج والوصفات: ' . $product->name_ar)

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Hero Card -->
    <div class="premium-card p-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">
                        {{ $product->category?->name_ar }}
                    </span>
                    @if($product->is_active)
                        <span class="badge bg-success">نشط بالإنتاج</span>
                    @else
                        <span class="badge bg-secondary">متوقف</span>
                    @endif
                </div>
                <h3 class="fw-bold mb-1 text-dark">{{ $product->name_ar }} <span class="text-muted fs-5 fw-normal">({{ $product->name }})</span></h3>
                <p class="text-secondary small mb-3">{{ $product->description_ar ?? $product->description }}</p>

                <div class="d-flex flex-wrap gap-4 text-muted small">
                    <span>كود المنتج: <strong class="en-font text-dark">{{ $product->product_code }}</strong></span>
                    <span>الرمز المخزني SKU: <strong class="en-font text-dark">{{ $product->sku }}</strong></span>
                    <span>الحجم: <strong class="text-dark">{{ $product->size }} ({{ $product->unit }})</strong></span>
                    <span>النوع: <strong class="text-dark">{{ $product->product_type }}</strong></span>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('production.create') }}" class="btn btn-ns-primary me-2">
                    <i class="bi bi-gear-wide me-1"></i> أمر تشغيل للدفعة
                </a>
                <a href="{{ route('products.edit', $product) }}" class="btn btn-light border">
                    <i class="bi bi-pencil me-1"></i> تعديل
                </a>
            </div>
        </div>
    </div>

    <!-- Active BOM Formula Card -->
    <div class="premium-card">
        <div class="card-header-clean bg-light">
            <div>
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-card-checklist text-success me-2"></i>وصفة التصنيع وقائمة المواد المعتمدة (Bill of Materials)</h6>
                <small class="text-muted">نسب ومقادير الخامات الداخلة في تركيبة هذا المنتج</small>
            </div>
            @if($product->activeBom)
                <span class="badge bg-dark rounded-pill en-font">Version {{ $product->activeBom->version }}</span>
            @endif
        </div>
        <div class="table-responsive">
            <table class="table table-premium mb-0">
                <thead>
                    <tr>
                        <th>المادة الخام</th>
                        <th>الكود</th>
                        <th>الكمية المعيارية للخلطة</th>
                        <th>النسبة المئوية (%)</th>
                        <th>الوحدة</th>
                    </tr>
                </thead>
                <tbody>
                    @if($product->activeBom && $product->activeBom->items)
                        @foreach($product->activeBom->items as $item)
                        <tr>
                            <td class="fw-bold text-dark">{{ $item->rawMaterial?->name_ar }}</td>
                            <td class="en-font">{{ $item->rawMaterial?->code }}</td>
                            <td class="en-font fw-bold">{{ $item->quantity }}</td>
                            <td>
                                @if($item->percentage)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 6px; width: 100px;">
                                            <div class="progress-bar bg-success" style="width: {{ $item->percentage }}%"></div>
                                        </div>
                                        <span class="en-font fw-bold text-success">{{ $item->percentage }}%</span>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $item->unit }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr><td colspan="5" class="text-center py-4 text-muted">لم يتم إعداد وصفة مواد بعد.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Batches of this Product -->
    <div class="premium-card">
        <div class="card-header-clean bg-light">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-clock-history text-primary me-2"></i>آخر تشغيلات الإنتاج المصنعة من هذا المنتج</h6>
            <span class="badge bg-light text-dark border">{{ count($product->batches) }} دفعات</span>
        </div>
        <div class="table-responsive">
            <table class="table table-premium mb-0">
                <thead>
                    <tr>
                        <th>كود الدفعة</th>
                        <th>تاريخ الإنتاج</th>
                        <th>الوردية</th>
                        <th>الكمية الفعلية</th>
                        <th>الكراتين</th>
                        <th>حالة الجودة</th>
                        <th>الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($product->batches as $b)
                    <tr>
                        <td class="fw-bold en-font text-dark">{{ $b->batch_id }}</td>
                        <td>{{ $b->production_date?->format('Y-m-d') }}</td>
                        <td>وردية {{ $b->shift }}</td>
                        <td class="fw-bold en-font">{{ number_format($b->actual_quantity) }} KG</td>
                        <td class="fw-bold en-font">{{ $b->cartons_produced }} كرتونة</td>
                        <td>{!! $b->status_badge !!}</td>
                        <td>
                            <a href="{{ route('production.show', $b) }}" class="btn btn-sm btn-light border"><i class="bi bi-eye"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">لم يتم تشغيل أي دفعة لهذا المنتج بعد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
