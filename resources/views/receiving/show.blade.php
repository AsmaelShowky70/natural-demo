@extends('layouts.app')

@section('title', 'سند استلام ' . $receiving->receiving_number)
@section('page_title', 'سند إدخال واستلام توريد: ' . $receiving->receiving_number)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="premium-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success text-white p-3 rounded-4">
                        <i class="bi bi-truck-flatbed fs-2"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1 text-dark">{{ $receiving->receiving_number }}</h4>
                        <span class="text-muted small">المورد: <strong class="text-dark">{{ $receiving->supplier?->name_ar ?? $receiving->supplier?->name }}</strong></span>
                    </div>
                </div>
                <div>
                    <button class="btn btn-sm btn-outline-dark me-2" onclick="window.print()">
                        <i class="bi bi-printer me-1"></i> طباعة سند الاستلام
                    </button>
                    <a href="{{ route('receiving.index') }}" class="btn btn-sm btn-light border">
                        <i class="bi bi-arrow-right me-1"></i> العودة
                    </a>
                </div>
            </div>

            <!-- Header Info -->
            <div class="p-3 bg-light rounded-4 mb-4 border">
                <div class="row g-3">
                    <div class="col-md-3">
                        <small class="text-muted d-block">تاريخ الاستلام:</small>
                        <strong class="text-dark">{{ $receiving->receiving_date?->format('Y-m-d') }}</strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">نوع الشحنة:</small>
                        <strong class="text-dark">{{ $receiving->receiving_type === 'raw_material' ? 'مواد خام' : 'مواد تعبئة وتغليف' }}</strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">رقم الشاحنة / السائق:</small>
                        <strong class="text-dark">{{ $receiving->vehicle_number ?? 'غير مسجل' }} ({{ $receiving->driver_name }})</strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">مستلم الشحنة:</small>
                        <strong class="text-dark">{{ $receiving->received_by }}</strong>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-boxes text-success me-2"></i>الأصناف واللوتات المستلمة في هذا السند:</h6>
            <div class="table-responsive mb-4">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>رقم اللوت الداخلي المولد</th>
                            <th>الصنف</th>
                            <th>لوت المورد الأصلي</th>
                            <th>الكمية المستلمة</th>
                            <th>تاريخ الانتهاء</th>
                            <th>حالة الفحص المخبري</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($receiving->items as $item)
                        <tr>
                            <td class="fw-bold en-font text-dark">{{ $item->lot_number }}</td>
                            <td>
                                @if($item->rawMaterial)
                                    <div class="fw-semibold text-dark">{{ $item->rawMaterial->name_ar }}</div>
                                    <small class="text-muted">{{ $item->rawMaterial->code }}</small>
                                @else
                                    <div class="fw-semibold text-dark">{{ $item->packagingMaterial?->name_ar }}</div>
                                    <small class="text-muted">{{ $item->packagingMaterial?->code }}</small>
                                @endif
                            </td>
                            <td class="en-font">{{ $item->supplier_lot_number ?? '-' }}</td>
                            <td class="fw-bold en-font text-dark">{{ number_format($item->quantity) }} {{ $item->unit }}</td>
                            <td>{{ $item->expiry_date?->format('Y-m-d') ?? '-' }}</td>
                            <td>
                                @if($item->qc_status === 'approved')
                                    <span class="badge bg-success-subtle text-success">معتمد</span>
                                @elseif($item->qc_status === 'rejected')
                                    <span class="badge bg-danger-subtle text-danger">مرفوض</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning">قيد الفحص (Pending)</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($receiving->notes)
            <div class="p-3 bg-light rounded-3 border mb-3">
                <strong class="small text-dark d-block mb-1">ملاحظات الاستلام:</strong>
                <p class="small text-secondary mb-0">{{ $receiving->notes }}</p>
            </div>
            @endif

            <div class="d-flex align-items-center justify-content-between pt-3 border-top text-muted small">
                <span>تم التوثيق بمستودعات ناتشورال سناكس – سند إدخال رسمي</span>
                <span>تاريخ السند: {{ $receiving->created_at?->format('Y-m-d H:i') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
