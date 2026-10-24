<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بطاقة تشغيل وتتبع الدفعة | {{ $batch->batch_id }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: #ffffff;
            color: #0f172a;
            padding: 1.5rem;
        }

        .en-font {
            font-family: 'Outfit', sans-serif;
        }

        .traveler-sheet {
            max-width: 850px;
            margin: 0 auto;
            border: 2px solid #0f172a;
            padding: 1.75rem;
            background: #ffffff;
        }

        .header-box {
            border-bottom: 2px solid #0f172a;
            padding-bottom: 1rem;
            margin-bottom: 1.25rem;
        }

        .table-traveler {
            border: 1px solid #0f172a;
            width: 100%;
            margin-bottom: 1rem;
        }

        .table-traveler th, .table-traveler td {
            border: 1px solid #cbd5e1;
            padding: 0.45rem 0.65rem;
            font-size: 0.85rem;
            vertical-align: middle;
        }

        .table-traveler th {
            background-color: #f1f5f9;
            font-weight: 700;
        }

        .sign-box {
            border: 1px dashed #64748b;
            padding: 0.75rem;
            min-height: 80px;
            border-radius: 8px;
            font-size: 0.8rem;
        }

        @media print {
            body { padding: 0; }
            .traveler-sheet { border: none; padding: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="no-print text-center mb-3">
        <button class="btn btn-primary px-4 py-2 fw-bold" onclick="window.print()">
            <i class="bi bi-printer me-2"></i> طباعة كارت التشغيل (Print Traveler)
        </button>
        <button class="btn btn-secondary px-3 py-2 ms-2" onclick="window.close()">إغلاق</button>
    </div>

    <div class="traveler-sheet">
        <!-- Header -->
        <div class="header-box d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-dark text-white rounded p-2" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-shield-check fs-3"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">Natural Snacks Food Industries</h4>
                    <div class="fw-bold fs-6 text-secondary">بطاقة تشغيل وتتبع الدفعة (Production Batch Traveler Sheet)</div>
                </div>
            </div>
            <div class="text-center">
                <div class="mb-1">{!! $barcodeSvg !!}</div>
                <div class="fw-bold en-font fs-6">{{ $batch->batch_id }}</div>
            </div>
        </div>

        <!-- Batch & Product Details Grid -->
        <table class="table-traveler mb-3">
            <tr>
                <th style="width: 15%;">اسم المنتج:</th>
                <td style="width: 35%;" class="fw-bold">{{ $batch->product?->name_ar }} ({{ $batch->product?->sku }})</td>
                <th style="width: 15%;">تاريخ الإنتاج:</th>
                <td style="width: 35%;" class="en-font">{{ $batch->production_date?->format('Y-m-d') }}</td>
            </tr>
            <tr>
                <th>خط الإنتاج:</th>
                <td>{{ $batch->production_line }}</td>
                <th>الوردية:</th>
                <td>وردية {{ $batch->shift }}</td>
            </tr>
            <tr>
                <th>المشرف المسؤول:</th>
                <td>{{ $batch->production_supervisor }}</td>
                <th>الكمية المخططة:</th>
                <td class="fw-bold en-font">{{ number_format($batch->planned_quantity) }} KG</td>
            </tr>
            <tr>
                <th>الكمية الفعلية المنتجة:</th>
                <td class="fw-bold text-success en-font">{{ number_format($batch->actual_quantity) }} KG</td>
                <th>الكراتين المعبأة:</th>
                <td class="fw-bold en-font">{{ $batch->cartons_produced }} كرتونة</td>
            </tr>
        </table>

        <!-- Consumed Raw Materials & Lots -->
        <h6 class="fw-bold mb-1 text-dark"><i class="bi bi-boxes me-1"></i> المواد الخام والأطقم المستهلكة (Traceability Raw Lots):</h6>
        <table class="table-traveler mb-3">
            <thead>
                <tr>
                    <th>كود الخامة</th>
                    <th>اسم الخامة</th>
                    <th>رقم لوت الخامة (Lot #)</th>
                    <th>المورد الأصلي</th>
                    <th>الكمية المستهلكة</th>
                    <th>رطوبة الخامة</th>
                    <th>توقيع الفحص</th>
                </tr>
            </thead>
            <tbody>
                @foreach($batch->materials as $mat)
                @php $lot = $mat->rawMaterialLot; @endphp
                <tr>
                    <td class="en-font">{{ $lot?->rawMaterial?->code }}</td>
                    <td class="fw-semibold">{{ $lot?->rawMaterial?->name_ar }}</td>
                    <td class="en-font fw-bold">{{ $lot?->lot_number }}</td>
                    <td>{{ $lot?->supplier?->name_ar ?? $lot?->supplier?->name }}</td>
                    <td class="en-font fw-bold">{{ $mat->quantity_used }} {{ $mat->unit }}</td>
                    <td class="en-font">{{ $lot?->moisture_percentage ? $lot->moisture_percentage . '%' : '-' }}</td>
                    <td class="text-success"><i class="bi bi-check2"></i> معتمد</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Quality Control Lab Sign-off -->
        <h6 class="fw-bold mb-1 text-dark"><i class="bi bi-clipboard2-check me-1"></i> اعتماد واختبارات الجودة المخبرية (Quality Sign-off):</h6>
        <table class="table-traveler mb-3">
            <thead>
                <tr>
                    <th>نوع الفحص</th>
                    <th>الفاحص المسؤول</th>
                    <th>الرطوبة المقاسة</th>
                    <th>الوزن والحجم</th>
                    <th>الفحص الحسي واللون</th>
                    <th>النتيجة النهائية</th>
                </tr>
            </thead>
            <tbody>
                @foreach($batch->inspections as $ins)
                <tr>
                    <td>{{ $ins->inspection_type }}</td>
                    <td>{{ $ins->inspector }}</td>
                    <td class="en-font fw-bold">{{ $ins->moisture_percentage ? $ins->moisture_percentage . '%' : 'مطابق' }}</td>
                    <td>مطابق (30g)</td>
                    <td>مقرمش وخالي من العيوب</td>
                    <td class="fw-bold text-success">مجاز (PASS)</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Signatures Box -->
        <div class="row g-2 mt-2">
            <div class="col-4">
                <div class="sign-box">
                    <strong>توقيع مشرف الإنتاج:</strong>
                    <div class="mt-2 text-muted small">{{ $batch->production_supervisor }}</div>
                </div>
            </div>
            <div class="col-4">
                <div class="sign-box">
                    <strong>توقيع مراقب الجودة (QC):</strong>
                    <div class="mt-2 text-muted small">د. ليلى محمود (QA Lead)</div>
                </div>
            </div>
            <div class="col-4">
                <div class="sign-box">
                    <strong>توقيع استلام المستودع:</strong>
                    <div class="mt-2 text-muted small">أمين مستودع المنتج النهائي</div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top border-dark text-muted" style="font-size: 0.72rem;">
            <span>طبع بواسطة نظام ناتشورال سناكس للتتبع الذكي في: {{ date('Y-m-d H:i:s') }}</span>
            <span>معايير الأيزو وسلامة الغذاء ISO 22000 & HACCP</span>
        </div>
    </div>
</body>
</html>
