@extends('layouts.app')

@section('title', 'Transaksi Stok')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h5 class="fw-bold mb-1 text-dark">Transaksi Stok</h5>
        <p class="text-muted mb-0" style="font-size:0.8125rem;">Riwayat pencatatan barang masuk (restock) & keluar (penjualan)</p>
    </div>
    <button type="button" class="btn btn-mat-primary" data-bs-toggle="modal" data-bs-target="#modalInputStok">
        <i class="bi bi-plus-lg me-1"></i> Input Transaksi
    </button>
</div>

{{-- Filter Card --}}
<div class="mat-card mb-4">
    <div class="mat-card-body py-3">
        <form action="{{ route('inventory.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3 col-sm-6">
                <label for="start_date" class="form-label fw-medium mb-1" style="font-size:0.8125rem;">Tanggal Mulai</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3 col-sm-6">
                <label for="end_date" class="form-label fw-medium mb-1" style="font-size:0.8125rem;">Tanggal Akhir</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-2 col-sm-6">
                <label for="type" class="form-label fw-medium mb-1" style="font-size:0.8125rem;">Tipe Transaksi</label>
                <select class="form-select" id="type" name="type">
                    <option value="">Semua Tipe</option>
                    <option value="in" {{ request('type') === 'in' ? 'selected' : '' }}>📦 Masuk</option>
                    <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>📤 Keluar</option>
                </select>
            </div>
            <div class="col-md-2 col-sm-6">
                <label for="item_id" class="form-label fw-medium mb-1" style="font-size:0.8125rem;">Pilih Barang</label>
                <select class="form-select" id="item_id" name="item_id">
                    <option value="">Semua Barang</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 col-sm-12 d-flex gap-2">
                <button type="submit" class="btn btn-mat-primary w-100">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                @if(request()->hasAny(['start_date', 'end_date', 'type', 'item_id']) && (request('start_date') || request('end_date') || request('type') || request('item_id')))
                    <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
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
                        <th>Tanggal</th>
                        <th>Tipe</th>
                        <th>Barang</th>
                        <th class="text-center">Jumlah</th>
                        <th>Catatan</th>
                        <th>Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $i => $trx)
                        <tr>
                            <td>{{ $transactions->firstItem() ? $transactions->firstItem() + $i : $i + 1 }}</td>
                            <td><small class="text-muted">{{ $trx->created_at->format('d/m/Y H:i') }}</small></td>
                            <td>
                                @if($trx->type === 'in')
                                    <span class="mat-badge mat-badge-success">
                                        <i class="bi bi-arrow-down-circle"></i> Masuk
                                    </span>
                                @else
                                    <span class="mat-badge mat-badge-error">
                                        <i class="bi bi-arrow-up-circle"></i> Keluar
                                    </span>
                                @endif
                            </td>
                            <td class="fw-semibold text-dark">{{ $trx->item->name ?? '-' }}</td>
                            <td class="text-center fw-bold text-dark">{{ $trx->quantity }}</td>
                            <td class="text-muted">{{ $trx->notes ?? '-' }}</td>
                            <td><small class="text-secondary">{{ $trx->user->name ?? '-' }}</small></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tidak ada transaksi stok yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="text-muted small">
                    Menampilkan {{ $transactions->firstItem() }} sampai {{ $transactions->lastItem() }} dari {{ $transactions->total() }} transaksi
                </div>
                <div>
                    {{ $transactions->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

{{-- MODAL INPUT TRANSAKSI STOK --}}
<div class="modal fade" id="modalInputStok" tabindex="-1" aria-labelledby="modalInputStokLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-light border-0 py-3 px-4">
                <h6 class="modal-title fw-bold text-dark d-flex align-items-center" id="modalInputStokLabel">
                    <i class="bi bi-arrow-left-right text-primary me-2 fs-5"></i> Input Transaksi Stok
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('inventory.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="modal_type" class="form-label fw-medium">Tipe Transaksi <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="modal_type" name="type" required>
                            <option value="">Pilih Tipe</option>
                            <option value="in" {{ old('type') === 'in' ? 'selected' : '' }}>📦 Barang Masuk (Restock Supplier)</option>
                            <option value="out" {{ old('type') === 'out' ? 'selected' : '' }}>📤 Barang Keluar (Penjualan / Konsumen)</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="modal_item_id" class="form-label fw-medium">Pilih Barang Beras <span class="text-danger">*</span></label>
                        <select class="form-select @error('item_id') is-invalid @enderror" id="modal_item_id" name="item_id" required>
                            <option value="">Pilih Barang</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}
                                        data-stock="{{ $item->stock }}">
                                    {{ $item->sku_code }} — {{ $item->name }} (Sisa Stok: {{ $item->stock }})
                                </option>
                            @endforeach
                        </select>
                        @error('item_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="modal_quantity" class="form-label fw-medium">Jumlah <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                               id="modal_quantity" name="quantity" value="{{ old('quantity') }}" min="1" placeholder="Masukkan jumlah unit / karung" required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text mt-2" id="modalStockInfo"></div>
                    </div>

                    <div class="mb-2">
                        <label for="modal_notes" class="form-label fw-medium">Catatan / Keterangan</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="modal_notes" name="notes" rows="2" placeholder="Contoh: Restock dari supplier / Penjualan ke toko...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 py-3 px-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-mat-primary">
                        <i class="bi bi-check-lg me-1"></i> Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const itemSelectModal = document.getElementById('modal_item_id');
    const stockInfoModal = document.getElementById('modalStockInfo');

    if (itemSelectModal && stockInfoModal) {
        itemSelectModal.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const stock = selected.dataset.stock;
            if (stock !== undefined && this.value) {
                stockInfoModal.innerHTML = '<span class="mat-badge mat-badge-primary"><i class="bi bi-info-circle me-1"></i>Stok saat ini: <strong>' + stock + '</strong></span>';
            } else {
                stockInfoModal.innerHTML = '';
            }
        });
    }

    // If validation error occurs on submit, re-open modal automatically
    @if($errors->any() && (old('item_id') || old('type') || old('quantity')))
        const inputModal = new bootstrap.Modal(document.getElementById('modalInputStok'));
        inputModal.show();
    @endif
</script>
@endpush
