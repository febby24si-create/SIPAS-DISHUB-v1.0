<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Illuminate\Http\Request;

class ArsipController extends Controller
{
    public function index(Request $request)
    {
        $arsip = Surat::where('status', 'final')
            ->with('jenisSurat')
            ->when($request->filled('arah'), fn ($q) => $q->where('arah', $request->arah))
            ->when($request->filled('q'), fn ($q) => $q->where('perihal', 'like', '%' . $request->q . '%'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('arsip.index', compact('arsip'));
    }
}
