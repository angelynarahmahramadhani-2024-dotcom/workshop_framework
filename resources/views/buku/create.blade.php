@extends('layouts.app')

@section('title', 'Tambah Buku')

@section('page-title', 'Tambah Buku')
@section('page-icon', 'mdi-book-plus')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('buku.index') }}">Buku</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Buku</h4>
                <p class="card-description">Masukkan data buku baru</p>
                
                <form id="formBuku" action="{{ route('buku.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="idkategori">Kategori</label>
                        <select class="form-control @error('idkategori') is-invalid @enderror" 
                                id="idkategori" name="idkategori" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->idkategori }}" {{ old('idkategori') == $kat->idkategori ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('idkategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="kode">Kode Buku</label>
                        <input type="text" class="form-control @error('kode') is-invalid @enderror" 
                               id="kode" name="kode" value="{{ old('kode') }}"
                               placeholder="Contoh: NV-01" required maxlength="20">
                        @error('kode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="judul">Judul Buku</label>
                        <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                               id="judul" name="judul" value="{{ old('judul') }}"
                               placeholder="Masukkan judul buku" required maxlength="500">
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="pengarang">Pengarang</label>
                        <input type="text" class="form-control @error('pengarang') is-invalid @enderror" 
                               id="pengarang" name="pengarang" value="{{ old('pengarang') }}"
                               placeholder="Masukkan nama pengarang" required maxlength="200">
                        @error('pengarang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </form>
                
                <div class="mt-4">
                    <button type="button" id="btnSubmitBuku" class="btn btn-primary me-2 btn-spinner">
                        <span class="spinner-border d-none" role="status" aria-hidden="true"></span>
                        <i class="mdi mdi-content-save"></i> Simpan
                    </button>
                    <a href="{{ route('buku.index') }}" class="btn btn-light">
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
    $('#btnSubmitBuku').on('click', function(e) {
        e.preventDefault();
        
        const form = $('#formBuku')[0];
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
