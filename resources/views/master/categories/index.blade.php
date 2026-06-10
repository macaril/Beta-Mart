@extends('layouts.app')

@section('title', 'Kategori Barang - Beta Mart')
@section('page_title', 'Kategori Barang')
@section('page_subtitle', 'Kelola kategori untuk pengelompokan barang.')

@section('content')
<section class="card">
    <div class="card-head">
        <h2>Data Kategori</h2>
        <a class="btn primary" href="{{ route('categories.create') }}">Tambah Kategori</a>
    </div>
    <table>
        <thead><tr><th>Nama</th><th>Deskripsi</th><th class="right">Jumlah Barang</th><th class="right">Aksi</th></tr></thead>
        <tbody>
        @foreach ($categories as $category)
            <tr>
                <td><strong>{{ $category->name }}</strong></td>
                <td>{{ $category->description ?? '-' }}</td>
                <td class="right">{{ $category->products_count }}</td>
                <td class="actions">
                    <a href="{{ route('categories.show', $category) }}">Detail</a>
                    <a href="{{ route('categories.edit', $category) }}">Edit</a>
                    <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini?')">
                        @csrf @method('DELETE')
                        <button type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $categories->links() }}
</section>
@endsection
