<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') | ناتشورال سناكس – نظام تتبع الأغذية</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 RTL & Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

    <style>
        :root {
            --ns-primary: #059669;
            --ns-primary-dark: #047857;
            --ns-primary-light: #10b981;
            --ns-primary-subtle: #ecfdf5;
            --ns-secondary: #0f172a;
            --ns-accent: #f59e0b;
            --ns-bg: #f8fafc;
            --ns-card-bg: #ffffff;
            --ns-border: #e2e8f0;
            --ns-sidebar-w: 280px;
            --ns-font-ar: 'Cairo', system-ui, -apple-system, sans-serif;
            --ns-font-en: 'Outfit', sans-serif;
        }

        body {
            font-family: var(--ns-font-ar);
            background-color: var(--ns-bg);
            color: #1e293b;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .en-font {
            font-family: var(--ns-font-en);
        }

        /* ─── Sidebar Styling with Smooth Scroll ─── */
        #sidebar-wrapper {
            width: var(--ns-sidebar-w);
            height: 100vh;
            max-height: 100vh;
            background: linear-gradient(180deg, #09131f 0%, #0d1e32 100%);
            color: #f1f5f9;
            position: fixed;
            top: 0;
            bottom: 0;
            right: 0;
            z-index: 1040;
            box-shadow: -4px 0 25px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .sidebar-brand {
            padding: 1.25rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(255, 255, 255, 0.02);
            flex-shrink: 0;
        }

        .brand-logo-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.4rem;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35);
        }

        .sidebar-scrollable-content {
            flex: 1 1 auto;
            overflow-y: auto !important;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.25) transparent;
        }

        .sidebar-scrollable-content::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-scrollable-content::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.2);
        }

        .sidebar-scrollable-content::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.25);
            border-radius: 4px;
        }

        .sidebar-scrollable-content::-webkit-scrollbar-thumb:hover {
            background: #10b981;
        }

        .sidebar-footer {
            flex-shrink: 0;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(0, 0, 0, 0.25);
            padding: 0.85rem 1rem;
        }

        .sidebar-heading {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            padding: 1rem 1.25rem 0.35rem;
            font-weight: 700;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0.5rem 0.75rem;
            margin: 0;
        }

        .sidebar-nav .nav-item {
            margin-bottom: 3px;
        }

        .sidebar-nav .nav-link {
            color: #94a3b8;
            padding: 0.65rem 0.9rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            font-size: 0.92rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .sidebar-nav .nav-link .link-content {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-nav .nav-link i {
            font-size: 1.15rem;
            width: 22px;
            text-align: center;
            transition: transform 0.2s;
        }

        .sidebar-nav .nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.07);
        }

        .sidebar-nav .nav-link:hover i {
            transform: scale(1.15);
            color: #34d399;
        }

        .sidebar-nav .nav-link.active {
            color: #ffffff;
            background: linear-gradient(90deg, #059669 0%, #10b981 100%);
            box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
        }

        .sidebar-nav .nav-link.active i {
            color: #ffffff;
        }

        /* ─── Main Content ─── */
        #main-content {
            margin-right: var(--ns-sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        /* ─── Top Navbar ─── */
        .top-navbar {
            background: #ffffff;
            border-bottom: 1px solid var(--ns-border);
            padding: 0.85rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1020;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        }

        .page-body {
            padding: 1.75rem;
            flex: 1;
        }

        /* ─── Premium Cards & Widgets ─── */
        .premium-card {
            background: #ffffff;
            border: 1px solid var(--ns-border);
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .premium-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
        }

        .card-header-clean {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-card {
            position: relative;
            overflow: hidden;
            border-radius: 16px;
            padding: 1.4rem;
            background: #ffffff;
            border: 1px solid var(--ns-border);
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            transition: all 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        /* ─── Custom Badge & Tables ─── */
        .badge-subtle-success {
            background: #ecfdf5;
            color: #059669;
            font-weight: 600;
        }

        .badge-subtle-warning {
            background: #fffbeb;
            color: #d97706;
            font-weight: 600;
        }

        .badge-subtle-danger {
            background: #fef2f2;
            color: #dc2626;
            font-weight: 600;
        }

        .badge-subtle-info {
            background: #eff6ff;
            color: #2563eb;
            font-weight: 600;
        }

        .table-premium {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        .table-premium th {
            background: #f8fafc;
            color: #475569;
            font-weight: 700;
            font-size: 0.82rem;
            padding: 0.9rem 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .table-premium td {
            padding: 0.95rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
            vertical-align: middle;
        }

        .table-premium tr:hover td {
            background-color: #f8fafc;
        }

        /* ─── Mobile Responsiveness ─── */
        @media (max-width: 991.98px) {
            #sidebar-wrapper {
                right: -280px;
            }
            #sidebar-wrapper.show {
                right: 0;
            }
            #main-content {
                margin-right: 0;
            }
        }
    </style>
</head>
<body>
    @php
        $user = auth()->user();
        $isAdmin = $user && $user->isAdmin();
        $isProd = $user && $user->role === 'production_manager';
        $isQC = $user && $user->role === 'quality_control';
        $isWH = $user && $user->role === 'warehouse';
        $isAuditor = $user && $user->role === 'viewer';
    @endphp

    <!-- Sidebar -->
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <div class="brand-logo-icon">
                <i class="bi bi-shield-check"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-bold text-white fs-6">Natural Snacks</h6>
                <small class="text-emerald-400 text-muted" style="font-size: 0.72rem;">نظام تتبع وإدارة الجودة</small>
            </div>
        </div>

        <div class="sidebar-scrollable-content">
            <ul class="sidebar-nav">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <span class="link-content"><i class="bi bi-grid-1x2-fill"></i> لوحة التحكم الحية</span>
                    </a>
                </li>

                {{-- ─── 1. التتبع والرقابة (Traceability) ─── --}}
                <li class="sidebar-heading">إدارة التتبع وسلسلة الإمداد</li>

                @if($isAdmin || $isQC || $isProd || $isAuditor)
                <li class="nav-item">
                    <a href="{{ route('traceability.backward') }}" class="nav-link {{ request()->routeIs('traceability.backward') ? 'active' : '' }}">
                        <span class="link-content"><i class="bi bi-diagram-3-fill"></i> التتبع العكسي (Backward)</span>
                    </a>
                </li>
                @endif

                @if($isAdmin || $isQC || $isWH || $isAuditor)
                <li class="nav-item">
                    <a href="{{ route('traceability.forward') }}" class="nav-link {{ request()->routeIs('traceability.forward') ? 'active' : '' }}">
                        <span class="link-content"><i class="bi bi-signpost-split-fill"></i> التتبع الأمامي (Forward)</span>
                    </a>
                </li>
                @endif

                @if($isAdmin || $isQC)
                <li class="nav-item">
                    <a href="{{ route('traceability.recall') }}" class="nav-link {{ request()->routeIs('traceability.recall') ? 'active' : '' }}">
                        <span class="link-content"><i class="bi bi-exclamation-triangle-fill text-warning"></i> محاكي الاستدعاء (Recall)</span>
                        <span class="badge bg-danger rounded-pill" style="font-size: 0.65rem;">طوارئ</span>
                    </a>
                </li>
                @endif

                @if($isAdmin || $isWH || $isProd || $isQC)
                <li class="nav-item">
                    <a href="{{ route('barcodes.index') }}" class="nav-link {{ request()->routeIs('barcodes.*') ? 'active' : '' }}">
                        <span class="link-content"><i class="bi bi-qr-code-scan"></i> مركز الباركود والـ QR</span>
                    </a>
                </li>
                @endif

                {{-- ─── 2. صالة الإنتاج والتشغيل (Production) ─── --}}
                @if($isAdmin || $isProd)
                <li class="sidebar-heading">صالة الإنتاج والتشغيل</li>
                <li class="nav-item">
                    <a href="{{ route('production.index') }}" class="nav-link {{ request()->routeIs('production.*') ? 'active' : '' }}">
                        <span class="link-content"><i class="bi bi-gear-wide-connected"></i> دفعات الإنتاج (Batches)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <span class="link-content"><i class="bi bi-box-seam-fill"></i> المنتجات والوصفات (BOM)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('material-issues.index') }}" class="nav-link {{ request()->routeIs('material-issues.*') ? 'active' : '' }}">
                        <span class="link-content"><i class="bi bi-arrow-left-right"></i> استلام خامات التشغيل</span>
                    </a>
                </li>
                @elseif($isQC || $isAuditor)
                <li class="sidebar-heading">سجلات خطوط الإنتاج</li>
                <li class="nav-item">
                    <a href="{{ route('production.index') }}" class="nav-link {{ request()->routeIs('production.*') ? 'active' : '' }}">
                        <span class="link-content"><i class="bi bi-gear-wide-connected"></i> استعراض دفعات الإنتاج</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <span class="link-content"><i class="bi bi-box-seam-fill"></i> مواصفات المنتجات</span>
                    </a>
                </li>
                @endif

                {{-- ─── 3. إدارة الجودة والمختبر (Quality Control) ─── --}}
                @if($isAdmin || $isQC)
                <li class="sidebar-heading">إدارة الجودة والمختبر (QC/QA)</li>
                <li class="nav-item">
                    <a href="{{ route('quality.index') }}" class="nav-link {{ request()->routeIs('quality.*') ? 'active' : '' }}">
                        <span class="link-content"><i class="bi bi-clipboard2-check-fill"></i> فحوصات الجودة والاعتماد</span>
                        @php $pendingQC = \App\Models\QcNotification::where('status', 'pending')->count(); @endphp
                        @if($pendingQC > 0)
                            <span class="badge bg-warning text-dark rounded-pill">{{ $pendingQC }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('documents.index') }}" class="nav-link {{ request()->routeIs('documents.*') ? 'active' : '' }}">
                        <span class="link-content"><i class="bi bi-file-earmark-medical-fill"></i> شهادات التحليل (COA)</span>
                    </a>
                </li>
                @elseif($isAuditor)
                <li class="sidebar-heading">وثائق الجودة والاعتماد</li>
                <li class="nav-item">
                    <a href="{{ route('quality.index') }}" class="nav-link {{ request()->routeIs('quality.*') ? 'active' : '' }}">
                        <span class="link-content"><i class="bi bi-clipboard2-check-fill"></i> سجل فحوصات الجودة</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('documents.index') }}" class="nav-link {{ request()->routeIs('documents.*') ? 'active' : '' }}">
                        <span class="link-content"><i class="bi bi-file-earmark-medical-fill"></i> شهادات التحليل (COA)</span>
                    </a>
                </li>
                @endif

                {{-- ─── 4. المستودعات وسلاسل الإمداد (Warehouse) ─── --}}
                @if($isAdmin || $isWH)
                <li class="sidebar-heading">المستودعات وسلاسل الإمداد</li>
                <li class="nav-item">
                    <a href="{{ route('receiving.index') }}" class="nav-link {{ request()->routeIs('receiving.*') ? 'active' : '' }}">
                        <span class="link-content"><i class="bi bi-truck-flatbed"></i> سندات الاستلام (GRN)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('raw-materials.index') }}" class="nav-link {{ request()->routeIs('raw-materials.*') ? 'active' : '' }}">
                        <span class="link-content"><i class="bi bi-boxes"></i> مخزن المواد الخام</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('packaging.index') }}" class="nav-link {{ request()->routeIs('packaging.*') ? 'active' : '' }}">
                        <span class="link-content"><i class="bi bi-archive-fill"></i> مخزن مواد التعبئة</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('material-issues.index') }}" class="nav-link {{ request()->routeIs('material-issues.*') ? 'active' : '' }}">
                        <span class="link-content"><i class="bi bi-arrow-left-right"></i> صرف خامات للتشغيل</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('stock-audit.index') }}" class="nav-link {{ request()->routeIs('stock-audit.*') ? 'active' : '' }}">
                        <span class="link-content"><i class="bi bi-card-checklist"></i> الجرد المخزني الدوري</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                        <span class="link-content"><i class="bi bi-buildings-fill"></i> دليل الموردين المعتمدين</span>
                    </a>
                </li>
                @endif

                {{-- ─── 5. النظام والرقابة (Admin & Auditor) ─── --}}
                @if($isAdmin || $isAuditor)
                <li class="sidebar-heading">الحوكمة والرقابة</li>
                <li class="nav-item">
                    <a href="{{ route('audit.index') }}" class="nav-link {{ request()->routeIs('audit.*') ? 'active' : '' }}">
                        <span class="link-content"><i class="bi bi-fingerprint"></i> سجل التدقيق الرقمي (Audit)</span>
                    </a>
                </li>
                @endif

                @if($isAdmin)
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <span class="link-content"><i class="bi bi-people-fill"></i> المستخدمين والصلاحيات</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>

        <div class="sidebar-footer text-center text-muted small">
            <div class="fw-semibold text-light">Natural Snacks v2.0 Enterprise</div>
            <div class="text-xs" style="font-size: 0.72rem; color: #94a3b8;">معايير ISO 22000 & HACCP</div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div id="main-content">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light d-lg-none border" id="sidebar-toggle">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div class="d-none d-sm-block">
                    <span class="text-muted small">منظومة الرقابة والتتبع الذكية</span>
                    <h6 class="mb-0 fw-bold">@yield('page_title', 'لوحة التحكم')</h6>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <!-- Fast Role Switcher for Testing -->
                <div class="dropdown d-none d-md-block">
                    <button class="btn btn-light border btn-sm dropdown-toggle rounded-pill px-3" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-badge-fill me-1 text-primary"></i>
                        <span>تبديل القسم: {{ $user->role_title ?? '' }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 p-2">
                        <li><h6 class="dropdown-header">الانتقال الفوري لدور وظيفي آخر:</h6></li>
                        <li><a class="dropdown-item py-2 rounded-2 {{ $isAdmin ? 'bg-success-subtle fw-bold' : '' }}" href="{{ route('quick-login', 'admin') }}"><i class="bi bi-shield-lock-fill text-danger me-2"></i>الإدارة العليا (Admin)</a></li>
                        <li><a class="dropdown-item py-2 rounded-2 {{ $isProd ? 'bg-success-subtle fw-bold' : '' }}" href="{{ route('quick-login', 'production') }}"><i class="bi bi-gear-fill text-primary me-2"></i>مدير الإنتاج (Production)</a></li>
                        <li><a class="dropdown-item py-2 rounded-2 {{ $isQC ? 'bg-success-subtle fw-bold' : '' }}" href="{{ route('quick-login', 'qc') }}"><i class="bi bi-check2-circle text-success me-2"></i>مراقبة الجودة (QC/QA)</a></li>
                        <li><a class="dropdown-item py-2 rounded-2 {{ $isWH ? 'bg-success-subtle fw-bold' : '' }}" href="{{ route('quick-login', 'warehouse') }}"><i class="bi bi-box-fill text-warning me-2"></i>أمين المستودع (Warehouse)</a></li>
                        <li><a class="dropdown-item py-2 rounded-2 {{ $isAuditor ? 'bg-success-subtle fw-bold' : '' }}" href="{{ route('quick-login', 'auditor') }}"><i class="bi bi-search text-info me-2"></i>المدقق والمراجع (Auditor)</a></li>
                    </ul>
                </div>

                <!-- User Dropdown -->
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle text-dark" data-bs-toggle="dropdown">
                        <div class="avatar-circle rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px;">
                            {{ mb_substr($user->name_ar ?? $user->name, 0, 1) }}
                        </div>
                        <div class="d-none d-sm-block text-start">
                            <div class="fw-bold fs-7">{{ $user->name_ar ?? $user->name }}</div>
                            <div class="text-muted" style="font-size: 0.72rem;">{{ $user->role_title }}</div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 p-2">
                        <li class="px-3 py-2 border-bottom mb-2">
                            <div class="fw-bold">{{ $user->name_ar ?? $user->name }}</div>
                            <div class="text-muted small">{{ $user->email }}</div>
                            <span class="badge bg-secondary-subtle text-dark border mt-1">{{ $user->role_title }}</span>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger rounded-2 py-2">
                                    <i class="bi bi-box-arrow-right me-2"></i> تسجيل الخروج
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show m-3 rounded-3 shadow-sm border-0" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5 align-middle"></i>
                <strong>نجاح العملية:</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show m-3 rounded-3 shadow-sm border-0" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5 align-middle"></i>
                <strong>تنبيه الصلاحيات:</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-warning alert-dismissible fade show m-3 rounded-3 shadow-sm border-0" role="alert">
                <i class="bi bi-info-circle-fill me-2 fs-5 align-middle"></i>
                <strong>يرجى مراجعة الملاحظات التالية:</strong>
                <ul class="mb-0 mt-1 small">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Main Body -->
        <main class="page-body">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle mobile sidebar
        const toggleBtn = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar-wrapper');
        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
