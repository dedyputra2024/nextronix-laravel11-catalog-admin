@extends('layouts.admin', ['title' => 'Detail Order'])

@section('admin_content')
<div class="bg-white rounded shadow-sm p-4 mb-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <h3>Order {{ $order->order_number }}</h3>
            <p class="text-muted mb-0">{{ $order->created_at->format('d M Y H:i') }}</p>
        </div>
        <a href="{{ route('invoice.download', $order) }}" class="btn btn-outline-dark rounded-pill">Download Invoice</a>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="bg-white rounded shadow-sm p-4">
            <h5>Items</h5>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Produk</th><th>Qty</th><th>Harga</th><th>Subtotal</th></tr></thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr><td>{{ $item->product_name }}</td><td>{{ $item->quantity }}</td><td>@rupiah($item->price)</td><td>@rupiah($item->subtotal)</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="bg-white rounded shadow-sm p-4 mt-4">
            <h5>Payment Gateway Logs</h5>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>Waktu</th><th>Gateway</th><th>Status</th><th>Type</th><th>ID</th></tr></thead>
                    <tbody>
                        @forelse($order->paymentTransactions as $trx)
                            <tr><td>{{ $trx->created_at->format('d M Y H:i') }}</td><td>{{ $trx->gateway }}</td><td>{{ $trx->transaction_status }}</td><td>{{ $trx->payment_type }}</td><td>{{ $trx->transaction_id }}</td></tr>
                        @empty
                            <tr><td colspan="5" class="text-muted text-center">Belum ada callback/payment log.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="bg-white rounded shadow-sm p-4">
            <h5>Customer</h5>
            <p class="mb-1"><strong>{{ $order->name }}</strong></p>
            <p class="mb-1">{{ $order->email }}</p>
            <p class="mb-3">{{ $order->phone }}</p>
            <p>{{ $order->address }}, {{ $order->city }} {{ $order->postal_code }}</p>
            @if($order->destination_label)<p><strong>Destination:</strong> {{ $order->destination_label }}</p>@endif
            @if($order->courier_code)<p><strong>Kurir:</strong> {{ strtoupper($order->courier_code) }} {{ $order->courier_service }} {{ $order->courier_etd }}</p>@endif
            <hr>
            <div class="d-flex justify-content-between"><span>Subtotal</span><strong>@rupiah($order->subtotal)</strong></div>
            <div class="d-flex justify-content-between"><span>Ongkir</span><strong>@rupiah($order->shipping_cost)</strong></div>
            <div class="d-flex justify-content-between h5 mt-2"><span>Total</span><strong>@rupiah($order->total)</strong></div>
            <hr>
            <p class="mb-1"><strong>Payment:</strong> {{ strtoupper($order->payment_method) }}</p>
            <p class="mb-3"><strong>Status bayar:</strong> {{ ucfirst(str_replace('_',' ',$order->payment_status)) }}</p>
            <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                @csrf @method('PATCH')
                <label class="form-label">Status Order</label>
                <select name="status" class="form-select mb-3">
                    @foreach(['pending','processing','shipped','completed','cancelled'] as $status)
                        <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary w-100 rounded-pill">Update Status</button>
            </form>
        </div>
    </div>
</div>
@endsection
