<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class GajiBerkala extends Model
{
    use HasFactory;

    protected $table = 'gaji_berkala';

    protected $fillable = [
        'nomor_usulan',
        'pegawai_id',
        'gaji_pokok_lama',
        'gaji_pokok_baru',
        'tmt_sebelumnya',
        'tmt_berikutnya',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tmt_sebelumnya' => 'date',
        'tmt_berikutnya' => 'date',
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
