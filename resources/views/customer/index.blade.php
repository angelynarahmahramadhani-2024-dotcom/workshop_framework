@extends('layouts.app')

@section('title', 'Data Customer')

@section('page-title', 'Data Customer')
@section('page-icon', 'mdi-account-multiple')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Data Customer</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Customer</h4>
                <p class="card-description">
                    Data customer yang sudah terdaftar
                    <a href="{{ route('customer.create1') }}" class="btn btn-primary btn-sm float-end ms-2">
                        <i class="mdi mdi-camera"></i> Tambah Customer 1 (BLOB)
                    </a>
                    <a href="{{ route('customer.create2') }}" class="btn btn-info btn-sm float-end">
                        <i class="mdi mdi-camera"></i> Tambah Customer 2 (File)
                    </a>
                </p>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Provinsi</th>
                                <th>Kota</th>
                                <th>Kecamatan</th>
                                <th>Kelurahan</th>
                                <th>Tipe Foto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $i => $customer)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    @if($customer->foto_blob)
                                        <img src="{{ route('customer.foto', $customer->id) }}" 
                                             alt="Foto {{ $customer->nama }}" 
                                             style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                                    @elseif($customer->foto_path)
                                        <img src="{{ asset('storage/' . $customer->foto_path) }}" 
                                             alt="Foto {{ $customer->nama }}" 
                                             style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                                    @else
                                        <span class="badge bg-secondary">No Foto</span>
                                    @endif
                                </td>
                                <td>{{ $customer->nama }}</td>
                                <td>{{ $customer->alamat ?? '-' }}</td>
                                <td>{{ $customer->provinsi->nama ?? '-' }}</td>
                                <td>{{ $customer->kota->nama ?? '-' }}</td>
                                <td>{{ $customer->kecamatan->nama ?? '-' }}</td>
                                <td>{{ $customer->kelurahan->nama ?? '-' }}</td>
                                <td>
                                    @if($customer->foto_blob)
                                        <span class="badge bg-warning text-dark">BLOB</span>
                                    @elseif($customer->foto_path)
                                        <span class="badge bg-info text-white">File Path</span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">Belum ada data customer</td>
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
