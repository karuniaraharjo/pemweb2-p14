<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\BukuController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerpustakaanController;
use App\Http\Controllers\KategoriController;
use App\Models\Anggota;
use App\Models\Buku;
use Illuminate\Support\Facades\DB;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Home route
Route::get('/', function () {
    return view('home');
})->name('home');

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
