<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PT Rocker Technology Innovation')</title>
    
    <!-- FAVICON PNG -->
    <link rel="icon" href="{{ asset('image/logo.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Tambahkan CSS ini di bagian atas style Anda */
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden; /* HILANGKAN SCROLL GLOBAL */
        }
        
        :root {
            --color-primary: #2C5AA0;
            --color-secondary: #4A90E2;
            --color-light: #E8F1FC;
            --color-dark: #1A3A6B;
            --color-success: #28A745;
            --color-warning: #FFC107;
            --color-danger: #DC3545;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
        
        /* Navbar tetap di atas */
        .navbar-color {
            background-color: var(--color-primary);
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            flex-shrink: 0; /* Navbar tidak mengecil */
            z-index: 1030;
        }
        
        /* Container utama untuk sidebar + content */
        .main-container {
            display: flex;
            flex: 1;
            overflow: hidden; /* Hilangkan scroll global */
        }
        
        /* Sidebar tetap */
        .sidebar {
            background-color: white;
            box-shadow: 2px 0 5px rgba(0,0,0,.05);
            height: calc(100vh - 73px); /* Tinggi penuh minus navbar */
            overflow-y: auto; /* Sidebar bisa discroll sendiri */
            position: sticky;
            top: 73px;
            flex-shrink: 0;
            z-index: 1020;
        }
        
        /* Area konten utama yang bisa discroll */
        .content-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden; /* Hilangkan overflow di parent */
        }
        
        .main-content {
            flex: 1;
            overflow-y: auto; /* HANYA INI YANG BISA SCROLL */
            padding: 25px;
            background-color: #f8f9fa;
        }
        
        .sidebar .nav-link {
            color: #555;
            padding: 12px 20px;
            border-left: 4px solid transparent;
            transition: all 0.3s;
            border-radius: 0;
            margin: 2px 0;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: var(--color-light);
            color: var(--color-primary);
            border-left-color: var(--color-primary);
        }
        
        /* Custom scrollbar untuk main content */
        .main-content::-webkit-scrollbar {
            width: 8px;
        }
        
        .main-content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        .main-content::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        .main-content::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }
        
        /* Custom scrollbar untuk sidebar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: #d1d1d1;
            border-radius: 3px;
        }
        
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #b1b1b1;
        }
        
        /* Sisa style Anda tetap sama */
        .card-color {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,.05);
            transition: transform 0.3s;
        }
        
        .card-color:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,.1);
        }
        
        .bg-color-light {
            background-color: var(--color-light);
        }
        
        .text-color-primary {
            color: var(--color-primary);
        }
        
        .btn-color {
            background-color: var(--color-primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            transition: all 0.3s;
        }
        
        .btn-color:hover {
            background-color: var(--color-dark);
            color: white;
            transform: translateY(-1px);
        }
        
        .task-force {
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .quick-action {
            background: white;
            border-radius: 10px;
            padding: 25px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            border: 1px solid #eee;
            height: 100%;
        }
        
        .quick-action:hover {
            border-color: var(--color-primary);
            box-shadow: 0 5px 15px rgba(44, 90, 160, 0.1);
        }
        
        .quick-action i {
            font-size: 2rem;
            color: var(--color-primary);
            margin-bottom: 15px;
        }
        
        .chart-container {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 25px;
        }
        
        .table-client {
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .badge-new {
            background-color: var(--color-warning);
            color: #000;
        }
        
        .badge-active {
    background-color: var(--color-success);
    color: white;
    position: relative;
    padding: 6px 12px;
    border-radius: 6px;
}

.badge-inactive {
    background-color: #6c757d;
    color: white;
    position: relative;
    padding: 6px 12px;
    border-radius: 6px;
}

/* SELECT TRANSPARAN */
.status-select {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
}

        
        .form-control:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 0.25rem rgba(44, 90, 160, 0.25);
        }
        
        .btn-outline-color {
            color: var(--color-primary);
            border-color: var(--color-primary);
        }
        
        .btn-outline-color:hover {
            background-color: var(--color-primary);
            color: white;
        }
        
        .pagination .page-item.active .page-link {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
        }
        
        .pagination .page-link {
            color: var(--color-primary);
        }
        
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            padding: 20px;
        }
        
        .auth-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            padding: 40px;
        }
        
        .auth-logo {
            text-align: center;
            margin-bottom: 30px;
            color: var(--color-primary);
        }
        
        .welcome-container {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .welcome-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.2);
            padding: 50px;
            max-width: 500px;
            width: 100%;
            color: #333;
        }
        
        .welcome-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .welcome-logo h1 {
            color: var(--color-primary);
            font-weight: bold;
        }
        
        .welcome-badge {
            display: inline-block;
            background: var(--color-success);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-top: 10px;
        }
        
        .welcome-features {
            list-style: none;
            padding: 0;
            margin: 30px 0;
        }
        
        .welcome-features li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        .welcome-features li i {
            color: var(--color-primary);
            margin-right: 10px;
        }
    </style>
</head>
<body>
    @if(Request::is('/'))
        @yield('welcome-content')
    @elseif(Request::is('login') || Request::is('register'))
        @yield('auth-content')
    @else
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark navbar-color">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <a class="navbar-brand d-flex align-items-center me-0" href="{{ route('dashboard') }}">
                        <img src="{{ asset('image/logo.png') }}" 
                             alt="Logo"
                             class="me-2"
                             style="height: 40px; width: auto;">
                        <strong class="brand-text">PT Rocker Technology Innovation</strong>
                    </a>
                </div>
                
                <div class="navbar-text">
                    @auth
                    <span class="text-light me-3">{{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-light">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-sm btn-light me-2">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-sm btn-outline-light">
                        <i class="bi bi-person-plus"></i> Register
                    </a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Main Container (Sidebar + Content) -->
        <div class="main-container">
            <!-- Sidebar -->
            <div class="col-lg-2 col-md-3 sidebar p-0">
                <div class="p-3">
                    <h6 class="text-uppercase text-muted mb-3">MENU UTAMA</h6>
                    <nav class="nav flex-column">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                           href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                        <a class="nav-link {{ request()->routeIs('data-client.index') ? 'active' : '' }}" 
                           href="{{ route('data-client.index') }}">
                            <i class="bi bi-people me-2"></i> Data Client
                        </a>
                        <a class="nav-link {{ request()->routeIs('add-clients.create') ? 'active' : '' }}" 
                           href="{{ route('add-clients.create') }}">
                            <i class="bi bi-person-plus me-2"></i> Tambah Client
                        </a>
                        <a class="nav-link {{ request()->routeIs('statistik.index') ? 'active' : '' }}" 
                           href="{{ route('statistik.index') }}">
                            <i class="bi bi-bar-chart me-2"></i> Statistik
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Main Content (HANYA INI YANG SCROLL) -->
                <div class="main-content">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    
                    @yield('content')
                </div>
            </div>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>