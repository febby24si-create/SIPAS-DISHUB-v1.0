<?php

namespace App\Helpers;

use App\Models\UnitKerja;
use Illuminate\Support\Str;

class ArsipPathHelper
{
    /**
     * Bangun folder path arsip berdasarkan ID Seksi (unit_kerja_id), arah, dan tahun.
     *
     * Mengembalikan string path relatif untuk disk 'public', contoh:
     *   arsip/bidang-pelayaran/seksi-kepelabuhan/2026/masuk
     *
     * Jika Seksi atau parent Bidang tidak ditemukan, kembalikan null
     * sehingga pemanggil dapat menggunakan fallback path lama.
     *
     * @param  int|null  $unitKerjaId   ID Seksi (bukan Bidang)
     * @param  string    $arah          'masuk' atau 'keluar'
     * @param  string    $tahun         '2026'
     * @return string|null
     */
    public static function build(?int $unitKerjaId, string $arah, string $tahun): ?string
    {
        if (!$unitKerjaId) {
            return null;
        }

        $seksi = UnitKerja::with('parent')->find($unitKerjaId);

        // Harus ada Seksi dan Bidang-nya (parent bukan null)
        if (!$seksi || !$seksi->parent) {
            return null;
        }

        $bidangSlug = Str::slug($seksi->parent->nama);
        $seksiSlug  = Str::slug($seksi->nama);
        $arahSlug   = in_array($arah, ['masuk', 'keluar']) ? $arah : 'keluar';

        return "arsip/{$bidangSlug}/{$seksiSlug}/{$tahun}/{$arahSlug}";
    }
}
