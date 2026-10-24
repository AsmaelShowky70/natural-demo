@extends('layouts.app')

@section('title', 'محاكي الاستدعاء (Recall Simulator)')
@section('page_title', 'محاكي إدارة الأزمات والاستدعاء الفوري للمنتجات (Product Recall Simulator)')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Search & Lot Selector Card -->
    <div class="premium-card p-4 border-danger border-opacity-25 bg-danger-subtle bg-opacity-10">
        <form action="{{ route('traceability.recall') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-lg-8">
                <label class="form-label fw-bold text-danger"><i class="bi bi-shield-exclamation me-1"></i> فحص سيناريو تلوث أو استدعاء لوت مادة خام مشبوهة:</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-radioactive text-danger"></i></span>
                    <input type="text" name="lot_number" class="form-control form-control-lg en-font fw-bold" placeholder="مثال: RICE-260920-005" value="{{ $lotNumber }}" required>
                    <button type="submit" class="btn btn-danger px-4 fw-bold"><i class="bi bi-radar me-1"></i> حساب نطاق التأثر الفوري</button>
                </div>
            </div>
            <div class="col-lg-4">
                <label class="form-label text-muted small">لوتات خامات لاختبار المحاكي:</label>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($allLots->take(4) as $al)
                    <a href="{{ route('traceability.recall', ['lot_number' => $al->lot_number]) }}" class="badge bg-white text-dark border text-decoration-none py-2 px-3 en-font hover-shadow">
                        {{ $al->lot_number }}
                    </a>
                    @endforeach
                </div>
            </div>
        </form>
    </div>

    @if($recallData)
    <!-- Critical Recall Header -->
    <div class="premium-card p-4 bg-white border-0 shadow-sm border-start border-5 @if($recallData['severity'] === 'CRITICAL') border-danger @else border-warning @endif">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
            <div class="d-flex align-items-center gap-2">
                @if($recallData['severity'] === 'CRITICAL')
                    <span class="badge bg-danger text-white fs-6 py-2 px-3 rounded-pill">
                        <i class="bi bi-exclamation-octagon-fill me-1"></i> مستوى الخطورة: فوري وقصوى (CRITICAL RECALL)
                    </span>
                @else
                    <span class="badge bg-warning text-dark fs-6 py-2 px-3 rounded-pill">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> مستوى الخطورة: متوسط (MEDIUM RECALL)
                    </span>
                @endif
                <span class="text-muted small">لوت المادة الخام: <strong class="en-font text-dark">{{ $recallData['lot_number'] }}</strong></span>
            </div>
            <button class="btn btn-sm btn-dark" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> طباعة تقرير الاستدعاء الرقابي
            </button>
        </div>

        <h4 class="fw-bold text-dark mb-2">تقرير تقييم الأثر الميداني للخامة: {{ $recallData['material_name'] }}</h4>
        <div class="text-muted small mb-3">المورد المعتمد: <strong>{{ $recallData['supplier_name'] }}</strong> | تاريخ التوريد والاستلام: <strong>{{ $recallData['receiving_date'] }}</strong></div>

        <div class="alert alert-danger bg-danger-subtle text-danger border-0 rounded-3 p-3 mb-0">
            <strong><i class="bi bi-shield-x me-1"></i> الإجراء التنظيمي الموصى به:</strong>
            <p class="mb-0 mt-1 small">{{ $recallData['recommended_action'] }}</p>
        </div>
    </div>

    <!-- Impact Stats Grid -->
    <div class="row g-3">
        <div class="col-md-3">
            <div class="kpi-card border-danger border-opacity-25">
                <span class="text-muted small">إجمالي الكراتين المتأثرة بالأسواق</span>
                <h2 class="fw-bold text-danger en-font mb-0">{{ number_format($recallData['total_cartons_in_market']) }}</h2>
                <small class="text-muted">كرتونة في قنوات التوزيع</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card border-danger border-opacity-25">
                <span class="text-muted small">عدد دفعات الإنتاج المتأثرة</span>
                <h2 class="fw-bold text-danger en-font mb-0">{{ $recallData['total_batches_count'] }}</h2>
                <small class="text-muted">أوامر تشغيل معتمدة</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card border-warning border-opacity-25">
                <span class="text-muted small">الكمية المستهلكة من اللوت</span>
                <h2 class="fw-bold text-dark en-font mb-0">{{ number_format($recallData['total_consumed_kg']) }} <span class="fs-6">KG</span></h2>
                <small class="text-muted">تم تصنيعها كلياً</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card border-info border-opacity-25">
                <span class="text-muted small">الرصيد المتبقي بمستودع المصنع</span>
                <h2 class="fw-bold text-primary en-font mb-0">{{ number_format($recallData['remaining_stock_kg']) }} <span class="fs-6">KG</span></h2>
                <small class="text-muted">جاهز للحجز الفوري (Quarantine)</small>
            </div>
        </div>
    </div>

    <!-- Affected Batches Immediate Quarantine List -->
    <div class="premium-card">
        <div class="card-header-clean bg-light">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-list-check text-danger me-2"></i>قائمة الدفعات الواجب عزلها وسحبها فوراً من نقاط البيع والمستودعات</h6>
            <span class="badge bg-danger rounded-pill">{{ count($recallData['affected_batches']) }} دفعات في دائرة الخطر</span>
        </div>
        <div class="table-responsive">
            <table class="table table-premium mb-0">
                <thead>
                    <tr>
                        <th>كود الدفعة (Batch Code)</th>
                        <th>اسم المنتج التجاري</th>
                        <th>تاريخ الإنتاج</th>
                        <th>الكمية المستهلكة من اللوت</th>
                        <th>الكراتين في السوق</th>
                        <th>حالة الإفراج الحالية</th>
                        <th class="text-center">إجراءات العزل</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recallData['affected_batches'] as $b)
                    <tr>
                        <td class="fw-bold en-font text-danger">{{ $b['batch_id'] }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $b['product_name_ar'] }}</div>
                            <small class="text-muted">{{ $b['product_name'] }}</small>
                        </td>
                        <td>{{ $b['production_date'] }}</td>
                        <td class="fw-bold en-font">{{ $b['quantity_used'] }} {{ $b['unit'] }}</td>
                        <td class="fw-bold text-danger en-font">{{ $b['cartons_produced'] }} كرتونة</td>
                        <td><span class="badge bg-danger-subtle text-danger">{{ $b['final_release_status'] }}</span></td>
                        <td class="text-center">
                            <a href="{{ route('traceability.backward', ['batch_code' => $b['batch_id']]) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                                <i class="bi bi-eye me-1"></i> تتبع كامل
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">لا توجد أي دفعات إنتاجية استهلكت هذا اللوت (لم يدخل خطوط الإنتاج).</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection
