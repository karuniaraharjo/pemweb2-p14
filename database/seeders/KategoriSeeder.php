<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama_kategori' => 'Programming', 'deskripsi' => 'Buku tentang programming', 'icon' => 'code-slash', 'warna' => 'primary', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Database', 'deskripsi' => 'Buku tentang database', 'icon' => 'database', 'warna' => 'success', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Web Design', 'deskripsi' => 'Buku tentang desain web', 'icon' => 'palette', 'warna' => 'info', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Networking', 'deskripsi' => 'Buku tentang jaringan', 'icon' => 'wifi', 'warna' => 'warning', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Data Science', 'deskripsi' => 'Buku tentang data science', 'icon' => 'graph-up', 'warna' => 'danger', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('kategori')->insert($data);
    }
}
