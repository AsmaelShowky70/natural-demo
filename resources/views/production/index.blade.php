@extends('layouts.app')

@section('title', 'دفعات الإنتاج')
@section('page_title', 'إدارة ومتابعة تشغيل دفعات الإنتاج (Production Batches)')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Top Action Bar & Filter -->
    <div class="premium-card p-4">
        <div class="row g-3 align-items-center justify-content-between">
            <div class="col-lg-8">
                <form action="{{ route('production.index') }}" method="GET" class="d-flex flex-wrap gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" style="max-width: 250px;" placeholder="بحث برقم الدفعة أو المشرف..." value="{{ request('search') }}">
                    <select name="status" class="form-select form-select-sm" style="max-width: 170px;">
                        <option value="">كافة الحالات</option>
                        <option value="released" {{ request('status') === 'released' ? 'selected' : '' }}>مفرج عنه (Released)</option>
                        <option value="in_production" {{ request('status') === 'in_production' ? 'selected' : '' }}>قيد التصنيع (In Production)</option>
                        <option value="qc_pending" {{ request('status') === 'qc_pending' ? 'selected' : '' }}>بانتظار الجودة (QC Pending)</option>
                        <option value="on_hold" {{ request('status') === 'on_hold' ? 'selected' : '' }}>معلق (On Hold)</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>مرفوض (Rejected)</option>
                    </select>
                    <select name="shift" class="form-select form-select-sm" style="max-width: 130px;">
                        <option value="">كافة الورديات</option>
                        <option value="A" {{ request('shift') === 'A' ? 'selected' : '' }}>وردية A</option>
                        <option value="B" {{ request('shift') === 'B' ? 'selected' : '' }}>وردية B</option>
                        <option value="C" {{ request('shift') === 'C' ? 'selected' : '' }}>وردية C</option>
                        <option value="Night" {{ request('shift') === 'Night' ? 'selected' : '' }}>ليلية</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-filter"></i> تصفية</button>
                    @if(request()->hasAny(['search', 'status', 'shift']))
                        <a href="{{ route('production.index') }}" class="btn btn-sm btn-outline-secondary">إلغاء</a>
                    @endif
                </form>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('production.create') }}" class="btn btn-sm btn-ns-primary">
                    <i class="bi bi-plus-circle me-1"></i> إنشاء أمر تشغيل دفعة جديد
                </a>
            </div>
        </div>
    </div>

    <!-- Batches Table Card -->
    <div class="premium-card">
        <div class="card-header-clean bg-light bg-opacity-50">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-boxes text-success me-2"></i>سجل أوامر التشغيل والإنتاج</h6>
            <span class="badge bg-light text-dark border">إجمالي {{ $batches->total() }} أمر إنتاج</span>
        </div>
        <div class="table-responsive">
            <table class="table table-premium mb-0">
                <thead>
                    <tr>
                        <th>كود الدفعة (Batch Code)</th>
                        <th>المنتج</th>
                        <th>تاريخ الإنتاج</th>
                        <th>الوردية والخط</th>
                        <th>الكمية المخططة / الفعلية</th>
                        <th>الكراتين المنتجة</th>
                        <th>نسبة الإنتاجية (Yield)</th>
                        <th>حالة الجودة</th>
                        <th>حالة الدفعة</th>
                        <th class="text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batches as $b)
                    <tr>
                        <td>
                            <a href="{{ route('production.show', $b) }}" class="fw-bold en-font text-dark text-decoration-none">
                                {{ $b->batch_id }}
                            </a>
                            <div class="text-muted en-font" style="font-size: 0.72rem;">{{ $b->finished_goods_barcode }}</div>
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
                            <span class="en-font fw-bold text-success">{{ $b->productivity_yield }}%</span>
                            @if($b->waste_quantity > 0)
                                <div class="text-danger" style="font-size: 0.72rem;">هالك: {{ $b->waste_quantity }} KG</div>
                            @endif
                        </td>
                        <td>
                            @if($b->qc_status === 'passed')
                                <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-check-circle me-1"></i>مجاز (Pass)</span>
                            @elseif($b->qc_status === 'failed')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle"><i class="bi bi-x-circle me-1"></i>مرفوض</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle"><i class="bi bi-clock me-1"></i>قيد الفحص</span>
                            @endif
                        </td>
                        <td>{!! $b->status_badge !!}</td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('production.show', $b) }}" class="btn btn-light border" title="معاينة التفاصيل والتتبع">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('production.traveler', $b) }}" target="_blank" class="btn btn-light border text-primary" title="طباعة كارت التشغيل Traveler">
                                    <i class="bi bi-printer"></i>
                                </a>
                                <a href="{{ route('trace.public', $b->batch_id) }}" target="_blank" class="btn btn-light border text-success" title="صفحة التتبع العامة للمستهلك">
                                    <i class="bi bi-qr-code"></i>
                                </a>
                                <button type="button" class="btn btn-light border text-secondary" data-bs-toggle="modal" data-bs-target="#updateModal{{ $b->id }}" title="تحديث الفعلي والهالك">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </div>

                            <!-- Update Output Modal -->
                            <div class="modal fade" id="updateModal{{ $b->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 border-0 shadow">
                                        <div class="modal-header">
                                            <h6 class="modal-title fw-bold">تحديث مخرجات الإنتاج الفعلي: {{ $b->batch_id }}</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('production.actuals', $b) }}" method="POST">
                                            @csrf
                                            <div class="modal-body text-start">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold">الكمية الفعلية المنتجة (KG)</label>
                                                    <input type="number" step="0.01" name="actual_quantity" class="form-control" value="{{ $b->actual_quantity }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold">عدد الكراتين المعبأة</label>
                                                    <input type="number" name="cartons_produced" class="form-control" value="{{ $b->cartons_produced }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold">كمية الهالك (Waste/Scrap KG)</label>
                                                    <input type="number" step="0.01" name="waste_quantity" class="form-control" value="{{ $b->waste_quantity }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold">حالة التشغيل</label>
                                                    <select name="batch_status" class="form-select">
                                                        <option value="in_production" {{ $b->batch_status === 'in_production' ? 'selected' : '' }}>قيد التصنيع (In Production)</option>
                                                        <option value="qc_pending" {{ $b->batch_status === 'qc_pending' ? 'selected' : '' }}>جاهز لفحص الجودة (QC Pending)</option>
                                                        <option value="completed" {{ $b->batch_status === 'completed' ? 'selected' : '' }}>مكتمل التصنيع (Completed)</option>
                                                        <option value="on_hold" {{ $b->batch_status === 'on_hold' ? 'selected' : '' }}>معلق (On Hold)</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">إلغاء</button>
                                                <button type="submit" class="btn btn-sm btn-success">حفظ التحديث</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">لا توجد أوامر تشغيل تطابق معايير البحث.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($batches->hasPages())
        <div class="p-3 border-top">
            {{ $batches->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
