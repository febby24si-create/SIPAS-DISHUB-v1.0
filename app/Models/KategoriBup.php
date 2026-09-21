<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriBup extends Model
{
    use HasFactory;

    protected $table = 'kategori_bup';

    protected $fillable = ['nama_kategori', 'usia_pensiun', 'status'];

    protected $casts = [
        'usia_pensiun' => 'integer',
        'status' => 'boolean',
    ];

    public function pegawai(): HasMany
    {
        return $this->hasMany(Pegawai::class, 'kategori_bup_id');
    }
}
