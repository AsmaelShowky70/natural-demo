@extends('layouts.app')

@section('title', 'تسجيل سند استلام توريد جديد (GRN)')
@section('page_title', 'تسجيل سند استلام توريد جديد (New Goods Receiving Note)')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="premium-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                <div>
                    <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-truck text-success me-2"></i>تسجيل سند إدخال واستلام توريد مخزني (GRN)</h5>
                    <small class="text-muted">سيقوم النظام بتوليد أرقام اللوتات الداخلية وملصقات الباركود وإرسال إشعار فحص فوري للمختبر</small>
                </div>
                <a href="{{ route('receiving.index') }}" class="btn btn-sm btn-light border">
                    <i class="bi bi-arrow-right me-1"></i> العودة للقائمة
                </a>
            </div>

            <form action="{{ route('receiving.store') }}" method="POST">
                @csrf

                <!-- Section 1: Inbound Logistic Data -->
                <h6 class="fw-bold text-primary mb-3"><i class="bi bi-truck-front me-1"></i> 1. بيانات الشحنة والمورد</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-5">
                        <label class="form-label small fw-bold">المورد المعتمد <span class="text-danger">*</span></label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="">-- اختر المورد --</option>
                            @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->name_ar ?? $sup->name }} ({{ $sup->code }}) - {{ $sup->country }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">نوع التوريد <span class="text-danger">*</span></label>
                        <select name="receiving_type" id="receiving_type" class="form-select" required>
                            <option value="raw_material">مواد خام زراعية وغذائية (Raw Materials)</option>
                            <option value="packaging_material">مواد تعبئة وتغليف (Packaging Materials)</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold">تاريخ الاستلام <span class="text-danger">*</span></label>
                        <input type="date" name="receiving_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">رقم لوحة الشاحنة / الحاوية</label>
                        <input type="text" name="vehicle_number" class="form-control" placeholder="مثال: SAU-12345">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">اسم السائق / الناقل</label>
                        <input type="text" name="driver_name" class="form-control" placeholder="اسم سائق الشاحنة">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">مستلم الشحنة بالمستودع</label>
                        <input type="text" name="received_by" class="form-control" value="{{ auth()->user()->name_ar ?? auth()->user()->name }}" required>
                    </div>
                </div>

                <!-- Section 2: Items Received -->
                <div class="p-3 bg-light rounded-4 mb-4 border">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-boxes text-success me-1"></i> 2. الأصناف والكميات المستلمة</h6>
                            <small class="text-muted">أدخل الأصناف وسيتم تخصيص أرقام لوت مميزة لكل صنف تلقائياً</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-success" id="add-grn-row">
                            <i class="bi bi-plus-circle me-1"></i> إضافة صنف آخر
                        </button>
                    </div>

                    <div id="grn-rows-container">
                        <div class="row g-2 mb-3 grn-row align-items-end p-2 bg-white rounded-3 border">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">الصنف المستلم</label>
                                <select name="items[0][material_id]" class="form-select form-select-sm material-selector" required>
                                    <option value="">-- اختر الصنف --</option>
                                    @foreach($rawMaterials as $rm)
                                    <option value="{{ $rm->id }}" data-type="raw_material" data-unit="{{ $rm->unit }}">
                                        [خام] {{ $rm->name_ar }} ({{ $rm->code }})
                                    </option>
                                    @endforeach
                                    @foreach($packagingMaterials as $pm)
                                    <option value="{{ $pm->id }}" data-type="packaging_material" data-unit="{{ $pm->unit }}" class="d-none">
                                        [تعبئة] {{ $pm->name_ar }} ({{ $pm->code }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold">الكمية</label>
                                <input type="number" step="0.01" name="items[0][quantity]" class="form-control form-control-sm" placeholder="الكمية" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold">الوحدة</label>
                                <input type="text" name="items[0][unit]" class="form-control form-control-sm" value="KG" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold">لوت المورد الأصلي</label>
                                <input type="text" name="items[0][supplier_lot_number]" class="form-control form-control-sm" placeholder="Supplier Lot">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold">تاريخ الانتهاء</label>
                                <input type="date" name="items[0][expiry_date]" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Notes -->
                <div class="mb-4">
                    <label class="form-label small fw-bold">ملاحظات الاستلام والفحص الظاهري</label>
                    <textarea name="notes" rows="2" class="form-control" placeholder="سلامة الأختام، نظافة الشاحنة، درجات الحرارة..."></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('receiving.index') }}" class="btn btn-light border px-4">إلغاء</a>
                    <button type="submit" class="btn btn-ns-primary px-5">
                        <i class="bi bi-check-lg me-1"></i> حفظ وتوليد اللوتات والباركود
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const typeSelect = document.getElementById('receiving_type');
    function updateMaterialOptions() {
        const type = typeSelect.value;
        document.querySelectorAll('.material-selector option').forEach(opt => {
            if (!opt.value) return;
            if (opt.dataset.type === type) {
                opt.classList.remove('d-none');
            } else {
                opt.classList.add('d-none');
            }
        });
    }
    typeSelect.addEventListener('change', updateMaterialOptions);
    updateMaterialOptions();

    let grnRowIndex = 1;
    document.getElementById('add-grn-row').addEventListener('click', function() {
        const container = document.getElementById('grn-rows-container');
        const firstRow = container.querySelector('.grn-row');
        const clone = firstRow.cloneNode(true);

        clone.querySelectorAll('input, select').forEach(el => {
            el.name = el.name.replace(/\[\d+\]/, '[' + grnRowIndex + ']');
            if (el.type !== 'select-one') el.value = '';
        });

        container.appendChild(clone);
        grnRowIndex++;
    });
</script>
@endpush
