@extends('layouts.app')

@section('title', 'التتبع العكسي (Backward Traceability)')
@section('page_title', 'محرك التتبع العكسي – من المنتج النهائي إلى المورد')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Search & Batch Selector Card -->
    <div class="premium-card p-4">
        <form action="{{ route('traceability.backward') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-lg-7">
                <label class="form-label fw-bold text-dark"><i class="bi bi-qr-code-scan text-success me-1"></i> أدخل كود الدفعة أو امسح الباركود للبدء بالتتبع العكسي:</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-upc-scan text-muted"></i></span>
                    <input type="text" name="batch_code" class="form-control form-control-lg en-font fw-bold" placeholder="مثال: RK-M-260925-A-001" value="{{ $batchCode }}" required>
                    <button type="submit" class="btn btn-ns-primary px-4"><i class="bi bi-search me-1"></i> تتبع فوري</button>
                </div>
            </div>
            <div class="col-lg-5">
                <label class="form-label text-muted small">أو اختر دفعة إنتاج جاهزة للعرض السريع:</label>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($allBatches->take(4) as $ab)
                    <a href="{{ route('traceability.backward', ['batch_code' => $ab->batch_id]) }}" class="badge bg-light text-dark border text-decoration-none py-2 px-3 en-font hover-shadow">
                        {{ $ab->batch_id }}
                    </a>
                    @endforeach
                </div>
            </div>
        </form>
    </div>

    @if($error)
    <div class="alert alert-danger rounded-4 p-4 shadow-sm">
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-exclamation-octagon-fill fs-2"></i>
            <div>
                <h5 class="fw-bold mb-1">خطأ في التتبع:</h5>
                <p class="mb-0">{{ $error }}</p>
            </div>
        </div>
    </div>
    @endif

    @if($traceData)
    @php
        $b = $traceData['batch'];
        $p = $traceData['product'];
    @endphp

    <!-- Traceability Tree Header / Batch Hero Card -->
    <div class="premium-card p-4 bg-white border-0 shadow-sm">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-success-subtle text-success border border-success-subtle py-1 px-3 rounded-pill fw-bold">
                        <i class="bi bi-check-circle-fill me-1"></i> مسار التتبع مكتمل وموثق 100%
                    </span>
                    <span class="badge bg-light text-dark border">ISO 22000 Certified</span>
                </div>
                <h3 class="fw-bold mb-1 text-dark">{{ $p?->name_ar }} <span class="text-muted fw-normal fs-5">({{ $p?->name }})</span></h3>
                <div class="d-flex flex-wrap gap-3 text-muted small mt-2">
                    <span><i class="bi bi-tag text-success me-1"></i> كود المنتج: <strong>{{ $p?->product_code }}</strong></span>
                    <span><i class="bi bi-upc text-success me-1"></i> SKU: <strong class="en-font">{{ $p?->sku }}</strong></span>
                    <span><i class="bi bi-calendar3 text-success me-1"></i> تاريخ الإنتاج: <strong>{{ $b->production_date?->format('Y-m-d') }}</strong></span>
                    <span><i class="bi bi-clock-history text-success me-1"></i> الوردية: <strong>{{ $b->shift }} ({{ $b->production_line }})</strong></span>
                </div>
            </div>
            <div class="col-lg-5 text-lg-end">
                <div class="d-inline-flex flex-column align-items-center p-3 bg-light rounded-4 border">
                    <div class="mb-2">{!! $barcodeSvg !!}</div>
                    <div class="fw-bold en-font small text-dark">{{ $b->batch_id }}</div>
                    <a href="{{ route('trace.public', $b->batch_id) }}" target="_blank" class="btn btn-sm btn-outline-success mt-2 rounded-pill px-3">
                        <i class="bi bi-box-arrow-up-right me-1"></i> رابط الفحص العام للعملاء
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Traceability Flow Steps (Timeline Layout) -->
    <div class="row g-4">
        <!-- Step 1: Raw Materials & Origin Suppliers -->
        <div class="col-12">
            <div class="premium-card">
                <div class="card-header-clean bg-light bg-opacity-50">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px;">1</div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-boxes text-success me-1"></i> أطقم المواد الخام المستهلكة في الدفعة (Raw Materials Trace)</h6>
                            <small class="text-muted">بيانات اللوت، المورد الأصلي، سند الاستلام، ونسبة الرطوبة وشهادة التحليل COA</small>
                        </div>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">{{ count($traceData['raw_materials']) }} خامات رئيسية</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-premium mb-0">
                        <thead>
                            <tr>
                                <th>لوت الخامة (Lot Number)</th>
                                <th>اسم المادة الخام</th>
                                <th>المورد الزراعي / التجاري</th>
                                <th>لوت المورد الأصلي</th>
                                <th>الكمية المستهلكة</th>
                                <th>تاريخ الاستلام</th>
                                <th>تاريخ الصلاحية</th>
                                <th>الرطوبة (Moisture)</th>
                                <th>شهادة التحليل (COA)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($traceData['raw_materials'] as $rm)
                            <tr>
                                <td class="fw-bold en-font text-dark">
                                    <a href="{{ route('traceability.forward', ['lot_number' => $rm['lot_number']]) }}" class="text-decoration-none">
                                        {{ $rm['lot_number'] }}
                                    </a>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $rm['material_name_ar'] }}</div>
                                    <small class="text-muted">{{ $rm['material_name'] }}</small>
                                </td>
                                <td>
                                    <span class="text-dark fw-semibold">{{ $rm['supplier_name_ar'] ?? $rm['supplier_name'] }}</span>
                                </td>
                                <td class="en-font small text-muted">{{ $rm['supplier_lot_number'] ?? 'N/A' }}</td>
                                <td class="fw-bold en-font text-dark">{{ $rm['quantity_used'] }} {{ $rm['unit'] }}</td>
                                <td>{{ $rm['receiving_date'] }}</td>
                                <td>{{ $rm['expiry_date'] }}</td>
                                <td>
                                    @if($rm['moisture_percentage'])
                                        <span class="badge bg-info-subtle text-info border border-info-subtle en-font">{{ $rm['moisture_percentage'] }}%</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($rm['coa_documents']) && count($rm['coa_documents']) > 0)
                                        <a href="{{ route('documents.index', ['search' => $rm['lot_number']]) }}" class="btn btn-sm btn-outline-success py-0 px-2 rounded-pill">
                                            <i class="bi bi-file-earmark-pdf me-1"></i> معتمد
                                        </a>
                                    @else
                                        <span class="badge bg-light text-muted border">مرفق بالسند</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Step 2: Packaging Materials -->
        <div class="col-12">
            <div class="premium-card">
                <div class="card-header-clean bg-light bg-opacity-50">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px;">2</div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-archive-fill text-primary me-1"></i> مواد التعبئة والتغليف (Packaging Materials)</h6>
                            <small class="text-muted">الأكياس ورقائق الألومنيوم والكراتين المستخدمة لحفظ المنتج الغذائي</small>
                        </div>
                    </div>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">{{ count($traceData['packaging_materials']) }} عناصر تغليف</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-premium mb-0">
                        <thead>
                            <tr>
                                <th>لوت مادة التعبئة</th>
                                <th>اسم المادة</th>
                                <th>المورد</th>
                                <th>الكمية المستهلكة</th>
                                <th>حالة الاعتماد</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($traceData['packaging_materials'] as $pm)
                            <tr>
                                <td class="fw-bold en-font text-dark">{{ $pm['lot_number'] }}</td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $pm['material_name_ar'] }}</div>
                                    <small class="text-muted">{{ $pm['material_name'] }}</small>
                                </td>
                                <td>{{ $pm['supplier_name'] }}</td>
                                <td class="fw-bold en-font">{{ number_format($pm['quantity_used']) }} {{ $pm['unit'] }}</td>
                                <td><span class="badge bg-success-subtle text-success"><i class="bi bi-check-circle me-1"></i>معتمد غذائياً</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Step 3: Production Operations & Efficiency -->
        <div class="col-lg-6">
            <div class="premium-card h-100">
                <div class="card-header-clean bg-light bg-opacity-50">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px;">3</div>
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-gear-wide-connected text-warning me-1"></i> بيانات تشغيل المصنع والصالة</h6>
                    </div>
                    <span class="badge bg-light text-dark border">وردية {{ $b->shift }}</span>
                </div>
                <div class="p-4 d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between p-2 rounded bg-light">
                        <span class="text-muted">خط الإنتاج:</span>
                        <strong class="text-dark">{{ $b->production_line }}</strong>
                    </div>
                    <div class="d-flex justify-content-between p-2 rounded bg-light">
                        <span class="text-muted">المشرف المسؤول:</span>
                        <strong class="text-dark">{{ $b->production_supervisor ?? 'مهندس الصالة' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between p-2 rounded bg-light">
                        <span class="text-muted">الكمية المخططة / الفعلية:</span>
                        <strong class="text-dark en-font">{{ number_format($b->actual_quantity) }} / {{ number_format($b->planned_quantity) }} KG</strong>
                    </div>
                    <div class="d-flex justify-content-between p-2 rounded bg-light">
                        <span class="text-muted">عدد الكراتين المعبأة:</span>
                        <strong class="text-dark en-font">{{ $b->cartons_produced }} كرتونة ({{ $b->units_per_carton }} عبوة/كرتون)</strong>
                    </div>
                    <div class="d-flex justify-content-between p-2 rounded bg-light">
                        <span class="text-muted">نسبة الإنتاجية (Efficiency Yield):</span>
                        <strong class="text-success en-font">{{ $b->productivity_yield }}%</strong>
                    </div>
                    <div class="d-flex justify-content-between p-2 rounded bg-light">
                        <span class="text-muted">كمية الهالك (Waste/Scrap):</span>
                        <strong class="text-danger en-font">{{ $b->waste_quantity }} KG ({{ $b->scrap_percentage }}%)</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 4: Quality Control & Lab Release Sign-off -->
        <div class="col-lg-6">
            <div class="premium-card h-100">
                <div class="card-header-clean bg-light bg-opacity-50">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px;">4</div>
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-shield-check text-success me-1"></i> فحص الجودة والإفراج المخبري</h6>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle">معتمد نهائياً</span>
                </div>
                <div class="p-4">
                    @forelse($b->inspections as $ins)
                    <div class="mb-3 p-3 border rounded-3 bg-light bg-opacity-50">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold text-dark"><i class="bi bi-clipboard-check text-success me-1"></i> {{ $ins->inspection_type }} ({{ $ins->inspection_number }})</span>
                            {!! $ins->result_badge !!}
                        </div>
                        <div class="small text-muted mb-2">الفاحص: <strong>{{ $ins->inspector }}</strong> | التاريخ: <strong>{{ $ins->inspection_date?->format('Y-m-d') }}</strong></div>

                        <div class="table-responsive">
                            <table class="table table-sm table-bordered bg-white mb-0" style="font-size: 0.8rem;">
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
                                                <span class="text-success fw-bold"><i class="bi bi-check"></i> ناجح</span>
                                            @else
                                                <span class="text-danger fw-bold"><i class="bi bi-x"></i> راسب</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">لم يتم تسجيل فحص مخبري لهذه الدفعة بعد.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection
