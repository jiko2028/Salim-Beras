<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Persediaan Barang — Salim Beras</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        thead th {
            background: #2d6a4f;
            color: #fff;
            padding: 8px 6px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: left;
        }

        tbody td {
            padding: 7px 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10.5px;
        }

        tbody tr:nth-child(even) {
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

        .badge-danger {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .badge-warning {
            background: #fffbeb;
            color: #d97706;
            border: 1px solid #fde68a;
        }

        .badge-success {
            background: #f0fdf4;
            color: #059669;
            border: 1px solid #bbf7d0;
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
        <h2>Laporan Persediaan Barang</h2>
        <p>Salam, Kabupaten Magelang</p>
    </div>

    <div class="period">
        <strong>Periode:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
        &nbsp;&nbsp;|&nbsp;&nbsp;
        <strong>Dicetak:</strong> {{ now()->format('d/m/Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width:30px;">No</th>
                <th style="width:60px;">SKU</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th class="text-center">Masuk</th>
                <th class="text-center">Keluar</th>
                <th class="text-center">Stok</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $i => $item)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $item->sku_code }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->category->name ?? '-' }}</td>
                    <td class="text-center text-success">{{ $item->stock_in > 0 ? '+' . $item->stock_in : '0' }}</td>
                    <td class="text-center text-danger">{{ $item->stock_out > 0 ? '-' . $item->stock_out : '0' }}</td>
                    <td class="text-center" style="font-weight:700;">{{ $item->stock }}</td>
                    <td class="text-center">
                        @if($item->stock == 0)
                            <span class="badge badge-danger">Habis</span>
                        @elseif($item->isLowStock())
                            <span class="badge badge-warning">Menipis</span>
                        @else
                            <span class="badge badge-success">Aman</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        &copy; {{ date('Y') }} Salim Beras — Sistem Informasi Keuangan & Persediaan | Dokumen ini digenarasi secara otomatis
    </div>

</body>
</html>
