@extends('layouts.app')

@section('title', 'Data Barang')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h5 class="fw-bold mb-1 text-dark">Data Barang</h5>
        <p class="text-muted mb-0" style="font-size:0.8125rem;">Kelola daftar produk beras, harga, dan persediaan</p>
    </div>
    <button type="button" class="btn btn-mat-primary" data-bs-toggle="modal" data-bs-target="#modalTambahBarang">
        <i class="bi bi-plus-lg me-1"></i> Tambah Barang
    </button>
</div>

{{-- Filter & Search Card --}}
<div class="mat-card mb-4">
    <div class="mat-card-body py-3">
        <form action="{{ route('items.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label for="search" class="form-label fw-medium mb-1" style="font-size:0.8125rem;">Cari Barang / SKU</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Ketik nama beras atau kode SKU...">
                </div>
            </div>
            <div class="col-md-4">
                <label for="category_id" class="form-label fw-medium mb-1" style="font-size:0.8125rem;">Kategori</label>
                <select class="form-select" id="category_id" name="category_id">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-mat-primary w-100">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'category_id']) && (request('search') || request('category_id')))
                    <a href="{{ route('items.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="mat-card">
    <div class="mat-card-body p-0">
        <div class="table-responsive">
            <table class="mat-table">
                <thead>
                    <tr>
                        <th width="40">No</th>
                        <th>Kode SKU</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th class="text-end">Harga Beli</th>
                        <th class="text-end">Harga Jual</th>
                        <th class="text-center">Stok</th>
                        <th width="120" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $i => $item)
                        <tr>
                            <td>{{ $items->firstItem() ? $items->firstItem() + $i : $i + 1 }}</td>
                            <td><span class="badge bg-light text-dark border px-2 py-1">{{ $item->sku_code }}</span></td>
                            <td class="fw-semibold text-dark">{{ $item->name }}</td>
                            <td><span class="mat-badge mat-badge-primary">{{ $item->category->name ?? '-' }}</span></td>
                            <td>{{ $item->unit }}</td>
                            <td class="text-end">Rp {{ number_format($item->purchase_price, 0, ',', '.') }}</td>
                            <td class="text-end fw-medium text-dark">Rp {{ number_format($item->selling_price, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @if($item->stock == 0)
                                    <span class="mat-badge mat-badge-error">Habis</span>
                                @elseif($item->isLowStock())
                                    <span class="mat-badge mat-badge-warning">
                                        <i class="bi bi-exclamation-triangle"></i> {{ $item->stock }}
                                    </span>
                                @else
                                    <span class="mat-badge mat-badge-success">{{ $item->stock }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-primary me-1 rounded-pill btn-edit-item"
                                        data-id="{{ $item->id }}"
                                        data-sku="{{ $item->sku_code }}"
                                        data-name="{{ $item->name }}"
                                        data-category="{{ $item->category_id }}"
                                        data-unit="{{ $item->unit }}"
                                        data-purchase="{{ (int)$item->purchase_price }}"
                                        data-selling="{{ (int)$item->selling_price }}"
                                        data-stock="{{ $item->stock }}"
                                        data-minstock="{{ $item->min_stock }}"
                                        title="Edit Barang">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form action="{{ route('items.destroy', $item) }}" method="POST" class="d-inline" data-name="{{ $item->name }}">
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
                            <td colspan="9" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada data barang atau tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($items->hasPages())
            <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="text-muted small">
                    Menampilkan {{ $items->firstItem() }} sampai {{ $items->lastItem() }} dari {{ $items->total() }} barang
                </div>
                <div>
                    {{ $items->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

{{-- MODAL TAMBAH BARANG --}}
<div class="modal fade" id="modalTambahBarang" tabindex="-1" aria-labelledby="modalTambahBarangLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-light border-0 py-3 px-4">
                <h6 class="modal-title fw-bold text-dark d-flex align-items-center" id="modalTambahBarangLabel">
                    <i class="bi bi-plus-circle text-primary me-2 fs-5"></i> Tambah Barang Baru
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('items.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="modal_name" class="form-label fw-medium">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="modal_name" name="name" value="{{ old('name') }}" placeholder="Contoh: Beras Pandan Wangi 5kg" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="modal_sku_code" class="form-label fw-medium d-flex justify-content-between">
                                <span>Kode SKU <span class="text-danger">*</span></span>
                                <small class="text-primary fw-normal" style="font-size: 0.72rem;"><i class="bi bi-magic me-1"></i>Auto</small>
                            </label>
                            <input type="text" class="form-control @error('sku_code') is-invalid @enderror"
                                   id="modal_sku_code" name="sku_code" value="{{ old('sku_code') }}" placeholder="Otomatis singkat" required>
                            @error('sku_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="modal_category_id" class="form-label fw-medium">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="modal_category_id" name="category_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="modal_unit" class="form-label fw-medium">Satuan Kemasan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('unit') is-invalid @enderror"
                                   id="modal_unit" name="unit" value="{{ old('unit') }}" placeholder="Contoh: Karung 5kg, Kg" required>
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="modal_purchase_price" class="form-label fw-medium">Harga Beli (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('purchase_price') is-invalid @enderror"
                                   id="modal_purchase_price" name="purchase_price" value="{{ old('purchase_price', 0) }}" min="0" required>
                            @error('purchase_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="modal_selling_price" class="form-label fw-medium">Harga Jual (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('selling_price') is-invalid @enderror"
                                   id="modal_selling_price" name="selling_price" value="{{ old('selling_price', 0) }}" min="0" required>
                            @error('selling_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="modal_stock" class="form-label fw-medium">Stok Awal <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                   id="modal_stock" name="stock" value="{{ old('stock', 0) }}" min="0" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="modal_min_stock" class="form-label fw-medium">Batas Stok Minimum <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('min_stock') is-invalid @enderror"
                                   id="modal_min_stock" name="min_stock" value="{{ old('min_stock', 5) }}" min="0" required>
                            @error('min_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 py-3 px-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-mat-primary">
                        <i class="bi bi-check-lg me-1"></i> Simpan Barang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT BARANG --}}
<div class="modal fade" id="modalEditBarang" tabindex="-1" aria-labelledby="modalEditBarangLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-light border-0 py-3 px-4">
                <h6 class="modal-title fw-bold text-dark d-flex align-items-center" id="modalEditBarangLabel">
                    <i class="bi bi-pencil-square text-primary me-2 fs-5"></i> Edit Data Barang
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditItem" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_sku_code" class="form-label fw-medium">Kode SKU <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_sku_code" name="sku_code" required>
                        </div>

                        <div class="col-md-6">
                            <label for="edit_category_id" class="form-label fw-medium">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_category_id" name="category_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-8">
                            <label for="edit_name" class="form-label fw-medium">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>

                        <div class="col-md-4">
                            <label for="edit_unit" class="form-label fw-medium">Satuan Kemasan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_unit" name="unit" required>
                        </div>

                        <div class="col-md-6">
                            <label for="edit_purchase_price" class="form-label fw-medium">Harga Beli (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_purchase_price" name="purchase_price" min="0" required>
                        </div>

                        <div class="col-md-6">
                            <label for="edit_selling_price" class="form-label fw-medium">Harga Jual (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_selling_price" name="selling_price" min="0" required>
                        </div>

                        <div class="col-md-6">
                            <label for="edit_stock" class="form-label fw-medium">Stok Saat Ini <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_stock" name="stock" min="0" required>
                        </div>

                        <div class="col-md-6">
                            <label for="edit_min_stock" class="form-label fw-medium">Batas Stok Minimum <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_min_stock" name="min_stock" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 py-3 px-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-mat-primary">
                        <i class="bi bi-check-lg me-1"></i> Perbarui Barang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const editItemModal = new bootstrap.Modal(document.getElementById('modalEditBarang'));
    const formEditItem = document.getElementById('formEditItem');

    // Smart SKU Abbreviation Generator Function
    function generateSKU(name) {
        if (!name || !name.trim()) return '';

        const words = name.trim().split(/[\s\-]+/);
        let lettersPart = '';
        let numbersPart = '';

        words.forEach(word => {
            const cleaned = word.replace(/[^a-zA-Z0-9]/g, '');
            if (!cleaned) return;

            // If word contains numbers (e.g., 5kg, 10kg, 25kg, IR64, C4)
            if (/\d/.test(cleaned)) {
                if (/^[a-zA-Z]+\d+$/.test(cleaned)) {
                    lettersPart += cleaned.toUpperCase();
                } else {
                    numbersPart += (numbersPart ? '-' : '') + cleaned.toUpperCase();
                }
            } else {
                // Normal word: take first letter (e.g. Beras -> B, Pandan -> P, Wangi -> W)
                lettersPart += cleaned.charAt(0).toUpperCase();
            }
        });

        let result = lettersPart;
        if (numbersPart) {
            result += (result ? '-' : '') + numbersPart;
        }

        return result;
    }

    // Auto-generate SKU on typing in Modal Tambah Barang
    const inputName = document.getElementById('modal_name');
    const inputSku = document.getElementById('modal_sku_code');
    let isManualSku = false;

    if (inputName && inputSku) {
        inputName.addEventListener('input', function() {
            if (!isManualSku || inputSku.value.trim() === '') {
                inputSku.value = generateSKU(this.value);
            }
        });

        inputSku.addEventListener('input', function() {
            isManualSku = (this.value.trim().length > 0);
        });

        // Reset manual flag when modal opens fresh
        document.getElementById('modalTambahBarang').addEventListener('show.bs.modal', function () {
            if (!inputName.value) {
                isManualSku = false;
                inputSku.value = '';
            }
        });
    }

    document.querySelectorAll('.btn-edit-item').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            formEditItem.action = `/items/${id}`;

            document.getElementById('edit_sku_code').value = this.dataset.sku;
            document.getElementById('edit_name').value = this.dataset.name;
            document.getElementById('edit_category_id').value = this.dataset.category;
            document.getElementById('edit_unit').value = this.dataset.unit;
            document.getElementById('edit_purchase_price').value = this.dataset.purchase;
            document.getElementById('edit_selling_price').value = this.dataset.selling;
            document.getElementById('edit_stock').value = this.dataset.stock;
            document.getElementById('edit_min_stock').value = this.dataset.minstock;

            editItemModal.show();
        });
    });

    // Reopen modal if add item failed with validation errors
    @if($errors->any() && (old('sku_code') || old('name') || old('purchase_price')))
        const addModal = new bootstrap.Modal(document.getElementById('modalTambahBarang'));
        addModal.show();
    @endif
</script>
@endpush
