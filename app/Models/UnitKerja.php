<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitKerja extends Model
{
    use HasFactory;

    protected $table = 'unit_kerja';

    protected $fillable = [
        'kode_unit',
        'nama',
        'kepala_id',
    ];

    /**
     * Get the Pegawai that heads this UnitKerja.
     */
    public function kepala(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'kepala_id');
    }

    /**
     * Get the Pegawais associated with this UnitKerja.
     */
    public function pegawais(): HasMany
    {
        return $this->hasMany(Pegawai::class, 'unit_kerja_id');
    }
}
