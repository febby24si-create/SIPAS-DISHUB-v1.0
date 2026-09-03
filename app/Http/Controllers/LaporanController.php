<?php

namespace App\Http\Controllers;

use App\Models\Surat;

class LaporanController extends Controller
{
    public function index()
    {
        $totalMasuk = Surat::masuk()->count();
        $totalKeluar = Surat::keluar()->where('status', 'final')->count();
        $totalDraft = Surat::keluar()->where('status', 'draft')->count();

        $perJenis = Surat::selectRaw('jenis_surat_id, count(*) as total')
            ->groupBy('jenis_surat_id')
            ->with('jenisSurat')
            ->get();

        return view('laporan.index', compact('totalMasuk', 'totalKeluar', 'totalDraft', 'perJenis'));
    }
}
