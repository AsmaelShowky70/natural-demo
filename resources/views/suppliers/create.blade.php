@extends('layouts.app')

@section('title', 'تسجيل مورد جديد')
@section('page_title', 'تسجيل مورد معتمد جديد (New Supplier)')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="premium-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                <div>
                    <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-buildings-fill text-success me-2"></i>تسجيل مورد جديد</h5>
                    <small class="text-muted">بيانات المورد، جهات الاتصال، والسجل التجاري</small>
                </div>
                <a href="{{ route('suppliers.index') }}" class="btn btn-sm btn-light border">
                    <i class="bi bi-arrow-right me-1"></i> العودة للقائمة
                </a>
            </div>

            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">اسم الشركة / المورد بالعربية <span class="text-danger">*</span></label>
                        <input type="text" name="name_ar" class="form-control" placeholder="مثال: شركة الخير للتجارة الزراعية" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">اسم المورد بالإنجليزية <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Al-Khair Agri Trading Co." required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">كود المورد الفريد (Code) <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control en-font" placeholder="SUP-006" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">مسؤول الاتصال</label>
                        <input type="text" name="contact_person" class="form-control" placeholder="اسم مسؤول المبيعات / الحساب">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">الدولة</label>
                        <input type="text" name="country" class="form-control" placeholder="السعودية، مصر، الإمارات...">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control" placeholder="supplier@example.com">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">رقم الهاتف</label>
                        <input type="text" name="phone" class="form-control en-font" placeholder="+966...">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label small fw-bold">العنوان ومقر المستودعات</label>
                        <input type="text" name="address" class="form-control" placeholder="المدينة، المنطقة الصناعية، الشارع...">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label small fw-bold">ملاحظات واعتمادات الجودة</label>
                        <textarea name="notes" rows="2" class="form-control" placeholder="شهادات الجودة (HACCP / ISO)، تقييم المورد..."></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('suppliers.index') }}" class="btn btn-light border px-4">إلغاء</a>
                    <button type="submit" class="btn btn-ns-primary px-5">
                        <i class="bi bi-check-lg me-1"></i> حفظ وتثبيت المورد
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
