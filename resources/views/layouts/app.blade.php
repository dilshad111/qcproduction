<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($company) ? $company->name : config('app.name', 'Laravel') }}</title>
    @if(isset($company) && $company->logo_path)
        <link rel="icon" href="{{ asset('storage/' . $company->logo_path) }}">
    @endif

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body data-theme="{{ Auth::user()->theme ?? 'light' }}">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ isset($company) ? $company->name : config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}">Dashboard</a>
                            </li>
                            @if(Auth::user()->canViewMenu('customers'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('customers.index') }}">Customers</a>
                            </li>
                            @endif
                            
                            @if(Auth::user()->canViewMenu('masters'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('masters.index') }}">Masters</a>
                            </li>
                            @endif

                            @if(Auth::user()->canViewMenu('job_cards'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('job-cards.index') }}">Job Cards</a>
                            </li>
                            @endif

                            @if(Auth::user()->canViewMenu('production'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('production.index') }}">Production</a>
                            </li>
                            @endif
                            
                            @if(Auth::user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('users.index') }}">Users</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('company.setup') }}">Company Setup</a>
                            </li>
                            @endif
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @auth
                        <!-- Theme Selector -->
                        <li class="nav-item dropdown">
                            <a id="themeDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                Theme
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="themeDropdown">
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('theme-light-form').submit();">Light</a>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('theme-dark-form').submit();">Dark</a>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('theme-blue-form').submit();">Blue</a>

                                <form id="theme-light-form" action="{{ route('theme.update') }}" method="POST" class="d-none"> @csrf <input type="hidden" name="theme" value="light"> </form>
                                <form id="theme-dark-form" action="{{ route('theme.update') }}" method="POST" class="d-none"> @csrf <input type="hidden" name="theme" value="dark"> </form>
                                <form id="theme-blue-form" action="{{ route('theme.update') }}" method="POST" class="d-none"> @csrf <input type="hidden" name="theme" value="blue"> </form>
                            </div>
                        </li>
                        @endauth

                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
