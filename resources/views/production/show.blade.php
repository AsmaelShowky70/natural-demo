@extends('layouts.app')

@section('title', 'تفاصيل الدفعة ' . $batch->batch_id)
@section('page_title', 'أمر تشغيل الدفعة: ' . $batch->batch_id)

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Top Card / Header -->
    <div class="premium-card p-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-2">
                    {!! $batch->status_badge !!}
                    @if($batch->qc_status === 'passed')
                        <span class="badge bg-success text-white py-1 px-3 rounded-pill"><i class="bi bi-shield-check me-1"></i>فحص الجودة: معتمد</span>
                    @else
                        <span class="badge bg-warning text-dark py-1 px-3 rounded-pill"><i class="bi bi-clock me-1"></i>فحص الجودة: {{ $batch->qc_status }}</span>
                    @endif
                </div>
                <h3 class="fw-bold mb-1 text-dark">{{ $batch->product?->name_ar }} <span class="text-muted fs-5 fw-normal">({{ $batch->product?->name }})</span></h3>
                <div class="d-flex flex-wrap gap-3 text-muted small mt-2">
                    <span>كود الدفعة: <strong class="en-font text-dark">{{ $batch->batch_id }}</strong></span>
                    <span>الباركود: <strong class="en-font text-dark">{{ $batch->finished_goods_barcode }}</strong></span>
                    <span>تاريخ الإنتاج: <strong>{{ $batch->production_date?->format('Y-m-d') }}</strong></span>
                    <span>الوردية: <strong>{{ $batch->shift }} ({{ $batch->production_line }})</strong></span>
                </div>
            </div>
            <div class="col-lg-5 text-lg-end">
                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                    <a href="{{ route('production.traveler', $batch) }}" target="_blank" class="btn btn-sm btn-primary">
                        <i class="bi bi-printer me-1"></i> طباعة كارت التشغيل (Traveler)
                    </a>
                    <a href="{{ route('trace.public', $batch->batch_id) }}" target="_blank" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-qr-code me-1"></i> صفحة التتبع العامة (QR)
                    </a>
                    @if($batch->qc_status === 'passed' && $batch->final_release_status !== 'released')
                    <form action="{{ route('quality.release', $batch) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">
                            <i class="bi bi-patch-check-fill me-1"></i> اعتماد الإفراج النهائي للمستودع
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Output & Yield Metrics -->
    <div class="row g-3">
        <div class="col-md-3">
            <div class="kpi-card">
                <span class="text-muted small">الكمية المخططة (Planned)</span>
                <h3 class="fw-bold text-dark en-font mb-0">{{ number_format($batch->planned_quantity) }} <span class="fs-6 text-muted">KG</span></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card">
                <span class="text-muted small">الكمية الفعلية المنتجة (Actual)</span>
                <h3 class="fw-bold text-primary en-font mb-0">{{ number_format($batch->actual_quantity) }} <span class="fs-6 text-muted">KG</span></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card">
                <span class="text-muted small">عدد الكراتين المعبأة</span>
                <h3 class="fw-bold text-success en-font mb-0">{{ $batch->cartons_produced }} <span class="fs-6 text-muted">كرتونة</span></h3>
                <small class="text-muted">({{ $batch->units_per_carton }} عبوة في الكرتون)</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card">
                <span class="text-muted small">نسبة الكفاءة والهالك</span>
                <h3 class="fw-bold text-dark en-font mb-0">{{ $batch->productivity_yield }}% <small class="fs-6 text-success">كفاءة</small></h3>
                <small class="text-danger">الهالك: {{ $batch->waste_quantity }} KG ({{ $batch->scrap_percentage }}%)</small>
            </div>
        </div>
    </div>

    <!-- Consumed Raw Materials & Packaging Grid -->
    <div class="row g-4">
        <!-- Raw Materials Consumed -->
        <div class="col-lg-7">
            <div class="premium-card h-100">
                <div class="card-header-clean bg-light">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-boxes text-success me-2"></i>المواد الخام المستهلكة في التصنيع</h6>
                    <span class="badge bg-success-subtle text-success">{{ count($batch->materials) }} خامات</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-premium mb-0">
                        <thead>
                            <tr>
                                <th>لوت الخامة</th>
                                <th>اسم المادة الخام</th>
                                <th>المورد الأصلي</th>
                                <th>الكمية المستهلكة</th>
                                <th>الرطوبة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($batch->materials as $mat)
                            @php $lot = $mat->rawMaterialLot; @endphp
                            <tr>
                                <td class="fw-bold en-font text-dark">
                                    <a href="{{ route('traceability.forward', ['lot_number' => $lot?->lot_number]) }}" class="text-decoration-none">
                                        {{ $lot?->lot_number }}
                                    </a>
                                </td>
                                <td>{{ $lot?->rawMaterial?->name_ar }}</td>
                                <td>{{ $lot?->supplier?->name_ar ?? $lot?->supplier?->name }}</td>
                                <td class="fw-bold en-font">{{ $mat->quantity_used }} {{ $mat->unit }}</td>
                                <td>
                                    @if($lot?->moisture_percentage)
                                        <span class="badge bg-light text-dark border en-font">{{ $lot->moisture_percentage }}%</span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-3 text-muted">لم يتم تسجيل خامات مستهلكة.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Packaging Materials Consumed & QR Code Preview -->
        <div class="col-lg-5">
            <div class="premium-card h-100">
                <div class="card-header-clean bg-light">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-qr-code text-primary me-2"></i>ملصق الباركود ورمز الـ QR</h6>
                    <span class="badge bg-light text-dark border">التتبع الرقمي</span>
                </div>
                <div class="p-4 text-center d-flex flex-column align-items-center justify-content-center">
                    <div class="p-3 bg-white border rounded-4 shadow-sm mb-3">
                        {!! $qrSvg !!}
                    </div>
                    <div class="fw-bold en-font text-dark fs-5">{{ $batch->batch_id }}</div>
                    <small class="text-muted mb-3">مسح الرمز يفتح صفحة التتبع الرسمية للمستهلك والمفتشين</small>
                    <a href="{{ $publicTraceUrl }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                        <i class="bi bi-box-arrow-up-right me-1"></i> فتح صفحة التتبع العامة
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quality Control Inspections on this batch -->
    <div class="premium-card">
        <div class="card-header-clean bg-light">
            <div class="d-flex align-items-center gap-2">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-clipboard2-check-fill text-info me-2"></i>سجل الفحوصات المخبرية واختبارات الجودة للدفعة</h6>
            </div>
            <a href="{{ route('quality.create', ['target' => 'finished_goods', 'target_id' => $batch->id]) }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-plus-circle me-1"></i> إضافة فحص جودة جديد
            </a>
        </div>
        <div class="p-4">
            @forelse($batch->inspections as $ins)
            <div class="p-3 bg-light rounded-3 mb-3 border">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold text-dark fs-6">{{ $ins->inspection_type }} (رقم: {{ $ins->inspection_number }})</span>
                    {!! $ins->result_badge !!}
                </div>
                <div class="small text-muted mb-2">الفاحص: <strong>{{ $ins->inspector }}</strong> | تاريخ الفحص: <strong>{{ $ins->inspection_date?->format('Y-m-d') }}</strong></div>
                <p class="mb-3 small text-secondary">{{ $ins->notes }}</p>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered bg-white mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th>المعيار</th>
                                <th>المواصفة القياسية</th>
                                <th>النتيجة المقاسة</th>
                                <th>التقييم</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ins->results as $res)
                            <tr>
                                <td>{{ $res->parameter }}</td>
                                <td class="text-muted">{{ $res->specification }}</td>
                                <td class="fw-bold">{{ $res->actual_result }}</td>
                                <td>
                                    @if($res->result === 'pass')
                                        <span class="badge bg-success-subtle text-success">ناجح</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger">راسب</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @empty
            <div class="text-center py-4 text-muted">
                لا توجد فحوصات جودة مسجلة لهذه الدفعة حتى الآن.
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
