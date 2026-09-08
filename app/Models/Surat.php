<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    protected $table = 'surat';
    protected $fillable = [
        'jenis_surat_id',
        'klasifikasi_id',
        'template_surat_id',
        'arah',
        'nomor_surat',
        'tanggal_surat',
        'tanggal_diterima',
        'perihal',
        'pengirim',
        'tujuan',
        'file_word',
        'file_pdf',
        'file_dokumen',
        'status',
        'created_by',
        'source_type',
        'source_id',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_diterima' => 'date',
    ];

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class);
    }

    public function klasifikasi()
    {
        return $this->belongsTo(KlasifikasiSurat::class, 'klasifikasi_id');
    }

    public function template()
    {
        return $this->belongsTo(TemplateSurat::class, 'template_surat_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function scopeMasuk($query)
    {
        return $query->where('arah', 'masuk');
    }

    public function scopeKeluar($query)
    {
        return $query->where('arah', 'keluar');
    }

    public function source()
    {
        return $this->morphTo();
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
