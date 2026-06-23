<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Perpustakaan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #0d6efd;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 20px;
            color: #0d6efd;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 12px;
            color: #666;
        }
        .filter-info {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 10px 15px;
            margin-bottom: 15px;
            font-size: 10px;
        }
        .filter-info strong {
            color: #0d6efd;
        }
        .summary {
            display: flex;
            margin-bottom: 15px;
        }
        .summary-item {
            display: inline-block;
            width: 32%;
            text-align: center;
            padding: 10px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            margin-right: 2%;
        }
        .summary-item:last-child {
            margin-right: 0;
        }
        .summary-item h3 {
            font-size: 16px;
            margin-bottom: 3px;
        }
        .summary-item p {
            font-size: 10px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table thead th {
            background-color: #0d6efd;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }
        table tbody td {
            padding: 6px;
            border-bottom: 1px solid #eee;
            font-size: 10px;
        }
        table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge-success {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .badge-danger {
            background-color: #f8d7da;
            color: #842029;
        }
        .text-danger {
            color: #dc3545;
        }
        .text-muted {
            color: #999;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #dee2e6;
            font-size: 10px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📚 Laporan Transaksi Perpustakaan</h1>
        <p>Dicetak pada: {{ now()->format('d M Y H:i') }}</p>
    </div>

    {{-- Filter Info --}}
    <div class="filter-info">
        <strong>Filter yang digunakan:</strong>
        @if(request('dari') || request('sampai'))
            Tanggal: {{ request('dari', 'Awal') }} s/d {{ request('sampai', 'Sekarang') }} |
        @endif
        @if(request('status'))
            Status: {{ request('status') }} |
        @endif
        @if(request('anggota_id'))
            Anggota: {{ $anggotas->find(request('anggota_id'))?->nama ?? '-' }} |
        @endif
        @if(!request('dari') && !request('sampai') && !request('status') && !request('anggota_id'))
            Semua data (tanpa filter)
        @endif
    </div>

    {{-- Summary --}}
    <table style="margin-bottom: 15px;">
        <tr>
            <td style="width: 33%; text-align: center; border: 1px solid #dee2e6; padding: 10px; border-radius: 5px;">
                <div style="font-size: 16px; font-weight: bold; color: #0d6efd;">{{ $transaksis->count() }}</div>
                <div style="font-size: 10px; color: #666;">Total Transaksi</div>
            </td>
            <td style="width: 33%; text-align: center; border: 1px solid #dee2e6; padding: 10px; border-radius: 5px;">
                <div style="font-size: 16px; font-weight: bold; color: #dc3545;">Rp {{ number_format($totalDenda, 0, ',', '.') }}</div>
                <div style="font-size: 10px; color: #666;">Total Denda</div>
            </td>
            <td style="width: 33%; text-align: center; border: 1px solid #dee2e6; padding: 10px; border-radius: 5px;">
                <div style="font-size: 16px; font-weight: bold; color: #ffc107;">{{ $transaksis->where('status', 'Dipinjam')->count() }}</div>
                <div style="font-size: 10px; color: #666;">Masih Dipinjam</div>
            </td>
        </tr>
    </table>

    {{-- Tabel --}}
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Anggota</th>
                <th>Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $transaksi)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $transaksi->kode_transaksi }}</td>
                <td>{{ $transaksi->anggota->nama }}</td>
                <td>{{ $transaksi->buku->judul }}</td>
                <td>{{ $transaksi->tanggal_pinjam->format('d/m/Y') }}</td>
                <td>
                    {{ $transaksi->tanggal_kembali->format('d/m/Y') }}
                    @if($transaksi->status == 'Dikembalikan' && $transaksi->tanggal_dikembalikan)
                        <br><small class="text-muted">Kembali: {{ $transaksi->tanggal_dikembalikan->format('d/m/Y') }}</small>
                    @endif
                </td>
                <td>
                    @if($transaksi->status == 'Dipinjam')
                        <span class="badge badge-warning">Dipinjam</span>
                        @if($transaksi->terlambat > 0)
                            <br><span class="badge badge-danger">Terlambat {{ $transaksi->terlambat }} hr</span>
                        @endif
                    @else
                        <span class="badge badge-success">Dikembalikan</span>
                    @endif
                </td>
                <td>
                    @if($transaksi->denda > 0)
                        <span class="text-danger">Rp {{ number_format($transaksi->denda, 0, ',', '.') }}</span>
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 20px; color: #999;">
                    Tidak ada transaksi ditemukan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Sistem Perpustakaan &copy; {{ date('Y') }} — Laporan ini digenerate secara otomatis
    </div>
</body>
</html>
