@extends('layouts.app')

@section('title', 'Vendor - Pesanan Lunas')

@section('page-title', 'Vendor - Pesanan Lunas')
@section('page-icon', 'mdi-cash-check')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Pesanan Lunas</li>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title mb-0">Daftar Pesanan Status Lunas</h5>
            <form method="GET" action="{{ route('vendor.orders.paid') }}" class="d-flex gap-2">
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
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Customer</th>
                        <th>Waktu</th>
                        <th>Total</th>
                        <th>Metode Bayar</th>
                        <th>Status</th>
                        <th>Detail Item</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>#{{ $order->idpesanan }}</td>
                            <td>{{ $order->nama }}</td>
                            <td>{{ $order->timestamp }}</td>
                            <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            <td>{{ $order->metode_bayar_label }}</td>
                            <td><span class="badge badge-gradient-success">{{ strtoupper($order->status_bayar_label) }}</span></td>
                            <td>
                                @if($order->detail->isEmpty())
                                    <span class="text-muted">-</span>
                                @else
                                    <ul class="mb-0 ps-3">
                                        @foreach($order->detail as $item)
                                            <li>{{ $item->menu->nama_menu ?? 'Menu #'.$item->idmenu }} x {{ $item->jumlah }} — Rp {{ number_format($item->subtotal, 0, ',', '.') }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada pesanan lunas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
