@extends('layouts.app')

@section('title', 'Detail Pengguna - Beta Mart')
@section('page_title', 'Detail Pengguna')
@section('page_subtitle', $user->name)

@section('content')
<section class="card detail">
    <div class="card-head">
        <h2>{{ $user->name }}</h2>
        <a class="btn secondary" href="{{ route('users.edit', $user) }}">Edit</a>
    </div>
    <dl>
        <div><dt>Email</dt><dd>{{ $user->email }}</dd></div>
        <div><dt>Telepon</dt><dd>{{ $user->phone ?? '-' }}</dd></div>
        <div><dt>Peran</dt><dd>{{ ucfirst($user->role) }}</dd></div>
        <div><dt>Status</dt><dd>{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</dd></div>
    </dl>
</section>
@endsection
