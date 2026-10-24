@extends('layouts.app')

@section('title', 'تعريف مادة خام جديدة')
@section('page_title', 'تعريف مادة خام جديدة في المخزون (New Raw Material)')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="premium-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                <div>
                    <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-boxes text-success me-2"></i>إضافة مادة خام جديدة</h5>
                    <small class="text-muted">تسجيل بيانات الخامة، شروط التخزين، والمواصفات القياسية</small>
                </div>
                <a href="{{ route('raw-materials.index') }}" class="btn btn-sm btn-light border">
                    <i class="bi bi-arrow-right me-1"></i> العودة للقائمة
                </a>
            </div>

            <form action="{{ route('raw-materials.store') }}" method="POST">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">اسم المادة الخام بالعربية <span class="text-danger">*</span></label>
                        <input type="text" name="name_ar" class="form-control" placeholder="مثال: حبوب كينوا بيضاء" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">اسم المادة بالإنجليزية (English) <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. White Quinoa Seeds" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">كود المادة (Material Code) <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control en-font" placeholder="RM-QU-001" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">نوع المادة الخام</label>
                        <select name="material_type" class="form-select">
                            <option value="Grain">حبوب (Grain)</option>
                            <option value="Seed">بذور (Seed)</option>
                            <option value="Additive">مضافات غذائية (Additive)</option>
                            <option value="Spice">بهارات وتوابل (Spice)</option>
                            <option value="Sweetener">محليات وعسل (Sweetener)</option>
                            <option value="Other">أخرى</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">المورد المعتمد الافتراضي</label>
                        <select name="supplier_id" class="form-select">
                            <option value="">-- اختياري --</option>
                            @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->name_ar ?? $sup->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">وحدة القياس <span class="text-danger">*</span></label>
                        <input type="text" name="unit" class="form-control" value="KG" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">الحد الأدنى للمخزون (Min Stock Alert)</label>
                        <input type="number" step="0.01" name="minimum_stock" class="form-control" value="50">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label small fw-bold">المواصفات القياسية وشروط التخزين</label>
                        <textarea name="specification" rows="3" class="form-control" placeholder="درجة نقاء، أقصى نسبة رطوبة مقبولة، درجة حرارة التخزين..."></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('raw-materials.index') }}" class="btn btn-light border px-4">إلغاء</a>
                    <button type="submit" class="btn btn-ns-primary px-5">
                        <i class="bi bi-check-lg me-1"></i> حفظ المادة الخام
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
