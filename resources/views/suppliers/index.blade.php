@extends('layouts.app')

@section('title', 'دليل الموردين المعتمدين')
@section('page_title', 'سجل ودليل الموردين المعتمدين (Approved Suppliers)')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Top Action Card -->
    <div class="premium-card p-4">
        <div class="row g-3 align-items-center justify-content-between">
            <div class="col-lg-7">
                <form action="{{ route('suppliers.index') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" style="max-width: 300px;" placeholder="بحث باسم المورد أو الكود أو مسؤول الاتصال..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-search"></i> بحث</button>
                    @if(request('search'))
                        <a href="{{ route('suppliers.index') }}" class="btn btn-sm btn-outline-secondary">إلغاء</a>
                    @endif
                </form>
            </div>
            <div class="col-lg-5 text-lg-end">
                <a href="{{ route('suppliers.create') }}" class="btn btn-sm btn-ns-primary">
                    <i class="bi bi-plus-circle me-1"></i> تسجيل مورد معتمد جديد
                </a>
            </div>
        </div>
    </div>

    <!-- Suppliers Table Card -->
    <div class="premium-card">
        <div class="card-header-clean bg-light bg-opacity-50">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-buildings-fill text-success me-2"></i>قائمة الموردين المعتمدين والشركاء الزراعيين</h6>
            <span class="badge bg-light text-dark border">إجمالي {{ $suppliers->total() }} مورد</span>
        </div>
        <div class="table-responsive">
            <table class="table table-premium mb-0">
                <thead>
                    <tr>
                        <th>كود المورد</th>
                        <th>اسم الشركة / المورد</th>
                        <th>مسؤول الاتصال</th>
                        <th>البريد الإلكتروني</th>
                        <th>الهاتف</th>
                        <th>الدولة</th>
                        <th>الخامات الموردة</th>
                        <th>إجمالي اللوتات</th>
                        <th>الحالة</th>
                        <th class="text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $sup)
                    <tr>
                        <td class="fw-bold en-font text-dark">{{ $sup->code }}</td>
                        <td>
                            <a href="{{ route('suppliers.show', $sup) }}" class="fw-semibold text-dark text-decoration-none">
                                {{ $sup->name_ar ?? $sup->name }}
                            </a>
                            <div class="text-muted small">{{ $sup->name }}</div>
                        </td>
                        <td>{{ $sup->contact_person ?? '-' }}</td>
                        <td class="en-font">{{ $sup->email ?? '-' }}</td>
                        <td class="en-font">{{ $sup->phone ?? '-' }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $sup->country ?? 'غير محدد' }}</span></td>
                        <td><span class="badge bg-success-subtle text-success">{{ $sup->raw_materials_count }} خامات</span></td>
                        <td><span class="badge bg-primary-subtle text-primary">{{ $sup->raw_material_lots_count }} شحنات</span></td>
                        <td>
                            @if($sup->is_active)
                                <span class="badge bg-success-subtle text-success">معتمد</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">معلق</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('suppliers.show', $sup) }}" class="btn btn-light border" title="معاينة"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('suppliers.edit', $sup) }}" class="btn btn-light border" title="تعديل"><i class="bi bi-pencil"></i></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="text-center py-4 text-muted">لا يوجد موردين مسجلين.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($suppliers->hasPages())
        <div class="p-3 border-top">
            {{ $suppliers->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
