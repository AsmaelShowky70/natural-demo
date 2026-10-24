@extends('layouts.app')

@section('title', 'أرشيف شهادات التحليل والمستندات (COA)')
@section('page_title', 'أرشيف شهادات التحليل والمستندات الرقابية (COA & Certificates)')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Top Action Card -->
    <div class="premium-card p-4">
        <div class="row g-3 align-items-center justify-content-between">
            <div class="col-lg-7">
                <form action="{{ route('documents.index') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" style="max-width: 300px;" placeholder="بحث بعنوان المستند أو رقم اللوت..." value="{{ request('search') }}">
                    <select name="type" class="form-select form-select-sm" style="max-width: 170px;">
                        <option value="">كافة أنواع المستندات</option>
                        <option value="coa" {{ request('type') === 'coa' ? 'selected' : '' }}>شهادة تحليل (COA)</option>
                        <option value="qc_report" {{ request('type') === 'qc_report' ? 'selected' : '' }}>تقرير فحص جودة</option>
                        <option value="production_report" {{ request('type') === 'production_report' ? 'selected' : '' }}>تقرير إنتاج</option>
                        <option value="receiving_certificate" {{ request('type') === 'receiving_certificate' ? 'selected' : '' }}>سند استلام</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-dark"><i class="bi bi-search"></i> بحث</button>
                    @if(request()->hasAny(['search', 'type']))
                        <a href="{{ route('documents.index') }}" class="btn btn-sm btn-outline-secondary">إلغاء</a>
                    @endif
                </form>
            </div>
            <div class="col-lg-5 text-lg-end">
                <a href="{{ route('documents.create') }}" class="btn btn-sm btn-ns-primary">
                    <i class="bi bi-cloud-arrow-up-fill me-1"></i> أرشفة ورفع مستند أو شهادة COA جديدة
                </a>
            </div>
        </div>
    </div>

    <!-- Documents Table Card -->
    <div class="premium-card">
        <div class="card-header-clean bg-light bg-opacity-50">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-folder-check text-success me-2"></i>الأرشيف الرقمي المعتمد لشهادات التحليل والفحص</h6>
            <span class="badge bg-light text-dark border">إجمالي {{ $documents->total() }} مستند</span>
        </div>
        <div class="table-responsive">
            <table class="table table-premium mb-0">
                <thead>
                    <tr>
                        <th>نوع المستند</th>
                        <th>عنوان المستند والملف</th>
                        <th>مرتبط بالدفعة / اللوت</th>
                        <th>تاريخ الرفع</th>
                        <th>المسؤول عن الرفع</th>
                        <th>الوصف</th>
                        <th class="text-center">معاينة وتنزيل</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                    <tr>
                        <td>
                            @if($doc->document_type === 'coa')
                                <span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-patch-check-fill me-1"></i>شهادة COA</span>
                            @elseif($doc->document_type === 'qc_report')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">تقرير جودة</span>
                            @else
                                <span class="badge bg-light text-dark border">{{ $doc->document_type }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $doc->title }}</div>
                            <small class="text-muted en-font"><i class="bi bi-file-earmark-pdf text-danger me-1"></i>{{ $doc->file_name }}</small>
                        </td>
                        <td class="en-font fw-bold">
                            @if($doc->batch)
                                <a href="{{ route('production.show', $doc->batch) }}" class="text-decoration-none">{{ $doc->batch->batch_id }}</a>
                            @elseif($doc->rawMaterialLot)
                                {{ $doc->rawMaterialLot->lot_number }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $doc->upload_date?->format('Y-m-d') }}</td>
                        <td>{{ $doc->uploader?->name_ar ?? 'النظام الآلي' }}</td>
                        <td><small class="text-muted">{{ Str::limit($doc->description, 45) }}</small></td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border py-2 px-3">
                                <i class="bi bi-check-circle-fill text-success me-1"></i> محفوظ وموثق
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">لا توجد مستندات مسجلة بالأرشيف.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($documents->hasPages())
        <div class="p-3 border-top">
            {{ $documents->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
