<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DetectController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\EdukasiController;

// ── Halaman utama ─────────────────────────────────────────────
Route::get('/',          [HomeController::class,    'index'])->name('home');
Route::get('/deteksi',   [DetectController::class,  'index'])->name('detect');
Route::get('/edukasi',   [EdukasiController::class, 'index'])->name('edukasi');

// ── Riwayat ───────────────────────────────────────────────────
Route::prefix('riwayat')->name('history.')->group(function () {
    Route::get('/',        [HistoryController::class, 'index']  )->name('index');
    Route::get('/export',  [HistoryController::class, 'export'] )->name('export');
    Route::get('/{id}',    [HistoryController::class, 'show']   )->name('show');
    Route::delete('/',     [HistoryController::class, 'clear']  )->name('clear');
    Route::delete('/{id}', [HistoryController::class, 'destroy'])->name('destroy');
});

// ── API (dipanggil oleh JS frontend) ─────────────────────────
Route::prefix('api')->group(function () {
    Route::post('/detect',        [DetectController::class, 'detect']    )->name('api.detect');
    Route::get('/detections/map', [DetectController::class, 'mapPoints'] )->name('api.map-points');
    Route::get('/detect/health',  [DetectController::class, 'health']    )->name('api.health');
});