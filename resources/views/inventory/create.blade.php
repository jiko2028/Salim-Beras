@extends('layouts.app')

@section('title', 'Input Transaksi Stok')

@section('content')

<div class="mb-4">
    <a href="{{ route('inventory.index') }}" class="text-decoration-none text-muted" style="font-size:0.8125rem;">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Transaksi Stok
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="mat-card">
            <div class="mat-card-header">
                <h6 class="mat-card-title">
                    <i class="bi bi-arrow-left-right text-primary me-2"></i>Input Barang Masuk / Keluar
                </h6>
            </div>
            <div class="mat-card-body">
                <form action="{{ route('inventory.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="type" class="form-label fw-medium">Tipe Transaksi <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">Pilih Tipe Transaksi</option>
                            <option value="in" {{ old('type') === 'in' ? 'selected' : '' }}>📦 Barang Masuk (Restock Supplier)</option>
                            <option value="out" {{ old('type') === 'out' ? 'selected' : '' }}>📤 Barang Keluar (Penjualan / Konsumen)</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="item_id" class="form-label fw-medium">Pilih Barang Beras <span class="text-danger">*</span></label>
                        <select class="form-select @error('item_id') is-invalid @enderror" id="item_id" name="item_id" required>
                            <option value="">Pilih Barang</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}
                                        data-stock="{{ $item->stock }}">
                                    {{ $item->sku_code }} — {{ $item->name }} (Stok Saat Ini: {{ $item->stock }})
                                </option>
                            @endforeach
                        </select>
                        @error('item_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="quantity" class="form-label fw-medium">Jumlah <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                               id="quantity" name="quantity" value="{{ old('quantity') }}" min="1" placeholder="Masukkan jumlah unit / karung" required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text mt-2" id="stockInfo"></div>
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="form-label fw-medium">Catatan / Keterangan</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes" name="notes" rows="3" placeholder="Contoh: Restock dari supplier CV Tani Makmur">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-mat-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan Transaksi
                        </button>
                        <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const itemSelect = document.getElementById('item_id');
    const stockInfo = document.getElementById('stockInfo');

    if (itemSelect && stockInfo) {
        itemSelect.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const stock = selected.dataset.stock;
            if (stock !== undefined && this.value) {
                stockInfo.innerHTML = '<span class="mat-badge mat-badge-primary"><i class="bi bi-info-circle me-1"></i>Stok tersedia saat ini: <strong>' + stock + '</strong></span>';
            } else {
                stockInfo.innerHTML = '';
            }
        });
    }
</script>
@endpush
