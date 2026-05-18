<?php
// ============================================================
// routes/web.php
// ============================================================

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DetectController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\EdukasiController;


Route::get('/deteksi',  [DetectController::class, 'index'])->name('detect');
Route::post('/api/detect', [DetectController::class, 'detect'])->name('api.detect');

Route::prefix('riwayat')->name('history.')->group(function () {
    Route::get('/',          [HistoryController::class, 'index'])->name('index');
    Route::get('/export',    [HistoryController::class, 'export'])->name('export');
    Route::get('/{detection}',      [HistoryController::class, 'show'])->name('show');
    Route::delete('/{detection}',   [HistoryController::class, 'destroy'])->name('destroy');
    Route::delete('/',              [HistoryController::class, 'clear'])->name('clear');
});

Route::get('/',          [HomeController::class,    'index'])->name('home');
Route::get('/edukasi',   [EdukasiController::class, 'index'])->name('edukasi');

// API for AI detection (called by JS frontend)
Route::post('/api/detect', [DetectController::class, 'detect'])->name('api.detect');