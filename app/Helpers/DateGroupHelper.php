<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Pengelompokan baris tabel berdasarkan tanggal input (created_at).
 *
 * Dipakai oleh tabel Surat Masuk, Surat Keluar, dan Draft Surat Keluar.
 * Tanggal dihitung memakai zona waktu aplikasi (default Asia/Jakarta) supaya
 * surat di sekitar pergantian tengah malam tidak salah kelompok.
 *
 * Nomor urut baris tidak disimpan ke database dan tetap mengikuti perilaku
 * pagination yang sudah ada (lanjut antar halaman, tidak kembali ke 1).
 */
class DateGroupHelper
{
    public const TODAY_LABEL = 'Hari Ini';

    public const YESTERDAY_LABEL = 'Kemarin';

    /** Locale tampilan label tanggal (Selasa, 13 Oktober 2026). */
    public const LOCALE = 'id';

    /** Kunci kelompok untuk data tanpa created_at. */
    public const NO_DATE_KEY = 'tanpa-tanggal';

    public static function timezone(?string $timezone = null): string
    {
        return $timezone ?: (config('app.timezone') ?: 'Asia/Jakarta');
    }

    /**
     * Kelompokkan halaman aktif berdasarkan tanggal input.
     *
     * @return array<int, array{key: string, prefix: ?string, label: string, count: int, items: array<int, array{surat: mixed, no: int, pos: int}>}>
     */
    public static function groupByInputDate(LengthAwarePaginator $paginator, ?string $timezone = null): array
    {
        $timezone = self::timezone($timezone);
        $today = Carbon::now($timezone)->startOfDay();
        $todayKey = $today->toDateString();
        $yesterdayKey = $today->copy()->subDay()->toDateString();

        // Nomor urut tetap memakai perilaku pagination yang sudah berjalan.
        $firstItem = $paginator->firstItem() ?: 1;

        $groups = [];
        $position = 0;

        foreach ($paginator as $item) {
            $createdAt = $item->created_at ? $item->created_at->copy()->setTimezone($timezone) : null;
            $day = $createdAt ? $createdAt->copy()->startOfDay() : null;
            $key = $day ? $day->toDateString() : self::NO_DATE_KEY;

            if (! isset($groups[$key])) {
                $groups[$key] = self::makeGroup($day, $todayKey, $yesterdayKey);
            }

            $groups[$key]['items'][] = [
                'surat' => $item,
                'no' => $firstItem + $position, // nomor urut lanjut antar halaman
                'pos' => $position + 1,         // nomor urut per halaman (draft)
            ];
            $groups[$key]['count']++;
            $position++;
        }

        return array_values($groups);
    }

    /**
     * Struktur kelompok "Hari Ini" tanpa data, dipakai sebagai template
     * header kelompok saat baris baru disisipkan lewat AJAX di sisi klien.
     */
    public static function todayGroup(?string $timezone = null): array
    {
        $timezone = self::timezone($timezone);
        $today = Carbon::now($timezone)->startOfDay();

        return self::makeGroup($today, $today->toDateString(), $today->copy()->subDay()->toDateString());
    }

    /**
     * @return array{key: string, prefix: ?string, label: string, count: int, items: array<int, mixed>}
     */
    private static function makeGroup(?Carbon $day, string $todayKey, string $yesterdayKey): array
    {
        if (! $day) {
            return [
                'key' => self::NO_DATE_KEY,
                'prefix' => null,
                'label' => 'Tanpa tanggal input',
                'count' => 0,
                'items' => [],
            ];
        }

        $key = $day->toDateString();

        return [
            'key' => $key,
            'prefix' => $key === $todayKey
                ? self::TODAY_LABEL
                : ($key === $yesterdayKey ? self::YESTERDAY_LABEL : null),
            'label' => $day->locale(self::LOCALE)->translatedFormat('l, d F Y'),
            'count' => 0,
            'items' => [],
        ];
    }
}
