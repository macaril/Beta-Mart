@extends('layouts.app')

@section('title', 'Daftar Barang - Beta Mart')
@section('page_title', 'Daftar Barang')
@section('page_subtitle', 'Kelola master produk dan stok awal.')

@section('content')
<section class="card">
    <div class="card-head">
        <h2>Data Barang</h2>
        <div class="toolbar">
            <form class="filters" method="GET">
                <input name="q" value="{{ request('q') }}" placeholder="Cari barang atau kode">
                <button class="btn secondary" type="submit">Cari</button>
            </form>
            <a class="btn primary" href="{{ route('products.create') }}">Tambah Barang</a>
        </div>
    </div>
    <table>
        <thead><tr><th>Kode</th><th>Nama</th><th>Kategori</th><th class="right">Stok</th><th class="right">Harga Jual</th><th>Status</th><th class="right">Aksi</th></tr></thead>
        <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->code }}</td>
                <td><strong>{{ $product->name }}</strong></td>
                <td>{{ $product->category->name }}</td>
                <td class="right">{{ $product->stock }} {{ $product->unit }}</td>
                <td class="right">Rp{{ number_format($product->selling_price, 0, ',', '.') }}</td>
                <td><span class="badge {{ $product->stock > 0 ? 'tersedia' : 'kosong' }}">{{ $product->availability }}</span></td>
                <td class="actions">
                    <a href="{{ route('products.show', $product) }}">Detail</a>
                    <a href="{{ route('products.edit', $product) }}">Edit</a>
                    <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Hapus barang ini?')">
                        @csrf @method('DELETE')
                        <button type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $products->links() }}
</section>
@endsection
