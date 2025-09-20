<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Warehouse Management System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #c8e7f7 0%, #eaf6fc 100%);
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            transition: transform 0.3s ease-in-out;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            z-index: 1000;
            transform: translateX(-100%);
            padding-top: 70px !important;
        }
        
        .sidebar.show {
            transform: translateX(0);
        }
        
        /* Desktop styles */
        @media (min-width: 768px) {
            .sidebar {
                position: fixed;
                transform: translateX(-100%);
                width: 250px;
                padding-top: 70px !important;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            #main-content {
                margin-left: 0;
                transition: margin-left 0.3s ease-in-out;
            }
            
            #main-content.shifted {
                margin-left: 250px;
            }
        }
        
        /* Mobile overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }
        
        .sidebar-overlay.show {
            display: block;
        }
        
        /* Hamburger button styles */
        #sidebarToggle {
            border: none;
            background: transparent;
            color: #5a9bc4;
            font-size: 1.2rem;
            padding: 0.5rem;
        }
        
        #sidebarToggle:hover {
            background-color: #f8f9fa;
            color: #5a9bc4;
        }
        
        .sidebar .nav-link {
            color: rgba(0, 0, 0, 0.9);
            padding: 12px 20px;
            margin: 4px 0;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: black;
            background: rgba(255, 255, 255, 0.8);
            transform: translateX(5px);
            box-shadow: 0 2px 8px rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        
        .main-content {
            background: linear-gradient(135deg, #f0f8ff 0%, #e6f3ff 100%);
            min-height: 100vh;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        
        .card-header {
            background: #c8e7f7;
            color: black;
            border-radius: 12px 12px 0 0 !important;
            border: none;
        }
        
        .btn-primary {
            background: #c8e7f7;
            border: none;
            border-radius: 8px;
            color: black;
        }
        
        .btn-primary:hover {
            background: #95cae8;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(168, 216, 240, 0.4);
        }
        
        .stat-card {
            background: #c8e7f7;
            color: black;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .table {
            border-radius: 12px;
            overflow: hidden;
        }
        
        .table thead th {
            background: #eaf6fc;
            color: black;
            border: none;
            font-weight: 500;
        }
        
        .navbar {
            background: white !important;
        }
        
        /* Navbar icon links styling */
        .navbar-nav .nav-link {
            color: #5a9bc4 !important;
            padding: 0.5rem 0.75rem !important;
            margin: 0 0.25rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 1.1rem;
        }
        
        .navbar-nav .nav-link:hover {
            background-color: #f0f8ff;
            color: #2c5282 !important;
            transform: translateY(-2px);
        }
        
        .navbar-nav .nav-link.active {
            background: linear-gradient(135deg, #c8e7f7 0%, #eaf6fc 100%);
            color: black !important;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
        }
        
        .btn-info {
            background: #b8e0f5;
            border: none;
            color: #2c5282;
        }
        
        .btn-info:hover {
            background: #d1e7ff;
            color: #2c5282;
        }

        /* .bg-primary {
            background: #c8e7f7 !important;
        }

        .bg-success {
            background: #b2e8cb !important;
        }

        .bg-danger {
            background: #f7b9bd !important;
        }

        .bg-warning {
            background: #f9e7b6 !important;
        } */
        
        .badge.bg-primary {
            background: #6ba3c5 !important;
        }
        
        .badge.bg-info {
            background: #e6f3ff !important;
            color: #2c5282 !important;
        }

        .badge.bg-success {
            background: #d2f4e1 !important;
            color: #256b44 !important;
        }

        .badge.bg-danger {
            background: #f8d7da !important;
            color: #721c24 !important;
        }

        .badge.bg-warning {
            background: #fff3cd !important;
            color: #856404 !important;
        }

        .badge.bg-secondary {
            background: #eff2f4 !important;
            color: #595c5e !important;
        }

        .btn-info {
            background-color: #e6f3ff !important;
            border-color: #e6f3ff !important;
            color: #2c5282 !important;
        }

        .btn-warning {
            background-color: #fff3cd !important;
            border-color: #fff3cd !important;
            color: #856404 !important;
        }

        .btn-danger {
            background-color: #f8d7da !important;
            border-color: #f8d7da !important;
            color: #721c24 !important;
        }
        
        .text-primary {
            color: #5a9bc4 !important;
        }
        
        .btn-outline-primary {
            border-color: #eaf6fc;
            color: #5a9bc4;
        }
        
        .btn-outline-primary:hover {
            background-color: #c8e7f7;
            border-color: #eaf6fc;
            color: black;
        }
        
        /* Pagination styling */
        .pagination {
            margin-bottom: 0;
            gap: 0.25rem;
        }
        
        .pagination .page-item {
            margin: 0 2px;
        }
        
        .pagination .page-link {
            color: #5a9bc4;
            border: 1px solid #c8e7f7;
            border-radius: 6px !important;
            padding: 0.375rem 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }
        
        .pagination .page-item.active .page-link {
            background: #c8e7f7;
            border-color: #c8e7f7;
            color: #2c5282;
            font-weight: 600;
        }
        
        .pagination .page-link:hover {
            background: #e6f3ff;
            border-color: #c8e7f7;
            color: #2c5282;
        }
        
        .pagination .page-item.disabled .page-link {
            color: #9ca3af;
            background-color: #f9fafb;
            border-color: #e5e7eb;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            font-size: 0.8rem;
            padding: 0.375rem 0.45rem;
        }
        
        /* Ensure table is responsive */
        .table-responsive {
            border-radius: 0 0 12px 12px;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white">
            <div class="container-fluid">
                <!-- Hamburger Menu Button -->
                <button class="btn btn-outline-primary me-3" type="button" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                
                <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                    <i class="fas fa-warehouse text-primary me-2"></i>
                    <span class="d-none d-sm-inline">Warehouse Management System</span>
                    <span class="d-sm-none">WMS</span>
                </a>
                
                <div class="navbar-nav ms-auto d-flex flex-row">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                       href="{{ route('dashboard') }}" title="Dashboard">
                        <i class="fas fa-tachometer-alt"></i>
                    </a>
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" 
                       href="{{ route('products.index') }}" title="Products">
                        <i class="fas fa-box"></i>
                    </a>
                    <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" 
                       href="{{ route('categories.index') }}" title="Categories">
                        <i class="fas fa-tags"></i>
                    </a>
                    <a class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" 
                       href="{{ route('suppliers.index') }}" title="Suppliers">
                        <i class="fas fa-truck"></i>
                    </a>
                    <a class="nav-link {{ request()->routeIs('warehouses.*') ? 'active' : '' }}" 
                       href="{{ route('warehouses.index') }}" title="Warehouses">
                        <i class="fas fa-warehouse"></i>
                    </a>
                    <a class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}" 
                       href="{{ route('inventory.index') }}" title="Inventory">
                        <i class="fas fa-boxes"></i>
                    </a>
                    <a class="nav-link {{ request()->routeIs('stock-movements.*') ? 'active' : '' }}" 
                       href="{{ route('stock-movements.index') }}" title="Stock Movements">
                        <i class="fas fa-exchange-alt"></i>
                    </a>
                </div>
            </div>
        </nav>

        <!-- Sidebar Overlay for mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <!-- Sidebar (positioned absolutely) -->
        <div class="sidebar p-3" id="sidebar">
            <nav class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                   href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>Dashboard
                </a>
                <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" 
                   href="{{ route('products.index') }}">
                    <i class="fas fa-box"></i>Products
                </a>
                <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" 
                   href="{{ route('categories.index') }}">
                    <i class="fas fa-tags"></i>Categories
                </a>
                <a class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" 
                   href="{{ route('suppliers.index') }}">
                    <i class="fas fa-truck"></i>Suppliers
                </a>
                <a class="nav-link {{ request()->routeIs('warehouses.*') ? 'active' : '' }}" 
                   href="{{ route('warehouses.index') }}">
                    <i class="fas fa-warehouse"></i>Warehouses
                </a>
                <a class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}" 
                   href="{{ route('inventory.index') }}">
                    <i class="fas fa-boxes"></i>Inventory
                </a>
                <a class="nav-link {{ request()->routeIs('stock-movements.*') ? 'active' : '' }}" 
                   href="{{ route('stock-movements.index') }}">
                    <i class="fas fa-exchange-alt"></i>Stock Movements
                </a>
            </nav>
        </div>
        
        <!-- Main content (full width) -->
        <div id="main-content">
            <div class="main-content p-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Please fix the following errors:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            // Toggle sidebar function
            function toggleSidebar() {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
                document.body.classList.toggle('sidebar-open');
                
                // On desktop, shift main content
                const mainContent = document.getElementById('main-content');
                if (window.innerWidth >= 768) {
                    mainContent.classList.toggle('shifted');
                }
            }
            
            // Close sidebar function
            function closeSidebar() {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                document.body.classList.remove('sidebar-open');
                
                // On desktop, remove main content shift
                const mainContent = document.getElementById('main-content');
                mainContent.classList.remove('shifted');
            }
            
            // Event listeners
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', toggleSidebar);
            }
            
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeSidebar);
            }
            
            // Close sidebar when clicking on nav links (mobile)
            const navLinks = sidebar.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        closeSidebar();
                    }
                });
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    closeSidebar();
                }
            });
            
            // Prevent body scroll when sidebar is open
            const style = document.createElement('style');
            style.textContent = `
                body.sidebar-open {
                    overflow: hidden;
                }
            `;
            document.head.appendChild(style);
        });
    </script>
    
    @yield('scripts')
</body>
</html>
