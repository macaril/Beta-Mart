<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('admin.guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('admin.auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/persediaan', [StockMovementController::class, 'index'])->name('inventory.index');
    Route::post('/persediaan/masuk', [StockMovementController::class, 'storeIn'])->name('inventory.in');
    Route::post('/persediaan/keluar', [StockMovementController::class, 'storeOut'])->name('inventory.out');

    Route::resource('master/kategori', CategoryController::class)
        ->names('categories')
        ->parameters(['kategori' => 'category']);
    Route::resource('master/barang', ProductController::class)
        ->names('products')
        ->parameters(['barang' => 'product']);
    Route::resource('master/pengguna', UserController::class)
        ->names('users')
        ->parameters(['pengguna' => 'user']);

    Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/laporan/export-pdf', [ReportController::class, 'exportPDF'])->name('reports.exportPDF');
});
