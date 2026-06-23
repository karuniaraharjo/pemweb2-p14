<?php
 
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Public routes (tanpa auth)
Route::get('/', function () {
    return redirect()->route('login');
});
 
// Protected routes (dengan auth middleware)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Specific routes for Buku (must be before the resource route)
    Route::get('/buku/search', [BukuController::class, 'search'])->name('buku.search');

    // Custom route untuk filter kategori
    Route::get('/buku/kategori/{kategori}', [BukuController::class, 'filterKategori'])
        ->name('buku.kategori');

    // Route untuk bulk delete
    Route::post('/buku/bulk-delete', [BukuController::class, 'bulkDelete'])
        ->name('buku.bulk-delete');

    // Guard route jika URL bulk-delete diakses via GET
    Route::get('/buku/bulk-delete', function () {
        return redirect()->route('buku.index')
            ->with('error', 'Metode request tidak valid untuk bulk delete.');
    });

    // Route untuk export CSV
    Route::get('/buku/export', [BukuController::class, 'export'])
        ->name('buku.export');

    // Resource route untuk Buku
    Route::resource('buku', BukuController::class);

    // Specific routes for Anggota
    Route::get('/anggota/search', [AnggotaController::class, 'search'])->name('anggota.search');
    Route::get('/anggota/export', [AnggotaController::class, 'export'])->name('anggota.export');

    // Resource route untuk Anggota (akan dibuat nanti)
    Route::resource('anggota', AnggotaController::class);
 
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
 
    // Transaksi - Custom routes (harus sebelum resource)
    Route::get('/transaksi/laporan', [TransaksiController::class, 'laporan'])->name('transaksi.laporan');
    Route::get('/transaksi/laporan/pdf', [TransaksiController::class, 'exportPdf'])->name('transaksi.laporan.pdf');
    
    // Transaksi - CRUD + Custom routes
    Route::resource('transaksi', TransaksiController::class);
    Route::put('/transaksi/{id}/kembalikan', [TransaksiController::class, 'kembalikan'])->name('transaksi.kembalikan');
});
 
require __DIR__.'/auth.php';