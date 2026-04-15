@extends('layouts.app')

@section('title', 'Vendor - Edit Menu')

@section('page-title', 'Vendor - Edit Menu')
@section('page-icon', 'mdi-pencil')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('vendor.menu.index') }}">Master Menu Vendor</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Menu</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Edit Menu #{{ $menuItem->idmenu }}</h5>

                <form method="POST" action="{{ route('vendor.menu.update', $menuItem->idmenu) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-2">
                        <label class="form-label">Vendor</label>
                        <select name="idvendor" class="form-select" required>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id_vendor }}" @selected(old('idvendor', $menuItem->idvendor) == $vendor->id_vendor)>{{ $vendor->nama_vendor }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Nama Menu</label>
                        <input type="text" name="nama_menu" class="form-control" value="{{ old('nama_menu', $menuItem->nama_menu) }}" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Harga</label>
                        <input type="number" name="harga" min="0" class="form-control" value="{{ old('harga', $menuItem->harga) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Path Gambar (opsional)</label>
                        <input type="text" name="path_gambar" class="form-control" value="{{ old('path_gambar', $menuItem->path_gambar) }}">
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('vendor.menu.index') }}" class="btn btn-light">Kembali</a>
                        <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
