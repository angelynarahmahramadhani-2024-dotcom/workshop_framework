@extends('layouts.app')

@section('title', 'Daftar Barang')

@section('page-title', 'Daftar Barang')
@section('page-icon', 'mdi-package-variant')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Barang</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
@endpush

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">Daftar Barang</h4>
                    <div>
                        <a href="{{ route('barang.print-form') }}" class="btn btn-info btn-sm me-2">
                            <i class="mdi mdi-printer"></i> Cetak Label
                        </a>
                        <a href="{{ route('barang.create') }}" class="btn btn-primary btn-sm">
                            <i class="mdi mdi-plus"></i> Tambah Barang
                        </a>
                    </div>
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                
                <div class="table-responsive">
                    <table id="barangTable" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="12%">ID Barang</th>
                                <th>Nama Barang</th>
                                <th width="15%">Harga</th>
                                <th width="15%">Waktu</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barang as $item)
                            <tr>
                                <td></td>
                                <td><span class="badge bg-primary">{{ $item->id_barang }}</span></td>
                                <td>{{ $item->nama }}</td>
                                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td>{{ date('d/m/Y H:i', strtotime($item->timestamp)) }}</td>
                                <td>
                                    <a href="{{ route('barang.edit', $item->id_barang) }}" class="btn btn-warning btn-sm">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    <form action="{{ route('barang.destroy', $item->id_barang) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Select Cards Section -->
<div class="row">
    <!-- Card Select 1 (Basic Select) -->
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card select-card">
            <div class="card-header">
                Select 1
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="select1">Pilih Kota:</label>
                    <select class="form-select" id="select1">
                        <option value="">-- Pilih Kota --</option>
                        <option value="jakarta">Jakarta</option>
                        <option value="bandung">Bandung</option>
                        <option value="surabaya">Surabaya</option>
                        <option value="medan">Medan</option>
                        <option value="makassar">Makassar</option>
                    </select>
                </div>
                <div id="kotaTerpilih1" class="mt-3"></div>
            </div>
        </div>
    </div>

    <!-- Card Select 2 (Select2 with Search) -->
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card select-card">
            <div class="card-header">
                Select 2 <small class="text-muted">(dengan pencarian)</small>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="select2">Pilih Negara:</label>
                    <select class="form-select" id="select2" style="width: 100%;">
                        <option value="">-- Pilih Negara --</option>
                        <option value="indonesia">Indonesia</option>
                        <option value="malaysia">Malaysia</option>
                        <option value="singapore">Singapura</option>
                        <option value="thailand">Thailand</option>
                        <option value="vietnam">Vietnam</option>
                        <option value="philippines">Filipina</option>
                        <option value="brunei">Brunei</option>
                        <option value="cambodia">Kamboja</option>
                        <option value="laos">Laos</option>
                        <option value="myanmar">Myanmar</option>
                    </select>
                </div>
                <div id="kotaTerpilih2" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Edit/Delete Barang -->
<div class="modal fade modal-row-action" id="modalBarang" tabindex="-1" aria-labelledby="modalBarangLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBarangLabel">Detail Barang</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>ID Barang:</label>
                    <input type="text" class="form-control" id="modal_id_barang" readonly>
                </div>
                <div class="form-group">
                    <label>Nama Barang:</label>
                    <input type="text" class="form-control" id="modal_nama" required>
                </div>
                <div class="form-group">
                    <label>Harga:</label>
                    <input type="number" class="form-control" id="modal_harga" required min="0">
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-group w-100">
                    <button type="button" class="btn btn-danger" id="btnHapusModal">
                        <i class="mdi mdi-delete"></i> Hapus
                    </button>
                    <button type="button" class="btn btn-primary" id="btnUbahModal">
                        <i class="mdi mdi-content-save"></i> Ubah
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize DataTable
    const table = $('#barangTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
        },
        "order": [[4, "desc"]], // Sort by timestamp descending
        "pageLength": 10,
        "columnDefs": [
            {
                "targets": 0,
                "orderable": false,
                "searchable": false,
                "render": function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                "targets": 5,
                "orderable": false,
                "searchable": false
            }
        ]
    });

    // Variable to store clicked row data
    let currentRowData = {};

    // Handle table row click (except action buttons)
    $('#barangTable tbody').on('click', 'tr td:not(:last-child)', function() {
        const row = table.row($(this).closest('tr'));
        const data = row.data();
        
        if (!data) return;
        
        // Extract data from table cells
        const cells = $(this).closest('tr').find('td');
        currentRowData = {
            id_barang: $(cells[1]).text().trim(),
            nama: $(cells[2]).text().trim(),
            harga: $(cells[3]).text().replace(/[^0-9]/g, '') // Remove Rp and formatting
        };
        
        // Populate modal with data
        $('#modal_id_barang').val(currentRowData.id_barang);
        $('#modal_nama').val(currentRowData.nama);
        $('#modal_harga').val(currentRowData.harga);
        
        // Show modal
        $('#modalBarang').modal('show');
    });

    // Handle Ubah button in modal (UI only, no database update)
    $('#btnUbahModal').on('click', function() {
        const newNama = $('#modal_nama').val();
        const newHarga = $('#modal_harga').val();
        
        if (!newNama || !newHarga) {
            alert('Nama dan harga tidak boleh kosong!');
            return;
        }
        
        // Update UI only (find row and update cells)
        $('#barangTable tbody tr').each(function() {
            const cells = $(this).find('td');
            const rowId = $(cells[1]).text().trim();
            
            if (rowId === currentRowData.id_barang) {
                $(cells[2]).text(newNama);
                $(cells[3]).text('Rp ' + parseInt(newHarga).toLocaleString('id-ID'));
            }
        });
        
        $('#modalBarang').modal('hide');
        
        // Show success message
        showMessage('Data berhasil diubah (hanya tampilan)', 'success');
    });

    // Handle Hapus button in modal (UI only, no database delete)
    $('#btnHapusModal').on('click', function() {
        if (!confirm('Yakin ingin menghapus data ini? (hanya tampilan)')) {
            return;
        }
        
        // Remove row from DataTable (UI only)
        $('#barangTable tbody tr').each(function() {
            const cells = $(this).find('td');
            const rowId = $(cells[1]).text().trim();
            
            if (rowId === currentRowData.id_barang) {
                table.row($(this)).remove().draw();
            }
        });
        
        $('#modalBarang').modal('hide');
        
        // Show success message
        showMessage('Data berhasil dihapus (hanya tampilan)', 'success');
    });

    // Initialize Select2 for select2
    $('#select2').select2({
        theme: 'bootstrap-5',
        placeholder: '-- Pilih Negara --',
        allowClear: true
    });

    // Handle Select 1 change
    $('#select1').on('change', function() {
        const value = $(this).val();
        const text = $(this).find('option:selected').text();
        
        if (value) {
            $('#kotaTerpilih1').html(`
                <div class="alert alert-info">
                    <strong>Kota Terpilih:</strong> ${text}
                </div>
            `);
        } else {
            $('#kotaTerpilih1').html('');
        }
    });

    // Handle Select 2 change
    $('#select2').on('change', function() {
        const value = $(this).val();
        const text = $(this).find('option:selected').text();
        
        if (value) {
            $('#kotaTerpilih2').html(`
                <div class="alert alert-success">
                    <strong>Negara Terpilih:</strong> ${text}
                </div>
            `);
        } else {
            $('#kotaTerpilih2').html('');
        }
    });

    // Helper function to show message
    function showMessage(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        $('.content-wrapper').prepend(alertHtml);
        
        // Auto dismiss after 3 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 3000);
    }
});
</script>
@endpush
