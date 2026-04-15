@extends('layouts.app')

@section('title', 'Vendor - Master Menu')

@section('page-title', 'Vendor - Master Menu')
@section('page-icon', 'mdi-silverware-fork-knife')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Master Menu Vendor</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Tambah Menu</h5>
                <form method="POST" action="{{ route('vendor.menu.store') }}">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Vendor</label>
                        <select name="idvendor" class="form-select" required>
                            <option value="">-- Pilih Vendor --</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id_vendor }}" @selected(old('idvendor') == $vendor->id_vendor)>{{ $vendor->nama_vendor }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Nama Menu</label>
                        <input type="text" name="nama_menu" class="form-control" value="{{ old('nama_menu') }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Harga</label>
                        <input type="number" name="harga" min="0" class="form-control" value="{{ old('harga') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Path Gambar (opsional)</label>
                        <input type="text" name="path_gambar" class="form-control" value="{{ old('path_gambar') }}" placeholder="contoh: images/menu/nasi-goreng.jpg">
                    </div>
                    <button class="btn btn-primary w-100" type="submit">Simpan Menu</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Daftar Menu</h5>
                    <form method="GET" action="{{ route('vendor.menu.index') }}" class="d-flex gap-2">
                        <select name="idvendor" class="form-select form-select-sm">
                            <option value="">Semua Vendor</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id_vendor }}" @selected((string)$vendorFilter === (string)$vendor->id_vendor)>{{ $vendor->nama_vendor }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-sm btn-outline-primary" type="submit">Filter</button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Menu</th>
                                <th>Vendor</th>
                                <th>Harga</th>
                                <th>Path Gambar</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($menu as $item)
                                <tr>
                                    <td>{{ $item->idmenu }}</td>
                                    <td>{{ $item->nama_menu }}</td>
                                    <td>{{ $item->vendor->nama_vendor ?? '-' }}</td>
                                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td>{{ $item->path_gambar ?: '-' }}</td>
                                    <td>
                                        <a href="{{ route('vendor.menu.edit', $item->idmenu) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('vendor.menu.destroy', $item->idmenu) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus menu ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Belum ada menu.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
