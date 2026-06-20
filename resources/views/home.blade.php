@extends('layouts.app', ['title' => 'Nextronix - Technology Catalog'])

@section('content')
<section class="hero-portfolio nx-hero">
    <div class="container py-5">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <span class="badge hero-badge mb-3"><i class="fas fa-microchip me-2"></i>SMART TECHNOLOGY CATALOG</span>
                <h1 class="display-4 fw-bold mb-3">Temukan Produk Teknologi Terbaik untuk Kebutuhan Digital Anda</h1>
                <p class="lead text-muted mb-4">Nextronix menghadirkan katalog produk teknologi modern yang memudahkan pelanggan menemukan perangkat pilihan secara cepat, informatif, dan terpercaya.</p>
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="{{ route('shop') }}" class="btn btn-primary rounded-pill py-3 px-5">Lihat Katalog</a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-primary rounded-pill py-3 px-5">Hubungi Admin</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="nx-hero-panel">
                    <div class="nx-orbit one"></div>
                    <div class="nx-orbit two"></div>
                    <div class="nx-device-grid">
                        <div class="nx-device-card featured">
                            <span>Featured Tech</span>
                            <img src="{{ asset('img/product-10.png') }}" alt="Featured smartphone">
                        </div>
                        <div class="nx-device-card small-card top-card">
                            <img src="{{ asset('img/product-11.png') }}" alt="Laptop">
                            <strong>Audio</strong>
                        </div>
                        <div class="nx-device-card small-card bottom-card">
                            <img src="{{ asset('img/product-8.png') }}" alt="Audio">
                            <strong>Laptop</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container py-5">
    <div class="section-heading d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-end mb-4">
        <div>
            <span class="section-kicker">Browse by category</span>
            <h2 class="mb-1">Kategori Produk</h2>
            <p class="text-muted mb-0">Pilih kategori untuk melihat produk yang tersedia di katalog Nextronix.</p>
        </div>
        <a href="{{ route('shop') }}" class="btn btn-outline-primary rounded-pill">Semua Produk</a>
    </div>
    <div class="row g-4">
        @forelse($categories as $category)
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('shop', ['category' => $category->slug]) }}" class="category-card text-decoration-none">
                    <span class="category-icon"><i class="fas fa-microchip"></i></span>
                    <h5>{{ $category->name }}</h5>
                    <p>{{ $category->description }}</p>
                    <small>{{ $category->products_count }} produk tersedia <i class="fas fa-arrow-right ms-1"></i></small>
                </a>
            </div>
        @empty
            <div class="col-12"><div class="empty-state">Kategori belum tersedia. Jalankan <code>php artisan migrate --seed</code>.</div></div>
        @endforelse
    </div>
</section>

<section class="product-section-soft py-5">
    <div class="container py-4">
        <div class="section-heading text-center mb-5">
            <span class="section-kicker">Selected products</span>
            <h2>Produk Unggulan</h2>
            <p class="text-muted mb-0">Produk pilihan yang harga, stok, gambar, dan status tampilnya bisa diatur dari admin panel.</p>
        </div>
        <div class="row g-4">
            @forelse($featuredProducts as $product)
                <div class="col-md-6 col-lg-3">@include('partials.product-card', ['product' => $product])</div>
            @empty
                <div class="col-12"><div class="empty-state">Produk belum ada. Jalankan <code>php artisan migrate --seed</code>.</div></div>
            @endforelse
        </div>
    </div>
</section>

<section class="container py-5">
    <div class="section-heading d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-end mb-4">
        <div>
            <span class="section-kicker">Fresh catalog</span>
            <h2 class="mb-1">Produk Terbaru</h2>
            <p class="text-muted mb-0">Katalog terbaru dari database Nextronix.</p>
        </div>
        <a href="{{ route('shop') }}" class="btn btn-primary rounded-pill">Lihat Katalog</a>
    </div>
    <div class="row g-4">
        @forelse($latestProducts as $product)
            <div class="col-md-6 col-lg-3">@include('partials.product-card', ['product' => $product])</div>
        @empty
            <div class="col-12"><div class="empty-state">Produk belum tersedia.</div></div>
        @endforelse
    </div>
</section>
@endsection
