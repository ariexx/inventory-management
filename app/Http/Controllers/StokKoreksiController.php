<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\KoreksiStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StokKoreksiController extends Controller
{
    /**
     * Display the stock correction form and history.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get all available products for dropdown
        $barang = Barang::orderBy('nama', 'asc')->get();

        // Get stock correction history with pagination
        $koreksiStok = KoreksiStok::with(['barang', 'user'])
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('staff.stok-koreksi.stok-koreksi', compact('barang', 'koreksiStok'));
    }

    /**
     * Store a newly created stock correction.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'stok_fisik' => 'required|numeric|min:0',
            'keterangan' => 'required|string|max:255',
        ]);

        // Get current stock information
        $barang = Barang::findOrFail($request->barang_id);
        $stokAwal = $barang->stok;
        $stokBaru = $request->stok_fisik;
        $selisih = $stokBaru - $stokAwal;

        // If there's no change, don't proceed
        if ($selisih == 0) {
            return redirect()->route('staff.stok-koreksi.index')
                ->with('error', 'Tidak ada perubahan stok yang perlu dikoreksi.');
        }

        try {
            // Start a database transaction
            DB::beginTransaction();

            // Create stock correction record
            KoreksiStok::create([
                'barang_id' => $barang->id,
                'user_id' => Auth::id(),
                'tanggal' => now(),
                'stok_awal' => $stokAwal,
                'stok_akhir' => $stokBaru,
                'selisih' => $selisih,
                'keterangan' => $request->keterangan,
            ]);

            // Update product stock
            $barang->stok = $stokBaru;
            $barang->save();

            DB::commit();

            return redirect()->route('staff.stok-koreksi.index')
                ->with('success', 'Koreksi stok berhasil disimpan. Stok ' . $barang->nama . ' telah diperbarui menjadi ' . $stokBaru . ' ' . $barang->satuan);

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('staff.stok-koreksi.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
