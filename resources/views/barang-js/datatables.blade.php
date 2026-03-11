@extends('layouts.app')

@section('title', 'DataTables Demo - Barang JavaScript')

@section('page-title', 'DataTables Demo JavaScript')
@section('page-icon', 'mdi-table-large')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item">Barang JavaScript</li>
    <li class="breadcrumb-item active" aria-current="page">DataTables Demo</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
<div class="row">
    {{-- Form Section --}}
    <div class="col-md-5 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Barang</h4>
                <p class="card-description">Form dengan DataTables (data tidak tersimpan ke database)</p>
                
                <form id="formBarang">
                    <div class="form-group">
                        <label for="nama_barang">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_barang" name="nama_barang" 
                               placeholder="Masukkan nama barang" maxlength="50">
                        <div class="invalid-feedback" id="error_nama_barang"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="harga_barang">Harga Barang <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="harga_barang" name="harga_barang" 
                               placeholder="Masukkan harga barang" min="0">
                        <small class="form-text text-muted">Masukkan harga dalam Rupiah</small>
                        <div class="invalid-feedback" id="error_harga_barang"></div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" id="btnSubmit" class="btn btn-primary btn-spinner">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            <i class="mdi mdi-content-save"></i> <span class="btn-text">Submit</span>
                        </button>
                        <button type="button" id="btnReset" class="btn btn-light">
                            <i class="mdi mdi-refresh"></i> Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    {{-- DataTable Section --}}
    <div class="col-md-7 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Barang (DataTables)</h4>
                <p class="card-description">Data dengan fitur sort dan pagination (tanpa search box)</p>
                
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTableBarang" style="width:100%">
                        <thead>
                            <tr>
                                <th width="20%">ID</th>
                                <th width="45%">Nama Barang</th>
                                <th width="35%">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data akan diisi oleh DataTables -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditLabel">Edit Data Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEdit">
                    <input type="hidden" id="edit_id">
                    
                    <div class="form-group">
                        <label for="edit_nama_barang">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nama_barang" name="edit_nama_barang" 
                               placeholder="Masukkan nama barang" maxlength="50">
                        <div class="invalid-feedback" id="error_edit_nama_barang"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_harga_barang">Harga Barang <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="edit_harga_barang" name="edit_harga_barang" 
                               placeholder="Masukkan harga barang" min="0">
                        <div class="invalid-feedback" id="error_edit_harga_barang"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="mdi mdi-close"></i> Tutup
                </button>
                <button type="button" id="btnUpdate" class="btn btn-primary btn-spinner">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <i class="mdi mdi-content-save"></i> <span class="btn-text">Update</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    // Array untuk menyimpan data barang (simulasi database)
    let dataBarang = [];
    let table;
    let editIndex = -1;
    
    // Load data dari localStorage saat halaman dibuka
    loadDataFromLocalStorage();
    
    // ========== INITIALIZE DATATABLES ==========
    function initDataTable() {
        if ($.fn.DataTable.isDataTable('#dataTableBarang')) {
            $('#dataTableBarang').DataTable().destroy();
        }
        
        table = $('#dataTableBarang').DataTable({
            data: dataBarang,
            searching: false, // Matikan fitur search
            columns: [
                { 
                    data: 'id',
                    render: function(data) {
                        return '<span class="badge bg-primary">' + data + '</span>';
                    }
                },
                { 
                    data: 'nama'
                },
                { 
                    data: 'harga',
                    render: function(data, type, row) {
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        }).format(data);
                    }
                }
            ],
            language: {
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Belum ada data",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                },
                zeroRecords: "Data tidak ditemukan",
                emptyTable: "Belum ada data. Silahkan tambah data melalui form."
            },
            pageLength: 5,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
            order: [[0, 'asc']],
            columnDefs: [
                {
                    targets: -1,
                    data: null,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return `
                            <button class="btn btn-warning btn-sm btn-edit" data-index="${meta.row}">
                                <i class="mdi mdi-pencil"></i>
                            </button>
                            <button class="btn btn-danger btn-sm btn-delete" data-index="${meta.row}" data-nama="${row.nama}">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        `;
                    }
                }
            ]
        });
    }
    
    // Initialize table
    initDataTable();
    
    // ========== VALIDASI REAL-TIME ==========
    $('#nama_barang').on('keyup blur', function() {
        validateNamaBarang($(this));
    });
    
    $('#harga_barang').on('keyup blur', function() {
        validateHargaBarang($(this));
    });
    
    $('#edit_nama_barang').on('keyup blur', function() {
        validateEditNamaBarang($(this));
    });
    
    $('#edit_harga_barang').on('keyup blur', function() {
        validateEditHargaBarang($(this));
    });
    
    // ========== SUBMIT FORM TAMBAH ==========
    $('#formBarang').on('submit', function(e) {
        e.preventDefault();
        
        const namaInput = $('#nama_barang');
        const hargaInput = $('#harga_barang');
        
        // Validasi semua input
        const isNamaValid = validateNamaBarang(namaInput);
        const isHargaValid = validateHargaBarang(hargaInput);
        
        if (!isNamaValid || !isHargaValid) {
            return false;
        }
        
        const btn = $('#btnSubmit');
        const spinner = btn.find('.spinner-border');
        const icon = btn.find('i.mdi');
        const btnText = btn.find('.btn-text');
        
        // Tampilkan spinner
        btn.prop('disabled', true);
        spinner.removeClass('d-none');
        icon.addClass('d-none');
        btnText.text('Menyimpan...');
        
        // Simulasi proses menyimpan (delay 800ms)
        setTimeout(function() {
            // Generate ID random
            const newId = 'BRG' + Math.floor(Math.random() * 10000).toString().padStart(4, '0');
            
            // Tambah data ke array
            dataBarang.push({
                id: newId,
                nama: namaInput.val(),
                harga: parseInt(hargaInput.val())
            });
            
            // Simpan ke localStorage
            saveDataToLocalStorage();
            
            // Refresh DataTable
            initDataTable();
            
            // Reset form
            $('#formBarang')[0].reset();
            namaInput.removeClass('is-valid');
            hargaInput.removeClass('is-valid');
            
            // Sembunyikan spinner
            btn.prop('disabled', false);
            spinner.addClass('d-none');
            icon.removeClass('d-none');
            btnText.text('Submit');
            
            // Tampilkan notifikasi sukses
            showToast('Berhasil!', 'Data barang berhasil ditambahkan.', 'success');
        }, 800);
    });
    
    // ========== RESET FORM ==========
    $('#btnReset').on('click', function() {
        $('#formBarang')[0].reset();
        $('#nama_barang, #harga_barang').removeClass('is-valid is-invalid');
        $('.invalid-feedback').text('').hide();
    });
    
    // ========== EDIT DATA ==========
    $(document).on('click', '.btn-edit', function() {
        const index = $(this).data('index');
        const rowData = table.row(index).data();
        editIndex = dataBarang.findIndex(item => item.id === rowData.id);
        
        if (editIndex !== -1) {
            const item = dataBarang[editIndex];
            $('#edit_id').val(item.id);
            $('#edit_nama_barang').val(item.nama);
            $('#edit_harga_barang').val(item.harga);
            
            // Reset validasi
            $('#edit_nama_barang, #edit_harga_barang').removeClass('is-valid is-invalid');
            $('.invalid-feedback').text('').hide();
            
            // Tampilkan modal
            const modal = new bootstrap.Modal(document.getElementById('modalEdit'));
            modal.show();
        }
    });
    
    // ========== UPDATE DATA ==========
    $('#btnUpdate').on('click', function() {
        const namaInput = $('#edit_nama_barang');
        const hargaInput = $('#edit_harga_barang');
        
        // Validasi semua input
        const isNamaValid = validateEditNamaBarang(namaInput);
        const isHargaValid = validateEditHargaBarang(hargaInput);
        
        if (!isNamaValid || !isHargaValid) {
            return false;
        }
        
        const btn = $(this);
        const spinner = btn.find('.spinner-border');
        const icon = btn.find('i.mdi');
        const btnText = btn.find('.btn-text');
        
        // Tampilkan spinner
        btn.prop('disabled', true);
        spinner.removeClass('d-none');
        icon.addClass('d-none');
        btnText.text('Mengupdate...');
        
        // Simulasi proses update (delay 500ms)
        setTimeout(function() {
            // Update data di array
            dataBarang[editIndex].nama = namaInput.val();
            dataBarang[editIndex].harga = parseInt(hargaInput.val());
            
            // Simpan ke localStorage
            saveDataToLocalStorage();
            
            // Refresh DataTable
            initDataTable();
            
            // Sembunyikan spinner
            btn.prop('disabled', false);
            spinner.addClass('d-none');
            icon.removeClass('d-none');
            btnText.text('Update');
            
            // Tutup modal
            bootstrap.Modal.getInstance(document.getElementById('modalEdit')).hide();
            
            // Tampilkan notifikasi sukses
            showToast('Berhasil!', 'Data barang berhasil diupdate.', 'success');
        }, 500);
    });
    
    // ========== DELETE DATA ==========
    $(document).on('click', '.btn-delete', function() {
        const index = $(this).data('index');
        const nama = $(this).data('nama');
        const rowData = table.row(index).data();
        
        // Konfirmasi hapus
        if (confirm(`Apakah Anda yakin ingin menghapus barang "${nama}"?`)) {
            // Hapus dari array
            dataBarang = dataBarang.filter(item => item.id !== rowData.id);
            
            // Simpan ke localStorage
            saveDataToLocalStorage();
            
            // Refresh DataTable
            initDataTable();
            
            // Tampilkan notifikasi sukses
            showToast('Berhasil!', 'Data barang berhasil dihapus.', 'success');
        }
    });
    
    // ========== FUNGSI VALIDASI ==========
    function validateNamaBarang(input) {
        const value = input.val().trim();
        const errorDiv = $('#error_nama_barang');
        
        if (value === '') {
            input.removeClass('is-valid').addClass('is-invalid');
            errorDiv.text('Nama barang wajib diisi!').show();
            return false;
        } else if (value.length < 3) {
            input.removeClass('is-valid').addClass('is-invalid');
            errorDiv.text('Nama barang minimal 3 karakter!').show();
            return false;
        } else {
            input.removeClass('is-invalid').addClass('is-valid');
            errorDiv.text('').hide();
            return true;
        }
    }
    
    function validateHargaBarang(input) {
        const value = input.val();
        const errorDiv = $('#error_harga_barang');
        
        if (value === '') {
            input.removeClass('is-valid').addClass('is-invalid');
            errorDiv.text('Harga barang wajib diisi!').show();
            return false;
        } else if (parseInt(value) < 0) {
            input.removeClass('is-valid').addClass('is-invalid');
            errorDiv.text('Harga barang tidak boleh negatif!').show();
            return false;
        } else if (parseInt(value) === 0) {
            input.removeClass('is-valid').addClass('is-invalid');
            errorDiv.text('Harga barang harus lebih dari 0!').show();
            return false;
        } else {
            input.removeClass('is-invalid').addClass('is-valid');
            errorDiv.text('').hide();
            return true;
        }
    }
    
    function validateEditNamaBarang(input) {
        const value = input.val().trim();
        const errorDiv = $('#error_edit_nama_barang');
        
        if (value === '') {
            input.removeClass('is-valid').addClass('is-invalid');
            errorDiv.text('Nama barang wajib diisi!').show();
            return false;
        } else if (value.length < 3) {
            input.removeClass('is-valid').addClass('is-invalid');
            errorDiv.text('Nama barang minimal 3 karakter!').show();
            return false;
        } else {
            input.removeClass('is-invalid').addClass('is-valid');
            errorDiv.text('').hide();
            return true;
        }
    }
    
    function validateEditHargaBarang(input) {
        const value = input.val();
        const errorDiv = $('#error_edit_harga_barang');
        
        if (value === '') {
            input.removeClass('is-valid').addClass('is-invalid');
            errorDiv.text('Harga barang wajib diisi!').show();
            return false;
        } else if (parseInt(value) < 0) {
            input.removeClass('is-valid').addClass('is-invalid');
            errorDiv.text('Harga barang tidak boleh negatif!').show();
            return false;
        } else if (parseInt(value) === 0) {
            input.removeClass('is-valid').addClass('is-invalid');
            errorDiv.text('Harga barang harus lebih dari 0!').show();
            return false;
        } else {
            input.removeClass('is-invalid').addClass('is-valid');
            errorDiv.text('').hide();
            return true;
        }
    }
    
    // ========== LOCAL STORAGE FUNCTIONS ==========
    function saveDataToLocalStorage() {
        localStorage.setItem('barangJsDataTables', JSON.stringify(dataBarang));
    }
    
    function loadDataFromLocalStorage() {
        const stored = localStorage.getItem('barangJsDataTables');
        if (stored) {
            dataBarang = JSON.parse(stored);
        }
    }
    
    // ========== TOAST NOTIFICATION ==========
    function showToast(title, message, type) {
        // Anda bisa gunakan library toast seperti toastr.js atau bootstrap toast
        // Untuk sementara menggunakan alert
        alert(title + '\n' + message);
    }
});
</script>
@endpush
