<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier';

    protected $fillable = [
        'nama',
        'alamat',
        'telepon',
        'email',
        'keterangan',
    ];

    public function barangMasuk(): HasMany
    {
        return $this->hasMany(BarangMasuk::class, 'supplier_id');
    }
}
