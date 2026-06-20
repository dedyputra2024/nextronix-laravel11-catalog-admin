@extends('layouts.app', ['title' => 'Admin Login - Nextronix'])

@section('content')
<section class="auth-page py-5">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="auth-card">
                    <div class="text-center mb-4">
                        <div class="auth-icon"><i class="fas fa-user-shield"></i></div>
                        <h3 class="mb-2">Admin Login</h3>
                        <p class="text-muted mb-0">Login khusus admin/staff untuk mengelola katalog Nextronix.</p>
                    </div>
                    <form method="POST" action="{{ route('login.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label for="remember" class="form-check-label">Remember me</label>
                        </div>
                        <button class="btn btn-primary w-100 rounded-pill py-3">Masuk Admin Panel</button>
                    </form>
                    <hr>
                    <div class="demo-login-box">
                        <p class="mb-1"><strong>Demo Admin:</strong> admin@nextronix.test / password</p>
                        <p class="mb-0"><strong>Demo Staff:</strong> staff@nextronix.test / password</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
