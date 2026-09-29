<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BukuTamu extends Model
{
    use HasFactory;

    protected $table = 'buku_tamu';

    protected $fillable = [
        'nama',
        'instansi',
        'no_hp',
        'keperluan',
        'pegawai_id',
        'unit_kerja_id',
        'tanggal',
        'jam',
    ];

    protected $casts = [
        'tanggal' => 'date',
        // 'jam' could also be cast if needed, but it's usually fine as string for time
    ];

    /**
     * Get the Pegawai (Contact Person) associated with this visitor log.
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    /**
     * Get the UnitKerja associated with this visitor log.
     */
    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }
}
