<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\KlasifikasiSurat;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // ── FILTER PARAMS ────────────────────────────────────────────────────
        $dari          = $request->filled('dari')           ? $request->dari           : null;
        $sampai        = $request->filled('sampai')         ? $request->sampai         : null;
        $arah          = $request->filled('arah')           ? $request->arah           : null;
        $jenisSuratId  = $request->filled('jenis_surat_id') ? $request->jenis_surat_id : null;
        $klasifikasiId = $request->filled('klasifikasi_id') ? $request->klasifikasi_id : null;

        // ── BASE QUERY: hanya surat FINAL ────────────────────────────────────
        // Definisi konsisten dengan Dashboard: status = 'final'
        $base = Surat::where('status', 'final')
            ->when($dari,          fn ($q) => $q->whereDate('tanggal_surat', '>=', $dari))
            ->when($sampai,        fn ($q) => $q->whereDate('tanggal_surat', '<=', $sampai))
            ->when($arah,          fn ($q) => $q->where('arah', $arah))
            ->when($jenisSuratId,  fn ($q) => $q->where('jenis_surat_id', $jenisSuratId))
            ->when($klasifikasiId, fn ($q) => $q->where('klasifikasi_id', $klasifikasiId));

        // ── STATISTIK UTAMA ───────────────────────────────────────────────────
        // totalMasuk: surat masuk final (ikut filter, kecuali filter arah sudah
        //             men-scope, maka clone base sudah benar)
        $totalMasuk  = (clone $base)->where('arah', 'masuk')->count();
        $totalKeluar = (clone $base)->where('arah', 'keluar')->count();
        $totalSemua  = $totalMasuk + $totalKeluar;

        // totalDraft: all-time, semua arah, tidak difilter periode/arah.
        // Definisi sama dengan Dashboard: kondisi kerja aktif saat ini.
        $totalDraft = Surat::where('status', 'draft')->count();

        // ── STATISTIK PER JENIS ───────────────────────────────────────────────
        // Hanya surat final, whereNotNull jenis_surat_id, pisah masuk vs keluar,
        // ikut seluruh filter aktif.
        $perJenisRaw = (clone $base)
            ->whereNotNull('jenis_surat_id')
            ->select('jenis_surat_id', 'arah', DB::raw('COUNT(*) as total'))
            ->groupBy('jenis_surat_id', 'arah')
            ->with('jenisSurat')
            ->orderByDesc(DB::raw('COUNT(*)'))
            ->get();

        // Kelompokkan per jenis_surat_id → { masuk: n, keluar: n }
        $perJenis = $perJenisRaw->groupBy('jenis_surat_id')->map(function ($rows) {
            $masuk  = optional($rows->firstWhere('arah', 'masuk'))->total  ?? 0;
            $keluar = optional($rows->firstWhere('arah', 'keluar'))->total ?? 0;
            return [
                'jenisSurat' => $rows->first()->jenisSurat,
                'masuk'      => (int) $masuk,
                'keluar'     => (int) $keluar,
                'total'      => (int) $masuk + (int) $keluar,
            ];
        })->sortByDesc('total')->values();

        // ── MASTER DATA untuk dropdown filter ────────────────────────────────
        $jenisSuratList  = JenisSurat::orderBy('nama')->get();
        $klasifikasiList = KlasifikasiSurat::where('status', 'aktif')->orderBy('nama')->get();

        // ── FILTER AKTIF (untuk info banner di view) ──────────────────────────
        $filterAktif = array_filter([
            'dari'           => $dari,
            'sampai'         => $sampai,
            'arah'           => $arah,
            'jenis_surat_id' => $jenisSuratId,
            'klasifikasi_id' => $klasifikasiId,
        ]);
        $adaFilter = ! empty($filterAktif);

        return view('laporan.index', compact(
            'totalMasuk',
            'totalKeluar',
            'totalSemua',
            'totalDraft',
            'perJenis',
            'jenisSuratList',
            'klasifikasiList',
            'dari',
            'sampai',
            'arah',
            'jenisSuratId',
            'klasifikasiId',
            'adaFilter'
        ));
    }
}
