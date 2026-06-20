@extends('layouts.app', ['title' => $product->name . ' - Nextronix'])

@section('content')
<section class="page-hero compact">
    <div class="container">
        <span class="section-kicker">Product detail</span>
        <h1 class="mb-2">{{ $product->name }}</h1>
        <p class="text-muted mb-0">Detail produk katalog Nextronix. Untuk pemesanan atau ketersediaan stok, customer diarahkan menghubungi admin.</p>
    </div>
</section>

<div class="container py-5">
    <div class="row g-5 align-items-start">
        <div class="col-lg-5">
            <div class="product-detail-image text-center p-4">
                <img src="{{ $product->image_url }}" class="img-fluid" style="max-height:420px; object-fit:contain;" alt="{{ $product->name }}">
            </div>
        </div>
        <div class="col-lg-7">
            <span class="badge bg-primary mb-3">{{ $product->category?->name }}</span>
            <h2 class="fw-bold mb-2">{{ $product->name }}</h2>
            <p class="text-muted">SKU: {{ $product->sku ?? '-' }}</p>
            <div class="mb-3">
                <strong class="h3 text-primary">@rupiah($product->active_price)</strong>
                @if($product->sale_price)
                    <span class="text-muted text-decoration-line-through ms-2">@rupiah($product->price)</span>
                @endif
            </div>
            <p class="lead-detail">{{ $product->description }}</p>
            <div class="nx-spec-grid mb-4">
                <div><span>Stok</span><strong>{{ $product->stock }}</strong></div>
                <div><span>Berat</span><strong>{{ $product->weight_gram }} gr</strong></div>
                <div><span>Kategori</span><strong>{{ $product->category?->name }}</strong></div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('contact') }}" class="btn btn-primary rounded-pill px-4 py-3">
                    Hubungi Admin
                </a>
                <a href="{{ route('shop') }}" class="btn btn-outline-primary rounded-pill px-4 py-3">
                    Kembali ke Katalog
                </a>
            </div>
        </div>
    </div>

    @if($relatedProducts->isNotEmpty())
        <hr class="my-5">
        <div class="section-heading mb-4">
            <span class="section-kicker">Related products</span>
            <h3 class="mb-1">Produk Terkait</h3>
        </div>
        <div class="row g-4">
            @foreach($relatedProducts as $related)
                <div class="col-md-6 col-lg-3">@include('partials.product-card', ['product' => $related])</div>
            @endforeach
        </div>
    @endif
</div>
@endsection
