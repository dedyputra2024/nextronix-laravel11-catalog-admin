<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Nextronix Admin' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom-electro.css') }}" rel="stylesheet">
</head>
<body class="admin-body">
    <div class="admin-shell">
        <aside class="admin-sidebar-pro">
            <a href="{{ route('admin.dashboard') }}" class="admin-brand text-decoration-none">
                <span class="admin-brand-icon"><i class="fas fa-bolt"></i></span>
                <span>
                    <strong>Nextronix</strong>
                    <small>Admin Console</small>
                </span>
            </a>

            <nav class="admin-menu">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fas fa-chart-line"></i>Dashboard</a>
                @can('manage categories')<a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"><i class="fas fa-layer-group"></i>Kategori</a>@endcan
                @can('manage products')<a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}"><i class="fas fa-box-open"></i>Produk</a>@endcan
                @can('manage orders')<a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"><i class="fas fa-receipt"></i>Orders</a>@endcan
                @can('manage users')<a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="fas fa-users"></i>Users</a>@endcan
                @can('manage roles')<a href="{{ route('admin.roles.index') }}" class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}"><i class="fas fa-user-shield"></i>Roles</a>@endcan
            </nav>

            <div class="admin-sidebar-footer">
                <a href="{{ route('home') }}" class="btn btn-light w-100 rounded-pill"><i class="fas fa-store me-2"></i>Lihat Toko</a>
            </div>
        </aside>

        <main class="admin-main">
            <header class="admin-topbar">
                <div>
                    <p class="admin-eyebrow mb-1">Nextronix Admin Panel</p>
                    <h1 class="admin-page-title">{{ $title ?? 'Dashboard' }}</h1>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="text-end d-none d-md-block">
                        <strong>{{ auth()->user()->name }}</strong><br>
                        <small class="text-muted">{{ auth()->user()->roles->pluck('name')->join(', ') ?: 'admin' }}</small>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-danger btn-sm rounded-pill"><i class="fas fa-sign-out-alt me-1"></i>Logout</button>
                    </form>
                </div>
            </header>

            @include('partials.alerts')
            @yield('admin_content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
