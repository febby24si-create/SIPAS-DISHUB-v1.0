<?php

namespace App\Services;

use App\Models\KlasifikasiSurat;
use App\Models\DocumentSequence;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DocumentNumberService
{
    public static function generate(?int $klasifikasiId, ?Carbon $tanggal = null): string
    {
        $tanggal = $tanggal ?? Carbon::now();
        $tahun = $tanggal->year;
        $bulan = $tanggal->format('m');

        $kode = $klasifikasiId
            ? optional(KlasifikasiSurat::find($klasifikasiId))->kode
            : 'UMUM';

        // Menggunakan $kode sebagai representasi kategori sequence
        $kategori = $kode;

        $lastNumber = DB::transaction(function () use ($kategori, $tahun) {
            $sequence = DocumentSequence::lockForUpdate()->firstOrCreate(
                ['kategori' => $kategori, 'tahun' => $tahun],
                ['last_number' => 0]
            );

            $sequence->last_number += 1;
            $sequence->save();

            return $sequence->last_number;
        });

        // Mengganti placeholder format nomor.
        // Format default jika tidak ada di config: '{urut}/{kode}/{bulan}/{tahun}'
        return str_replace(
            ['{urut}', '{kode}', '{bulan}', '{tahun}'],
            [str_pad((string) $lastNumber, 3, '0', STR_PAD_LEFT), $kode, $bulan, $tahun],
            config('persuratan.format_nomor', '{urut}/{kode}/{bulan}/{tahun}')
        );
    }

    public static function previewNextNumber(?int $klasifikasiId, ?Carbon $tanggal = null): string
    {
        $tanggal = $tanggal ?? Carbon::now();
        $tahun = $tanggal->year;
        $bulan = $tanggal->format('m');

        $kode = $klasifikasiId
            ? optional(KlasifikasiSurat::find($klasifikasiId))->kode
            : 'UMUM';

        $kategori = $kode;

        // Baca data tanpa lock dan tanpa membuat row baru
        $sequence = DocumentSequence::where('kategori', $kategori)->where('tahun', $tahun)->first();
        $nextNumber = $sequence ? $sequence->last_number + 1 : 1;

        return str_replace(
            ['{urut}', '{kode}', '{bulan}', '{tahun}'],
            [str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT), $kode, $bulan, $tahun],
            config('persuratan.format_nomor', '{urut}/{kode}/{bulan}/{tahun}')
        );
    }
}
