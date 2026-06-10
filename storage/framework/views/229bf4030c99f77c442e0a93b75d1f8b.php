<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', 'Beta Mart Warehouse'); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
</head>
<body>
<?php if(auth()->guard()->check()): ?>
    <aside class="sidebar">
        <a class="brand" href="<?php echo e(route('dashboard')); ?>">
            <span>BM</span>
            <strong>Beta Mart</strong>
            <small>Warehouse System</small>
        </a>
        <nav>
            <a class="<?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">Dashboard</a>
            <a class="<?php echo e(request()->routeIs('inventory.*') ? 'active' : ''); ?>" href="<?php echo e(route('inventory.index')); ?>">Persediaan Barang</a>
            <p>Master Data</p>
            <a class="<?php echo e(request()->routeIs('categories.*') ? 'active' : ''); ?>" href="<?php echo e(route('categories.index')); ?>">Kategori Barang</a>
            <a class="<?php echo e(request()->routeIs('products.*') ? 'active' : ''); ?>" href="<?php echo e(route('products.index')); ?>">Daftar Barang</a>
            <a class="<?php echo e(request()->routeIs('users.*') ? 'active' : ''); ?>" href="<?php echo e(route('users.index')); ?>">Manajemen Pengguna</a>
            <a class="<?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>" href="<?php echo e(route('reports.index')); ?>">Laporan</a>
        </nav>
        <form method="POST" action="<?php echo e(route('logout')); ?>" class="logout">
            <?php echo csrf_field(); ?>
            <div>
                <strong><?php echo e(auth()->user()->name); ?></strong>
                <small><?php echo e(auth()->user()->role); ?></small>
            </div>
            <button type="submit">Keluar</button>
        </form>
    </aside>
<?php endif; ?>

<main class="<?php if(auth()->guard()->check()): ?> app-main <?php else: ?> auth-main <?php endif; ?>">
    <?php if(auth()->guard()->check()): ?>
        <header class="topbar">
            <div>
                <h1><?php echo $__env->yieldContent('page_title', 'Beta Mart'); ?></h1>
                <span><?php echo $__env->yieldContent('page_subtitle', 'Sistem pergudangan minimarket'); ?></span>
            </div>
        </header>
    <?php endif; ?>

    <?php if(session('success')): ?>
        <div class="alert success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="alert danger">Periksa kembali input yang wajib diisi.</div>
    <?php endif; ?>

    <?php echo $__env->yieldContent('content'); ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3"></script>
</body>
</html>
<?php /**PATH C:\Users\lutfi\Downloads\Beta Mart\resources\views/layouts/app.blade.php ENDPATH**/ ?>