<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\LaporanController;

// Authentication Routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
});

// Admin Routes
Route::middleware(['auth', 'level:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // User Management
    Route::resource('users', App\Http\Controllers\UserController::class);

    // Penjualan
    Route::resource('penjualan', App\Http\Controllers\PenjualanController::class)->except('show');

    // Penjualan Laporan
    Route::get('penjualan/laporan', [App\Http\Controllers\PenjualanController::class, 'laporan'])->name('penjualan.laporan');

    // Penjualan Laporan Stok
    Route::get('penjualan/laporan-stok', [App\Http\Controllers\PenjualanController::class, 'laporanStok'])->name('penjualan.laporan-stok');

    // Data Barang
    Route::resource('barang', BarangController::class);

    // Barang Masuk
    Route::resource('barang-masuk', BarangMasukController::class);

    // Barang Keluar
    Route::resource('barang-keluar', BarangKeluarController::class);

    // Laporan Routes
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/stok', [LaporanController::class, 'stok'])->name('stok');
        Route::get('/barang-masuk', [LaporanController::class, 'barangMasuk'])->name('barang-masuk');
        Route::get('/barang-keluar', [LaporanController::class, 'barangKeluar'])->name('barang-keluar');
    });

    // Kategori Barang
    Route::resource('kategori', App\Http\Controllers\KategoriBarangController::class);

    // Supplier Management
    Route::resource('supplier', App\Http\Controllers\SupplierController::class);
});

// Staff Routes (has limited access)
Route::middleware(['auth', 'level:staff,admin'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'staffDashboard'])->name('dashboard');

    // Limited access to data
    Route::get('/barang', [BarangController::class, 'staffIndex'])->name('barang.index');
    Route::get('/barang-masuk', [BarangMasukController::class, 'staffIndex'])->name('barang-masuk.index');
    Route::get('/barang-keluar', [BarangKeluarController::class, 'staffIndex'])->name('barang-keluar.index');

    // Stock Correction
    Route::get('/stok-koreksi', [App\Http\Controllers\StokKoreksiController::class, 'index'])->name('stok-koreksi.index');
    Route::post('/stok-koreksi', [App\Http\Controllers\StokKoreksiController::class, 'store'])->name('stok-koreksi.store');
});

// Manager Routes (can see everything, edit some things)
Route::middleware(['auth', 'level:manager,admin'])->prefix('manager')->name('manager.')->group(function () {
//    Route::get('/dashboard', [AdminController::class, 'managerDashboard'])->name('dashboard');
    Route::get('/dashboard', [App\Http\Controllers\ManagerDashboardController::class, 'index'])->name('dashboard');


    // Resource routes with limitations
    Route::resource('barang', BarangController::class)->except(['destroy']);
    Route::resource('barang-masuk', BarangMasukController::class);
    Route::resource('barang-keluar', BarangKeluarController::class);

    // Manager Reports
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/stok', [LaporanController::class, 'stok'])->name('stok');
        Route::get('/barang-masuk', [LaporanController::class, 'barangMasuk'])->name('barang-masuk');
        Route::get('/barang-keluar', [LaporanController::class, 'barangKeluar'])->name('barang-keluar');
    });
});

// Default dashboard redirect based on user level
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        return match($user->level) {
            'admin' => redirect()->route('admin.dashboard'),
            'manager' => redirect()->route('manager.dashboard'),
            'staff' => redirect()->route('staff.dashboard'),
            default => redirect()->route('admin.dashboard'),
        };
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
