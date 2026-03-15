@extends('layouts.app')

@section('title', 'Point of Sales')

@section('page-title', 'Point of Sales (Kasir)')
@section('page-icon', 'mdi-cart')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">POS</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .form-control[readonly] {
        background-color: #f8f9fc;
    }
    .btn-tambahkan {
        min-width: 120px;
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
    <div class="col-12">
        <div class="card kasir-panel">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Halaman Kasir</h4>
                    <span class="badge badge-gradient-info">jQuery AJAX</span>
                </div>

                <!-- Form Input Barang -->
                <div class="row mb-3">
                    <div class="col-12">
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
                            <button class="btn btn-success btn-tambahkan" id="btnTambahkan" disabled>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
$(document).ready(function () {

    var cartItems = []; // [{id_barang, nama, harga, jumlah, subtotal}]
    var barangDitemukan = false;

    // ─── Enter di kode barang → cari barang ──────────────────────────────────
    $('#inputKodeBarang').on('keydown', function (e) {
        if (e.key !== 'Enter') return;
        e.preventDefault();

        var kode = $(this).val().trim();
        if (!kode) return;

        // Reset
        resetInputBarang(false);
        barangDitemukan = false;
        $('#btnTambahkan').prop('disabled', true);

        // Spinner on input (disable while searching)
        var $input = $(this);
        $input.prop('disabled', true).val(kode);

        $.ajax({
            method: 'GET',
            url: '{{ route("pos.cari-barang") }}',
            data: { id_barang: kode },
            success: function (response) {
                if (response.status === 'success') {
                    var b = response.data;
                    $('#inputNamaBarang').val(b.nama);
                    $('#inputHargaBarang').val(b.harga);
                    $('#inputJumlah').val(1);
                    barangDitemukan = true;
                    updateBtnTambah();
                } else {
                    Swal.fire('Tidak ditemukan', 'Barang dengan kode tersebut tidak ada di database.', 'warning');
                }
            },
            error: function () {
                Swal.fire('Error!', 'Gagal menghubungi server.', 'error');
            },
            complete: function () {
                $input.prop('disabled', false).focus();
            }
        });
    });

    // ─── Jumlah berubah → cek tombol ─────────────────────────────────────────
    $('#inputJumlah').on('input', function () {
        updateBtnTambah();
    });

    // ─── Tombol Tambahkan ─────────────────────────────────────────────────────
    $('#btnTambahkan').on('click', function () {
        var $btn = $(this);
        $btn.prop('disabled', true);
        $btn.find('.spinner-border').removeClass('d-none');
        $btn.find('.btn-tambah-text').text('Menambahkan...');

        var kode    = $('#inputKodeBarang').val().trim();
        var nama    = $('#inputNamaBarang').val();
        var harga   = parseInt($('#inputHargaBarang').val());
        var jumlah  = parseInt($('#inputJumlah').val());
        var subtotal = harga * jumlah;

        // Cek apakah kode sudah ada di cart
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

        $btn.find('.spinner-border').addClass('d-none');
        $btn.find('.btn-tambah-text').text('Tambahkan');
        // button tetap disabled karena barangDitemukan = false
    });

    // ─── Hapus baris (event delegation) ──────────────────────────────────────
    $('#bodyTransaksi').on('click', '.btn-hapus-row', function () {
        var idx = $(this).data('idx');
        cartItems.splice(idx, 1);
        renderCart();
    });

    // ─── Jumlah di tabel bisa diedit ─────────────────────────────────────────
    $('#bodyTransaksi').on('input', '.jumlah-input', function () {
        var idx    = $(this).data('idx');
        var jumlah = parseInt($(this).val());
        if (isNaN(jumlah) || jumlah < 1) return;

        cartItems[idx].jumlah   = jumlah;
        cartItems[idx].subtotal = cartItems[idx].harga * jumlah;
        renderCart();
    });

    // ─── Tombol Bayar ─────────────────────────────────────────────────────────
    $('#btnBayar').on('click', function () {
        if (cartItems.length === 0) return;
        var $btnBayar = $(this);

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
            $btnBayar.prop('disabled', true);
            $btnBayar.find('.spinner-border').removeClass('d-none');
            $btnBayar.find('.btn-bayar-text').text('Memproses...');

            $.ajax({
                method: 'POST',
                url: '{{ route("pos.bayar") }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    items: JSON.stringify(cartItems),
                },
                success: function (response) {
                    if (response.status === 'success') {
                        Swal.fire('Berhasil!', 'Pembayaran berhasil disimpan ke database.', 'success');
                        cartItems = [];
                        renderCart();
                        resetInputBarang(true);
                    } else {
                        Swal.fire('Gagal!', response.message, 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'Terjadi kesalahan saat menyimpan.', 'error');
                },
                complete: function () {
                    $btnBayar.find('.spinner-border').addClass('d-none');
                    $btnBayar.find('.btn-bayar-text').text('Bayar');
                    // tombol disabled state diatur ulang lewat renderCart()
                }
            });
        });
    });

    // ─── Helpers ─────────────────────────────────────────────────────────────
    function updateBtnTambah() {
        var jumlah = parseInt($('#inputJumlah').val());
        $('#btnTambahkan').prop('disabled', !(barangDitemukan && jumlah > 0));
    }

    function resetInputBarang(clearKode) {
        if (clearKode) $('#inputKodeBarang').val('').focus();
        $('#inputNamaBarang').val('');
        $('#inputHargaBarang').val('');
        $('#inputJumlah').val(1);
    }

    function formatRupiah(angka) {
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function renderCart() {
        var tbody = $('#bodyTransaksi');
        tbody.empty();

        if (cartItems.length === 0) {
            tbody.html('<tr id="emptyRow"><td colspan="6" class="text-center text-muted">Belum ada item</td></tr>');
            $('#totalHarga').text('Rp 0');
            $('#btnBayar').prop('disabled', true);
            return;
        }

        var total = 0;
        $.each(cartItems, function (idx, item) {
            total += item.subtotal;
            tbody.append(
                '<tr>' +
                '<td>' + item.id_barang + '</td>' +
                '<td>' + item.nama + '</td>' +
                '<td>' + formatRupiah(item.harga) + '</td>' +
                '<td><input type="number" class="form-control form-control-sm jumlah-input" ' +
                    'data-idx="' + idx + '" value="' + item.jumlah + '" min="1" style="width:80px"></td>' +
                '<td>' + formatRupiah(item.subtotal) + '</td>' +
                '<td><button class="btn btn-danger btn-hapus-row" data-idx="' + idx + '">Hapus</button></td>' +
                '</tr>'
            );
        });

        $('#totalHarga').text(formatRupiah(total));
        $('#btnBayar').prop('disabled', false);
    }
});
</script>
@endpush
