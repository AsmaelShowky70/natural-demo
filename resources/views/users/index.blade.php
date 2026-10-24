@extends('layouts.app')

@section('title', 'إدارة المستخدمين والصلاحيات')
@section('page_title', 'إدارة المستخدمين والصلاحيات (Users & RBAC Roles)')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Top Action Card -->
    <div class="premium-card p-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-people-fill text-success me-2"></i>إدارة مستخدمي وأقسام المصنع</h5>
                <small class="text-muted">التحكم في أدوار وصلاحيات الوصول لأقسام الإدارة العليا، المستودع، الإنتاج، والجودة</small>
            </div>
            <a href="{{ route('users.create') }}" class="btn btn-sm btn-ns-primary">
                <i class="bi bi-person-plus-fill me-1"></i> إضافة مستخدم جديد
            </a>
        </div>
    </div>

    <!-- Users Table Card -->
    <div class="premium-card">
        <div class="card-header-clean bg-light bg-opacity-50">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-shield-lock text-primary me-2"></i>حسابات المستخدمين النشطة</h6>
            <span class="badge bg-light text-dark border">إجمالي {{ $users->total() }} مستخدم</span>
        </div>
        <div class="table-responsive">
            <table class="table table-premium mb-0">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الدور الوظيفي (Role)</th>
                        <th>القسم التابع له</th>
                        <th>الحالة</th>
                        <th>تاريخ الإنشاء</th>
                        <th class="text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-circle rounded-circle bg-dark text-white d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    {{ mb_substr($u->name_ar ?? $u->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $u->name_ar ?? $u->name }}</div>
                                    <small class="text-muted">{{ $u->name }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="en-font">{{ $u->email }}</td>
                        <td>
                            @if($u->role === 'admin')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle"><i class="bi bi-shield-lock me-1"></i>الإدارة العليا</span>
                            @elseif($u->role === 'production_manager')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle"><i class="bi bi-gear me-1"></i>مدير الإنتاج</span>
                            @elseif($u->role === 'quality_control')
                                <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-check2-circle me-1"></i>مراقبة الجودة</span>
                            @elseif($u->role === 'warehouse')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle"><i class="bi bi-box me-1"></i>أمين المستودع</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle"><i class="bi bi-search me-1"></i>مدقق ومراجع</span>
                            @endif
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $u->department }}</span></td>
                        <td>
                            @if($u->is_active)
                                <span class="badge bg-success-subtle text-success">مفعل</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">معطل</span>
                            @endif
                        </td>
                        <td>{{ $u->created_at?->format('Y-m-d') }}</td>
                        <td class="text-center">
                            <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-light border" title="تعديل"><i class="bi bi-pencil"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="p-3 border-top">
            {{ $users->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
