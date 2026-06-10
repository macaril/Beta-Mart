@extends('layouts.app')

@section('title', 'Manajemen Pengguna - Beta Mart')
@section('page_title', 'Manajemen Pengguna')
@section('page_subtitle', 'Kelola akun admin, gudang, dan staff.')

@section('content')
<section class="card">
    <div class="card-head">
        <h2>Data Pengguna</h2>
        <a class="btn primary" href="{{ route('users.create') }}">Tambah Pengguna</a>
    </div>
    <table>
        <thead><tr><th>Nama</th><th>Email</th><th>Telepon</th><th>Peran</th><th>Status</th><th class="right">Aksi</th></tr></thead>
        <tbody>
        @foreach ($users as $user)
            <tr>
                <td><strong>{{ $user->name }}</strong></td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone ?? '-' }}</td>
                <td><span class="badge slate">{{ $user->role }}</span></td>
                <td><span class="badge {{ $user->is_active ? 'tersedia' : 'kosong' }}">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                <td class="actions">
                    <a href="{{ route('users.show', $user) }}">Detail</a>
                    <a href="{{ route('users.edit', $user) }}">Edit</a>
                    <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Hapus pengguna ini?')">
                        @csrf @method('DELETE')
                        <button type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $users->links() }}
</section>
@endsection
