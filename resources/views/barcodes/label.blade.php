<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ملصق باركود | {{ $code }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600;700;800&family=Outfit:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; background: #ffffff; padding: 1rem; }
        .en-font { font-family: 'Outfit', sans-serif; }
        .label-sticker {
            width: 100mm;
            height: 60mm;
            border: 2px solid #000000;
            padding: 4mm;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-sizing: border-box;
            background: #ffffff;
        }
        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
            .label-sticker { border: 1px solid #000; }
        }
    </style>
</head>
<body>
    <div class="no-print text-center mb-3">
        <button class="btn btn-primary btn-sm px-4 fw-bold" onclick="window.print()">طباعة الملصق (Print Sticker)</button>
        <button class="btn btn-secondary btn-sm ms-2" onclick="window.close()">إغلاق</button>
    </div>

    <div class="label-sticker">
        <!-- Top header -->
        <div class="d-flex align-items-center justify-content-between border-bottom border-dark pb-1">
            <strong class="fs-6">Natural Snacks Food Industries</strong>
            <span class="badge bg-dark text-white en-font" style="font-size: 0.65rem;">{{ strtoupper($type) }} TAG</span>
        </div>

        <!-- Middle Content -->
        <div class="row g-1 align-items-center my-1">
            <div class="col-8">
                <div class="text-start mb-1">{!! $barcodeSvg !!}</div>
                <div class="fw-bold en-font fs-6">{{ $code }}</div>
                <div class="text-muted" style="font-size: 0.72rem;">شركة ناتشورال سناكس – ضبط الجودة والسلامة</div>
            </div>
            <div class="col-4 text-center">
                <div class="p-1 border border-dark rounded d-inline-block">
                    {!! $qrSvg !!}
                </div>
                <div class="fw-bold" style="font-size: 0.6rem;">SCAN TO TRACE</div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="d-flex justify-content-between align-items-center border-top border-dark pt-1" style="font-size: 0.68rem;">
            <span>تاريخ الإصدار: {{ date('Y-m-d H:i') }}</span>
            <span class="en-font fw-bold">ISO 22000 & HACCP</span>
        </div>
    </div>
</body>
</html>
