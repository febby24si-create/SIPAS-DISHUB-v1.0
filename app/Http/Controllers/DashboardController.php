<?php

namespace App\Http\Controllers;

use App\Models\Surat;

class DashboardController extends Controller
{

    public function index()
    {
        // 1. STATISTIK PERSURATAN
        $totalSurat = Surat::where('status', 'final')->count();
        $totalMasuk = Surat::where('status', 'final')->where('arah', 'masuk')->count();
        $totalKeluar = Surat::where('status', 'final')->where('arah', 'keluar')->count();
        $totalDraft = Surat::where('status', 'draft')->count();

        // 2. STATISTIK KEPEGAWAIAN
        $cutiPending = \App\Models\PengajuanCuti::whereIn('status', ['diajukan', 'verifikasi'])->count();
        $kenaikanPangkatPending = \App\Models\KenaikanPangkat::whereIn('status', ['diajukan', 'verifikasi', 'diproses'])->count();
        $gajiBerkalaPending = \App\Models\GajiBerkala::whereIn('status', ['verifikasi', 'disetujui'])->count();

        // 3. AKTIVITAS TERBARU
        $aktivitasTerbaru = \App\Models\ActivityLog::with(['user', 'subject'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalSurat', 
            'totalMasuk', 
            'totalKeluar', 
            'totalDraft',
            'cutiPending',
            'kenaikanPangkatPending',
            'gajiBerkalaPending',
            'aktivitasTerbaru'
        ));
    }
}
