<?php

namespace App\Http\Controllers;

class KategoriController extends Controller
{
    private array $kategori_list = [
        [
            'id' => 1,
            'nama' => 'Programming',
            'deskripsi' => 'Buku pemrograman dan coding',
            'jumlah_buku' => 25,
        ],
        [
            'id' => 2,
            'nama' => 'Web Development',
            'deskripsi' => 'Buku tentang pengembangan website',
            'jumlah_buku' => 18,
        ],
        [
            'id' => 3,
            'nama' => 'Database',
            'deskripsi' => 'Buku manajemen dan desain database',
            'jumlah_buku' => 12,
        ],
        [
            'id' => 4,
            'nama' => 'Mobile Development',
            'deskripsi' => 'Buku pengembangan aplikasi mobile',
            'jumlah_buku' => 20,
        ],
        [
            'id' => 5,
            'nama' => 'UI/UX Design',
            'deskripsi' => 'Buku desain antarmuka dan pengalaman pengguna',
            'jumlah_buku' => 15,
        ],
    ];

    public function index()
    {
        $kategori_list = $this->kategori_list;

        return view('kategori.index', compact('kategori_list'));
    }

    public function show($id)
    {
        $kategori = collect($this->kategori_list)->firstWhere('id', (int) $id);

        abort_if(! $kategori, 404, 'Kategori tidak ditemukan');

        $bukuByKategori = [
            1 => [
                ['id' => 1, 'judul' => 'Belajar PHP untuk Pemula', 'penulis' => 'Adi Nugroho', 'tahun' => 2023],
                ['id' => 2, 'judul' => 'Clean Code', 'penulis' => 'Robert C. Martin', 'tahun' => 2022],
                ['id' => 3, 'judul' => 'Design Pattern', 'penulis' => 'Gang of Four', 'tahun' => 2023],
                ['id' => 4, 'judul' => 'Algoritma dan Struktur Data', 'penulis' => 'Budi Sutisna', 'tahun' => 2021],
                ['id' => 5, 'judul' => 'Python untuk Data Science', 'penulis' => 'Widy Siswanto', 'tahun' => 2023],
            ],
            2 => [
                ['id' => 1, 'judul' => 'HTML5 dan CSS3', 'penulis' => 'Marco Cesare', 'tahun' => 2023],
                ['id' => 2, 'judul' => 'JavaScript Handbook', 'penulis' => 'Kyle Simpson', 'tahun' => 2022],
                ['id' => 3, 'judul' => 'React.js untuk Pemula', 'penulis' => 'Ismail Yildirim', 'tahun' => 2023],
                ['id' => 4, 'judul' => 'Vue.js Mastery', 'penulis' => 'Evan You', 'tahun' => 2023],
            ],
            3 => [
                ['id' => 1, 'judul' => 'MySQL Handbook', 'penulis' => 'Paul DuBois', 'tahun' => 2023],
                ['id' => 2, 'judul' => 'PostgreSQL Kung Fu', 'penulis' => 'Josh Berkus', 'tahun' => 2022],
                ['id' => 3, 'judul' => 'MongoDB Guide', 'penulis' => 'Shannon Bradshaw', 'tahun' => 2023],
            ],
            4 => [
                ['id' => 1, 'judul' => 'Android Development with Kotlin', 'penulis' => 'Emmett Etienne', 'tahun' => 2023],
                ['id' => 2, 'judul' => 'Swift for iOS', 'penulis' => 'Ray Wenderlich', 'tahun' => 2023],
                ['id' => 3, 'judul' => 'Flutter Development', 'penulis' => 'Marty Sipley', 'tahun' => 2023],
            ],
            5 => [
                ['id' => 1, 'judul' => 'The Design of Everyday Things', 'penulis' => 'Don Norman', 'tahun' => 2023],
                ['id' => 2, 'judul' => 'UX for Developers', 'penulis' => 'Lynda Shadrake', 'tahun' => 2022],
                ['id' => 3, 'judul' => 'Wireframing and Prototyping', 'penulis' => 'Bill Scott', 'tahun' => 2023],
            ],
        ];

        $buku_list = $bukuByKategori[$id] ?? [];

        return view('kategori.show', compact('kategori', 'buku_list'));
    }

    public function search($keyword)
    {
        $keyword = trim((string) $keyword);
        $keywordLower = strtolower($keyword);

        $hasil_pencarian = array_values(array_filter($this->kategori_list, function (array $kategori) use ($keywordLower) {
            return str_contains(strtolower($kategori['nama']), $keywordLower)
                || str_contains(strtolower($kategori['deskripsi']), $keywordLower);
        }));

        return view('kategori.search', compact('hasil_pencarian', 'keyword'));
    }
}
