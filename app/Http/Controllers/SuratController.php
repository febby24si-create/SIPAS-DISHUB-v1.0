<?php

namespace App\Http\Controllers;

use App\Models\Surat;

class SuratController extends Controller
{
    /**
     * Tampilkan detail surat / viewer dokumen legacy (termasuk Cuti/KGB jika masih menggunakan).
     * Dependency terhadap PHPWord, TemplateProcessor, dan soffice telah dihilangkan.
     */
    public function show(Surat $surat)
    {
        return view('buat-surat.show', compact('surat'));
    }
}