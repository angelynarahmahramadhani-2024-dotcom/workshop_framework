@extends('layouts.app')

@section('title', 'Barcode Scanner — Barang')

@section('page-title', 'Barcode Scanner')
@section('page-icon', 'mdi-barcode-scan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('barang.index') }}">Barang</a></li>
    <li class="breadcrumb-item active" aria-current="page">Barcode Scanner</li>
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
    #reader video {
        border-radius: 12px;
    }

    /* Result Card */
    .result-card {
        background: linear-gradient(135deg, #f5f3ff, #ede9fe);
        border: 2px solid #c4b5fd;
        border-radius: 14px;
        padding: 28px;
        text-align: center;
        animation: fadeInUp 0.4s ease;
    }
    .result-card .result-icon {
        font-size: 2.5rem;
        margin-bottom: 12px;
    }
    .result-card .result-label {
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #7c3aed;
        font-weight: 600;
        margin-bottom: 4px;
    }
    .result-card .result-value {
        font-size: 1.15rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 16px;
    }
    .result-card .result-value.price {
        color: #7c3aed;
        font-size: 1.3rem;
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
        box-shadow: 0 6px 20px rgba(124,58,237,0.3);
        color: #fff;
    }

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
                    <i class="mdi mdi-barcode-scan"></i> Barcode Scanner — Barang
                </h4>
                <p class="card-description">
                    Arahkan kamera ke barcode label barang untuk melihat detail barang.
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
                            <i class="mdi mdi-barcode-scan"></i> Scan Lagi
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
    // ─── Beep menggunakan Web Audio API ─────────
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
            setTimeout(() => {
                oscillator.stop();
                ctx.close();
            }, duration);
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
            qrbox: { width: 300, height: 100 },
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
        statusBadge.innerHTML = '🔍 Mencari barang...';

        // 3. AJAX cari barang
        try {
            const response = await axios.get('{{ route("barang.find-by-barcode") }}', {
                params: { barcode: decodedText }
            });

            const barang = response.data.data;

            statusBadge.className = 'status-badge status-found';
            statusBadge.innerHTML = '✅ Barang ditemukan!';

            resultArea.style.display = 'block';
            resultArea.innerHTML = `
                <div class="result-card">
                    <div class="result-icon">📦</div>
                    <div class="result-label">ID Barang</div>
                    <div class="result-value">${esc(barang.id_barang)}</div>
                    <div class="result-label">Nama Barang</div>
                    <div class="result-value">${esc(barang.nama)}</div>
                    <div class="result-label">Harga</div>
                    <div class="result-value price">${rupiah(barang.harga)}</div>
                </div>
            `;
        } catch (error) {
            statusBadge.className = 'status-badge status-error';
            statusBadge.innerHTML = '❌ Barang tidak ditemukan';

            resultArea.style.display = 'block';
            resultArea.innerHTML = `
                <div class="result-card" style="border-color:#f87171;background:linear-gradient(135deg,#fef2f2,#fee2e2);">
                    <div class="result-icon">❌</div>
                    <div class="result-label" style="color:#dc2626;">Barcode</div>
                    <div class="result-value">${esc(decodedText)}</div>
                    <p class="text-danger mb-0">Barang dengan barcode ini tidak ditemukan di database.</p>
                </div>
            `;
        }

        scanAgainWrapper.style.display = 'block';
    }

    function onScanFailure(error) {
        // Silence — scanner terus berjalan sampai berhasil
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
