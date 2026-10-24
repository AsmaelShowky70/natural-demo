@extends('layouts.app')

@section('title', 'تعديل بيانات المورد - ' . ($supplier->name_ar ?? $supplier->name))
@section('page_title', 'تعديل بيانات المورد المعتمد (Edit Supplier)')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="premium-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                <div>
                    <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-pencil-square text-success me-2"></i>تعديل بيانات المورد: {{ $supplier->name_ar ?? $supplier->name }}</h5>
                    <small class="text-muted">تحديث جهات الاتصال والعنوان وتصريح التوريد</small>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-eye me-1"></i> عرض السجل
                    </a>
                    <a href="{{ route('suppliers.index') }}" class="btn btn-sm btn-light border">
                        <i class="bi bi-arrow-right me-1"></i> العودة للقائمة
                    </a>
                </div>
            </div>

            <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">اسم الشركة / المورد بالعربية <span class="text-danger">*</span></label>
                        <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar', $supplier->name_ar) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">اسم المورد بالإنجليزية <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $supplier->name) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">كود المورد الفريد (Code) <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control en-font" value="{{ old('code', $supplier->code) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">مسؤول الاتصال</label>
                        <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person', $supplier->contact_person) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">الدولة</label>
                        <input type="text" name="country" class="form-control" value="{{ old('country', $supplier->country) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $supplier->email) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">رقم الهاتف</label>
                        <input type="text" name="phone" class="form-control en-font" value="{{ old('phone', $supplier->phone) }}">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label small fw-bold">العنوان ومقر المستودعات</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', $supplier->address) }}">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label small fw-bold">ملاحظات واعتمادات الجودة</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $supplier->notes) }}</textarea>
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch p-0 pe-4 mt-2">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input ms-2" type="checkbox" name="is_active" id="supplierActiveCheck" value="1" {{ old('is_active', $supplier->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold small" for="supplierActiveCheck">المورد معتمد ونشط لاستلام شحنات التوريد</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-light px-4">إلغاء</a>
                    <button type="submit" class="btn btn-success px-4 fw-bold">
                        <i class="bi bi-check2-circle me-1"></i> حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
