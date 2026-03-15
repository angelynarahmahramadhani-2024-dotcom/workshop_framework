@extends('layouts.app')

@section('title', 'Point of Sales - Axios')

@section('page-title', 'Point of Sales (Kasir)')
@section('page-icon', 'mdi-cart')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">POS (Axios)</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .form-control[readonly] {
        background-color: #f8f9fc;
    }
    .row-total td {
        font-weight: bold;
    }
    #totalHarga {
        font-size: 1.2rem;
        font-weight: bold;
        color: #198754;
    }
    .btn-hapus-row {
        padding: 2px 8px;
        font-size: 12px;
    }
    .kasir-panel {
        min-height: 100%;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card kasir-panel">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Halaman Kasir</h4>
                    <span class="badge badge-gradient-primary">Axios</span>
                </div>

                <!-- Form Input Barang -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label>Kode barang :</label>
                            <input type="text" class="form-control" id="inputKodeBarang"
                                placeholder="Ketik kode barang lalu tekan Enter">
                        </div>
                        <div class="form-group mb-2">
                            <label>Nama barang :</label>
                            <input type="text" class="form-control" id="inputNamaBarang" readonly
                                placeholder="Otomatis terisi">
                        </div>
                        <div class="form-group mb-2">
                            <label>Harga barang :</label>
                            <input type="text" class="form-control" id="inputHargaBarang" readonly
                                placeholder="Otomatis terisi">
                        </div>
                        <div class="form-group mb-3">
                            <label>Jumlah :</label>
                            <input type="number" class="form-control" id="inputJumlah"
                                value="1" min="1">
                        </div>
                        <div class="text-end">
                            <button class="btn btn-success" id="btnTambahkan" disabled>
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                <span class="btn-tambah-text">Tambahkan</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabel Transaksi -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="tabelTransaksi">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="bodyTransaksi">
                            <tr id="emptyRow">
                                <td colspan="6" class="text-center text-muted">Belum ada item</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="row-total">
                                <td colspan="4" class="text-end fw-bold">Total</td>
                                <td colspan="2">
                                    <span id="totalHarga">Rp 0</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="text-end mt-3">
                    <button class="btn btn-success btn-lg" id="btnBayar" disabled>
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="btn-bayar-text">Bayar</span>
                    </button>
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

    var cartItems = [];
    var barangDitemukan = false;

    const inputKode    = document.getElementById('inputKodeBarang');
    const inputNama    = document.getElementById('inputNamaBarang');
    const inputHarga   = document.getElementById('inputHargaBarang');
    const inputJumlah  = document.getElementById('inputJumlah');
    const btnTambahkan = document.getElementById('btnTambahkan');
    const btnBayar     = document.getElementById('btnBayar');
    const bodyTransaksi = document.getElementById('bodyTransaksi');
    const totalHarga   = document.getElementById('totalHarga');

    // Setup Axios default headers (CSRF)
    axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';

    // ─── Enter di kode barang → cari barang ──────────────────────────────────
    inputKode.addEventListener('keydown', function (e) {
        if (e.key !== 'Enter') return;
        e.preventDefault();

        var kode = this.value.trim();
        if (!kode) return;

        resetInputBarang(false);
        barangDitemukan = false;
        btnTambahkan.disabled = true;

        // Disable input while searching
        inputKode.disabled = true;

        axios.get('{{ route("pos.cari-barang") }}', {
            params: { id_barang: kode }
        })
        .then(function (response) {
            if (response.data.status === 'success') {
                var b = response.data.data;
                inputNama.value  = b.nama;
                inputHarga.value = b.harga;
                inputJumlah.value = 1;
                barangDitemukan = true;
                updateBtnTambah();
            } else {
                Swal.fire('Tidak ditemukan', 'Barang dengan kode tersebut tidak ada di database.', 'warning');
            }
        })
        .catch(function () {
            Swal.fire('Error!', 'Gagal menghubungi server.', 'error');
        })
        .finally(function () {
            inputKode.disabled = false;
            inputKode.focus();
        });
    });

    // ─── Jumlah berubah → cek tombol ─────────────────────────────────────────
    inputJumlah.addEventListener('input', function () {
        updateBtnTambah();
    });

    // ─── Tombol Tambahkan ─────────────────────────────────────────────────────
    btnTambahkan.addEventListener('click', function () {
        var spinnerEl  = btnTambahkan.querySelector('.spinner-border');
        var textEl     = btnTambahkan.querySelector('.btn-tambah-text');

        btnTambahkan.disabled = true;
        spinnerEl.classList.remove('d-none');
        textEl.textContent = 'Menambahkan...';

        var kode     = inputKode.value.trim();
        var nama     = inputNama.value;
        var harga    = parseInt(inputHarga.value);
        var jumlah   = parseInt(inputJumlah.value);
        var subtotal = harga * jumlah;

        var existingIdx = cartItems.findIndex(function (i) { return i.id_barang === kode; });

        if (existingIdx >= 0) {
            cartItems[existingIdx].jumlah  += jumlah;
            cartItems[existingIdx].subtotal = cartItems[existingIdx].harga * cartItems[existingIdx].jumlah;
        } else {
            cartItems.push({ id_barang: kode, nama: nama, harga: harga, jumlah: jumlah, subtotal: subtotal });
        }

        renderCart();
        resetInputBarang(true);
        barangDitemukan = false;

        spinnerEl.classList.add('d-none');
        textEl.textContent = 'Tambahkan';
        // button tetap disabled karena barangDitemukan = false
    });

    // ─── Hapus & edit jumlah baris (event delegation) ────────────────────────
    bodyTransaksi.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-hapus-row')) {
            var idx = parseInt(e.target.getAttribute('data-idx'));
            cartItems.splice(idx, 1);
            renderCart();
        }
    });

    bodyTransaksi.addEventListener('input', function (e) {
        if (e.target.classList.contains('jumlah-input')) {
            var idx    = parseInt(e.target.getAttribute('data-idx'));
            var jumlah = parseInt(e.target.value);
            if (isNaN(jumlah) || jumlah < 1) return;

            cartItems[idx].jumlah   = jumlah;
            cartItems[idx].subtotal = cartItems[idx].harga * jumlah;
            renderCart();
        }
    });

    // ─── Tombol Bayar ─────────────────────────────────────────────────────────
    btnBayar.addEventListener('click', function () {
        if (cartItems.length === 0) return;

        var spinnerEl = btnBayar.querySelector('.spinner-border');
        var textEl    = btnBayar.querySelector('.btn-bayar-text');

        Swal.fire({
            title: 'Konfirmasi Pembayaran',
            text: 'Simpan transaksi ini ke database?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Bayar!',
            cancelButtonText: 'Batal',
        }).then(function (result) {
            if (!result.isConfirmed) return;

            // Spinner on bayar button
            btnBayar.disabled = true;
            spinnerEl.classList.remove('d-none');
            textEl.textContent = 'Memproses...';

            axios.post('{{ route("pos.bayar") }}', {
                items: cartItems,
            })
            .then(function (response) {
                if (response.data.status === 'success') {
                    Swal.fire('Berhasil!', 'Pembayaran berhasil disimpan ke database.', 'success');
                    cartItems = [];
                    renderCart();
                    resetInputBarang(true);
                } else {
                    Swal.fire('Gagal!', response.data.message, 'error');
                }
            })
            .catch(function () {
                Swal.fire('Error!', 'Terjadi kesalahan saat menyimpan.', 'error');
            })
            .finally(function () {
                spinnerEl.classList.add('d-none');
                textEl.textContent = 'Bayar';
                // disabled state diatur lewat renderCart()
            });
        });
    });

    // ─── Helpers ─────────────────────────────────────────────────────────────
    function updateBtnTambah() {
        var jumlah = parseInt(inputJumlah.value);
        btnTambahkan.disabled = !(barangDitemukan && jumlah > 0);
    }

    function resetInputBarang(clearKode) {
        if (clearKode) { inputKode.value = ''; inputKode.focus(); }
        inputNama.value  = '';
        inputHarga.value = '';
        inputJumlah.value = 1;
    }

    function formatRupiah(angka) {
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function renderCart() {
        bodyTransaksi.innerHTML = '';

        if (cartItems.length === 0) {
            bodyTransaksi.innerHTML = '<tr id="emptyRow"><td colspan="6" class="text-center text-muted">Belum ada item</td></tr>';
            totalHarga.textContent = 'Rp 0';
            btnBayar.disabled = true;
            return;
        }

        var total = 0;
        cartItems.forEach(function (item, idx) {
            total += item.subtotal;
            var tr = document.createElement('tr');
            tr.innerHTML =
                '<td>' + item.id_barang + '</td>' +
                '<td>' + item.nama + '</td>' +
                '<td>' + formatRupiah(item.harga) + '</td>' +
                '<td><input type="number" class="form-control form-control-sm jumlah-input" ' +
                    'data-idx="' + idx + '" value="' + item.jumlah + '" min="1" style="width:80px"></td>' +
                '<td>' + formatRupiah(item.subtotal) + '</td>' +
                '<td><button class="btn btn-danger btn-hapus-row" data-idx="' + idx + '">Hapus</button></td>';
            bodyTransaksi.appendChild(tr);
        });

        totalHarga.textContent = formatRupiah(total);
        btnBayar.disabled = false;
    }
});
</script>
@endpush
