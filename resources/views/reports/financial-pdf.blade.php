<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Arus Kas — Salim Beras</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'DejaVu Sans', Arial, sans-serif;
        }

        body {
            font-size: 11px;
            color: #1f2937;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #2d6a4f;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 18px;
            color: #1b4332;
            margin-bottom: 2px;
        }

        .header h2 {
            font-size: 14px;
            color: #2d6a4f;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .header p {
            font-size: 10px;
            color: #6b7280;
        }

        .period {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 6px;
            padding: 8px 14px;
            margin-bottom: 16px;
            font-size: 11px;
        }

        .period strong {
            color: #1b4332;
        }

        .summary-box {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .summary-box td {
            padding: 10px 14px;
            border: 1px solid #e5e7eb;
            width: 33.33%;
            text-align: center;
        }

        .summary-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .summary-value {
            font-size: 14px;
            font-weight: 700;
        }

        .summary-income {
            background: #f0fdf4;
        }

        .summary-income .summary-value {
            color: #059669;
        }

        .summary-expense {
            background: #fef2f2;
        }

        .summary-expense .summary-value {
            color: #dc2626;
        }

        .summary-balance {
            background: #eff6ff;
        }

        .summary-balance .summary-value {
            color: #1b4332;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.data-table thead th {
            background: #2d6a4f;
            color: #fff;
            padding: 8px 6px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: left;
        }

        table.data-table tbody td {
            padding: 7px 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10.5px;
        }

        table.data-table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-success {
            color: #059669;
            font-weight: 600;
        }

        .text-danger {
            color: #dc2626;
            font-weight: 600;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: 600;
        }

        .badge-income {
            background: #f0fdf4;
            color: #059669;
            border: 1px solid #bbf7d0;
        }

        .badge-expense {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>SALIM BERAS</h1>
        <h2>Laporan Arus Kas</h2>
        <p>Salam, Kabupaten Magelang</p>
    </div>

    <div class="period">
        <strong>Periode:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
        &nbsp;&nbsp;|&nbsp;&nbsp;
        <strong>Dicetak:</strong> {{ now()->format('d/m/Y H:i') }}
    </div>

    {{-- Ringkasan --}}
    <table class="summary-box">
        <tr>
            <td class="summary-income">
                <div class="summary-label">Total Pemasukan</div>
                <div class="summary-value">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
            </td>
            <td class="summary-expense">
                <div class="summary-label">Total Pengeluaran</div>
                <div class="summary-value">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
            </td>
            <td class="summary-balance">
                <div class="summary-label">Kas Bersih ({{ $netBalance >= 0 ? 'Surplus' : 'Defisit' }})</div>
                <div class="summary-value">Rp {{ number_format(abs($netBalance), 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    {{-- Detail Transaksi --}}
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width:30px;">No</th>
                <th style="width:70px;">Tanggal</th>
                <th style="width:70px;">Tipe</th>
                <th>Kategori</th>
                <th class="text-right">Jumlah (Rp)</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $i => $trx)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $trx->transaction_date->format('d/m/Y') }}</td>
                    <td>
                        @if($trx->type === 'income')
                            <span class="badge badge-income">Pemasukan</span>
                        @else
                            <span class="badge badge-expense">Pengeluaran</span>
                        @endif
                    </td>
                    <td>{{ $trx->category }}</td>
                    <td class="text-right {{ $trx->type === 'income' ? 'text-success' : 'text-danger' }}">
                        {{ $trx->type === 'income' ? '+' : '-' }} {{ number_format($trx->amount, 0, ',', '.') }}
                    </td>
                    <td>{{ $trx->description ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding:20px; color:#9ca3af;">Tidak ada transaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        &copy; {{ date('Y') }} Salim Beras — Sistem Informasi Keuangan & Persediaan | Dokumen ini digenarasi secara otomatis
    </div>

</body>
</html>
