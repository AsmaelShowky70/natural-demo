<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول | ناتشورال سناكس – نظام إدارة وتتبع الأغذية</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3 RTL & Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #09131f 0%, #0d2238 50%, #061928 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow-x: hidden;
        }

        .bg-mesh-circle {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            z-index: 0;
            opacity: 0.25;
            pointer-events: none;
        }

        .circle-1 {
            width: 450px;
            height: 450px;
            background: #10b981;
            top: -100px;
            right: -100px;
        }

        .circle-2 {
            width: 400px;
            height: 400px;
            background: #0284c7;
            bottom: -100px;
            left: -100px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45);
            max-width: 980px;
            width: 100%;
            z-index: 1;
            overflow: hidden;
        }

        .login-sidebar {
            background: linear-gradient(160deg, #064e3b 0%, #047857 50%, #059669 100%);
            color: #ffffff;
            padding: 3rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .btn-quick-role {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #1e293b;
            font-weight: 600;
            font-size: 0.88rem;
            transition: all 0.2s ease;
        }

        .btn-quick-role:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            border-color: #10b981;
            background: #f0fdf4;
            color: #065f46;
        }

        .btn-primary-ns {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            border: none;
            color: #ffffff;
            font-weight: 700;
            padding: 0.8rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.35);
            transition: all 0.2s ease;
        }

        .btn-primary-ns:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.45);
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="bg-mesh-circle circle-1"></div>
    <div class="bg-mesh-circle circle-2"></div>

    <div class="login-card">
        <div class="row g-0">
            <!-- Left Info Panel -->
            <div class="col-lg-5 login-sidebar d-none d-lg-flex">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="bg-white text-success rounded-4 p-2 d-flex align-items-center justify-content-center shadow" style="width: 50px; height: 50px;">
                            <i class="bi bi-shield-check fs-2 text-emerald-600"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0 text-white">Natural Snacks</h4>
                            <small class="text-light opacity-75">نظام تتبع وإدارة جودة الأغذية</small>
                        </div>
                    </div>

                    <h5 class="fw-bold text-white mb-3 mt-4">سلسلة تصنيع رقمية مترابطة بالكامل</h5>
                    <p class="text-light opacity-75 small lh-lg">
                        منظومة متطورة تربط استلام المواد الخام، أوامر الصرف للإنتاج، تصنيع الدفعات بالوصفات المعتمدة، الفحوصات المخبرية، إطلاق المنتج النهائي، وحتى مسح رمز الـ QR العام للمستهلك.
                    </p>

                    <div class="d-flex flex-column gap-3 mt-4">
                        <div class="d-flex align-items-center gap-2 small">
                            <i class="bi bi-check-circle-fill text-warning"></i>
                            <span>مطابقة كاملة لمعايير سلامة الغذاء ISO 22000 & HACCP</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 small">
                            <i class="bi bi-check-circle-fill text-warning"></i>
                            <span>تتبع عكسي وأمامي فوري برمز QR وباركود Barcode</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 small">
                            <i class="bi bi-check-circle-fill text-warning"></i>
                            <span>حساب دقيق لنسب الإنتاجية، الهالك، وأرصدة المستودع</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-top border-light border-opacity-25">
                    <a href="{{ route('trace.public', 'RK-M-260925-A-001') }}" target="_blank" class="text-white text-decoration-none small d-flex align-items-center justify-content-between p-2 rounded bg-black bg-opacity-25 hover-scale">
                        <span><i class="bi bi-qr-code-scan me-2 text-warning"></i> تجربة صفحة التتبع العامة للمستهلك</span>
                        <i class="bi bi-arrow-left"></i>
                    </a>
                </div>
            </div>

            <!-- Right Login Form -->
            <div class="col-lg-7 p-4 p-md-5">
                <div class="text-center text-lg-start mb-4">
                    <h3 class="fw-bold mb-1 text-dark">تسجيل الدخول للمنظومة</h3>
                    <p class="text-muted small">سجل دخولك أو استخدم أحد أزرار الدخول السريع المباشر للأقسام</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger py-2 px-3 rounded-3 small mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info py-2 px-3 rounded-3 small mb-4">
                        {{ session('info') }}
                    </div>
                @endif

                <!-- Standard Login Form -->
                <form action="{{ route('login.post') }}" method="POST" class="mb-4">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">البريد الإلكتروني</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" name="email" class="form-control border-start-0 py-2" placeholder="name@naturalsnacks.com" value="{{ old('email', 'admin@naturalsnacks.com') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">كلمة المرور</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                            <input type="password" name="password" class="form-control border-start-0 py-2" placeholder="••••••••" value="Admin@123456" required>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="rememberMe" checked>
                            <label class="form-check-label small text-muted" for="rememberMe">تذكر تسجيل دخولي</label>
                        </div>
                        <span class="text-muted small">كلمة المرور الموحدة: <code class="text-success fw-bold">Admin@123456</code></span>
                    </div>

                    <button type="submit" class="btn btn-primary-ns w-100 py-2 fs-6">
                        <i class="bi bi-box-arrow-in-left me-2"></i> دخول المنظومة
                    </button>
                </form>

                <!-- 1-Click Fast Department Login -->
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="fw-bold small text-muted">الدخول السريع بحسابات الأقسام (للتجربة والعرض):</span>
                    </div>

                    <div class="row g-2">
                        <div class="col-sm-6">
                            <a href="{{ route('quick-login', 'admin') }}" class="btn-quick-role">
                                <i class="bi bi-shield-lock-fill text-danger fs-5"></i>
                                <div class="text-start">
                                    <div class="text-dark">الإدارة العليا</div>
                                    <small class="text-muted fw-normal">admin@naturalsnacks.com</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ route('quick-login', 'production') }}" class="btn-quick-role">
                                <i class="bi bi-gear-fill text-primary fs-5"></i>
                                <div class="text-start">
                                    <div class="text-dark">مدير الإنتاج</div>
                                    <small class="text-muted fw-normal">production@naturalsnacks.com</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ route('quick-login', 'qc') }}" class="btn-quick-role">
                                <i class="bi bi-check2-circle text-success fs-5"></i>
                                <div class="text-start">
                                    <div class="text-dark">مراقبة الجودة (QC)</div>
                                    <small class="text-muted fw-normal">qc@naturalsnacks.com</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ route('quick-login', 'warehouse') }}" class="btn-quick-role">
                                <i class="bi bi-box-fill text-warning fs-5"></i>
                                <div class="text-start">
                                    <div class="text-dark">أمين المستودع</div>
                                    <small class="text-muted fw-normal">warehouse@naturalsnacks.com</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
