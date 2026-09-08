<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class KenaikanPangkat extends Model
{
    use HasFactory;

    protected $table = 'kenaikan_pangkat';

    protected $fillable = [
        'nomor_usulan',
        'pegawai_id',
        'pangkat_lama',
        'golongan_lama',
        'pangkat_baru',
        'golongan_baru',
        'tmt',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tmt' => 'date',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function activityLogs(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }
}
