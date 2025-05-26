<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'kode',
        'nama',
        'kategori_id',
        'stok',
        'minimal_stok',
        'satuan',
        'keterangan',
        'harga_jual', // Added field for harga jual
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriBarang::class, 'kategori_id');
    }

    public function barangMasuk(): HasMany
    {
        return $this->hasMany(BarangMasuk::class, 'barang_id');
    }

    public function barangKeluar(): HasMany
    {
        return $this->hasMany(BarangKeluar::class, 'barang_id');
    }
}
