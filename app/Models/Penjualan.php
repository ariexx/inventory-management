<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';

    protected $fillable = [
        'kode_transaksi',
        'barang_id',
        'jumlah',
        'harga_satuan',
        'total_harga',
        'tanggal',
        'nama_pembeli',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'harga_satuan' => 'decimal:2',
        'total_harga' => 'decimal:2',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
