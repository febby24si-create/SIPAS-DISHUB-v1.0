<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\KlasifikasiSurat;
use App\Models\Surat;
use App\Models\TemplateSurat;
use App\Services\NomorSuratGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;

class SuratController extends Controller
{
    public function create()
    {
        $templates = TemplateSurat::with('jenisSurat')->where('status', 'aktif')->get();
        $klasifikasiList = KlasifikasiSurat::where('status', 'aktif')->get();

        return view('buat-surat.create', compact('templates', 'klasifikasiList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'template_surat_id' => 'required|exists:template_surat,id',
            'klasifikasi_id' => 'nullable|exists:klasifikasi_surat,id',
            'perihal' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'tujuan' => 'nullable|string|max:255',
            'data' => 'nullable|array',
        ]);

        $template = TemplateSurat::findOrFail($validated['template_surat_id']);
        $nomorSurat = NomorSuratGenerator::generate($validated['klasifikasi_id'] ?? null);

        // Generate file Word dari template
        $templatePath = Storage::disk('public')->path($template->file_template);
        $processor = new TemplateProcessor($templatePath);

        $processor->setValue('nomor', $nomorSurat);
        $processor->setValue('tanggal', \Carbon\Carbon::parse($validated['tanggal_surat'])->translatedFormat('d F Y'));
        $processor->setValue('perihal', $validated['perihal']);

        foreach ($validated['data'] ?? [] as $key => $value) {
            $processor->setValue($key, $value);
        }

        Storage::disk('public')->makeDirectory('generated');
        $fileName = 'surat_' . time() . '_' . str()->random(6);
        $wordRelativePath = 'generated/' . $fileName . '.docx';
        $wordFullPath = Storage::disk('public')->path($wordRelativePath);
        $processor->saveAs($wordFullPath);

        // Konversi ke PDF via LibreOffice (perlu terpasang di server — lihat Langkah 37)
        $pdfRelativePath = null;
        $outputDir = dirname($wordFullPath);
        shell_exec('soffice --headless --convert-to pdf --outdir ' . escapeshellarg($outputDir) . ' ' . escapeshellarg($wordFullPath) . ' 2>&1');
        if (file_exists($outputDir . '/' . $fileName . '.pdf')) {
            $pdfRelativePath = 'generated/' . $fileName . '.pdf';
        }

        $surat = Surat::create([
            'jenis_surat_id' => $template->jenis_surat_id,
            'klasifikasi_id' => $validated['klasifikasi_id'] ?? null,
            'template_surat_id' => $template->id,
            'arah' => 'keluar',
            'nomor_surat' => $nomorSurat,
            'tanggal_surat' => $validated['tanggal_surat'],
            'perihal' => $validated['perihal'],
            'tujuan' => $validated['tujuan'] ?? null,
            'file_word' => $wordRelativePath,
            'file_pdf' => $pdfRelativePath,
            'status' => 'draft', // final setelah dikonfirmasi di halaman preview
            'created_by' => auth()->id(),
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'surat_id' => $surat->id,
            'aktivitas' => 'membuat surat (draft): ' . $surat->perihal,
        ]);

        return redirect()->route('buat-surat.show', $surat)->with('status', 'Surat berhasil dibuat, silakan cek preview.');
    }

    public function show(Surat $surat)
    {
        return view('buat-surat.show', compact('surat'));
    }

    public function finalize(Surat $surat)
    {
        $surat->update(['status' => 'final']);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'surat_id' => $surat->id,
            'aktivitas' => 'finalisasi surat: ' . $surat->perihal,
        ]);

        return redirect()->route('buat-surat.show', $surat)->with('status', 'Surat difinalisasi dan tersimpan di arsip.');
    }
}