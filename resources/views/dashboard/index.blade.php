@extends('layouts.app')

@section('title', 'لوحة التحكم الإحصائية')
@section('page_title', 'لوحة التحكم والمؤشرات الحية')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Top Welcome Banner & Department Selector -->
    <div class="premium-card p-4 bg-white border-0 shadow-sm">
        <div class="row align-items-center g-3">
            <div class="col-lg-6">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success-subtle text-success p-3 rounded-4 d-flex align-items-center justify-content-center" style="width: 58px; height: 58px;">
                        <i class="bi bi-buildings-fill fs-2"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1">مرحباً بك، {{ auth()->user()->name_ar ?? auth()->user()->name }} 👋</h4>
                        <div class="text-muted small">
                            <span class="badge bg-success text-white me-1">{{ auth()->user()->role_title }}</span>
                            <span>مصنع ناتشورال سناكس – خطوط الإنتاج والرقابة النشطة</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-lg-end">
                <div class="btn-group p-1 bg-light rounded-pill border" role="group">
                    @if(auth()->user()->isAdmin() || auth()->user()->role === 'viewer')
                    <a href="{{ route('dashboard', ['tab' => 'executive']) }}" class="btn btn-sm rounded-pill px-3 {{ $tab === 'executive' ? 'btn-success fw-bold text-white shadow-sm' : 'text-muted' }}">
                        <i class="bi bi-speedometer2 me-1"></i> الإدارة العليا
                    </a>
                    @endif
                    @if(auth()->user()->isAdmin() || auth()->user()->role === 'warehouse')
                    <a href="{{ route('dashboard', ['tab' => 'warehouse']) }}" class="btn btn-sm rounded-pill px-3 {{ $tab === 'warehouse' ? 'btn-success fw-bold text-white shadow-sm' : 'text-muted' }}">
                        <i class="bi bi-boxes me-1"></i> المستودعات
                    </a>
                    @endif
                    @if(auth()->user()->isAdmin() || auth()->user()->role === 'production_manager')
                    <a href="{{ route('dashboard', ['tab' => 'production']) }}" class="btn btn-sm rounded-pill px-3 {{ $tab === 'production' ? 'btn-success fw-bold text-white shadow-sm' : 'text-muted' }}">
                        <i class="bi bi-gear-wide-connected me-1"></i> الإنتاج
                    </a>
                    @endif
                    @if(auth()->user()->isAdmin() || auth()->user()->role === 'quality_control')
                    <a href="{{ route('dashboard', ['tab' => 'quality']) }}" class="btn btn-sm rounded-pill px-3 {{ $tab === 'quality' ? 'btn-success fw-bold text-white shadow-sm' : 'text-muted' }}">
                        <i class="bi bi-clipboard2-check me-1"></i> الجودة والمختبر
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Alert for QC Pending Notifications -->
    @if($pendingQcCount > 0)
    <div class="alert alert-warning border-0 shadow-sm rounded-4 p-3 d-flex align-items-center justify-content-between mb-0">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-warning text-dark p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                <i class="bi bi-bell-fill fs-5"></i>
            </div>
            <div>
                <strong class="d-block">يوجد {{ $pendingQcCount }} إشعار فحص جودة بانتظار اتخاذ الإجراء المخبري!</strong>
                <small class="text-muted">تشمل توريدات خام جديدة ودفعات إنتاج مكتملة تتطلب اعتماد الإفراج النهائي.</small>
            </div>
        </div>
        <a href="{{ route('quality.index') }}" class="btn btn-sm btn-dark rounded-pill px-3">
            الانتقال لقائمة الفحص <i class="bi bi-arrow-left ms-1"></i>
        </a>
    </div>
    @endif

    <!-- KPI Metric Cards Grid -->
    <div class="row g-3">
        <!-- Metric 1: Cartons Produced -->
        <div class="col-sm-6 col-xl-3">
            <div class="kpi-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted small fw-semibold">إنتاج الكراتين النهائي</span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">مفرج عنه</span>
                </div>
                <div class="d-flex align-items-baseline justify-content-between">
                    <div>
                        <h2 class="fw-bold mb-0 text-dark en-font">{{ number_format($totalCartonsProduced) }}</h2>
                        <small class="text-muted">كرتونة تامة الصنع</small>
                    </div>
                    <div class="kpi-icon-wrap bg-success-subtle text-success">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metric 2: Efficiency Yield Rate -->
        <div class="col-sm-6 col-xl-3">
            <div class="kpi-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted small fw-semibold">معدل كفاءة التشغيل (Yield)</span>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">كفاءة عالية</span>
                </div>
                <div class="d-flex align-items-baseline justify-content-between">
                    <div>
                        <h2 class="fw-bold mb-0 text-primary en-font">{{ number_format($avgYield, 1) }}%</h2>
                        <small class="text-muted">نسبة الهالك: {{ number_format($avgScrap, 1) }}%</small>
                    </div>
                    <div class="kpi-icon-wrap bg-primary-subtle text-primary">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metric 3: Quality Pass Rate -->
        <div class="col-sm-6 col-xl-3">
            <div class="kpi-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted small fw-semibold">معدل اجتياز الجودة (QC)</span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">معتمد</span>
                </div>
                <div class="d-flex align-items-baseline justify-content-between">
                    <div>
                        <h2 class="fw-bold mb-0 text-success en-font">{{ number_format($qcPassRate, 1) }}%</h2>
                        <small class="text-muted">من إجمالي {{ $totalInspections }} فحص مخبري</small>
                    </div>
                    <div class="kpi-icon-wrap bg-success-subtle text-success">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metric 4: Raw Material Stock -->
        <div class="col-sm-6 col-xl-3">
            <div class="kpi-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted small fw-semibold">رصيد الخامات المتاح</span>
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill">المستودع</span>
                </div>
                <div class="d-flex align-items-baseline justify-content-between">
                    <div>
                        <h2 class="fw-bold mb-0 text-dark en-font">{{ number_format($totalRawStockKg) }} <span class="fs-6 fw-normal text-muted">KG</span></h2>
                        <small class="text-muted">{{ number_format($totalPackagingStock) }} قطعة تعبئة وتغليف</small>
                    </div>
                    <div class="kpi-icon-wrap bg-warning-subtle text-warning">
                        <i class="bi bi-pie-chart-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Quality Section -->
    <div class="row g-4">
        <!-- Left: Production Output Chart -->
        <div class="col-lg-8">
            <div class="premium-card">
                <div class="card-header-clean">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-bar-chart-line-fill text-success me-2"></i>حجم الإنتاج اليومي وتوزيع الكراتين</h6>
                        <small class="text-muted">مقارنة الكميات الفعلية المصنعة (KG) والكراتين</small>
                    </div>
                    <a href="{{ route('production.create') }}" class="btn btn-sm btn-ns-primary">
                        <i class="bi bi-plus-circle me-1"></i> أمر تشغيل جديد
                    </a>
                </div>
                <div class="p-4">
                    <canvas id="productionChart" style="max-height: 280px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Right: Quality Lab Quick Status -->
        <div class="col-lg-4">
            <div class="premium-card h-100">
                <div class="card-header-clean">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-droplet-half text-info me-2"></i>مؤشرات الجودة والرطوبة</h6>
                    <span class="badge bg-light text-dark border">المختبر</span>
                </div>
                <div class="p-4 d-flex flex-column gap-3">
                    <!-- Moisture Raw -->
                    <div class="p-3 bg-light rounded-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small fw-semibold text-muted">متوسط رطوبة المواد الخام</span>
                            <span class="small fw-bold text-success en-font">{{ number_format($avgRawMoisture, 1) }}%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: {{ min(100, $avgRawMoisture * 7) }}%"></div>
                        </div>
                        <small class="text-muted" style="font-size: 0.72rem;">المعيار المعتمد: أقل من 14.0%</small>
                    </div>

                    <!-- Moisture Finished Goods -->
                    <div class="p-3 bg-light rounded-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small fw-semibold text-muted">متوسط رطوبة المنتج النهائي</span>
                            <span class="small fw-bold text-success en-font">{{ number_format($avgFgMoisture, 1) }}%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-emerald-500 bg-success" style="width: {{ min(100, $avgFgMoisture * 10) }}%"></div>
                        </div>
                        <small class="text-muted" style="font-size: 0.72rem;">المعيار المعتمد: أقل من 8.0%</small>
                    </div>

                    <!-- Quick Trace Search Box -->
                    <div class="p-3 rounded-3 border border-success-subtle bg-success-subtle bg-opacity-25 mt-auto">
                        <h6 class="fw-bold mb-2 text-dark fs-7"><i class="bi bi-search me-1 text-success"></i> فحص فوري لكود الباتش أو اللوت</h6>
                        <form action="{{ route('traceability.backward') }}" method="GET" class="d-flex gap-2">
                            <input type="text" name="batch_code" class="form-control form-control-sm border-0 shadow-sm" placeholder="مثال: RK-M-260925-A-001" value="RK-M-260925-A-001">
                            <button type="submit" class="btn btn-sm btn-success px-3"><i class="bi bi-arrow-left"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Production Batches Table -->
    <div class="premium-card">
        <div class="card-header-clean">
            <div>
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-list-task text-primary me-2"></i>دفعات الإنتاج الحديثة وحالات التتبع</h6>
                <small class="text-muted">متابعة حالة تصنيع واعتماد الدفعات وأرقام الباركود</small>
            </div>
            <a href="{{ route('production.index') }}" class="btn btn-sm btn-light border">
                عرض كافة الدفعات <i class="bi bi-arrow-left ms-1"></i>
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-premium align-middle mb-0">
                <thead>
                    <tr>
                        <th>كود الدفعة (Batch Code)</th>
                        <th>المنتج والوصفة</th>
                        <th>تاريخ الإنتاج</th>
                        <th>الوردية والخط</th>
                        <th>المخطط / الفعلي</th>
                        <th>الكراتين</th>
                        <th>حالة الجودة</th>
                        <th>حالة الدفعة</th>
                        <th class="text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBatches as $b)
                    <tr>
                        <td>
                            <div class="fw-bold text-dark en-font">{{ $b->batch_id }}</div>
                            <small class="text-muted en-font" style="font-size: 0.75rem;">{{ $b->finished_goods_barcode }}</small>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $b->product?->name_ar }}</div>
                            <small class="text-muted">{{ $b->product?->name }} ({{ $b->product?->sku }})</small>
                        </td>
                        <td>{{ $b->production_date?->format('Y-m-d') }}</td>
                        <td>
                            <span class="badge bg-light text-dark border me-1">وردية {{ $b->shift }}</span>
                            <small class="text-muted">{{ $b->production_line }}</small>
                        </td>
                        <td>
                            <span class="en-font fw-bold">{{ number_format($b->actual_quantity) }}</span> /
                            <span class="en-font text-muted">{{ number_format($b->planned_quantity) }} KG</span>
                        </td>
                        <td>
                            <span class="badge bg-dark rounded-pill en-font px-2 py-1">{{ $b->cartons_produced }} كرتونة</span>
                        </td>
                        <td>
                            @if($b->qc_status === 'passed')
                                <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-check-circle me-1"></i>مجاز (Pass)</span>
                            @elseif($b->qc_status === 'failed')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle"><i class="bi bi-x-circle me-1"></i>مرفوض (Fail)</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle"><i class="bi bi-clock me-1"></i>قيد الفحص</span>
                            @endif
                        </td>
                        <td>{!! $b->status_badge !!}</td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('production.show', $b) }}" class="btn btn-light border" title="تفاصيل الدفعة والتتبع">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('production.traveler', $b) }}" target="_blank" class="btn btn-light border text-primary" title="طباعة كارت التشغيل">
                                    <i class="bi bi-printer"></i>
                                </a>
                                <a href="{{ route('trace.public', $b->batch_id) }}" target="_blank" class="btn btn-light border text-success" title="صفحة التتبع العامة QR">
                                    <i class="bi bi-qr-code"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">لا توجد دفعات إنتاج مسجلة حالياً.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Warehouse & Supply Grid -->
    <div class="row g-4">
        <!-- Recent Goods Receiving (GRN) -->
        <div class="col-lg-6">
            <div class="premium-card h-100">
                <div class="card-header-clean">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-truck text-warning me-2"></i>آخر سندات استلام التوريدات (GRN)</h6>
                    <a href="{{ route('receiving.create') }}" class="btn btn-sm btn-light border"><i class="bi bi-plus-circle me-1"></i> استلام جديد</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-premium mb-0">
                        <thead>
                            <tr>
                                <th>رقم السند</th>
                                <th>المورد</th>
                                <th>النوع</th>
                                <th>التاريخ</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentReceivings as $grn)
                            <tr>
                                <td class="fw-bold en-font text-dark"><a href="{{ route('receiving.show', $grn) }}" class="text-decoration-none">{{ $grn->receiving_number }}</a></td>
                                <td>{{ $grn->supplier?->name_ar ?? $grn->supplier?->name }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $grn->receiving_type === 'raw_material' ? 'مواد خام' : 'مواد تعبئة' }}</span></td>
                                <td>{{ $grn->receiving_date?->format('Y-m-d') }}</td>
                                <td><span class="badge bg-success-subtle text-success">مكتمل</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-3 text-muted">لا توجد سجلات استلام حديثة.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- System Audit Log Trail -->
        <div class="col-lg-6">
            <div class="premium-card h-100">
                <div class="card-header-clean">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-fingerprint text-info me-2"></i>سجل الحركات الإدارية والرقابية</h6>
                    <a href="{{ route('audit.index') }}" class="btn btn-sm btn-light border">السجل الكامل</a>
                </div>
                <div class="p-3">
                    <div class="d-flex flex-column gap-3">
                        @forelse($recentAuditLogs as $log)
                        <div class="d-flex align-items-center justify-content-between p-2 rounded bg-light bg-opacity-50 border-bottom border-light">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-secondary rounded-pill en-font">{{ $log->action }}</span>
                                <span class="small fw-semibold text-dark">{{ $log->entity_type }} #{{ $log->entity_id }}</span>
                                <small class="text-muted d-none d-sm-inline">بواسطة {{ $log->user_email }}</small>
                            </div>
                            <span class="text-muted small en-font">{{ $log->created_at?->diffForHumans() }}</span>
                        </div>
                        @empty
                        <div class="text-center py-3 text-muted small">لا توجد سجلات تدقيق حتى الآن.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('productionChart');
        if (ctx) {
            const labels = [
                @foreach($dailyProduction as $dp)
                    "{{ $dp->date }}",
                @endforeach
            ];
            const dataKg = [
                @foreach($dailyProduction as $dp)
                    {{ $dp->total_kg }},
                @endforeach
            ];
            const dataCartons = [
                @foreach($dailyProduction as $dp)
                    {{ $dp->total_cartons }},
                @endforeach
            ];

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels.length ? labels : ['2026-09-20', '2026-09-21', '2026-09-22', '2026-09-23', '2026-09-24', '2026-09-25'],
                    datasets: [
                        {
                            label: 'الكمية المنتجة (KG)',
                            data: dataKg.length ? dataKg : [350, 420, 390, 500, 348, 398],
                            backgroundColor: 'rgba(16, 185, 129, 0.85)',
                            borderRadius: 8,
                            yAxisID: 'y'
                        },
                        {
                            label: 'عدد الكراتين المنتجة',
                            data: dataCartons.length ? dataCartons : [175, 210, 195, 250, 174, 200],
                            backgroundColor: 'rgba(59, 130, 246, 0.85)',
                            borderRadius: 8,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', labels: { font: { family: 'Cairo' } } }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: { display: true, text: 'كيلوجرام (KG)', font: { family: 'Cairo' } }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            grid: { drawOnChartArea: false },
                            title: { display: true, text: 'عدد الكراتين', font: { family: 'Cairo' } }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
