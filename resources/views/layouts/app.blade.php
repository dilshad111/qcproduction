<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($company) ? $company->name : 'CORRUGO MIS' }}</title>
    @if(isset($company) && $company->logo_path)
        <link rel="icon" href="{{ asset('storage/' . $company->logo_path) }}">
    @endif

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/BootstrapDash/star-admin2-free-admin-template@main/src/assets/css/vertical-layout-light/style.min.css">

    <style>
        /* Modern UI Theme Variables */
        [data-theme="light"] {
            --bg-gradient: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        [data-theme="dark"] {
            --bg-gradient: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
            --bs-body-bg: #0f172a;
            --bs-body-color: #f1f5f9;
            --bs-card-bg: #1e293b;
            --bs-card-border-color: #334155;
            --bs-navbar-bg: #1e293b;
        }
        [data-theme="blue"] {
            --bg-gradient: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            --bs-primary: #2563eb;
            --bs-body-bg: #f0f7ff;
        }
        [data-theme="sunset"] {
            --bg-gradient: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
            --bs-primary: #f97316;
            --bs-body-bg: #fffaf5;
            --bs-navbar-bg: #ffffff;
        }
        [data-theme="forest"] {
            --bg-gradient: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            --bs-primary: #16a34a;
            --bs-body-bg: #f7fee7;
        }
        [data-theme="glass"] {
            --bg-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            --bs-body-bg: #f5f3ff;
        }

        body {
            background: var(--bg-gradient);
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        .navbar {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.8) !important;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            z-index: 1050; /* Bootstrap's default for sticky-top is 1020, we make it higher */
        }
        [data-theme="dark"] .navbar {
            background-color: rgba(30, 41, 59, 0.8) !important;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            transition: transform 0.2s;
            position: relative;
            z-index: 1; /* Lower than navbar/dropdown */
        }
        .card:hover {
            transform: translateY(-5px);
        }
        
        /* Fix for User Dropdown visibility */
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-radius: 12px;
            padding: 8px;
            z-index: 2000 !important; /* Extremely high to stay above all form elements */
        }
        [data-theme="dark"] .dropdown-menu {
            background: #1e293b;
            color: white;
        }
        [data-theme="dark"] .dropdown-item {
            color: #cbd5e1;
        }
        [data-theme="dark"] .dropdown-item:hover {
            background: #334155;
            color: white;
        }

        .nav-link i {
            margin-right: 8px;
            width: 20px;
            text-align: center;
            transition: transform 0.2s;
        }
        .nav-link:hover i {
            transform: scale(1.2);
        }
        .dropdown-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* Vibrant Icon Colors */
        .text-dashboard { color: #3b82f6 !important; } /* Blue */
        .text-customers { color: #10b981 !important; } /* Emerald */
        .text-masters { color: #8b5cf6 !important; }   /* Violet */
        .text-jobs { color: #f59e0b !important; }      /* Amber */
        .text-production { color: #ef4444 !important; } /* Red */
        .text-admin { color: #6366f1 !important; }       /* Indigo */
        .text-theme { color: #ec4899 !important; }       /* Pink */
        .text-profile { color: #06b6d4 !important; }     /* Cyan */

        /* Reduced Navbar Height */
        .navbar.default-layout {
            height: 60px !important;
        }
        .navbar.default-layout .navbar-brand-wrapper {
            height: 60px !important;
            width: 240px !important;
        }
        .navbar.default-layout .navbar-menu-wrapper {
            height: 60px !important;
            width: calc(100% - 240px) !important;
        }
        .sidebar-offcanvas {
            top: 60px !important;
        }
        .container-scroller {
            padding-top: 0 !important;
        }
        .page-body-wrapper {
            padding-top: 60px !important;
        }
        .navbar.default-layout .navbar-brand-wrapper .navbar-brand img {
            height: 35px !important;
        }

        /* Increased Menu Font Sizes for Better Readability */
        .sidebar .nav .nav-item .nav-link .menu-title {
            font-size: 16px !important;
            font-weight: 500 !important;
        }
        .sidebar .nav .nav-item .nav-link .menu-icon {
            font-size: 20px !important;
        }
        .sidebar .nav .nav-category {
            font-size: 14px !important;
            font-weight: 600 !important;
        }
        .sidebar .nav .nav-item .nav-link .sub-menu .nav-item .nav-link {
            font-size: 15px !important;
        }
        .navbar .navbar-nav .nav-link {
            font-size: 15px !important;
            font-weight: 500 !important;
        }
        .dropdown-menu .dropdown-item {
            font-size: 15px !important;
        }
    </style>
</head>
<body data-theme="{{ Auth::user()->theme ?? 'light' }}">
    <div class="container-scroller" id="app">
        <!-- partial:navbar -->
        <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
                <div class="me-3">
                    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                        <span class="mdi mdi-menu"></span>
                    </button>
                </div>
                <div>
                    <a class="navbar-brand brand-logo" href="{{ url('/') }}">
                        @if(isset($company) && $company->logo_path)
                            <img src="{{ asset('storage/' . $company->logo_path) }}" alt="logo" style="height: 40px; width: auto;"/>
                        @else
                            <span class="fw-bold text-primary">{{ isset($company) ? $company->name : 'CORRUGO MIS' }}</span>
                        @endif
                    </a>
                    <a class="navbar-brand brand-logo-mini" href="{{ url('/') }}">
                        @if(isset($company) && $company->logo_path)
                            <img src="{{ asset('storage/' . $company->logo_path) }}" alt="logo" style="height: 30px; width: auto;"/>
                        @else
                            <span class="fw-bold text-primary">{{ isset($company) ? substr($company->name, 0, 1) : 'C' }}</span>
                        @endif
                    </a>
                </div>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-top">
                <ul class="navbar-nav">
                    @if(Request::routeIs('home'))
                    <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
                        <h1 class="welcome-text">Welcome, <span class="text-black fw-bold">{{ Auth::check() ? Auth::user()->name : 'Guest' }}</span></h1>
                        <h3 class="welcome-sub-text">CORRUGO MIS</h3>
                    </li>
                    @endif
                </ul>
                <ul class="navbar-nav ms-auto">
                    @auth
                    <!-- Theme Selector -->
                    <li class="nav-item dropdown d-none d-lg-block">
                        <a class="nav-link dropdown-toggle" id="themeDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-palette text-theme"></i> Theme
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="themeDropdown">
                            <a class="dropdown-item preview-item py-3" onclick="event.preventDefault(); document.getElementById('theme-light-form').submit();">
                                <span class="rounded-circle bg-light border me-2" style="width:12px;height:12px"></span> Light
                            </a>
                            <a class="dropdown-item preview-item py-3" onclick="event.preventDefault(); document.getElementById('theme-dark-form').submit();">
                                <span class="rounded-circle bg-dark border me-2" style="width:12px;height:12px"></span> Dark High Tech
                            </a>
                            <a class="dropdown-item preview-item py-3" onclick="event.preventDefault(); document.getElementById('theme-blue-form').submit();">
                                <span class="rounded-circle bg-primary border me-2" style="width:12px;height:12px"></span> Oceanic Blue
                            </a>
                            <a class="dropdown-item preview-item py-3" onclick="event.preventDefault(); document.getElementById('theme-sunset-form').submit();">
                                <span class="rounded-circle bg-warning border me-2" style="width:12px;height:12px"></span> Sunset Orange
                            </a>
                            <a class="dropdown-item preview-item py-3" onclick="event.preventDefault(); document.getElementById('theme-forest-form').submit();">
                                <span class="rounded-circle bg-success border me-2" style="width:12px;height:12px"></span> Forest Green
                            </a>
                            <a class="dropdown-item preview-item py-3" onclick="event.preventDefault(); document.getElementById('theme-glass-form').submit();">
                                <span class="rounded-circle bg-info border me-2" style="width:12px;height:12px"></span> Glassmorphism
                            </a>

                            <form id="theme-light-form" action="{{ route('theme.update') }}" method="POST" class="d-none"> @csrf <input type="hidden" name="theme" value="light"> </form>
                            <form id="theme-dark-form" action="{{ route('theme.update') }}" method="POST" class="d-none"> @csrf <input type="hidden" name="theme" value="dark"> </form>
                            <form id="theme-blue-form" action="{{ route('theme.update') }}" method="POST" class="d-none"> @csrf <input type="hidden" name="theme" value="blue"> </form>
                            <form id="theme-sunset-form" action="{{ route('theme.update') }}" method="POST" class="d-none"> @csrf <input type="hidden" name="theme" value="sunset"> </form>
                            <form id="theme-forest-form" action="{{ route('theme.update') }}" method="POST" class="d-none"> @csrf <input type="hidden" name="theme" value="forest"> </form>
                            <form id="theme-glass-form" action="{{ route('theme.update') }}" method="POST" class="d-none"> @csrf <input type="hidden" name="theme" value="glass"> </form>
                        </div>
                    </li>
                    @endauth

                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown d-none d-lg-block user-dropdown">
                            <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-circle-user text-profile fs-4"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                                <div class="dropdown-header text-center">
                                    <p class="mb-1 mt-3 font-weight-semibold">{{ Auth::user()->name }}</p>
                                    <p class="fw-light text-muted mb-0">{{ Auth::user()->email }}</p>
                                </div>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> My Profile
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i> Sign Out
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
                    <span class="mdi mdi-menu"></span>
                </button>
            </div>
        </nav>
        
        <div class="container-fluid page-body-wrapper">
            <!-- partial:sidebar -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="mdi mdi-grid-large menu-icon text-dashboard"></i>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item nav-category">Production Management</li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customers.index') }}">
                            <i class="mdi mdi-account-group menu-icon text-customers"></i>
                            <span class="menu-title">Customers</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('job-cards.index') }}">
                            <i class="mdi mdi-card-text-outline menu-icon text-jobs"></i>
                            <span class="menu-title">Job Cards</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('production.create') }}">
                            <i class="mdi mdi-file-document-edit menu-icon text-warning"></i>
                            <span class="menu-title">Issue Job Order</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('corrugation.index') }}">
                            <i class="mdi mdi-cogs menu-icon text-dark"></i>
                            <span class="menu-title">Corrugation Plant</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('production.index') }}">
                            <i class="mdi mdi-factory menu-icon text-production"></i>
                            <span class="menu-title">Production</span>
                        </a>
                    </li>
                    
                    <li class="nav-item nav-category">Setup</li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('masters.index') }}">
                            <i class="mdi mdi-database menu-icon text-masters"></i>
                            <span class="menu-title">Masters</span>
                        </a>
                    </li>

                    @if(Auth::user()->isAdmin() || Auth::user()->canViewMenu('audit_logs'))
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#admin-menu" aria-expanded="false" aria-controls="admin-menu">
                            <i class="menu-icon mdi mdi-shield-account text-admin"></i>
                            <span class="menu-title">Admin</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="admin-menu">
                            <ul class="nav flex-column sub-menu">
                                @if(Auth::user()->isAdmin())
                                <li class="nav-item"> <a class="nav-link" href="{{ route('users.index') }}">User Management</a></li>
                                <li class="nav-item"> <a class="nav-link" href="{{ route('company.setup') }}">Company Setup</a></li>
                                @endif
                                @if(Auth::user()->canViewMenu('audit_logs'))
                                <li class="nav-item"> <a class="nav-link" href="{{ route('audits.index') }}">Audit Logs</a></li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    @endif
                    @endauth
                </ul>
            </nav>
            
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:footer -->
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">CORRUGO MIS | Contact for Purchase: 0300-2566358</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">All rights reserved to SACHAAN TECH SOL. (c) {{ date('Y') }}.</span>
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

    <!-- Scripts at bottom -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/BootstrapDash/star-admin2-free-admin-template@main/src/assets/js/off-canvas.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/BootstrapDash/star-admin2-free-admin-template@main/src/assets/js/hoverable-collapse.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/BootstrapDash/star-admin2-free-admin-template@main/src/assets/js/template.js"></script>
    @stack('scripts')
</body>
</html>
