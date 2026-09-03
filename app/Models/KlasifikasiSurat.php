<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KlasifikasiSurat extends Model
{
    protected $table = 'klasifikasi_surat';
    protected $fillable = [
        'kode',
        'nama',
        'status',
    ];

    public function surat()
    {
        return $this->hasMany(Surat::class, 'klasifikasi_id');
    }
}
