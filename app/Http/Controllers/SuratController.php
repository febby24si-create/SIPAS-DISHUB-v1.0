<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\JenisSurat;
use App\Models\KlasifikasiSurat;
use App\Models\Surat;
use App\Models\TemplateSurat;
use App\Services\DocumentNumberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;

class SuratController extends Controller
{
    /**
     * Langkah 1: Pilih Jenis Surat.
     */
    public function create()
    {
        $jenisSuratList = JenisSurat::withCount([
                'templates' => fn ($q) => $q->where('status', 'aktif'),
            ])
            ->orderBy('nama')
            ->get();

        return view('buat-surat.create', compact('jenisSuratList'));
    }

    /**
     * Langkah 2: Pilih Template yang aktif untuk Jenis Surat yang dipilih.
     */
    public function pilihTemplate(JenisSurat $jenisSurat)
    {
        $templates = TemplateSurat::where('jenis_surat_id', $jenisSurat->id)
            ->where('status', 'aktif')
            ->orderBy('nama_template')
            ->get();

        return view('buat-surat.pilih-template', compact('jenisSurat', 'templates'));
    }

    /**
     * Langkah 3: Form dinamis berdasarkan placeholder_json milik Template yang dipilih.
     */
    public function form(TemplateSurat $template)
    {
        if ($template->status !== 'aktif') {
            return redirect()
                ->route('buat-surat.create')
                ->with('status', 'Template tersebut sudah tidak aktif. Silakan pilih jenis surat & template lain.');
        }

        $template->load('jenisSurat');
        $klasifikasiList = KlasifikasiSurat::where('status', 'aktif')->orderBy('nama')->get();

        return view('buat-surat.form', compact('template', 'klasifikasiList'));
    }

