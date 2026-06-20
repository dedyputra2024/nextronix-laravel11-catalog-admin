@extends('layouts.admin', ['title' => 'Orders'])

@section('admin_content')
<div class="admin-panel-card">
    <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-center mb-4">
        <div>
            <h4 class="mb-1">Order Management</h4>
            <p class="text-muted mb-0">Pantau pembayaran, status pengiriman, dan histori transaksi.</p>
        </div>
    </div>

    <form class="row g-3 admin-filter mb-4" method="GET" action="{{ route('admin.orders.index') }}">
        <div class="col-lg-4">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari order/customer/email/phone">
        </div>
        <div class="col-lg-3">
            <select name="status" class="form-select">
                <option value="">Semua status order</option>
                @foreach(['pending','processing','shipped','completed','cancelled'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3">
            <select name="payment_status" class="form-select">
                <option value="">Semua payment</option>
                @foreach(['unpaid','pending','paid','failed','refunded','cod_pending','challenge'] as $status)
                    <option value="{{ $status }}" @selected(request('payment_status') === $status)>{{ ucfirst(str_replace('_',' ',$status)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-2 d-grid">
            <button class="btn btn-primary"><i class="fas fa-filter me-2"></i>Filter</button>
        </div>
        @if(request()->hasAny(['q','status','payment_status']))
            <div class="col-12"><a href="{{ route('admin.orders.index') }}" class="small text-danger">Reset filter</a></div>
        @endif
    </form>

    <div class="table-responsive">
        <table class="table admin-table align-middle">
            <thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Payment</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong><br><small>{{ $order->created_at->format('d M Y H:i') }}</small></td>
                        <td>{{ $order->name }}<br><small>{{ $order->email }}</small></td>
                        <td><strong>@rupiah($order->total)</strong><br><small>{{ strtoupper($order->payment_method) }}</small></td>
                        <td><span class="status-pill {{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'failed' ? 'danger' : 'warning') }}">{{ ucfirst(str_replace('_',' ',$order->payment_status)) }}</span></td>
                        <td><span class="status-pill muted">{{ ucfirst($order->status) }}</span></td>
                        <td class="text-end"><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary rounded-pill">Detail</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-5">Belum ada order sesuai filter.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $orders->links() }}</div>
</div>
@endsection
