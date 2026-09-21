<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatDiklat extends Model
{
    use HasFactory;

    protected $table = 'riwayat_diklat';

    protected $fillable = [
        'pegawai_id',
        'nama_diklat',
        'penyelenggara',
        'tahun',
        'jam_pelajaran',
        'file_sertifikat',
    ];

    /**
     * Get the Pegawai that owns this diklat record.
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
