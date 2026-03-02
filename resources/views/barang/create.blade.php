@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('page-title', 'Tambah Barang')
@section('page-icon', 'mdi-package-variant-closed')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Barang</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Barang</h4>
                <p class="card-description">Masukkan data barang baru</p>
                
                <form action="{{ route('barang.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="nama">Nama Barang</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                               id="nama" name="nama" value="{{ old('nama') }}"
                               placeholder="Masukkan nama barang" required maxlength="50">
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="harga">Harga</label>
                        <input type="number" class="form-control @error('harga') is-invalid @enderror" 
                               id="harga" name="harga" value="{{ old('harga') }}"
                               placeholder="Masukkan harga barang" required min="0">
                        <small class="form-text text-muted">Masukkan harga dalam Rupiah</small>
                        @error('harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="mdi mdi-content-save"></i> Simpan
                        </button>
                        <a href="{{ route('barang.index') }}" class="btn btn-light">
                            <i class="mdi mdi-cancel"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
