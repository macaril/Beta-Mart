@extends('layouts.app')

@section('title', ($user->exists ? 'Edit' : 'Tambah').' Pengguna - Beta Mart')
@section('page_title', $user->exists ? 'Edit Pengguna' : 'Tambah Pengguna')
@section('page_subtitle', 'Lengkapi data akses pengguna.')

@section('content')
<form class="card form-card" method="POST" action="{{ $user->exists ? route('users.update', $user) : route('users.store') }}">
    @csrf
    @if ($user->exists) @method('PUT') @endif
    <div class="form-grid">
        <label>Nama
            <input name="name" value="{{ old('name', $user->name) }}" required>
        </label>
        <label>Email
            <input name="email" type="email" value="{{ old('email', $user->email) }}" required>
        </label>
        <label>Telepon
            <input name="phone" value="{{ old('phone', $user->phone) }}">
        </label>
        <label>Peran
            <select name="role" required>
                @foreach (['admin', 'gudang', 'staff'] as $role)
                    <option value="{{ $role }}" @selected(old('role', $user->role) === $role)>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
        </label>
        <label>Password {{ $user->exists ? '(kosongkan jika tidak diganti)' : '' }}
            <input name="password" type="password" {{ $user->exists ? '' : 'required' }}>
        </label>
        <label class="checkbox">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active ?? true))> Akun aktif
        </label>
    </div>
    <div class="form-actions">
        <a class="btn secondary" href="{{ route('users.index') }}">Batal</a>
        <button class="btn primary" type="submit">Simpan</button>
    </div>
</form>
@endsection
