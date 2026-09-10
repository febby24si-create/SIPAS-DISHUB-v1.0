<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{

    public function index()
    {
        // 1. STATISTIK PERSURATAN
        $totalSurat  = Surat::where('status', 'final')->count();
        $totalMasuk  = Surat::where('status', 'final')->where('arah', 'masuk')->count();
        $totalKeluar = Surat::where('status', 'final')->where('arah', 'keluar')->count();
        $totalDraft  = Surat::where('status', 'draft')->count();

        // 2. STATISTIK KEPEGAWAIAN
        $cutiPending            = \App\Models\PengajuanCuti::whereIn('status', ['diajukan', 'verifikasi'])->count();
        $kenaikanPangkatPending = \App\Models\KenaikanPangkat::whereIn('status', ['diajukan', 'verifikasi', 'diproses'])->count();
        $gajiBerkalaPending     = \App\Models\GajiBerkala::whereIn('status', ['verifikasi', 'disetujui'])->count();

        // 3. AKTIVITAS TERBARU
        $aktivitasTerbaru = \App\Models\ActivityLog::with(['user', 'subject'])
            ->latest()
            ->take(5)
            ->get();

        // 4. GRAFIK TREN SURAT – 6 bulan terakhir
        $bulanList = collect(range(5, 0))->map(fn ($i) => Carbon::now()->startOfMonth()->subMonths($i));

        // Driver-aware format (MySQL: DATE_FORMAT, SQLite: strftime)
        $driver      = DB::connection()->getDriverName();
        $formatBulan = $driver === 'sqlite'
            ? DB::raw("strftime('%Y-%m', tanggal_surat) as bulan")
            : DB::raw("DATE_FORMAT(tanggal_surat, '%Y-%m') as bulan");

        $trendRaw = Surat::where('status', 'final')
            ->where('tanggal_surat', '>=', $bulanList->first())
            ->select($formatBulan, 'arah', DB::raw('COUNT(*) as total'))
            ->groupBy('bulan', 'arah')
            ->get()
            ->groupBy('bulan');

        $trendLabels = $bulanList->map(fn ($d) => $d->translatedFormat('M Y'))->toArray();
        $trendMasuk  = $bulanList->map(fn ($d) => (int) optional($trendRaw->get($d->format('Y-m'))?->firstWhere('arah', 'masuk'))->total)->toArray();
        $trendKeluar = $bulanList->map(fn ($d) => (int) optional($trendRaw->get($d->format('Y-m'))?->firstWhere('arah', 'keluar'))->total)->toArray();

        // 5. GRAFIK DISTRIBUSI JENIS SURAT
        $distribusiRaw = Surat::where('status', 'final')
            ->whereNotNull('jenis_surat_id')
            ->with('jenisSurat')
            ->select('jenis_surat_id', DB::raw('COUNT(*) as total'))
            ->groupBy('jenis_surat_id')
            ->get();

        $distribusiLabels = $distribusiRaw->map(fn ($r) => $r->jenisSurat?->kode ?? 'Lainnya')->toArray();
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

        return view('dashboard', compact(
            'totalSurat',
            'totalMasuk',
            'totalKeluar',
            'totalDraft',
            'cutiPending',
            'kenaikanPangkatPending',
            'gajiBerkalaPending',
            'aktivitasTerbaru',
            'trendLabels',
            'trendMasuk',
            'trendKeluar',
            'distribusiLabels',
            'distribusiData',
            'klasifikasiLabels',
            'klasifikasiData'
        ));
    }
}
