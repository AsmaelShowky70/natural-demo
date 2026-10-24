@extends('layouts.app')

@section('title', 'مركز الباركود ورموز QR')
@section('page_title', 'مركز طباعة الباركود ورموز الاستجابة السريعة (Barcode & QR Center)')

@section('content')
<div class="d-flex flex-column gap-4">

    <!-- Top Card -->
    <div class="premium-card p-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h5 class="fw-bold mb-1 text-dark"><i class="bi bi-qr-code-scan text-success me-2"></i>مركز ملصقات الباركود والـ QR الصناعي</h5>
                <small class="text-muted">طباعة ملصقات الشحن والكراتين، طرود المواد الخام، وتوليد بطاقات التعريف الفورية</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('barcodes.label', ['type' => 'batch', 'code' => 'RK-M-260925-A-001']) }}" target="_blank" class="btn btn-sm btn-ns-primary">
                    <i class="bi bi-printer me-1"></i> طباعة ملصق نموذج دفعة تجريبية
                </a>
            </div>
        </div>
    </div>

    <!-- Production Batches Barcodes Table -->
    <div class="premium-card">
        <div class="card-header-clean bg-light bg-opacity-50">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-box-seam text-primary me-2"></i>ملصقات باركود دفعات الإنتاج والمنتج النهائي (Finished Goods)</h6>
            <span class="badge bg-light text-dark border">{{ count($batches) }} دفعات</span>
        </div>
        <div class="table-responsive">
            <table class="table table-premium mb-0">
                <thead>
                    <tr>
                        <th>كود الدفعة (Batch Code)</th>
                        <th>اسم المنتج</th>
                        <th>تاريخ الإنتاج</th>
                        <th>باركود الكرتونة النهائي</th>
                        <th class="text-center">طباعة الملصق</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($batches as $b)
                    <tr>
                        <td class="fw-bold en-font text-dark">{{ $b->batch_id }}</td>
                        <td>{{ $b->product?->name_ar }}</td>
                        <td>{{ $b->production_date?->format('Y-m-d') }}</td>
                        <td class="en-font">{{ $b->finished_goods_barcode }}</td>
                        <td class="text-center">
                            <a href="{{ route('barcodes.label', ['type' => 'batch', 'code' => $b->batch_id]) }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                                <i class="bi bi-printer me-1"></i> طباعة الملصق
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Raw Material Lots Barcodes Table -->
    <div class="premium-card">
        <div class="card-header-clean bg-light bg-opacity-50">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-boxes text-success me-2"></i>ملصقات باركود خامات ومواد المستودع (Raw Material Lots)</h6>
            <span class="badge bg-light text-dark border">{{ count($rawLots) }} لوتات</span>
        </div>
        <div class="table-responsive">
            <table class="table table-premium mb-0">
                <thead>
                    <tr>
                        <th>رقم اللوت (Lot Number)</th>
                        <th>اسم المادة الخام</th>
                        <th>تاريخ الاستلام</th>
                        <th>باركود التخزين الداخلي</th>
                        <th class="text-center">طباعة الملصق</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rawLots as $rl)
                    <tr>
                        <td class="fw-bold en-font text-dark">{{ $rl->lot_number }}</td>
                        <td>{{ $rl->rawMaterial?->name_ar }}</td>
                        <td>{{ $rl->receiving_date?->format('Y-m-d') }}</td>
                        <td class="en-font">{{ $rl->barcode }}</td>
                        <td class="text-center">
                            <a href="{{ route('barcodes.label', ['type' => 'lot', 'code' => $rl->lot_number]) }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                <i class="bi bi-printer me-1"></i> طباعة الملصق
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
