<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Display stock report.
     */
    public function stok(Request $request)
    {
        $query = Barang::with('kategori');

        // Filter by category if provided
        if ($request->has('kategori_id') && $request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter by stock status if provided
        if ($request->has('status') && $request->status) {
            if ($request->status == 'low') {
                $query->whereColumn('stok', '<', 'minimal_stok');
            } elseif ($request->status == 'safe') {
                $query->whereColumn('stok', '>=', 'minimal_stok');
            }
        }

        $barang = $query->latest()->paginate(15);
        $kategori = \App\Models\KategoriBarang::all();

        return view('admin.laporan.stok', compact('barang', 'kategori'));
    }

    /**
     * Display incoming items report.
     */
    public function barangMasuk(Request $request)
    {
        $query = BarangMasuk::with(['barang', 'supplier']);

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        // Filter by supplier
        if ($request->has('supplier_id') && $request->supplier_id) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Filter by item
        if ($request->has('barang_id') && $request->barang_id) {
            $query->where('barang_id', $request->barang_id);
        }

        $barangMasuk = $query->latest('tanggal')->paginate(15);
        $supplier = \App\Models\Supplier::all();
        $barang = \App\Models\Barang::all();

        return view('admin.laporan.barang-masuk', compact('barangMasuk', 'supplier', 'barang'));
    }

    /**
     * Display outgoing items report.
     */
    public function barangKeluar(Request $request)
    {
        $query = BarangKeluar::with('barang');

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        // Filter by destination
        if ($request->has('tujuan') && $request->tujuan) {
            $query->where('tujuan', 'like', '%' . $request->tujuan . '%');
        }

        // Filter by item
        if ($request->has('barang_id') && $request->barang_id) {
            $query->where('barang_id', $request->barang_id);
        }

        $barangKeluar = $query->latest('tanggal')->paginate(15);
        $barang = \App\Models\Barang::all();

        return view('admin.laporan.barang-keluar', compact('barangKeluar', 'barang'));
    }
}
