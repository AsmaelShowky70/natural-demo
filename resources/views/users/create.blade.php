@extends('layouts.app')

@section('title', 'إضافة مستخدم جديد')
@section('page_title', 'إضافة مستخدم جديد للنظام (New User)')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="premium-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                <div>
                    <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-person-plus-fill text-success me-2"></i>إضافة مستخدم وتحديد الصلاحيات</h5>
                    <small class="text-muted">منح الصلاحيات بناءً على الدور الوظيفي في المصنع</small>
                </div>
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-light border">
                    <i class="bi bi-arrow-right me-1"></i> العودة للقائمة
                </a>
            </div>

            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">الاسم بالعربية <span class="text-danger">*</span></label>
                        <input type="text" name="name_ar" class="form-control" placeholder="مثال: م. فهد العتيبي" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">الاسم بالإنجليزية <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Fahad Al-Otaibi" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">البريد الإلكتروني <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control en-font" placeholder="fahad@naturalsnacks.com" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">كلمة المرور الأولية <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" value="Admin@123456" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">الدور الوظيفي والصلاحيات <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required>
                            <option value="production_manager">مدير الإنتاج والتصنيع (Production Manager)</option>
                            <option value="quality_control">مراقبة وتوكيد الجودة (Quality Control)</option>
                            <option value="warehouse">أمين ومشرف المستودعات (Warehouse)</option>
                            <option value="viewer">مدقق ومراجع تقارير (Auditor / Viewer)</option>
                            <option value="admin">الإدارة العليا (Top Admin)</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">القسم <span class="text-danger">*</span></label>
                        <select name="department" class="form-select" required>
                            <option value="production">صالة الإنتاج (Production)</option>
                            <option value="quality">مختبر الجودة (Quality)</option>
                            <option value="warehouse">المستودعات وسلاسل الإمداد (Warehouse)</option>
                            <option value="management">الإدارة العامة (Management)</option>
                            <option value="audit">التدقيق والمراجعة (Audit)</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('users.index') }}" class="btn btn-light border px-4">إلغاء</a>
                    <button type="submit" class="btn btn-ns-primary px-5">
                        <i class="bi bi-check-lg me-1"></i> حفظ وتفعيل المستخدم
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
