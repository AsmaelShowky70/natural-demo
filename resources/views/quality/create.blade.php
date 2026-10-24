@extends('layouts.app')

@section('title', 'تسجيل فحص جودة جديد')
@section('page_title', 'تسجيل نتائج الفحص المخبري وضبط الجودة (Quality Inspection)')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="premium-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                <div>
                    <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-eyedropper text-success me-2"></i>تسجيل فحص مخبري وتحليل جودة</h5>
                    <small class="text-muted">تسجيل معايير الرطوبة، الوزن، التحاليل الميكروبيولوجية، واعتماد أو رفض اللوت/الدفعة</small>
                </div>
                <a href="{{ route('quality.index') }}" class="btn btn-sm btn-light border">
                    <i class="bi bi-arrow-right me-1"></i> العودة للقائمة
                </a>
            </div>

            <form action="{{ route('quality.store') }}" method="POST">
                @csrf

                <!-- Section 1: Target & Basic Info -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">الجهة أو العنصر المفحوص <span class="text-danger">*</span></label>
                        <select name="inspection_target" id="inspection_target" class="form-select" required>
                            <option value="finished_goods" {{ $target === 'finished_goods' ? 'selected' : '' }}>منتج نهائي / دفعة إنتاج (Finished Goods)</option>
                            <option value="raw_material" {{ $target === 'raw_material' ? 'selected' : '' }}>مادة خام واردة (Raw Material)</option>
                            <option value="packaging" {{ $target === 'packaging' ? 'selected' : '' }}>مواد تعبئة وتغليف (Packaging Material)</option>
                        </select>
                    </div>

                    <!-- Target ID Selector (Batches) -->
                    <div class="col-md-8 target-block" id="block-finished_goods">
                        <label class="form-label small fw-bold">اختر دفعة الإنتاج المفحوصة <span class="text-danger">*</span></label>
                        <select name="production_batch_id" class="form-select">
                            <option value="">-- اختر دفعة الإنتاج --</option>
                            @foreach($batches as $b)
                            <option value="{{ $b->id }}" {{ $targetId == $b->id ? 'selected' : '' }}>
                                {{ $b->batch_id }} | منتج: {{ $b->product?->name_ar }} (تاريخ: {{ $b->production_date?->format('Y-m-d') }}) - الحالة: {{ $b->batch_status }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Target ID Selector (Raw Lots) -->
                    <div class="col-md-8 target-block d-none" id="block-raw_material">
                        <label class="form-label small fw-bold">اختر لوت المادة الخام المفحوصة <span class="text-danger">*</span></label>
                        <select name="raw_material_lot_id" class="form-select">
                            <option value="">-- اختر لوت الخامة --</option>
                            @foreach($rawLots as $rl)
                            <option value="{{ $rl->id }}" {{ $targetId == $rl->id ? 'selected' : '' }}>
                                {{ $rl->lot_number }} | {{ $rl->rawMaterial?->name_ar }} (تاريخ الاستلام: {{ $rl->receiving_date?->format('Y-m-d') }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Target ID Selector (Packaging Lots) -->
                    <div class="col-md-8 target-block d-none" id="block-packaging">
                        <label class="form-label small fw-bold">اختر لوت مادة التعبئة والتغليف <span class="text-danger">*</span></label>
                        <select name="packaging_material_lot_id" class="form-select">
                            <option value="">-- اختر لوت مادة التعبئة --</option>
                            @foreach($pkgLots as $pl)
                            <option value="{{ $pl->id }}" {{ $targetId == $pl->id ? 'selected' : '' }}>
                                {{ $pl->lot_number }} | {{ $pl->packagingMaterial?->name_ar }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold">تاريخ الفحص <span class="text-danger">*</span></label>
                        <input type="date" name="inspection_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold">اسم الفاحص / كيميائي الجودة <span class="text-danger">*</span></label>
                        <input type="text" name="inspector" class="form-control" value="{{ auth()->user()->name_ar ?? auth()->user()->name }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold">نوع التحليل / الفحص <span class="text-danger">*</span></label>
                        <select name="inspection_type" class="form-select" required>
                            <option value="Final Release">فحص الإفراج النهائي (Final Release)</option>
                            <option value="Incoming">فحص استلام توريدات (Incoming QC)</option>
                            <option value="In-Process">فحص خط الإنتاج (In-Process QC)</option>
                            <option value="Microbiological">تحليل ميكروبيولوجي (Microbiological)</option>
                            <option value="Sensory">فحص حسي وتذوق (Sensory Test)</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold">نسبة الرطوبة المقاسة (Moisture %)</label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="moisture_percentage" class="form-control" placeholder="مثال: 6.2">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Detailed Parameters Table -->
                <div class="p-3 bg-light rounded-4 mb-4 border">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-list-check text-success me-1"></i> معايير ونتائج القياس المخبري</h6>
                        <button type="button" class="btn btn-sm btn-outline-success" id="add-param-row">
                            <i class="bi bi-plus-circle me-1"></i> إضافة معيار قياس
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered bg-white mb-0" id="parameters-table">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 25%;">المعيار المفحوص</th>
                                    <th style="width: 25%;">المواصفة القياسية</th>
                                    <th style="width: 25%;">النتيجة المقاسة</th>
                                    <th style="width: 25%;">التقييم</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="parameters[0][parameter]" class="form-control form-control-sm" value="نسبة الرطوبة (Moisture %)" required></td>
                                    <td><input type="text" name="parameters[0][specification]" class="form-control form-control-sm" value="أقل من 8.0%"></td>
                                    <td><input type="text" name="parameters[0][actual_result]" class="form-control form-control-sm" value="6.2%"></td>
                                    <td>
                                        <select name="parameters[0][result]" class="form-select form-select-sm">
                                            <option value="pass" selected>ناجح (Pass)</option>
                                            <option value="fail">راسب (Fail)</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="text" name="parameters[1][parameter]" class="form-control form-control-sm" value="وزن العبوة (Piece Weight)" required></td>
                                    <td><input type="text" name="parameters[1][specification]" class="form-control form-control-sm" value="30g ± 1g"></td>
                                    <td><input type="text" name="parameters[1][actual_result]" class="form-control form-control-sm" value="30.1g"></td>
                                    <td>
                                        <select name="parameters[1][result]" class="form-select form-select-sm">
                                            <option value="pass" selected>ناجح (Pass)</option>
                                            <option value="fail">راسب (Fail)</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="text" name="parameters[2][parameter]" class="form-control form-control-sm" value="فحص السالمونيلا الميكروبي" required></td>
                                    <td><input type="text" name="parameters[2][specification]" class="form-control form-control-sm" value="خالي / 25g"></td>
                                    <td><input type="text" name="parameters[2][actual_result]" class="form-control form-control-sm" value="سلبي (Absent)"></td>
                                    <td>
                                        <select name="parameters[2][result]" class="form-select form-select-sm">
                                            <option value="pass" selected>ناجح (Pass)</option>
                                            <option value="fail">راسب (Fail)</option>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Section 3: Overall Decision & Notes -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">القرار والنتيجة الإجمالية للفحص <span class="text-danger">*</span></label>
                        <select name="overall_result" class="form-select form-select-lg fw-bold" required>
                            <option value="pass" selected class="text-success">✅ اعتماد وقبول (PASS - Approved)</option>
                            <option value="fail" class="text-danger">❌ رفض واستبعاد (FAIL - Rejected)</option>
                            <option value="hold" class="text-warning">⏳ تعليق وحجر صحي (HOLD - Quarantine)</option>
                        </select>
                        <small class="text-muted">ملاحظة: اختيار (اعتماد) لدفعة الإنتاج يتيح ترحيلها فوراً إلى رصيد المنتج النهائي المتاح للبيع.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">ملاحظات وتقرير المختبر</label>
                        <textarea name="notes" rows="3" class="form-control" placeholder="تقرير تحليلي عن العينة..."></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('quality.index') }}" class="btn btn-light border px-4">إلغاء</a>
                    <button type="submit" class="btn btn-ns-primary px-5">
                        <i class="bi bi-check-lg me-1"></i> اعتماد وحفظ نتيجة الفحص
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Handle target selector toggling
    const targetSelect = document.getElementById('inspection_target');
    function updateTargetBlock() {
        document.querySelectorAll('.target-block').forEach(el => el.classList.add('d-none'));
        const activeBlock = document.getElementById('block-' + targetSelect.value);
        if (activeBlock) {
            activeBlock.classList.remove('d-none');
        }
    }
    targetSelect.addEventListener('change', updateTargetBlock);
    updateTargetBlock();

    // Add parameter row dynamically
    let paramIndex = 3;
    document.getElementById('add-param-row').addEventListener('click', function() {
        const tbody = document.querySelector('#parameters-table tbody');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="text" name="parameters[${paramIndex}][parameter]" class="form-control form-control-sm" placeholder="اسم المعيار" required></td>
            <td><input type="text" name="parameters[${paramIndex}][specification]" class="form-control form-control-sm" placeholder="المواصفة"></td>
            <td><input type="text" name="parameters[${paramIndex}][actual_result]" class="form-control form-control-sm" placeholder="النتيجة"></td>
            <td>
                <select name="parameters[${paramIndex}][result]" class="form-select form-select-sm">
                    <option value="pass">ناجح (Pass)</option>
                    <option value="fail">راسب (Fail)</option>
                </select>
            </td>
        `;
        tbody.appendChild(tr);
        paramIndex++;
    });
</script>
@endpush
