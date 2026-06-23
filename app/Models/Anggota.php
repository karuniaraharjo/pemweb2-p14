<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Anggota extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'anggota';

    /**
     * Kolom yang dapat diisi secara mass assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_anggota',
        'nama',
        'email',
        'telepon',
        'alamat',
        'tanggal_lahir',
        'jenis_kelamin',
        'pekerjaan',
        'tanggal_daftar',
        'status',
    ];

    /**
     * Tipe casting untuk atribut.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_daftar' => 'date',
    ];

    /**
     * Accessor untuk menghitung umur.
     */
    public function getUmurAttribute(): int
    {
        return Carbon::parse($this->tanggal_lahir)->age;
    }

    /**
     * Accessor untuk lama menjadi anggota (dalam hari).
     */
    public function getLamaAnggotaAttribute(): int
    {
        return Carbon::parse($this->tanggal_daftar)->diffInDays(now());
    }

    /**
     * Scope untuk filter anggota aktif.
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'Aktif');
    }

    /**
     * Scope untuk filter berdasarkan jenis kelamin.
     */
    public function scopeJenisKelamin($query, $jenisKelamin)
    {
        return $query->where('jenis_kelamin', $jenisKelamin);
    }

    /**
     * Accessor untuk status badge.
     */
    public function getStatusBadgeAttribute(): string
    {
        if (strtolower($this->status) === 'aktif') {
            return '<span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Aktif</span>';
        }

        return '<span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">Nonaktif</span>';
    }

    /**
     * Accessor kategori usia.
     */
    public function getKategoriUsiaAttribute(): string
    {
        $umur = $this->umur;

        if ($umur < 20) {
            return 'Remaja';
        }

        if ($umur >= 20 && $umur <= 50) {
            return 'Dewasa';
        }

        return 'Senior';
    }

    /**
     * Scope untuk anggota terdaftar pada bulan ini (tahun dan bulan sekarang).
     */
    public function scopeTerdaftarBulanIni($query)
    {
        return $query->whereYear('tanggal_daftar', now()->year)
            ->whereMonth('tanggal_daftar', now()->month);
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}
