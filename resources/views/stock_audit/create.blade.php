@extends('layouts.app')

@section('title', 'محضر جرد مخزني جديد')
@section('page_title', 'تسجيل محضر جرد مخزني فعلي (New Physical Stock Audit)')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="premium-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                <div>
                    <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-card-checklist text-success me-2"></i>إجراء جرد فعلي للمستودع</h5>
                    <small class="text-muted">أدخل الكميات المقاسة فعلياً على أرض الواقع وسيتم احتساب فروقات العجز أو الزيادة تلقائياً</small>
                </div>
                <a href="{{ route('stock-audit.index') }}" class="btn btn-sm btn-light border">
                    <i class="bi bi-arrow-right me-1"></i> العودة للقائمة
                </a>
            </div>

            <form action="{{ route('stock-audit.store') }}" method="POST">
                @csrf
                <input type="hidden" name="warehouse_type" value="{{ $type }}">

                <!-- Header Info -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">المستودع الخاضع للجرد</label>
                        <input type="text" class="form-control" value="{{ $type === 'raw_materials' ? 'مستودع المواد الخام' : ($type === 'packaging' ? 'مستودع مواد التعبئة والتغليف' : 'مستودع المنتج النهائي') }}" readonly>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">تاريخ الجرد <span class="text-danger">*</span></label>
                        <input type="date" name="audit_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">اسم المدقق / رئيس لجنة الجرد <span class="text-danger">*</span></label>
                        <input type="text" name="auditor_name" class="form-control" value="{{ auth()->user()->name_ar ?? auth()->user()->name }}" required>
                    </div>
                </div>

                <!-- Items Physical Count Table -->
                <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-list-ol text-success me-1"></i> حصر الأطقم والكميات الفعلية بالمستودع:</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>رقم اللوت (Lot Number)</th>
                                <th>الصنف</th>
                                <th>الرصيد الدفتري المسجل (النظام)</th>
                                <th style="width: 25%;">الرصيد الفعلي في المستودع <span class="text-danger">*</span></th>
                                <th>الوحدة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $idx => $item)
                            <tr>
                                <td class="fw-bold en-font text-dark">
                                    {{ $item['lot_number'] }}
                                    <input type="hidden" name="items[{{ $idx }}][lot_number]" value="{{ $item['lot_number'] }}">
                                    <input type="hidden" name="items[{{ $idx }}][item_code]" value="{{ $item['item_code'] }}">
                                    <input type="hidden" name="items[{{ $idx }}][item_name]" value="{{ $item['item_name'] }}">
                                    <input type="hidden" name="items[{{ $idx }}][system_quantity]" value="{{ $item['system_quantity'] }}">
                                    <input type="hidden" name="items[{{ $idx }}][unit]" value="{{ $item['unit'] }}">
                                </td>
                                <td>{{ $item['item_name'] }}</td>
                                <td class="en-font fw-bold text-primary">{{ number_format($item['system_quantity']) }} {{ $item['unit'] }}</td>
                                <td>
                                    <input type="number" step="0.01" name="items[{{ $idx }}][physical_quantity]" class="form-control form-control-sm fw-bold en-font" value="{{ $item['system_quantity'] }}" required>
                                </td>
                                <td>{{ $item['unit'] }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">لا توجد أرصدة خاضعة للجرد في هذا المستودع.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">ملاحظات لجنة الجرد وتبرير الفروقات إن وجدت</label>
                    <textarea name="notes" rows="2" class="form-control" placeholder="حالة التخزين، نظافة الطبالي، أي لوتات مفقودة أو تالفة..."></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('stock-audit.index') }}" class="btn btn-light border px-4">إلغاء</a>
                    <button type="submit" class="btn btn-ns-primary px-5">
                        <i class="bi bi-check-lg me-1"></i> حفظ واعتماد محضر الجرد
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
