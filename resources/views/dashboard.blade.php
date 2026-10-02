@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- Alert Stok Menipis --}}
@if($lowStockItems->count() > 0)
    <div class="alert border-0 shadow-sm mb-4" style="border-radius: 12px; background-color: #fef6e6; color: #aa7000; border-left: 5px solid #f4a100 !important;">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-warning"></i>
                <div>
                    <h6 class="fw-bold mb-0">Peringatan Stok Beras!</h6>
                    <small>Terdapat <strong>{{ $lowStockItems->count() }}</strong> barang dengan persediaan mendekati atau di bawah batas minimum.</small>
                </div>
            </div>
            <a href="{{ route('items.index') }}" class="btn btn-sm btn-warning text-white fw-medium px-3 rounded-pill">
                Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
@endif

{{-- Materially ReportCards (6 Widgets) --}}
<div class="row g-4 mb-4">
    {{-- Jumlah Barang --}}
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="mat-report-card mat-card-primary">
            <div class="mat-report-body">
                <div>
                    <div class="mat-report-val">{{ number_format($totalItems) }}</div>
                    <div class="mat-report-label">Jumlah Barang</div>
                </div>
                <div class="mat-report-icon">
                    <i class="bi bi-box2"></i>
                </div>
            </div>
            <div class="mat-report-footer">
                <span>Total Item Terdaftar</span>
                <i class="bi bi-arrow-up-right"></i>
            </div>
        </div>
    </div>

    {{-- Total Stok --}}
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="mat-report-card mat-card-info">
            <div class="mat-report-body">
                <div>
                    <div class="mat-report-val">{{ number_format($totalStock) }}</div>
                    <div class="mat-report-label">Total Stok</div>
                </div>
                <div class="mat-report-icon">
                    <i class="bi bi-archive"></i>
                </div>
            </div>
            <div class="mat-report-footer">
                <span>Unit / Karung Fisik</span>
                <i class="bi bi-boxes"></i>
            </div>
        </div>
    </div>

    {{-- Transaksi Penjualan Hari Ini --}}
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="mat-report-card mat-card-warning">
            <div class="mat-report-body">
                <div>
                    <div class="mat-report-val">{{ $todaySalesCount }}</div>
                    <div class="mat-report-label">Penjualan Hari Ini</div>
                </div>
                <div class="mat-report-icon">
                    <i class="bi bi-cart-check"></i>
                </div>
            </div>
            <div class="mat-report-footer">
                <span>Transaksi Keluar</span>
                <i class="bi bi-receipt"></i>
            </div>
        </div>
    </div>

    {{-- Pemasukan Hari Ini --}}
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="mat-report-card mat-card-success">
            <div class="mat-report-body">
                <div>
                    <div class="mat-report-val" style="font-size: 1.25rem;">Rp {{ number_format($todayIncome, 0, ',', '.') }}</div>
                    <div class="mat-report-label">Pemasukan Hari Ini</div>
                </div>
                <div class="mat-report-icon">
                    <i class="bi bi-arrow-down-left-circle"></i>
                </div>
            </div>
            <div class="mat-report-footer">
                <span>Total Penjualan Hari Ini</span>
                <i class="bi bi-cash-stack"></i>
            </div>
        </div>
    </div>

    {{-- Pengeluaran Hari Ini --}}
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="mat-report-card mat-card-error">
            <div class="mat-report-body">
                <div>
                    <div class="mat-report-val" style="font-size: 1.25rem;">Rp {{ number_format($todayExpense, 0, ',', '.') }}</div>
                    <div class="mat-report-label">Pengeluaran Hari Ini</div>
                </div>
                <div class="mat-report-icon">
                    <i class="bi bi-arrow-up-right-circle"></i>
                </div>
            </div>
            <div class="mat-report-footer">
                <span>Biaya Restock & Operasional</span>
                <i class="bi bi-credit-card"></i>
            </div>
        </div>
    </div>

    {{-- Kondisi Keuangan --}}
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="mat-report-card {{ $netBalance >= 0 ? 'mat-card-success' : 'mat-card-error' }}">
            <div class="mat-report-body">
                <div>
                    <div class="mat-report-val" style="font-size: 1.25rem;">Rp {{ number_format(abs($netBalance), 0, ',', '.') }}</div>
                    <div class="mat-report-label">Kondisi Keuangan</div>
                </div>
                <div class="mat-report-icon">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
            <div class="mat-report-footer">
                <span>Saldo Bersih ({{ $netBalance >= 0 ? 'Surplus' : 'Defisit' }})</span>
                <i class="bi {{ $netBalance >= 0 ? 'bi-graph-up-arrow' : 'bi-graph-down-arrow' }}"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Barang Stok Menipis --}}
    <div class="col-lg-5">
        <div class="mat-card h-100">
            <div class="mat-card-header">
                <h6 class="mat-card-title">
                    <i class="bi bi-exclamation-octagon text-warning me-2"></i>Persediaan Stok Menipis
                </h6>
                <span class="mat-badge mat-badge-warning">{{ $lowStockItems->count() }} Item</span>
            </div>
            <div class="mat-card-body p-0">
                @if($lowStockItems->count() > 0)
                    <div class="table-responsive">
                        <table class="mat-table">
                            <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th class="text-center">Sisa</th>
                                    <th class="text-center">Min</th>
                                    <th class="text-end">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockItems as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $item->name }}</div>
                                            <small class="text-muted">{{ $item->category->name ?? '-' }} ({{ $item->unit }})</small>
                                        </td>
                                        <td class="text-center fw-bold {{ $item->stock == 0 ? 'text-danger' : 'text-warning' }}">
                                            {{ $item->stock }}
                                        </td>
                                        <td class="text-center text-muted">{{ $item->min_stock }}</td>
                                        <td class="text-end">
                                            @if($item->stock == 0)
                                                <span class="mat-badge mat-badge-error">Habis</span>
                                            @else
                                                <span class="mat-badge mat-badge-warning">Menipis</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-check-circle fs-1 text-success d-block mb-2"></i>
                        <p class="mb-0 fw-medium">Semua stok barang dalam kondisi aman.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Aktivitas Terakhir --}}
    <div class="col-lg-7">
        <div class="mat-card h-100">
            <div class="mat-card-header">
                <h6 class="mat-card-title">
                    <i class="bi bi-clock-history text-primary me-2"></i>Riwayat Aktivitas Terakhir
                </h6>
                <a href="{{ route('inventory.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1">
                    Lihat Semua
                </a>
            </div>
            <div class="mat-card-body p-0">
                @if($recentTransactions->count() > 0)
                    <div class="table-responsive">
                        <table class="mat-table">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Tipe</th>
                                    <th>Barang</th>
                                    <th class="text-center">Qty</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $trx)
                                    <tr>
                                        <td>
                                            <small class="text-muted">{{ $trx->created_at->format('d M, H:i') }}</small>
                                        </td>
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
                                        <td class="fw-medium">{{ $trx->item->name ?? '-' }}</td>
                                        <td class="text-center fw-bold">{{ $trx->quantity }}</td>
                                        <td><small class="text-muted">{{ Str::limit($trx->notes, 28) ?? '-' }}</small></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        <p class="mb-0">Belum ada aktivitas transaksi persediaan.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
