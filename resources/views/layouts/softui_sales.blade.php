<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | {{ isset($company) ? $company->name : 'CORRUGO MIS' }}</title>
    @if(isset($company) && $company->logo_path)
        <link rel="icon" href="{{ asset('storage/' . $company->logo_path) }}">
    @endif
    
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        :root {
            --bs-primary: #cb0c9f;
            --bs-secondary: #8392ab;
            --bs-success: #82d616;
            --bs-info: #17adee;
            --bs-warning: #fbcf33;
            --bs-danger: #ea0606;
            --bs-dark: #344767;
            --sidebar-width: 250px;
        }

        body {
            font-family: "Open Sans", sans-serif;
            background-color: #f8f9fa;
            color: #67748e;
            overflow-x: hidden;
        }

        /* Soft UI Glassmorphism & Shadow */
        .card {
            box-shadow: 0 20px 27px 0 rgba(0, 0, 0, 0.05);
            border-radius: 1rem;
            border: none;
            transition: all 0.3s ease;
        }

        .btn {
            border-radius: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.2s ease;
        }

        .bg-gradient-primary, .btn-primary {
            background-image: linear-gradient(310deg, #7928ca 0%, #cb0c9f 100%) !important;
            border: none;
        }
        .bg-gradient-dark {
            background-image: linear-gradient(310deg, #141727 0%, #3a416b 100%) !important;
        }
        .icon-sm {
            font-size: 0.875rem !important;
        }

        /* Sidebar */
        .sidenav {
            width: var(--sidebar-width);
            background: #fff;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1000;
            padding: 1rem;
            margin: 1rem;
            border-radius: 1rem;
            box-shadow: 0 20px 27px 0 rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease-in-out;
            overflow: hidden;
        }

        .sidenav.collapsed {
            width: 82px;
        }

        .sidenav.collapsed .sidenav-brand-text,
        .sidenav.collapsed .nav-link-text,
        .sidenav.collapsed .menu-title,
        .sidenav.collapsed .nav-item h6,
        .sidenav.collapsed hr.horizontal {
            opacity: 0;
            display: none !important;
        }

        .sidenav.collapsed .nav-link {
            justify-content: center;
            padding: 0.75rem 0;
            margin: 0.6rem 0.75rem; /* Increased vertical margin for 'island' spacing */
        }

        .sidenav.collapsed .nav-link-icon {
            margin-right: 0;
            width: 46px;
            height: 46px;
            border-radius: 50px !important; /* Perfect pill/circle shape */
            box-shadow: 0 4px 12px 0 rgba(0, 0, 0, 0.08);
            background: #fff !important;
        }

        .sidenav.collapsed .nav-item.active .nav-link-icon {
            background-image: linear-gradient(310deg, #7928ca 0%, #cb0c9f 100%) !important;
            box-shadow: 0 4px 15px 0 rgba(203, 12, 159, 0.3);
        }

        .sidenav.collapsed .sidenav-header {
            padding: 1.5rem 0;
        }

        .sidenav.collapsed .navbar-brand {
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
        }

        .sidenav.collapsed .navbar-brand img {
            margin: 0 !important;
        }

        .sidenav-header {
            padding: 1.5rem;
            text-align: center;
        }

        .sidenav-brand-text {
            font-weight: 700;
            color: #344767;
            font-size: 1.1rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.675rem 1rem;
            margin: 0.125rem 0.5rem;
            border-radius: 0.5rem;
            color: #67748e;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .nav-link:hover {
            background-color: #f8f9fa;
            color: #344767;
        }

        .nav-item.active .nav-link {
            background-color: #fff;
            box-shadow: 0 20px 27px 0 rgba(0,0,0,0.05);
            color: #344767;
        }

        .nav-link-icon {
            width: 32px;
            height: 32px;
            background: #fff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            color: #344767;
            transition: all 0.2s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .nav-link:hover .nav-link-icon {
            transform: scale(1.1);
            background-color: #f8f9fa;
        }

        .nav-item.active .nav-link-icon {
            background-image: linear-gradient(310deg, #7928ca 0%, #cb0c9f 100%);
            color: #fff !important;
            box-shadow: 0 4px 7px -1px rgba(0, 0, 0, 0.11), 0 2px 4px -1px rgba(0, 0, 0, 0.07);
        }

        .nav-item.active .nav-link-icon i {
            color: #fff !important;
        }

        .menu-title {
            padding: 0.75rem 1.5rem;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            color: #8392ab;
            opacity: 0.6;
        }

        /* Main Content */
        .main-content {
            margin-left: calc(var(--sidebar-width) + 2rem);
            padding: 1.5rem;
            transition: all 0.2s ease-in-out;
        }

        .sidenav.collapsed + .main-content {
            margin-left: calc(82px + 2rem);
        }

        .navbar-main {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: saturate(200%) blur(30px);
            border-radius: 1rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.05);
            padding: 0.5rem 1rem;
        }

        @media (max-width: 991.98px) {
            .sidenav {
                transform: translateX(-110%);
            }
            .sidenav.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }

        /* Z-Index Fixes */
        .sidenav {
            z-index: 1050 !important;
        }
        .navbar-main {
            z-index: 1040 !important;
        }
        .dropdown-menu {
            z-index: 9999 !important;
        }
        .navbar .dropdown {
            z-index: 9999 !important;
        }
        /* Ensure content doesn't overlap sidebar on small screens */
        .g-sidenav-show .main-content {
            z-index: 1;
        }

        /* Professional Professional Table Styling */
        .table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        .table thead th {
            padding: 12px 24px !important;
            text-transform: uppercase !important;
            font-size: 0.75rem !important; /* Slightly larger */
            font-weight: 700 !important;
            letter-spacing: 0.05rem !important;
            color: #495057 !important; /* Darker grey for better visibility */
            border-bottom: 1px solid #e9ecef !important;
            background-color: #f8f9fa !important; /* Slightly different bg */
            border-top: none !important;
            opacity: 1 !important; /* Ensure full visibility */
        }

        .table tbody td {
            padding: 16px 24px !important;
            font-size: 0.85rem !important;
            border-bottom: 1px solid #e9ecef !important;
            vertical-align: middle !important;
            color: #344767 !important;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.01) !important;
        }

        .table-bordered, .table-bordered td, .table-bordered th {
            border: none !important;
            border-bottom: 1px solid #e9ecef !important;
        }

        /* Professional Action Buttons */
        .btn {
            border-radius: 0.5rem !important;
            font-weight: 700 !important;
            padding: 0.6rem 1.2rem !important;
            box-shadow: 0 4px 6px rgba(50, 50, 93, .11), 0 1px 3px rgba(0, 0, 0, .08) !important;
            transition: all .15s ease !important;
        }

        .btn:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 7px 14px rgba(50, 50, 93, .1), 0 3px 6px rgba(0, 0, 0, .08) !important;
        }

        .btn-sm {
            padding: 0.4rem 0.8rem !important;
            font-size: 0.75rem !important;
            margin: 2px !important;
        }

        .btn-info {
            background: linear-gradient(310deg, #2152ff 0%, #21d4fd 100%) !important;
            border: none !important;
        }

        .btn-dark {
            background: linear-gradient(310deg, #141727 0%, #3a416b 100%) !important;
            border: none !important;
        }

        .btn-warning {
            background: linear-gradient(310deg, #f53939 0%, #fbcf33 100%) !important;
            background: linear-gradient(310deg, #fbcf33 0%, #f9d423 100%) !important;
            border: none !important;
            color: #fff !important;
        }

        .btn-danger {
            background: linear-gradient(310deg, #ea0606 0%, #ff667c 100%) !important;
            border: none !important;
        }

        /* Form Control Refinement */
        .form-control, .form-select {
            border-radius: 0.5rem !important;
            padding: 0.6rem 0.75rem !important;
            border: 1px solid #d2d6da !important;
            font-size: 0.875rem !important;
        }

        .form-control:focus {
            border-color: #cb0c9f !important;
            box-shadow: 0 0 0 2px rgba(203, 12, 159, 0.2) !important;
        }

        /* Utility Classes */
        .bg-light-soft {
            background-color: #f8f9fa !important;
        }
        .text-xxs {
            font-size: 0.65rem !important;
        }
        .text-xs {
            font-size: 0.75rem !important;
        }
        .text-secondary {
            color: #6c757d !important; /* Darker than default for readability */
        }
        .opacity-7 {
            opacity: 0.9 !important; /* Increase opacity from 0.7 to 0.9 */
        }
        .text-sm {
            font-size: 0.875rem !important;
        }
        .font-weight-bolder {
            font-weight: 700 !important;
        }
        .opacity-7 {
            opacity: 0.7 !important;
        }
        .badge-sm {
            padding: 0.45em 0.75em !important;
            font-size: 0.65rem !important;
            border-radius: 0.375rem !important;
        }
        .bg-gradient-secondary {
            background-image: linear-gradient(310deg, #627594 0%, #a8b8d8 100%) !important;
        }
        .text-primary {
            color: #cb0c9f !important;
        }
        .btn-link {
            background: none !important;
            border: none !important;
            box-shadow: none !important;
        }
        .btn-link:hover {
            transform: none !important;
            box-shadow: none !important;
        }
        .gap-1 { gap: 0.25rem !important; }
        .gap-2 { gap: 0.5rem !important; }
        /* Custom Scrollbar for Sidebar */
        #sidenav-collapse-main::-webkit-scrollbar {
            width: 4px;
        }
        #sidenav-collapse-main::-webkit-scrollbar-track {
            background: transparent;
        }
        #sidenav-collapse-main::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.05);
            border-radius: 10px;
        }
        #sidenav-collapse-main:hover::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="g-sidenav-show">
    <!-- Sidebar -->
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3" id="sidenav-main">
        <div class="sidenav-header">
            <a class="navbar-brand m-0 text-decoration-none" href="{{ route('home') }}">
                @if(isset($company) && $company->logo_path)
                    <img src="{{ asset('storage/' . $company->logo_path) }}" alt="Logo" style="height: 40px;">
                @endif
                <span class="ms-1 sidenav-brand-text">{{ isset($company) ? $company->name : 'CORRUGO MIS' }}</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="ms-1 h-auto" id="sidenav-collapse-main" style="overflow-y: auto; overflow-x: hidden; max-height: calc(100vh - 150px);">
            <ul class="navbar-nav">
                @auth
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('home') ? 'active bg-white shadow-sm' : '' }}" href="{{ route('home') }}" title="Dashboard">
                        <div class="nav-link-icon {{ Request::routeIs('home') ? 'bg-gradient-primary text-white' : 'bg-white shadow-sm' }}">
                            <i class="fas fa-home {{ Request::routeIs('home') ? 'text-white' : 'text-primary' }} icon-sm"></i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
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
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Production Management</h6>
                </li>
                @endif

                @if(Auth::user()->isAdmin() || Auth::user()->canViewMenu('customers'))
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('customers.*') ? 'active bg-white shadow-sm' : '' }}" href="{{ route('customers.index') }}" title="Customers">
                        <div class="nav-link-icon {{ Request::routeIs('customers.*') ? 'bg-gradient-primary text-white' : 'bg-white shadow-sm' }}">
                            <i class="fas fa-users {{ Request::routeIs('customers.*') ? 'text-white' : 'text-success' }} icon-sm"></i>
                        </div>
                        <span class="nav-link-text ms-1">Customers</span>
                    </a>
                </li>
                @endif

                @if(Auth::user()->isAdmin() || Auth::user()->canViewMenu('job_cards'))
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('job-cards.*') ? 'active bg-white shadow-sm' : '' }}" href="{{ route('job-cards.index') }}" title="Job Cards">
                        <div class="nav-link-icon {{ Request::routeIs('job-cards.*') ? 'bg-gradient-primary text-white' : 'bg-white shadow-sm' }}">
                            <i class="fas fa-file-alt {{ Request::routeIs('job-cards.*') ? 'text-white' : 'text-warning' }} icon-sm"></i>
                        </div>
                        <span class="nav-link-text ms-1">Job Cards</span>
                    </a>
                </li>
                @endif

                @if(Auth::user()->isAdmin() || Auth::user()->canViewMenu('issue_job_order'))
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('production.create') ? 'active bg-white shadow-sm' : '' }}" href="{{ route('production.create') }}" title="Issue Job Order">
                        <div class="nav-link-icon {{ Request::routeIs('production.create') ? 'bg-gradient-primary text-white' : 'bg-white shadow-sm' }}">
                            <i class="fas fa-clipboard-list {{ Request::routeIs('production.create') ? 'text-white' : 'text-danger' }} icon-sm"></i>
                        </div>
                        <span class="nav-link-text ms-1">Issue Job Order</span>
                    </a>
                </li>
                @endif

                @if(Auth::user()->isAdmin() || Auth::user()->canViewMenu('corrugation'))
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('corrugation.*') ? 'active bg-white shadow-sm' : '' }}" href="{{ route('corrugation.index') }}" title="Corrugation Plant">
                        <div class="nav-link-icon {{ Request::routeIs('corrugation.*') ? 'bg-gradient-primary text-white' : 'bg-white shadow-sm' }}">
                            <i class="fas fa-cogs {{ Request::routeIs('corrugation.*') ? 'text-white' : 'text-dark' }} icon-sm"></i>
                        </div>
                        <span class="nav-link-text ms-1">Corrugation Plant</span>
                    </a>
                </li>
                @endif

                @if(Auth::user()->isAdmin() || Auth::user()->canViewMenu('production'))
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('production.index') ? 'active bg-white shadow-sm' : '' }}" href="{{ route('production.index') }}" title="Production">
                        <div class="nav-link-icon {{ Request::routeIs('production.index') ? 'bg-gradient-primary text-white' : 'bg-white shadow-sm' }}">
                            <i class="fas fa-industry {{ Request::routeIs('production.index') ? 'text-white' : 'text-primary' }} icon-sm"></i>
                        </div>
                        <span class="nav-link-text ms-1">Production</span>
                    </a>
                </li>
                @endif
                
                @if(Auth::user()->isAdmin() || Auth::user()->canViewMenu('masters'))
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Setup</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('masters.*') ? 'active bg-white shadow-sm' : '' }}" href="{{ route('masters.index') }}" title="Masters">
                        <div class="nav-link-icon {{ Request::routeIs('masters.*') ? 'bg-gradient-primary text-white' : 'bg-white shadow-sm' }}">
                            <i class="fas fa-database {{ Request::routeIs('masters.*') ? 'text-white' : 'text-info' }} icon-sm"></i>
                        </div>
                        <span class="nav-link-text ms-1">Masters</span>
                    </a>
                </li>
                @endif

                @if(Auth::user()->isAdmin() || Auth::user()->canViewMenu('admin') || Auth::user()->canViewMenu('audit_logs'))
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Admin</h6>
                </li>
                @if(Auth::user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('users.*') ? 'active bg-white shadow-sm' : '' }}" href="{{ route('users.index') }}" title="Users">
                        <div class="nav-link-icon {{ Request::routeIs('users.*') ? 'bg-gradient-primary text-white' : 'bg-white shadow-sm' }}">
                            <i class="fas fa-user-shield {{ Request::routeIs('users.*') ? 'text-white' : 'text-primary' }} icon-sm"></i>
                        </div>
                        <span class="nav-link-text ms-1">Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('company.setup') ? 'active bg-white shadow-sm' : '' }}" href="{{ route('company.setup') }}" title="Company Setup">
                        <div class="nav-link-icon {{ Request::routeIs('company.setup') ? 'bg-gradient-primary text-white' : 'bg-white shadow-sm' }}">
                            <i class="fas fa-building {{ Request::routeIs('company.setup') ? 'text-white' : 'text-dark' }} icon-sm"></i>
                        </div>
                        <span class="nav-link-text ms-1">Company Setup</span>
                    </a>
                </li>
                @endif
                @if(Auth::user()->isAdmin() || Auth::user()->canViewMenu('audit_logs'))
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('audits.*') ? 'active bg-white shadow-sm' : '' }}" href="{{ route('audits.index') }}" title="Audit Logs">
                        <div class="nav-link-icon {{ Request::routeIs('audits.*') ? 'bg-gradient-primary text-white' : 'bg-white shadow-sm' }}">
                            <i class="fas fa-history {{ Request::routeIs('audits.*') ? 'text-white' : 'text-warning' }} icon-sm"></i>
                        </div>
                        <span class="nav-link-text ms-1">Audit Logs</span>
                    </a>
                </li>
                @endif
                @endif
                
                <li class="nav-item mt-auto mb-3">
                    <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Logout">
                        <div class="nav-link-icon bg-white shadow-sm text-danger">
                            <i class="fas fa-sign-out-alt"></i>
                        </div>
                        <span class="nav-link-text ms-1">Logout</span>
                    </a>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">
                        <div class="nav-link-icon bg-white shadow-sm text-primary">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                        <span class="nav-link-text ms-1">Login</span>
                    </a>
                </li>
                @endauth
            </ul>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb" class="d-flex align-items-center">
                    <div class="sidenav-toggler-inner cursor-pointer me-3 d-none d-xl-block" id="sidebar-toggle-btn">
                        <i class="fas fa-bars"></i>
                    </div>
                    <h6 class="font-weight-bolder mb-0 text-primary">CORRUGO MIS (Soft UI Sales)</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <ul class="navbar-nav justify-content-end ms-md-auto">
                        @auth
                        <li class="nav-item dropdown px-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-palette cursor-pointer fs-5 text-primary"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4 shadow" aria-labelledby="dropdownMenuButton" style="min-width: 250px;">
                                <li><div class="dropdown-header text-center">Choose Your Theme</div></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('theme.update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="theme" value="staradmin">
                                        <button type="submit" class="dropdown-item border-radius-md d-flex align-items-center justify-content-between">
                                            Star Admin 2 @if(auth()->user()->theme == 'staradmin') <i class="fas fa-check text-success ms-2"></i> @endif
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('theme.update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="theme" value="softui_sales">
                                        <button type="submit" class="dropdown-item border-radius-md d-flex align-items-center justify-content-between">
                                            Soft UI (Sales) @if(auth()->user()->theme == 'softui_sales') <i class="fas fa-check text-success ms-2"></i> @endif
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('theme.update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="theme" value="sneat_sales">
                                        <button type="submit" class="dropdown-item border-radius-md d-flex align-items-center justify-content-between">
                                            Sneat (Sales) @if(auth()->user()->theme == 'sneat_sales') <i class="fas fa-check text-success ms-2"></i> @endif
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                        @endauth
                        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav" onclick="document.getElementById('sidenav-main').classList.toggle('show')">
                                <div class="sidenav-toggler-inner">
                                    <i class="fas fa-bars"></i>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item dropdown ps-3 d-flex align-items-center">
                            @auth
                            <a href="javascript:;" class="nav-link text-body p-0" id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-user cursor-pointer fs-5 text-secondary"></i>
                                <span class="d-sm-inline d-none ms-1 fw-bold">{{ auth()->user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4 shadow" aria-labelledby="userMenuButton">
                                <li>
                                    <a class="dropdown-item border-radius-md" href="{{ route('profile.edit') }}">
                                        <i class="fas fa-user-circle me-2"></i> Profile
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item border-radius-md" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </a>
                                </li>
                            </ul>
                            @else
                            <a href="{{ route('login') }}" class="nav-link text-body p-0 font-weight-bold">
                                <i class="fa fa-user me-sm-1"></i>
                                <span class="d-sm-inline d-none">Sign In</span>
                            </a>
                            @endauth
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>

        <!-- End Navbar -->
        <div class="container-fluid py-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show text-white shadow" role="alert" style="background-image: linear-gradient(310deg, #17ad37 0%, #98ec2d 100%);">
                    <span class="alert-icon"><i class="fas fa-check"></i></span>
                    <span class="alert-text">{{ session('success') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show text-white shadow" role="alert" style="background-image: linear-gradient(310deg, #ea0606 0%, #ff667c 100%);">
                    <span class="alert-icon"><i class="fas fa-exclamation-triangle"></i></span>
                    <span class="alert-text">{{ session('error') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @yield('content')

            <!-- Footer -->
            <footer class="footer pt-3 mt-5">
                <div class="container-fluid">
                    <div class="row align-items-center justify-content-lg-between">
                        <div class="col-lg-6 mb-lg-0 mb-4">
                            <div class="copyright text-center text-sm text-muted text-lg-start">
                                CORRUGO MIS | Contact for Purchase: 0300-2566358
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                                <li class="nav-item">
                                    <span class="nav-link text-muted">All rights reserved to SACHAAN TECH SOL. © {{ date('Y') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar Collapse Toggle
        const sidebar = document.getElementById('sidenav-main');
        const sidebarToggleBtn = document.getElementById('sidebar-toggle-btn');
        const isCollapsed = localStorage.getItem('sidebar-collapsed-soft-ui-sales') === 'true';

        if (isCollapsed) {
            sidebar.classList.add('collapsed');
        }

        if (sidebarToggleBtn) {
            sidebarToggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
                localStorage.setItem('sidebar-collapsed-soft-ui-sales', sidebar.classList.contains('collapsed'));
            });
        }
    </script>
</body>
</html>
