<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\KlasifikasiSurat;
use App\Models\Surat;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    // ── Shared: resolve & validate filter params ──────────────────────────────
    private function resolveFilters(Request $request): array
    {
        $dari          = $request->filled('dari')           ? $request->dari           : null;
        $sampai        = $request->filled('sampai')         ? $request->sampai         : null;
        $arah          = $request->filled('arah')           ? $request->arah           : null;
        $jenisSuratId  = $request->filled('jenis_surat_id') ? $request->jenis_surat_id : null;
        $klasifikasiId = $request->filled('klasifikasi_id') ? $request->klasifikasi_id : null;
        $bidangId      = $request->filled('bidang_id')      ? $request->bidang_id      : null;
        $seksiId       = $request->filled('seksi_id')       ? $request->seksi_id       : null;

        // Validasi Bidang & Seksi
        if ($bidangId && $seksiId) {
            $seksi = UnitKerja::where('id', $seksiId)->where('parent_id', $bidangId)->first();
            if (!$seksi) {
                $seksiId = null;
            }
        }

        return compact('dari', 'sampai', 'arah', 'jenisSuratId', 'klasifikasiId', 'bidangId', 'seksiId');
    }

    // ── Shared: build filtered base query (status = 'final') ─────────────────
    private function buildBase(array $f)
    {
        return Surat::where('status', 'final')
            ->when($f['dari'],          fn ($q) => $q->whereDate('tanggal_surat', '>=', $f['dari']))
            ->when($f['sampai'],        fn ($q) => $q->whereDate('tanggal_surat', '<=', $f['sampai']))
            ->when($f['arah'],          fn ($q) => $q->where('arah', $f['arah']))
            ->when($f['jenisSuratId'],  fn ($q) => $q->where('jenis_surat_id', $f['jenisSuratId']))
            ->when($f['klasifikasiId'], fn ($q) => $q->where('klasifikasi_id', $f['klasifikasiId']))
            ->when($f['seksiId'],       fn ($q) => $q->where('unit_kerja_id', $f['seksiId']))
            ->when($f['bidangId'] && !$f['seksiId'], fn ($q) => $q->whereIn('unit_kerja_id', function ($query) use ($f) {
                $query->select('id')->from('unit_kerja')->where('parent_id', $f['bidangId']);
            }));
    }

    // ── Shared: compute statistics from base query ────────────────────────────
    private function computeStats($base): array
    {
        $totalMasuk  = (clone $base)->where('arah', 'masuk')->count();
        $totalKeluar = (clone $base)->where('arah', 'keluar')->count();
        $totalSemua  = $totalMasuk + $totalKeluar;
        $totalDraft  = Surat::where('status', 'draft')->count();

        $perJenisRaw = (clone $base)
            ->whereNotNull('jenis_surat_id')
            ->select('jenis_surat_id', 'arah', DB::raw('COUNT(*) as total'))
            ->groupBy('jenis_surat_id', 'arah')
            ->with('jenisSurat')
            ->orderByDesc(DB::raw('COUNT(*)'))
            ->get();

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

        return compact('totalMasuk', 'totalKeluar', 'totalSemua', 'totalDraft', 'perJenis');
    }

    // ── Shared: load master data for dropdowns / labels ───────────────────────
    private function masterData(): array
    {
        return [
            'jenisSuratList'  => JenisSurat::orderBy('nama')->get(),
            'klasifikasiList' => KlasifikasiSurat::where('status', 'aktif')->orderBy('nama')->get(),
            'bidangs'         => UnitKerja::whereNull('parent_id')->orderBy('nama')->get(),
            'seksis'          => UnitKerja::whereNotNull('parent_id')->orderBy('nama')->get(),
        ];
    }

    // ── index ─────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $f       = $this->resolveFilters($request);
        $base    = $this->buildBase($f);
        $stats   = $this->computeStats($base);
        $master  = $this->masterData();

        $filterAktif = array_filter([
            'dari'           => $f['dari'],
            'sampai'         => $f['sampai'],
            'arah'           => $f['arah'],
            'jenis_surat_id' => $f['jenisSuratId'],
            'klasifikasi_id' => $f['klasifikasiId'],
            'bidang_id'      => $f['bidangId'],
            'seksi_id'       => $f['seksiId'],
        ]);
        $adaFilter = !empty($filterAktif);

        return view('laporan.index', array_merge($stats, $master, $f, compact('adaFilter')));
    }

    // ── print ─────────────────────────────────────────────────────────────────
    public function print(Request $request)
    {
        $f      = $this->resolveFilters($request);
        $base   = $this->buildBase($f);
        $stats  = $this->computeStats($base);
        $master = $this->masterData();

        $bidangNama = $f['bidangId']
            ? optional($master['bidangs']->firstWhere('id', $f['bidangId']))->nama
            : null;
        $seksiNama = $f['seksiId']
            ? optional($master['seksis']->firstWhere('id', $f['seksiId']))->nama
            : null;
        $jenisSuratNama = $f['jenisSuratId']
            ? optional($master['jenisSuratList']->firstWhere('id', $f['jenisSuratId']))->nama
            : null;
        $klasifikasiNama = $f['klasifikasiId']
            ? optional($master['klasifikasiList']->firstWhere('id', $f['klasifikasiId']))->nama
            : null;

        return view('laporan.print', array_merge($stats, $f, compact(
            'bidangNama',
            'seksiNama',
            'jenisSuratNama',
            'klasifikasiNama'
        )));
    }
}
