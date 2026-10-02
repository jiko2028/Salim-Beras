@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h5 class="fw-bold mb-1 text-dark">Laporan Arus Kas</h5>
        <p class="text-muted mb-0" style="font-size:0.8125rem;">Rekapitulasi pemasukan, pengeluaran, dan saldo kas bersih usaha</p>
    </div>
    <a href="{{ route('reports.financial.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
       class="btn btn-danger rounded-pill px-3 shadow-sm" target="_blank">
        <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
    </a>
</div>

{{-- Filter Tanggal Card --}}
<div class="mat-card mb-4">
    <div class="mat-card-body py-3">
        <form action="{{ route('reports.financial') }}" method="GET" class="row g-3 align-items-end">
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

{{-- Ringkasan Laporan (Materially ReportCards) --}}
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="mat-report-card mat-card-success">
            <div class="mat-report-body">
                <div>
                    <div class="mat-report-val" style="font-size: 1.4rem;">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
                    <div class="mat-report-label">Total Pemasukan Periode Ini</div>
                </div>
                <div class="mat-report-icon">
                    <i class="bi bi-arrow-down-left-circle"></i>
                </div>
            </div>
            <div class="mat-report-footer">
                <span>Total Pemasukan</span>
                <i class="bi bi-cash-stack"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="mat-report-card mat-card-error">
            <div class="mat-report-body">
                <div>
                    <div class="mat-report-val" style="font-size: 1.4rem;">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
                    <div class="mat-report-label">Total Pengeluaran Periode Ini</div>
                </div>
                <div class="mat-report-icon">
                    <i class="bi bi-arrow-up-right-circle"></i>
                </div>
            </div>
            <div class="mat-report-footer">
                <span>Total Pengeluaran</span>
                <i class="bi bi-credit-card"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="mat-report-card {{ $netBalance >= 0 ? 'mat-card-success' : 'mat-card-error' }}">
            <div class="mat-report-body">
                <div>
                    <div class="mat-report-val" style="font-size: 1.4rem;">Rp {{ number_format(abs($netBalance), 0, ',', '.') }}</div>
                    <div class="mat-report-label">Kas Bersih Periode Ini</div>
                </div>
                <div class="mat-report-icon">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
            <div class="mat-report-footer">
                <span>Status Kas ({{ $netBalance >= 0 ? 'Surplus' : 'Defisit' }})</span>
                <i class="bi {{ $netBalance >= 0 ? 'bi-graph-up-arrow' : 'bi-graph-down-arrow' }}"></i>
            </div>
        </div>
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
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $i => $trx)
                        <tr>
                            <td>{{ $i + 1 }}</td>
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tidak ada transaksi keuangan pada periode tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
