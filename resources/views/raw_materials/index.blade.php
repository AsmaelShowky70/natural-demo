@extends('layouts.app')

@section('title', 'مخزن المواد الخام')
@section('page_title', 'إدارة مخزون المواد الخام والأطقم الواردة (Raw Materials & Lots)')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Top Action Card -->
    <div class="premium-card p-4">
        <div class="row g-3 align-items-center justify-content-between">
            <div class="col-lg-7">
                <form action="{{ route('raw-materials.index') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" style="max-width: 300px;" placeholder="بحث باسم المادة أو الكود..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-search"></i> بحث</button>
                    @if(request('search'))
                        <a href="{{ route('raw-materials.index') }}" class="btn btn-sm btn-outline-secondary">إلغاء</a>
                    @endif
                </form>
            </div>
            <div class="col-lg-5 text-lg-end">
                <a href="{{ route('raw-materials.create') }}" class="btn btn-sm btn-ns-primary me-2">
                    <i class="bi bi-plus-circle me-1"></i> تعريف مادة خام جديدة
                </a>
                <a href="{{ route('receiving.create') }}" class="btn btn-sm btn-outline-dark">
                    <i class="bi bi-truck me-1"></i> استلام توريد (GRN)
                </a>
            </div>
        </div>
    </div>

    <!-- Raw Materials Catalog -->
    <div class="premium-card">
        <div class="card-header-clean bg-light bg-opacity-50">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-boxes text-success me-2"></i>كتالوج المواد الخام والأرصدة الإجمالية المتاحة</h6>
            <span class="badge bg-light text-dark border">إجمالي {{ $materials->total() }} خامة</span>
        </div>
        <div class="table-responsive">
            <table class="table table-premium mb-0">
                <thead>
                    <tr>
                        <th>الكود (Code)</th>
                        <th>اسم المادة الخام</th>
                        <th>النوع</th>
                        <th>المورد المعتمد</th>
                        <th>الرصيد المتاح بالمستودع</th>
                        <th>الحد الأدنى للطلب</th>
                        <th>حالة المخزون</th>
                        <th class="text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materials as $rm)
                    @php $avail = $rm->totalAvailableStock(); @endphp
                    <tr>
                        <td class="fw-bold en-font text-dark">{{ $rm->code }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $rm->name_ar }}</div>
                            <small class="text-muted">{{ $rm->name }}</small>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $rm->material_type ?? 'خامة عامة' }}</span></td>
                        <td>{{ $rm->supplier?->name_ar ?? $rm->supplier?->name ?? 'موردين متعددين' }}</td>
                        <td class="fw-bold text-success en-font">{{ number_format($avail, 1) }} {{ $rm->unit }}</td>
                        <td class="en-font text-muted">{{ number_format($rm->minimum_stock, 1) }} {{ $rm->unit }}</td>
                        <td>
                            @if($avail <= 0)
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">نفد المخزون</span>
                            @elseif($avail < $rm->minimum_stock)
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle">مخزون منخفض</span>
                            @else
                                <span class="badge bg-success-subtle text-success border border-success-subtle">كافي وآمن</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('raw-materials.show', $rm) }}" class="btn btn-sm btn-light border" title="معاينة اللوتات والتفاصيل">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">لا توجد مواد خام مسجلة.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($materials->hasPages())
        <div class="p-3 border-top">
            {{ $materials->links() }}
        </div>
        @endif
    </div>

    <!-- Active Raw Material Lots Table -->
    <div class="premium-card">
        <div class="card-header-clean bg-light bg-opacity-50">
            <div>
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-upc-scan text-primary me-2"></i>أطقم ولوتات المواد الخام النشطة بالمستودع (Active Lots)</h6>
                <small class="text-muted">الأطقم الواردة مع تواريخ الصلاحية ونسب الرطوبة وحالة الاعتماد</small>
            </div>
            <a href="{{ route('material-issues.index') }}" class="btn btn-sm btn-outline-success">
                <i class="bi bi-arrow-left-right me-1"></i> صرف خامات لصالة الإنتاج
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-premium mb-0">
                <thead>
                    <tr>
                        <th>رقم اللوت (Lot Number)</th>
                        <th>اسم الخامة</th>
                        <th>المورد</th>
                        <th>تاريخ الاستلام</th>
                        <th>تاريخ الانتهاء</th>
                        <th>الرصيد المتاح</th>
                        <th>في الإنتاج</th>
                        <th>الرطوبة %</th>
                        <th>الحالة</th>
                        <th class="text-center">التتبع</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lots as $lot)
                    <tr>
                        <td class="fw-bold en-font text-dark">{{ $lot->lot_number }}</td>
                        <td>{{ $lot->rawMaterial?->name_ar }}</td>
                        <td>{{ $lot->supplier?->name_ar ?? $lot->supplier?->name }}</td>
                        <td>{{ $lot->receiving_date?->format('Y-m-d') }}</td>
                        <td>{{ $lot->expiry_date?->format('Y-m-d') ?? '-' }}</td>
                        <td class="fw-bold text-success en-font">{{ number_format($lot->available_quantity, 1) }} {{ $lot->unit }}</td>
                        <td class="en-font text-muted">{{ number_format($lot->in_production_quantity, 1) }} {{ $lot->unit }}</td>
                        <td>
                            @if($lot->moisture_percentage)
                                <span class="badge bg-light text-dark border en-font">{{ $lot->moisture_percentage }}%</span>
                            @else
                                -
                            @endif
                        </td>
                        <td>{!! $lot->status_badge !!}</td>
                        <td class="text-center">
                            <a href="{{ route('traceability.forward', ['lot_number' => $lot->lot_number]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3" title="التتبع الأمامي">
                                <i class="bi bi-signpost-split me-1"></i> تتبع اللوت
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="text-center py-4 text-muted">لا توجد لوتات نشطة.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
