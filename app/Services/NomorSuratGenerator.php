<?php

namespace App\Services;

use App\Models\KlasifikasiSurat;
use App\Models\Surat;
use Carbon\Carbon;

class NomorSuratGenerator
{
    public static function generate(?int $klasifikasiId): string
    {
        $tahun = Carbon::now()->year;

        $urut = Surat::whereYear('created_at', $tahun)
            ->when($klasifikasiId, fn ($q) => $q->where('klasifikasi_id', $klasifikasiId))
            ->count() + 1;

        $kode = $klasifikasiId
            ? optional(KlasifikasiSurat::find($klasifikasiId))->kode
            : 'UMUM';

        return str_replace(
            ['{urut}', '{kode}', '{tahun}'],
            [str_pad((string) $urut, 3, '0', STR_PAD_LEFT), $kode, $tahun],
            config('persuratan.format_nomor')
        );
    }
}
