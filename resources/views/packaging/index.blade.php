@extends('layouts.app')

@section('title', 'مخزن مواد التعبئة والتغليف')
@section('page_title', 'إدارة مواد التعبئة والتغليف والكراتين (Packaging Materials)')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Top Action Card -->
    <div class="premium-card p-4">
        <div class="row g-3 align-items-center justify-content-between">
            <div class="col-lg-7">
                <form action="{{ route('packaging.index') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" style="max-width: 300px;" placeholder="بحث باسم مادة التغليف أو الكود..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-search"></i> بحث</button>
                    @if(request('search'))
                        <a href="{{ route('packaging.index') }}" class="btn btn-sm btn-outline-secondary">إلغاء</a>
                    @endif
                </form>
            </div>
            <div class="col-lg-5 text-lg-end">
                <a href="{{ route('packaging.create') }}" class="btn btn-sm btn-ns-primary me-2">
                    <i class="bi bi-plus-circle me-1"></i> تعريف مادة تغليف جديدة
                </a>
                <a href="{{ route('receiving.create') }}" class="btn btn-sm btn-outline-dark">
                    <i class="bi bi-truck me-1"></i> استلام توريد (GRN)
                </a>
            </div>
        </div>
    </div>

    <!-- Packaging Materials Table -->
    <div class="premium-card">
        <div class="card-header-clean bg-light bg-opacity-50">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-archive-fill text-primary me-2"></i>كتالوج مواد التعبئة والتغليف والأرصدة المتاحة</h6>
            <span class="badge bg-light text-dark border">إجمالي {{ $materials->total() }} صنف</span>
        </div>
        <div class="table-responsive">
            <table class="table table-premium mb-0">
                <thead>
                    <tr>
                        <th>الكود</th>
                        <th>اسم المادة</th>
                        <th>النوع</th>
                        <th>المورد</th>
                        <th>الرصيد المتاح بالمستودع</th>
                        <th>الحد الأدنى للطلب</th>
                        <th>حالة المخزون</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materials as $pm)
                    @php $avail = $pm->totalAvailableStock(); @endphp
                    <tr>
                        <td class="fw-bold en-font text-dark">{{ $pm->code }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $pm->name_ar }}</div>
                            <small class="text-muted">{{ $pm->name }}</small>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $pm->material_type }}</span></td>
                        <td>{{ $pm->supplier?->name_ar ?? $pm->supplier?->name ?? 'متعدد' }}</td>
                        <td class="fw-bold text-success en-font">{{ number_format($avail) }} {{ $pm->unit }}</td>
                        <td class="en-font text-muted">{{ number_format($pm->minimum_stock) }} {{ $pm->unit }}</td>
                        <td>
                            @if($avail <= 0)
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">نفد الرصيد</span>
                            @elseif($avail < $pm->minimum_stock)
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle">مخزون منخفض</span>
                            @else
                                <span class="badge bg-success-subtle text-success border border-success-subtle">متوفر وآمن</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">لا توجد مواد تعبئة مسجلة.</td></tr>
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

    <!-- Packaging Lots Table -->
    <div class="premium-card">
        <div class="card-header-clean bg-light bg-opacity-50">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-boxes text-success me-2"></i>أطقم ولوتات مواد التغليف الواردة بالمستودع (Active Packaging Lots)</h6>
            <span class="badge bg-light text-dark border">{{ count($lots) }} لوتات</span>
        </div>
        <div class="table-responsive">
            <table class="table table-premium mb-0">
                <thead>
                    <tr>
                        <th>رقم اللوت (Lot Number)</th>
                        <th>اسم مادة التعبئة</th>
                        <th>المورد</th>
                        <th>تاريخ الاستلام</th>
                        <th>الكمية المستلمة</th>
                        <th>الرصيد المتاح</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lots as $lot)
                    <tr>
                        <td class="fw-bold en-font text-dark">{{ $lot->lot_number }}</td>
                        <td>{{ $lot->packagingMaterial?->name_ar }}</td>
                        <td>{{ $lot->supplier?->name_ar ?? $lot->supplier?->name }}</td>
                        <td>{{ $lot->receiving_date?->format('Y-m-d') }}</td>
                        <td class="en-font">{{ number_format($lot->quantity) }} {{ $lot->unit }}</td>
                        <td class="fw-bold text-success en-font">{{ number_format($lot->available_quantity) }} {{ $lot->unit }}</td>
                        <td>{!! $lot->status_badge !!}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">لا توجد لوتات تغليف نشطة.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
