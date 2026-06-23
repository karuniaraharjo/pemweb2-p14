@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="bi bi-speedometer2"></i> Dashboard Perpustakaan
    </h1>
</div>

<!-- Statistics Cards -->
<div class="row mb-4 g-3">
    <div class="col-md-3">
        <div class="card bg-primary text-white h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1 text-white-50">Total Buku</h6>
                        <h2 class="mb-0 fw-bold">{{ $totalBuku }}</h2>
                    </div>
                    <div>
                        <i class="bi bi-book" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-success text-white h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1 text-white-50">Total Anggota</h6>
                        <h2 class="mb-0 fw-bold">{{ $totalAnggota }}</h2>
                    </div>
                    <div>
                        <i class="bi bi-people" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-warning text-dark h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1 text-dark-50" style="opacity: 0.7;">Dipinjam</h6>
                        <h2 class="mb-0 fw-bold">{{ \App\Models\Transaksi::where('status', 'Dipinjam')->count() }}</h2>
                    </div>
                    <div>
                        <i class="bi bi-journal-arrow-up" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-danger text-white h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1 text-white-50">Buku Terlambat</h6>
                        <h2 class="mb-0 fw-bold">{{ $jumlahTerlambat }}</h2>
                    </div>
                    <div>
                        <i class="bi bi-exclamation-triangle" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Widget Buku Terlambat (Tugas 3) --}}
@if($jumlahTerlambat > 0)
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm border-0 border-start border-danger border-4">
            <div class="card-header bg-danger text-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="bi bi-exclamation-triangle-fill"></i> Buku Terlambat Dikembalikan
                    <span class="badge bg-white text-danger ms-2">{{ $jumlahTerlambat }}</span>
                </h5>
                <a href="{{ route('transaksi.laporan', ['status' => 'Dipinjam']) }}" class="btn btn-sm btn-outline-light">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4 py-3">Anggota</th>
                                <th class="py-3">Buku</th>
                                <th class="py-3">Batas Kembali</th>
                                <th class="py-3">Terlambat</th>
                                <th class="py-3">Estimasi Denda</th>
                                <th class="pe-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksiTerlambat as $trl)
                            <tr>
                                <td class="ps-4 py-3">
                                    <i class="bi bi-person-fill text-muted me-1"></i>
                                    <strong>{{ $trl->anggota->nama }}</strong>
                                    <br><small class="text-muted">{{ $trl->anggota->kode_anggota }}</small>
                                </td>
                                <td class="py-3">{{ $trl->buku->judul }}</td>
                                <td class="py-3">
                                    <span class="text-danger">{{ $trl->tanggal_kembali->format('d M Y') }}</span>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-danger px-3 py-2 rounded-pill">
                                        {{ $trl->terlambat }} hari
                                    </span>
                                </td>
                                <td class="py-3">
                                    <span class="text-danger fw-bold">
                                        Rp {{ number_format($trl->terlambat * 5000, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="pe-4 py-3">
                                    <a href="{{ route('transaksi.show', $trl->id) }}" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row mb-4">
    <!-- Quick Actions -->
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-lightning-charge text-warning"></i> Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="{{ route('buku.create') }}" class="btn btn-outline-primary w-100 py-3 d-flex align-items-center justify-content-center flex-column gap-2">
                            <i class="bi bi-book fs-3"></i>
                            <span class="fw-medium">Tambah Buku</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('anggota.create') }}" class="btn btn-outline-success w-100 py-3 d-flex align-items-center justify-content-center flex-column gap-2">
                            <i class="bi bi-person-plus fs-3"></i>
                            <span class="fw-medium">Tambah Anggota</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('transaksi.create') }}" class="btn btn-outline-warning w-100 py-3 d-flex align-items-center justify-content-center flex-column gap-2">
                            <i class="bi bi-journal-plus fs-3"></i>
                            <span class="fw-medium">Pinjam Buku</span>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('transaksi.laporan') }}" class="btn btn-outline-info w-100 py-3 d-flex align-items-center justify-content-center flex-column gap-2">
                            <i class="bi bi-file-earmark-bar-graph fs-3"></i>
                            <span class="fw-medium">Laporan Transaksi</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold"><i class="bi bi-clock-history text-primary"></i> Transaksi Terbaru</h5>
                <a href="{{ route('transaksi.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4 py-3">Kode</th>
                                <th class="py-3">Anggota</th>
                                <th class="py-3">Buku</th>
                                <th class="py-3">Tanggal Pinjam</th>
                                <th class="pe-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\Transaksi::with(['anggota', 'buku'])->latest()->take(5)->get() as $transaksi)
                            <tr>
                                <td class="ps-4 py-3"><span class="fw-medium">{{ $transaksi->kode_transaksi }}</span></td>
                                <td class="py-3">{{ $transaksi->anggota->nama }}</td>
                                <td class="py-3 text-muted">{{ $transaksi->buku->judul }}</td>
                                <td class="py-3">{{ $transaksi->tanggal_pinjam->format('d M Y') }}</td>
                                <td class="pe-4 py-3">
                                    @if($transaksi->status == 'Dipinjam')
                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">{{ $transaksi->status }}</span>
                                        @if($transaksi->terlambat > 0)
                                            <span class="badge bg-danger px-3 py-2 rounded-pill">Terlambat {{ $transaksi->terlambat }} hari</span>
                                        @endif
                                    @else
                                        <span class="badge bg-success px-3 py-2 rounded-pill">{{ $transaksi->status }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-4 d-block mb-2 text-secondary"></i>
                                    Belum ada transaksi
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection