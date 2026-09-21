<?php

namespace App\Http\Controllers;

use App\Helpers\ArsipPathHelper;
use App\Models\ActivityLog;
use App\Models\JenisSurat;
use App\Models\KlasifikasiSurat;
use App\Models\Surat;
use App\Services\DocumentNumberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        $jenisSuratList = JenisSurat::orderBy('nama')->get();

        $suratKeluar = Surat::keluar()
            ->where('status', '!=', 'draft')
            ->with(['jenisSurat', 'klasifikasi'])
            ->when($request->filled('q'), fn ($q) => $q->where(function ($sub) use ($request) {
                $sub->where('perihal', 'like', '%' . $request->q . '%')
                    ->orWhere('nomor_surat', 'like', '%' . $request->q . '%')
                    ->orWhere('tujuan', 'like', '%' . $request->q . '%');
            }))
            ->when($request->filled('jenis_surat_id'), fn ($q) => $q->where('jenis_surat_id', $request->jenis_surat_id))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('dari'), fn ($q) => $q->whereDate('tanggal_surat', '>=', $request->dari))
            ->when($request->filled('sampai'), fn ($q) => $q->whereDate('tanggal_surat', '<=', $request->sampai))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('surat-keluar.index', compact('suratKeluar', 'jenisSuratList'));
    }

    public function draft(Request $request)
    {
        $draftSurat = Surat::keluar()
            ->where('status', 'draft')
            ->with('jenisSurat')
            ->latest()
            ->paginate(10);

        return view('surat-keluar.draft', compact('draftSurat'));
    }

    public function create()
    {
        $jenisSuratList = JenisSurat::orderBy('nama')->get();
        $klasifikasiList = KlasifikasiSurat::where('status', 'aktif')->orderBy('nama')->get();
        $bidangs = \App\Models\UnitKerja::whereNull('parent_id')->with('children')->orderBy('nama')->get();

        return view('surat-keluar.create', compact('jenisSuratList', 'klasifikasiList', 'bidangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_surat_id' => 'required|exists:jenis_surat,id',
            'klasifikasi_id' => 'nullable|exists:klasifikasi_surat,id',
            'bidang_id'      => 'required|exists:unit_kerja,id',
            'unit_kerja_id'  => [
                'required',
                'exists:unit_kerja,id',
                function ($attribute, $value, $fail) use ($request) {
                    $seksi = \App\Models\UnitKerja::find($value);
                    if (!$seksi || $seksi->parent_id === null) {
                        $fail('Unit kerja yang dipilih harus berupa Seksi, bukan Bidang.');
                    }
                    if ($seksi && $seksi->parent_id != $request->bidang_id) {
                        $fail('Seksi yang dipilih tidak sesuai dengan Bidang.');
                    }
                }
            ],
            'nomor_surat'    => 'nullable|string|max:255',
            'perihal'        => 'required|string|max:500',
            'tujuan'         => 'nullable|string|max:255',
            'tanggal_surat'  => 'required|date',
            'status'         => 'nullable|in:draft,final',
            'file_dokumen'   => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        $path = null;
        if ($request->hasFile('file_dokumen')) {
            $unitKerjaId = $validated['unit_kerja_id'] ?? null;
            $tahun = date('Y', strtotime($validated['tanggal_surat']));
            $folder = ArsipPathHelper::build($unitKerjaId, 'keluar', $tahun) ?? 'surat-keluar';
            $path = $request->file('file_dokumen')->store($folder, 'public');
        }

        $nomorSurat = $validated['nomor_surat'] ?? null;
        $status = $validated['status'] ?? 'final';
        
        // Auto-generate number if final and number is empty
        if ($status === 'final' && empty($nomorSurat)) {
            $nomorSurat = DocumentNumberService::generate($validated['klasifikasi_id'] ?? null, \Carbon\Carbon::parse($validated['tanggal_surat']));
        } elseif ($status === 'draft') {
            $nomorSurat = null; // Pastikan nomor kosong jika draft
        }

        $surat = Surat::create([
            'jenis_surat_id' => $validated['jenis_surat_id'],
            'klasifikasi_id' => $validated['klasifikasi_id'] ?? null,
            'unit_kerja_id'  => $validated['unit_kerja_id'],
            'arah'           => 'keluar',
            'nomor_surat'    => $nomorSurat,
            'perihal'        => $validated['perihal'],
            'tujuan'         => $validated['tujuan'] ?? null,
            'tanggal_surat'  => $validated['tanggal_surat'],
            'file_dokumen'   => $path,
            'status'         => $status,
            'created_by'     => auth()->id(),
        ]);

        $logMessage = $surat->status === 'final' 
            ? 'Memfinalisasi surat keluar: ' . $surat->nomor_surat 
            : 'Mencatat draft surat keluar';

        ActivityLog::create([
            'user_id'   => auth()->id(),
            'surat_id'  => $surat->id,
            'aktivitas' => $logMessage,
        ]);

        return redirect()->route('surat-keluar.show', $surat)->with('status', 'Surat keluar berhasil dicatat.');
    }

    public function show(Surat $surat)
    {
        abort_if($surat->arah !== 'keluar', 404);
        $surat->load(['jenisSurat', 'klasifikasi', 'creator', 'template']);

        return view('surat-keluar.show', compact('surat'));
    }

    public function edit(Surat $surat)
    {
        abort_if($surat->arah !== 'keluar', 404);
        abort_if($surat->status === 'final', 403, 'Surat final tidak dapat diedit.');
        $jenisSuratList = JenisSurat::orderBy('nama')->get();
        $klasifikasiList = KlasifikasiSurat::where('status', 'aktif')->orderBy('nama')->get();
        $bidangs = \App\Models\UnitKerja::whereNull('parent_id')->with('children')->orderBy('nama')->get();

        return view('surat-keluar.edit', compact('surat', 'jenisSuratList', 'klasifikasiList', 'bidangs'));
    }

    public function update(Request $request, Surat $surat)
    {
        abort_if($surat->arah !== 'keluar', 404);
        abort_if($surat->status === 'final', 403, 'Surat final tidak dapat diedit.');

        $validated = $request->validate([
            'jenis_surat_id' => 'required|exists:jenis_surat,id',
            'klasifikasi_id' => 'nullable|exists:klasifikasi_surat,id',
            'bidang_id'      => 'nullable|exists:unit_kerja,id',
            'unit_kerja_id'  => [
                'nullable',
                'exists:unit_kerja,id',
                function ($attribute, $value, $fail) use ($request) {
                    $seksi = \App\Models\UnitKerja::find($value);
                    if (!$seksi || $seksi->parent_id === null) {
                        $fail('Unit kerja yang dipilih harus berupa Seksi, bukan Bidang.');
                    }
                    if ($seksi && $request->filled('bidang_id') && $seksi->parent_id != $request->bidang_id) {
                        $fail('Seksi yang dipilih tidak sesuai dengan Bidang.');
                    }
                }
            ],
            'nomor_surat'    => 'nullable|string|max:255',
            'perihal'        => 'required|string|max:500',
            'tujuan'         => 'nullable|string|max:255',
            'tanggal_surat'  => 'required|date',
            'status'         => 'nullable|in:draft,final',
            'file_dokumen'   => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        if ($request->hasFile('file_dokumen')) {
            if ($surat->file_dokumen) {
                Storage::disk('public')->delete($surat->file_dokumen);
            }
            $currentUnitKerjaId = $validated['unit_kerja_id'] ?? $surat->unit_kerja_id ?? null;
            $tahun = date('Y', strtotime($validated['tanggal_surat'] ?? $surat->tanggal_surat));
            $folder = ArsipPathHelper::build($currentUnitKerjaId, 'keluar', $tahun) ?? 'surat-keluar';
            $validated['file_dokumen'] = $request->file('file_dokumen')->store($folder, 'public');
        } else {
            unset($validated['file_dokumen']);
        }

        $nomorSurat = $validated['nomor_surat'] ?? $surat->nomor_surat;
        $status = $validated['status'] ?? 'final';
        
        // Auto-generate number if finalizing a draft and number is still empty
        if ($status === 'final' && empty($nomorSurat)) {
            $nomorSurat = DocumentNumberService::generate($validated['klasifikasi_id'] ?? null, \Carbon\Carbon::parse($validated['tanggal_surat']));
        } elseif ($status === 'draft') {
            $nomorSurat = null; // Pastikan nomor kosong jika kembali ke draft (sesuai aturan)
        }
        
        $validated['nomor_surat'] = $nomorSurat;

        $surat->update($validated);

        $logMessage = $surat->status === 'final' 
            ? 'Memfinalisasi surat keluar: ' . $surat->nomor_surat 
            : 'Mengubah draft surat keluar';

        ActivityLog::create([
            'user_id'   => auth()->id(),
            'surat_id'  => $surat->id,
            'aktivitas' => $logMessage,
        ]);

        return redirect()->route('surat-keluar.show', $surat)->with('status', 'Surat keluar berhasil diperbarui.');
    }

    public function destroy(Surat $surat)
    {
        abort_if($surat->arah !== 'keluar', 404);
        abort_if($surat->status === 'final', 403, 'Surat final tidak dapat dihapus.');

        if ($surat->file_dokumen) {
            Storage::disk('public')->delete($surat->file_dokumen);
        }
        if ($surat->file_word) {
            Storage::disk('public')->delete($surat->file_word);
        }
        if ($surat->file_pdf) {
            Storage::disk('public')->delete($surat->file_pdf);
        }

        ActivityLog::create([
            'user_id'   => auth()->id(),
            'surat_id'  => $surat->id,
            'aktivitas' => 'Menghapus draft surat keluar',
        ]);

        $surat->delete();

        return redirect()->route('surat-keluar.index')->with('status', 'Surat keluar berhasil dihapus.');
    }

    public function download(Surat $surat)
    {
        abort_if($surat->arah !== 'keluar', 404);

        // Prioritas: file_pdf > file_word > file_dokumen
        $filePath = $surat->file_pdf ?? $surat->file_word ?? $surat->file_dokumen;

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            return back()->with('error', 'File dokumen tidak ditemukan.');
        }

        $nomorSlug = $surat->nomor_surat ? str_replace('/', '-', $surat->nomor_surat) : $surat->id;

        return Storage::disk('public')->download($filePath,
            'Surat-Keluar-' . $nomorSlug . '.' . pathinfo($filePath, PATHINFO_EXTENSION)
        );
    }
}
