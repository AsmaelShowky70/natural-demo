@extends('layouts.app')

@section('title', 'صرف خامات لصالة الإنتاج')
@section('page_title', 'أوامر صرف المواد الخام لصالة وخطوط الإنتاج (Material Issue Orders)')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Top Action Card -->
    <div class="premium-card p-4">
        <div class="row g-3 align-items-center justify-content-between">
            <div class="col-lg-7">
                <form action="{{ route('material-issues.index') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" style="max-width: 300px;" placeholder="بحث برقم الإذن أو الخط أو المستلم..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-search"></i> بحث</button>
                    @if(request('search'))
                        <a href="{{ route('material-issues.index') }}" class="btn btn-sm btn-outline-secondary">إلغاء</a>
                    @endif
                </form>
            </div>
            <div class="col-lg-5 text-lg-end">
                <button type="button" class="btn btn-sm btn-ns-primary" data-bs-toggle="modal" data-bs-target="#newIssueModal">
                    <i class="bi bi-arrow-left-right me-1"></i> صرف كمية خامة جديدة للإنتاج
                </button>
            </div>
        </div>
    </div>

    <!-- Active Approved Stock in Warehouse -->
    <div class="premium-card">
        <div class="card-header-clean bg-light bg-opacity-50">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-check-circle-fill text-success me-2"></i>اللوتات المعتمدة الجاهزة للصرف من المستودع</h6>
            <span class="badge bg-success-subtle text-success">{{ count($approvedLots) }} لوتات متاحة</span>
        </div>
        <div class="p-3">
            <div class="row g-2">
                @foreach($approvedLots as $al)
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between">
                        <div>
                            <strong class="d-block text-dark small">{{ $al->rawMaterial?->name_ar }}</strong>
                            <span class="en-font small text-primary fw-bold">{{ $al->lot_number }}</span>
                            <div class="text-muted small">المتاح: <strong class="text-dark en-font">{{ number_format($al->available_quantity) }} {{ $al->unit }}</strong></div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3" onclick="prepareIssueModal('{{ $al->id }}', '{{ $al->lot_number }}', '{{ $al->available_quantity }}')">
                            صرف <i class="bi bi-arrow-left"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Material Issue History Table -->
    <div class="premium-card">
        <div class="card-header-clean bg-light bg-opacity-50">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-clock-history text-primary me-2"></i>سجل أذونات الصرف السابقة للإنتاج</h6>
            <span class="badge bg-light text-dark border">إجمالي {{ $orders->total() }} إذن صرف</span>
        </div>
        <div class="table-responsive">
            <table class="table table-premium mb-0">
                <thead>
                    <tr>
                        <th>رقم الإذن (Issue #)</th>
                        <th>تاريخ الصرف</th>
                        <th>لوت الخامة المنصرف</th>
                        <th>اسم الخامة</th>
                        <th>الكمية المنصرفة</th>
                        <th>خط الإنتاج المستهدف</th>
                        <th>الصادر بواسطة</th>
                        <th>المستلم في الصالة</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $ord)
                    <tr>
                        <td class="fw-bold en-font text-dark">{{ $ord->issue_number }}</td>
                        <td>{{ $ord->issue_date?->format('Y-m-d') }}</td>
                        <td class="en-font fw-bold text-dark">{{ $ord->rawMaterialLot?->lot_number }}</td>
                        <td>{{ $ord->rawMaterialLot?->rawMaterial?->name_ar }}</td>
                        <td class="fw-bold text-success en-font">{{ number_format($ord->quantity) }} {{ $ord->unit }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $ord->target_line }}</span></td>
                        <td>{{ $ord->issued_by }}</td>
                        <td>{{ $ord->received_by }}</td>
                        <td><span class="badge bg-success-subtle text-success"><i class="bi bi-check-circle me-1"></i>تم الصرف والخصم</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">لا توجد أذونات صرف مسجلة حتى الآن.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
        <div class="p-3 border-top">
            {{ $orders->links() }}
        </div>
        @endif
    </div>

</div>

<!-- Modal: New Material Issue -->
<div class="modal fade" id="newIssueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header">
                <h6 class="modal-title fw-bold"><i class="bi bi-arrow-left-right text-success me-2"></i>إذن صرف خامة لصالة الإنتاج</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('material-issues.store') }}" method="POST">
                @csrf
                <div class="modal-body text-start">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">اختر لوت الخامة المراد صرفه <span class="text-danger">*</span></label>
                        <select name="raw_material_lot_id" id="modal_lot_select" class="form-select" required>
                            <option value="">-- اختر لوت معتمد --</option>
                            @foreach($approvedLots as $lot)
                            <option value="{{ $lot->id }}">
                                {{ $lot->rawMaterial?->name_ar }} | اللوت: {{ $lot->lot_number }} (المتاح: {{ $lot->available_quantity }} {{ $lot->unit }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">الكمية المصروفة (KG) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="quantity" class="form-control" placeholder="أدخل الكمية المصروفة..." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">خط الإنتاج المستهدف <span class="text-danger">*</span></label>
                        <select name="target_line" class="form-select" required>
                            <option value="Line 1 - Rice Cake Line">خط الإنتاج 1 (Rice Cake Line)</option>
                            <option value="Line 2 - Large Cake Line">خط الإنتاج 2 (Large Cake Line)</option>
                            <option value="Line 3 - Granola Bar Line">خط الإنتاج 3 (Granola Bar Line)</option>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">الصادر بواسطة (أمين المستودع) <span class="text-danger">*</span></label>
                            <input type="text" name="issued_by" class="form-control form-control-sm" value="{{ auth()->user()->name_ar ?? auth()->user()->name }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">المستلم (مشرف الصالة) <span class="text-danger">*</span></label>
                            <input type="text" name="received_by" class="form-control form-control-sm" placeholder="اسم المستلم" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">ملاحظات (اختياري)</label>
                        <textarea name="notes" rows="2" class="form-control form-control-sm" placeholder="ملاحظات الصرف..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-sm btn-success">تأكيد الصرف وخصم الرصيد</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function prepareIssueModal(lotId, lotNumber, availableQty) {
        const select = document.getElementById('modal_lot_select');
        select.value = lotId;
        const modal = new bootstrap.Modal(document.getElementById('newIssueModal'));
        modal.show();
    }
</script>
@endpush
