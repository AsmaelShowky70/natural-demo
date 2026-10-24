@extends('layouts.app')

@section('title', 'سندات استلام التوريدات (GRN)')
@section('page_title', 'استلام التوريدات وسندات الإدخال المخزني (Goods Receiving Notes - GRN)')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Top Action Bar -->
    <div class="premium-card p-4">
        <div class="row g-3 align-items-center justify-content-between">
            <div class="col-lg-7">
                <form action="{{ route('receiving.index') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" style="max-width: 300px;" placeholder="بحث برقم السند أو الشاحنة أو السائق..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-search"></i> بحث</button>
                    @if(request('search'))
                        <a href="{{ route('receiving.index') }}" class="btn btn-sm btn-outline-secondary">إلغاء</a>
                    @endif
                </form>
            </div>
            <div class="col-lg-5 text-lg-end">
                <a href="{{ route('receiving.create') }}" class="btn btn-sm btn-ns-primary">
                    <i class="bi bi-plus-circle me-1"></i> تسجيل سند استلام توريد جديد (GRN)
                </a>
            </div>
        </div>
    </div>

    <!-- GRN Table Card -->
    <div class="premium-card">
        <div class="card-header-clean bg-light bg-opacity-50">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-truck-flatbed text-success me-2"></i>سجل استلام التوريدات واللوتات الواردة</h6>
            <span class="badge bg-light text-dark border">إجمالي {{ $records->total() }} سند</span>
        </div>
        <div class="table-responsive">
            <table class="table table-premium mb-0">
                <thead>
                    <tr>
                        <th>رقم السند (GRN #)</th>
                        <th>المورد المعتمد</th>
                        <th>نوع التوريد</th>
                        <th>تاريخ الاستلام</th>
                        <th>رقم الشاحنة / السائق</th>
                        <th>مستلم الشحنة</th>
                        <th>عدد الأصناف واللوتات</th>
                        <th>الحالة</th>
                        <th class="text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $rec)
                    <tr>
                        <td class="fw-bold en-font text-dark">
                            <a href="{{ route('receiving.show', $rec) }}" class="text-decoration-none">
                                {{ $rec->receiving_number }}
                            </a>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $rec->supplier?->name_ar ?? $rec->supplier?->name }}</div>
                            <small class="text-muted">{{ $rec->supplier?->country }}</small>
                        </td>
                        <td>
                            @if($rec->receiving_type === 'raw_material')
                                <span class="badge bg-success-subtle text-success border border-success-subtle">مواد خام</span>
                            @else
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">مواد تعبئة</span>
                            @endif
                        </td>
                        <td>{{ $rec->receiving_date?->format('Y-m-d') }}</td>
                        <td>
                            <div>{{ $rec->vehicle_number ?? 'غير مسجل' }}</div>
                            <small class="text-muted">{{ $rec->driver_name }}</small>
                        </td>
                        <td>{{ $rec->received_by }}</td>
                        <td><span class="badge bg-light text-dark border en-font">{{ count($rec->items) }} لوتات</span></td>
                        <td><span class="badge bg-success-subtle text-success"><i class="bi bi-check-circle me-1"></i>مكتمل</span></td>
                        <td class="text-center">
                            <a href="{{ route('receiving.show', $rec) }}" class="btn btn-sm btn-light border" title="معاينة السند واللوتات">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">لا توجد سندات استلام مسجلة.</td>
                    </tr>
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
