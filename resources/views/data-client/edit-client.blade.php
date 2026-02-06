@extends('layouts.app')

@section('title', 'Edit Client')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('data-client.index') }}" class="btn btn-outline-secondary me-3">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <div>
            <h3 class="mb-0">Edit Client {{ $client->nama_brand }}</h3>
            <p class="text-muted mb-0">Edit sebagian data client bila diperlukan</p>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('data-client.update', $client->id) }}" 
                  method="POST" 
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Nama & Brand -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name', $client->name) }}">
                    </div>

                    <div class="col-md-6">
                        <label>Nama Brand</label>
                        <input type="text" name="nama_brand" class="form-control"
                               value="{{ old('nama_brand', $client->nama_brand) }}">
                    </div>
                </div>

                <!-- Phone & Email -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control"
                               value="{{ old('phone', $client->phone) }}">
                    </div>

                    <div class="col-md-6">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', $client->email) }}">
                    </div>
                </div>

                 <!-- Category (DROPDOWN DARI MODEL) -->
                <div class="row mb-3">
                    <div class="col-md-6">
                    <label>Category</label>
                    <select name="category" class="form-control">
                        @foreach(\App\Models\Client::getCategories() as $category)
                            <option value="{{ $category }}"
                                {{ old('category', $client->category) == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- Status -->
                 <div class="col-md-6">
    <label>Status</label>
    <select name="status" class="form-control">

        <option value="{{ \App\Models\Client::STATUS_ACTIVE }}"
            {{ $client->status === \App\Models\Client::STATUS_ACTIVE ? 'selected' : '' }}>
            Aktif
        </option>

        <option value="{{ \App\Models\Client::STATUS_INACTIVE }}"
            {{ $client->status === \App\Models\Client::STATUS_INACTIVE ? 'selected' : '' }}>
            Non Aktif
        </option>
    </select>
</div>
                </div>

                <!-- TANGGAL (TIDAK WAJIB DIUBAH) -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control"
                               value="{{ old('start_date', $client->start_date?->format('Y-m-d')) }}">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah</small>
                    </div>

                    <div class="col-md-6">
                        <label>Expired Date</label>
                        <input type="date" name="expired_date" class="form-control"
                               value="{{ old('expired_date', $client->expired_date?->format('Y-m-d')) }}">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah</small>
                    </div>
                </div>

                
<!-- Address -->
                <div class="mb-1.5">
                    <label>Address</label>
                    <textarea name="address" class="form-control" rows="3">{{ old('address', $client->address) }}</textarea>
                </div>



                <!-- Notes -->
                <div class="mb-1.5">
                    <label>Notes</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $client->notes) }}</textarea>
                </div>

                <!-- INVOICE -->
<div class="mb-4">
    <label>Invoice</label>

    @if($client->invoices->count())
        <div class="mb-3">
            @foreach($client->invoices as $invoice)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <i class="fas fa-file-pdf text-danger"></i>
                        {{ $invoice->file_original_name }}
                        <small class="text-muted">
                            ({{ number_format($invoice->file_size / 1024, 2) }} KB)
                        </small>
                    </div>
                    <div>
                        <a href="{{ route('invoices.show', $invoice->id) }}"
                           target="_blank"
                           class="btn btn-sm btn-outline-primary">
                            Lihat
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-muted">Belum ada invoice</p>
    @endif

    <input type="file" name="invoice" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
    <small class="text-muted">Upload hanya jika ingin mengganti invoice (PDF, JPG, PNG, max 2MB)</small>
</div>

                <!-- BUTTON -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('data-client.index') }}" class="btn btn-secondary">
                        Batal
                    </a>
                    <button class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
