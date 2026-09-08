<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatJabatanPangkat extends Model
{
    use HasFactory;

    protected $table = 'riwayat_jabatan_pangkat';

    protected $fillable = [
        'pegawai_id',
        'pangkat',
        'golongan',
        'jabatan',
        'tmt',
        'keterangan',
    ];

    protected $casts = [
        'tmt' => 'date',
    ];

    /**
     * Get the Pegawai that owns the history record.
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
