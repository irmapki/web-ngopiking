<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PesananController;
use App\Http\Controllers\Admin\InputManualController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\RiwayatController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\AkunController;
use App\Http\Controllers\Admin\StrukController;
use App\Http\Controllers\OrderController;

// Auth
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');
Route::get('/', fn() => redirect()->route('login'));

Route::prefix('admin')->name('admin.')->middleware('admin.auth')->group(function () {

    // ── Semua role (admin + kasir) ──
    Route::get('/dashboard',     [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/riwayat',       [RiwayatController::class, 'index'])->name('riwayat');
    Route::get('/riwayat/{transaction}', [RiwayatController::class, 'show'])->name('riwayat.show');
    Route::get('/struk/{id}', [StrukController::class, 'show'])->name('struk');

    // Pesanan masuk
    Route::get('/pesanan',                   [PesananController::class, 'index'])->name('pesanan');
    Route::post('/pesanan/{id}/selesai',     [PesananController::class, 'selesai'])->name('pesanan.selesai');
    Route::post('/pesanan/{id}/batal',       [PesananController::class, 'batal'])->name('pesanan.batal');

    // Input manual
    Route::get('/input-manual',  [InputManualController::class, 'index'])->name('input-manual');
    Route::post('/input-manual', [InputManualController::class, 'store'])->name('input-manual.store');

    // ── Hanya admin ──
    Route::middleware('role.admin')->group(function () {

        // Produk
        Route::get('/produk',              [ProdukController::class, 'index'])->name('produk');
        Route::post('/produk',             [ProdukController::class, 'store'])->name('produk.store');
        Route::put('/produk/{product}',    [ProdukController::class, 'update'])->name('produk.update');
        Route::delete('/produk/{product}', [ProdukController::class, 'destroy'])->name('produk.destroy');

        // Laporan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');

        // Pengaturan
        Route::get('/pengaturan',              [PengaturanController::class, 'index'])->name('pengaturan');
        Route::post('/pengaturan/pajak',       [PengaturanController::class, 'updatePajak'])->name('pengaturan.pajak');
        Route::post('/pengaturan/nomor',       [PengaturanController::class, 'updateNomor'])->name('pengaturan.nomor');
        Route::post('/pengaturan/printer',     [PengaturanController::class, 'updatePrinter'])->name('pengaturan.printer');
        Route::post('/pengaturan/profil',      [PengaturanController::class, 'updateProfil'])->name('pengaturan.profil');

        // Kelola akun
        Route::get('/akun',           [AkunController::class, 'index'])->name('akun');
        Route::post('/akun',          [AkunController::class, 'store'])->name('akun.store');
        Route::put('/akun/{user}',    [AkunController::class, 'update'])->name('akun.update');
        Route::delete('/akun/{user}', [AkunController::class, 'destroy'])->name('akun.destroy');

    }); // tutup role.admin

}); // tutup admin prefix

// ── Self-order (publik) — TIDAK di-nest dalam /admin atau middleware admin ──
Route::prefix('order')->name('order.')->group(function () {
    Route::get('/',          [OrderController::class, 'index'])->name('index');
    Route::get('/menu',      [OrderController::class, 'menu'])->name('menu');
    Route::post('/keranjang/tambah', [OrderController::class, 'tambah'])->name('tambah');
    Route::post('/keranjang/hapus', [OrderController::class, 'hapus'])->name('hapus');
    Route::post('/keranjang/update', [OrderController::class, 'update'])->name('update');
    Route::get('/keranjang', [OrderController::class, 'keranjang'])->name('keranjang');
    Route::get('/checkout',  [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'store'])->name('store');
    Route::get('/qris/{kode}',    [OrderController::class, 'qris'])->name('qris');
    Route::post('/qris/{kode}/selesai', [OrderController::class, 'qrisSelesai'])->name('qris.selesai');
    Route::get('/success/{kode}', [OrderController::class, 'success'])->name('success');
});