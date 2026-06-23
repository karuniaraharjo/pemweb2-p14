@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="bi bi-receipt"></i>
        Detail Transaksi
    </h1>
    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

{{-- Warning Terlambat (Tugas 3) --}}
@if($transaksi->status == 'Dipinjam' && $transaksi->terlambat > 0)
<div class="alert alert-danger d-flex align-items-start" role="alert">
    <i class="bi bi-exclamation-triangle-fill fs-4 me-3 mt-1"></i>
    <div>
        <h5 class="alert-heading mb-1">⚠️ Buku Terlambat Dikembalikan!</h5>
        <p class="mb-1">Buku ini sudah melewati batas tanggal pengembalian <strong>{{ $transaksi->tanggal_kembali->format('d M Y') }}</strong>.</p>
        <p class="mb-0">
            Keterlambatan: <strong class="text-danger">{{ $transaksi->terlambat }} hari</strong> — 
            Estimasi denda: <strong class="text-danger">Rp {{ number_format($transaksi->terlambat * 5000, 0, ',', '.') }}</strong>
        </p>
    </div>
</div>
@endif

<div class="row">
    {{-- Info Transaksi --}}
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informasi Transaksi</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="fw-bold text-muted" style="width: 200px;">Kode Transaksi</td>
                            <td><code class="fs-5">{{ $transaksi->kode_transaksi }}</code></td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Anggota</td>
                            <td>
                                <i class="bi bi-person-fill me-1"></i>
                                {{ $transaksi->anggota->nama }}
                                <small class="text-muted">({{ $transaksi->anggota->kode_anggota }})</small>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Buku</td>
                            <td>
                                <i class="bi bi-book-fill me-1"></i>
                                {{ $transaksi->buku->judul }}
                                <small class="text-muted">({{ $transaksi->buku->kode_buku }})</small>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Tanggal Pinjam</td>
                            <td>{{ $transaksi->tanggal_pinjam->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Batas Pengembalian</td>
                            <td>
                                {{ $transaksi->tanggal_kembali->format('d M Y') }}
                                @if($transaksi->status == 'Dipinjam' && $transaksi->terlambat > 0)
                                    <span class="badge bg-danger ms-2">Terlewat {{ $transaksi->terlambat }} hari</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Durasi Peminjaman</td>
                            <td>{{ $transaksi->durasi_peminjaman }} hari</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Status</td>
                            <td>
                                @if($transaksi->status == 'Dipinjam')
                                    <span class="badge bg-warning text-dark px-3 py-2">
                                        <i class="bi bi-hourglass-split"></i> Dipinjam
                                    </span>
                                @else
                                    <span class="badge bg-success px-3 py-2">
                                        <i class="bi bi-check-circle"></i> Dikembalikan
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @if($transaksi->keterangan)
                        <tr>
                            <td class="fw-bold text-muted">Keterangan</td>
                            <td>{{ $transaksi->keterangan }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Info Pengembalian (jika sudah dikembalikan) --}}
        @if($transaksi->status == 'Dikembalikan')
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-success text-white py-3">
                <h5 class="mb-0"><i class="bi bi-check-circle"></i> Informasi Pengembalian</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr>
                            <td class="fw-bold text-muted" style="width: 200px;">Tanggal Dikembalikan</td>
                            <td>{{ $transaksi->tanggal_dikembalikan->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Keterlambatan</td>
                            <td>
                                @if($transaksi->terlambat > 0)
                                    <span class="text-danger fw-bold">{{ $transaksi->terlambat }} hari terlambat</span>
                                @else
                                    <span class="text-success"><i class="bi bi-check-circle"></i> Tepat waktu</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Total Denda</td>
                            <td>
                                @if($transaksi->denda > 0)
                                    <span class="fs-5 fw-bold text-danger">Rp {{ number_format($transaksi->denda, 0, ',', '.') }}</span>
                                    <br><small class="text-muted">{{ $transaksi->terlambat }} hari × Rp 5.000/hari</small>
                                @else
                                    <span class="text-success fw-bold">Rp 0 (Tidak ada denda)</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    {{-- Sidebar Aksi --}}
    <div class="col-md-4">
        {{-- Card Aksi Kembalikan --}}
        @if($transaksi->status == 'Dipinjam')
        <div class="card shadow-sm border-0 mb-4 {{ $transaksi->terlambat > 0 ? 'border-danger' : 'border-primary' }}">
            <div class="card-header {{ $transaksi->terlambat > 0 ? 'bg-danger' : 'bg-primary' }} text-white py-3">
                <h5 class="mb-0"><i class="bi bi-arrow-return-left"></i> Kembalikan Buku</h5>
            </div>
            <div class="card-body">
                @if($transaksi->terlambat > 0)
                    <div class="alert alert-warning py-2 mb-3">
                        <small>
                            <i class="bi bi-exclamation-triangle"></i>
                            Terlambat <strong>{{ $transaksi->terlambat }} hari</strong><br>
                            Estimasi denda: <strong>Rp {{ number_format($transaksi->terlambat * 5000, 0, ',', '.') }}</strong>
                        </small>
                    </div>
                @else
                    <div class="alert alert-info py-2 mb-3">
                        <small>
                            <i class="bi bi-info-circle"></i>
                            Buku masih dalam batas waktu pengembalian.
                        </small>
                    </div>
                @endif

                <form action="{{ route('transaksi.kembalikan', $transaksi->id) }}" method="POST" id="formKembalikan">
                    @csrf
                    @method('PUT')
                    <button type="button" class="btn {{ $transaksi->terlambat > 0 ? 'btn-danger' : 'btn-primary' }} w-100 py-2" onclick="konfirmasiKembalikan()">
                        <i class="bi bi-arrow-return-left me-1"></i> Kembalikan Buku
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- Card Info Buku --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0"><i class="bi bi-book"></i> Info Buku</h5>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>{{ $transaksi->buku->judul }}</strong></p>
                <p class="text-muted mb-1"><small>Pengarang: {{ $transaksi->buku->pengarang }}</small></p>
                <p class="text-muted mb-1"><small>Penerbit: {{ $transaksi->buku->penerbit }}</small></p>
                <p class="text-muted mb-0"><small>Stok saat ini: <strong>{{ $transaksi->buku->stok }}</strong></small></p>
            </div>
        </div>

        {{-- Card Info Anggota --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0"><i class="bi bi-person"></i> Info Anggota</h5>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>{{ $transaksi->anggota->nama }}</strong></p>
                <p class="text-muted mb-1"><small>Kode: {{ $transaksi->anggota->kode_anggota }}</small></p>
                <p class="text-muted mb-1"><small>Email: {{ $transaksi->anggota->email }}</small></p>
                <p class="text-muted mb-0"><small>Telepon: {{ $transaksi->anggota->telepon }}</small></p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function konfirmasiKembalikan() {
        Swal.fire({
            title: 'Kembalikan Buku?',
            html: `
                <p>Apakah Anda yakin ingin mengembalikan buku ini?</p>
                @if($transaksi->terlambat > 0)
                <div class="alert alert-warning text-start mt-2">
                    <small>
                        <strong>Keterlambatan:</strong> {{ $transaksi->terlambat }} hari<br>
                        <strong>Denda:</strong> Rp {{ number_format($transaksi->terlambat * 5000, 0, ',', '.') }}
                    </small>
                </div>
                @endif
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Kembalikan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formKembalikan').submit();
            }
        });
    }
</script>
@endpush
@endsection
