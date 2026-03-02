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
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#barangTable').DataTable({
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
});
</script>
@endpush
