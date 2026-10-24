@extends('layouts.app')

@section('title', 'أرشفة مستند جديد')
@section('page_title', 'أرشفة شهادة تحليل أو مستند رسمي (Upload Document / COA)')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="premium-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                <div>
                    <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-cloud-arrow-up-fill text-success me-2"></i>رفع وأرشفة مستند أو شهادة COA</h5>
                    <small class="text-muted">ربط الشهادات والتقارير بدفعات الإنتاج أو لوتات المواد الخام المستلمة</small>
                </div>
                <a href="{{ route('documents.index') }}" class="btn btn-sm btn-light border">
                    <i class="bi bi-arrow-right me-1"></i> العودة للقائمة
                </a>
            </div>

            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">عنوان المستند <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="مثال: شهادة تحليل الأرز الوارد لوت RICE-005" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">نوع المستند <span class="text-danger">*</span></label>
                        <select name="document_type" class="form-select" required>
                            <option value="coa">شهادة تحليل مخبري للمورد (COA Certificate)</option>
                            <option value="qc_report">تقرير فحص جودة داخلي (QC Report)</option>
                            <option value="production_report">تقرير تشغيل صالة الإنتاج (Production Sheet)</option>
                            <option value="receiving_certificate">سند استلام رسمي (Receiving Certificate)</option>
                            <option value="other">مستند رسمي آخر</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">ربط بدفعة إنتاج (اختياري)</label>
                        <select name="production_batch_id" class="form-select">
                            <option value="">-- غير مرتبط بدفعة إنتاج --</option>
                            @foreach($batches as $b)
                            <option value="{{ $b->id }}">{{ $b->batch_id }} ({{ $b->product?->name_ar }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">ربط بلوت مادة خام (اختياري)</label>
                        <select name="raw_material_lot_id" class="form-select">
                            <option value="">-- غير مرتبط بلوت خام --</option>
                            @foreach($lots as $lot)
                            <option value="{{ $lot->id }}">{{ $lot->lot_number }} ({{ $lot->rawMaterial?->name_ar }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label small fw-bold">ملف المستند (PDF / صور)</label>
                        <input type="file" name="file" class="form-control" accept=".pdf,.png,.jpg,.jpeg">
                        <small class="text-muted">الحد الأقصى 10 ميجابايت بصيغة PDF أو صورة فحص</small>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label small fw-bold">وصف المستند وملاحظات الاعتماد</label>
                        <textarea name="description" rows="3" class="form-control" placeholder="بيانات إضافية عن المستند أو نتائج المختبر الخارجي..."></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('documents.index') }}" class="btn btn-light border px-4">إلغاء</a>
                    <button type="submit" class="btn btn-ns-primary px-5">
                        <i class="bi bi-check-lg me-1"></i> حفظ وتوثيق في الأرشيف
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
