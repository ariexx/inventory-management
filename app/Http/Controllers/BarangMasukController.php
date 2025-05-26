<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangMasuk = BarangMasuk::with(['barang', 'supplier'])
            ->latest('tanggal')
            ->paginate(10);
        return view('admin.barang-masuk.index', compact('barangMasuk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barang = Barang::all();
        $supplier = Supplier::all();
        // Auto generate kode transaksi
        $lastTransaksi = BarangMasuk::latest()->first();
        $kodeTransaksi = 'BM-' . date('Ymd') . '-' . str_pad(($lastTransaksi ? substr($lastTransaksi->kode_transaksi, -4) + 1 : 1), 4, '0', STR_PAD_LEFT);

        return view('admin.barang-masuk.create', compact('barang', 'supplier', 'kodeTransaksi'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_transaksi' => 'required|unique:barang_masuk,kode_transaksi',
            'barang_id' => 'required|exists:barang,id',
            'supplier_id' => 'nullable|exists:supplier,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            // Create barang masuk record
            BarangMasuk::create($request->all());

            // Update stock
            $barang = Barang::find($request->barang_id);
            $barang->stok += $request->jumlah;
            $barang->save();

            DB::commit();
            return redirect()->route('admin.barang-masuk.index')
                ->with('success', 'Data barang masuk berhasil ditambahkan');
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
    public function show(BarangMasuk $barangMasuk)
    {
        $barangMasuk->load(['barang', 'supplier']);
        return view('admin.barang-masuk.show', compact('barangMasuk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarangMasuk $barangMasuk)
    {
        $barang = Barang::all();
        $supplier = Supplier::all();
        return view('admin.barang-masuk.edit', compact('barangMasuk', 'barang', 'supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BarangMasuk $barangMasuk)
    {
        $request->validate([
            'kode_transaksi' => 'required|unique:barang_masuk,kode_transaksi,' . $barangMasuk->id,
            'barang_id' => 'required|exists:barang,id',
            'supplier_id' => 'nullable|exists:supplier,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            // Update stock (subtract old value, add new value)
            $barang = Barang::find($barangMasuk->barang_id);
            $barang->stok -= $barangMasuk->jumlah;
            $barang->save();

            // If the item is changed, update both items' stocks
            if ($barangMasuk->barang_id != $request->barang_id) {
                $barang = Barang::find($request->barang_id);
            }
            $barang->stok += $request->jumlah;
            $barang->save();

            // Update record
            $barangMasuk->update($request->all());

            DB::commit();
            return redirect()->route('admin.barang-masuk.index')
                ->with('success', 'Data barang masuk berhasil diperbarui');
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
    public function destroy(BarangMasuk $barangMasuk)
    {
        DB::beginTransaction();
        try {
            // Update stock
            $barang = Barang::find($barangMasuk->barang_id);
            $barang->stok -= $barangMasuk->jumlah;
            $barang->save();

            // Delete the record
            $barangMasuk->delete();

            DB::commit();
            return redirect()->route('admin.barang-masuk.index')
                ->with('success', 'Data barang masuk berhasil dihapus');
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
        $barangMasuk = BarangMasuk::with(['barang', 'supplier'])
            ->latest('tanggal')
            ->paginate(10);
        return view('staff.barang-masuk.index', compact('barangMasuk'));
    }
}
