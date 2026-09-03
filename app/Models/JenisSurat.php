<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    protected $table = 'jenis_surat';
    protected $fillable = [
        'nama',
        'kode',
    ];

    public function templates()
    {
        return $this->hasMany(TemplateSurat::class);
    }
    public function surat()
    {
        return $this->hasMany(Surat::class);
    }
}
