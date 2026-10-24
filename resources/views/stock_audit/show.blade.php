@extends('layouts.app')

@section('title', 'محضر جرد ' . $record->audit_number)
@section('page_title', 'محضر جرد وتطابق أرصدة: ' . $record->audit_number)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="premium-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary text-white p-3 rounded-4">
                        <i class="bi bi-card-checklist fs-2"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1 text-dark">{{ $record->audit_number }}</h4>
                        <span class="text-muted small">رئيس لجنة الجرد: <strong class="text-dark">{{ $record->auditor_name }}</strong></span>
                    </div>
                </div>
                <div>
                    <button class="btn btn-sm btn-outline-dark me-2" onclick="window.print()">
                        <i class="bi bi-printer me-1"></i> طباعة محضر الجرد
                    </button>
                    <a href="{{ route('stock-audit.index') }}" class="btn btn-sm btn-light border">
                        <i class="bi bi-arrow-right me-1"></i> العودة
                    </a>
                </div>
            </div>

            <!-- Details Box -->
            <div class="p-3 bg-light rounded-4 mb-4 border">
                <div class="row g-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block">المستودع:</small>
                        <strong class="text-dark">
                            @if($record->warehouse_type === 'raw_materials')
                                مستودع المواد الخام
                            @elseif($record->warehouse_type === 'packaging')
                                مستودع مواد التعبئة والتغليف
                            @else
                                مستودع المنتج النهائي
                            @endif
                        </strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">تاريخ الجرد:</small>
                        <strong class="text-dark">{{ $record->audit_date?->format('Y-m-d') }}</strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">حالة المحضر:</small>
                        <span class="badge bg-success-subtle text-success">مكتمل ومعتمد</span>
                    </div>
                </div>
            </div>

            <!-- Items & Variances Table -->
            <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-list-check text-success me-2"></i>الأصناف المفحوصة وفروقات الجرد (Variance):</h6>
            <div class="table-responsive mb-4">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>رقم اللوت (Lot Number)</th>
                            <th>الصنف</th>
                            <th>الرصيد الدفتري (System)</th>
                            <th>الرصيد الفعلي (Physical)</th>
                            <th>الفارق (Variance)</th>
                            <th>الوحدة</th>
                            <th>حالة التطابق</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($record->items as $item)
                        <tr>
                            <td class="fw-bold en-font text-dark">{{ $item->lot_number }}</td>
                            <td>{{ $item->item_name }}</td>
                            <td class="en-font">{{ number_format($item->system_quantity, 1) }}</td>
                            <td class="en-font fw-bold text-dark">{{ number_format($item->physical_quantity, 1) }}</td>
                            <td class="en-font fw-bold">
                                @if($item->variance == 0)
                                    <span class="text-success">0.00 (مطابق)</span>
                                @elseif($item->variance > 0)
                                    <span class="text-primary">+{{ number_format($item->variance, 1) }} (زيادة)</span>
                                @else
                                    <span class="text-danger">{{ number_format($item->variance, 1) }} (عجز)</span>
                                @endif
                            </td>
                            <td>{{ $item->unit }}</td>
                            <td>
                                @if($item->variance == 0)
                                    <span class="badge bg-success-subtle text-success"><i class="bi bi-check-circle me-1"></i>مطابق 100%</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning">تمت التسوية</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($record->notes)
            <div class="p-3 bg-light rounded-3 border mb-3">
                <strong class="small text-dark d-block mb-1">ملاحظات لجنة الجرد:</strong>
                <p class="small text-secondary mb-0">{{ $record->notes }}</p>
            </div>
            @endif

            <div class="d-flex align-items-center justify-content-between pt-3 border-top text-muted small">
                <span>تم إعداد محضر الجرد والتسوية رسمياً</span>
                <span>تاريخ التوثيق: {{ $record->created_at?->format('Y-m-d H:i') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
