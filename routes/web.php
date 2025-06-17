<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PesananBarangController;
use App\Http\Controllers\RestockRequestController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::middleware('record.login')->post('/login', [AuthController::class, 'login']);
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

    // Pesanan Barang (Product Orders)
    Route::resource('pesanan-barang', PesananBarangController::class);
    Route::get('pesanan-barang/{pesananBarang}/receive', [PesananBarangController::class, 'receive'])->name('pesanan-barang.receive');
    Route::post('pesanan-barang/{pesananBarang}/receive', [PesananBarangController::class, 'processReceive'])->name('pesanan-barang.process-receive');

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

    // Invoice Route for Penjualan
    Route::get('/penjualan/{penjualan}/invoice', [App\Http\Controllers\PenjualanController::class, 'invoice'])->name('admin.penjualan.invoice');

    // Login Activity routes (admin only)
    Route::get('/admin/login-activities', [App\Http\Controllers\LoginActivityController::class, 'index'])
        ->name('admin.login-activities.index');

    // Restock Request Management
    Route::get('restock-requests', [RestockRequestController::class, 'adminIndex'])->name('restock-requests.index');
    Route::get('restock-requests/{restockRequest}', [RestockRequestController::class, 'adminShow'])->name('restock-requests.show');
    Route::post('restock-requests/{restockRequest}/process', [RestockRequestController::class, 'adminProcess'])->name('restock-requests.process');
});

// Staff Routes (has limited access)
Route::middleware(['auth', 'level:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'staffDashboard'])->name('dashboard');

    // Limited access to data
    Route::get('/barang', [BarangController::class, 'staffIndex'])->name('barang.index');
    Route::get('/barang-masuk', [BarangMasukController::class, 'staffIndex'])->name('barang-masuk.index');
    Route::get('/barang-keluar', [BarangKeluarController::class, 'staffIndex'])->name('barang-keluar.index');

    // Stock Correction
    Route::get('/stok-koreksi', [App\Http\Controllers\StokKoreksiController::class, 'index'])->name('stok-koreksi.index');
    Route::post('/stok-koreksi', [App\Http\Controllers\StokKoreksiController::class, 'store'])->name('stok-koreksi.store');

    // Restock Requests
    Route::get('restock-requests', [RestockRequestController::class, 'staffIndex'])->name('restock-requests.index');
    Route::get('restock-requests/create', [RestockRequestController::class, 'staffCreate'])->name('restock-requests.create');
    Route::post('restock-requests', [RestockRequestController::class, 'staffStore'])->name('restock-requests.store');
});

// Manager Routes (can see everything, edit some things)
Route::middleware(['auth', 'level:manager'])->prefix('manager')->name('manager.')->group(function () {
    //    Route::get('/dashboard', [AdminController::class, 'managerDashboard'])->name('dashboard');
    Route::get('/dashboard', [App\Http\Controllers\ManagerDashboardController::class, 'index'])->name('dashboard');

    // Resource routes with limitations
    Route::resource('barang', BarangController::class)->except(['destroy']);
    Route::resource('barang-masuk', BarangMasukController::class);
    Route::resource('barang-keluar', BarangKeluarController::class);


    // Pesanan Barang (Product Orders)
    Route::resource('pesanan-barang', PesananBarangController::class);
    Route::get('pesanan-barang/{pesananBarang}/receive', [PesananBarangController::class, 'receive'])->name('pesanan-barang.receive');
    Route::post('pesanan-barang/{pesananBarang}/receive', [PesananBarangController::class, 'processReceive'])->name('pesanan-barang.process-receive');


    // Manager Reports
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/stok', [LaporanController::class, 'stok'])->name('stok');
        Route::get('/barang-masuk', [LaporanController::class, 'barangMasuk'])->name('barang-masuk');
        Route::get('/barang-keluar', [LaporanController::class, 'barangKeluar'])->name('barang-keluar');
    });

    // Restock Request Management
    Route::get('restock-requests', [RestockRequestController::class, 'managerIndex'])->name('restock-requests.index');
    Route::get('restock-requests/{restockRequest}', [RestockRequestController::class, 'managerShow'])->name('restock-requests.show');
    Route::post('restock-requests/{restockRequest}/process', [RestockRequestController::class, 'managerProcess'])->name('restock-requests.process');
});

// Default dashboard redirect based on user level
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();


        if ($user->level === 'manager' && !request()->routeIs('manager.dashboard')) {
            return redirect()->route('manager.dashboard');
        }
        if ($user->level === 'staff' && !request()->routeIs('staff.dashboard')) {
            return redirect()->route('staff.dashboard');
        }
        if ($user->level === 'admin' && !request()->routeIs('admin.dashboard')) {
            return redirect()->route('admin.dashboard');
        }

        // Prevent redirect loop: show a default view or message if already on the correct dashboard
        abort(403, 'Unauthorized access.');
    })->name('dashboard');

    // Logout route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Apply record login middleware to necessary routes
Route::middleware(['web', 'auth', 'record.login'])->group(function () {
    // Add your authenticated routes here if they aren't already in a group
    // Or you can apply this middleware in the RouteServiceProvider to the desired route groups
});
