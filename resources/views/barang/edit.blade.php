@extends('layouts.app')

@section('title', 'Edit Barang')

@section('page-title', 'Edit Barang')
@section('page-icon', 'mdi-package-variant-closed')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Barang</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Barang</h4>
                <p class="card-description">Ubah data barang</p>
                
                <form id="formEditBarang" action="{{ route('barang.update', $barang->id_barang) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="id_barang">ID Barang</label>
                        <input type="text" class="form-control" 
                               id="id_barang" value="{{ $barang->id_barang }}" disabled>
                        <small class="form-text text-muted">ID barang tidak dapat diubah</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="nama">Nama Barang</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                               id="nama" name="nama" value="{{ old('nama', $barang->nama) }}"
                               placeholder="Masukkan nama barang" required maxlength="50">
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="harga">Harga</label>
                        <input type="number" class="form-control @error('harga') is-invalid @enderror" 
                               id="harga" name="harga" value="{{ old('harga', $barang->harga) }}"
                               placeholder="Masukkan harga barang" required min="0">
                        <small class="form-text text-muted">Masukkan harga dalam Rupiah</small>
                        @error('harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </form>
                
                <div class="mt-4">
                    <button type="button" id="btnUpdateBarang" class="btn btn-primary me-2 btn-spinner">
                        <span class="spinner-border d-none" role="status" aria-hidden="true"></span>
                        <i class="mdi mdi-content-save"></i> Update
                    </button>
                    <a href="{{ route('barang.index') }}" class="btn btn-light">
                        <i class="mdi mdi-cancel"></i> Batal
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#btnUpdateBarang').on('click', function(e) {
        e.preventDefault();
        
        const form = $('#formEditBarang')[0];
        const btn = $(this);
        const spinner = btn.find('.spinner-border');
        const icon = btn.find('i');
        
        // Validasi form HTML5
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        // Tampilkan spinner
        btn.addClass('loading');
        spinner.removeClass('d-none');
        icon.addClass('d-none');
        btn.prop('disabled', true);
        
        // Submit form
        form.submit();
    });
});
</script>
@endpush
