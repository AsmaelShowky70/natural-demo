@extends('layouts.app')

@section('title', 'تعريف مادة تعبئة وتغليف جديدة')
@section('page_title', 'تعريف مادة تعبئة وتغليف جديدة (New Packaging Material)')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="premium-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                <div>
                    <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-archive-fill text-primary me-2"></i>تعريف مادة تعبئة وتغليف جديدة</h5>
                    <small class="text-muted">الأكياس، البرطمانات، الأغطية، الكراتين، والملصقات</small>
                </div>
                <a href="{{ route('packaging.index') }}" class="btn btn-sm btn-light border">
                    <i class="bi bi-arrow-right me-1"></i> العودة للقائمة
                </a>
            </div>

            <form action="{{ route('packaging.store') }}" method="POST">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">اسم مادة التعبئة بالعربية <span class="text-danger">*</span></label>
                        <input type="text" name="name_ar" class="form-control" placeholder="مثال: أكياس رقائق الأرز المطبوعة" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">اسم مادة التعبئة بالإنجليزية <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Printed Rice Cake Foil Bag" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">كود المادة (Code) <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control en-font" placeholder="PM-BAG-002" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">نوع مادة التعبئة</label>
                        <select name="material_type" class="form-select">
                            <option value="Bag">أكياس (Bag)</option>
                            <option value="Carton">كرتون (Carton)</option>
                            <option value="Jar">برطمان (Jar)</option>
                            <option value="Lid">غطاء (Lid)</option>
                            <option value="Label">ملصق (Label)</option>
                            <option value="Other">أخرى</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">المورد المعتمد</label>
                        <select name="supplier_id" class="form-select">
                            <option value="">-- اختياري --</option>
                            @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->name_ar ?? $sup->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">وحدة القياس <span class="text-danger">*</span></label>
                        <input type="text" name="unit" class="form-control" value="PCS" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">الحد الأدنى للطلب</label>
                        <input type="number" step="1" name="minimum_stock" class="form-control" value="1000">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label small fw-bold">المواصفات الفنية ومطابقة الغذاء</label>
                        <textarea name="specification" rows="3" class="form-control" placeholder="حاجز النيتروجين، سماكة الميكرون، شهادة ملامسة الغذاء..."></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('packaging.index') }}" class="btn btn-light border px-4">إلغاء</a>
                    <button type="submit" class="btn btn-ns-primary px-5">
                        <i class="bi bi-check-lg me-1"></i> حفظ مادة التعبئة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
