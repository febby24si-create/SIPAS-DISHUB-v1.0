<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';

    /**
     * Virtual attribute for TMT to be used by Observer
     */
    public $tmt_input;

    protected $fillable = [
        'nip',
        'nama',
        'pangkat',
        'golongan',
        'jabatan',
        'unit_kerja_id',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    /**
     * Get the UnitKerja this Pegawai belongs to.
     */
    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }

    /**
     * Get the UnitKerja where this Pegawai is the head.
     */
    public function kepalaUnitKerja(): HasMany
    {
        return $this->hasMany(UnitKerja::class, 'kepala_id');
    }

    /**
     * Get the history of jabatan and pangkat for this Pegawai.
     */
    public function riwayatJabatanPangkat(): HasMany
    {
        return $this->hasMany(RiwayatJabatanPangkat::class, 'pegawai_id');
    }
}
