@extends('layouts.app')

@section('title', 'Laporan Persediaan')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h5 class="fw-bold mb-1 text-dark">Laporan Persediaan Barang</h5>
        <p class="text-muted mb-0" style="font-size:0.8125rem;">Rekapitulasi stok masuk, keluar, dan sisa stok dalam periode tertentu</p>
    </div>
    <a href="{{ route('reports.inventory.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
       class="btn btn-danger rounded-pill px-3 shadow-sm" target="_blank">
        <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
    </a>
</div>

{{-- Filter Tanggal Card --}}
<div class="mat-card mb-4">
    <div class="mat-card-body py-3">
        <form action="{{ route('reports.inventory') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="start_date" class="form-label fw-medium mb-1" style="font-size:0.8125rem;">Tanggal Mulai</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label fw-medium mb-1" style="font-size:0.8125rem;">Tanggal Akhir</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-mat-primary w-100">
                    <i class="bi bi-funnel me-1"></i> Filter Laporan
                </button>
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
                        <th class="text-center">Stok Masuk</th>
                        <th class="text-center">Stok Keluar</th>
                        <th class="text-center">Stok Saat Ini</th>
                        <th class="text-end">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><span class="badge bg-light text-dark border px-2 py-1">{{ $item->sku_code }}</span></td>
                            <td class="fw-semibold text-dark">{{ $item->name }}</td>
                            <td><span class="mat-badge mat-badge-primary">{{ $item->category->name ?? '-' }}</span></td>
                            <td class="text-center text-success fw-bold">{{ $item->stock_in > 0 ? '+' . $item->stock_in : '0' }}</td>
                            <td class="text-center text-danger fw-bold">{{ $item->stock_out > 0 ? '-' . $item->stock_out : '0' }}</td>
                            <td class="text-center fw-bold text-dark">{{ $item->stock }}</td>
                            <td class="text-end">
                                @if($item->stock == 0)
                                    <span class="mat-badge mat-badge-error">Habis</span>
                                @elseif($item->isLowStock())
                                    <span class="mat-badge mat-badge-warning">Menipis</span>
                                @else
                                    <span class="mat-badge mat-badge-success">Aman</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tidak ada data persediaan barang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
