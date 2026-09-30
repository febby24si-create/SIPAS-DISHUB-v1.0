<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\PengajuanCuti;
use App\Models\GajiBerkala;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        // 1. STATISTIK PERSURATAN
        // Surat Masuk/Keluar = hanya surat "murni" (bukan arsip KGB/Cuti dari source)
        $suratFinal  = Surat::where('status', 'final')->whereNull('source_type');
        $totalSurat  = (clone $suratFinal)->count();
        $totalMasuk  = (clone $suratFinal)->where('arah', 'masuk')->count();
        $totalKeluar = (clone $suratFinal)->where('arah', 'keluar')->count();
        $totalDraft  = Surat::where('status', 'draft')->count();

        // (Kepegawaian metrics dihapus dari card atas, diganti grafik rekap di bawah)

        // 3. AKTIVITAS TERBARU
        $aktivitasTerbaru = \App\Models\ActivityLog::with(['user', 'subject'])
            ->latest()
            ->take(5)
            ->get();

        // 4. GRAFIK TREN – multi periode
        $periode = in_array($request->get('periode'), ['7_hari', '30_hari', 'bulan'])
            ? $request->get('periode')
            : 'bulan';

        $driver = DB::connection()->getDriverName();

        if ($periode === '7_hari') {
            // 7 titik per hari
            $days     = collect(range(6, 0))->map(fn ($i) => Carbon::today()->subDays($i));
            $dateFrom = $days->first()->startOfDay();

            $fmtSurat = $driver === 'sqlite'
                ? DB::raw("strftime('%Y-%m-%d', tanggal_surat) as periode")
                : DB::raw("DATE_FORMAT(tanggal_surat, '%Y-%m-%d') as periode");
            $fmtCuti = $driver === 'sqlite'
                ? DB::raw("strftime('%Y-%m-%d', tanggal_mulai) as periode")
                : DB::raw("DATE_FORMAT(tanggal_mulai, '%Y-%m-%d') as periode");
            $fmtKgb = $driver === 'sqlite'
                ? DB::raw("strftime('%Y-%m-%d', created_at) as periode")
                : DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as periode");

            $trendLabels = $days->map(fn ($d) => $d->translatedFormat('d M'))->toArray();
            $keyFn       = fn ($d) => $d->format('Y-m-d');
            $keyField    = 'Y-m-d';

        } elseif ($periode === '30_hari') {
            // 30 titik per hari
            $days     = collect(range(29, 0))->map(fn ($i) => Carbon::today()->subDays($i));
            $dateFrom = $days->first()->startOfDay();

            $fmtSurat = $driver === 'sqlite'
                ? DB::raw("strftime('%Y-%m-%d', tanggal_surat) as periode")
                : DB::raw("DATE_FORMAT(tanggal_surat, '%Y-%m-%d') as periode");
            $fmtCuti = $driver === 'sqlite'
                ? DB::raw("strftime('%Y-%m-%d', tanggal_mulai) as periode")
                : DB::raw("DATE_FORMAT(tanggal_mulai, '%Y-%m-%d') as periode");
            $fmtKgb = $driver === 'sqlite'
                ? DB::raw("strftime('%Y-%m-%d', created_at) as periode")
                : DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as periode");

            $trendLabels = $days->map(fn ($d) => $d->translatedFormat('d M'))->toArray();
            $keyFn       = fn ($d) => $d->format('Y-m-d');
            $keyField    = 'Y-m-d';

        } else {
            // Default: 6 bulan per bulan
            $days     = collect(range(5, 0))->map(fn ($i) => Carbon::now()->startOfMonth()->subMonths($i));
            $dateFrom = $days->first();

            $fmtSurat = $driver === 'sqlite'
                ? DB::raw("strftime('%Y-%m', tanggal_surat) as periode")
                : DB::raw("DATE_FORMAT(tanggal_surat, '%Y-%m') as periode");
            $fmtCuti = $driver === 'sqlite'
                ? DB::raw("strftime('%Y-%m', tanggal_mulai) as periode")
                : DB::raw("DATE_FORMAT(tanggal_mulai, '%Y-%m') as periode");
            $fmtKgb = $driver === 'sqlite'
                ? DB::raw("strftime('%Y-%m', created_at) as periode")
                : DB::raw("DATE_FORMAT(created_at, '%Y-%m') as periode");

            $trendLabels = $days->map(fn ($d) => $d->translatedFormat('M Y'))->toArray();
            $keyFn       = fn ($d) => $d->format('Y-m');
            $keyField    = 'Y-m';
        }

        // --- Surat Masuk/Keluar: sumber tabel surat, hanya surat "murni" ---
        $suratRaw = Surat::where('status', 'final')
            ->whereNull('source_type')
            ->where('tanggal_surat', '>=', $dateFrom)
            ->select($fmtSurat, 'arah', DB::raw('COUNT(*) as total'))
            ->groupBy('periode', 'arah')
            ->get()
            ->groupBy('periode');

        $trendMasuk  = $days->map(fn ($d) => (int) optional($suratRaw->get($keyFn($d))?->firstWhere('arah', 'masuk'))->total)->toArray();
        $trendKeluar = $days->map(fn ($d) => (int) optional($suratRaw->get($keyFn($d))?->firstWhere('arah', 'keluar'))->total)->toArray();

        // --- Cuti: sumber tabel pengajuan_cuti, field: tanggal_mulai, status: diterbitkan ---
        $cutiRaw = PengajuanCuti::where('status', 'diterbitkan')
            ->where('tanggal_mulai', '>=', $dateFrom)
            ->select($fmtCuti, DB::raw('COUNT(*) as total'))
            ->groupBy('periode')
            ->get()
            ->keyBy('periode');

        $trendCuti = $days->map(fn ($d) => (int) optional($cutiRaw->get($keyFn($d)))->total)->toArray();

        // --- KGB: sumber tabel gaji_berkala, field: created_at, status: selesai ---
        $kgbRaw = GajiBerkala::where('status', 'selesai')
            ->where('created_at', '>=', $dateFrom)
            ->select($fmtKgb, DB::raw('COUNT(*) as total'))
            ->groupBy('periode')
            ->get()
            ->keyBy('periode');

        $trendKgb = $days->map(fn ($d) => (int) optional($kgbRaw->get($keyFn($d)))->total)->toArray();

        // 5. GRAFIK DISTRIBUSI JENIS SURAT
        $distribusiRaw = Surat::where('status', 'final')
            ->whereNotNull('jenis_surat_id')
            ->with('jenisSurat')
            ->select('jenis_surat_id', DB::raw('COUNT(*) as total'))
            ->groupBy('jenis_surat_id')
            ->get();

        $distribusiLabels = $distribusiRaw->map(fn ($r) => $r->jenisSurat?->nama ?? 'Lainnya')->toArray();
        $distribusiData   = $distribusiRaw->pluck('total')->map(fn ($v) => (int) $v)->toArray();

        // 6. GRAFIK SURAT BERDASARKAN KLASIFIKASI – top 8, hanya surat final
        $klasifikasiRaw = Surat::where('status', 'final')
            ->whereNotNull('klasifikasi_id')
            ->with('klasifikasi')
            ->select('klasifikasi_id', DB::raw('COUNT(*) as total'))
            ->groupBy('klasifikasi_id')
            ->orderByDesc('total')
            ->take(8)
            ->get();

        $klasifikasiLabels = $klasifikasiRaw->map(fn ($r) => $r->klasifikasi?->nama ?? 'Lainnya')->toArray();
        $klasifikasiData   = $klasifikasiRaw->pluck('total')->map(fn ($v) => (int) $v)->toArray();

        // 7. GRAFIK SURAT BERDASARKAN UNIT KERJA
        $unitKerjaRaw = Surat::where('status', 'final')
            ->whereNull('source_type')
            ->with('unitKerja')
            ->select('unit_kerja_id', DB::raw('COUNT(*) as total'))
            ->groupBy('unit_kerja_id')
            ->orderByDesc('total')
            ->get();

        $unitKerjaLabels = $unitKerjaRaw->map(fn ($r) => $r->unitKerja?->nama ?? 'Tanpa Unit Kerja')->toArray();
        $unitKerjaData   = $unitKerjaRaw->pluck('total')->map(fn ($v) => (int) $v)->toArray();

        // 8. GRAFIK REKAP KEPEGAWAIAN
        $rekapCuti = PengajuanCuti::where('status', 'diterbitkan')->count();
        $rekapKgb  = GajiBerkala::where('status', 'selesai')->count();
        $rekapKepegawaianLabels = ['Cuti', 'KGB'];
        $rekapKepegawaianData   = [$rekapCuti, $rekapKgb];

        return view('dashboard', compact(
            'totalSurat',
            'totalMasuk',
            'totalKeluar',
            'totalDraft',
            'aktivitasTerbaru',
            'trendLabels',
            'trendMasuk',
            'trendKeluar',
            'trendCuti',
            'trendKgb',
            'periode',
            'distribusiLabels',
            'distribusiData',
            'klasifikasiLabels',
            'klasifikasiData',
            'unitKerjaLabels',
            'unitKerjaData',
            'rekapKepegawaianLabels',
            'rekapKepegawaianData'
        ));
    }

    // -------------------------------------------------------
    // Endpoint JSON untuk AJAX periode chart (tanpa reload)
    // GET /dashboard/trend?periode=7_hari|30_hari|bulan
    // -------------------------------------------------------
    public function trend(Request $request): \Illuminate\Http\JsonResponse
    {
        $periode = in_array($request->get('periode'), ['7_hari', '30_hari', 'bulan'])
            ? $request->get('periode')
            : 'bulan';

        $driver = DB::connection()->getDriverName();

        if ($periode === '7_hari') {
            $days     = collect(range(6, 0))->map(fn ($i) => Carbon::today()->subDays($i));
            $dateFrom = $days->first()->copy()->startOfDay();
            $fmtSurat = $driver === 'sqlite'
                ? DB::raw("strftime('%Y-%m-%d', tanggal_surat) as periode")
                : DB::raw("DATE_FORMAT(tanggal_surat, '%Y-%m-%d') as periode");
            $fmtCuti  = $driver === 'sqlite'
                ? DB::raw("strftime('%Y-%m-%d', tanggal_mulai) as periode")
                : DB::raw("DATE_FORMAT(tanggal_mulai, '%Y-%m-%d') as periode");
            $fmtKgb   = $driver === 'sqlite'
                ? DB::raw("strftime('%Y-%m-%d', created_at) as periode")
                : DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as periode");
            $keyFn    = fn ($d) => $d->format('Y-m-d');
            $labelFn  = fn ($d) => $d->translatedFormat('d M');

        } elseif ($periode === '30_hari') {
            $days     = collect(range(29, 0))->map(fn ($i) => Carbon::today()->subDays($i));
            $dateFrom = $days->first()->copy()->startOfDay();
            $fmtSurat = $driver === 'sqlite'
                ? DB::raw("strftime('%Y-%m-%d', tanggal_surat) as periode")
                : DB::raw("DATE_FORMAT(tanggal_surat, '%Y-%m-%d') as periode");
            $fmtCuti  = $driver === 'sqlite'
                ? DB::raw("strftime('%Y-%m-%d', tanggal_mulai) as periode")
                : DB::raw("DATE_FORMAT(tanggal_mulai, '%Y-%m-%d') as periode");
            $fmtKgb   = $driver === 'sqlite'
                ? DB::raw("strftime('%Y-%m-%d', created_at) as periode")
                : DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as periode");
            $keyFn    = fn ($d) => $d->format('Y-m-d');
            $labelFn  = fn ($d) => $d->translatedFormat('d M');

        } else {
            $days     = collect(range(5, 0))->map(fn ($i) => Carbon::now()->startOfMonth()->subMonths($i));
            $dateFrom = $days->first();
            $fmtSurat = $driver === 'sqlite'
                ? DB::raw("strftime('%Y-%m', tanggal_surat) as periode")
                : DB::raw("DATE_FORMAT(tanggal_surat, '%Y-%m') as periode");
            $fmtCuti  = $driver === 'sqlite'
                ? DB::raw("strftime('%Y-%m', tanggal_mulai) as periode")
                : DB::raw("DATE_FORMAT(tanggal_mulai, '%Y-%m') as periode");
            $fmtKgb   = $driver === 'sqlite'
                ? DB::raw("strftime('%Y-%m', created_at) as periode")
                : DB::raw("DATE_FORMAT(created_at, '%Y-%m') as periode");
            $keyFn    = fn ($d) => $d->format('Y-m');
            $labelFn  = fn ($d) => $d->translatedFormat('M Y');
        }

        // Surat masuk/keluar — surat "murni" saja
        $suratRaw = Surat::where('status', 'final')
            ->whereNull('source_type')
            ->where('tanggal_surat', '>=', $dateFrom)
            ->select($fmtSurat, 'arah', DB::raw('COUNT(*) as total'))
            ->groupBy('periode', 'arah')
            ->get()
            ->groupBy('periode');

        $masuk  = $days->map(fn ($d) => (int) optional($suratRaw->get($keyFn($d))?->firstWhere('arah', 'masuk'))->total)->values()->toArray();
        $keluar = $days->map(fn ($d) => (int) optional($suratRaw->get($keyFn($d))?->firstWhere('arah', 'keluar'))->total)->values()->toArray();

        // Cuti — tanggal_mulai, status diterbitkan
        $cutiRaw = \App\Models\PengajuanCuti::where('status', 'diterbitkan')
            ->where('tanggal_mulai', '>=', $dateFrom)
            ->select($fmtCuti, DB::raw('COUNT(*) as total'))
            ->groupBy('periode')
            ->get()
            ->keyBy('periode');

        $cuti = $days->map(fn ($d) => (int) optional($cutiRaw->get($keyFn($d)))->total)->values()->toArray();

        // KGB — created_at, status selesai
        $kgbRaw = \App\Models\GajiBerkala::where('status', 'selesai')
            ->where('created_at', '>=', $dateFrom)
            ->select($fmtKgb, DB::raw('COUNT(*) as total'))
            ->groupBy('periode')
            ->get()
            ->keyBy('periode');

        $kgb    = $days->map(fn ($d) => (int) optional($kgbRaw->get($keyFn($d)))->total)->values()->toArray();
        $labels = $days->map(fn ($d) => $labelFn($d))->values()->toArray();

        return response()->json([
            'labels' => $labels,
            'masuk'  => $masuk,
            'keluar' => $keluar,
            'cuti'   => $cuti,
            'kgb'    => $kgb,
        ]);
    }
}

