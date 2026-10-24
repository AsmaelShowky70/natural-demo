@extends('layouts.app')

@section('title', 'تعديل المستخدم')
@section('page_title', 'تعديل بيانات وصلاحيات المستخدم: ' . ($user->name_ar ?? $user->name))

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="premium-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                <div>
                    <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-pencil text-primary me-2"></i>تعديل بيانات المستخدم</h5>
                    <small class="text-muted">{{ $user->email }}</small>
                </div>
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-light border">
                    <i class="bi bi-arrow-right me-1"></i> العودة للقائمة
                </a>
            </div>

            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">الاسم بالعربية <span class="text-danger">*</span></label>
                        <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar', $user->name_ar) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">الاسم بالإنجليزية <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">البريد الإلكتروني <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control en-font" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">كلمة المرور الجديدة (اتركه فارغاً للإبقاء على الحالية)</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">الدور الوظيفي والصلاحيات <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required>
                            <option value="production_manager" {{ $user->role === 'production_manager' ? 'selected' : '' }}>مدير الإنتاج والتصنيع</option>
                            <option value="quality_control" {{ $user->role === 'quality_control' ? 'selected' : '' }}>مراقبة وتوكيد الجودة</option>
                            <option value="warehouse" {{ $user->role === 'warehouse' ? 'selected' : '' }}>أمين ومشرف المستودعات</option>
                            <option value="viewer" {{ $user->role === 'viewer' ? 'selected' : '' }}>مدقق ومراجع تقارير</option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>الإدارة العليا (Top Admin)</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">القسم <span class="text-danger">*</span></label>
                        <select name="department" class="form-select" required>
                            <option value="production" {{ $user->department === 'production' ? 'selected' : '' }}>صالة الإنتاج (Production)</option>
                            <option value="quality" {{ $user->department === 'quality' ? 'selected' : '' }}>مختبر الجودة (Quality)</option>
                            <option value="warehouse" {{ $user->department === 'warehouse' ? 'selected' : '' }}>المستودعات وسلاسل الإمداد (Warehouse)</option>
                            <option value="management" {{ $user->department === 'management' ? 'selected' : '' }}>الإدارة العامة (Management)</option>
                            <option value="audit" {{ $user->department === 'audit' ? 'selected' : '' }}>التدقيق والمراجعة (Audit)</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('users.index') }}" class="btn btn-light border px-4">إلغاء</a>
                    <button type="submit" class="btn btn-ns-primary px-5">
                        <i class="bi bi-check-lg me-1"></i> حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
