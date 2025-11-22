<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AirPay Cashier')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #ffffff;
            min-height: 100vh;
        }
        .sidebar {
            background: #00004d;
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(217, 212, 212, 0.2);
            min-height: 100vh;
            position: fixed;
            width: 250px;
            left: 0;
            top: 0;
            z-index: 1000;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
        }
        .nav-link {
            color: #ffffffff;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 0;
            transition: all 0.3s ease;
        }
        .nav-link:hover, .nav-link.active {
            background: #373774ff;
            color: white;
            transform: translateX(5px);
        }
        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background: #817DFF;
            color: black;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .table {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            overflow: hidden;
        }
        .table th {
            background: #00004d;
            color: white;
            font-weight: 600;
            border: none;
        }
        .sidebar-brand {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            font-weight: 700;
            font-size: 1.5rem;
            background: #00004d;
            color: white;
        }
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .status-tersedia {
            background: #d4edda;
            color: #155724;
        }
        .status-hampir-habis {
            background: #fff3cd;
            color: #856404;
        }
        .status-habis {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    @auth
    <div class="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('img/logo(d).png') }}" alt="logo">
        </div>
        <div class="p-3">
            <nav class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
                <a class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}" href="{{ route('transactions.index') }}">
                    <i class="fas fa-shopping-cart me-2"></i>Transaksi
                </a>
                <div class="nav-link dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#productsCollapse">
                    <i class="fa-solid fa-basket-shopping me-2"></i>Produk
                </div>
                <div class="collapse" id="productsCollapse">
                    <a class="nav-link ms-3 {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                        <i class="fas fa-box me-2"></i>Daftar Produk
                    </a>
                    <a class="nav-link ms-3 {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                        <i class="fas fa-tags me-2"></i>Kategori
                    </a>
                </div>
                <div class="nav-link dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#reportsCollapse">
                    <i class="fas fa-chart-bar me-2"></i>Laporan
                </div>
                <div class="collapse" id="reportsCollapse">
                    <a class="nav-link ms-3" href="{{ route('reports.keuangan.index') }}">
                        <i class="fas fa-money-bill me-2"></i>Keuangan
                    </a>
                    <a class="nav-link ms-3" href="{{ route('reports.inventaris.index') }}">
                        <i class="fas fa-warehouse me-2"></i>Inventaris
                    </a>
                </div>
                <hr>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </button>
                </form>
            </nav>
        </div>
    </div>
    @endauth

    <div class="main-content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    @stack('scripts')
</body>
</html>