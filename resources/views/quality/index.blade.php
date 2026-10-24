@extends('layouts.app')

@section('title', 'فحوصات الجودة والمختبر')
@section('page_title', 'مراقبة وتوكيد الجودة وفحوصات المختبر (QC & QA Laboratory)')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Urgent Pending Inspection Queue -->
    @if(count($pendingNotifications) > 0)
    <div class="premium-card p-4 border-warning border-opacity-50 bg-warning-subtle bg-opacity-15">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-bell-fill text-warning fs-4"></i>
                <h6 class="fw-bold mb-0 text-dark">قائمة الفحص العاجل الواردة من الإنتاج والمستودع (Pending Queue)</h6>
            </div>
            <span class="badge bg-warning text-dark">{{ count($pendingNotifications) }} مهام فحص بانتظار الاعتماد</span>
        </div>
        <div class="row g-2">
            @foreach($pendingNotifications as $noti)
            <div class="col-md-6">
                <div class="p-3 bg-white rounded-3 border d-flex align-items-center justify-content-between shadow-sm">
                    <div>
                        <div class="fw-bold text-dark small">{{ $noti->title_ar }}</div>
                        <p class="text-muted small mb-0">{{ $noti->message_ar }}</p>
                    </div>
                    <a href="{{ route('quality.create', ['target' => ($noti->type === 'incoming_raw' ? 'raw_material' : 'finished_goods'), 'target_id' => $noti->target_id]) }}" class="btn btn-sm btn-success rounded-pill px-3 text-nowrap ms-2">
                        <i class="bi bi-eyedropper me-1"></i> فحص الآن
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Top Action Bar & Filter -->
    <div class="premium-card p-4">
        <div class="row g-3 align-items-center justify-content-between">
            <div class="col-lg-8">
                <form action="{{ route('quality.index') }}" method="GET" class="d-flex flex-wrap gap-2">
                    <select name="result" class="form-select form-select-sm" style="max-width: 180px;">
                        <option value="">كافة النتائج</option>
                        <option value="pass" {{ request('result') === 'pass' ? 'selected' : '' }}>ناجح (PASS)</option>
                        <option value="fail" {{ request('result') === 'fail' ? 'selected' : '' }}>راسب (FAIL)</option>
                        <option value="hold" {{ request('result') === 'hold' ? 'selected' : '' }}>معلق (HOLD)</option>
                    </select>
                    <select name="target" class="form-select form-select-sm" style="max-width: 200px;">
                        <option value="">كافة أهداف الفحص</option>
                        <option value="finished_goods" {{ request('target') === 'finished_goods' ? 'selected' : '' }}>منتج نهائي (Finished Goods)</option>
                        <option value="raw_material" {{ request('target') === 'raw_material' ? 'selected' : '' }}>مواد خام (Raw Material)</option>
                        <option value="packaging" {{ request('target') === 'packaging' ? 'selected' : '' }}>مواد تعبئة (Packaging)</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-filter"></i> تصفية</button>
                    @if(request()->hasAny(['result', 'target']))
                        <a href="{{ route('quality.index') }}" class="btn btn-sm btn-outline-secondary">إلغاء</a>
                    @endif
                </form>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('quality.create') }}" class="btn btn-sm btn-ns-primary">
                    <i class="bi bi-plus-circle me-1"></i> تسجيل فحص مخبري جديد
                </a>
            </div>
        </div>
    </div>

    <!-- Inspections Table Card -->
    <div class="premium-card">
        <div class="card-header-clean bg-light bg-opacity-50">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-clipboard2-check text-success me-2"></i>سجل نتائج التحاليل المخبرية المعتمدة</h6>
            <span class="badge bg-light text-dark border">إجمالي {{ $inspections->total() }} فحص</span>
        </div>
        <div class="table-responsive">
            <table class="table table-premium mb-0">
                <thead>
                    <tr>
                        <th>رقم الفحص (QC #)</th>
                        <th>الهدف المفحوص</th>
                        <th>الكود أو رقم الدفعة</th>
                        <th>نوع التحليل</th>
                        <th>الفاحص المسؤول</th>
                        <th>تاريخ الفحص</th>
                        <th>الرطوبة المقاسة</th>
                        <th>النتيجة الإجمالية</th>
                        <th class="text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inspections as $ins)
                    <tr>
                        <td class="fw-bold en-font text-dark"><a href="{{ route('quality.show', $ins) }}" class="text-decoration-none">{{ $ins->inspection_number }}</a></td>
                        <td>
                            @if($ins->inspection_target === 'finished_goods')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">منتج نهائي</span>
                            @elseif($ins->inspection_target === 'raw_material')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle">مادة خام</span>
                            @else
                                <span class="badge bg-info-subtle text-info border border-info-subtle">مواد تعبئة</span>
                            @endif
                        </td>
                        <td class="en-font fw-bold text-dark">
                            @if($ins->batch)
                                <a href="{{ route('production.show', $ins->batch) }}" class="text-decoration-none">{{ $ins->batch->batch_id }}</a>
                            @elseif($ins->rawMaterialLot)
                                {{ $ins->rawMaterialLot->lot_number }}
                            @else
                                {{ $ins->packagingMaterialLot?->lot_number }}
                            @endif
                        </td>
                        <td>{{ $ins->inspection_type }}</td>
                        <td>{{ $ins->inspector }}</td>
                        <td>{{ $ins->inspection_date?->format('Y-m-d') }}</td>
                        <td>
                            @if($ins->moisture_percentage)
                                <span class="badge bg-light text-dark border en-font">{{ $ins->moisture_percentage }}%</span>
                            @else
                                -
                            @endif
                        </td>
                        <td>{!! $ins->result_badge !!}</td>
                        <td class="text-center">
                            <a href="{{ route('quality.show', $ins) }}" class="btn btn-sm btn-light border" title="معاينة تقرير الفحص">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">لا توجد سجلات فحص جودة مسجلة تطابق الشروط.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($inspections->hasPages())
        <div class="p-3 border-top">
            {{ $inspections->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
