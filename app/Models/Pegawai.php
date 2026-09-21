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
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'pendidikan_terakhir',
        'pangkat',
        'golongan',
        'tmt_pangkat',
        'jabatan',
        'tmt_jabatan',
        'unit_kerja_id',
        'status_aktif',
        'kategori_bup_id',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
        'tanggal_lahir' => 'date',
        'tmt_pangkat' => 'date',
        'tmt_jabatan' => 'date',
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

    /**
     * Get the user account associated with this Pegawai.
     */
    public function user()
    {
        return $this->hasOne(User::class);
    }

    /**
     * Get the diklat history for this Pegawai.
     */
    public function riwayatDiklat(): HasMany
    {
        return $this->hasMany(RiwayatDiklat::class, 'pegawai_id');
    }

    /**
     * Get the Gaji Berkala records for this Pegawai.
     */
    public function gajiBerkala(): HasMany
    {
        return $this->hasMany(GajiBerkala::class, 'pegawai_id');
    }

    /**
     * Get the Kenaikan Pangkat records for this Pegawai.
     */
    public function kenaikanPangkat(): HasMany
    {
        return $this->hasMany(KenaikanPangkat::class, 'pegawai_id');
    }

    /**
     * Get the Kategori BUP for this Pegawai.
     */
    public function kategoriBup(): BelongsTo
    {
        return $this->belongsTo(KategoriBup::class, 'kategori_bup_id');
    }
}
