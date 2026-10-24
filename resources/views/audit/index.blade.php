@extends('layouts.app')

@section('title', 'سجل التدقيق الرقمي')
@section('page_title', 'سجل التدقيق الرقمي والعمليات الرقابية (Enterprise Audit Trail)')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Top Action Card -->
    <div class="premium-card p-4">
        <div class="row g-3 align-items-center justify-content-between">
            <div class="col-lg-8">
                <form action="{{ route('audit.index') }}" method="GET" class="d-flex flex-wrap gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" style="max-width: 250px;" placeholder="بحث بالبريد أو رقم العنصر..." value="{{ request('search') }}">
                    <select name="action" class="form-select form-select-sm" style="max-width: 150px;">
                        <option value="">كافة العمليات</option>
                        <option value="CREATE" {{ request('action') === 'CREATE' ? 'selected' : '' }}>إنشاء (CREATE)</option>
                        <option value="UPDATE" {{ request('action') === 'UPDATE' ? 'selected' : '' }}>تعديل (UPDATE)</option>
                        <option value="RELEASE" {{ request('action') === 'RELEASE' ? 'selected' : '' }}>إفراج (RELEASE)</option>
                        <option value="ISSUE" {{ request('action') === 'ISSUE' ? 'selected' : '' }}>صرف (ISSUE)</option>
                        <option value="LOGIN" {{ request('action') === 'LOGIN' ? 'selected' : '' }}>دخول (LOGIN)</option>
                    </select>
                    <select name="entity_type" class="form-select form-select-sm" style="max-width: 180px;">
                        <option value="">كافة الجداول</option>
                        <option value="ProductionBatch" {{ request('entity_type') === 'ProductionBatch' ? 'selected' : '' }}>دفعات إنتاج</option>
                        <option value="RawMaterialLot" {{ request('entity_type') === 'RawMaterialLot' ? 'selected' : '' }}>لوتات خامات</option>
                        <option value="QualityInspection" {{ request('entity_type') === 'QualityInspection' ? 'selected' : '' }}>فحوصات جودة</option>
                        <option value="ReceivingRecord" {{ request('entity_type') === 'ReceivingRecord' ? 'selected' : '' }}>سندات استلام</option>
                        <option value="User" {{ request('entity_type') === 'User' ? 'selected' : '' }}>مستخدمين</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-filter"></i> تصفية</button>
                    @if(request()->hasAny(['search', 'action', 'entity_type']))
                        <a href="{{ route('audit.index') }}" class="btn btn-sm btn-outline-secondary">إلغاء</a>
                    @endif
                </form>
            </div>
            <div class="col-lg-4 text-lg-end">
                <span class="badge bg-light text-dark border p-2">
                    <i class="bi bi-shield-check text-success me-1"></i> متوافق مع معايير التدقيق الصناعي الصارم
                </span>
            </div>
        </div>
    </div>

    <!-- Audit Log Table -->
    <div class="premium-card">
        <div class="card-header-clean bg-light bg-opacity-50">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-fingerprint text-info me-2"></i>سجل الحركات الإدارية والتغييرات (Non-repudiation Log)</h6>
            <span class="badge bg-light text-dark border">إجمالي {{ $logs->total() }} حركة مسجلة</span>
        </div>
        <div class="table-responsive">
            <table class="table table-premium mb-0">
                <thead>
                    <tr>
                        <th>العملية (Action)</th>
                        <th>الجدول / العنصر</th>
                        <th>المعرف (ID)</th>
                        <th>المستخدم المسؤول</th>
                        <th>عنوان IP</th>
                        <th>وقت التسجيل</th>
                        <th>تفاصيل التغيير</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>
                            @if($log->action === 'CREATE' || $log->action === 'CREATE_USER')
                                <span class="badge bg-success-subtle text-success border border-success-subtle">{{ $log->action }}</span>
                            @elseif($log->action === 'RELEASE')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">RELEASED</span>
                            @elseif($log->action === 'ISSUE')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle">ISSUED</span>
                            @elseif($log->action === 'LOGIN' || $log->action === 'QUICK_LOGIN')
                                <span class="badge bg-info-subtle text-info border border-info-subtle">AUTH</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">{{ $log->action }}</span>
                            @endif
                        </td>
                        <td class="fw-bold text-dark">{{ $log->entity_type }}</td>
                        <td class="en-font">{{ $log->entity_id ?? '-' }}</td>
                        <td>
                            <div class="small fw-semibold text-dark">{{ $log->user?->name_ar ?? $log->user?->name ?? 'مستخدم' }}</div>
                            <small class="text-muted en-font">{{ $log->user_email }}</small>
                        </td>
                        <td class="en-font small text-muted">{{ $log->ip_address }}</td>
                        <td class="en-font small">{{ $log->created_at?->format('Y-m-d H:i:s') }}</td>
                        <td>
                            @if($log->new_value)
                                <button type="button" class="btn btn-sm btn-light border py-0 px-2" data-bs-toggle="collapse" data-bs-target="#logData{{ $log->id }}">
                                    عرض البيانات <i class="bi bi-chevron-down"></i>
                                </button>
                                <div class="collapse mt-2" id="logData{{ $log->id }}">
                                    <pre class="bg-dark text-white p-2 rounded small mb-0 en-font text-start" style="font-size: 0.72rem; max-width: 350px;">{{ json_encode($log->new_value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">لا توجد حركات مسجلة.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
        <div class="p-3 border-top">
            {{ $logs->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
