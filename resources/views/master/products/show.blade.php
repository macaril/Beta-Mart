@extends('layouts.app')

@section('title', 'Detail Barang - Beta Mart')
@section('page_title', 'Detail Barang')
@section('page_subtitle', $product->name)

@section('content')
<section class="card detail">
    <div class="card-head">
        <h2>{{ $product->name }}</h2>
        <a class="btn secondary" href="{{ route('products.edit', $product) }}">Edit</a>
    </div>
    <dl>
        <div><dt>Kode</dt><dd>{{ $product->code }}</dd></div>
        <div><dt>Kategori</dt><dd>{{ $product->category->name }}</dd></div>
        <div><dt>Stok Saat Ini</dt><dd>{{ $product->stock }} {{ $product->unit }}</dd></div>
        <div><dt>Status</dt><dd>{{ $product->availability }}</dd></div>
        <div><dt>Harga Beli</dt><dd>Rp{{ number_format($product->purchase_price, 0, ',', '.') }}</dd></div>
        <div><dt>Harga Jual</dt><dd>Rp{{ number_format($product->selling_price, 0, ',', '.') }}</dd></div>
    </dl>
</section>
<section class="card">
    <h2>Riwayat Keluar Masuk</h2>
    <table>
        <thead><tr><th>Tanggal</th><th>Jenis</th><th class="right">Jumlah</th><th class="right">Stok Akhir</th><th>Pengguna</th></tr></thead>
        <tbody>
        @forelse ($product->stockMovements as $movement)
            <tr>
                <td>{{ $movement->movement_date->format('d M Y') }}</td>
                <td><span class="badge {{ $movement->type }}">{{ ucfirst($movement->type) }}</span></td>
                <td class="right">{{ $movement->quantity }}</td>
                <td class="right">{{ $movement->stock_after }}</td>
                <td>{{ $movement->user?->name ?? '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="5">Belum ada transaksi.</td></tr>
        @endforelse
        </tbody>
    </table>
</section>
@endsection
