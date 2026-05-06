@extends('layouts.app')

@section('title', 'Scan QR Pesanan — Vendor')

@section('page-title', 'Scan QR Pesanan')
@section('page-icon', 'mdi-qrcode-scan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('vendor.orders.paid') }}">Pesanan Lunas</a></li>
    <li class="breadcrumb-item active" aria-current="page">Scan QR</li>
@endsection

@push('styles')
<style>
    .scanner-container {
        max-width: 520px;
        margin: 0 auto;
    }
    #reader {
        width: 100%;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid #e2e8f0;
    }

    /* Order Result */
    .order-result {
        background: #fff;
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        animation: fadeInUp 0.4s ease;
    }
    .order-header {
        background: linear-gradient(135deg, #a855f7, #7c3aed);
        color: #fff;
        padding: 20px 24px;
        text-align: center;
    }
    .order-header h5 {
        margin: 0;
        font-weight: 700;
        font-size: 1.1rem;
    }
    .order-header .order-id {
        font-size: 0.85rem;
        opacity: 0.85;
        margin-top: 4px;
    }
    .order-body {
        padding: 20px 24px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-bottom: 18px;
    }
    .info-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 14px;
    }
    .info-item .info-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 4px;
    }
    .info-item .info-value {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
    }
    .info-item .info-value.lunas { color: #059669; }
    .info-item .info-value.pending { color: #d97706; }
    .info-item .info-value.gagal { color: #dc2626; }
    .info-item .info-value.total { color: #7c3aed; }

    /* Menu Table */
    .menu-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .menu-table thead th {
        background: #f1f5f9;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        font-weight: 600;
        padding: 10px 12px;
        border-bottom: 2px solid #e2e8f0;
    }
    .menu-table thead th:first-child { border-radius: 8px 0 0 0; }
    .menu-table thead th:last-child  { border-radius: 0 8px 0 0; }
    .menu-table tbody td {
        padding: 10px 12px;
        font-size: 0.9rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .menu-table tbody tr:last-child td { border-bottom: none; }
    .menu-table tfoot th {
        padding: 12px;
        border-top: 2px solid #ddd6fe;
        font-size: 0.95rem;
    }

    /* Status badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 18px;
        border-radius: 100px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .status-scanning {
        background: linear-gradient(135deg, #ecfdf5, #d1fae5);
        border: 1px solid #34d399;
        color: #047857;
    }
    .status-found {
        background: linear-gradient(135deg, #f5f3ff, #ede9fe);
        border: 1px solid #a78bfa;
        color: #6d28d9;
    }
    .status-error {
        background: linear-gradient(135deg, #fef2f2, #fee2e2);
        border: 1px solid #f87171;
        color: #dc2626;
    }

    .btn-scan-again {
        background: linear-gradient(135deg, #a855f7, #7c3aed);
        border: none;
        color: #fff;
        font-weight: 700;
        font-size: 0.95rem;
        padding: 12px 32px;
        border-radius: 10px;
        transition: all 0.25s ease;
        box-shadow: 0 4px 14px rgba(124,58,237,0.2);
    }
    .btn-scan-again:hover {
        background: linear-gradient(135deg, #9333ea, #6d28d9);
        transform: translateY(-1px);
        color: #fff;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">
                    <i class="mdi mdi-qrcode-scan"></i> Scan QR Code Pesanan
                </h4>
                <p class="card-description">
                    Arahkan kamera ke QR Code customer untuk melihat detail pesanan dan status pembayaran.
                </p>

                {{-- Status --}}
                <div class="text-center mb-3">
                    <span class="status-badge status-scanning" id="statusBadge">
                        📷 Sedang memindai...
                    </span>
                </div>

                <div class="scanner-container">
                    {{-- Scanner Area --}}
                    <div id="reader"></div>

                    {{-- Result Area --}}
                    <div id="resultArea" class="mt-4" style="display:none;"></div>

                    {{-- Scan Again Button --}}
                    <div class="text-center mt-3" id="scanAgainWrapper" style="display:none;">
                        <button class="btn btn-scan-again" onclick="startScanner()">
                            <i class="mdi mdi-qrcode-scan"></i> Scan Lagi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- html5-qrcode library --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    // ─── Beep (Web Audio API) ───────────────────
    function playBeep(frequency = 1000, duration = 200) {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = ctx.createOscillator();
            const gain = ctx.createGain();
            oscillator.connect(gain);
            gain.connect(ctx.destination);
            oscillator.frequency.value = frequency;
            oscillator.type = 'sine';
            gain.gain.value = 0.5;
            oscillator.start();
            setTimeout(() => { oscillator.stop(); ctx.close(); }, duration);
        } catch (e) {
            console.warn('Audio not available', e);
        }
    }

    // ─── Scanner Instance ───────────────────────
    let html5QrcodeScanner = null;
    const statusBadge = document.getElementById('statusBadge');
    const resultArea = document.getElementById('resultArea');
    const scanAgainWrapper = document.getElementById('scanAgainWrapper');

    function startScanner() {
        resultArea.style.display = 'none';
        scanAgainWrapper.style.display = 'none';
        statusBadge.className = 'status-badge status-scanning';
        statusBadge.innerHTML = '📷 Sedang memindai...';

        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear();
        }

        html5QrcodeScanner = new Html5QrcodeScanner("reader", {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA],
            rememberLastUsedCamera: true,
        });

        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    }

    async function onScanSuccess(decodedText, decodedResult) {
        // 1. Beep pendek
        playBeep(1000, 200);

        // 2. Stop scanner
        await html5QrcodeScanner.clear();

        statusBadge.className = 'status-badge status-found';
        statusBadge.innerHTML = '🔍 Mengambil data pesanan...';

        // 3. AJAX ambil detail pesanan
        try {
            const response = await axios.get('{{ route("vendor.order-by-qr") }}', {
                params: { idpesanan: decodedText }
            });

            const order = response.data.data;
            const statusClass = order.status_bayar === 1 ? 'lunas'
                              : order.status_bayar === 0 ? 'pending' : 'gagal';

            statusBadge.className = 'status-badge status-found';
            statusBadge.innerHTML = '✅ Pesanan ditemukan!';

            // Build menu rows
            let menuRows = '';
            order.items.forEach(item => {
                menuRows += `
                    <tr>
                        <td style="font-weight:500;">${esc(item.nama_menu)}</td>
                        <td class="text-center">${item.jumlah}</td>
                        <td class="text-end">${rupiah(item.harga)}</td>
                        <td class="text-end" style="font-weight:600;">${rupiah(item.subtotal)}</td>
                    </tr>
                `;
            });

            resultArea.style.display = 'block';
            resultArea.innerHTML = `
                <div class="order-result">
                    <div class="order-header">
                        <h5>📋 Detail Pesanan</h5>
                        <div class="order-id">ID Pesanan: #${order.idpesanan}</div>
                    </div>
                    <div class="order-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Customer</div>
                                <div class="info-value">${esc(order.nama)}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Status Bayar</div>
                                <div class="info-value ${statusClass}">${esc(order.status_bayar_label)}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Metode Bayar</div>
                                <div class="info-value">${esc(order.metode_bayar_label)}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Total</div>
                                <div class="info-value total">${rupiah(order.total)}</div>
                            </div>
                        </div>

                        <h6 style="font-weight:700;margin-bottom:10px;">🍽️ Menu yang Dipesan</h6>
                        <table class="menu-table">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${menuRows}
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3">Total</th>
                                    <th class="text-end" style="color:#7c3aed;">${rupiah(order.total)}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            `;
        } catch (error) {
            statusBadge.className = 'status-badge status-error';
            statusBadge.innerHTML = '❌ Pesanan tidak ditemukan';

            resultArea.style.display = 'block';
            resultArea.innerHTML = `
                <div class="order-result" style="border-color:#f87171;">
                    <div class="order-header" style="background:linear-gradient(135deg,#ef4444,#dc2626);">
                        <h5>❌ Pesanan Tidak Ditemukan</h5>
                        <div class="order-id">QR Code: ${esc(decodedText)}</div>
                    </div>
                    <div class="order-body text-center">
                        <p class="text-danger mb-0">Pesanan dengan ID ini tidak ditemukan di database.</p>
                    </div>
                </div>
            `;
        }

        scanAgainWrapper.style.display = 'block';
    }

    function onScanFailure(error) {
        // Silent — keep scanning
    }

    // ─── Helpers ────────────────────────────────
    function rupiah(n) {
        return 'Rp ' + Number(n).toLocaleString('id-ID');
    }
    function esc(v) {
        return String(v).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // ─── Start scanner on page load ─────────────
    document.addEventListener('DOMContentLoaded', startScanner);
</script>
@endpush
