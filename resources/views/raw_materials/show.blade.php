@extends('layouts.app')

@section('title', 'مادة خام ' . $rawMaterial->name_ar)
@section('page_title', 'تفاصيل المادة الخام واللوتات: ' . $rawMaterial->name_ar)

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Hero Card -->
    <div class="premium-card p-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">
                        {{ $rawMaterial->material_type ?? 'خامة' }}
                    </span>
                    <span class="badge bg-light text-dark border">كود: {{ $rawMaterial->code }}</span>
                </div>
                <h3 class="fw-bold mb-1 text-dark">{{ $rawMaterial->name_ar }} <span class="text-muted fs-5 fw-normal">({{ $rawMaterial->name }})</span></h3>
                <p class="text-secondary small mb-3">{{ $rawMaterial->specification ?? 'مطابقة للمواصفات الغذائية' }}</p>

                <div class="d-flex flex-wrap gap-4 text-muted small">
                    <span>المورد الرئيسي: <strong class="text-dark">{{ $rawMaterial->supplier?->name_ar ?? 'متعدد' }}</strong></span>
                    <span>الرصيد المتاح حالياً: <strong class="text-success fs-6 en-font">{{ number_format($rawMaterial->totalAvailableStock()) }} {{ $rawMaterial->unit }}</strong></span>
                    <span>الحد الأدنى للطلب: <strong class="text-dark en-font">{{ number_format($rawMaterial->minimum_stock) }} {{ $rawMaterial->unit }}</strong></span>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('receiving.create') }}" class="btn btn-ns-primary me-2">
                    <i class="bi bi-truck me-1"></i> استلام شحنة جديدة (GRN)
                </a>
                <a href="{{ route('material-issues.index') }}" class="btn btn-light border">
                    <i class="bi bi-arrow-left-right me-1"></i> صرف للإنتاج
                </a>
            </div>
        </div>
    </div>

    <!-- Lots Received of this material -->
    <div class="premium-card">
        <div class="card-header-clean bg-light">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-boxes text-success me-2"></i>الأطقم واللوتات الواردة من هذه المادة (Lots History)</h6>
            <span class="badge bg-light text-dark border">{{ count($rawMaterial->lots) }} لوتات</span>
        </div>
        <div class="table-responsive">
            <table class="table table-premium mb-0">
                <thead>
                    <tr>
                        <th>رقم اللوت (Lot Number)</th>
                        <th>المورد</th>
                        <th>تاريخ الاستلام</th>
                        <th>تاريخ الصلاحية</th>
                        <th>الكمية الواردة</th>
                        <th>الرصيد المتاح</th>
                        <th>المصروف للإنتاج</th>
                        <th>الرطوبة %</th>
                        <th>الحالة</th>
                        <th class="text-center">التتبع الأمامي</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rawMaterial->lots as $lot)
                    <tr>
                        <td class="fw-bold en-font text-dark">{{ $lot->lot_number }}</td>
                        <td>{{ $lot->supplier?->name_ar ?? $lot->supplier?->name }}</td>
                        <td>{{ $lot->receiving_date?->format('Y-m-d') }}</td>
                        <td>{{ $lot->expiry_date?->format('Y-m-d') ?? '-' }}</td>
                        <td class="en-font">{{ number_format($lot->quantity) }} {{ $lot->unit }}</td>
                        <td class="fw-bold text-success en-font">{{ number_format($lot->available_quantity) }} {{ $lot->unit }}</td>
                        <td class="en-font text-muted">{{ number_format($lot->in_production_quantity) }} {{ $lot->unit }}</td>
                        <td>{{ $lot->moisture_percentage ? $lot->moisture_percentage . '%' : '-' }}</td>
                        <td>{!! $lot->status_badge !!}</td>
                        <td class="text-center">
                            <a href="{{ route('traceability.forward', ['lot_number' => $lot->lot_number]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <i class="bi bi-signpost-split me-1"></i> تتبع بالدفعات
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="text-center py-4 text-muted">لا توجد لوتات مسجلة لهذه الخامة.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
