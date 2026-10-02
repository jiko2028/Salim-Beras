@extends('layouts.app')

@section('title', 'Transaksi Keuangan')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h5 class="fw-bold mb-1 text-dark">Transaksi Keuangan</h5>
        <p class="text-muted mb-0" style="font-size:0.8125rem;">Pencatatan arus kas pemasukan & pengeluaran operasional</p>
    </div>
    <button type="button" class="btn btn-mat-primary" data-bs-toggle="modal" data-bs-target="#modalTambahKeuangan">
        <i class="bi bi-plus-lg me-1"></i> Tambah Transaksi
    </button>
</div>

{{-- Ringkasan Keuangan (Materially ReportCards) --}}
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="mat-report-card mat-card-success">
            <div class="mat-report-body">
                <div>
                    <div class="mat-report-val" style="font-size: 1.4rem;">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
                    <div class="mat-report-label">Total Pemasukan</div>
                </div>
                <div class="mat-report-icon">
                    <i class="bi bi-arrow-down-left-circle"></i>
                </div>
            </div>
            <div class="mat-report-footer">
                <span>Arus Kas Masuk</span>
                <i class="bi bi-cash-stack"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="mat-report-card mat-card-error">
            <div class="mat-report-body">
                <div>
                    <div class="mat-report-val" style="font-size: 1.4rem;">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
                    <div class="mat-report-label">Total Pengeluaran</div>
                </div>
                <div class="mat-report-icon">
                    <i class="bi bi-arrow-up-right-circle"></i>
                </div>
            </div>
            <div class="mat-report-footer">
                <span>Arus Kas Keluar</span>
                <i class="bi bi-credit-card"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="mat-report-card {{ $netBalance >= 0 ? 'mat-card-success' : 'mat-card-error' }}">
            <div class="mat-report-body">
                <div>
                    <div class="mat-report-val" style="font-size: 1.4rem;">Rp {{ number_format(abs($netBalance), 0, ',', '.') }}</div>
                    <div class="mat-report-label">Saldo Kas Bersih</div>
                </div>
                <div class="mat-report-icon">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
            <div class="mat-report-footer">
                <span>Kondisi Kas ({{ $netBalance >= 0 ? 'Surplus' : 'Defisit' }})</span>
                <i class="bi {{ $netBalance >= 0 ? 'bi-graph-up-arrow' : 'bi-graph-down-arrow' }}"></i>
            </div>
        </div>
    </div>
</div>

{{-- Filter Card --}}
<div class="mat-card mb-4">
    <div class="mat-card-body py-3">
        <form action="{{ route('finance.index') }}" method="GET" class="row g-3 align-items-end">
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
                    <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>💰 Pemasukan</option>
                    <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>💸 Pengeluaran</option>
                </select>
            </div>
            <div class="col-md-2 col-sm-6">
                <label for="category" class="form-label fw-medium mb-1" style="font-size:0.8125rem;">Kategori</label>
                <input type="text" class="form-control" id="category" name="category" value="{{ request('category') }}" placeholder="Cari kategori...">
            </div>
            <div class="col-md-2 col-sm-12 d-flex gap-2">
                <button type="submit" class="btn btn-mat-primary w-100">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                @if(request()->hasAny(['start_date', 'end_date', 'type', 'category']) && (request('start_date') || request('end_date') || request('type') || request('category')))
                    <a href="{{ route('finance.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
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
                        <th>Kategori</th>
                        <th class="text-end">Jumlah</th>
                        <th>Keterangan</th>
                        <th width="60" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $i => $trx)
                        <tr>
                            <td>{{ $transactions->firstItem() ? $transactions->firstItem() + $i : $i + 1 }}</td>
                            <td><small class="text-muted">{{ $trx->transaction_date->format('d/m/Y') }}</small></td>
                            <td>
                                @if($trx->type === 'income')
                                    <span class="mat-badge mat-badge-success">Pemasukan</span>
                                @else
                                    <span class="mat-badge mat-badge-error">Pengeluaran</span>
                                @endif
                            </td>
                            <td class="fw-semibold text-dark">{{ $trx->category }}</td>
                            <td class="text-end fw-bold {{ $trx->type === 'income' ? 'text-success' : 'text-danger' }}">
                                {{ $trx->type === 'income' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                            </td>
                            <td class="text-muted">{{ $trx->description ?? '-' }}</td>
                            <td class="text-center">
                                <form action="{{ route('finance.destroy', $trx) }}" method="POST" class="d-inline" data-name="Transaksi {{ $trx->category }} (Rp {{ number_format($trx->amount, 0, ',', '.') }})">
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
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tidak ada transaksi keuangan yang sesuai dengan filter.
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

{{-- MODAL TAMBAH TRANSAKSI KEUANGAN --}}
<div class="modal fade" id="modalTambahKeuangan" tabindex="-1" aria-labelledby="modalTambahKeuanganLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-light border-0 py-3 px-4">
                <h6 class="modal-title fw-bold text-dark d-flex align-items-center" id="modalTambahKeuanganLabel">
                    <i class="bi bi-cash-stack text-primary me-2 fs-5"></i> Tambah Transaksi Keuangan
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('finance.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="modal_fin_type" class="form-label fw-medium">Tipe Transaksi <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="modal_fin_type" name="type" required>
                            <option value="">Pilih Tipe</option>
                            <option value="income" {{ old('type') === 'income' ? 'selected' : '' }}>💰 Pemasukan (Income)</option>
                            <option value="expense" {{ old('type') === 'expense' ? 'selected' : '' }}>💸 Pengeluaran (Expense / Beban)</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="modal_fin_category" class="form-label fw-medium">Kategori Transaksi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('category') is-invalid @enderror"
                               id="modal_fin_category" name="category" value="{{ old('category') }}"
                               placeholder="Contoh: Penjualan, Operasional, Restock, Listrik, Gaji" required>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="modal_fin_amount" class="form-label fw-medium">Jumlah (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('amount') is-invalid @enderror"
                                   id="modal_fin_amount" name="amount" value="{{ old('amount') }}" min="1" placeholder="0" required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="modal_fin_date" class="form-label fw-medium">Tanggal Transaksi <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('transaction_date') is-invalid @enderror"
                                   id="modal_fin_date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                            @error('transaction_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-2 mt-3">
                        <label for="modal_fin_desc" class="form-label fw-medium">Keterangan / Rincian</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="modal_fin_desc" name="description" rows="2" placeholder="Deskripsi transaksi (opsional)">{{ old('description') }}</textarea>
                        @error('description')
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
    // Reopen modal if validation errors exist
    @if($errors->any() && (old('amount') || old('category') || old('type')))
        const finModal = new bootstrap.Modal(document.getElementById('modalTambahKeuangan'));
        finModal.show();
    @endif
</script>
@endpush
