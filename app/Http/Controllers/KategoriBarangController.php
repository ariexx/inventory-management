<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class KategoriBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategori = KategoriBarang::withCount('barang')->latest()->paginate(10);
        return view('admin.kategori.index', compact('kategori'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:kategori_barang,nama',
            'keterangan' => 'nullable|string'
        ]);

        KategoriBarang::create([
            'nama' => $request->nama,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori barang berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(KategoriBarang $kategori)
    {
        $kategori->load(['barang' => function($query) {
            $query->latest()->take(10);
        }]);

        return view('admin.kategori.show', compact('kategori'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KategoriBarang $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KategoriBarang $kategori)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:kategori_barang,nama,' . $kategori->id,
            'keterangan' => 'nullable|string'
        ]);

        $kategori->update([
            'nama' => $request->nama,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori barang berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KategoriBarang $kategori)
    {
        // Check if category has items
        if ($kategori->barang()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki barang terkait');
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori barang berhasil dihapus');
    }
}
