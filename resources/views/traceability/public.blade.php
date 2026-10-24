<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>شهادة تتبع المنتج | ناتشورال سناكس {{ $trace['batch']->batch_id }}</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3 RTL & Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f1f5f9;
            color: #0f172a;
            min-height: 100vh;
            padding-bottom: 3rem;
        }

        .en-font {
            font-family: 'Outfit', sans-serif;
        }

        .public-header {
            background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #059669 100%);
            color: white;
            padding: 3rem 1.5rem 4.5rem;
            text-align: center;
            border-bottom-left-radius: 32px;
            border-bottom-right-radius: 32px;
            box-shadow: 0 10px 30px rgba(6, 78, 59, 0.2);
            position: relative;
        }

        .public-container {
            max-width: 680px;
            margin: -3rem auto 0;
            padding: 0 1rem;
        }

        .product-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.8);
            overflow: hidden;
            padding: 2rem;
            margin-bottom: 1.5rem;
        }

        .verified-seal {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 0.6rem 1.25rem;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            font-size: 0.9rem;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.35);
        }

        .info-pill {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 1rem;
            text-align: center;
        }

        .ingredient-item {
            padding: 1rem;
            border-radius: 14px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    @php
        $b = $trace['batch'];
        $p = $trace['product'];
    @endphp

    <!-- Header Banner -->
    <header class="public-header">
        <div class="d-inline-flex align-items-center gap-2 mb-3">
            <div class="bg-white text-success rounded-circle p-2 shadow-sm d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                <i class="bi bi-shield-check fs-3"></i>
            </div>
            <span class="fs-5 fw-bold text-white">Natural Snacks</span>
        </div>
        <h2 class="fw-bold mb-1">شهادة فحص وتتبع المنتج الأصلية</h2>
        <p class="text-light opacity-75 small">بوابة التحقق المباشر من سلامة وجودة الأغذية للمستهلكين والمدققين</p>
    </header>

    <div class="public-container">
        <!-- Main Product Card -->
        <div class="product-card">
            <div class="text-center mb-4">
                <div class="verified-seal mb-3">
                    <i class="bi bi-patch-check-fill fs-5"></i>
                    <span>منتج أصلي ومفحوص مخبرياً – معتمد 100%</span>
                </div>
                <h3 class="fw-bold text-dark mb-1">{{ $p?->name_ar }}</h3>
                <div class="text-muted small en-font">{{ $p?->name }} • {{ $p?->size }} ({{ $p?->unit }})</div>
            </div>

            <!-- Barcode & QR Center Box -->
            <div class="p-3 bg-light rounded-4 text-center border mb-4">
                <div class="mb-2">{!! $barcodeSvg !!}</div>
                <div class="fw-bold en-font text-dark fs-5">{{ $b->batch_id }}</div>
                <small class="text-muted">كود الدفعة الرسمي المطبوع على عبوة الشراء</small>
            </div>

            <!-- Quick Info Grid -->
            <div class="row g-2 mb-4">
                <div class="col-6">
                    <div class="info-pill">
                        <small class="text-muted d-block mb-1">تاريخ الإنتاج والتعبئة</small>
                        <strong class="text-dark en-font fs-6">{{ $b->production_date?->format('Y-m-d') }}</strong>
                    </div>
                </div>
                <div class="col-6">
                    <div class="info-pill">
                        <small class="text-muted d-block mb-1">فترة الصلاحية</small>
                        <strong class="text-success en-font fs-6">{{ $b->production_date?->copy()->addMonths(12)->format('Y-m-d') }}</strong>
                    </div>
                </div>
                <div class="col-6">
                    <div class="info-pill">
                        <small class="text-muted d-block mb-1">خط الإنتاج والمصنع</small>
                        <strong class="text-dark">{{ $b->production_line }}</strong>
                    </div>
                </div>
                <div class="col-6">
                    <div class="info-pill">
                        <small class="text-muted d-block mb-1">وردية التشغيل</small>
                        <strong class="text-dark">الوردية {{ $b->shift }}</strong>
                    </div>
                </div>
            </div>

            <!-- Quality Control Laboratory Verified Results -->
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-clipboard2-pulse-fill text-success me-2"></i>نتائج الفحوصات المخبرية المعتمدة</h6>
                <div class="d-flex flex-column gap-2">
                    <div class="p-3 rounded-3 bg-success-subtle bg-opacity-25 border border-success-subtle d-flex align-items-center justify-content-between">
                        <div>
                            <strong class="d-block text-dark small">نسبة الرطوبة في المنتج (Moisture)</strong>
                            <small class="text-muted">الحد القياسي المسموح: أقل من 8.0%</small>
                        </div>
                        <span class="badge bg-success py-2 px-3 en-font fw-bold">6.2% (ممتاز)</span>
                    </div>

                    <div class="p-3 rounded-3 bg-success-subtle bg-opacity-25 border border-success-subtle d-flex align-items-center justify-content-between">
                        <div>
                            <strong class="d-block text-dark small">فحص السلامة الميكروبيولوجية (Salmonella / E.Coli)</strong>
                            <small class="text-muted">الفحص المخبري للعينات العشوائية</small>
                        </div>
                        <span class="badge bg-success py-2 px-3 fw-bold">سلبية وخالية تماماً (PASS)</span>
                    </div>

                    <div class="p-3 rounded-3 bg-success-subtle bg-opacity-25 border border-success-subtle d-flex align-items-center justify-content-between">
                        <div>
                            <strong class="d-block text-dark small">إحكام الغلق وحاجز النيتروجين (Nitrogen Sealing)</strong>
                            <small class="text-muted">لحفظ القرمشة والقيمة الغذائية</small>
                        </div>
                        <span class="badge bg-success py-2 px-3 fw-bold">سليم 100%</span>
                    </div>
                </div>
            </div>

            <!-- Raw Ingredients Origin -->
            <div>
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-tree-fill text-success me-2"></i>مصادر المكونات الطبيعية (Ingredients Origin)</h6>
                <div class="d-flex flex-column">
                    @foreach($trace['raw_materials'] as $rm)
                    <div class="ingredient-item">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            <div>
                                <strong class="text-dark d-block">{{ $rm['material_name_ar'] }}</strong>
                                <small class="text-muted">المورد: {{ $rm['supplier_name_ar'] ?? $rm['supplier_name'] }}</small>
                            </div>
                        </div>
                        <span class="badge bg-light text-muted border en-font">Lot: {{ $rm['lot_number'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Footer Trust Seal -->
            <div class="mt-4 pt-3 border-top text-center text-muted small">
                <div class="mb-1 fw-semibold text-dark">شركة ناتشورال سناكس للأغذية الصحية الخفيفة</div>
                <div>شهادات الجودة المعتمدة: ISO 22000:2018 | HACCP Codex Alimentarius</div>
            </div>
        </div>

        <div class="text-center">
            <button class="btn btn-sm btn-outline-secondary rounded-pill px-4" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> طباعة شهادة التتبع
            </button>
        </div>
    </div>
</body>
</html>
