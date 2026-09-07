<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\TemplateSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateSuratController extends Controller
{
    public function index()
    {
        $templates = TemplateSurat::with('jenisSurat')
            ->latest()
            ->paginate(10);

        return view('template-surat.index', compact('templates'));
    }

    public function create()
    {
        $jenisSuratList = JenisSurat::orderBy('nama')->get();

        return view('template-surat.form', [
            'template' => new TemplateSurat(),
            'jenisSuratList' => $jenisSuratList,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_surat_id' => [
                'required',
                'exists:jenis_surat,id',
            ],

            'nama_template' => [
                'required',
                'string',
                'max:255',
            ],

            'file_template' => [
                'required',
                'file',
                'mimes:docx',
                'max:5120',
            ],

            'placeholder' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ], [
            'jenis_surat_id.required' => 'Jenis surat wajib dipilih.',
            'nama_template.required' => 'Nama template wajib diisi.',
            'file_template.required' => 'File template Word wajib diupload.',
            'file_template.mimes' => 'Template harus berupa file .docx.',
            'file_template.max' => 'Ukuran template maksimal 5 MB.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Upload Template
        |--------------------------------------------------------------------------
        */

        $path = $request->file('file_template')
            ->store('templates', 'public');

        /*
        |--------------------------------------------------------------------------
        | Simpan Metadata
        |--------------------------------------------------------------------------
        */

        TemplateSurat::create([
            'jenis_surat_id' => $validated['jenis_surat_id'],
            'nama_template' => $validated['nama_template'],
            'file_template' => $path,
            'placeholder_json' => $this->parsePlaceholder(
                $validated['placeholder'] ?? null
            ),
            'status' => 'aktif',
        ]);

        return redirect()
            ->route('template-surat.index')
            ->with('status', 'Template surat berhasil ditambahkan.');
    }

    public function edit(TemplateSurat $templateSurat)
    {
        $jenisSuratList = JenisSurat::orderBy('nama')->get();

        return view('template-surat.form', [
            'template' => $templateSurat,
            'jenisSuratList' => $jenisSuratList,
        ]);
    }

    public function update(Request $request, TemplateSurat $templateSurat)
    {
        $validated = $request->validate([
            'jenis_surat_id' => [
                'required',
                'exists:jenis_surat,id',
            ],

            'nama_template' => [
                'required',
                'string',
                'max:255',
            ],

            'file_template' => [
                'nullable',
                'file',
                'mimes:docx',
                'max:5120',
            ],

            'placeholder' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'status' => [
                'required',
                'in:aktif,nonaktif',
            ],
        ], [
            'file_template.mimes' => 'Template harus berupa file .docx.',
            'file_template.max' => 'Ukuran template maksimal 5 MB.',
        ]);

        $path = $templateSurat->file_template;

        /*
        |--------------------------------------------------------------------------
        | Jika upload file baru
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('file_template')) {

            // Simpan file baru terlebih dahulu
            $newPath = $request->file('file_template')
                ->store('templates', 'public');

            // Hapus file lama jika ada
            if (
                $path &&
                Storage::disk('public')->exists($path)
            ) {
                Storage::disk('public')->delete($path);
            }

            $path = $newPath;
        }

        /*
        |--------------------------------------------------------------------------
        | Update Database
        |--------------------------------------------------------------------------
        */

        $templateSurat->update([
            'jenis_surat_id' => $validated['jenis_surat_id'],
            'nama_template' => $validated['nama_template'],
            'file_template' => $path,
            'placeholder_json' => $this->parsePlaceholder(
                $validated['placeholder'] ?? null
            ),
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('template-surat.index')
            ->with('status', 'Template surat berhasil diperbarui.');
    }

    public function destroy(TemplateSurat $templateSurat)
    {
        /*
        |--------------------------------------------------------------------------
        | Jangan hapus template jika masih digunakan oleh surat
        |--------------------------------------------------------------------------
        */

        if ($templateSurat->surat()->exists()) {
            return redirect()
                ->route('template-surat.index')
                ->with(
                    'error',
                    'Template tidak dapat dihapus karena sudah digunakan oleh surat.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Hapus File
        |--------------------------------------------------------------------------
        */

        if (
            $templateSurat->file_template &&
            Storage::disk('public')->exists($templateSurat->file_template)
        ) {
            Storage::disk('public')->delete(
                $templateSurat->file_template
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Hapus Database
        |--------------------------------------------------------------------------
        */

        $templateSurat->delete();

        return redirect()
            ->route('template-surat.index')
            ->with('status', 'Template surat berhasil dihapus.');
    }

    /**
     * Ubah input:
     *
     * nama, nip, jabatan, tanggal
     *
     * menjadi:
     *
     * [
     *     'nama',
     *     'nip',
     *     'jabatan',
     *     'tanggal'
     * ]
     */
    private function parsePlaceholder(?string $raw): array
    {
        if (!$raw) {
            return [];
        }

        return collect(explode(',', $raw))
            ->map(function ($item) {
                return trim($item);
            })
            ->filter()
            ->map(function ($item) {
                // Hapus ${ } jika admin tidak sengaja menuliskannya
                return preg_replace('/^\$\{|\}$/', '', $item);
            })
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}