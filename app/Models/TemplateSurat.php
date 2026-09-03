<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateSurat extends Model
{
    protected $table = 'template_surat';
    protected $fillable = [
        'jenis_surat_id',
        'nama_template',
        'file_template',
        'placeholder_json',
        'status',
    ];

    protected $casts = [
        'placeholder_json' => 'array',
    ];

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class);
    }

    public function surat()
    {
        return $this->hasMany(Surat::class);
    }
}
