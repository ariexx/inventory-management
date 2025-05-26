<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KoreksiStok extends Model
{
    use HasFactory;

    protected $table = 'koreksi_stok';

    protected $fillable = [
        'barang_id',
        'user_id',
        'tanggal',
        'stok_awal',
        'stok_akhir',
        'selisih',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'stok_awal' => 'float',
        'stok_akhir' => 'float',
        'selisih' => 'float',
    ];

    /**
     * Get the product associated with this correction.
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    /**
     * Get the user who made the correction.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
