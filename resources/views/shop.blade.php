@extends('layouts.app', ['title' => 'Katalog Produk - Nextronix'])

@section('content')
<section class="page-hero compact">
    <div class="container">
        <span class="section-kicker">Product catalog</span>
        <h1 class="mb-2">Katalog Produk Nextronix</h1>
        <p class="text-muted mb-0">Customer dapat mencari dan melihat detail produk. Admin dapat mengubah produk, stok, kategori, dan gambar dari dashboard.</p>
    </div>
</section>

<section class="container py-5">
    <div class="row g-4">
        <div class="col-lg-3">
            <form action="{{ route('shop') }}" method="GET" class="filter-card sticky-lg-top">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Filter</h5>
                    <a href="{{ route('shop') }}" class="small text-danger">Reset</a>
                </div>
                <div class="mb-3">
                    <label class="form-label">Keyword</label>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Nama/SKU produk">
                </div>
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="category" class="form-select">
                        <option value="">Semua kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }} ({{ $category->active_products_count }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label">Min</label>
                        <input type="number" name="min_price" value="{{ request('min_price') }}" class="form-control" placeholder="0">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Max</label>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" class="form-control" placeholder="10000000">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Urutkan</label>
                    <select name="sort" class="form-select">
                        <option value="latest" @selected(request('sort') === 'latest')>Terbaru</option>
                        <option value="name" @selected(request('sort') === 'name')>Nama A-Z</option>
                        <option value="price_asc" @selected(request('sort') === 'price_asc')>Harga Termurah</option>
                        <option value="price_desc" @selected(request('sort') === 'price_desc')>Harga Termahal</option>
                    </select>
                </div>
                <button class="btn btn-primary w-100 rounded-pill">Terapkan Filter</button>
            </form>
        </div>
        <div class="col-lg-9">
            <div class="shop-toolbar mb-4">
                <div>
                    <h5 class="mb-1">{{ $products->total() }} Produk Ditemukan</h5>
                    <p class="text-muted mb-0">Menampilkan {{ $products->count() }} produk pada halaman ini.</p>
                </div>
                <div class="active-filter-chips">
                    @if(request('q'))<span>Keyword: {{ request('q') }}</span>@endif
                    @if(request('category'))<span>Kategori: {{ request('category') }}</span>@endif
                    @if(request('min_price'))<span>Min: @rupiah(request('min_price'))</span>@endif
                    @if(request('max_price'))<span>Max: @rupiah(request('max_price'))</span>@endif
                </div>
            </div>

            <div class="row g-4">
                @forelse($products as $product)
                    <div class="col-md-6 col-xl-4">@include('partials.product-card', ['product' => $product])</div>
                @empty
                    <div class="col-12">
                        <div class="empty-state large">
                            <i class="fas fa-search mb-3"></i>
                            <h4>Produk tidak ditemukan</h4>
                            <p>Coba ubah keyword, kategori, atau rentang harga.</p>
                            <a href="{{ route('shop') }}" class="btn btn-primary rounded-pill">Reset Filter</a>
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="mt-5 catalog-pagination">
    {{ $products->links('pagination::bootstrap-5') }}
</div>
        </div>
    </div>
</section>
@endsection
