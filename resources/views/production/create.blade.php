@extends('layouts.app')

@section('title', 'إنشاء أمر تشغيل دفعة جديدة')
@section('page_title', 'أمر تشغيل وتصنيع دفعة إنتاج (New Production Batch)')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="premium-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                <div>
                    <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-plus-circle-fill text-success me-2"></i>إنشاء أمر تشغيل وتصنيع دفعة إنتاج</h5>
                    <small class="text-muted">سيتم توليد كود الباتش والباركود تلقائياً وربط الخامات ومواد التغليف المستهلكة</small>
                </div>
                <a href="{{ route('production.index') }}" class="btn btn-sm btn-light border">
                    <i class="bi bi-arrow-right me-1"></i> العودة للقائمة
                </a>
            </div>

            <form action="{{ route('production.store') }}" method="POST">
                @csrf

                <!-- Section 1: Basic Batch Info -->
                <h6 class="fw-bold text-primary mb-3"><i class="bi bi-info-circle me-1"></i> 1. البيانات الأساسية للدفعة</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">المنتج المراد تصنيعه <span class="text-danger">*</span></label>
                        <select name="product_id" id="product_select" class="form-select" required>
                            <option value="">-- اختر المنتج --</option>
                            @foreach($products as $prod)
                            <option value="{{ $prod->id }}" data-bom-id="{{ $prod->activeBom?->id }}" data-bom-name="{{ $prod->activeBom?->name_ar }}">
                                {{ $prod->name_ar }} ({{ $prod->sku }}) - {{ $prod->size }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold">تاريخ الإنتاج <span class="text-danger">*</span></label>
                        <input type="date" name="production_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold">الوردية <span class="text-danger">*</span></label>
                        <select name="shift" class="form-select" required>
                            <option value="A">الوردية الأولى (A)</option>
                            <option value="B">الوردية الثانية (B)</option>
                            <option value="C">الوردية الثالثة (C)</option>
                            <option value="Night">الوردية الليلية (Night)</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">خط الإنتاج <span class="text-danger">*</span></label>
                        <select name="production_line" class="form-select" required>
                            <option value="Line 1 - Rice Cake Line">خط الإنتاج 1 (Rice Cake Line)</option>
                            <option value="Line 2 - Large Cake Line">خط الإنتاج 2 (Large Cake Line)</option>
                            <option value="Line 3 - Granola Bar Line">خط الإنتاج 3 (Granola Bar Line)</option>
                            <option value="Line 4 - Packaging Line">خط الإنتاج 4 (Packaging Line)</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">مشرف صالة الإنتاج <span class="text-danger">*</span></label>
                        <input type="text" name="production_supervisor" class="form-control" value="{{ auth()->user()->name_ar ?? auth()->user()->name }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">الكمية المستهدفة (Planned KG) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="planned_quantity" class="form-control" placeholder="مثال: 400" required>
                    </div>
                </div>

                <!-- Section 2: Consumed Raw Material Lots -->
                <div class="p-3 bg-light rounded-4 mb-4 border">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-boxes text-success me-1"></i> 2. أطقم المواد الخام المستهلكة من المستودع (Raw Material Lots)</h6>
                            <small class="text-muted">اختر لوت الخامة المعتمد من المستودع لتسجيل التتبع وخصم الرصيد تلقائياً</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-success" id="add-raw-row">
                            <i class="bi bi-plus-circle me-1"></i> إضافة خامة أخرى
                        </button>
                    </div>

                    <div id="raw-rows-container">
                        <div class="row g-2 mb-2 raw-row align-items-center">
                            <div class="col-md-6">
                                <select name="raw_lots[0][lot_id]" class="form-select form-select-sm" required>
                                    <option value="">-- اختر لوت الخامة المعتمد --</option>
                                    @foreach($rawLots as $rl)
                                    <option value="{{ $rl->id }}">
                                        {{ $rl->rawMaterial?->name_ar }} | اللوت: {{ $rl->lot_number }} (المتاح: {{ $rl->available_quantity }} {{ $rl->unit }}) - المورد: {{ $rl->supplier?->name_ar }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="0.01" name="raw_lots[0][quantity]" class="form-control form-control-sm" placeholder="الكمية المستهلكة (KG)" required>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="raw_lots[0][notes]" class="form-control form-control-sm" placeholder="ملاحظات (اختياري)">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Consumed Packaging Material Lots -->
                <div class="p-3 bg-light rounded-4 mb-4 border">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-archive text-primary me-1"></i> 3. مواد التعبئة والتغليف المستهلكة (Packaging Lots)</h6>
                            <small class="text-muted">الأكياس والكراتين المستخدمة في تعبئة الدفعة</small>
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <select name="packaging_lots[0][lot_id]" class="form-select form-select-sm">
                                <option value="">-- اختر لوت مادة التعبئة --</option>
                                @foreach($packagingLots as $pl)
                                <option value="{{ $pl->id }}">
                                    {{ $pl->packagingMaterial?->name_ar }} | اللوت: {{ $pl->lot_number }} (المتاح: {{ number_format($pl->available_quantity) }} {{ $pl->unit }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" step="1" name="packaging_lots[0][quantity]" class="form-control form-control-sm" placeholder="الكمية المستهلكة (PCS)">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="packaging_lots[0][notes]" class="form-control form-control-sm" placeholder="ملاحظات (اختياري)">
                        </div>
                    </div>
                </div>

                <!-- Section 4: Notes -->
                <div class="mb-4">
                    <label class="form-label small fw-bold">ملاحظات التشغيل والصالة</label>
                    <textarea name="notes" rows="2" class="form-control" placeholder="أي تعليمات أو ملاحظات خاصة بضغط الأفران أو سرعة الخط..."></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('production.index') }}" class="btn btn-light border px-4">إلغاء</a>
                    <button type="submit" class="btn btn-ns-primary px-5">
                        <i class="bi bi-check-lg me-1"></i> حفظ وتوليد أمر التشغيل
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let rawRowIndex = 1;
    document.getElementById('add-raw-row').addEventListener('click', function() {
        const container = document.getElementById('raw-rows-container');
        const firstRow = container.querySelector('.raw-row');
        const clone = firstRow.cloneNode(true);

        clone.querySelectorAll('input, select').forEach(el => {
            el.name = el.name.replace(/\[\d+\]/, '[' + rawRowIndex + ']');
            el.value = '';
        });

        container.appendChild(clone);
        rawRowIndex++;
    });
</script>
@endpush
