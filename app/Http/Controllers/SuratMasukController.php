<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\JenisSurat;
use App\Models\KlasifikasiSurat;
use App\Models\Surat;
use Illuminate\Http\Request;

class SuratMasukController extends Controller
{
    public function index(Request $request)
    {
        $suratMasuk = Surat::masuk()
            ->with('jenisSurat')
            ->when($request->filled('q'), fn ($q) => $q->where('perihal', 'like', '%' . $request->q . '%'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('surat-masuk.index', compact('suratMasuk'));
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
            'jenis_surat_id' => 'required|exists:jenis_surat,id',
            'klasifikasi_id' => 'nullable|exists:klasifikasi_surat,id',
            'nomor_surat' => 'nullable|string|max:255',
            'perihal' => 'required|string|max:255',
            'pengirim' => 'nullable|string|max:255',
            'tanggal_surat' => 'required|date',
            'tanggal_diterima' => 'required|date',
            'file_dokumen' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $path = null;
        if ($request->hasFile('file_dokumen')) {
            $path = $request->file('file_dokumen')->store('surat-masuk', 'public');
        }

        $surat = Surat::create([
            'jenis_surat_id' => $validated['jenis_surat_id'],
            'klasifikasi_id' => $validated['klasifikasi_id'] ?? null,
            'arah' => 'masuk',
            'nomor_surat' => $validated['nomor_surat'] ?? null,
            'perihal' => $validated['perihal'],
            'pengirim' => $validated['pengirim'] ?? null,
            'tanggal_surat' => $validated['tanggal_surat'],
            'tanggal_diterima' => $validated['tanggal_diterima'],
            'file_dokumen' => $path,
            'status' => 'final',
            'created_by' => auth()->id(),
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'surat_id' => $surat->id,
            'aktivitas' => 'mencatat surat masuk: ' . $surat->perihal,
        ]);

        return redirect()->route('surat-masuk.index')->with('status', 'Surat masuk berhasil dicatat.');
    }
}