    /**
     * Langkah 4: Proses generate Word/PDF dan simpan ke DB.
     */
public function store(Request $request)
{
    $validated = $request->validate([
        'template_surat_id' => [
            'required',
            'exists:template_surat,id',
        ],

        'klasifikasi_id' => [
            'nullable',
            'exists:klasifikasi_surat,id',
        ],

        'perihal' => [
            'required',
            'string',
            'max:255',
        ],

        'tanggal_surat' => [
            'required',
            'date',
        ],

        'tujuan' => [
            'nullable',
            'string',
            'max:255',
        ],

        'data' => [
            'nullable',
            'array',
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Ambil Template
    |--------------------------------------------------------------------------
    */

    $template = TemplateSurat::with('jenisSurat')
        ->where('id', $validated['template_surat_id'])
        ->where('status', 'aktif')
        ->first();

    if (!$template) {
        return back()
            ->withInput()
            ->with(
                'error',
                'Template tidak ditemukan atau sudah tidak aktif.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Klasifikasi
    |--------------------------------------------------------------------------
    */

    $klasifikasiId = !empty($validated['klasifikasi_id'])
        ? (int) $validated['klasifikasi_id']
        : null;

    /*
    |--------------------------------------------------------------------------
    | Tunda Generate Nomor Surat
    |--------------------------------------------------------------------------
    */

    // Nomor surat tidak digenerate saat draft, digenerate saat finalize.
    // Memberikan placeholder untuk template processor.
    $nomorSurat = 'DRAFT-' . str()->random(5);

    /*
    |--------------------------------------------------------------------------
    | Cek File Template
    |--------------------------------------------------------------------------
    */

    $templateStoragePath = $template->file_template;

    if (
        !$templateStoragePath ||
        !Storage::disk('public')->exists($templateStoragePath)
    ) {
        return back()
            ->withInput()
            ->with(
                'error',
                'File template Word tidak ditemukan. Silakan upload ulang template "' .
                $template->nama_template .
                '".'
            );
    }

    $templatePath = Storage::disk('public')
        ->path($templateStoragePath);

    /*
    |--------------------------------------------------------------------------
    | Generate Word
    |--------------------------------------------------------------------------
    */

    try {

        $processor = new TemplateProcessor(
            $templatePath
        );

        /*
        |--------------------------------------------------------------------------
        | Placeholder Sistem
        |--------------------------------------------------------------------------
        */

        $processor->setValue(
            'nomor',
            $nomorSurat
        );

        $processor->setValue(
            'tanggal',
            \Carbon\Carbon::parse(
                $validated['tanggal_surat']
            )->translatedFormat('d F Y')
        );

        $processor->setValue(
            'perihal',
            $validated['perihal']
        );

        $processor->setValue(
            'tujuan',
            $validated['tujuan'] ?? ''
        );

        /*
        |--------------------------------------------------------------------------
        | Placeholder Dinamis
        |--------------------------------------------------------------------------
        */

        foreach ($validated['data'] ?? [] as $key => $value) {

            // Hanya izinkan nama placeholder yang aman
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $key)) {
                continue;
            }

            $processor->setValue(
                $key,
                is_scalar($value)
                    ? (string) $value
                    : ''
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Folder Generated
        |--------------------------------------------------------------------------
        */

        Storage::disk('public')
            ->makeDirectory('generated');

        $fileName = 'surat_' .
            now()->format('Ymd_His') .
            '_' .
            str()->random(6);

        $wordRelativePath =
            'generated/' . $fileName . '.docx';

        $wordFullPath =
            Storage::disk('public')
                ->path($wordRelativePath);

        /*
        |--------------------------------------------------------------------------
        | Simpan DOCX
        |--------------------------------------------------------------------------
        */

        $processor->saveAs(
            $wordFullPath
        );

    } catch (\Throwable $e) {

        report($e);

        return back()
            ->withInput()
            ->with(
                'error',
                'Gagal membuat dokumen Word. Pastikan template .docx valid dan placeholder-nya benar.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Convert DOCX → PDF
    |--------------------------------------------------------------------------
    */

    $pdfRelativePath = null;

    $outputDir = dirname(
        $wordFullPath
    );

    $command =
        'soffice --headless --convert-to pdf ' .
        '--outdir ' .
        escapeshellarg($outputDir) .
        ' ' .
        escapeshellarg($wordFullPath) .
        ' 2>&1';

    @shell_exec($command);

    $pdfFullPath =
        $outputDir .
        DIRECTORY_SEPARATOR .
        $fileName .
        '.pdf';

    if (file_exists($pdfFullPath)) {
        $pdfRelativePath =
            'generated/' .
            $fileName .
            '.pdf';
    }

    /*
    |--------------------------------------------------------------------------
    | Simpan Surat
    |--------------------------------------------------------------------------
    */

    $surat = Surat::create([
        'jenis_surat_id' => $template->jenis_surat_id,
        'klasifikasi_id' => $klasifikasiId,
        'template_surat_id' => $template->id,
        'arah' => 'keluar',
        'nomor_surat' => null, // Biarkan null saat draft
        'tanggal_surat' => $validated['tanggal_surat'],
        'perihal' => $validated['perihal'],
        'tujuan' => $validated['tujuan'] ?? null,
        'file_word' => $wordRelativePath,
        'file_pdf' => $pdfRelativePath,
        'status' => 'draft',
        'created_by' => auth()->id(),
    ]);

    /*
    |--------------------------------------------------------------------------
    | Activity Log
    |--------------------------------------------------------------------------
    */

    ActivityLog::create([
        'user_id' => auth()->id(),
        'surat_id' => $surat->id,
        'aktivitas' =>
            'membuat surat (draft): ' .
            $surat->perihal,
    ]);

    return redirect()
        ->route('buat-surat.show', $surat)
        ->with(
            'status',
            'Surat berhasil dibuat. Silakan cek preview dan unduh dokumen.'
        );
}

    public function show(Surat $surat)
    {
        return view('buat-surat.show', compact('surat'));
    }

    public function finalize(Surat $surat)
    {
        // Jika dokumen belum memiliki nomor resmi, generate nomor
        if (empty($surat->nomor_surat)) {
            $nomorSurat = DocumentNumberService::generate($surat->klasifikasi_id, \Carbon\Carbon::parse($surat->tanggal_surat));
            $surat->update([
                'status' => 'final',
                'nomor_surat' => $nomorSurat,
            ]);
        } else {
            $surat->update(['status' => 'final']);
        }

        ActivityLog::create([
            'user_id'   => auth()->id(),
            'surat_id'  => $surat->id,
            'aktivitas' => 'finalisasi surat: ' . $surat->perihal,
        ]);

        return redirect()
            ->route('buat-surat.show', $surat)
            ->with('status', 'Surat difinalisasi dan tersimpan di arsip.');
    }
}