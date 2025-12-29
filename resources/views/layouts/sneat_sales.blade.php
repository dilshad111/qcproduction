<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | {{ isset($company) ? $company->name : 'CORRUGO MIS' }}</title>
    @if(isset($company) && $company->logo_path)
        <link rel="icon" href="{{ asset('storage/' . $company->logo_path) }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    
    <style>
        :root {
            --bs-primary: #696cff;
            --bs-primary-rgb: 105, 108, 255;
            --bs-body-bg: #f5f5f9;
            --bs-card-cap-bg: #fff;
            --sidebar-width: 260px;
            --topbar-height: 64px;
        }

        body {
            font-family: 'Public Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: var(--bs-body-bg);
            color: #566a7f;
            overflow-x: hidden;
        }

        /* Layout Structure */
        .layout-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        .layout-menu {
            width: var(--sidebar-width);
            background: #fff;
            box-shadow: 0 0.125rem 0.375rem 0 rgba(161, 172, 184, 0.12);
            z-index: 1000;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            overflow-y: auto;
            transition: all 0.2s ease-in-out;
        }

        .layout-menu.collapsed {
            width: 80px;
        }

        .layout-menu.collapsed .app-brand-text,
        .layout-menu.collapsed .menu-header,
        .layout-menu.collapsed .menu-link div {
            display: none;
        }

        .layout-menu.collapsed .menu-icon {
            margin-right: 0;
            font-size: 1.4rem;
        }

        .layout-menu.collapsed .menu-item {
            margin: 0.2rem 0.5rem;
        }

        .layout-menu.collapsed .menu-link {
            justify-content: center;
            padding: 0.625rem 0;
        }

        .layout-menu.collapsed .app-brand {
            justify-content: center;
            padding: 1.25rem 0;
        }

        .menu-inner {
            padding: 0.5rem 0;
            display: flex;
            flex-direction: column;
        }

        .app-brand {
            display: flex;
            align-items: center;
            padding: 1.25rem 1.5rem;
            min-height: 64px;
        }

        .app-brand-text {
            font-size: 1.375rem;
            font-weight: 700;
            color: #566a7f;
            text-transform: capitalize;
            margin-left: 0.75rem;
        }

        .menu-item {
            margin: 0.2rem 1rem;
        }

        .menu-link {
            display: flex;
            align-items: center;
            padding: 0.625rem 1rem;
            color: #697a8d;
            text-decoration: none;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
        }

        .menu-link:hover {
            background-color: rgba(67, 89, 113, 0.04);
            color: #566a7f;
        }

        .menu-item.active > .menu-link {
            background-color: rgba(105, 108, 255, 0.08);
            color: #696cff;
            font-weight: 500;
        }

        .menu-icon {
            margin-right: 0.75rem;
            font-size: 1.15rem;
            width: 20px;
            text-align: center;
        }

        .menu-header {
            padding: 1.5rem 1.5rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 500;
            color: #a1acb8;
            text-transform: uppercase;
        }

        /* Page Content */
        .layout-page {
            flex: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            min-width: 0;
            transition: margin-left 0.2s ease-in-out;
        }

        .layout-menu.collapsed + .layout-page {
            margin-left: 80px;
        }

        .layout-navbar {
            height: var(--topbar-height);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 0.125rem 0.375rem 0 rgba(161, 172, 184, 0.12);
            margin: 0 1.5rem;
            border-radius: 0 0 0.5rem 0.5rem;
        }

        .content-wrapper {
            padding: 1.5rem;
            flex: 1;
        }

        /* Card Customization */
        .card {
            border: none;
            box-shadow: 0 2px 6px 0 rgba(67, 89, 113, 0.12);
            border-radius: 0.5rem;
        }

        .card-header {
            background: transparent;
            padding: 1.5rem;
            border-bottom: none;
        }

        .btn-primary {
            background-color: #696cff;
            border-color: #696cff;
            box-shadow: 0 0.125rem 0.25rem 0 rgba(105, 108, 255, 0.4);
        }

        .btn-primary:hover {
            background-color: #5f61e6;
            border-color: #5f61e6;
            transform: translateY(-1px);
        }

        .avatar {
            width: 40px;
            height: 40px;
            background: #696cff;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-weight: 600;
        }

        @media (max-width: 991.98px) {
            .layout-menu {
                transform: translateX(-100%);
            }
            .layout-page {
                margin-left: 0;
            }
            .layout-menu.show {
                transform: translateX(0);
            }
        }

        /* Menu Icon Colors */
        .bx-home-circle { color: #696cff !important; }
        .bx-group { color: #71dd37 !important; }
        .bx-file { color: #ffab00 !important; }
        .bx-clipboard { color: #ff3e1d !important; }
        .bx-cog { color: #03c3ec !important; }
        .bx-building { color: #696cff !important; }
        .bx-data { color: #8592a3 !important; }
        .bx-user-circle { color: #03c3ec !important; }
        .bx-buildings { color: #ffab00 !important; }
        .bx-history { color: #71dd37 !important; }
        .fas.fa-sign-out-alt { color: #ff3e1d !important; }

        .menu-item.active .menu-icon {
            color: #fff !important;
        }
    </style>
</head>
<body>
    <div class="layout-wrapper">
        <!-- Sidebar Menu -->
        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            <div class="app-brand demo">
                <a href="{{ route('home') }}" class="app-brand-link text-decoration-none">
                    <span class="app-brand-logo demo">
                        @if(isset($company) && $company->logo_path)
                            <img src="{{ asset('storage/' . $company->logo_path) }}" alt="Logo" style="height: 40px;">
                        @else
                            <i class="fas fa-cube text-primary fa-2x"></i>
                        @endif
                    </span>
                    <span class="app-brand-text demo menu-text fw-bolder ms-2">{{ isset($company) ? $company->name : 'CORRUGO MIS' }}</span>
                </a>
            </div>

            <ul class="menu-inner py-1">
                @auth
                <!-- Dashboard -->
                <li class="menu-item {{ Request::routeIs('home') ? 'active' : '' }}">
                    <a href="{{ route('home') }}" class="menu-link" title="Dashboard">
                        <i class="menu-icon tf-icons bx bx-home-circle text-primary"></i>
                        <div data-i18n="Dashboard">Dashboard</div>
                    </a>
                </li>

                <!-- Production Management -->
                @php
                    $hasProductionMenus = Auth::user()->isAdmin() || 
                                        Auth::user()->canViewMenu('customers') || 
                                        Auth::user()->canViewMenu('job_cards') || 
                                        Auth::user()->canViewMenu('issue_job_order') || 
                                        Auth::user()->canViewMenu('corrugation') || 
                                        Auth::user()->canViewMenu('production');
                @endphp

                @if($hasProductionMenus)
                <li class="menu-header small text-uppercase">
                    <span class="menu-header-text">Production Management</span>
                </li>
                @endif

                @if(Auth::user()->isAdmin() || Auth::user()->canViewMenu('customers'))
                <li class="menu-item {{ Request::routeIs('customers.*') ? 'active' : '' }}">
                    <a href="{{ route('customers.index') }}" class="menu-link" title="Customers">
                        <i class="menu-icon tf-icons bx bx-group text-success"></i>
                        <div data-i18n="Customers">Customers</div>
                    </a>
                </li>
                @endif

                @if(Auth::user()->isAdmin() || Auth::user()->canViewMenu('job_cards'))
                <li class="menu-item {{ Request::routeIs('job-cards.*') ? 'active' : '' }}">
                    <a href="{{ route('job-cards.index') }}" class="menu-link" title="Job Cards">
                        <i class="menu-icon tf-icons bx bx-file text-warning"></i>
                        <div data-i18n="Job Cards">Job Cards</div>
                    </a>
                </li>
                @endif

                @if(Auth::user()->isAdmin() || Auth::user()->canViewMenu('issue_job_order'))
                <li class="menu-item {{ Request::routeIs('production.create') ? 'active' : '' }}">
                    <a href="{{ route('production.create') }}" class="menu-link" title="Issue Job Order">
                        <i class="menu-icon tf-icons bx bx-clipboard text-danger"></i>
                        <div data-i18n="Issue Job Order">Issue Job Order</div>
                    </a>
                </li>
                @endif

                @if(Auth::user()->isAdmin() || Auth::user()->canViewMenu('corrugation'))
                <li class="menu-item {{ Request::routeIs('corrugation.*') ? 'active' : '' }}">
                    <a href="{{ route('corrugation.index') }}" class="menu-link" title="Corrugation Plant">
                        <i class="menu-icon tf-icons bx bx-cog text-info"></i>
                        <div data-i18n="Corrugation Plant">Corrugation Plant</div>
                    </a>
                </li>
                @endif

                @if(Auth::user()->isAdmin() || Auth::user()->canViewMenu('production'))
                <li class="menu-item {{ Request::routeIs('production.index') ? 'active' : '' }}">
                    <a href="{{ route('production.index') }}" class="menu-link" title="Production">
                        <i class="menu-icon tf-icons bx bx-building text-primary"></i>
                        <div data-i18n="Production">Production</div>
                    </a>
                </li>
                @endif

                <!-- Setup -->
                @if(Auth::user()->isAdmin() || Auth::user()->canViewMenu('masters'))
                <li class="menu-header small text-uppercase">
                    <span class="menu-header-text">Setup</span>
                </li>
                <li class="menu-item {{ Request::routeIs('masters.*') ? 'active' : '' }}">
                    <a href="{{ route('masters.index') }}" class="menu-link" title="Masters">
                        <i class="menu-icon tf-icons bx bx-data text-secondary"></i>
                        <div data-i18n="Masters">Masters</div>
                    </a>
                </li>
                @endif

                <!-- Admin -->
                @if(Auth::user()->isAdmin() || Auth::user()->canViewMenu('admin') || Auth::user()->canViewMenu('audit_logs'))
                <li class="menu-header small text-uppercase">
                    <span class="menu-header-text">Admin</span>
                </li>
                @if(Auth::user()->isAdmin())
                <li class="menu-item {{ Request::routeIs('users.*') ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}" class="menu-link" title="User Management">
                        <i class="menu-icon tf-icons bx bx-user-circle text-info"></i>
                        <div data-i18n="User Management">User Management</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::routeIs('company.setup') ? 'active' : '' }}">
                    <a href="{{ route('company.setup') }}" class="menu-link" title="Company Setup">
                        <i class="menu-icon tf-icons bx bx-buildings text-warning"></i>
                        <div data-i18n="Company Setup">Company Setup</div>
                    </a>
                </li>
                @endif
                @if(Auth::user()->isAdmin() || Auth::user()->canViewMenu('audit_logs'))
                <li class="menu-item {{ Request::routeIs('audits.*') ? 'active' : '' }}">
                    <a href="{{ route('audits.index') }}" class="menu-link" title="Audit Logs">
                        <i class="menu-icon tf-icons bx bx-history text-success"></i>
                        <div data-i18n="Audit Logs">Audit Logs</div>
                    </a>
                </li>
                @endif
                @endif
                
                <li class="menu-item mt-auto">
                    <a href="#" class="menu-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Logout">
                        <i class="menu-icon tf-icons fas fa-sign-out-alt text-danger"></i>
                        <div>Logout</div>
                    </a>
                </li>
                @else
                <li class="menu-item">
                    <a href="{{ route('login') }}" class="menu-link">
                        <i class="menu-icon tf-icons fas fa-sign-in-alt"></i>
                        <div>Login</div>
                    </a>
                </li>
                @endauth
            </ul>
        </aside>

        <!-- Layout Page -->
        <div class="layout-page">
            <!-- Navbar -->
            <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)" onclick="document.getElementById('layout-menu').classList.toggle('show')">
                        <i class="fas fa-bars"></i>
                    </a>
                </div>
                
                <div class="layout-menu-toggle navbar-nav align-items-center me-3 d-none d-xl-flex">
                    <a class="nav-item nav-link px-0" href="javascript:void(0)" id="sidebar-toggle-btn">
                        <i class="fas fa-bars"></i>
                    </a>
                </div>

                <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                    <div class="navbar-nav align-items-center">
                        <div class="nav-item d-flex align-items-center">
                            <span class="fw-bold fs-5 text-primary">CORRUGO MIS (Sneat Sales)</span>
                        </div>
                    </div>

                    <ul class="navbar-nav flex-row align-items-center ms-auto">
                        @auth
                        <!-- Theme Selector -->
                        <li class="nav-item dropdown me-2">
                            <button class="btn btn-outline-primary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-palette me-1"></i> Theme
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow" style="min-width: 250px;">
                                <li><div class="dropdown-header text-center">Choose Your Theme</div></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('theme.update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="theme" value="staradmin">
                                        <button type="submit" class="dropdown-item d-flex align-items-center justify-content-between">
                                            Star Admin 2 @if(auth()->user()->theme == 'staradmin') <i class="fas fa-check text-success ms-2"></i> @endif
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('theme.update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="theme" value="softui_sales">
                                        <button type="submit" class="dropdown-item d-flex align-items-center justify-content-between">
                                            Soft UI (Sales) @if(auth()->user()->theme == 'softui_sales') <i class="fas fa-check text-success ms-2"></i> @endif
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('theme.update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="theme" value="sneat_sales">
                                        <button type="submit" class="dropdown-item d-flex align-items-center justify-content-between">
                                            Sneat (Sales) @if(auth()->user()->theme == 'sneat_sales') <i class="fas fa-check text-success ms-2"></i> @endif
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                        @endauth
                        
                        @auth
                        <li class="nav-item navbar-dropdown dropdown-user dropdown">
                            <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle fs-3 text-secondary"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <span class="d-flex align-items-center justify-content-center bg-light text-primary rounded-circle" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user"></i>
                                                </span>
                                            </div>
                                            <div class="flex-grow-1">
                                                <span class="fw-semibold d-block text-capitalize">{{ auth()->user()->name }}</span>
                                                <small class="text-muted">{{ auth()->user()->role ?? 'User' }}</small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li><div class="dropdown-divider"></div></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="fas fa-user me-2"></i>
                                        <span class="align-middle">My Profile</span>
                                    </a>
                                </li>
                                <li><div class="dropdown-divider"></div></li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-power-off me-2"></i>
                                        <span class="align-middle">Log Out</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i> Login
                            </a>
                        </li>
                        @endauth
                    </ul>
                </div>
            </nav>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>

            <!-- Content Wrapper -->
            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center alert-dismissible fade show shadow-sm" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <div>{{ session('success') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger d-flex align-items-center alert-dismissible fade show shadow-sm" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <div>{{ session('error') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>

                <!-- Footer -->
                <footer class="footer mt-auto py-3 bg-white shadow-sm mx-4 mb-4 rounded">
                    <div class="container-fluid d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                        <div class="mb-2 mb-md-0">
                            CORRUGO MIS | Contact for Purchase: 0300-2566358
                        </div>
                        <div>
                            All rights reserved to SACHAAN TECH SOL. © {{ date('Y') }}
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar Collapse Toggle
        const sidebar = document.getElementById('layout-menu');
        const sidebarToggleBtn = document.getElementById('sidebar-toggle-btn');
        const isCollapsed = localStorage.getItem('sidebar-collapsed-sneat-sales') === 'true';

        if (isCollapsed) {
            sidebar.classList.add('collapsed');
        }

        if (sidebarToggleBtn) {
            sidebarToggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
                localStorage.setItem('sidebar-collapsed-sneat-sales', sidebar.classList.contains('collapsed'));
            });
        }
    </script>
</body>
</html>
