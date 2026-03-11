@extends('layouts.app')

@section('title', 'Select Kota')

@section('page-title', 'Select Kota')
@section('page-icon', 'mdi-map-marker')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Select Kota</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
@endpush

@section('content')
<!-- Select Cards Section -->
<div class="row">
    <!-- Card Select 1 -->
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Select 1</h4>
                <p class="card-description">Menggunakan select biasa</p>
                
                <div class="form-group">
                    <label for="inputKota1">Nama Kota:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="inputKota1" placeholder="Masukkan nama kota">
                        <button class="btn btn-primary btn-spinner" type="button" id="btnTambahKota1">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            <i class="mdi mdi-plus"></i> <span class="btn-text">Tambahkan</span>
                        </button>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="select1">Pilih Kota:</label>
                    <select class="form-select" id="select1">
                        <option value="">-- Pilih Kota --</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Kota Terpilih:</label>
                    <div id="kotaTerpilih1">
                        <p class="text-muted">Belum ada kota yang dipilih</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Select 2 (dengan Select2) -->
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Select 2</h4>
                <p class="card-description">Menggunakan Select2 dengan fitur search</p>
                
                <div class="form-group">
                    <label for="inputKota2">Nama Kota:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="inputKota2" placeholder="Masukkan nama kota">
                        <button class="btn btn-primary btn-spinner" type="button" id="btnTambahKota2">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            <i class="mdi mdi-plus"></i> <span class="btn-text">Tambahkan</span>
                        </button>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="select2">Pilih Kota:</label>
                    <select class="form-select" id="select2" style="width: 100%;">
                        <option value="">-- Pilih Kota --</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Kota Terpilih:</label>
                    <div id="kotaTerpilih2">
                        <p class="text-muted">Belum ada kota yang dipilih</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // ========== INITIALIZE SELECT2 (Card 2) ==========
    $('#select2').select2({
        theme: 'bootstrap-5',
        placeholder: '-- Pilih Kota --',
        allowClear: true
    });
    
    // ========== CARD 1: TAMBAH KOTA (Select Biasa) ==========
    $('#btnTambahKota1').on('click', function() {
        const kota = $('#inputKota1').val().trim();
        
        if (kota === '') {
            alert('Nama kota tidak boleh kosong!');
            return;
        }
        
        // Cek apakah kota sudah ada
        const exists = $('#select1 option').filter(function() {
            return $(this).val() === kota;
        }).length > 0;
        
        if (exists) {
            alert('Kota "' + kota + '" sudah ada dalam daftar!');
            return;
        }
        
        const btn = $(this);
        const spinner = btn.find('.spinner-border');
        const icon = btn.find('i.mdi');
        const btnText = btn.find('.btn-text');
        
        // Tampilkan spinner
        btn.prop('disabled', true);
        spinner.removeClass('d-none');
        icon.addClass('d-none');
        btnText.text('Menambahkan...');
        
        // Simulasi proses tambah kota (delay 500ms)
        setTimeout(function() {
            // Tambahkan opsi baru ke select
            $('#select1').append(new Option(kota, kota));
            
            // Clear input
            $('#inputKota1').val('');
            
            // Fokus kembali ke input
            $('#inputKota1').focus();
            
            // Sembunyikan spinner
            btn.prop('disabled', false);
            spinner.addClass('d-none');
            icon.removeClass('d-none');
            btnText.text('Tambahkan');
        }, 500);
    });
    
    // Enter key untuk tambah kota (Card 1)
    $('#inputKota1').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            $('#btnTambahKota1').click();
        }
    });
    
    // ========== CARD 1: EVENT SELECT CHANGE ==========
    $('#select1').on('change', function() {
        const kota = $(this).val();
        
        if (kota) {
            $('#kotaTerpilih1').html(`
                <div class="alert alert-success" role="alert">
                    <i class="mdi mdi-map-marker"></i> <strong>${kota}</strong>
                </div>
            `);
        } else {
            $('#kotaTerpilih1').html('<p class="text-muted">Belum ada kota yang dipilih</p>');
        }
    });
    
    // ========== CARD 2: TAMBAH KOTA (Select2) ==========
    $('#btnTambahKota2').on('click', function() {
        const kota = $('#inputKota2').val().trim();
        
        if (kota === '') {
            alert('Nama kota tidak boleh kosong!');
            return;
        }
        
        // Cek apakah kota sudah ada
        const exists = $('#select2 option').filter(function() {
            return $(this).val() === kota;
        }).length > 0;
        
        if (exists) {
            alert('Kota "' + kota + '" sudah ada dalam daftar!');
            return;
        }
        
        const btn = $(this);
        const spinner = btn.find('.spinner-border');
        const icon = btn.find('i.mdi');
        const btnText = btn.find('.btn-text');
        
        // Tampilkan spinner
        btn.prop('disabled', true);
        spinner.removeClass('d-none');
        icon.addClass('d-none');
        btnText.text('Menambahkan...');
        
        // Simulasi proses tambah kota (delay 500ms)
        setTimeout(function() {
            // Tambahkan opsi baru ke select2
            const newOption = new Option(kota, kota, false, false);
            $('#select2').append(newOption).trigger('change');
            
            // Clear input
            $('#inputKota2').val('');
            
            // Fokus kembali ke input
            $('#inputKota2').focus();
            
            // Sembunyikan spinner
            btn.prop('disabled', false);
            spinner.addClass('d-none');
            icon.removeClass('d-none');
            btnText.text('Tambahkan');
        }, 500);
    });
    
    // Enter key untuk tambah kota (Card 2)
    $('#inputKota2').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            $('#btnTambahKota2').click();
        }
    });
    
    // ========== CARD 2: EVENT SELECT2 CHANGE ==========
    $('#select2').on('change', function() {
        const kota = $(this).val();
        
        if (kota) {
            $('#kotaTerpilih2').html(`
                <div class="alert alert-success" role="alert">
                    <i class="mdi mdi-map-marker"></i> <strong>${kota}</strong>
                </div>
            `);
        } else {
            $('#kotaTerpilih2').html('<p class="text-muted">Belum ada kota yang dipilih</p>');
        }
    });
});
</script>
@endpush
