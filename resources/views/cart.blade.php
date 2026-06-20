@extends('layouts.app', ['title' => 'Cart Disabled - Nextronix'])

@section('content')
<div class="container py-5">
    <div class="empty-state large">
        <i class="fas fa-shopping-cart mb-3"></i>
        <h4>Cart Dinonaktifkan</h4>
        <p>Nextronix memakai mode katalog. Customer dapat melihat produk dan menghubungi admin untuk pemesanan.</p>
        <a href="{{ route('shop') }}" class="btn btn-primary rounded-pill">Kembali ke Katalog</a>
    </div>
</div>
@endsection
