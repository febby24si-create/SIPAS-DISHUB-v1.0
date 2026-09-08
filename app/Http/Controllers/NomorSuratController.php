<?php

namespace App\Http\Controllers;

use App\Services\DocumentNumberService;

class NomorSuratController extends Controller
{
    public function index()
    {
        $format = config('persuratan.format_nomor');
        $contoh = DocumentNumberService::previewNextNumber(null);

        return view('nomor-surat.index', compact('format', 'contoh'));
    }
}
