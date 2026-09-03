<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Illuminate\Http\Request;

class SuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        $suratKeluar = Surat::keluar()
            ->where('status', 'final')
            ->with('jenisSurat')
            ->when($request->filled('q'), fn ($q) => $q->where('perihal', 'like', '%' . $request->q . '%'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('surat-keluar.index', compact('suratKeluar'));
    }

    public function draft(Request $request)
    {
        $draftSurat = Surat::keluar()
            ->where('status', 'draft')
            ->with('jenisSurat')
            ->latest()
            ->paginate(10);

        return view('surat-keluar.draft', compact('draftSurat'));
    }
}
