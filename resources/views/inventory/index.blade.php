@extends('layouts.app')

@section('title', 'Persediaan Barang - Beta Mart')
@section('page_title', 'Persediaan Barang')
@section('page_subtitle', 'Catat barang masuk/keluar dan pantau stok terakhir.')

@section('content')
<section class="grid two">
    <form class="card" method="POST" action="{{ route('inventory.in') }}">
        @csrf
        <h2>Barang Masuk</h2>
        @include('inventory.movement-form', ['products' => $allProducts, 'type' => 'masuk'])
        <button class="btn success" type="submit">Simpan Barang Masuk</button>
    </form>
    <form class="card" method="POST" action="{{ route('inventory.out') }}">
        @csrf
        <h2>Barang Keluar</h2>
        @include('inventory.movement-form', ['products' => $allProducts, 'type' => 'keluar'])
        <button class="btn primary" type="submit">Simpan Barang Keluar</button>
    </form>
</section>

<section class="card">
    <div class="card-head">
        <h2>Stok Saat Ini</h2>
        <form class="filters" method="GET">
            <input name="q" value="{{ request('q') }}" placeholder="Cari kode atau barang">
            <select name="status">
                <option value="">Semua Status</option>
                <option value="tersedia" @selected(request('status') === 'tersedia')>Tersedia</option>
                <option value="rendah" @selected(request('status') === 'rendah')>Stok Rendah</option>
                <option value="kosong" @selected(request('status') === 'kosong')>Tidak Tersedia</option>
            </select>
            <button class="btn secondary" type="submit">Filter</button>
        </form>
    </div>
    <table>
        <thead><tr><th>Kode</th><th>Nama Barang</th><th>Kategori</th><th class="right">Stok Terakhir</th><th>Status</th></tr></thead>
        <tbody>
        @forelse ($products as $product)
            <tr>
                <td>{{ $product->code }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name }}</td>
                <td class="right">{{ $product->stock }} {{ $product->unit }}</td>
                <td>
                    @if ($product->stock <= 0)
                        <span class="badge kosong">Tidak Tersedia</span>
                    @elseif ($product->stock < 10)
                        <span class="badge rendah">Stok Rendah</span>
                    @else
                        <span class="badge tersedia">Tersedia</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="5">Data barang tidak ditemukan.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $products->links() }}
</section>

<section class="card">
    <h2>Riwayat Terakhir</h2>
    <table>
        <thead><tr><th>Tanggal</th><th>Jenis</th><th>Barang</th><th class="right">Jumlah</th><th>Catatan</th></tr></thead>
        <tbody>
        @foreach ($movements as $movement)
            <tr>
                <td>{{ $movement->movement_date->format('d M Y') }}</td>
                <td><span class="badge {{ $movement->type }}">{{ ucfirst($movement->type) }}</span></td>
                <td>{{ $movement->product->name }}</td>
                <td class="right">{{ $movement->quantity }} {{ $movement->product->unit }}</td>
                <td>{{ $movement->note ?? '-' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</section>
@endsection
