<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Illuminate\Http\Request;

class PencarianController extends Controller
{
    public function index(Request $request)
    {
        $hasil = null;

        if ($request->filled('q') || $request->filled('tanggal')) {
            $hasil = Surat::with('jenisSurat')
                ->when($request->filled('q'), function ($query) use ($request) {
                    $keyword = $request->q;
                    $query->where(function ($qq) use ($keyword) {
                        $qq->where('nomor_surat', 'like', '%' . $keyword . '%')
                           ->orWhere('perihal', 'like', '%' . $keyword . '%');
                    });
                })
                ->when($request->filled('tanggal'), fn ($q) => $q->whereDate('tanggal_surat', $request->tanggal))
                ->latest()
                ->paginate(10)
                ->withQueryString();
        }

        return view('pencarian.index', [
            'hasil' => $hasil,
            'q' => $request->q,
            'tanggal' => $request->tanggal,
        ]);
    }
}
