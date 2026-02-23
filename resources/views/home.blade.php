@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')
@section('page-icon', 'mdi-home')
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="font-weight-bold">Selamat Datang, {{ Auth::user()->name }}!</h3>
                <p class="text-muted">Dashboard Sistem Koleksi Buku</p>
            </div>
        </div>
    </div>
</div>

{{-- Statistik Cards --}}
<div class="row">
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card bg-gradient-primary card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('images/circle.svg') }}" class="card-img-absolute" alt="circle-image">
                <h4 class="font-weight-normal mb-3">Total Buku
                    <i class="mdi mdi-book-multiple mdi-24px float-right"></i>
                </h4>
                <h2 class="mb-5">{{ \App\Models\Buku::count() }}</h2>
                <h6 class="card-text">Koleksi perpustakaan</h6>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card bg-gradient-info card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('images/circle.svg') }}" class="card-img-absolute" alt="circle-image">
                <h4 class="font-weight-normal mb-3">Total Kategori
                    <i class="mdi mdi-tag-multiple mdi-24px float-right"></i>
                </h4>
                <h2 class="mb-5">{{ \App\Models\Kategori::count() }}</h2>
                <h6 class="card-text">Jenis kategori buku</h6>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card bg-gradient-success card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('images/circle.svg') }}" class="card-img-absolute" alt="circle-image">
                <h4 class="font-weight-normal mb-3">Total Pengguna
                    <i class="mdi mdi-account-multiple mdi-24px float-right"></i>
                </h4>
                <h2 class="mb-5">{{ \App\Models\User::count() }}</h2>
                <h6 class="card-text">Pengguna terdaftar</h6>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card bg-gradient-danger card-img-holder text-white">
            <div class="card-body">
                <img src="{{ asset('images/circle.svg') }}" class="card-img-absolute" alt="circle-image">
                <h4 class="font-weight-normal mb-3">Hari Ini
                    <i class="mdi mdi-calendar-today mdi-24px float-right"></i>
                </h4>
                <h2 class="mb-5">{{ date('d M Y') }}</h2>
                <h6 class="card-text">Tanggal hari ini</h6>
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Aksi Cepat</h4>
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('buku.create') }}" class="btn btn-outline-primary btn-lg btn-block">
                            <i class="mdi mdi-book-plus"></i><br>Tambah Buku
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('kategori.create') }}" class="btn btn-outline-info btn-lg btn-block">
                            <i class="mdi mdi-tag-plus"></i><br>Tambah Kategori
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('buku.index') }}" class="btn btn-outline-success btn-lg btn-block">
                            <i class="mdi mdi-book-multiple"></i><br>Lihat Buku
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="{{ route('kategori.index') }}" class="btn btn-outline-warning btn-lg btn-block">
                            <i class="mdi mdi-tag-multiple"></i><br>Lihat Kategori
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Daftar Buku Terbaru --}}
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Daftar Buku</h4>
                    <a href="{{ route('buku.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Judul</th>
                                <th>Pengarang</th>
                                <th>Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\Buku::with('kategori')->latest('idbuku')->take(5)->get() as $buku)
                            <tr>
                                <td><span class="badge bg-primary">{{ $buku->kode }}</span></td>
                                <td>{{ $buku->judul }}</td>
                                <td>{{ $buku->pengarang }}</td>
                                <td><span class="badge bg-info">{{ $buku->kategori->nama_kategori ?? '-' }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada data buku</td>
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
