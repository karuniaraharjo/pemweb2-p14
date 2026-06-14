<div class="card mb-3 shadow-sm border-0 overflow-hidden">
    <div class="card-body p-4">
        <div class="row g-4 align-items-center">
            <div class="col-md-2 text-center">
                <div class="form-check mb-3 mt-2">
                    <input class="form-check-input" type="checkbox" name="buku_ids[]" value="{{ $buku->id }}" id="buku_{{ $buku->id }}" form="bulk-delete-form" style="width: 1.25em; height: 1.25em; cursor: pointer;">
                </div>
                <div class="rounded-4 bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center" style="width: 88px; height: 88px;">
                    <i class="bi bi-book-half display-6"></i>
                </div>
                <div class="mt-3">
                    @php
                        $kategoriClass = match ($buku->kategori) {
                            'Programming' => 'primary',
                            'Database' => 'success',
                            'Web Design' => 'info',
                            'Networking' => 'warning',
                            'Data Science' => 'danger',
                            default => 'secondary',
                        };
                    @endphp
                    <span class="badge text-bg-{{ $kategoriClass }}">
                        {{ $buku->kategori }}
                    </span>
                </div>
            </div>

            <div class="col-md-7">
                <h5 class="card-title mb-2">
                    <a href="{{ route('buku.show', $buku->id) }}" class="text-decoration-none text-dark">
                        {{ $buku->judul }}
                    </a>
                </h5>

                <p class="text-muted mb-2">
                    <i class="bi bi-person me-1"></i> {{ $buku->pengarang }}
                </p>

                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge text-bg-light border text-dark">
                        <i class="bi bi-tag me-1"></i> {{ $buku->harga_format }}
                    </span>
                    <span class="badge {{ $buku->stok > 0 ? 'text-bg-success' : 'text-bg-danger' }}">
                        {{ $buku->stok > 0 ? 'Tersedia' : 'Habis' }}
                    </span>
                    <span class="badge text-bg-secondary">
                        Stok: {{ $buku->stok }}
                    </span>
                </div>

                <p class="text-muted small mb-0">
                    {{ $buku->penerbit }} | {{ $buku->tahun_terbit }}
                </p>
            </div>

            <div class="col-md-3 text-md-end">
                <div class="mb-3">
                    <h4 class="text-primary mb-1">{{ $buku->harga_format }}</h4>
                    <small class="text-muted">Harga buku</small>
                </div>

                @if ($showActions)
                    <div class="btn-group-vertical d-grid gap-2">
                        <a href="{{ route('buku.show', $buku->id) }}" class="btn btn-sm btn-info text-white">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                        <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        
                        {{-- Delete Button dengan SweetAlert --}}
                        <form action="{{ route('buku.destroy', $buku->id) }}" 
                            method="POST" 
                            class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-danger w-100 btn-delete" 
                                    data-judul="{{ $buku->judul }}">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
                        
                        @push('scripts')
                        <script>
                            // SweetAlert confirmation untuk delete
                            document.querySelectorAll('.btn-delete').forEach(button => {
                                button.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    const form = this.closest('form');
                                    const judul = this.getAttribute('data-judul');
                                    
                                    Swal.fire({
                                        title: 'Konfirmasi Hapus',
                                        text: `Apakah Anda yakin ingin menghapus buku "${judul}"?`,
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#d33',
                                        cancelButtonColor: '#3085d6',
                                        confirmButtonText: 'Ya, Hapus!',
                                        cancelButtonText: 'Batal'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            form.submit();
                                        }
                                    });
                                });
                            });
                        </script>
                        @endpush
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>