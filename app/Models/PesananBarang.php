<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PesananBarang extends Model
{
    use HasFactory;

    protected $table = 'pesanan_barang';

    protected $fillable = [
        'kode_pesanan',
        'barang_id',
        'supplier_id',
        'jumlah',
        'tanggal_pemesanan',
        'tanggal_pengiriman_diharapkan',
        'harga_satuan',
        'status', // pending, approved, received, cancelled
        'keterangan',
    ];

    protected $casts = [
        'tanggal_pemesanan' => 'date',
        'tanggal_pengiriman_diharapkan' => 'date',
        'harga_satuan' => 'decimal:2',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    
    // Relationship to BarangMasuk (if this order was fulfilled and received)
    public function barangMasuk(): HasOne
    {
        return $this->hasOne(BarangMasuk::class, 'pesanan_id');
    }
}
