@extends('layouts.app')

@section('title', 'Tambah Transaksi Keuangan')

@section('content')

<div class="mb-4">
    <a href="{{ route('finance.index') }}" class="text-decoration-none text-muted" style="font-size:0.8125rem;">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Transaksi Keuangan
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="mat-card">
            <div class="mat-card-header">
                <h6 class="mat-card-title">
                    <i class="bi bi-cash-stack text-primary me-2"></i>Tambah Transaksi Keuangan
                </h6>
            </div>
            <div class="mat-card-body">
                <form action="{{ route('finance.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="type" class="form-label fw-medium">Tipe Transaksi <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">Pilih Tipe</option>
                            <option value="income" {{ old('type') === 'income' ? 'selected' : '' }}>💰 Pemasukan (Income)</option>
                            <option value="expense" {{ old('type') === 'expense' ? 'selected' : '' }}>💸 Pengeluaran (Expense / Beban)</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label fw-medium">Kategori Transaksi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('category') is-invalid @enderror"
                               id="category" name="category" value="{{ old('category') }}"
                               placeholder="Contoh: Penjualan, Operasional, Restock, Listrik, Gaji Pegawai" required>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="amount" class="form-label fw-medium">Jumlah Nominal (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('amount') is-invalid @enderror"
                                   id="amount" name="amount" value="{{ old('amount') }}" min="1" placeholder="0" required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="transaction_date" class="form-label fw-medium">Tanggal Transaksi <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('transaction_date') is-invalid @enderror"
                                   id="transaction_date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                            @error('transaction_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4 mt-3">
                        <label for="description" class="form-label fw-medium">Keterangan / Rincian</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3" placeholder="Deskripsi transaksi (opsional)">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-mat-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan Transaksi
                        </button>
                        <a href="{{ route('finance.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
