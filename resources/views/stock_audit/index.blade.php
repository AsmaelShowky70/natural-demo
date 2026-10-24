@extends('layouts.app')

@section('title', 'الجرد المخزني الدوري')
@section('page_title', 'الجرد المخزني الفعلي ومطابقة الأرصدة (Physical Stock Audit)')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Top Action Card -->
    <div class="premium-card p-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-card-checklist text-success me-2"></i>محاضر الجرد المخزني والتسويات</h5>
                <small class="text-muted">مقارنة الرصيد الفعلي في عنابر التخزين مع الرصيد الدفتري المسجل واحتساب فروقات العجز والزيادة</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('stock-audit.create', ['warehouse_type' => 'raw_materials']) }}" class="btn btn-sm btn-ns-primary">
                    <i class="bi bi-plus-circle me-1"></i> جرد مخزن المواد الخام
                </a>
                <a href="{{ route('stock-audit.create', ['warehouse_type' => 'packaging']) }}" class="btn btn-sm btn-outline-dark">
                    <i class="bi bi-archive me-1"></i> جرد مواد التعبئة
                </a>
                <a href="{{ route('stock-audit.create', ['warehouse_type' => 'finished_goods']) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-box-seam me-1"></i> جرد كراتين المنتج النهائي
                </a>
            </div>
        </div>
    </div>

    <!-- Audits Table Card -->
    <div class="premium-card">
        <div class="card-header-clean bg-light bg-opacity-50">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-clipboard-data text-primary me-2"></i>سجل محاضر الجرد الدوري</h6>
            <span class="badge bg-light text-dark border">إجمالي {{ $records->total() }} محضر جرد</span>
        </div>
        <div class="table-responsive">
            <table class="table table-premium mb-0">
                <thead>
                    <tr>
                        <th>رقم محضر الجرد (Audit #)</th>
                        <th>المستودع المفحوص</th>
                        <th>تاريخ الجرد</th>
                        <th>اسم المدقق / رئيس لجنة الجرد</th>
                        <th>عدد الأصناف واللوتات</th>
                        <th>الحالة</th>
                        <th class="text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $rec)
                    <tr>
                        <td class="fw-bold en-font text-dark"><a href="{{ route('stock-audit.show', $rec) }}" class="text-decoration-none">{{ $rec->audit_number }}</a></td>
                        <td>
                            @if($rec->warehouse_type === 'raw_materials')
                                <span class="badge bg-success-subtle text-success border border-success-subtle">مستودع المواد الخام</span>
                            @elseif($rec->warehouse_type === 'packaging')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">مستودع مواد التعبئة</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle">مستودع المنتج النهائي</span>
                            @endif
                        </td>
                        <td>{{ $rec->audit_date?->format('Y-m-d') }}</td>
                        <td>{{ $rec->auditor_name }}</td>
                        <td><span class="badge bg-light text-dark border en-font">{{ count($rec->items) }} عناصر</span></td>
                        <td><span class="badge bg-success-subtle text-success"><i class="bi bi-check-circle me-1"></i>معتمد ومطابق</span></td>
                        <td class="text-center">
                            <a href="{{ route('stock-audit.show', $rec) }}" class="btn btn-sm btn-light border" title="معاينة محضر الجرد">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">لا توجد محاضر جرد مسجلة حتى الآن.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($records->hasPages())
        <div class="p-3 border-top">
            {{ $records->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
