@extends('layouts.app')

@section('title', 'Tambah Customer 1 (BLOB)')

@section('page-title', 'Tambah Customer 1')
@section('page-icon', 'mdi-camera')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('customer.index') }}">Data Customer</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah Customer 1 (BLOB)</li>
@endsection

@push('styles')
<style>
    .foto-preview-box {
        width: 220px;
        height: 180px;
        border: 2px dashed #a3d9a5;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #f9fff9;
        margin-bottom: 12px;
    }
    .foto-preview-box img {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
    }
    .foto-preview-box .placeholder-text {
        color: #adb5bd;
        font-size: 0.95rem;
    }
    .video-container {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        justify-content: center;
    }
    .video-box, .snapshot-box {
        width: 300px;
        height: 220px;
        border: 2px solid #dee2e6;
        border-radius: 4px;
        overflow: hidden;
        background: #eee;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .video-box video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .snapshot-box canvas {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .camera-controls {
        display: flex;
        gap: 10px;
        justify-content: center;
        align-items: center;
        margin-top: 16px;
        flex-wrap: wrap;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Customer 1</h4>
                <p class="card-description">Foto customer diambil dari kamera dan disimpan sebagai <code>blob data</code> dalam database</p>

                <form action="{{ route('customer.store1') }}" method="POST" id="customerForm1">
                    @csrf

                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                               id="nama" name="nama" value="{{ old('nama') }}" 
                               placeholder="Masukkan nama customer" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" class="form-control @error('alamat') is-invalid @enderror" 
                               id="alamat" name="alamat" value="{{ old('alamat') }}" 
                               placeholder="Masukkan alamat">
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="kode_provinsi">Provinsi</label>
                        <select class="form-control @error('kode_provinsi') is-invalid @enderror" id="kode_provinsi" name="kode_provinsi">
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach($provinsi as $prov)
                                <option value="{{ $prov->kode }}">{{ $prov->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="kode_kota">Kota</label>
                        <select class="form-control" id="kode_kota" name="kode_kota" disabled>
                            <option value="">-- Pilih Kota --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="kode_kecamatan">Kecamatan</label>
                        <select class="form-control" id="kode_kecamatan" name="kode_kecamatan" disabled>
                            <option value="">-- Pilih Kecamatan --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="kode_kelurahan">Kodepos - Kelurahan</label>
                        <select class="form-control" id="kode_kelurahan" name="kode_kelurahan" disabled>
                            <option value="">-- Pilih Kelurahan --</option>
                        </select>
                    </div>

                    {{-- Foto Preview --}}
                    <div class="form-group">
                        <label>Foto</label>
                        <div class="foto-preview-box" id="fotoPreviewBox">
                            <span class="placeholder-text" id="fotoPlaceholder">Foto</span>
                            <img id="fotoPreview" src="" alt="Preview" style="display:none;">
                        </div>
                        <input type="hidden" name="foto_blob" id="fotoBlobInput">
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#cameraModal">
                            <i class="mdi mdi-camera"></i> Ambil Foto
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Ambil Foto --}}
<div class="modal fade" id="cameraModal" tabindex="-1" aria-labelledby="cameraModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cameraModalLabel">Modal ambil Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="video-container">
                    <div class="text-center">
                        <p class="mb-2 fw-bold">Video</p>
                        <div class="video-box">
                            <video id="cameraVideo" autoplay playsinline></video>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="mb-2 fw-bold">Snapshot</p>
                        <div class="snapshot-box">
                            <canvas id="snapshotCanvas" width="300" height="220"></canvas>
                        </div>
                    </div>
                </div>

                <div class="camera-controls">
                    <select class="form-control" id="cameraSelect" style="width:auto;min-width:180px;">
                        <option value="">Pilihan kamera</option>
                    </select>
                    <button type="button" class="btn btn-info" id="btnCapture">
                        <i class="mdi mdi-camera"></i> Ambil Foto
                    </button>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary" id="btnSaveFoto">
                    <i class="mdi mdi-content-save"></i> Simpan Foto
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    const video = document.getElementById('cameraVideo');
    const canvas = document.getElementById('snapshotCanvas');
    const ctx = canvas.getContext('2d');
    const cameraSelect = document.getElementById('cameraSelect');
    const btnCapture = document.getElementById('btnCapture');
    const btnSaveFoto = document.getElementById('btnSaveFoto');
    const fotoPreview = document.getElementById('fotoPreview');
    const fotoPlaceholder = document.getElementById('fotoPlaceholder');
    const fotoBlobInput = document.getElementById('fotoBlobInput');
    const cameraModal = document.getElementById('cameraModal');

    let currentStream = null;
    let capturedData = null;

    async function getCameras() {
        try {
            await navigator.mediaDevices.getUserMedia({ video: true });
            const devices = await navigator.mediaDevices.enumerateDevices();
            const videoDevices = devices.filter(d => d.kind === 'videoinput');

            cameraSelect.innerHTML = '';
            videoDevices.forEach((device, index) => {
                const option = document.createElement('option');
                option.value = device.deviceId;
                option.textContent = device.label || `Kamera ${index + 1}`;
                cameraSelect.appendChild(option);
            });

            if (videoDevices.length > 0) {
                startCamera(videoDevices[0].deviceId);
            }
        } catch (err) {
            console.error('Error accessing cameras:', err);
            alert('Tidak dapat mengakses kamera. Pastikan izin kamera sudah diberikan.');
        }
    }

    async function startCamera(deviceId) {
        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
        }
        try {
            const constraints = { video: deviceId ? { deviceId: { exact: deviceId } } : true };
            currentStream = await navigator.mediaDevices.getUserMedia(constraints);
            video.srcObject = currentStream;
        } catch (err) {
            console.error('Error starting camera:', err);
        }
    }

    cameraSelect.addEventListener('change', function() {
        if (this.value) startCamera(this.value);
    });

    btnCapture.addEventListener('click', function() {
        canvas.width = video.videoWidth || 300;
        canvas.height = video.videoHeight || 220;
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        capturedData = canvas.toDataURL('image/png');
    });

    btnSaveFoto.addEventListener('click', function() {
        if (!capturedData) {
            alert('Ambil foto terlebih dahulu!');
            return;
        }
        fotoBlobInput.value = capturedData;
        fotoPreview.src = capturedData;
        fotoPreview.style.display = 'block';
        fotoPlaceholder.style.display = 'none';
        bootstrap.Modal.getInstance(cameraModal).hide();
    });

    cameraModal.addEventListener('shown.bs.modal', function() { getCameras(); });
    cameraModal.addEventListener('hidden.bs.modal', function() {
        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
            currentStream = null;
        }
    });

    // Cascading select wilayah
    const selProvinsi = document.getElementById('kode_provinsi');
    const selKota = document.getElementById('kode_kota');
    const selKecamatan = document.getElementById('kode_kecamatan');
    const selKelurahan = document.getElementById('kode_kelurahan');

    selProvinsi.addEventListener('change', function() {
        selKota.innerHTML = '<option value="">-- Pilih Kota --</option>';
        selKecamatan.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
        selKelurahan.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
        selKota.disabled = selKecamatan.disabled = selKelurahan.disabled = true;
        if (this.value) {
            fetch(`{{ route('wilayah.get-kota') }}?kode_provinsi=${this.value}`)
                .then(r => r.json())
                .then(res => {
                    (res.data || res).forEach(item => {
                        selKota.innerHTML += `<option value="${item.kode}">${item.nama}</option>`;
                    });
                    selKota.disabled = false;
                });
        }
    });

    selKota.addEventListener('change', function() {
        selKecamatan.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
        selKelurahan.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
        selKecamatan.disabled = selKelurahan.disabled = true;
        if (this.value) {
            fetch(`{{ route('wilayah.get-kecamatan') }}?kode_kota=${this.value}`)
                .then(r => r.json())
                .then(res => {
                    (res.data || res).forEach(item => {
                        selKecamatan.innerHTML += `<option value="${item.kode}">${item.nama}</option>`;
                    });
                    selKecamatan.disabled = false;
                });
        }
    });

    selKecamatan.addEventListener('change', function() {
        selKelurahan.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
        selKelurahan.disabled = true;
        if (this.value) {
            fetch(`{{ route('wilayah.get-kelurahan') }}?kode_kecamatan=${this.value}`)
                .then(r => r.json())
                .then(res => {
                    (res.data || res).forEach(item => {
                        selKelurahan.innerHTML += `<option value="${item.kode}">${item.nama}</option>`;
                    });
                    selKelurahan.disabled = false;
                });
        }
    });
})();
</script>
@endpush
