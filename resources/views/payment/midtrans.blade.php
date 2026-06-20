@extends('layouts.app', ['title' => 'Pembayaran Midtrans'])

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="bg-light border rounded p-5 text-center">
                <h1 class="mb-3">Pembayaran Order {{ $order->order_number }}</h1>
                <p class="text-muted">Total pembayaran: <strong>@rupiah($order->total)</strong></p>
                <p class="mb-4">Klik tombol di bawah untuk membuka halaman pembayaran Midtrans Snap.</p>
                <a href="{{ $order->payment_redirect_url }}" class="btn btn-primary rounded-pill px-5 py-3">Bayar Sekarang</a>
                <a href="{{ route('account.orders.show', $order) }}" class="btn btn-outline-secondary rounded-pill px-4 py-3 ms-2">Lihat Order</a>
                <p class="small text-muted mt-4 mb-0">Client key tidak ditampilkan di halaman ini karena mode redirect lebih sederhana dan aman untuk project tugas.</p>
            </div>
        </div>
    </div>
</div>
@endsection
