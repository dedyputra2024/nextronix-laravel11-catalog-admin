@extends('layouts.admin', ['title' => 'Dashboard'])

@section('admin_content')
<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="admin-stat-card">
            <span class="stat-icon"><i class="fas fa-box-open"></i></span>
            <p>Total Produk</p>
            <h3>{{ $productsCount }}</h3>
            <small>Produk aktif dan nonaktif</small>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="admin-stat-card">
            <span class="stat-icon"><i class="fas fa-receipt"></i></span>
            <p>Total Order</p>
            <h3>{{ $ordersCount }}</h3>
            <small>{{ $pendingOrdersCount }} order masih pending</small>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="admin-stat-card">
            <span class="stat-icon"><i class="fas fa-wallet"></i></span>
            <p>Paid Revenue</p>
            <h3>@rupiah($paidRevenue)</h3>
            <small>Berdasarkan payment paid</small>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="admin-stat-card warning">
            <span class="stat-icon"><i class="fas fa-exclamation-triangle"></i></span>
            <p>Low Stock</p>
            <h3>{{ $lowStockCount }}</h3>
            <small>Produk stok ≤ 5</small>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="admin-panel-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="mb-1">Order Terbaru</h5>
                    <p class="text-muted mb-0">Pantau transaksi yang masuk ke toko.</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary btn-sm rounded-pill">Lihat semua</a>
            </div>
            <div class="table-responsive">
                <table class="table admin-table align-middle">
                    <thead><tr><th>No Order</th><th>Customer</th><th>Total</th><th>Payment</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($latestOrders as $order)
                            <tr>
                                <td><a href="{{ route('admin.orders.show', $order) }}" class="fw-bold">{{ $order->order_number }}</a><br><small>{{ $order->created_at->format('d M Y H:i') }}</small></td>
                                <td>{{ $order->name }}<br><small>{{ $order->email }}</small></td>
                                <td>@rupiah($order->total)</td>
                                <td><span class="status-pill {{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst(str_replace('_',' ',$order->payment_status)) }}</span></td>
                                <td><span class="status-pill muted">{{ ucfirst($order->status) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada order.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="admin-panel-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="mb-1">Stok Rendah</h5>
                    <p class="text-muted mb-0">Butuh restock segera.</p>
                </div>
                <a href="{{ route('admin.products.index', ['low_stock' => 1]) }}" class="small">Kelola</a>
            </div>
            @forelse($lowStockProducts as $product)
                <div class="low-stock-row">
                    <div>
                        <strong>{{ $product->name }}</strong><br>
                        <small>{{ $product->category?->name }}</small>
                    </div>
                    <span>{{ $product->stock }}</span>
                </div>
            @empty
                <div class="empty-state-mini">Tidak ada produk stok rendah.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
