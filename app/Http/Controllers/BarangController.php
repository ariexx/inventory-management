<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barang = Barang::with('kategori')->latest()->paginate(10);
        return view('admin.barang.index', compact('barang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = KategoriBarang::all();
        return view('admin.barang.create', compact('kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:barang,kode',
            'nama' => 'required|string|max:100',
            'kategori_id' => 'required|exists:kategori_barang,id',
            'stok' => 'required|integer|min:0',
            'minimal_stok' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
        ]);

        Barang::create($request->all());

        return redirect()->route('admin.barang.index')
            ->with('success', 'Data barang berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Barang $barang)
    {
        return view('admin.barang.show', compact('barang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang)
    {
        $kategori = KategoriBarang::all();
        return view('admin.barang.edit', compact('barang', 'kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'kode' => 'required|unique:barang,kode,' . $barang->id,
            'nama' => 'required|string|max:100',
            'kategori_id' => 'required|exists:kategori_barang,id',
            'stok' => 'required|integer|min:0',
            'minimal_stok' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
        ]);

        $barang->update($request->all());

        return redirect()->route('admin.barang.index')
            ->with('success', 'Data barang berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang)
    {
        try {
            $barang->delete();
            return redirect()->route('admin.barang.index')
                ->with('success', 'Data barang berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.barang.index')
                ->with('error', 'Gagal menghapus data barang. Data ini masih digunakan.');
        }
    }

    /**
     * Display a listing of the resource for staff.
     */
    public function staffIndex()
    {
        $barang = Barang::with('kategori')->latest()->paginate(10);
        return view('staff.barang.index', compact('barang'));
    }
}
