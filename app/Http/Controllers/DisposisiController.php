<?php

namespace App\Http\Controllers;

use App\Models\Surat;

class DisposisiController extends Controller
{
    public function index()
    {
        $suratMasuk = Surat::masuk()->with('jenisSurat')->latest()->take(10)->get();

        return view('disposisi.index', compact('suratMasuk'));
    }
}
