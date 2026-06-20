@extends('layouts.app', ['title' => 'Detail Order'])

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h1 class="mb-1">Order {{ $order->order_number }}</h1>
            <p class="text-muted mb-0">Status order: {{ ucfirst($order->status) }} | Status bayar: {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</p>
        </div>
        <div>
            <a href="{{ route('invoice.download', $order) }}" class="btn btn-outline-dark rounded-pill">Download Invoice PDF</a>
            @if($order->canBePaid())
                <a href="{{ route('payment.midtrans.pay', $order) }}" class="btn btn-primary rounded-pill">Bayar Midtrans</a>
            @endif
            <a href="{{ route('account.orders') }}" class="btn btn-outline-primary rounded-pill">Kembali</a>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="table-responsive bg-white rounded shadow-sm mb-4">
                <table class="table align-middle mb-0">
                    <thead class="table-light"><tr><th>Produk</th><th>Qty</th><th>Harga</th><th>Subtotal</th></tr></thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr><td>{{ $item->product_name }}</td><td>{{ $item->quantity }}</td><td>@rupiah($item->price)</td><td>@rupiah($item->subtotal)</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($order->paymentTransactions->isNotEmpty())
                <div class="bg-white rounded shadow-sm p-4">
                    <h5>Log Payment Gateway</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead><tr><th>Waktu</th><th>Status</th><th>Type</th><th>Transaction ID</th></tr></thead>
                            <tbody>
                                @foreach($order->paymentTransactions as $trx)
                                    <tr><td>{{ $trx->created_at->format('d M Y H:i') }}</td><td>{{ $trx->transaction_status }}</td><td>{{ $trx->payment_type }}</td><td>{{ $trx->transaction_id }}</td></tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-lg-4">
            <div class="bg-light rounded p-4 border">
                <h5>Ringkasan</h5>
                <p class="mb-1"><strong>Nama:</strong> {{ $order->name }}</p>
                <p class="mb-1"><strong>Email:</strong> {{ $order->email }}</p>
                <p class="mb-1"><strong>Telepon:</strong> {{ $order->phone }}</p>
                <p class="mb-1"><strong>Alamat:</strong> {{ $order->address }}, {{ $order->city }} {{ $order->postal_code }}</p>
                @if($order->destination_label)<p class="mb-1"><strong>Destination:</strong> {{ $order->destination_label }}</p>@endif
                @if($order->courier_code)<p class="mb-3"><strong>Kurir:</strong> {{ strtoupper($order->courier_code) }} {{ $order->courier_service }} @if($order->courier_etd) ({{ $order->courier_etd }}) @endif</p>@endif
                <p class="mb-1"><strong>Pembayaran:</strong> {{ strtoupper($order->payment_method) }}</p>
                <p class="mb-3"><strong>Reference:</strong> {{ $order->payment_reference ?: '-' }}</p>
                <div class="d-flex justify-content-between"><span>Subtotal</span><strong>@rupiah($order->subtotal)</strong></div>
                <div class="d-flex justify-content-between"><span>Ongkir</span><strong>@rupiah($order->shipping_cost)</strong></div>
                <hr>
                <div class="d-flex justify-content-between h5"><span>Total</span><strong>@rupiah($order->total)</strong></div>
            </div>
        </div>
    </div>
</div>
@endsection
