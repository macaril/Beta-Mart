<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pergerakan Stok</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            color: #172033;
            font-size: 10pt;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #172033;
            padding-bottom: 15px;
        }
        .header h2 {
            margin-bottom: 5px;
            font-size: 16pt;
        }
        .header p {
            color: #687386;
            font-size: 9pt;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        .stat-box {
            border: 1px solid #d1d5db;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
        }
        .stat-label {
            color: #687386;
            font-size: 8pt;
            margin-bottom: 3px;
        }
        .stat-value {
            font-weight: bold;
            font-size: 11pt;
        }
        .stat-in .stat-value {
            color: #16a34a;
        }
        .stat-out .stat-value {
            color: #ea580c;
        }
        .stat-count .stat-value {
            color: #2563eb;
        }
        .stat-net .stat-value {
            color: #172033;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        thead {
            background-color: #f3f4f6;
            border-bottom: 2px solid #d1d5db;
        }
        th {
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 9pt;
        }
        td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            font-size: 9pt;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .type-masuk {
            color: #16a34a;
            font-weight: bold;
        }
        .type-keluar {
            color: #ea580c;
            font-weight: bold;
        }
        .qty-in {
            color: #16a34a;
            font-weight: bold;
        }
        .qty-out {
            color: #ea580c;
            font-weight: bold;
        }
        .txt-right {
            text-align: right;
        }
        .txt-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
            text-align: center;
            font-size: 8pt;
            color: #999;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Beta Mart - Laporan Pergerakan Stok</h2>
        <p>Periode: {{ \Carbon\Carbon::parse($from)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($to)->format('d M Y') }}</p>
        @if($type !== 'all')
            <p>Jenis: <strong>{{ ucfirst($type) }}</strong></p>
        @endif
    </div>

    <div class="stats">
        <div class="stat-box stat-in">
            <div class="stat-label">Total Masuk</div>
            <div class="stat-value">{{ number_format($totalIn, 0, ',', '.') }}</div>
            <div class="stat-label">unit</div>
        </div>
        <div class="stat-box stat-out">
            <div class="stat-label">Total Keluar</div>
            <div class="stat-value">{{ number_format($totalOut, 0, ',', '.') }}</div>
            <div class="stat-label">unit</div>
        </div>
        <div class="stat-box stat-count">
            <div class="stat-label">Total Transaksi</div>
            <div class="stat-value">{{ $transactionCount }}</div>
            <div class="stat-label">transaksi</div>
        </div>
        <div class="stat-box stat-net">
            <div class="stat-label">Perubahan Bersih</div>
            <div class="stat-value">{{ number_format($totalIn - $totalOut, 0, ',', '.') }}</div>
            <div class="stat-label">unit</div>
        </div>
    </div>

    <h3 style="margin-bottom: 10px; font-size: 11pt;">Rincian Transaksi</h3>

    @if($movements->isEmpty())
        <div class="no-data">
            Tidak ada transaksi pada rentang tanggal ini.
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">Tanggal</th>
                    <th style="width: 8%;">Jenis</th>
                    <th style="width: 25%;">Barang</th>
                    <th style="width: 15%;">Kategori</th>
                    <th style="width: 10%; text-align: right;">Jumlah</th>
                    <th style="width: 15%;">Stok</th>
                    <th style="width: 12%;">Pengguna</th>
                    <th style="width: 20%;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($movements as $movement)
                    <tr>
                        <td class="txt-center">{{ $movement->movement_date->format('d M Y') }}</td>
                        <td class="txt-center">
                            <span class="@if($movement->type === 'masuk') type-masuk @else type-keluar @endif">
                                {{ $movement->type === 'masuk' ? '↓ Masuk' : '↑ Keluar' }}
                            </span>
                        </td>
                        <td>{{ $movement->product->name }}</td>
                        <td>{{ $movement->product->category->name }}</td>
                        <td class="txt-right @if($movement->type === 'masuk') qty-in @else qty-out @endif">
                            {{ $movement->type === 'masuk' ? '+' : '−' }}{{ $movement->quantity }} {{ $movement->product->unit }}
                        </td>
                        <td class="txt-center">{{ $movement->stock_before }} → {{ $movement->stock_after }}</td>
                        <td>{{ $movement->user?->name ?? '-' }}</td>
                        <td>{{ $movement->note ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        Laporan ini digenerate otomatis oleh sistem Beta Mart pada {{ now()->format('d M Y H:i:s') }}
    </div>
</body>
</html>
