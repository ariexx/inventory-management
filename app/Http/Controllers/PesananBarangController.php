<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\PesananBarang;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PesananBarangController extends Controller
{
    public function index()
    {
        $pesanan = PesananBarang::with(['barang', 'supplier'])->latest()->get();
        return view('admin.pesanan-barang.index', compact('pesanan'));
    }

    public function create()
    {
        $barang = Barang::all();
        $supplier = Supplier::all();
        return view('admin.pesanan-barang.create', compact('barang', 'supplier'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'supplier_id' => 'required|exists:supplier,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pemesanan' => 'required|date',
            'tanggal_pengiriman_diharapkan' => 'nullable|date',
            'harga_satuan' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $kode = 'PO-' . Str::upper(Str::random(8));
            
            PesananBarang::create([
                'kode_pesanan' => $kode,
                'barang_id' => $request->barang_id,
                'supplier_id' => $request->supplier_id,
                'jumlah' => $request->jumlah,
                'tanggal_pemesanan' => $request->tanggal_pemesanan,
                'tanggal_pengiriman_diharapkan' => $request->tanggal_pengiriman_diharapkan,
                'harga_satuan' => $request->harga_satuan,
                'status' => 'pending',
                'keterangan' => $request->keterangan,
            ]);
            
            DB::commit();
            return redirect()->route('admin.pesanan-barang.index')->with('success', 'Pesanan barang berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(PesananBarang $pesananBarang)
    {
        return view('admin.pesanan-barang.show', compact('pesananBarang'));
    }

    public function edit(PesananBarang $pesananBarang)
    {
        $barang = Barang::all();
        $supplier = Supplier::all();
        return view('admin.pesanan-barang.edit', compact('pesananBarang', 'barang', 'supplier'));
    }

    public function update(Request $request, PesananBarang $pesananBarang)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'supplier_id' => 'required|exists:supplier,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pemesanan' => 'required|date',
            'tanggal_pengiriman_diharapkan' => 'nullable|date',
            'harga_satuan' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,approved,received,cancelled',
            'keterangan' => 'nullable|string',
        ]);

        $pesananBarang->update($request->all());
        
        return redirect()->route('admin.pesanan-barang.index')->with('success', 'Pesanan barang berhasil diperbarui.');
    }

    public function destroy(PesananBarang $pesananBarang)
    {
        if ($pesananBarang->status === 'received') {
            return back()->with('error', 'Pesanan yang sudah diterima tidak dapat dihapus.');
        }
        
        $pesananBarang->delete();
        return redirect()->route('admin.pesanan-barang.index')->with('success', 'Pesanan barang berhasil dihapus.');
    }
    
    public function receive(PesananBarang $pesananBarang)
    {
        if ($pesananBarang->status !== 'approved') {
            return back()->with('error', 'Hanya pesanan yang sudah disetujui yang dapat diterima.');
        }
        
        return view('admin.pesanan-barang.receive', compact('pesananBarang'));
    }
    
    public function processReceive(Request $request, PesananBarang $pesananBarang)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jumlah' => 'required|integer|min:1|max:' . $pesananBarang->jumlah,
            'keterangan' => 'nullable|string',
        ]);
        
        DB::beginTransaction();
        try {
            // Create barang masuk record
            $kode = 'BM-' . Str::upper(Str::random(8));
            
            BarangMasuk::create([
                'kode_transaksi' => $kode,
                'barang_id' => $pesananBarang->barang_id,
                'supplier_id' => $pesananBarang->supplier_id,
                'pesanan_id' => $pesananBarang->id,
                'jumlah' => $request->jumlah,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
            ]);
            
            // Update pesanan status
            $pesananBarang->update([
                'status' => 'received',
            ]);
            
            // Update stock
            $barang = $pesananBarang->barang;
            $barang->stok += $request->jumlah;
            $barang->save();
            
            DB::commit();
            return redirect()->route('admin.pesanan-barang.index')->with('success', 'Pesanan berhasil diterima dan stok diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
}
