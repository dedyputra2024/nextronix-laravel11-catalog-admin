<div class="card product-card h-100 border-0 shadow-sm">
    <div class="position-relative product-image-wrap">
        <a href="{{ route('products.show', $product) }}">
            <img src="{{ $product->image_url }}" class="card-img-top product-card-img p-3" alt="{{ $product->name }}">
        </a>
        <div class="product-badges">
            @if($product->discount_percent)
                <span class="badge bg-danger">-{{ $product->discount_percent }}%</span>
            @endif
            @if($product->stock <= 5 && $product->stock > 0)
                <span class="badge bg-warning text-dark">Low Stock</span>
            @elseif($product->stock < 1)
                <span class="badge bg-secondary">Sold Out</span>
            @endif
        </div>
    </div>
    <div class="card-body d-flex flex-column">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="category-chip">{{ $product->category?->name }}</span>
            <span class="rating-mini"><i class="fas fa-star"></i> 4.8</span>
        </div>
        <h5 class="card-title line-clamp-2 mb-2"><a href="{{ route('products.show', $product) }}" class="text-dark text-decoration-none">{{ $product->name }}</a></h5>
        <p class="card-text small text-muted line-clamp-2 flex-grow-1">{{ $product->description }}</p>
        <div class="d-flex align-items-end justify-content-between gap-2 mb-3">
            <div>
                <strong class="product-price d-block">@rupiah($product->active_price)</strong>
                @if($product->sale_price)
                    <small class="text-muted text-decoration-line-through">@rupiah($product->price)</small>
                @endif
            </div>
            <small class="text-muted">Stok {{ $product->stock }}</small>
        </div>
        <a href="{{ route('products.show', $product) }}" class="btn btn-primary w-100 rounded-pill">
            Lihat Detail
        </a>
    </div>
</div>
