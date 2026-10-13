<?php

namespace App\Http\Controllers;

use App\Helpers\ArsipPathHelper;
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
        $klasifikasiList = KlasifikasiSurat::where('status', 'aktif')->orderBy('nama')->get();
        $bidangs = \App\Models\UnitKerja::whereNull('parent_id')->with('children')->orderBy('nama')->get();

        $suratMasuk = Surat::masuk()
            ->with(['jenisSurat', 'klasifikasi', 'unitKerja.parent'])
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

        return view('surat-masuk.index', compact('suratMasuk', 'jenisSuratList', 'klasifikasiList', 'bidangs'));
    }

    public function create()
    {
        $jenisSuratList = JenisSurat::orderBy('nama')->get();
        $klasifikasiList = KlasifikasiSurat::where('status', 'aktif')->orderBy('nama')->get();
        $bidangs = \App\Models\UnitKerja::whereNull('parent_id')->with('children')->orderBy('nama')->get();

        return view('surat-masuk.create', compact('jenisSuratList', 'klasifikasiList', 'bidangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_surat_id'   => 'required|exists:jenis_surat,id',
            'klasifikasi_id'   => 'nullable|exists:klasifikasi_surat,id',
            'bidang_id'        => 'required|exists:unit_kerja,id',
            'unit_kerja_id'    => [
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
            $unitKerjaId = $validated['unit_kerja_id'] ?? null;
            $tahun = date('Y', strtotime($validated['tanggal_surat']));
            $folder = ArsipPathHelper::build($unitKerjaId, 'masuk', $tahun) ?? 'surat-masuk';
            $path = $request->file('file_dokumen')->store($folder, 'public');
        }

        $surat = Surat::create([
            'jenis_surat_id'   => $validated['jenis_surat_id'],
            'klasifikasi_id'   => $validated['klasifikasi_id'] ?? null,
            'unit_kerja_id'    => $validated['unit_kerja_id'],
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
            'aktivitas' => 'Mencatat surat masuk: ' . ($surat->nomor_surat ?? 'Tanpa Nomor'),
        ]);

        if ($request->wantsJson()) {
            $surat->load(['jenisSurat', 'klasifikasi', 'unitKerja.parent', 'creator']);
            return response()->json([
                'success' => true,
                'message' => 'Surat masuk berhasil dicatat.',
                'surat'   => $this->formatSuratForJson($surat),
            ], 201);
        }

        return redirect()->route('surat-masuk.show', $surat)->with('status', 'Surat masuk berhasil dicatat.');
    }

    public function show(Request $request, Surat $surat)
    {
        abort_if($surat->arah !== 'masuk', 404);
        $surat->load(['jenisSurat', 'klasifikasi', 'unitKerja.parent', 'creator']);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'surat'   => $this->formatSuratForJson($surat),
            ]);
        }

        return view('surat-masuk.show', compact('surat'));
    }

    public function edit(Surat $surat)
    {
        abort_if($surat->arah !== 'masuk', 404);
        $jenisSuratList = JenisSurat::orderBy('nama')->get();
        $klasifikasiList = KlasifikasiSurat::where('status', 'aktif')->orderBy('nama')->get();
        $bidangs = \App\Models\UnitKerja::whereNull('parent_id')->with('children')->orderBy('nama')->get();

        return view('surat-masuk.edit', compact('surat', 'jenisSuratList', 'klasifikasiList', 'bidangs'));
    }

    public function update(Request $request, Surat $surat)
    {
        abort_if($surat->arah !== 'masuk', 404);

        $validated = $request->validate([
            'jenis_surat_id'   => 'required|exists:jenis_surat,id',
            'klasifikasi_id'   => 'nullable|exists:klasifikasi_surat,id',
            'bidang_id'        => 'nullable|exists:unit_kerja,id',
            'unit_kerja_id'    => [
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
            'nomor_surat'      => 'nullable|string|max:255',
            'perihal'          => 'required|string|max:500',
            'pengirim'         => 'nullable|string|max:255',
            'tanggal_surat'    => 'required|date',
            'tanggal_diterima' => 'required|date',
            'status'           => 'nullable|in:draft,final,diproses,selesai',
            'file_dokumen'     => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
        ]);

        if ($request->hasFile('file_dokumen')) {
            // Hapus file lama hanya setelah file baru berhasil disimpan
            $currentUnitKerjaId = $validated['unit_kerja_id'] ?? $surat->unit_kerja_id ?? null;
            $tahun = date('Y', strtotime($validated['tanggal_surat'] ?? $surat->tanggal_surat));
            $folder = ArsipPathHelper::build($currentUnitKerjaId, 'masuk', $tahun) ?? 'surat-masuk';
            $newPath = $request->file('file_dokumen')->store($folder, 'public');
            // Hapus file lama hanya setelah file baru tersimpan dengan sukses
            if ($surat->file_dokumen) {
                Storage::disk('public')->delete($surat->file_dokumen);
            }
            $validated['file_dokumen'] = $newPath;
        } else {
            // Jangan sentuh file lama jika tidak ada file baru
            unset($validated['file_dokumen']);
        }

        $surat->update($validated);

        ActivityLog::create([
            'user_id'   => auth()->id(),
            'surat_id'  => $surat->id,
            'aktivitas' => 'Memperbarui surat masuk: ' . ($surat->nomor_surat ?? 'Tanpa Nomor'),
        ]);

        if ($request->wantsJson()) {
            $surat->load(['jenisSurat', 'klasifikasi', 'unitKerja.parent', 'creator']);
            return response()->json([
                'success' => true,
                'message' => 'Surat masuk berhasil diperbarui.',
                'surat'   => $this->formatSuratForJson($surat),
            ]);
        }

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
            'aktivitas' => 'Menghapus surat masuk: ' . ($surat->nomor_surat ?? 'Tanpa Nomor'),
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

    /**
     * Tampilkan dokumen surat masuk secara inline (preview PDF di browser).
     *
     * Hanya file yang benar-benar melekat pada surat ini yang dilayani
     * (tidak ada parameter path), tetap di bawah middleware auth.
     */
    public function preview(Surat $surat)
    {
        abort_if($surat->arah !== 'masuk', 404);

        if (!$surat->file_dokumen || !Storage::disk('public')->exists($surat->file_dokumen)) {
            abort(404, 'File dokumen tidak ditemukan.');
        }

        if (strtolower(pathinfo($surat->file_dokumen, PATHINFO_EXTENSION)) !== 'pdf') {
            abort(404, 'Pratinjau hanya tersedia untuk dokumen PDF.');
        }

        $name = 'Surat-Masuk-' . ($surat->nomor_surat ? str_replace('/', '-', $surat->nomor_surat) : $surat->id) . '.pdf';

        return Storage::disk('public')->response($surat->file_dokumen, $name, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $name . '"',
        ], 'inline');
    }

    /**
     * Format surat data untuk respons JSON modal.
     */
    private function formatSuratForJson(Surat $surat): array
    {
        return [
            'id'                   => $surat->id,
            'jenis_surat_id'       => $surat->jenis_surat_id,
            'jenis_surat_nama'     => $surat->jenisSurat?->nama,
            'jenis_surat_kode'     => $surat->jenisSurat?->kode,
            'klasifikasi_id'       => $surat->klasifikasi_id,
            'klasifikasi_nama'     => $surat->klasifikasi?->nama,
            'klasifikasi_kode'     => $surat->klasifikasi?->kode,
            'unit_kerja_id'        => $surat->unit_kerja_id,
            'unit_kerja_nama'      => $surat->unitKerja?->nama,
            'induk_unit_kerja_nama'=> $surat->unitKerja?->parent?->nama,
            'bidang_id'            => $surat->unitKerja?->parent_id,
            'nomor_surat'          => $surat->nomor_surat,
            'perihal'              => $surat->perihal,
            'pengirim'             => $surat->pengirim,
            'tanggal_surat'        => $surat->tanggal_surat?->format('Y-m-d'),
            'tanggal_surat_formatted' => $surat->tanggal_surat?->translatedFormat('d F Y'),
            'tanggal_diterima'     => $surat->tanggal_diterima?->format('Y-m-d'),
            'tanggal_diterima_formatted' => $surat->tanggal_diterima?->translatedFormat('d F Y'),
            'status'               => $surat->status,
            'file_dokumen'         => $surat->file_dokumen,
            'file_dokumen_basename'=> $surat->file_dokumen ? basename($surat->file_dokumen) : null,
            'download_url'         => $surat->file_dokumen ? route('surat-masuk.download', $surat) : null,
            'is_pdf'               => $surat->file_dokumen ? strtolower(pathinfo($surat->file_dokumen, PATHINFO_EXTENSION)) === 'pdf' : false,
            'view_url'             => ($surat->file_dokumen && strtolower(pathinfo($surat->file_dokumen, PATHINFO_EXTENSION)) === 'pdf') ? route('surat-masuk.preview', $surat) : null,
            'creator_name'         => $surat->creator?->name,
            'created_at_formatted' => $surat->created_at?->translatedFormat('d F Y, H:i'),
            'show_url'             => route('surat-masuk.show', $surat),
            'edit_url'             => route('surat-masuk.edit', $surat),
            'destroy_url'          => route('surat-masuk.destroy', $surat),
        ];
    }
}
