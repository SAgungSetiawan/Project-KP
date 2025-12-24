{{-- resources/views/dashboard/edit-client.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Client')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">Edit Client: {{ $client->name }}</h3>
                <p class="text-muted mb-0">Perbarui data client</p>
            </div>
            <a href="{{ route('dashboard.clients') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i> Kembali
            </a>
        </div>

        <div class="card-orrea">
            <div class="card-body">
                <form action="{{ route('dashboard.update-client', $client->id) }}" method="POST">
                    @csrf
                    @method('POST')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama Lengkap *</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="{{ $client->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Telepon</label>
                            <input type="text" class="form-control" id="phone" name="phone" 
                                   value="{{ $client->phone }}">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ $client->email }}">
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea class="form-control" id="address" name="address" rows="2">{{ $client->address }}</textarea>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="category" class="form-label">Kategori *</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="Regular" {{ $client->category == 'Regular' ? 'selected' : '' }}>Regular</option>
                                <option value="VIP" {{ $client->category == 'VIP' ? 'selected' : '' }}>VIP</option>
                                <option value="Corporate" {{ $client->category == 'Corporate' ? 'selected' : '' }}>Corporate</option>
                                <option value="UMKM" {{ $client->category == 'UMKM' ? 'selected' : '' }}>UMKM</option>
                                <option value="Premium" {{ $client->category == 'Premium' ? 'selected' : '' }}>Premium</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="join_date" class="form-label">Tanggal Daftar *</label>
                            <input type="date" class="form-control" id="join_date" name="join_date" 
                                   value="{{ $client->join_date->format('Y-m-d') }}" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="active" {{ $client->status == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="new" {{ $client->status == 'new' ? 'selected' : '' }}>Baru</option>
                                <option value="inactive" {{ $client->status == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3">{{ $client->notes }}</textarea>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('dashboard.clients') }}" class="btn btn-outline-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-orrea">
                            <i class="bi bi-check-circle me-2"></i> Update Client
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection