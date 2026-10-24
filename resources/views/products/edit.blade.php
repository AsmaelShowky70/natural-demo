@extends('layouts.app')

@section('title', 'تعديل بيانات المنتج - ' . $product->name_ar)
@section('page_title', 'تعديل بيانات المنتج (Edit Product)')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="premium-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                <div>
                    <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-pencil-square text-success me-2"></i>تعديل مواصفات المنتج: {{ $product->name_ar }}</h5>
                    <small class="text-muted">تحديث معايير المنتج والتصنيف والوحدات وسجل الرقابة</small>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-eye me-1"></i> معاينة الملف
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-light border">
                        <i class="bi bi-arrow-right me-1"></i> العودة للقائمة
                    </a>
                </div>
            </div>

            <form action="{{ route('products.update', $product) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">اسم المنتج بالعربية <span class="text-danger">*</span></label>
                        <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar', $product->name_ar) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">اسم المنتج بالإنجليزية (English) <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">كود المنتج (Product Code) <span class="text-danger">*</span></label>
                        <input type="text" name="product_code" class="form-control en-font" value="{{ old('product_code', $product->product_code) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">رمز التخزين الفريد (SKU) <span class="text-danger">*</span></label>
                        <input type="text" name="sku" class="form-control en-font" value="{{ old('sku', $product->sku) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">التصنيف <span class="text-danger">*</span></label>
                        <select name="product_category_id" class="form-select" required>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('product_category_id', $product->product_category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name_ar ?? $cat->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">النوع (Type)</label>
                        <input type="text" name="product_type" class="form-control" value="{{ old('product_type', $product->product_type) }}" placeholder="e.g. snack, cake, bar">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">الحجم / الوزن</label>
                        <input type="text" name="size" class="form-control" value="{{ old('size', $product->size) }}" placeholder="مثال: 30g أو 100g">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">وحدة القياس <span class="text-danger">*</span></label>
                        <select name="unit" class="form-select" required>
                            <option value="pack" {{ old('unit', $product->unit) == 'pack' ? 'selected' : '' }}>عبوة (Pack)</option>
                            <option value="box" {{ old('unit', $product->unit) == 'box' ? 'selected' : '' }}>كرتونة (Box / Carton)</option>
                            <option value="kg" {{ old('unit', $product->unit) == 'kg' ? 'selected' : '' }}>كيلوجرام (KG)</option>
                            <option value="pcs" {{ old('unit', $product->unit) == 'pcs' ? 'selected' : '' }}>قطعة (PCS)</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">الوصف بالعربية</label>
                        <textarea name="description_ar" class="form-control" rows="3">{{ old('description_ar', $product->description_ar) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">الوصف بالإنجليزية</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch p-0 pe-4 mt-2">
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input ms-2" type="checkbox" name="is_active" id="isActiveCheck" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold small" for="isActiveCheck">المنتج نشط في خطوط الإنتاج والكتالوج التجاري</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('products.show', $product) }}" class="btn btn-light px-4">إلغاء</a>
                    <button type="submit" class="btn btn-success px-4 fw-bold">
                        <i class="bi bi-check2-circle me-1"></i> حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
