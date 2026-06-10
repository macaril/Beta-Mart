@extends('layouts.app')

@section('title', 'Laporan - Beta Mart')
@section('page_title', 'Laporan')
@section('page_subtitle', 'Informasi keluar masuk barang dengan filter tanggal.')

@section('content')
<form class="card filters report-filter" method="GET">
    <label>Dari Tanggal
        <input name="from" type="date" value="{{ $from }}">
    </label>
    <label>Sampai Tanggal
        <input name="to" type="date" value="{{ $to }}">
    </label>
    <label>Jenis
        <select name="type">
            <option value="all" @selected($type === 'all')>Semua</option>
            <option value="masuk" @selected($type === 'masuk')>Masuk</option>
            <option value="keluar" @selected($type === 'keluar')>Keluar</option>
        </select>
    </label>
    <button class="btn primary" type="submit">Terapkan Filter</button>
    <a class="btn secondary" href="{{ route('reports.index') }}">Reset</a>
    <a class="btn secondary" href="{{ route('reports.exportPDF', ['from' => $from, 'to' => $to, 'type' => $type]) }}" target="_blank">📥 Download PDF</a>
</form>

<section class="stats">
    <x-stat label="Total Masuk" :value="number_format($totalIn, 0, ',', '.')" tone="green" />
    <x-stat label="Total Keluar" :value="number_format($totalOut, 0, ',', '.')" tone="amber" />
    <x-stat label="Jumlah Transaksi" :value="$transactionCount" tone="blue" />
    <x-stat label="Perubahan Bersih" :value="number_format($totalIn - $totalOut, 0, ',', '.')" tone="slate" />
</section>

<section class="card">
    <h2>Rincian Transaksi</h2>
    <table>
        <thead><tr><th>Tanggal</th><th>Jenis</th><th>Barang</th><th>Kategori</th><th class="right">Jumlah</th><th>Stok</th><th>Pengguna</th><th>Keterangan</th></tr></thead>
        <tbody>
        @forelse ($movements as $movement)
            <tr>
                <td>{{ $movement->movement_date->format('d M Y') }}</td>
                <td><span class="badge {{ $movement->type }}">{{ ucfirst($movement->type) }}</span></td>
                <td>{{ $movement->product->name }}</td>
                <td>{{ $movement->product->category->name }}</td>
                <td class="right">{{ $movement->type === 'masuk' ? '+' : '-' }}{{ $movement->quantity }} {{ $movement->product->unit }}</td>
                <td>{{ $movement->stock_before }} -> {{ $movement->stock_after }}</td>
                <td>{{ $movement->user?->name ?? '-' }}</td>
                <td>{{ $movement->note ?? '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="8">Tidak ada transaksi pada rentang tanggal ini.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $movements->links() }}
</section>
@endsection
