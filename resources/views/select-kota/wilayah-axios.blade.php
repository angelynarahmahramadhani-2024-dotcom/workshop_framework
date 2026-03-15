@extends('layouts.app')

@section('title', 'Wilayah Indonesia - Axios')

@section('page-title', 'Cascading Select Wilayah')
@section('page-icon', 'mdi-map-marker')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Wilayah (Axios)</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .select-group select {
        background-color: #3d84f7;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 10px 14px;
        font-size: 15px;
        width: 100%;
        appearance: none;
        -webkit-appearance: none;
        cursor: pointer;
    }
    .select-group select option {
        background-color: #fff;
        color: #333;
    }
    .select-group select:disabled {
        background-color: #8aaef7;
        cursor: not-allowed;
    }
    .select-wrapper {
        position: relative;
    }
    .select-wrapper::after {
        content: '\25BC';
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #fff;
        pointer-events: none;
        font-size: 12px;
    }
    .spinner-overlay {
        display: none;
        position: absolute;
        right: 36px;
        top: 50%;
        transform: translateY(-50%);
    }
    .badge-version {
        background-color: #6f42c1;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Pilih Wilayah Indonesia</h4>
                    <span class="badge-version">Axios</span>
                </div>
                <p class="card-description mb-4">
                    Pemilihan provinsi menentukan pilihan kota, kota menentukan kecamatan, dan kecamatan menentukan kelurahan. <em>(Versi Axios)</em>
                </p>

                <!-- Provinsi -->
                <div class="form-group mb-3 select-group">
                    <label class="form-label fw-semibold">Provinsi :</label>
                    <div class="select-wrapper">
                        <select id="selectProvinsi">
                            <option value="0">Pilih Provinsi</option>
                            @foreach ($provinsi as $p)
                                <option value="{{ $p->kode }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                        <div class="spinner-overlay" id="spinnerKota">
                            <div class="spinner-border spinner-border-sm text-light" role="status"></div>
                        </div>
                    </div>
                </div>

                <!-- Kota -->
                <div class="form-group mb-3 select-group">
                    <label class="form-label fw-semibold">Kota :</label>
                    <div class="select-wrapper">
                        <select id="selectKota" disabled>
                            <option value="0">Pilih Kota</option>
                        </select>
                        <div class="spinner-overlay" id="spinnerKecamatan">
                            <div class="spinner-border spinner-border-sm text-light" role="status"></div>
                        </div>
                    </div>
                </div>

                <!-- Kecamatan -->
                <div class="form-group mb-3 select-group">
                    <label class="form-label fw-semibold">Kecamatan :</label>
                    <div class="select-wrapper">
                        <select id="selectKecamatan" disabled>
                            <option value="0">Pilih Kecamatan</option>
                        </select>
                        <div class="spinner-overlay" id="spinnerKelurahan">
                            <div class="spinner-border spinner-border-sm text-light" role="status"></div>
                        </div>
                    </div>
                </div>

                <!-- Kelurahan -->
                <div class="form-group mb-4 select-group">
                    <label class="form-label fw-semibold">Kelurahan :</label>
                    <div class="select-wrapper">
                        <select id="selectKelurahan" disabled>
                            <option value="0">Pilih Kelurahan</option>
                        </select>
                    </div>
                </div>

                <!-- Hasil -->
                <div id="hasilWilayah" class="alert alert-info d-none">
                    <strong>Wilayah Terpilih:</strong>
                    <div class="mt-1" id="hasilText"></div>
                </div>

                <!-- Link ke versi jQuery -->
                <div class="text-end mt-3">
                    <a href="{{ route('wilayah.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="mdi mdi-swap-horizontal"></i> Lihat Versi jQuery
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const selectProvinsi  = document.getElementById('selectProvinsi');
    const selectKota      = document.getElementById('selectKota');
    const selectKecamatan = document.getElementById('selectKecamatan');
    const selectKelurahan = document.getElementById('selectKelurahan');
    const spinnerKota      = document.getElementById('spinnerKota');
    const spinnerKecamatan = document.getElementById('spinnerKecamatan');
    const spinnerKelurahan = document.getElementById('spinnerKelurahan');
    const hasilWilayah    = document.getElementById('hasilWilayah');
    const hasilText       = document.getElementById('hasilText');

    // ─── Provinsi berubah → fetch Kota ───────────────────────────────────────
    selectProvinsi.addEventListener('change', function () {
        const kodeProvinsi = this.value;

        resetSelect(selectKota, 'Pilih Kota');
        resetSelect(selectKecamatan, 'Pilih Kecamatan');
        resetSelect(selectKelurahan, 'Pilih Kelurahan');
        hasilWilayah.classList.add('d-none');

        if (kodeProvinsi == 0) return;

        spinnerKota.style.display = 'block';

        axios.get('{{ route("wilayah.get-kota") }}', {
            params: { kode_provinsi: kodeProvinsi }
        })
        .then(function (response) {
            if (response.data.status === 'success') {
                populateSelect(selectKota, response.data.data, 'Pilih Kota');
                selectKota.disabled = false;
            }
        })
        .catch(function () {
            Swal.fire('Error!', 'Gagal mengambil data kota.', 'error');
        })
        .finally(function () {
            spinnerKota.style.display = 'none';
        });
    });

    // ─── Kota berubah → fetch Kecamatan ──────────────────────────────────────
    selectKota.addEventListener('change', function () {
        const kodeKota = this.value;

        resetSelect(selectKecamatan, 'Pilih Kecamatan');
        resetSelect(selectKelurahan, 'Pilih Kelurahan');
        hasilWilayah.classList.add('d-none');

        if (kodeKota == 0) return;

        spinnerKecamatan.style.display = 'block';

        axios.get('{{ route("wilayah.get-kecamatan") }}', {
            params: { kode_kota: kodeKota }
        })
        .then(function (response) {
            if (response.data.status === 'success') {
                populateSelect(selectKecamatan, response.data.data, 'Pilih Kecamatan');
                selectKecamatan.disabled = false;
            }
        })
        .catch(function () {
            Swal.fire('Error!', 'Gagal mengambil data kecamatan.', 'error');
        })
        .finally(function () {
            spinnerKecamatan.style.display = 'none';
        });
    });

    // ─── Kecamatan berubah → fetch Kelurahan ─────────────────────────────────
    selectKecamatan.addEventListener('change', function () {
        const kodeKecamatan = this.value;

        resetSelect(selectKelurahan, 'Pilih Kelurahan');
        hasilWilayah.classList.add('d-none');

        if (kodeKecamatan == 0) return;

        spinnerKelurahan.style.display = 'block';

        axios.get('{{ route("wilayah.get-kelurahan") }}', {
            params: { kode_kecamatan: kodeKecamatan }
        })
        .then(function (response) {
            if (response.data.status === 'success') {
                populateSelect(selectKelurahan, response.data.data, 'Pilih Kelurahan');
                selectKelurahan.disabled = false;
            }
        })
        .catch(function () {
            Swal.fire('Error!', 'Gagal mengambil data kelurahan.', 'error');
        })
        .finally(function () {
            spinnerKelurahan.style.display = 'none';
        });
    });

    // ─── Kelurahan berubah → tampilkan hasil ─────────────────────────────────
    selectKelurahan.addEventListener('change', function () {
        if (this.value == 0) {
            hasilWilayah.classList.add('d-none');
            return;
        }
        tampilkanHasil();
    });

    // ─── Helpers ─────────────────────────────────────────────────────────────
    function resetSelect(el, placeholder) {
        el.innerHTML = '<option value="0">' + placeholder + '</option>';
        el.disabled = true;
    }

    function populateSelect(el, data, placeholder) {
        let html = '<option value="0">' + placeholder + '</option>';
        data.forEach(function (item) {
            html += '<option value="' + item.kode + '">' + item.nama + '</option>';
        });
        el.innerHTML = html;
    }

    function tampilkanHasil() {
        const provinsi  = selectProvinsi.options[selectProvinsi.selectedIndex].text;
        const kota      = selectKota.options[selectKota.selectedIndex].text;
        const kecamatan = selectKecamatan.options[selectKecamatan.selectedIndex].text;
        const kelurahan = selectKelurahan.options[selectKelurahan.selectedIndex].text;

        hasilText.innerHTML = provinsi + ' &rsaquo; ' + kota + ' &rsaquo; ' + kecamatan + ' &rsaquo; <strong>' + kelurahan + '</strong>';
        hasilWilayah.classList.remove('d-none');
    }
});
</script>
@endpush
