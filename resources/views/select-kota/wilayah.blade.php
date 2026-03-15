@extends('layouts.app')

@section('title', 'Wilayah Indonesia - AJAX dan Axios')

@section('page-title', 'Cascading Select Wilayah')
@section('page-icon', 'mdi-map-marker')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Wilayah (AJAX & Axios)</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .select-wrapper {
        position: relative;
    }
    .select-wrapper .form-select {
        padding-right: 2.4rem;
    }
    .spinner-overlay {
        display: none;
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 3;
    }
    .wilayah-panel {
        min-height: 100%;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12 col-xl-6 mb-4">
        <div class="card wilayah-panel">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Pilih Wilayah Indonesia</h4>
                    <span class="badge badge-gradient-info">jQuery AJAX</span>
                </div>
                <p class="card-description mb-4">
                    Pemilihan provinsi menentukan pilihan kota, kota menentukan kecamatan, dan kecamatan menentukan kelurahan.
                </p>

                <!-- Provinsi -->
                <div class="form-group mb-3 select-group">
                    <label class="form-label fw-semibold">Provinsi :</label>
                    <div class="select-wrapper">
                        <select id="selectProvinsiJq" class="form-select">
                            <option value="0">Pilih Provinsi</option>
                            @foreach ($provinsi as $p)
                                <option value="{{ $p->kode }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                        <div class="spinner-overlay" id="spinnerKotaJq">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        </div>
                    </div>
                </div>

                <!-- Kota -->
                <div class="form-group mb-3 select-group">
                    <label class="form-label fw-semibold">Kota :</label>
                    <div class="select-wrapper">
                        <select id="selectKotaJq" class="form-select" disabled>
                            <option value="0">Pilih Kota</option>
                        </select>
                        <div class="spinner-overlay" id="spinnerKecamatanJq">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        </div>
                    </div>
                </div>

                <!-- Kecamatan -->
                <div class="form-group mb-3 select-group">
                    <label class="form-label fw-semibold">Kecamatan :</label>
                    <div class="select-wrapper">
                        <select id="selectKecamatanJq" class="form-select" disabled>
                            <option value="0">Pilih Kecamatan</option>
                        </select>
                        <div class="spinner-overlay" id="spinnerKelurahanJq">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        </div>
                    </div>
                </div>

                <!-- Kelurahan -->
                <div class="form-group mb-4 select-group">
                    <label class="form-label fw-semibold">Kelurahan :</label>
                    <div class="select-wrapper">
                        <select id="selectKelurahanJq" class="form-select" disabled>
                            <option value="0">Pilih Kelurahan</option>
                        </select>
                    </div>
                </div>

                <!-- Hasil -->
                <div id="hasilWilayahJq" class="alert alert-info d-none">
                    <strong>Wilayah Terpilih:</strong>
                    <div class="mt-1" id="hasilTextJq"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-6 mb-4">
        <div class="card wilayah-panel">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Pilih Wilayah Indonesia</h4>
                    <span class="badge badge-gradient-primary">Axios</span>
                </div>
                <p class="card-description mb-4">
                    Pemilihan provinsi menentukan pilihan kota, kota menentukan kecamatan, dan kecamatan menentukan kelurahan.
                </p>

                <div class="form-group mb-3 select-group">
                    <label class="form-label fw-semibold">Provinsi :</label>
                    <div class="select-wrapper">
                        <select id="selectProvinsiAx" class="form-select">
                            <option value="0">Pilih Provinsi</option>
                            @foreach ($provinsi as $p)
                                <option value="{{ $p->kode }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                        <div class="spinner-overlay" id="spinnerKotaAx">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3 select-group">
                    <label class="form-label fw-semibold">Kota :</label>
                    <div class="select-wrapper">
                        <select id="selectKotaAx" class="form-select" disabled>
                            <option value="0">Pilih Kota</option>
                        </select>
                        <div class="spinner-overlay" id="spinnerKecamatanAx">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3 select-group">
                    <label class="form-label fw-semibold">Kecamatan :</label>
                    <div class="select-wrapper">
                        <select id="selectKecamatanAx" class="form-select" disabled>
                            <option value="0">Pilih Kecamatan</option>
                        </select>
                        <div class="spinner-overlay" id="spinnerKelurahanAx">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4 select-group">
                    <label class="form-label fw-semibold">Kelurahan :</label>
                    <div class="select-wrapper">
                        <select id="selectKelurahanAx" class="form-select" disabled>
                            <option value="0">Pilih Kelurahan</option>
                        </select>
                    </div>
                </div>

                <div id="hasilWilayahAx" class="alert alert-info d-none">
                    <strong>Wilayah Terpilih:</strong>
                    <div class="mt-1" id="hasilTextAx"></div>
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
$(document).ready(function () {

    // ─── Provinsi berubah → fetch Kota ───────────────────────────────────────
    $('#selectProvinsiJq').on('change', function () {
        var kodeProvinsi = $(this).val();

        // Reset kota, kecamatan, kelurahan
        resetSelect('#selectKotaJq', 'Pilih Kota');
        resetSelect('#selectKecamatanJq', 'Pilih Kecamatan');
        resetSelect('#selectKelurahanJq', 'Pilih Kelurahan');
        $('#hasilWilayahJq').addClass('d-none');

        if (kodeProvinsi == 0) return;

        $('#spinnerKotaJq').show();

        $.ajax({
            method: 'GET',
            url: '{{ route("wilayah.get-kota") }}',
            data: { kode_provinsi: kodeProvinsi },
            success: function (response) {
                if (response.status === 'success') {
                    populateSelect('#selectKotaJq', response.data, 'Pilih Kota');
                    $('#selectKotaJq').prop('disabled', false);
                }
            },
            error: function (xhr) {
                Swal.fire('Error!', 'Gagal mengambil data kota.', 'error');
            },
            complete: function () {
                $('#spinnerKotaJq').hide();
            }
        });
    });

    // ─── Kota berubah → fetch Kecamatan ──────────────────────────────────────
    $('#selectKotaJq').on('change', function () {
        var kodeKota = $(this).val();

        resetSelect('#selectKecamatanJq', 'Pilih Kecamatan');
        resetSelect('#selectKelurahanJq', 'Pilih Kelurahan');
        $('#hasilWilayahJq').addClass('d-none');

        if (kodeKota == 0) return;

        $('#spinnerKecamatanJq').show();

        $.ajax({
            method: 'GET',
            url: '{{ route("wilayah.get-kecamatan") }}',
            data: { kode_kota: kodeKota },
            success: function (response) {
                if (response.status === 'success') {
                    populateSelect('#selectKecamatanJq', response.data, 'Pilih Kecamatan');
                    $('#selectKecamatanJq').prop('disabled', false);
                }
            },
            error: function () {
                Swal.fire('Error!', 'Gagal mengambil data kecamatan.', 'error');
            },
            complete: function () {
                $('#spinnerKecamatanJq').hide();
            }
        });
    });

    // ─── Kecamatan berubah → fetch Kelurahan ─────────────────────────────────
    $('#selectKecamatanJq').on('change', function () {
        var kodeKecamatan = $(this).val();

        resetSelect('#selectKelurahanJq', 'Pilih Kelurahan');
        $('#hasilWilayahJq').addClass('d-none');

        if (kodeKecamatan == 0) return;

        $('#spinnerKelurahanJq').show();

        $.ajax({
            method: 'GET',
            url: '{{ route("wilayah.get-kelurahan") }}',
            data: { kode_kecamatan: kodeKecamatan },
            success: function (response) {
                if (response.status === 'success') {
                    populateSelect('#selectKelurahanJq', response.data, 'Pilih Kelurahan');
                    $('#selectKelurahanJq').prop('disabled', false);
                }
            },
            error: function () {
                Swal.fire('Error!', 'Gagal mengambil data kelurahan.', 'error');
            },
            complete: function () {
                $('#spinnerKelurahanJq').hide();
            }
        });
    });

    // ─── Kelurahan berubah → tampilkan hasil ─────────────────────────────────
    $('#selectKelurahanJq').on('change', function () {
        if ($(this).val() == 0) {
            $('#hasilWilayahJq').addClass('d-none');
            return;
        }
        tampilkanHasil();
    });

    // ─── Helpers ─────────────────────────────────────────────────────────────
    function resetSelect(selector, placeholder) {
        $(selector)
            .html('<option value="0">' + placeholder + '</option>')
            .prop('disabled', true);
    }

    function populateSelect(selector, data, placeholder) {
        var html = '<option value="0">' + placeholder + '</option>';
        $.each(data, function (i, item) {
            html += '<option value="' + item.kode + '">' + item.nama + '</option>';
        });
        $(selector).html(html);
    }

    function tampilkanHasil() {
        var provinsi    = $('#selectProvinsiJq option:selected').text();
        var kota        = $('#selectKotaJq option:selected').text();
        var kecamatan   = $('#selectKecamatanJq option:selected').text();
        var kelurahan   = $('#selectKelurahanJq option:selected').text();

        $('#hasilTextJq').html(
            provinsi + ' &rsaquo; ' + kota + ' &rsaquo; ' + kecamatan + ' &rsaquo; <strong>' + kelurahan + '</strong>'
        );
        $('#hasilWilayahJq').removeClass('d-none');
    }
});

document.addEventListener('DOMContentLoaded', function () {

    const selectProvinsi  = document.getElementById('selectProvinsiAx');
    const selectKota      = document.getElementById('selectKotaAx');
    const selectKecamatan = document.getElementById('selectKecamatanAx');
    const selectKelurahan = document.getElementById('selectKelurahanAx');
    const spinnerKota      = document.getElementById('spinnerKotaAx');
    const spinnerKecamatan = document.getElementById('spinnerKecamatanAx');
    const spinnerKelurahan = document.getElementById('spinnerKelurahanAx');
    const hasilWilayah    = document.getElementById('hasilWilayahAx');
    const hasilText       = document.getElementById('hasilTextAx');

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

    selectKelurahan.addEventListener('change', function () {
        if (this.value == 0) {
            hasilWilayah.classList.add('d-none');
            return;
        }
        tampilkanHasil();
    });

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
