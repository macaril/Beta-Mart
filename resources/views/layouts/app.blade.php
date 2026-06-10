<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Beta Mart Warehouse')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
@auth
    <aside class="sidebar">
        <a class="brand" href="{{ route('dashboard') }}">
            <span>BM</span>
            <strong>Beta Mart</strong>
            <small>Warehouse System</small>
        </a>
        <nav>
            <a class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
            <a class="{{ request()->routeIs('inventory.*') ? 'active' : '' }}" href="{{ route('inventory.index') }}">Persediaan Barang</a>
            <p>Master Data</p>
            <a class="{{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">Kategori Barang</a>
            <a class="{{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">Daftar Barang</a>
            <a class="{{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">Manajemen Pengguna</a>
            <a class="{{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">Laporan</a>
        </nav>
        <form method="POST" action="{{ route('logout') }}" class="logout">
            @csrf
            <div>
                <strong>{{ auth()->user()->name }}</strong>
                <small>{{ auth()->user()->role }}</small>
            </div>
            <button type="submit">Keluar</button>
        </form>
    </aside>
@endauth

<main class="@auth app-main @else auth-main @endauth">
    @auth
        <header class="topbar">
            <div>
                <h1>@yield('page_title', 'Beta Mart')</h1>
                <span>@yield('page_subtitle', 'Sistem pergudangan minimarket')</span>
            </div>
        </header>
    @endauth

    @if (session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert danger">Periksa kembali input yang wajib diisi.</div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3"></script>
</body>
</html>
