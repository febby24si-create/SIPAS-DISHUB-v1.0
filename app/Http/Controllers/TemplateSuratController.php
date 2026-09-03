<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\TemplateSurat;
use Illuminate\Http\Request;

class TemplateSuratController extends Controller
{
    public function index()
    {
        $templates = TemplateSurat::with('jenisSurat')->latest()->paginate(10);
        return view('template-surat.index', compact('templates'));
    }

    public function create()
    {
        $jenisSuratList = JenisSurat::orderBy('nama')->get();
        return view('template-surat.form', ['template' => new TemplateSurat(), 'jenisSuratList' => $jenisSuratList]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_surat_id' => 'required|exists:jenis_surat,id',
            'nama_template' => 'required|string|max:255',
            'file_template' => 'required|file|mimes:docx|max:5120',
            'placeholder' => 'nullable|string', // input dipisah koma, contoh: nama,nip,jabatan,tanggal
        ]);

        $path = $request->file('file_template')->store('templates', 'public');

        TemplateSurat::create([
            'jenis_surat_id' => $validated['jenis_surat_id'],
            'nama_template' => $validated['nama_template'],
            'file_template' => $path,
            'placeholder_json' => $this->parsePlaceholder($validated['placeholder'] ?? null),
            'status' => 'aktif',
        ]);

        return redirect()->route('template-surat.index')->with('status', 'Template berhasil ditambahkan.');
    }

    public function edit(TemplateSurat $templateSurat)
    {
        $jenisSuratList = JenisSurat::orderBy('nama')->get();
        return view('template-surat.form', ['template' => $templateSurat, 'jenisSuratList' => $jenisSuratList]);
    }

    public function update(Request $request, TemplateSurat $templateSurat)
    {
        $validated = $request->validate([
            'jenis_surat_id' => 'required|exists:jenis_surat,id',
            'nama_template' => 'required|string|max:255',
            'file_template' => 'nullable|file|mimes:docx|max:5120',
            'placeholder' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $path = $templateSurat->file_template;
        if ($request->hasFile('file_template')) {
            $path = $request->file('file_template')->store('templates', 'public');
        }

        $templateSurat->update([
            'jenis_surat_id' => $validated['jenis_surat_id'],
            'nama_template' => $validated['nama_template'],
            'file_template' => $path,
            'placeholder_json' => $this->parsePlaceholder($validated['placeholder'] ?? null),
            'status' => $validated['status'],
        ]);

        return redirect()->route('template-surat.index')->with('status', 'Template berhasil diperbarui.');
    }

    public function destroy(TemplateSurat $templateSurat)
    {
        $templateSurat->delete();
        return redirect()->route('template-surat.index')->with('status', 'Template berhasil dihapus.');
    }

    private function parsePlaceholder(?string $raw): array
    {
        if (!$raw) return [];
        return collect(explode(',', $raw))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }
}
