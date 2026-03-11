@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('page-title', 'Edit Kategori')
@section('page-icon', 'mdi-tag-text-outline')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Kategori</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Kategori</h4>
                <p class="card-description">Ubah data kategori</p>
                
                <form id="formEditKategori" action="{{ route('kategori.update', $kategori->idkategori) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="nama_kategori">Nama Kategori</label>
                        <input type="text" class="form-control @error('nama_kategori') is-invalid @enderror" 
                               id="nama_kategori" name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                               placeholder="Masukkan nama kategori" required maxlength="100">
                        @error('nama_kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </form>
                
                <div class="mt-4">
                    <button type="button" id="btnUpdateKategori" class="btn btn-primary me-2 btn-spinner">
                        <span class="spinner-border d-none" role="status" aria-hidden="true"></span>
                        <i class="mdi mdi-content-save"></i> Update
                    </button>
                    <a href="{{ route('kategori.index') }}" class="btn btn-light">
                        <i class="mdi mdi-arrow-left"></i> Kembali
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
    $('#btnUpdateKategori').on('click', function(e) {
        e.preventDefault();
        
        const form = $('#formEditKategori')[0];
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
