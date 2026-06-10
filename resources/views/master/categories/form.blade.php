@extends('layouts.app')

@section('title', ($category->exists ? 'Edit' : 'Tambah').' Kategori - Beta Mart')
@section('page_title', $category->exists ? 'Edit Kategori' : 'Tambah Kategori')
@section('page_subtitle', 'Lengkapi data kategori barang.')

@section('content')
<form class="card form-card" method="POST" action="{{ $category->exists ? route('categories.update', $category) : route('categories.store') }}">
    @csrf
    @if ($category->exists) @method('PUT') @endif
    <label>Nama Kategori
        <input name="name" value="{{ old('name', $category->name) }}" required>
    </label>
    <label>Deskripsi
        <textarea name="description" rows="4">{{ old('description', $category->description) }}</textarea>
    </label>
    <div class="form-actions">
        <a class="btn secondary" href="{{ route('categories.index') }}">Batal</a>
        <button class="btn primary" type="submit">Simpan</button>
    </div>
</form>
@endsection
