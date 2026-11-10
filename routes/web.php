<?php

use App\Http\Controllers\AnakController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GalonController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PublicRoomController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TenantController;
use App\Http\Middleware\OwnerOnly;
use App\Http\Middleware\TenantOnly;
use Illuminate\Support\Facades\Route;

// ---------- Publik ----------
Route::get('/', [PublicRoomController::class, 'index'])->name('home');
Route::post('/pesan', [PublicRoomController::class, 'kirimPesan'])->middleware('throttle:5,1')->name('pesan.kirim');

// ---------- Setelah login: arahkan sesuai peran ----------
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return auth()->user()->role === 'pemilik'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('anak.home');
    })->name('dashboard');

    Route::get('bukti/{payment}', [PaymentController::class, 'bukti'])->name('bukti.show');
});

// ---------- Panel pemilik (ibu kos) ----------
Route::middleware(['auth', OwnerOnly::class])->prefix('admin')->group(function () {
    Route::get('dashboard', DashboardController::class)->name('admin.dashboard');

    Route::resource('kamar', RoomController::class)->parameters(['kamar' => 'room'])->except('show');

    Route::get('foto', [PhotoController::class, 'index'])->name('foto.index');
    Route::post('foto', [PhotoController::class, 'store'])->name('foto.store');
    Route::delete('foto/{photo}', [PhotoController::class, 'destroy'])->name('foto.destroy');

    Route::get('penghuni', [TenantController::class, 'index'])->name('penghuni.index');
    Route::get('penghuni/baru', [TenantController::class, 'create'])->name('penghuni.create');
    Route::post('penghuni', [TenantController::class, 'store'])->name('penghuni.store');
    Route::patch('penghuni/{tenant}/keluar', [TenantController::class, 'checkout'])->name('penghuni.keluar');

    Route::get('tagihan', [PaymentController::class, 'index'])->name('tagihan.index');
    Route::post('tagihan/buat', [PaymentController::class, 'generate'])->name('tagihan.generate');
    Route::patch('tagihan/{payment}/lunas', [PaymentController::class, 'lunas'])->name('tagihan.lunas');
    Route::patch('tagihan/{payment}/tolak', [PaymentController::class, 'tolak'])->name('tagihan.tolak');

    Route::get('galon', [GalonController::class, 'index'])->name('galon.index');
    Route::patch('galon/{order}', [GalonController::class, 'update'])->name('galon.update');

    Route::get('laporan', [ComplaintController::class, 'index'])->name('laporan.index');
    Route::post('laporan', [ComplaintController::class, 'store'])->name('laporan.store');
    Route::patch('laporan/{complaint}', [ComplaintController::class, 'update'])->name('laporan.update');

    Route::get('pesan', [MessageController::class, 'index'])->name('pesan.index');
    Route::patch('pesan/{message}/balas', [MessageController::class, 'balas'])->name('pesan.balas');
    Route::patch('pesan/{message}/baca', [MessageController::class, 'baca'])->name('pesan.baca');
    Route::delete('pesan/{message}', [MessageController::class, 'destroy'])->name('pesan.destroy');
});

// ---------- Halaman anak kos ----------
Route::middleware(['auth', TenantOnly::class])->prefix('anak')->name('anak.')->group(function () {
    Route::get('/', [AnakController::class, 'home'])->name('home');
    Route::get('tagihan', [AnakController::class, 'tagihan'])->name('tagihan');
    Route::post('tagihan/{payment}/bayar', [AnakController::class, 'bayar'])->name('bayar');
    Route::get('galon', [AnakController::class, 'galon'])->name('galon');
    Route::post('galon', [AnakController::class, 'pesanGalon'])->name('galon.store');
    Route::get('laporan', [AnakController::class, 'laporan'])->name('laporan');
    Route::post('laporan', [AnakController::class, 'kirimLaporan'])->name('laporan.store');
    Route::get('pesan', [AnakController::class, 'pesan'])->name('pesan');
    Route::post('pesan', [AnakController::class, 'kirimPesan'])->name('pesan.store');
});

require __DIR__ . '/auth.php';
