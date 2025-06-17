<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\RestockRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestockRequestController extends Controller
{
    // Staff methods
    public function staffIndex()
    {
        $requests = RestockRequest::where('user_id', Auth::id())
            ->with(['barang'])
            ->latest()
            ->get();

        return view('staff.restock-requests.index', compact('requests'));
    }

    public function staffCreate()
    {
        $barang = Barang::where('stok', '<=', function($query) {
                $query->selectRaw('minimal_stok * 1.2'); // Get items where stock is <= 120% of minimum stock
            })
            ->orWhere('stok', '<', 10) // Or less than 10 units regardless
            ->get();

        return view('staff.restock-requests.create', compact('barang'));
    }

    public function staffStore(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah_diminta' => 'required|integer|min:1',
            'alasan' => 'required|string|max:255',
        ]);

        RestockRequest::create([
            'barang_id' => $request->barang_id,
            'user_id' => Auth::id(),
            'jumlah_diminta' => $request->jumlah_diminta,
            'alasan' => $request->alasan,
            'status' => 'pending',
        ]);

        return redirect()->route('staff.restock-requests.index')
            ->with('success', 'Permintaan restock berhasil dibuat.');
    }

    // Admin/manager methods
    public function adminIndex()
    {
        $requests = RestockRequest::with(['barang', 'user'])
            ->latest()
            ->get();

        return view('admin.restock-requests.index', compact('requests'));
    }

    public function adminShow(RestockRequest $restockRequest)
    {
        return view('admin.restock-requests.show', compact('restockRequest'));
    }

    public function adminProcess(Request $request, RestockRequest $restockRequest)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'notes' => 'nullable|string',
        ]);

        $restockRequest->update([
            'status' => $request->status,
            'notes' => $request->notes,
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        // If approved, create a PesananBarang for the restock
        if ($request->status === 'approved') {
            // Redirect to create order form pre-populated
            return redirect()->route('admin.pesanan-barang.create', [
                'barang_id' => $restockRequest->barang_id,
                'jumlah' => $restockRequest->jumlah_diminta,
                'restock_request_id' => $restockRequest->id,
            ])->with('success', 'Permintaan restock disetujui. Silakan buat pesanan ke supplier.');
        }

        return redirect()->route('admin.restock-requests.index')
            ->with('success', 'Permintaan restock berhasil diproses.');
    }

    public function managerIndex()
    {
        $requests = RestockRequest::with(['barang', 'user'])
            ->latest()
            ->get();

        return view('manager.restock-requests.index', compact('requests'));
    }

    public function managerShow(RestockRequest $restockRequest)
    {
        return view('manager.restock-requests.show', compact('restockRequest'));
    }

    public function managerProcess(Request $request, RestockRequest $restockRequest)
    {
        return $this->adminProcess($request, $restockRequest);
    }
}
