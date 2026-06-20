@extends('layouts.app', ['title' => 'Contact - Nextronix'])

@section('content')
<section class="page-hero compact">
    <div class="container">
        <span class="section-kicker">Contact</span>
        <h1 class="mb-2">Hubungi Nextronix</h1>
        <p class="text-muted mb-0">Form kontak demo untuk customer yang ingin menanyakan produk, stok, atau pemesanan.</p>
    </div>
</section>
<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="contact-info-card h-100">
                <h4>Nextronix Store</h4>
                <p class="text-muted">Project katalog teknologi berbasis Laravel 11. Pengaturan produk dilakukan melalui admin panel.</p>
                <p><i class="fa fa-map-marker-alt text-primary me-2"></i>Indonesia</p>
                <p><i class="fa fa-envelope text-primary me-2"></i>support@nextronix.test</p>
                <p><i class="fa fa-lock text-primary me-2"></i>Admin: admin@nextronix.test</p>
            </div>
        </div>
        <div class="col-lg-7">
            <form action="{{ route('contact.submit') }}" method="POST" class="contact-form-card">
                @csrf
                <div class="mb-3"><label class="form-label">Nama</label><input name="name" value="{{ old('name') }}" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Email</label><input name="email" type="email" value="{{ old('email') }}" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Pesan</label><textarea name="message" class="form-control" rows="5" required>{{ old('message') }}</textarea></div>
                <button class="btn btn-primary rounded-pill px-4">Kirim Pesan</button>
            </form>
        </div>
    </div>
</div>
@endsection
