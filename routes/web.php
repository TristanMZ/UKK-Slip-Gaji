<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SlipGajiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Aplikasi Slip Gaji Karyawan
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route(auth()->check() ? 'dashboard' : 'login');
});

// Halaman login (tamu saja)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Captcha (harus bisa diakses tanpa login, dipakai di form)
Route::get('/captcha', [SlipGajiController::class, 'captcha'])->name('captcha');

// Halaman yang membutuhkan login
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [SlipGajiController::class, 'index'])->name('dashboard');
    Route::get('/slip/buat', [SlipGajiController::class, 'create'])->name('slip.create');
    Route::post('/slip', [SlipGajiController::class, 'store'])->name('slip.store');
    Route::get('/slip/print', [SlipGajiController::class, 'printByPeriode'])->name('slip.print');
    Route::post('/slip/{slipGaji}/email', [SlipGajiController::class, 'sendEmail'])->name('slip.email');
    Route::get('/slip/{slipGaji}/whatsapp', [SlipGajiController::class, 'sendWhatsapp'])->name('slip.whatsapp');
    Route::get('/slip/{slipGaji}/edit', [SlipGajiController::class, 'edit'])->name('slip.edit');
    Route::put('/slip/{slipGaji}', [SlipGajiController::class, 'update'])->name('slip.update');
    Route::get('/slip/{slipGaji}', [SlipGajiController::class, 'show'])->name('slip.show');
    Route::delete('/slip/{slipGaji}', [SlipGajiController::class, 'destroy'])->name('slip.destroy');
    Route::get('/karyawan/cari', [SlipGajiController::class, 'cariKaryawan'])->name('karyawan.cari');
});
