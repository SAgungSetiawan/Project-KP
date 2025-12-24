{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('auth-content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-logo">
            <h3><i class="bi bi-calendar-event"></i> ORREA Organizer</h3>
            <p class="text-muted">Dashboard Login</p>
        </div>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div><i class="bi bi-exclamation-triangle"></i> {{ $error }}</div>
                @endforeach
            </div>
        @endif
        
        @if (session('status'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i> {{ session('status') }}
            </div>
        @endif
        
        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="{{ old('email') }}" required autofocus placeholder="email@contoh.com">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password" 
                           required placeholder="Masukkan password">
                </div>
            </div>
            
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-orrea">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Login
                </button>
            </div>
            
            <div class="text-center">
                <p class="text-muted mb-0">Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-orrea-primary">Daftar di sini</a>
                </p>
                <p class="text-muted mt-2">
                    <a href="{{ url('/') }}" class="text-decoration-none">
                        <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                    </a>
                </p>
            </div>
        </form>
        
        <hr class="my-4">
        
        <div class="text-center">
            <p class="text-muted small">Akun Demo:</p>
            <div class="alert alert-info small">
                <strong>Email:</strong> demo@orrea.com<br>
                <strong>Password:</strong> password
            </div>
        </div>
    </div>
</div>
@endsection