@extends('layouts.app')

@section('title', 'Detail Kategori - Beta Mart')
@section('page_title', 'Detail Kategori')
@section('page_subtitle', $category->name)

@section('content')
<section class="card">
    <div class="card-head">
        <h2>{{ $category->name }}</h2>
        <a class="btn secondary" href="{{ route('categories.edit', $category) }}">Edit</a>
    </div>
    <p>{{ $category->description ?? 'Tidak ada deskripsi.' }}</p>
    <h3>Barang dalam kategori ini</h3>
    <table>
        <thead><tr><th>Kode</th><th>Nama</th><th class="right">Stok</th><th>Status</th></tr></thead>
        <tbody>
        @forelse ($category->products as $product)
            <tr>
                <td>{{ $product->code }}</td>
                <td>{{ $product->name }}</td>
                <td class="right">{{ $product->stock }} {{ $product->unit }}</td>
                <td>{{ $product->availability }}</td>
            </tr>
        @empty
            <tr><td colspan="4">Belum ada barang.</td></tr>
        @endforelse
        </tbody>
    </table>
</section>
@endsection
