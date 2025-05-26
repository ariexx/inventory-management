<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SupplierController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $suppliers = Supplier::all();
        return view('admin.supplier.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.supplier.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'keterangan' => 'nullable|string',
        ]);

        Supplier::create($validatedData);

        return redirect()->route('admin.supplier.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function show(Supplier $supplier)
    {
        return view('admin.supplier.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.supplier.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $supplier->update($validatedData);

        return redirect()->route('admin.supplier.index')
            ->with('success', 'Data supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        // Check if supplier has related records
        if ($supplier->barangMasuk->count() > 0) {
            return redirect()->route('admin.supplier.index')
                ->with('error', 'Supplier tidak dapat dihapus karena memiliki transaksi barang masuk.');
        }

        $supplier->delete();

        return redirect()->route('admin.supplier.index')
            ->with('success', 'Supplier berhasil dihapus.');
    }
}
