@extends('layouts.app')

@section('title', 'تقرير الفحص المخبري ' . $inspection->inspection_number)
@section('page_title', 'تقرير الفحص المخبري: ' . $inspection->inspection_number)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="premium-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success-subtle text-success p-3 rounded-4">
                        <i class="bi bi-patch-check-fill fs-2"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <h4 class="fw-bold mb-0 text-dark">{{ $inspection->inspection_type }}</h4>
                            {!! $inspection->result_badge !!}
                        </div>
                        <span class="text-muted small">رقم الفحص: <strong class="en-font">{{ $inspection->inspection_number }}</strong></span>
                    </div>
                </div>
                <div>
                    <button class="btn btn-sm btn-outline-dark me-2" onclick="window.print()">
                        <i class="bi bi-printer me-1"></i> طباعة التقرير
                    </button>
                    <a href="{{ route('quality.index') }}" class="btn btn-sm btn-light border">
                        <i class="bi bi-arrow-right me-1"></i> العودة
                    </a>
                </div>
            </div>

            <!-- Target Details -->
            <div class="p-3 bg-light rounded-4 mb-4 border">
                <div class="row g-3">
                    <div class="col-md-3">
                        <small class="text-muted d-block">الجهة المفحوصة:</small>
                        <strong class="text-dark">
                            @if($inspection->inspection_target === 'finished_goods')
                                دفعة إنتاج نهائية
                            @elseif($inspection->inspection_target === 'raw_material')
                                مادة خام واردة
                            @else
                                مواد تعبئة وتغليف
                            @endif
                        </strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">الكود أو رقم اللوت:</small>
                        <strong class="text-dark en-font">
                            @if($inspection->batch)
                                <a href="{{ route('production.show', $inspection->batch) }}">{{ $inspection->batch->batch_id }}</a>
                            @elseif($inspection->rawMaterialLot)
                                {{ $inspection->rawMaterialLot->lot_number }}
                            @else
                                {{ $inspection->packagingMaterialLot?->lot_number }}
                            @endif
                        </strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">الفاحص المسؤول:</small>
                        <strong class="text-dark">{{ $inspection->inspector }}</strong>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted d-block">تاريخ الفحص:</small>
                        <strong class="text-dark">{{ $inspection->inspection_date?->format('Y-m-d') }}</strong>
                    </div>
                </div>
            </div>

            <!-- Measured Parameters Table -->
            <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-list-check text-success me-2"></i>معايير القياس المخبري ونتائج الفحص:</h6>
            <div class="table-responsive mb-4">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>المعيار</th>
                            <th>المواصفة القياسية المعتمدة</th>
                            <th>النتيجة المقاسة</th>
                            <th>وحدة القياس</th>
                            <th>التقييم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inspection->results as $res)
                        <tr>
                            <td class="fw-semibold">{{ $res->parameter }}</td>
                            <td class="text-muted">{{ $res->specification }}</td>
                            <td class="fw-bold en-font">{{ $res->actual_result }}</td>
                            <td>{{ $res->unit ?? '-' }}</td>
                            <td>
                                @if($res->result === 'pass')
                                    <span class="badge bg-success-subtle text-success"><i class="bi bi-check-circle me-1"></i>ناجح (PASS)</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger"><i class="bi bi-x-circle me-1"></i>راسب (FAIL)</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-3 text-muted">لم تسجل معايير تفصيلية.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($inspection->notes)
            <div class="p-3 bg-light rounded-3 mb-4 border">
                <strong class="d-block mb-1 text-dark small"><i class="bi bi-chat-left-text me-1"></i> ملاحظات المختبر وتوصيات الاعتماد:</strong>
                <p class="mb-0 text-secondary small">{{ $inspection->notes }}</p>
            </div>
            @endif

            <div class="d-flex align-items-center justify-content-between pt-3 border-top text-muted small">
                <span>تم الاعتماد بواسطة مختبر الجودة المركزي – شركة ناتشورال سناكس</span>
                <span>تاريخ التسجيل: {{ $inspection->created_at?->format('Y-m-d H:i') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
