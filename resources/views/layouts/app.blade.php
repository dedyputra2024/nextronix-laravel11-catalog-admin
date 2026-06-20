<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Nextronix - Technology Catalog' }}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom-electro.css') }}" rel="stylesheet">
</head>
<body class="nx-public-body">
    <header class="nx-site-header">
        <div class="nx-topbar d-none d-lg-block">
            <div class="container-fluid px-5">
                <div class="row align-items-center">
                    <div class="col-lg-4">
                        <a href="{{ route('contact') }}" class="nx-top-link"><i class="fas fa-headset me-2"></i>Contact</a>
                    </div>
                    <div class="col-lg-4 text-center">
                    </div>
                    <div class="col-lg-4 text-end">
                        @auth
                            @if(auth()->user()->is_admin || auth()->user()->hasAnyRole(['admin', 'super-admin', 'staff']))
                                <a href="{{ route('admin.dashboard') }}" class="nx-top-link me-3"><i class="fas fa-user-shield me-1"></i>Admin Panel</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button class="btn btn-link nx-top-link p-0" type="submit">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="nx-top-link"><i class="fas fa-lock me-1"></i>Admin Login</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <div class="nx-brand-row">
            <div class="container-fluid px-5">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-3 col-md-4">
    <a href="{{ route('home') }}" class="nx-brand text-decoration-none">
        <img src="{{ asset('img/logo.png') }}" alt="Nextronix Logo" class="nx-brand-img">
        <span class="nx-brand-text">Nextronix</span>
    </a>
</div>
                    <div class="col-lg-6 col-md-8">
                        <form action="{{ route('shop') }}" method="GET" class="nx-search-shell">
                            <input name="q" value="{{ request('q') }}" type="text" placeholder="Cari laptop, smartphone, audio, kamera......" aria-label="Cari produk">
                            <button type="submit" aria-label="Cari"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                    <div class="col-lg-3 d-none d-lg-flex justify-content-end">
                        <a href="https://t.me/kerangcupu" target="_blank" rel="noopener noreferrer" class="nx-contact-pill text-decoration-none">
    <span><i class="fab fa-telegram-plane"></i></span>
    <div>
        <strong>Kerja Sama Brand</strong>
        <small>Hubungi lewat Telegram</small>
    </div>
</a>
                    </div>
                </div>
            </div>
        </div>

        <nav class="nx-main-nav navbar navbar-expand-lg">
            <div class="container-fluid px-5">
                <div class="dropdown d-none d-lg-block">
                    <button class="nx-category-button dropdown-toggle" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bars me-2"></i>Kategori
                    </button>
                    <ul class="dropdown-menu nx-category-menu" aria-labelledby="categoryDropdown">
                        <li><a class="dropdown-item" href="{{ route('shop') }}">Semua Produk</a></li>
                        @foreach($navCategories as $category)
                            <li><a class="dropdown-item" href="{{ route('shop', ['category' => $category->slug]) }}">{{ $category->name }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <a href="{{ route('home') }}" class="navbar-brand d-lg-none nx-mobile-brand">
                    <i class="fas fa-microchip me-2"></i>Nextronix
                </a>
                <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-label="Toggle navigation">
                    <span class="fas fa-bars"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto py-0">
                        <a href="{{ route('home') }}" class="nav-item nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                        <a href="{{ route('shop') }}" class="nav-item nav-link {{ request()->routeIs('shop') || request()->routeIs('products.show') ? 'active' : '' }}">Katalog</a>
                        <a href="{{ route('contact') }}" class="nav-item nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
                        @auth
                            @if(auth()->user()->is_admin || auth()->user()->hasAnyRole(['admin', 'super-admin', 'staff']))
                                <a href="{{ route('admin.dashboard') }}" class="nav-item nav-link">Admin</a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main>
        @include('partials.alerts')
        @yield('content')
    </main>

    <footer class="nx-footer">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-5">
                <h4 class="text-white mb-3">
                    <i class="fas fa-microchip me-2"></i>Nextronix
                </h4>
                <p class="mb-0">
                    Nextronix adalah katalog produk teknologi modern yang membantu pelanggan menemukan perangkat pilihan secara cepat, informatif, dan terpercaya.
                </p>
            </div>

            <div class="col-lg-3">
                <h5 class="text-white mb-3">Menu</h5>
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('shop') }}">Katalog</a>
                <a href="{{ route('contact') }}">Contact</a>
            </div>

            <div class="col-lg-4">
                <h5 class="text-white mb-3">Kerja Sama</h5>
                <p class="mb-3">
                    Terbuka untuk kerja sama brand, katalog produk, dan penawaran teknologi.
                </p>
                <a href="https://t.me/kerangcupu" target="_blank" rel="noopener noreferrer" class="text-decoration-none">
                    <i class="fab fa-telegram-plane me-2"></i>Hubungi via Telegram
                </a>
            </div>
        </div>
    </div>
</footer>

    <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
