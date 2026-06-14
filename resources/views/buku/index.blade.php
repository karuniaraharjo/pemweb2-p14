@extends('layouts.app')
 
@section('title', 'Daftar Buku')
 
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="bi bi-book"></i>
        Daftar Buku
    </h1>
    <a href="{{ route('buku.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Buku
    </a>
    <a href="{{ route('buku.export') }}" class="btn btn-success">
        <i class="bi bi-download"></i> Export CSV
    </a>
</div>
 
{{-- Statistik Cards --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Buku</h6>
                        <h2 class="mb-0">{{ $totalBuku }}</h2>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-book-fill" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Buku Tersedia</h6>
                        <h2 class="mb-0">{{ $bukuTersedia }}</h2>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Buku Habis</h6>
                        <h2 class="mb-0">{{ $bukuHabis }}</h2>
                    </div>
                    <div class="text-danger">
                        <i class="bi bi-x-circle-fill" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 
{{-- Search & Advanced Filters --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('buku.search') }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small">Keyword</label>
                    <input type="search" name="keyword" value="{{ $keyword ?? '' }}" class="form-control" placeholder="Cari judul, pengarang, penerbit">
                </div>

                <div class="col-md-2">
                    <label class="form-label small">Kategori</label>
                    <select name="kategori" class="form-select">
                        <option value="">Semua</option>
                        <option value="Programming" {{ (isset($kategori) && $kategori == 'Programming') ? 'selected' : '' }}>Programming</option>
                        <option value="Database" {{ (isset($kategori) && $kategori == 'Database') ? 'selected' : '' }}>Database</option>
                        <option value="Web Design" {{ (isset($kategori) && $kategori == 'Web Design') ? 'selected' : '' }}>Web Design</option>
                        <option value="Networking" {{ (isset($kategori) && $kategori == 'Networking') ? 'selected' : '' }}>Networking</option>
                        <option value="Data Science" {{ (isset($kategori) && $kategori == 'Data Science') ? 'selected' : '' }}>Data Science</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small">Tahun</label>
                    <select name="tahun" class="form-select">
                        <option value="">Semua</option>
                        @if(isset($tahunList))
                            @foreach($tahunList as $th)
                                <option value="{{ $th }}" {{ (isset($tahun) && $tahun == $th) ? 'selected' : '' }}>{{ $th }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small">Ketersediaan</label>
                    <select name="ketersediaan" class="form-select">
                        <option value="">Semua</option>
                        <option value="tersedia" {{ (isset($ketersediaan) && $ketersediaan == 'tersedia') ? 'selected' : '' }}>Tersedia</option>
                        <option value="habis" {{ (isset($ketersediaan) && $ketersediaan == 'habis') ? 'selected' : '' }}>Habis</option>
                    </select>
                </div>

                <div class="col-md-2 text-end">
                    <button type="submit" class="btn btn-primary">Cari</button>
                    <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

@if ($bukus->count() > 0)
{{-- Bulk Delete Form --}}
<form id="bulk-delete-form" action="{{ route('buku.bulk-delete') }}" method="POST">
    @csrf
    <div class="card mb-4 border-warning">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="select-all" style="width: 1.25em; height: 1.25em; cursor: pointer;">
                    <label class="form-check-label" for="select-all" style="cursor: pointer;">
                        <strong>Pilih Semua</strong>
                    </label>
                </div>
                
                <div>
                    <span id="selected-count" class="badge bg-info">0 terpilih</span>
                    <button type="submit" id="bulk-delete-btn" class="btn btn-danger btn-sm ms-2" disabled>
                        <i class="bi bi-trash"></i> Hapus Terpilih
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Daftar Buku --}}
@forelse ($bukus as $buku)
    <x-buku-card :buku="$buku" />
@empty
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i>
        Tidak ada data buku
        @isset($kategori)
            dengan kategori <strong>{{ $kategori }}</strong>
        @endisset
    </div>
@endforelse

<div class="text-center mt-4">
    <p class="text-muted">
        Menampilkan {{ $bukus->count() }} buku
        @isset($kategori)
            dari kategori <strong>{{ $kategori }}</strong>
        @endisset
    </p>
</div>
@else
 
{{-- Daftar Buku (tanpa bulk delete) --}}
@forelse ($bukus as $buku)
    <x-buku-card :buku="$buku" />
@empty
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i>
        Tidak ada data buku
        @isset($kategori)
            dengan kategori <strong>{{ $kategori }}</strong>
        @endisset
    </div>
@endforelse

<div class="text-center mt-4">
    <p class="text-muted">
        Menampilkan {{ $bukus->count() }} buku
        @isset($kategori)
            dari kategori <strong>{{ $kategori }}</strong>
        @endisset
    </p>
</div>

@endif

@push('scripts')
<script>
    // Handle select all checkbox
    const selectAllCheckbox = document.getElementById('select-all');
    const bookCheckboxes = document.querySelectorAll('input[name="buku_ids[]"]');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    const selectedCount = document.getElementById('selected-count');

    // Select all functionality
    if (selectAllCheckbox && bookCheckboxes.length > 0) {
        selectAllCheckbox.addEventListener('change', function() {
            bookCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkDeleteButton();
        });

        // Update button state when individual checkbox is clicked
        bookCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateBulkDeleteButton();
                
                // Update select all checkbox state
                const allChecked = Array.from(bookCheckboxes).every(cb => cb.checked);
                const someChecked = Array.from(bookCheckboxes).some(cb => cb.checked);
                
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = someChecked && !allChecked;
            });
        });
    }

    function updateBulkDeleteButton() {
        const checkedCount = document.querySelectorAll('input[name="buku_ids[]"]:checked').length;
        
        if (selectedCount) {
            selectedCount.textContent = checkedCount + ' terpilih';
        }
        
        if (bulkDeleteBtn) {
            bulkDeleteBtn.disabled = checkedCount === 0;
        }
    }

    // Confirm bulk delete
    const bulkDeleteForm = document.getElementById('bulk-delete-form');
    if (bulkDeleteForm) {
        bulkDeleteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const checkedCount = document.querySelectorAll('input[name="buku_ids[]"]:checked').length;
            
            if (checkedCount === 0) {
                alert('Pilih minimal satu buku untuk dihapus.');
                return false;
            }
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: `Apakah Anda yakin ingin menghapus ${checkedCount} buku terpilih?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        bulkDeleteForm.submit();
                    }
                });
            } else {
                if (!confirm(`Apakah Anda yakin ingin menghapus ${checkedCount} buku terpilih?`)) {
                    return false;
                } else {
                    bulkDeleteForm.submit();
                }
            }
        });
    }
</script>
@endpush
@endsection