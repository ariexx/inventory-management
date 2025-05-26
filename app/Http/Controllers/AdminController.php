<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Count total items
        $totalBarang = \App\Models\Barang::count();

        // Count items received this month
        $totalBarangMasuk = \App\Models\BarangMasuk::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('jumlah');

        // Count items distributed this month
        $totalBarangKeluar = \App\Models\BarangKeluar::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('jumlah');

        // Total transactions for all time
        $totalBarangMasukAll = \App\Models\BarangMasuk::count();
        $totalBarangKeluarAll = \App\Models\BarangKeluar::count();

        // Get recent items
        $recentBarangMasuk = \App\Models\BarangMasuk::with('barang')
            ->latest('tanggal')
            ->take(5)
            ->get();

        $recentBarangKeluar = \App\Models\BarangKeluar::with('barang')
            ->latest('tanggal')
            ->take(5)
            ->get();

        // Get low stock items
        $lowStockItems = \App\Models\Barang::whereColumn('stok', '<', 'minimal_stok')
            ->orWhere('stok', '<=', 5)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalBarang',
            'totalBarangMasuk',
            'totalBarangKeluar',
            'totalBarangMasukAll',
            'totalBarangKeluarAll',
            'recentBarangMasuk',
            'recentBarangKeluar',
            'lowStockItems'
        ));
    }

    /**
     * Display staff dashboard with limited information
     */
    public function staffDashboard()
    {
        // Count visible items
        $totalBarang = Barang::count();

        // Count items received/distributed today
        $totalBarangMasukToday = BarangMasuk::whereDate('tanggal', today())->sum('jumlah');
        $totalBarangKeluarToday = BarangKeluar::whereDate('tanggal', today())->sum('jumlah');

        // Get recent activities only from today and yesterday
        $recentBarangMasuk = BarangMasuk::with('barang')
            ->whereDate('tanggal', '>=', now()->subDay())
            ->latest('tanggal')
            ->take(5)
            ->get();

        $recentBarangKeluar = BarangKeluar::with('barang')
            ->whereDate('tanggal', '>=', now()->subDay())
            ->latest('tanggal')
            ->take(5)
            ->get();

        // Get critical stock items (only the most critical ones)
        $criticalStockItems = Barang::whereColumn('stok', '<', 'minimal_stok')
            ->orderBy('stok')
            ->take(3)
            ->get();

        // Staff activity log
        $userActivity = [
            'last_login' => Auth::user()->last_login_at ?? now(),
            'name' => Auth::user()->name,
        ];

        // Get most active inventory items
        $mostActiveItems = Barang::select('barang.*')
            ->selectRaw('(SELECT COUNT(*) FROM barang_masuk WHERE barang_masuk.barang_id = barang.id) +
                         (SELECT COUNT(*) FROM barang_keluar WHERE barang_keluar.barang_id = barang.id) as activity_count')
            ->orderByRaw('activity_count DESC')
            ->take(5)
            ->get();

        return view('staff.dashboard.index', compact(
            'totalBarang',
            'totalBarangMasukToday',
            'totalBarangKeluarToday',
            'recentBarangMasuk',
            'recentBarangKeluar',
            'criticalStockItems',
            'userActivity',
            'mostActiveItems'
        ));
    }

    /**
     * Display manager dashboard with comprehensive information
     */
    public function managerDashboard()
    {
        // Count total items
        $totalBarang = Barang::count();

        // Count items received/distributed this month and previous month for comparison
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $previousMonth = now()->subMonth();

        $totalBarangMasukCurrentMonth = BarangMasuk::whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->sum('jumlah');

        $totalBarangKeluarCurrentMonth = BarangKeluar::whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $currentYear)
            ->sum('jumlah');

        $totalBarangMasukPreviousMonth = BarangMasuk::whereMonth('tanggal', $previousMonth->month)
            ->whereYear('tanggal', $previousMonth->year)
            ->sum('jumlah');

        $totalBarangKeluarPreviousMonth = BarangKeluar::whereMonth('tanggal', $previousMonth->month)
            ->whereYear('tanggal', $previousMonth->year)
            ->sum('jumlah');

        // Calculate percentage changes
        $masukPercentageChange = $totalBarangMasukPreviousMonth > 0
            ? (($totalBarangMasukCurrentMonth - $totalBarangMasukPreviousMonth) / $totalBarangMasukPreviousMonth) * 100
            : 0;

        $keluarPercentageChange = $totalBarangKeluarPreviousMonth > 0
            ? (($totalBarangKeluarCurrentMonth - $totalBarangKeluarPreviousMonth) / $totalBarangKeluarPreviousMonth) * 100
            : 0;

        // Get low stock items
        $lowStockItems = Barang::whereColumn('stok', '<', 'minimal_stok')
            ->orWhere('stok', '<=', 5)
            ->get();

        // Get recent transactions
        $recentTransactions = collect()
            ->merge(BarangMasuk::with('barang', 'supplier')
                ->latest('tanggal')
                ->take(5)
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'kode' => $item->kode_transaksi,
                        'tanggal' => $item->tanggal,
                        'barang' => $item->barang->nama,
                        'jumlah' => $item->jumlah,
                        'type' => 'masuk',
                        'detail' => $item->supplier ? $item->supplier->nama : '-'
                    ];
                })
            )
            ->merge(BarangKeluar::with('barang')
                ->latest('tanggal')
                ->take(5)
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'kode' => $item->kode_transaksi,
                        'tanggal' => $item->tanggal,
                        'barang' => $item->barang->nama,
                        'jumlah' => $item->jumlah,
                        'type' => 'keluar',
                        'detail' => $item->tujuan ?? '-'
                    ];
                })
            )
            ->sortByDesc('tanggal')
            ->take(10);

        return view('manager.dashboard.index', compact(
            'totalBarang',
            'totalBarangMasukCurrentMonth',
            'totalBarangKeluarCurrentMonth',
            'masukPercentageChange',
            'keluarPercentageChange',
            'lowStockItems',
            'recentTransactions'
        ));
    }
}
