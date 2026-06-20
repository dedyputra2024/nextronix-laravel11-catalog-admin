@extends('layouts.app', ['title' => 'Checkout Disabled - Nextronix'])

@section('content')
<div class="container py-5">
    <div class="empty-state large">
        <i class="fas fa-paper-plane mb-3"></i>
        <h4>Checkout Customer Dinonaktifkan</h4>
        <p>Untuk demo ini, pemesanan diarahkan melalui kontak/admin agar website publik lebih sederhana.</p>
        <a href="{{ route('contact') }}" class="btn btn-primary rounded-pill">Hubungi Admin</a>
    </div>
</div>
@endsection
