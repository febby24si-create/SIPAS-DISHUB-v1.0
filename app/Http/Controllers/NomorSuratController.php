<?php

namespace App\Http\Controllers;

use App\Services\NomorSuratGenerator;

class NomorSuratController extends Controller
{
    public function index()
    {
        $format = config('persuratan.format_nomor');
        $contoh = NomorSuratGenerator::generate(null);

        return view('nomor-surat.index', compact('format', 'contoh'));
    }
}
