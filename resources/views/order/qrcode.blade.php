@extends('layouts.app')

@section('title', 'QR Code Pesanan — #' . $pesanan->idpesanan)

@section('page-title', 'QR Code Pesanan')
@section('page-icon', 'mdi-qrcode')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('order.index') }}">Order</a></li>
    <li class="breadcrumb-item active" aria-current="page">QR Code #{{ $pesanan->idpesanan }}</li>
@endsection

@push('styles')
<style>
    .qr-card {
        max-width: 480px;
        margin: 0 auto;
        text-align: center;
    }
    .success-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #ecfdf5, #d1fae5);
        border: 1px solid #34d399;
        color: #047857;
        padding: 8px 20px;
        border-radius: 100px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 20px;
    }
    .qr-wrapper {
        display: inline-block;
        background: #fff;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .qr-wrapper svg {
        display: block;
    }
    .order-info {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 20px;
        text-align: left;
    }
    .order-info .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
        font-size: 0.9rem;
    }
    .order-info .info-row:not(:last-child) {
        border-bottom: 1px solid #e2e8f0;
    }
    .order-info .info-label {
        color: #64748b;
        font-weight: 500;
    }
    .order-info .info-value {
        font-weight: 700;
    }
    .order-info .info-value.total {
        color: #7c3aed;
        font-size: 1.05rem;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body qr-card">
                <div class="success-badge">✅ Pembayaran Berhasil</div>

                <h4 class="card-title">QR Code Pesanan</h4>
                <p class="card-description">Tunjukkan QR Code ini ke kasir untuk mengambil pesananmu</p>

                <div class="qr-wrapper">
                    {!! $qrCode !!}
                </div>

                <div class="order-info">
                    <div class="info-row">
                        <span class="info-label">ID Pesanan</span>
                        <span class="info-value">#{{ $pesanan->idpesanan }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Nama</span>
                        <span class="info-value">{{ $pesanan->nama }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Total</span>
                        <span class="info-value total">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status</span>
                        <span class="info-value" style="color:#059669;">{{ $pesanan->status_bayar_label ?? 'Lunas' }}</span>
                    </div>
                </div>

                <a href="{{ route('order.index') }}" class="btn btn-primary">
                    <i class="mdi mdi-food"></i> Pesan Lagi
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
