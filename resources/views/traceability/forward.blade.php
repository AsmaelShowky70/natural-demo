@extends('layouts.app')

@section('title', 'التتبع الأمامي (Forward Traceability)')
@section('page_title', 'محرك التتبع الأمامي – من المادة الخام إلى المنتجات والأسواق')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Search & Lot Selector Card -->
    <div class="premium-card p-4">
        <form action="{{ route('traceability.forward') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-lg-7">
                <label class="form-label fw-bold text-dark"><i class="bi bi-box-arrow-in-up-right text-primary me-1"></i> أدخل رقم لوت المادة الخام للتتبع الأمامي:</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-upc-scan text-muted"></i></span>
                    <input type="text" name="lot_number" class="form-control form-control-lg en-font fw-bold" placeholder="مثال: RICE-260920-005" value="{{ $lotNumber }}" required>
                    <button type="submit" class="btn btn-ns-primary px-4"><i class="bi bi-search me-1"></i> تتبع اللوت</button>
                </div>
            </div>
            <div class="col-lg-5">
                <label class="form-label text-muted small">أو اختر لوت خام جاهز للعرض:</label>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($allLots->take(4) as $al)
                    <a href="{{ route('traceability.forward', ['lot_number' => $al->lot_number]) }}" class="badge bg-light text-dark border text-decoration-none py-2 px-3 en-font hover-shadow">
                        {{ $al->lot_number }}
                    </a>
                    @endforeach
                </div>
            </div>
        </form>
    </div>

    @if($error)
    <div class="alert alert-danger rounded-4 p-4 shadow-sm">
        <i class="bi bi-exclamation-octagon-fill fs-3 me-2"></i> {{ $error }}
    </div>
    @endif

    @if($forwardData)
    @php
        $lot = $forwardData['lot'];
        $mat = $forwardData['material'];
        $sup = $forwardData['supplier'];
    @endphp

    <!-- Lot Overview Hero -->
    <div class="premium-card p-4 bg-white border-0 shadow-sm">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle py-1 px-3 rounded-pill fw-bold">
                        <i class="bi bi-boxes me-1"></i> لوت مادة خام مسجل
                    </span>
                    {!! $lot->status_badge !!}
                </div>
                <h3 class="fw-bold mb-1 text-dark">{{ $mat?->name_ar }} <span class="text-muted fw-normal fs-5">({{ $mat?->name }})</span></h3>
                <div class="d-flex flex-wrap gap-3 text-muted small mt-2">
                    <span><i class="bi bi-upc text-primary me-1"></i> رقم اللوت: <strong class="en-font">{{ $lot->lot_number }}</strong></span>
                    <span><i class="bi bi-buildings text-primary me-1"></i> المورد: <strong>{{ $sup?->name_ar ?? $sup?->name }}</strong></span>
                    <span><i class="bi bi-calendar3 text-primary me-1"></i> الاستلام: <strong>{{ $lot->receiving_date?->format('Y-m-d') }}</strong></span>
                    <span><i class="bi bi-hourglass text-primary me-1"></i> الصلاحية: <strong>{{ $lot->expiry_date?->format('Y-m-d') }}</strong></span>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('traceability.recall', ['lot_number' => $lot->lot_number]) }}" class="btn btn-outline-danger rounded-pill px-4 py-2">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> فتح محاكي الاستدعاء (Recall)
                </a>
            </div>
        </div>
    </div>

    <!-- Impact & Metrics Summary -->
    <div class="row g-3">
        <div class="col-md-3">
            <div class="kpi-card">
                <span class="text-muted small">الكمية المستلمة كلياً</span>
                <h3 class="fw-bold mb-0 text-dark en-font">{{ number_format($forwardData['total_quantity']) }} <small class="fs-6 text-muted">{{ $lot->unit }}</small></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card">
                <span class="text-muted small">الكمية المستهلكة في التصنيع</span>
                <h3 class="fw-bold mb-0 text-primary en-font">{{ number_format($forwardData['total_consumed']) }} <small class="fs-6 text-muted">{{ $lot->unit }}</small></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card">
                <span class="text-muted small">الرصيد المتبقي بالمستودع</span>
                <h3 class="fw-bold mb-0 text-success en-font">{{ number_format($forwardData['available_quantity']) }} <small class="fs-6 text-muted">{{ $lot->unit }}</small></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card">
                <span class="text-muted small">إجمالي الكراتين المصنعة بالأسواق</span>
                <h3 class="fw-bold mb-0 text-dark en-font">{{ number_format($forwardData['total_cartons_affected']) }} <small class="fs-6 text-muted">كرتونة</small></h3>
            </div>
        </div>
    </div>

    <!-- Affected Production Batches Table -->
    <div class="premium-card">
        <div class="card-header-clean bg-light bg-opacity-50">
            <div>
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-diagram-2-fill text-primary me-2"></i>كافة دفعات الإنتاج والمنتجات النهائية المرتبطة بهذا اللوت</h6>
                <small class="text-muted">حصر دقيق للمنتجات النهائية التي دخل هذا اللوت في تركيبتها لتسهيل الفحص أو الاستدعاء</small>
            </div>
            <span class="badge bg-dark rounded-pill">{{ count($forwardData['batches']) }} دفعات تصنيع</span>
        </div>
        <div class="table-responsive">
            <table class="table table-premium mb-0">
                <thead>
                    <tr>
                        <th>كود الدفعة (Batch Code)</th>
                        <th>المنتج النهائي</th>
                        <th>تاريخ الإنتاج</th>
                        <th>الكمية المستهلكة من اللوت</th>
                        <th>الكراتين المصنعة</th>
                        <th>حالة الجودة</th>
                        <th>حالة الدفعة</th>
                        <th class="text-center">التتبع العكسي للدفعة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($forwardData['batches'] as $item)
                    <tr>
                        <td class="fw-bold en-font text-dark">{{ $item['batch_id'] }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $item['product_name_ar'] }}</div>
                            <small class="text-muted">{{ $item['product_name'] }}</small>
                        </td>
                        <td>{{ $item['production_date'] }}</td>
                        <td class="fw-bold en-font">{{ $item['quantity_used'] }} {{ $item['unit'] }}</td>
                        <td><span class="badge bg-light text-dark border en-font">{{ $item['cartons_produced'] }} كرتونة</span></td>
                        <td>
                            @if($item['qc_status'] === 'passed')
                                <span class="badge bg-success-subtle text-success">مجاز (Pass)</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning">{{ $item['qc_status'] }}</span>
                            @endif
                        </td>
                        <td><span class="badge bg-success-subtle text-success">{{ $item['batch_status'] }}</span></td>
                        <td class="text-center">
                            <a href="{{ route('traceability.backward', ['batch_code' => $item['batch_id']]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <i class="bi bi-eye me-1"></i> تتبع الدفعة كاملة
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">لم يتم استهلاك هذا اللوت في أي دفعة إنتاج حتى الآن (الرصيد كامل في المستودع).</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection
