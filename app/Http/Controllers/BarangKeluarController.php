<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangKeluar = BarangKeluar::with('barang')
            ->latest('tanggal')
            ->paginate(10);
        return view('admin.barang-keluar.index', compact('barangKeluar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barang = Barang::where('stok', '>', 0)->get();
        // Auto generate kode transaksi
        $lastTransaksi = BarangKeluar::latest()->first();
        $kodeTransaksi = 'BK-' . date('Ymd') . '-' . str_pad(($lastTransaksi ? substr($lastTransaksi->kode_transaksi, -4) + 1 : 1), 4, '0', STR_PAD_LEFT);

        return view('admin.barang-keluar.create', compact('barang', 'kodeTransaksi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_transaksi' => 'required|unique:barang_keluar,kode_transaksi',
            'barang_id' => 'required|exists:barang,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'tujuan' => 'nullable|string|max:100',
            'penerima' => 'nullable|string|max:100',
        ]);

        DB::beginTransaction();
        try {
            // Check if stock is sufficient
            $barang = Barang::find($request->barang_id);
            if ($barang->stok < $request->jumlah) {
                return redirect()->back()
                    ->with('error', 'Stok tidak mencukupi')
                    ->withInput();
            }

            // Create barang keluar record
            BarangKeluar::create($request->all());

            // Update stock
            $barang->stok -= $request->jumlah;
            $barang->save();

            DB::commit();
            return redirect()->route('admin.barang-keluar.index')
                ->with('success', 'Data barang keluar berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BarangKeluar $barangKeluar)
    {
        $barangKeluar->load('barang');
        return view('admin.barang-keluar.show', compact('barangKeluar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarangKeluar $barangKeluar)
    {
        $barang = Barang::all();
        return view('admin.barang-keluar.edit', compact('barangKeluar', 'barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BarangKeluar $barangKeluar)
    {
        $request->validate([
            'kode_transaksi' => 'required|unique:barang_keluar,kode_transaksi,' . $barangKeluar->id,
            'barang_id' => 'required|exists:barang,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'tujuan' => 'nullable|string|max:100',
            'penerima' => 'nullable|string|max:100',
        ]);

        DB::beginTransaction();
        try {
            // Return the old quantity to stock
            $oldBarang = Barang::find($barangKeluar->barang_id);
            $oldBarang->stok += $barangKeluar->jumlah;
            $oldBarang->save();

            // If item is changed, get the new item
            $newBarang = ($barangKeluar->barang_id != $request->barang_id)
                ? Barang::find($request->barang_id)
                : $oldBarang;

            // Check if new stock is sufficient
            if ($newBarang->stok < $request->jumlah) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Stok tidak mencukupi')
                    ->withInput();
            }

            // Decrease the stock with new quantity
            $newBarang->stok -= $request->jumlah;
            $newBarang->save();

            // Update record
            $barangKeluar->update($request->all());

            DB::commit();
            return redirect()->route('admin.barang-keluar.index')
                ->with('success', 'Data barang keluar berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangKeluar $barangKeluar)
    {
        DB::beginTransaction();
        try {
            // Return quantity to stock
            $barang = Barang::find($barangKeluar->barang_id);
            $barang->stok += $barangKeluar->jumlah;
            $barang->save();

            // Delete the record
            $barangKeluar->delete();

            DB::commit();
            return redirect()->route('admin.barang-keluar.index')
                ->with('success', 'Data barang keluar berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of the resource for staff.
     */
    public function staffIndex()
    {
        $barangKeluar = BarangKeluar::with('barang')
            ->latest('tanggal')
            ->paginate(10);
        return view('staff.barang-keluar.index', compact('barangKeluar'));
    }
}
