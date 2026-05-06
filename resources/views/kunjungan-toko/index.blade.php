@extends('layouts.app')

@section('title', 'Kunjungan Toko — Geolocation')
@section('page-title', 'Kunjungan Toko')
@section('page-icon', 'mdi-map-marker-radius')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Kunjungan Toko</li>
@endsection

@push('styles')
<style>
    .section-title{font-weight:700;font-size:1.05rem;margin-bottom:14px;display:flex;align-items:center;gap:8px;}
    .section-title .icon-circle{width:32px;height:32px;background:linear-gradient(135deg,#a855f7,#7c3aed);color:#fff;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:.85rem;flex-shrink:0;}
    .glass-card{background:#fff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 1px 3px rgba(15,23,42,.04),0 4px 12px rgba(15,23,42,.06);padding:24px;margin-bottom:20px;}
    .tbl-toko{width:100%;border-collapse:separate;border-spacing:0;}
    .tbl-toko thead th{background:#f1f5f9;font-size:.78rem;text-transform:uppercase;letter-spacing:.04em;color:#64748b;font-weight:600;padding:10px 12px;border-bottom:2px solid #e2e8f0;}
    .tbl-toko tbody td{padding:10px 12px;font-size:.88rem;border-bottom:1px solid #f1f5f9;vertical-align:middle;}
    .btn-cetak{background:linear-gradient(135deg,#a855f7,#7c3aed);border:none;color:#fff;font-weight:600;font-size:.8rem;padding:6px 14px;border-radius:8px;}
    .btn-cetak:hover{background:linear-gradient(135deg,#9333ea,#6d28d9);color:#fff;}
    .geo-input{border:2px solid #e2e8f0;border-radius:8px;padding:10px 14px;font-size:.92rem;width:100%;transition:all .25s;}
    .geo-input:focus{border-color:#a855f7;box-shadow:0 0 0 3px rgba(168,85,247,.15);outline:none;}
    .btn-geo{background:linear-gradient(135deg,#38bdf8,#0ea5e9);border:none;color:#fff;font-weight:700;padding:12px 24px;border-radius:10px;font-size:.92rem;}
    .btn-geo:hover{background:linear-gradient(135deg,#0ea5e9,#0284c7);color:#fff;}
    .btn-submit{background:linear-gradient(135deg,#a855f7,#7c3aed);border:none;color:#fff;font-weight:700;padding:12px 24px;border-radius:10px;font-size:.92rem;}
    .btn-submit:hover{background:linear-gradient(135deg,#9333ea,#6d28d9);color:#fff;}
    #reader{width:100%;border-radius:12px;overflow:hidden;border:2px solid #e2e8f0;}
    .visit-result{border-radius:14px;padding:24px;animation:fadeInUp .4s ease;margin-top:16px;}
    .visit-accepted{background:linear-gradient(135deg,#ecfdf5,#d1fae5);border:2px solid #34d399;}
    .visit-rejected{background:linear-gradient(135deg,#fef2f2,#fee2e2);border:2px solid #f87171;}
    .visit-label{font-size:.78rem;text-transform:uppercase;letter-spacing:.05em;color:#64748b;font-weight:600;margin-bottom:2px;}
    .visit-value{font-size:.95rem;font-weight:700;color:#1e293b;margin-bottom:12px;}
    .status-badge{display:inline-flex;align-items:center;gap:6px;padding:8px 18px;border-radius:100px;font-size:.85rem;font-weight:600;}
    .status-scanning{background:linear-gradient(135deg,#ecfdf5,#d1fae5);border:1px solid #34d399;color:#047857;}
    @keyframes fadeInUp{from{opacity:0;transform:translateY(16px);}to{opacity:1;transform:translateY(0);}}
</style>
@endpush

@section('content')
{{-- 1. LIST TOKO --}}
<div class="glass-card">
    <div class="section-title"><div class="icon-circle">1</div> List Toko</div>
    <div class="table-responsive">
        <table class="tbl-toko">
            <thead><tr><th>Barcode</th><th>Nama Toko</th><th>Latitude</th><th>Longitude</th><th>Accuracy</th><th>Cetak</th></tr></thead>
            <tbody>
                @forelse($tokoList as $toko)
                <tr>
                    <td><code>{{ $toko->barcode }}</code></td>
                    <td>{{ $toko->nama_toko }}</td>
                    <td>{{ $toko->latitude }}</td>
                    <td>{{ $toko->longitude }}</td>
                    <td>{{ $toko->accuracy }} m</td>
                    <td><a href="{{ route('kunjungan-toko.cetak-barcode', $toko->barcode) }}" target="_blank" class="btn btn-cetak btn-sm"><i class="mdi mdi-barcode"></i> Cetak</a></td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data toko.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- 2. INPUT TITIK AWAL (Tambah Toko) --}}
<div class="glass-card">
    <div class="section-title"><div class="icon-circle">2</div> Input Titik Awal (Tambah Toko)</div>
    <form action="{{ route('kunjungan-toko.store') }}" method="POST">
        @csrf
        <div class="row g-3 mb-3">
            <div class="col-md-12"><input type="text" name="nama_toko" class="geo-input" placeholder="Nama Toko" required></div>
            <div class="col-md-4"><input type="text" name="latitude" id="inputLat" class="geo-input" placeholder="Latitude" required></div>
            <div class="col-md-4"><input type="text" name="longitude" id="inputLng" class="geo-input" placeholder="Longitude" required></div>
            <div class="col-md-4"><input type="text" name="accuracy" id="inputAcc" class="geo-input" placeholder="Accuracy (m)" required></div>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-geo" onclick="getLocation('inputLat','inputLng','inputAcc')"><i class="mdi mdi-crosshairs-gps"></i> Geoloc</button>
            <button type="submit" class="btn btn-submit"><i class="mdi mdi-content-save"></i> Submit</button>
        </div>
    </form>
</div>

{{-- 3. TITIK KUNJUNGAN --}}
<div class="glass-card">
    <div class="section-title"><div class="icon-circle">3</div> Titik Kunjungan</div>
    <div class="text-center mb-3">
        <span class="status-badge status-scanning" id="statusBadge">📷 Scan barcode toko...</span>
    </div>
    <div style="max-width:520px;margin:0 auto;">
        <div id="reader"></div>
        <div id="visitResult" class="mt-3" style="display:none;"></div>
        <div class="text-center mt-3" id="scanAgainWrap" style="display:none;">
            <button class="btn btn-submit" onclick="startVisitScanner()"><i class="mdi mdi-barcode-scan"></i> Scan Lagi</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ─── Beep ───────────────────────────────────
    function playBeep(freq=1000,dur=200){try{const c=new(window.AudioContext||window.webkitAudioContext)();const o=c.createOscillator();const g=c.createGain();o.connect(g);g.connect(c.destination);o.frequency.value=freq;o.type='sine';g.gain.value=.5;o.start();setTimeout(()=>{o.stop();c.close();},dur);}catch(e){}}

    // ─── Accurate Geolocation (Lampiran 1) ──────
    function getAccuratePosition(targetAccuracy=50,maxWait=20000){
        return new Promise((resolve,reject)=>{
            let bestResult=null;
            const startTime=Date.now();
            const watchId=navigator.geolocation.watchPosition(
                (position)=>{
                    const acc=position.coords.accuracy;
                    if(!bestResult||acc<bestResult.coords.accuracy){bestResult=position;}
                    if(acc<=targetAccuracy){navigator.geolocation.clearWatch(watchId);resolve(bestResult);}
                    if(Date.now()-startTime>=maxWait){navigator.geolocation.clearWatch(watchId);if(bestResult)resolve(bestResult);else reject(new Error("Timeout, tidak dapat posisi"));}
                },
                (error)=>reject(error),
                {enableHighAccuracy:true,maximumAge:0,timeout:maxWait}
            );
        });
    }

    // ─── Get Location for form ──────────────────
    async function getLocation(latId,lngId,accId){
        const btn=event.target.closest('button');
        btn.disabled=true;btn.innerHTML='<i class="mdi mdi-loading mdi-spin"></i> Mengambil lokasi...';
        try{
            const pos=await getAccuratePosition(50);
            document.getElementById(latId).value=pos.coords.latitude;
            document.getElementById(lngId).value=pos.coords.longitude;
            document.getElementById(accId).value=pos.coords.accuracy.toFixed(2);
        }catch(e){alert('Gagal ambil lokasi: '+e.message);}
        btn.disabled=false;btn.innerHTML='<i class="mdi mdi-crosshairs-gps"></i> Geoloc';
    }

    // ─── Visit Scanner ──────────────────────────
    let visitScanner=null;
    const statusBadge=document.getElementById('statusBadge');
    const visitResult=document.getElementById('visitResult');
    const scanAgainWrap=document.getElementById('scanAgainWrap');

    function startVisitScanner(){
        visitResult.style.display='none';
        scanAgainWrap.style.display='none';
        statusBadge.className='status-badge status-scanning';
        statusBadge.innerHTML='📷 Scan barcode toko...';
        if(visitScanner)visitScanner.clear();
        visitScanner=new Html5QrcodeScanner("reader",{fps:10,qrbox:{width:300,height:100},supportedScanTypes:[Html5QrcodeScanType.SCAN_TYPE_CAMERA],rememberLastUsedCamera:true});
        visitScanner.render(onVisitScanSuccess,()=>{});
    }

    async function onVisitScanSuccess(decodedText){
        playBeep(1000,200);
        await visitScanner.clear();
        statusBadge.innerHTML='📍 Mengambil posisi GPS sales...';

        try{
            // 1. Ambil posisi sales
            const pos=await getAccuratePosition(50);
            const salesLat=pos.coords.latitude;
            const salesLng=pos.coords.longitude;
            const salesAcc=pos.coords.accuracy;

            statusBadge.innerHTML='🔍 Memverifikasi kunjungan...';

            // 2. Kirim ke backend
            const resp=await axios.get('{{ route("kunjungan-toko.verify-visit") }}',{params:{barcode:decodedText,sales_lat:salesLat,sales_lng:salesLng,sales_accuracy:salesAcc}});
            const d=resp.data.data;
            const accepted=d.diterima;

            statusBadge.className='status-badge '+(accepted?'status-scanning':'');
            statusBadge.innerHTML=accepted?'✅ Kunjungan DITERIMA':'❌ Kunjungan DITOLAK';
            statusBadge.style.background=accepted?'':'linear-gradient(135deg,#fef2f2,#fee2e2)';
            statusBadge.style.borderColor=accepted?'':'#f87171';
            statusBadge.style.color=accepted?'':'#dc2626';

            visitResult.style.display='block';
            visitResult.innerHTML=`
                <div class="visit-result ${accepted?'visit-accepted':'visit-rejected'}">
                    <h5 style="font-weight:800;margin-bottom:16px;color:${accepted?'#047857':'#dc2626'}">${accepted?'✅ DITERIMA':'❌ DITOLAK'}</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="visit-label">Data Toko (dari DB)</div>
                            <div class="visit-value">🏪 ${esc(d.toko.nama_toko)}</div>
                            <div class="visit-label">Barcode</div>
                            <div class="visit-value">${esc(d.toko.barcode)}</div>
                            <div class="visit-label">Latitude Toko</div>
                            <div class="visit-value">${d.toko.latitude}</div>
                            <div class="visit-label">Longitude Toko</div>
                            <div class="visit-value">${d.toko.longitude}</div>
                            <div class="visit-label">Accuracy Toko</div>
                            <div class="visit-value">${d.accuracy_toko} m</div>
                        </div>
                        <div class="col-md-6">
                            <div class="visit-label">Data Titik Kunjungan (Sales)</div>
                            <div class="visit-value">📍 Posisi GPS</div>
                            <div class="visit-label">Latitude Sales</div>
                            <div class="visit-value">${d.sales_lat}</div>
                            <div class="visit-label">Longitude Sales</div>
                            <div class="visit-value">${d.sales_lng}</div>
                            <div class="visit-label">Accuracy Sales</div>
                            <div class="visit-value">${d.accuracy_sales.toFixed(2)} m</div>
                        </div>
                    </div>
                    <hr>
                    <div class="visit-label">Jarak Aktual</div>
                    <div class="visit-value">${d.jarak_aktual} m</div>
                    <div class="visit-label">Jarak Max</div>
                    <div class="visit-value">${d.jarak_max} m</div>
                    <div class="visit-label">Threshold Efektif (max + acc_toko + acc_sales)</div>
                    <div class="visit-value">${d.threshold_efektif} m</div>
                    <div class="visit-label">Hasil</div>
                    <div class="visit-value" style="font-size:1.1rem;color:${accepted?'#047857':'#dc2626'}">
                        ${d.jarak_aktual} m ${accepted?'≤':'>'} ${d.threshold_efektif} m → ${accepted?'DITERIMA ✓':'DITOLAK ✗'}
                    </div>
                </div>`;
        }catch(e){
            statusBadge.className='status-badge';
            statusBadge.style.background='linear-gradient(135deg,#fef2f2,#fee2e2)';
            statusBadge.style.borderColor='#f87171';
            statusBadge.style.color='#dc2626';
            statusBadge.innerHTML='❌ Error';
            visitResult.style.display='block';
            visitResult.innerHTML=`<div class="visit-result visit-rejected"><p class="text-danger mb-0">${esc(e?.response?.data?.message||e.message||'Terjadi kesalahan')}</p></div>`;
        }
        scanAgainWrap.style.display='block';
    }

    function esc(v){return String(v).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');}

    document.addEventListener('DOMContentLoaded',startVisitScanner);
</script>
@endpush
