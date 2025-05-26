<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManagerDashboardController extends Controller
{
    public function index()
    {
        // Get monthly sales data for the current year
        $monthlySales = BarangKeluar::select(
            DB::raw('MONTH(tanggal) as month'),
            DB::raw('SUM(jumlah) as total')
        )
            ->whereYear('tanggal', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        // Format data for the sales chart
        $salesChartLabels = [];
        $salesChartData = [];

        for ($i = 1; $i <= 12; $i++) {
            $monthName = date('F', mktime(0, 0, 0, $i, 1));
            $salesChartLabels[] = $monthName;
            $salesChartData[] = $monthlySales[$i] ?? 0;
        }

        // Get top 10 products based on outgoing quantity
        $hotProducts = BarangKeluar::select(
            'barang_id',
            DB::raw('SUM(jumlah) as total')
        )
            ->with('barang:id,nama')
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->groupBy('barang_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Format data for the hot products chart
        $productChartLabels = $hotProducts->pluck('barang.nama')->toArray();
        $productChartData = $hotProducts->pluck('total')->toArray();

        // Get stock status for inventory decisions
        $stockStatus = Barang::select(
            'id', 'kode', 'nama', 'stok', 'minimal_stok',
            'satuan', 'kategori_id', 'keterangan'
        )
            ->with('kategori:id,nama')
            ->orderBy('stok')
            ->get();

        // Summary data
        $totalBarang = Barang::count();
        $totalBarangKeluar = BarangKeluar::whereMonth('tanggal', Carbon::now()->month)->sum('jumlah');
        $totalBarangMasuk = BarangMasuk::whereMonth('tanggal', Carbon::now()->month)->sum('jumlah');
        $lowStockCount = Barang::where('stok', '<', DB::raw('minimal_stok'))->count();

        // Daily sales data for the current month
        $currentDate = Carbon::now();
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();

        $dailySales = BarangKeluar::select(
            DB::raw('DATE(tanggal) as date'),
            DB::raw('SUM(jumlah) as total_items'),
            DB::raw('SUM(jumlah * harga_jual) as total_turnover')
        )
            ->whereMonth('tanggal', $currentDate->month)
            ->whereYear('tanggal', $currentDate->year)
            ->groupBy(DB::raw('DATE(tanggal)'))
            ->orderBy('date')
            ->get();

        // Calculate total sold goods and turnover for the current month
        $totalSoldGoods = $dailySales->sum('total_items');
        $totalTurnover = $dailySales->sum('total_turnover');

        // Format daily sales data for chart
        $dailySalesLabels = [];
        $dailySalesData = [];
        $dailyTurnoverData = [];

        $period = new \DatePeriod(
            $startOfMonth,
            new \DateInterval('P1D'),
            $endOfMonth->modify('+1 day')
        );

        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            $dailySalesLabels[] = $date->format('d M');

            $daySale = $dailySales->firstWhere('date', $dateStr);
            $dailySalesData[] = $daySale ? $daySale->total_items : 0;
            $dailyTurnoverData[] = $daySale ? $daySale->total_turnover : 0;
        }

        return view('manager.dashboard.index', compact(
            'salesChartLabels',
            'salesChartData',
            'productChartLabels',
            'productChartData',
            'stockStatus',
            'totalBarang',
            'totalBarangKeluar',
            'totalBarangMasuk',
            'lowStockCount',
            'dailySalesLabels',
            'dailySalesData',
            'dailyTurnoverData',
            'totalSoldGoods',
            'totalTurnover',
            'dailySales'
        ));
    }
}
