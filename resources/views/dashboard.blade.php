@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-4">
    <div class="p-4 p-md-5 rounded-4 text-white shadow-sm" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 45%, #0a58ca 100%);">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <span class="badge bg-light text-primary mb-3">Ringkasan Perpustakaan</span>
                <h1 class="display-6 fw-bold mb-3">Dashboard Sistem Perpustakaan</h1>
                <p class="lead mb-0 opacity-75">
                    Pantau kondisi koleksi buku, status anggota, dan data terbaru dalam satu tampilan.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white bg-opacity-10 border border-white border-opacity-25" style="width: 110px; height: 110px;">
                    <i class="bi bi-book-half display-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="text-muted mb-1">Total Buku</p>
                        <h2 class="fw-bold mb-0">{{ $totalBuku }}</h2>
                    </div>
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                        <i class="bi bi-book fs-4"></i>
                    </div>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <span class="badge text-bg-success">Tersedia: {{ $bukuTersedia }}</span>
                    <span class="badge text-bg-danger">Habis: {{ $bukuHabis }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="text-muted mb-1">Total Anggota</p>
                        <h2 class="fw-bold mb-0">{{ $totalAnggota }}</h2>
                    </div>
                    <div class="bg-success bg-opacity-10 text-success rounded-3 p-3">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <span class="badge text-bg-success">Aktif: {{ $anggotaAktif }}</span>
                    <span class="badge text-bg-secondary">Nonaktif: {{ $anggotaNonaktif }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted mb-2">Quick Links</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('buku.index') }}" class="btn btn-outline-primary text-start">
                        <i class="bi bi-book me-2"></i> Menu Buku
                    </a>
                    <a href="{{ route('anggota.index') }}" class="btn btn-outline-success text-start">
                        <i class="bi bi-people me-2"></i> Menu Anggota
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary text-start">
                        <i class="bi bi-house me-2"></i> Home
                    </a>
                    <a href="#" class="btn btn-outline-info text-start">
                        <i class="bi bi-arrow-left-right me-2"></i> Transaksi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0"><i class="bi bi-journal-text me-2 text-primary"></i>5 Buku Terbaru</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bukuTerbaru as $buku)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $buku->judul }}</div>
                                    <small class="text-muted">{{ $buku->kode_buku }}</small>
                                </td>
                                <td>{{ $buku->kategori }}</td>
                                <td>
                                    @if ($buku->stok > 0)
                                        <span class="badge text-bg-success">{{ $buku->stok }}</span>
                                    @else
                                        <span class="badge text-bg-danger">Habis</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">Belum ada data buku.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0"><i class="bi bi-people-fill me-2 text-success"></i>5 Anggota Terbaru</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Kode</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($anggotaTerbaru as $anggota)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $anggota->nama }}</div>
                                    <small class="text-muted">{{ $anggota->email }}</small>
                                </td>
                                <td>{{ $anggota->kode_anggota }}</td>
                                <td>
                                    @if (strtolower($anggota->status) === 'aktif')
                                        <span class="badge text-bg-success">Aktif</span>
                                    @else
                                        <span class="badge text-bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">Belum ada data anggota.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection