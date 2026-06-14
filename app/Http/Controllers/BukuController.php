<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use Illuminate\Http\Request;
use App\Models\Buku;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data buku dari database
        $bukus = Buku::latest()->get();

        // Statistik untuk card
        $totalBuku = Buku::count();
        $bukuTersedia = Buku::where('stok', '>', 0)->count();
        $bukuHabis = Buku::where('stok', 0)->count();

        $tahunList = Buku::select('tahun_terbit')->distinct()->orderByDesc('tahun_terbit')->pluck('tahun_terbit');

        // Return view dengan data
        return view('buku.index', compact(
            'bukus',
            'totalBuku',
            'bukuTersedia',
            'bukuHabis',
            'tahunList'
        ));
    }

    /**
     * Search and advanced filter for buku.
     */
    public function search(\Illuminate\Http\Request $request)
    {
        $query = Buku::query();

        if ($request->filled('keyword')) {
            $kw = $request->keyword;
            $query->where(function ($q) use ($kw) {
                $q->where('judul', 'like', "%{$kw}%")
                    ->orWhere('pengarang', 'like', "%{$kw}%")
                    ->orWhere('penerbit', 'like', "%{$kw}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('tahun')) {
            $query->where('tahun_terbit', $request->tahun);
        }

        if ($request->filled('ketersediaan')) {
            if ($request->ketersediaan === 'tersedia') {
                $query->where('stok', '>', 0);
            } elseif ($request->ketersediaan === 'habis') {
                $query->where('stok', 0);
            }
        }

        $bukus = $query->latest()->get();

        $totalBuku = $bukus->count();
        $bukuTersedia = $bukus->where('stok', '>', 0)->count();
        $bukuHabis = $bukus->where('stok', 0)->count();

        $tahunList = Buku::select('tahun_terbit')->distinct()->orderByDesc('tahun_terbit')->pluck('tahun_terbit');

        $kategori = $request->kategori ?? null;
        $tahun = $request->tahun ?? null;
        $ketersediaan = $request->ketersediaan ?? null;
        $keyword = $request->keyword ?? null;

        return view('buku.index', compact(
            'bukus',
            'totalBuku',
            'bukuTersedia',
            'bukuHabis',
            'tahunList',
            'kategori',
            'tahun',
            'ketersediaan',
            'keyword'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Akan diimplementasi di pertemuan 12
        return view('buku.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBukuRequest $request)
    {
        try {
            // Create buku baru dengan validated data
            Buku::create($request->validated());

            // Redirect dengan success message
            return redirect()->route('buku.index')
                ->with('success', 'Buku berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Redirect dengan error message jika gagal
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan buku: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find buku by ID, throw 404 if not found
        $buku = Buku::findOrFail($id);

        // Return view detail buku
        return view('buku.show', compact('buku'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Akan diimplementasi di pertemuan 12
        $buku = Buku::findOrFail($id);
        return view('buku.edit', compact('buku'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBukuRequest $request, string $id)
    {
        try {
            $buku = Buku::findOrFail($id);

            // Update buku dengan validated data
            $buku->update($request->validated());

            // Redirect dengan success message
            return redirect()->route('buku.show', $buku->id)
                ->with('success', 'Buku berhasil diupdate!');
        } catch (\Exception $e) {
            // Redirect dengan error message jika gagal
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate buku: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $buku = Buku::findOrFail($id);
            $judulBuku = $buku->judul;

            // Delete buku
            $buku->delete();

            // Redirect dengan success message
            return redirect()->route('buku.index')
                ->with('success', "Buku '{$judulBuku}' berhasil dihapus!");
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return redirect()->route('buku.index')
                    ->with('error', 'Data buku tidak ditemukan atau sudah dihapus.');
            }

            // Redirect dengan error message jika gagal
            return redirect()->back()
                ->with('error', 'Gagal menghapus buku: ' . $e->getMessage());
        }
    }

    /**
     * Filter buku berdasarkan kategori.
     */
    public function filterKategori($kategori)
    {
        $bukus = Buku::where('kategori', $kategori)->latest()->get();

        $totalBuku = $bukus->count();
        $bukuTersedia = $bukus->where('stok', '>', 0)->count();
        $bukuHabis = $bukus->where('stok', 0)->count();

        return view('buku.index', compact(
            'bukus',
            'totalBuku',
            'bukuTersedia',
            'bukuHabis',
            'kategori'
        ));
    }

    /**
     * Delete multiple books at once.
     */
    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->input('buku_ids', []);

            // Validate that IDs array is not empty
            if (empty($ids)) {
                return redirect()->route('buku.index')
                    ->with('error', 'Pilih minimal satu buku untuk dihapus.');
            }

            // Cast IDs to integers and filter out invalid values
            $validIds = array_filter(array_map('intval', $ids), function($id) { 
                return $id > 0; 
            });
            
            if (empty($validIds)) {
                return redirect()->route('buku.index')
                    ->with('error', 'ID buku tidak valid.');
            }

            // Delete books with the specified IDs
            $deleted = Buku::whereIn('id', $validIds)->delete();

            // Redirect dengan success message
            return redirect()->route('buku.index')
                ->with('success', $deleted . ' buku berhasil dihapus!');
        } catch (\Exception $e) {
            // Redirect dengan error message jika gagal
            return redirect()->back()
                ->with('error', 'Gagal menghapus buku: ' . $e->getMessage());
        }
    }

    /**
     * Export books data to CSV file.
     */
    public function export()
    {
        try {
            $bukus = Buku::all();

            $filename = 'buku_' . date('Y-m-d_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function () use ($bukus) {
                $file = fopen('php://output', 'w');

                // Add BOM for proper UTF-8 encoding in Excel
                fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

                // CSV Header
                fputcsv($file, [
                    'Kode Buku',
                    'Judul',
                    'Kategori',
                    'Pengarang',
                    'Penerbit',
                    'Tahun Terbit',
                    'ISBN',
                    'Harga',
                    'Stok',
                    'Bahasa',
                    'Deskripsi'
                ], ';');

                // CSV Data
                foreach ($bukus as $buku) {
                    fputcsv($file, [
                        $buku->kode_buku,
                        $buku->judul,
                        $buku->kategori,
                        $buku->pengarang,
                        $buku->penerbit,
                        $buku->tahun_terbit,
                        $buku->isbn,
                        number_format($buku->harga, 2, ',', '.'),
                        $buku->stok,
                        $buku->bahasa,
                        $buku->deskripsi,
                    ], ';');
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal export data: ' . $e->getMessage());
        }
    }
}
