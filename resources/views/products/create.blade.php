@extends('layouts.app')

@section('title', 'إضافة منتج جديد')
@section('page_title', 'إضافة منتج ووصفة تشغيل جديدة (New Product & BOM)')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="premium-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                <div>
                    <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-box-seam-fill text-success me-2"></i>إضافة منتج غذائي جديد إلى الكتالوج</h5>
                    <small class="text-muted">تسجيل بيانات المنتج، وحدات القياس، ووصفة التصنيع وقائمة المواد (BOM)</small>
                </div>
                <a href="{{ route('products.index') }}" class="btn btn-sm btn-light border">
                    <i class="bi bi-arrow-right me-1"></i> العودة للقائمة
                </a>
            </div>

            <form action="{{ route('products.store') }}" method="POST">
                @csrf

                <!-- Basic Product Data -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">اسم المنتج بالعربية <span class="text-danger">*</span></label>
                        <input type="text" name="name_ar" class="form-control" placeholder="مثال: كعكة الأرز بالملح الطبيعي" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">اسم المنتج بالإنجليزية (English) <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Rice Cake Salted" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold">كود المنتج (Product Code) <span class="text-danger">*</span></label>
                        <input type="text" name="product_code" class="form-control en-font" placeholder="RC-003" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold">رمز التخزين الفريد (SKU) <span class="text-danger">*</span></label>
                        <input type="text" name="sku" class="form-control en-font" placeholder="RC-SALT-30G" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold">التصنيف <span class="text-danger">*</span></label>
                        <select name="product_category_id" class="form-select" required>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name_ar ?? $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold">الحجم / الوزن</label>
                        <input type="text" name="size" class="form-control" placeholder="مثال: 30g أو 100g">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold">وحدة القياس</label>
                        <input type="text" name="unit" class="form-control" value="PCS" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold">نوع المنتج</label>
                        <input type="text" name="product_type" class="form-control" value="Snack">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">الوصف التسويقي والغذائي</label>
                        <input type="text" name="description_ar" class="form-control" placeholder="سناك خفيف وطبيعي 100% بدون مواد حافظة">
                    </div>
                </div>

                <!-- Initial BOM Recipe Section -->
                <div class="p-3 bg-light rounded-4 mb-4 border">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-card-checklist text-success me-1"></i> وصفة التشغيل وقائمة المواد المعتمدة (BOM Formula)</h6>
                            <small class="text-muted">حدد نسب ومكونات تصنيع هذا المنتج</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-success" id="add-bom-row">
                            <i class="bi bi-plus-circle me-1"></i> إضافة مادة خام للوصفة
                        </button>
                    </div>

                    <div id="bom-rows-container">
                        <div class="row g-2 mb-2 bom-row align-items-center">
                            <div class="col-md-5">
                                <select name="bom_items[0][raw_material_id]" class="form-select form-select-sm" required>
                                    <option value="">-- اختر المادة الخام للخلطة --</option>
                                    @foreach($rawMaterials as $rm)
                                    <option value="{{ $rm->id }}">{{ $rm->name_ar }} ({{ $rm->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="0.01" name="bom_items[0][quantity]" class="form-control form-control-sm" placeholder="الكمية للدفعة المعيارية (KG)" required>
                            </div>
                            <div class="col-md-2">
                                <input type="number" step="0.1" name="bom_items[0][percentage]" class="form-control form-control-sm" placeholder="النسبة %">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="bom_items[0][unit]" class="form-control form-control-sm" value="KG">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('products.index') }}" class="btn btn-light border px-4">إلغاء</a>
                    <button type="submit" class="btn btn-ns-primary px-5">
                        <i class="bi bi-check-lg me-1"></i> حفظ المنتج والوصفة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let bomIndex = 1;
    document.getElementById('add-bom-row').addEventListener('click', function() {
        const container = document.getElementById('bom-rows-container');
        const firstRow = container.querySelector('.bom-row');
        const clone = firstRow.cloneNode(true);

        clone.querySelectorAll('input, select').forEach(el => {
            el.name = el.name.replace(/\[\d+\]/, '[' + bomIndex + ']');
            if (el.type !== 'select-one') el.value = '';
        });

        container.appendChild(clone);
        bomIndex++;
    });
</script>
@endpush
