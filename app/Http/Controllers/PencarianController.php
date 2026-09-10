<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Illuminate\Http\Request;

class PencarianController extends Controller
{
    public function index(Request $request)
    {
        $hasil = null;

        // Cek apakah ada parameter pencarian yang dikirim (selain sekadar buka halaman)
        $isSearching = $request->hasAny(['q', 'tanggal', 'arah', 'jenis_surat_id', 'klasifikasi_id', 'pengirim', 'tujuan']);

        if ($isSearching) {
            $hasil = Surat::with(['jenisSurat', 'klasifikasi'])
                ->where('status', 'final') // KRUSIAL: Sembunyikan draft
                ->when($request->filled('q'), function ($query) use ($request) {
                    $keyword = $request->q;
                    $query->where(function ($qq) use ($keyword) {
                        $qq->where('nomor_surat', 'like', '%' . $keyword . '%')
                           ->orWhere('perihal', 'like', '%' . $keyword . '%');
                    });
                })
                ->when($request->filled('tanggal'), fn ($q) => $q->whereDate('tanggal_surat', $request->tanggal))
                ->when($request->filled('arah'), fn ($q) => $q->where('arah', $request->arah))
                ->when($request->filled('jenis_surat_id'), fn ($q) => $q->where('jenis_surat_id', $request->jenis_surat_id))
                ->when($request->filled('klasifikasi_id'), fn ($q) => $q->where('klasifikasi_id', $request->klasifikasi_id))
                ->when($request->filled('pengirim'), fn ($q) => $q->where('pengirim', 'like', '%' . $request->pengirim . '%'))
                ->when($request->filled('tujuan'), fn ($q) => $q->where('tujuan', 'like', '%' . $request->tujuan . '%'))
                ->latest()
                ->paginate(10)
                ->withQueryString();
        }

        $jenisSuratList = \App\Models\JenisSurat::orderBy('nama')->get();
        $klasifikasiList = \App\Models\KlasifikasiSurat::orderBy('nama')->get();

        return view('pencarian.index', compact(
            'hasil', 'jenisSuratList', 'klasifikasiList'
        ));
    }
}
