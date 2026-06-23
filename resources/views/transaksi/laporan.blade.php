@extends('layouts.app')

@section('title', 'Laporan Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="bi bi-file-earmark-bar-graph"></i>
        Laporan Transaksi
    </h1>
    <div>
        <a href="{{ route('transaksi.laporan.pdf', request()->query()) }}" class="btn btn-danger" target="_blank">
            <i class="bi bi-file-earmark-pdf"></i> Export PDF
        </a>
    </div>
</div>

{{-- Form Filter --}}
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white border-bottom py-3">
        <h5 class="mb-0"><i class="bi bi-funnel"></i> Filter Laporan</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('transaksi.laporan') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="dari" class="form-label">Tanggal Dari</label>
                    <input type="date" name="dari" id="dari" class="form-control" value="{{ request('dari') }}">
                </div>
                <div class="col-md-3">
                    <label for="sampai" class="form-label">Tanggal Sampai</label>
                    <input type="date" name="sampai" id="sampai" class="form-control" value="{{ request('sampai') }}">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="anggota_id" class="form-label">Anggota</label>
                    <select name="anggota_id" id="anggota_id" class="form-select">
                        <option value="">Semua Anggota</option>
                        @foreach($anggotas as $anggota)
                            <option value="{{ $anggota->id }}" {{ request('anggota_id') == $anggota->id ? 'selected' : '' }}>
                                {{ $anggota->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filter
                </button>
                <a href="{{ route('transaksi.laporan') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Ringkasan --}}
<div class="row mb-4 g-3">
    <div class="col-md-4">
        <div class="card border-primary shadow-sm border-0">
            <div class="card-body text-center">
                <h6 class="text-muted"><i class="bi bi-receipt"></i> Total Transaksi</h6>
                <h2 class="fw-bold text-primary">{{ $transaksis->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-danger shadow-sm border-0">
            <div class="card-body text-center">
                <h6 class="text-muted"><i class="bi bi-cash-coin"></i> Total Denda</h6>
                <h2 class="fw-bold text-danger">Rp {{ number_format($totalDenda, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-warning shadow-sm border-0">
            <div class="card-body text-center">
                <h6 class="text-muted"><i class="bi bi-hourglass-split"></i> Masih Dipinjam</h6>
                <h2 class="fw-bold text-warning">{{ $transaksis->where('status', 'Dipinjam')->count() }}</h2>
            </div>
        </div>
    </div>
</div>

{{-- Tabel Hasil --}}
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kode Transaksi</th>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                        <th>Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $transaksi)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><code>{{ $transaksi->kode_transaksi }}</code></td>
                        <td>{{ $transaksi->anggota->nama }}</td>
                        <td>{{ $transaksi->buku->judul }}</td>
                        <td>{{ $transaksi->tanggal_pinjam->format('d M Y') }}</td>
                        <td>
                            {{ $transaksi->tanggal_kembali->format('d M Y') }}
                            @if($transaksi->status == 'Dikembalikan' && $transaksi->tanggal_dikembalikan)
                                <br><small class="text-muted">Dikembalikan: {{ $transaksi->tanggal_dikembalikan->format('d M Y') }}</small>
                            @endif
                        </td>
                        <td>
                            @if($transaksi->status == 'Dipinjam')
                                <span class="badge bg-warning text-dark">Dipinjam</span>
                                @if($transaksi->terlambat > 0)
                                    <br><span class="badge bg-danger mt-1">Terlambat {{ $transaksi->terlambat }} hari</span>
                                @endif
                            @else
                                <span class="badge bg-success">Dikembalikan</span>
                            @endif
                        </td>
                        <td>
                            @if($transaksi->denda > 0)
                                <span class="text-danger fw-bold">Rp {{ number_format($transaksi->denda, 0, ',', '.') }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            Tidak ada transaksi ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
