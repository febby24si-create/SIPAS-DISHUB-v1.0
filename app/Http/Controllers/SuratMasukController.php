<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\JenisSurat;
use App\Models\KlasifikasiSurat;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratMasukController extends Controller
{
    public function index(Request $request)
    {
        $jenisSuratList = JenisSurat::orderBy('nama')->get();

        $suratMasuk = Surat::masuk()
            ->with(['jenisSurat', 'klasifikasi'])
            ->when($request->filled('q'), fn ($q) => $q->where(function ($sub) use ($request) {
                $sub->where('perihal', 'like', '%' . $request->q . '%')
                    ->orWhere('nomor_surat', 'like', '%' . $request->q . '%')
                    ->orWhere('pengirim', 'like', '%' . $request->q . '%');
            }))
            ->when($request->filled('jenis_surat_id'), fn ($q) => $q->where('jenis_surat_id', $request->jenis_surat_id))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('dari'), fn ($q) => $q->whereDate('tanggal_diterima', '>=', $request->dari))
            ->when($request->filled('sampai'), fn ($q) => $q->whereDate('tanggal_diterima', '<=', $request->sampai))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('surat-masuk.index', compact('suratMasuk', 'jenisSuratList'));
    }

    public function create()
    {
        $jenisSuratList = JenisSurat::orderBy('nama')->get();
        $klasifikasiList = KlasifikasiSurat::where('status', 'aktif')->orderBy('nama')->get();

        return view('surat-masuk.create', compact('jenisSuratList', 'klasifikasiList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_surat_id'   => 'required|exists:jenis_surat,id',
            'klasifikasi_id'   => 'nullable|exists:klasifikasi_surat,id',
            'nomor_surat'      => 'nullable|string|max:255',
            'perihal'          => 'required|string|max:500',
            'pengirim'         => 'nullable|string|max:255',
            'tanggal_surat'    => 'required|date',
            'tanggal_diterima' => 'required|date',
            'status'           => 'nullable|in:draft,final,diproses,selesai',
            'file_dokumen'     => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        $path = null;
        if ($request->hasFile('file_dokumen')) {
            $path = $request->file('file_dokumen')->store('surat-masuk', 'public');
        }

        $surat = Surat::create([
            'jenis_surat_id'   => $validated['jenis_surat_id'],
            'klasifikasi_id'   => $validated['klasifikasi_id'] ?? null,
            'arah'             => 'masuk',
            'nomor_surat'      => $validated['nomor_surat'] ?? null,
            'perihal'          => $validated['perihal'],
            'pengirim'         => $validated['pengirim'] ?? null,
            'tanggal_surat'    => $validated['tanggal_surat'],
            'tanggal_diterima' => $validated['tanggal_diterima'],
            'file_dokumen'     => $path,
            'status'           => $validated['status'] ?? 'final',
            'created_by'       => auth()->id(),
        ]);

        ActivityLog::create([
            'user_id'   => auth()->id(),
            'surat_id'  => $surat->id,
            'aktivitas' => 'mencatat surat masuk: ' . $surat->perihal,
        ]);

        return redirect()->route('surat-masuk.show', $surat)->with('status', 'Surat masuk berhasil dicatat.');
    }

    public function show(Surat $surat)
    {
        abort_if($surat->arah !== 'masuk', 404);
        $surat->load(['jenisSurat', 'klasifikasi', 'creator']);

        return view('surat-masuk.show', compact('surat'));
    }

    public function edit(Surat $surat)
    {
        abort_if($surat->arah !== 'masuk', 404);
        $jenisSuratList = JenisSurat::orderBy('nama')->get();
        $klasifikasiList = KlasifikasiSurat::where('status', 'aktif')->orderBy('nama')->get();

        return view('surat-masuk.edit', compact('surat', 'jenisSuratList', 'klasifikasiList'));
    }

    public function update(Request $request, Surat $surat)
    {
        abort_if($surat->arah !== 'masuk', 404);

        $validated = $request->validate([
            'jenis_surat_id'   => 'required|exists:jenis_surat,id',
            'klasifikasi_id'   => 'nullable|exists:klasifikasi_surat,id',
            'nomor_surat'      => 'nullable|string|max:255',
            'perihal'          => 'required|string|max:500',
            'pengirim'         => 'nullable|string|max:255',
            'tanggal_surat'    => 'required|date',
            'tanggal_diterima' => 'required|date',
            'status'           => 'nullable|in:draft,final,diproses,selesai',
            'file_dokumen'     => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        if ($request->hasFile('file_dokumen')) {
            // Hapus file lama
            if ($surat->file_dokumen) {
                Storage::disk('public')->delete($surat->file_dokumen);
            }
            $validated['file_dokumen'] = $request->file('file_dokumen')->store('surat-masuk', 'public');
        } else {
            unset($validated['file_dokumen']);
        }

        $surat->update($validated);

        ActivityLog::create([
            'user_id'   => auth()->id(),
            'surat_id'  => $surat->id,
            'aktivitas' => 'memperbarui surat masuk: ' . $surat->perihal,
        ]);

        return redirect()->route('surat-masuk.show', $surat)->with('status', 'Surat masuk berhasil diperbarui.');
    }

    public function destroy(Surat $surat)
    {
        abort_if($surat->arah !== 'masuk', 404);

        if ($surat->file_dokumen) {
            Storage::disk('public')->delete($surat->file_dokumen);
        }

        ActivityLog::create([
            'user_id'   => auth()->id(),
            'surat_id'  => $surat->id,
            'aktivitas' => 'menghapus surat masuk: ' . $surat->perihal,
        ]);

        $surat->delete();

        return redirect()->route('surat-masuk.index')->with('status', 'Surat masuk berhasil dihapus.');
    }

    public function download(Surat $surat)
    {
        abort_if($surat->arah !== 'masuk', 404);

        if (!$surat->file_dokumen || !Storage::disk('public')->exists($surat->file_dokumen)) {
            return back()->with('error', 'File dokumen tidak ditemukan.');
        }

        return Storage::disk('public')->download($surat->file_dokumen,
            'Surat-Masuk-' . ($surat->nomor_surat ? str_replace('/', '-', $surat->nomor_surat) : $surat->id) . '.' . pathinfo($surat->file_dokumen, PATHINFO_EXTENSION)
        );
    }
}
