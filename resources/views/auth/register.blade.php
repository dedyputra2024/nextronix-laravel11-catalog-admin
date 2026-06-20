@extends('layouts.app', ['title' => 'Register Disabled - Nextronix'])

@section('content')
<div class="container py-5">
    <div class="empty-state large">
        <i class="fas fa-lock mb-3"></i>
        <h4>Registrasi Customer Dinonaktifkan</h4>
        <p>Versi Nextronix memakai mode katalog. Login hanya tersedia untuk admin/staff.</p>
        <a href="{{ route('login') }}" class="btn btn-primary rounded-pill">Admin Login</a>
    </div>
</div>
@endsection
