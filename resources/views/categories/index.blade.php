@extends('layouts.app')

@section('title', 'Data Kategori')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h5 class="fw-bold mb-1 text-dark">Data Kategori</h5>
        <p class="text-muted mb-0" style="font-size:0.8125rem;">Kelola kategori produk beras & sembako</p>
    </div>
    <button type="button" class="btn btn-mat-primary" data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
    </button>
</div>

<div class="mat-card">
    <div class="mat-card-body p-0">
        <div class="table-responsive">
            <table class="mat-table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th class="text-center">Jumlah Barang</th>
                        <th width="140" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $i => $category)
                        <tr>
                            <td>{{ $categories->firstItem() ? $categories->firstItem() + $i : $i + 1 }}</td>
                            <td class="fw-semibold text-dark">{{ $category->name }}</td>
                            <td class="text-muted">{{ $category->description ?? '-' }}</td>
                            <td class="text-center">
                                <span class="mat-badge mat-badge-primary">{{ $category->items_count }} Item</span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-primary me-1 rounded-pill btn-edit-category"
                                        data-id="{{ $category->id }}"
                                        data-name="{{ $category->name }}"
                                        data-description="{{ $category->description }}"
                                        title="Edit Kategori">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline" data-name="{{ $category->name }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill btn-delete" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada data kategori.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="text-muted small">
                    Menampilkan {{ $categories->firstItem() }} sampai {{ $categories->lastItem() }} dari {{ $categories->total() }} kategori
                </div>
                <div>
                    {{ $categories->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

{{-- MODAL TAMBAH KATEGORI --}}
<div class="modal fade" id="modalTambahKategori" tabindex="-1" aria-labelledby="modalTambahKategoriLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-light border-0 py-3 px-4">
                <h6 class="modal-title fw-bold text-dark d-flex align-items-center" id="modalTambahKategoriLabel">
                    <i class="bi bi-plus-circle text-primary me-2 fs-5"></i> Tambah Kategori Baru
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="cat_name" class="form-label fw-medium">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="cat_name" name="name" value="{{ old('name') }}" placeholder="Contoh: Beras Premium" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <label for="cat_desc" class="form-label fw-medium">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="cat_desc" name="description" rows="3" placeholder="Deskripsi kategori (opsional)">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 py-3 px-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-mat-primary">
                        <i class="bi bi-check-lg me-1"></i> Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT KATEGORI --}}
<div class="modal fade" id="modalEditKategori" tabindex="-1" aria-labelledby="modalEditKategoriLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-light border-0 py-3 px-4">
                <h6 class="modal-title fw-bold text-dark d-flex align-items-center" id="modalEditKategoriLabel">
                    <i class="bi bi-pencil-square text-primary me-2 fs-5"></i> Edit Kategori
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditCategory" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="edit_cat_name" class="form-label fw-medium">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_cat_name" name="name" required>
                    </div>

                    <div class="mb-2">
                        <label for="edit_cat_desc" class="form-label fw-medium">Deskripsi</label>
                        <textarea class="form-control" id="edit_cat_desc" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 py-3 px-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-mat-primary">
                        <i class="bi bi-check-lg me-1"></i> Perbarui Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const editModal = new bootstrap.Modal(document.getElementById('modalEditKategori'));
    const formEdit = document.getElementById('formEditCategory');
    const inputName = document.getElementById('edit_cat_name');
    const inputDesc = document.getElementById('edit_cat_desc');

    document.querySelectorAll('.btn-edit-category').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const desc = this.dataset.description || '';

            formEdit.action = `/categories/${id}`;
            inputName.value = name;
            inputDesc.value = desc;

            editModal.show();
        });
    });
</script>
@endpush
