@extends('layouts.app')

@section('title', 'Dashboard')

{{-- Style Page - Khusus halaman ini --}}
@push('styles')
<style>
    .fs-30 {
        font-size: 30px;
    }
</style>
@endpush

@section('content')
    {{-- Page Header --}}
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between flex-wrap">
                <div class="d-flex align-items-end flex-wrap">
                    <div class="me-md-3 me-xl-5">
                        <h2>Selamat Datang!</h2>
                        <p class="mb-md-0">Dashboard Sistem Koleksi Buku</p>
                    </div>
                    <div class="d-flex">
                        <i class="mdi mdi-home text-muted hover-cursor"></i>
                        <p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;Dashboard&nbsp;</p>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-end flex-wrap">
                    <a href="{{ url('/buku/create') }}" class="btn btn-gradient-primary btn-rounded btn-icon-text mb-2 mb-md-0">
                        <i class="mdi mdi-plus-circle btn-icon-prepend"></i>
                        Tambah Buku
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row">
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card card-tale">
                <div class="card-body">
                    <p class="mb-4">Total Buku</p>
                    <p class="fs-30 mb-2">0</p>
                    <p>Koleksi perpustakaan</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card card-dark-blue">
                <div class="card-body">
                    <p class="mb-4">Total Kategori</p>
                    <p class="fs-30 mb-2">0</p>
                    <p>Jenis kategori buku</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card card-light-blue">
                <div class="card-body">
                    <p class="mb-4">Total Pengguna</p>
                    <p class="fs-30 mb-2">0</p>
                    <p>Pengguna terdaftar</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card card-light-danger">
                <div class="card-body">
                    <p class="mb-4">Aktivitas Hari Ini</p>
                    <p class="fs-30 mb-2">0</p>
                    <p>Aktivitas tercatat</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title mb-4">Aksi Cepat</p>
                    <div class="row">
                        <div class="col-md-3 col-6 mb-4">
                            <a href="{{ url('/buku/create') }}" class="text-decoration-none">
                                <div class="card card-outline-primary p-3 text-center h-100">
                                    <i class="mdi mdi-book-plus text-primary" style="font-size: 2.5rem;"></i>
                                    <h5 class="mt-3 mb-1">Tambah Buku</h5>
                                    <p class="text-muted small mb-0">Tambahkan buku baru ke koleksi</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-6 mb-4">
                            <a href="{{ url('/kategori/create') }}" class="text-decoration-none">
                                <div class="card card-outline-success p-3 text-center h-100">
                                    <i class="mdi mdi-tag-plus text-success" style="font-size: 2.5rem;"></i>
                                    <h5 class="mt-3 mb-1">Tambah Kategori</h5>
                                    <p class="text-muted small mb-0">Buat kategori baru</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-6 mb-4">
                            <a href="{{ url('/buku') }}" class="text-decoration-none">
                                <div class="card card-outline-warning p-3 text-center h-100">
                                    <i class="mdi mdi-book-multiple text-warning" style="font-size: 2.5rem;"></i>
                                    <h5 class="mt-3 mb-1">Lihat Buku</h5>
                                    <p class="text-muted small mb-0">Kelola data buku yang ada</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-6 mb-4">
                            <a href="{{ url('/laporan') }}" class="text-decoration-none">
                                <div class="card card-outline-danger p-3 text-center h-100">
                                    <i class="mdi mdi-chart-bar text-danger" style="font-size: 2.5rem;"></i>
                                    <h5 class="mt-3 mb-1">Lihat Laporan</h5>
                                    <p class="text-muted small mb-0">Statistik dan laporan lengkap</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Activity & Info --}}
    <div class="row">
        <div class="col-md-7 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Aktivitas Terakhir</p>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Aktivitas</th>
                                    <th>Waktu</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Belum ada aktivitas</td>
                                    <td>-</td>
                                    <td><span class="badge badge-gradient-info">Info</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Informasi Sistem</p>
                    <ul class="list-unstyled">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Versi Aplikasi</span>
                            <span class="fw-bold">1.0.0</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Framework</span>
                            <span class="fw-bold">Laravel {{ app()->version() }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">PHP Version</span>
                            <span class="fw-bold">{{ phpversion() }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Template</span>
                            <span class="fw-bold">Purple Admin</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted">Server Time</span>
                            <span class="fw-bold">{{ now()->format('d M Y H:i') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Javascript Page - Khusus halaman ini --}}
@push('scripts')
<script>
    // Dashboard-specific JavaScript
    $(document).ready(function() {
        console.log('Dashboard page loaded');
    });
</script>
@endpush
