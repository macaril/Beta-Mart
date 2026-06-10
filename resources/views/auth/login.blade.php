@extends('layouts.app')

@section('title', 'Login - Beta Mart')

@section('content')
<section class="login-page">
    <div class="login-hero">
        <span class="brand-badge">Beta Mart</span>
        <h1>Kelola stok gudang minimarket dengan rapi.</h1>
        <p>Masuk untuk memantau barang masuk, barang keluar, stok terakhir, master data, dan laporan transaksi.</p>
    </div>
    <form class="login-card" method="POST" action="{{ route('login.attempt') }}">
        @csrf
        <h2>Login Admin</h2>
        <label>Email
            <input name="email" type="email" value="{{ old('email') }}" placeholder="test@betamart.local" required autofocus>
        </label>
        <label>Kata Sandi
            <input name="password" type="password" placeholder="password" required>
        </label>
        <label class="checkbox">
            <input type="checkbox" name="remember" value="1"> Ingat saya
        </label>
        <button class="btn primary" type="submit">Masuk</button>
    </form>
</section>
@endsection
