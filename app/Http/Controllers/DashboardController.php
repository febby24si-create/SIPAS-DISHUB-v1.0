<?php

namespace App\Http\Controllers;

use App\Models\Surat;

class DashboardController extends Controller
{

    public function index()
    {

        $totalSurat = Surat::count();
        $totalMasuk = Surat::masuk()->count();
        $totalKeluar = Surat::keluar()->count();

        $suratTerbaru = Surat::with(['jenisSurat', 'creator'])
            ->latest()
            ->take(5)
            ->get();
        return view('dashboard', compact('totalSurat', 'totalMasuk', 'totalKeluar', 'suratTerbaru'));
    }
}
