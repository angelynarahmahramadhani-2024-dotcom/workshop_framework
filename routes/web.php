<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangJsController;
use App\Http\Controllers\SelectKotaController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\PdfController;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;

// Redirect ke login jika belum login
Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

// Google OAuth Routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// OTP Routes
Route::get('/otp/verify', [OtpController::class, 'showVerifyForm'])->name('otp.verify');
Route::post('/otp/verify', [OtpController::class, 'verify']);
Route::get('/otp/resend', [OtpController::class, 'resend'])->name('otp.resend');

// Midtrans callback (webhook, tanpa login & tanpa CSRF)
Route::post('/payment/midtrans/callback', [OrderController::class, 'midtransCallback'])
    ->withoutMiddleware([ValidateCsrfToken::class])
    ->name('payment.midtrans.callback');

// Order / Payment Gateway Routes (tanpa login, customer = guest)
Route::get('/order', [OrderController::class, 'index'])->name('order.index');
Route::get('/order/menu', [OrderController::class, 'menuByVendor'])->name('order.menu');
Route::post('/order/checkout', [OrderController::class, 'checkout'])->name('order.checkout');
Route::get('/order/qrcode/{idpesanan}', [OrderController::class, 'showQrCode'])->name('order.qrcode');

// Semua route di bawah ini butuh login
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', function () {
        return view('welcome');
    })->name('dashboard');

    // Kategori Routes
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

    // Buku Routes
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
    Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
    Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
    Route::get('/buku/{id}/edit', [BukuController::class, 'edit'])->name('buku.edit');
    Route::put('/buku/{id}', [BukuController::class, 'update'])->name('buku.update');
    Route::delete('/buku/{id}', [BukuController::class, 'destroy'])->name('buku.destroy');

    // Barang Routes
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/barang/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');
    
    // Barang Print Label Routes
    Route::get('/barang/print/form', [BarangController::class, 'printForm'])->name('barang.print-form');
    Route::post('/barang/print/label', [BarangController::class, 'printLabel'])->name('barang.print-label');

    // Barang JavaScript Routes (tidak tersimpan ke database)
    Route::get('/barang-js/form-validasi', [BarangJsController::class, 'formValidasi'])->name('barang-js.form-validasi');
    Route::get('/barang-js/datatables', [BarangJsController::class, 'datatables'])->name('barang-js.datatables');

    // Select Kota Route (Study Case 4)
    Route::get('/select-kota', [SelectKotaController::class, 'index'])->name('select-kota.index');

    // Wilayah Cascading Select Routes
    Route::get('/wilayah', [SelectKotaController::class, 'wilayah'])->name('wilayah.index');
    Route::get('/wilayah-axios', [SelectKotaController::class, 'wilayahAxios'])->name('wilayah.axios');
    Route::get('/wilayah/get-kota', [SelectKotaController::class, 'getKota'])->name('wilayah.get-kota');
    Route::get('/wilayah/get-kecamatan', [SelectKotaController::class, 'getKecamatan'])->name('wilayah.get-kecamatan');
    Route::get('/wilayah/get-kelurahan', [SelectKotaController::class, 'getKelurahan'])->name('wilayah.get-kelurahan');

    // PDF Routes
    Route::get('/pdf/sertifikat', [PdfController::class, 'sertifikat'])->name('pdf.sertifikat');
    Route::get('/pdf/undangan', [PdfController::class, 'undangan'])->name('pdf.undangan');

    // Customer Routes
    Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('/customer/create1', [CustomerController::class, 'create1'])->name('customer.create1');
    Route::post('/customer/store1', [CustomerController::class, 'store1'])->name('customer.store1');
    Route::get('/customer/create2', [CustomerController::class, 'create2'])->name('customer.create2');
    Route::post('/customer/store2', [CustomerController::class, 'store2'])->name('customer.store2');
    Route::get('/customer/foto/{id}', [CustomerController::class, 'showFoto'])->name('customer.foto');

    // POS (Point of Sales) Routes
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::get('/pos-axios', [PosController::class, 'indexAxios'])->name('pos.axios');
    Route::get('/pos/cari-barang', [PosController::class, 'cariBarang'])->name('pos.cari-barang');
    Route::post('/pos/bayar', [PosController::class, 'bayar'])->name('pos.bayar');

    // Vendor routes (hanya role vendor)
    Route::middleware(['role:vendor'])->group(function () {
        Route::get('/vendor/menu', [VendorController::class, 'menuIndex'])->name('vendor.menu.index');
        Route::post('/vendor/menu', [VendorController::class, 'menuStore'])->name('vendor.menu.store');
        Route::get('/vendor/menu/{id}/edit', [VendorController::class, 'menuEdit'])->name('vendor.menu.edit');
        Route::put('/vendor/menu/{id}', [VendorController::class, 'menuUpdate'])->name('vendor.menu.update');
        Route::delete('/vendor/menu/{id}', [VendorController::class, 'menuDestroy'])->name('vendor.menu.destroy');

        Route::get('/vendor/pesanan/lunas', [VendorController::class, 'paidOrders'])->name('vendor.orders.paid');
    });
});
