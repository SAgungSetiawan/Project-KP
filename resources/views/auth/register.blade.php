{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')

@section('auth-content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-logo">
            <h3><i class="bi bi-calendar-event"></i> ORREA Organizer</h3>
            <p class="text-muted">Buat Akun Baru</p>
        </div>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div><i class="bi bi-exclamation-triangle"></i> {{ $error }}</div>
                @endforeach
            </div>
        @endif
        
        <form action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="{{ old('name') }}" required autofocus placeholder="Nama Anda">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="{{ old('email') }}" required placeholder="email@contoh.com">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password" 
                           required placeholder="Minimal 8 karakter">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" class="form-control" id="password_confirmation" 
                           name="password_confirmation" required placeholder="Ulangi password">
                </div>
            </div>
            
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-orrea">
                    <i class="bi bi-person-plus me-2"></i> Daftar Sekarang
                </button>
            </div>
            
            <div class="text-center">
                <p class="text-muted mb-0">Sudah punya akun? 
                    <a href="{{ route('login') }}" class="text-orrea-primary">Login di sini</a>
                </p>
                <p class="text-muted mt-2">
                    <a href="{{ url('/') }}" class="text-decoration-none">
                        <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection