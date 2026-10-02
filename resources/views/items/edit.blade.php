@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')

<div class="mb-4">
    <a href="{{ route('items.index') }}" class="text-decoration-none text-muted" style="font-size:0.8125rem;">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Data Barang
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="mat-card">
            <div class="mat-card-header">
                <h6 class="mat-card-title">
                    <i class="bi bi-pencil-square text-primary me-2"></i>Edit Barang
                </h6>
            </div>
            <div class="mat-card-body">
                <form action="{{ route('items.update', $item) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="sku_code" class="form-label fw-medium">Kode SKU <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('sku_code') is-invalid @enderror"
                                   id="sku_code" name="sku_code" value="{{ old('sku_code', $item->sku_code) }}" required>
                            @error('sku_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="category_id" class="form-label fw-medium">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $item->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-8">
                            <label for="name" class="form-label fw-medium">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $item->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="unit" class="form-label fw-medium">Satuan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('unit') is-invalid @enderror"
                                   id="unit" name="unit" value="{{ old('unit', $item->unit) }}" required>
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="purchase_price" class="form-label fw-medium">Harga Beli (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('purchase_price') is-invalid @enderror"
                                   id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $item->purchase_price) }}" min="0" required>
                            @error('purchase_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="selling_price" class="form-label fw-medium">Harga Jual (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('selling_price') is-invalid @enderror"
                                   id="selling_price" name="selling_price" value="{{ old('selling_price', $item->selling_price) }}" min="0" required>
                            @error('selling_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="stock" class="form-label fw-medium">Stok Saat Ini <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                   id="stock" name="stock" value="{{ old('stock', $item->stock) }}" min="0" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="min_stock" class="form-label fw-medium">Batas Stok Minimum <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('min_stock') is-invalid @enderror"
                                   id="min_stock" name="min_stock" value="{{ old('min_stock', $item->min_stock) }}" min="0" required>
                            @error('min_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-mat-primary">
                            <i class="bi bi-check-lg me-1"></i> Perbarui
                        </button>
                        <a href="{{ route('items.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
