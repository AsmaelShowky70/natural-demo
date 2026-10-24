@extends('layouts.app')

@section('title', 'المورد ' . ($supplier->name_ar ?? $supplier->name))
@section('page_title', 'ملف المورد المعتمد: ' . ($supplier->name_ar ?? $supplier->name))

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Hero Card -->
    <div class="premium-card p-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">
                        مورد معتمد
                    </span>
                    <span class="badge bg-light text-dark border">كود: {{ $supplier->code }}</span>
                </div>
                <h3 class="fw-bold mb-1 text-dark">{{ $supplier->name_ar ?? $supplier->name }} <span class="text-muted fs-5 fw-normal">({{ $supplier->name }})</span></h3>
                <div class="d-flex flex-wrap gap-4 text-muted small mt-2">
                    <span><i class="bi bi-geo-alt me-1 text-success"></i> {{ $supplier->address ?? 'المقر الرئيسي' }} ({{ $supplier->country }})</span>
                    <span><i class="bi bi-person me-1 text-success"></i> مسؤول الاتصال: <strong>{{ $supplier->contact_person ?? '-' }}</strong></span>
                    <span><i class="bi bi-telephone me-1 text-success"></i> <strong class="en-font">{{ $supplier->phone ?? '-' }}</strong></span>
                    <span><i class="bi bi-envelope me-1 text-success"></i> <strong class="en-font">{{ $supplier->email ?? '-' }}</strong></span>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('receiving.create') }}" class="btn btn-ns-primary me-2">
                    <i class="bi bi-truck me-1"></i> استلام توريد من هذا المورد
                </a>
                <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-light border">
                    <i class="bi bi-pencil me-1"></i> تعديل
                </a>
            </div>
        </div>
    </div>

    <!-- Supplied Materials & Recent Lots -->
    <div class="row g-4">
        <!-- Materials -->
        <div class="col-lg-5">
            <div class="premium-card h-100">
                <div class="card-header-clean bg-light">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-boxes text-success me-2"></i>المواد الخام المرتبطة بهذا المورد</h6>
                    <span class="badge bg-light text-dark border">{{ count($supplier->rawMaterials) }} مواد</span>
                </div>
                <div class="p-3">
                    <div class="d-flex flex-column gap-2">
                        @forelse($supplier->rawMaterials as $mat)
                        <div class="p-3 bg-light rounded-3 d-flex align-items-center justify-content-between">
                            <div>
                                <strong class="text-dark d-block">{{ $mat->name_ar }}</strong>
                                <small class="text-muted en-font">{{ $mat->code }} • {{ $mat->material_type }}</small>
                            </div>
                            <span class="badge bg-success-subtle text-success">{{ number_format($mat->totalAvailableStock()) }} {{ $mat->unit }} متاح</span>
                        </div>
                        @empty
                        <div class="text-center py-4 text-muted small">لم يتم ربط مواد خام بعد.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Lots Received from Supplier -->
        <div class="col-lg-7">
            <div class="premium-card h-100">
                <div class="card-header-clean bg-light">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-upc-scan text-primary me-2"></i>الشحنات واللوتات المستلمة من هذا المورد</h6>
                    <span class="badge bg-light text-dark border">{{ count($supplier->rawMaterialLots) }} شحنات</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-premium mb-0">
                        <thead>
                            <tr>
                                <th>رقم اللوت</th>
                                <th>الخامة</th>
                                <th>تاريخ الاستلام</th>
                                <th>الكمية</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($supplier->rawMaterialLots as $lot)
                            <tr>
                                <td class="fw-bold en-font text-dark">{{ $lot->lot_number }}</td>
                                <td>{{ $lot->rawMaterial?->name_ar }}</td>
                                <td>{{ $lot->receiving_date?->format('Y-m-d') }}</td>
                                <td class="fw-bold en-font">{{ number_format($lot->quantity) }} {{ $lot->unit }}</td>
                                <td>{!! $lot->status_badge !!}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted small">لا توجد لوتات مستلمة حتى الآن.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
