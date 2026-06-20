@extends('layouts.admin', ['title' => 'Produk'])

@section('admin_content')
<div class="admin-panel-card mb-4">
    <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center mb-4">
        <div>
            <h4 class="mb-1">Manajemen Produk</h4>
            <p class="text-muted mb-0">Kelola katalog, harga, stok, dan status produk.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary rounded-pill"><i class="fas fa-plus me-2"></i>Tambah Produk</a>
    </div>

    <form method="GET" action="{{ route('admin.products.index') }}" class="row g-3 admin-filter mb-4">
        <div class="col-lg-4">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama, SKU, atau deskripsi">
        </div>
        <div class="col-lg-3">
            <select name="category_id" class="form-select">
                <option value="">Semua kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-2">
            <select name="status" class="form-select">
                <option value="">Semua status</option>
                <option value="active" @selected(request('status') === 'active')>Aktif</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
            </select>
        </div>
        <div class="col-lg-2 d-flex align-items-center">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" name="low_stock" id="low_stock" @checked(request()->boolean('low_stock'))>
                <label class="form-check-label" for="low_stock">Stok rendah</label>
            </div>
        </div>
        <div class="col-lg-1 d-grid">
            <button class="btn btn-primary"><i class="fas fa-search"></i></button>
        </div>
        @if(request()->hasAny(['q','category_id','status','low_stock']))
            <div class="col-12"><a href="{{ route('admin.products.index') }}" class="small text-danger">Reset filter</a></div>
        @endif
    </form>

    <div class="table-responsive">
        <table class="table admin-table align-middle">
            <thead><tr><th>Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $product->image_url }}" class="admin-product-thumb" alt="{{ $product->name }}">
                                <div>
                                    <strong>{{ $product->name }}</strong><br>
                                    <small class="text-muted">{{ $product->sku ?: 'No SKU' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $product->category?->name }}</td>
                        <td>
                            <strong>@rupiah($product->active_price)</strong>
                            @if($product->sale_price)
                                <br><small class="text-muted text-decoration-line-through">@rupiah($product->price)</small>
                            @endif
                        </td>
                        <td><span class="status-pill {{ $product->stock <= 5 ? 'danger' : 'muted' }}">{{ $product->stock }}</span></td>
                        <td><span class="status-pill {{ $product->is_active ? 'success' : 'muted' }}">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td>
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-light" target="_blank">Preview</a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-5">Tidak ada produk sesuai filter.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $products->links() }}</div>
</div>
@endsection
