<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penjualan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penjualans = Penjualan::with('barang')
            ->latest('tanggal')
            ->paginate(10);

        return view('admin.penjualan.index', compact('penjualans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barangs = Barang::where('stok', '>', 0)->get();

        return view('admin.penjualan.create', compact('barangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'nama_pembeli' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        // Get the barang
        $barang = Barang::findOrFail($request->barang_id);

        // Check stock availability
        if ($barang->stok < $request->jumlah) {
            return back()->with('error', 'Stok tidak mencukupi! Stok tersedia: '.$barang->stok);
        }

        // Set the transaction code
        $kodeTrx = 'TRX-'.date('Ymd').'-'.Str::random(5);

        // Get the price (either from hidden field or directly from model)
        $hargaSatuan = $request->input('harga_satuan_hidden') ?? $barang->harga_jual;
        $totalHarga = $hargaSatuan * $request->jumlah;

        // Create the sale record
        $penjualan = Penjualan::create([
            'kode_transaksi' => $kodeTrx,
            'barang_id' => $request->barang_id,
            'jumlah' => $request->jumlah,
            'harga_satuan' => $hargaSatuan,  // This will now have a value
            'total_harga' => $totalHarga,
            'tanggal' => $request->tanggal,
            'nama_pembeli' => $request->nama_pembeli ?? null,
            'keterangan' => $request->keterangan ?? null,
        ]);

        // Update stock
        $barang->stok -= $request->jumlah;
        $barang->save();

        return redirect()->route('admin.penjualan.index')->with('success', 'Data penjualan berhasil ditambahkan!');
    }

    /**
     * Display reports of sales.
     */
    public function laporan(Request $request)
    {
        $periode = $request->periode ?? 'harian';
        $tanggal = $request->tanggal ?? Carbon::now()->format('Y-m-d');
        $bulan = $request->bulan ?? Carbon::now()->format('Y-m');
        $tahun = $request->tahun ?? Carbon::now()->format('Y');

        if ($periode === 'harian') {
            $penjualans = Penjualan::with('barang')
                ->whereDate('tanggal', $tanggal)
                ->latest('tanggal')
                ->get();

            $totalPenjualan = $penjualans->sum('jumlah');
            $totalOmset = $penjualans->sum('total_harga');

            return view('admin.penjualan.laporan-harian', compact(
                'penjualans', 'tanggal', 'totalPenjualan', 'totalOmset'
            ));
        } elseif ($periode === 'bulanan') {
            $penjualans = Penjualan::with('barang')
                ->whereYear('tanggal', substr($bulan, 0, 4))
                ->whereMonth('tanggal', substr($bulan, 5, 2))
                ->latest('tanggal')
                ->get();

            $totalPenjualan = $penjualans->sum('jumlah');
            $totalOmset = $penjualans->sum('total_harga');

            $grafikPenjualan = Penjualan::selectRaw('DATE(tanggal) as tgl, SUM(jumlah) as total_penjualan, SUM(total_harga) as total_omset')
                ->whereYear('tanggal', substr($bulan, 0, 4))
                ->whereMonth('tanggal', substr($bulan, 5, 2))
                ->groupBy('tgl')
                ->orderBy('tgl')
                ->get();

            return view('admin.penjualan.laporan-bulanan', compact(
                'penjualans', 'bulan', 'totalPenjualan', 'totalOmset', 'grafikPenjualan'
            ));
        } else { // tahunan
            $penjualansBulan = Penjualan::selectRaw('MONTH(tanggal) as bulan, SUM(jumlah) as total_penjualan, SUM(total_harga) as total_omset')
                ->whereYear('tanggal', $tahun)
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get();

            $totalPenjualan = $penjualansBulan->sum('total_penjualan');
            $totalOmset = $penjualansBulan->sum('total_omset');

            return view('admin.penjualan.laporan-tahunan', compact(
                'penjualansBulan', 'tahun', 'totalPenjualan', 'totalOmset'
            ));
        }
    }

    /**
     * Display inventory stock report.
     */
    public function laporanStok()
    {
        $barangs = Barang::with('kategori')
            ->orderBy('stok')
            ->get();

        $kritisList = $barangs->filter(function ($barang) {
            return $barang->stok < $barang->minimal_stok;
        });

        $rendahList = $barangs->filter(function ($barang) {
            return $barang->stok >= $barang->minimal_stok && $barang->stok < ($barang->minimal_stok * 1.5);
        });

        $amanList = $barangs->filter(function ($barang) {
            return $barang->stok >= ($barang->minimal_stok * 1.5);
        });

        return view('admin.penjualan.laporan-stok', compact(
            'barangs', 'kritisList', 'rendahList', 'amanList'
        ));
    }

    /**
     * Generate invoice for a specific sale.
     *
     * @return \Illuminate\Http\Response
     */
    public function invoice(Penjualan $penjualan)
    {
        // Load the related barang model
        $penjualan->load('barang');

        // Get company info (you might want to store this in config or settings table)
        $companyInfo = [
            'name' => config('app.name', 'Inventory Management'),
            'address' => config('app.address', 'Jl. Contoh Alamat No. 123, Jakarta'),
            'phone' => config('app.phone', '021-12345678'),
            'email' => config('app.email', 'example@inventory.com'),
        ];

        return view('admin.penjualan.invoice', compact('penjualan', 'companyInfo'));
    }
}
