@extends('layouts.app')

@section('title', ($product->exists ? 'Edit' : 'Tambah').' Barang - Beta Mart')
@section('page_title', $product->exists ? 'Edit Barang' : 'Tambah Barang')
@section('page_subtitle', 'Lengkapi data barang minimarket.')

@section('content')
<form class="card form-card" method="POST" action="{{ $product->exists ? route('products.update', $product) : route('products.store') }}">
    @csrf
    @if ($product->exists) @method('PUT') @endif
    <div class="form-grid">
        <label>Kode Barang
            <input name="code" value="{{ old('code', $product->code) }}" placeholder="BRG-019" required>
        </label>
        <label>Nama Barang
            <input name="name" value="{{ old('name', $product->name) }}" placeholder="Nama produk" required>
        </label>
        <label>Kategori
            <select name="category_id" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </label>
        <label>Satuan
            <input name="unit" value="{{ old('unit', $product->unit) }}" placeholder="pcs" required>
        </label>
        <label>Stok
            <input name="stock" type="number" min="0" value="{{ old('stock', $product->stock ?? 0) }}" required>
        </label>
        <label>Harga Beli
            <input name="purchase_price" type="number" min="0" value="{{ old('purchase_price', $product->purchase_price ?? 0) }}" required>
        </label>
        <label>Harga Jual
            <input name="selling_price" type="number" min="0" value="{{ old('selling_price', $product->selling_price ?? 0) }}" required>
        </label>
    </div>
    <div class="form-actions">
        <a class="btn secondary" href="{{ route('products.index') }}">Batal</a>
        <button class="btn primary" type="submit">Simpan</button>
    </div>
</form>
@endsection
