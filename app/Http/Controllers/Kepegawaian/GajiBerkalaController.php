<?php

namespace App\Http\Controllers\Kepegawaian;

use App\Http\Controllers\Controller;
use App\Models\GajiBerkala;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GajiBerkalaController extends Controller
{
    public function index()
    {
        $kgb = GajiBerkala::with('pegawai')->latest()->paginate(10);
        return view('kepegawaian.kgb.index', compact('kgb'));
    }

    public function create()
    {
        $pegawais = Pegawai::where('status_aktif', true)->get();
        return view('kepegawaian.kgb.create', compact('pegawais'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
            'gaji_pokok_lama' => 'required|numeric|min:0',
            'gaji_pokok_baru' => 'required|numeric|min:0',
            'tmt_sebelumnya' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        $validated['status'] = 'draft';
        $validated['nomor_usulan'] = 'KGB-' . time();
        $validated['tmt_berikutnya'] = Carbon::parse($validated['tmt_sebelumnya'])->addMonths(24);

        $kgb = GajiBerkala::create($validated);

        if ($request->hasFile('file_pendukung')) {
            $path = $request->file('file_pendukung')->store('kgb', 'public');
            $kgb->attachments()->create([
                'original_name' => $request->file('file_pendukung')->getClientOriginalName(),
                'file_path' => $path,
                'mime_type' => $request->file('file_pendukung')->getClientMimeType(),
                'size' => $request->file('file_pendukung')->getSize(),
                'uploaded_by' => auth()->id(),
            ]);
        }

        return redirect()->route('kepegawaian.kgb.show', $kgb)->with('status', 'Usulan Gaji Berkala (Draft) berhasil dibuat.');
    }

    public function show(GajiBerkala $kgb)
    {
        $kgb->load(['pegawai', 'attachments', 'activityLogs']);
        return view('kepegawaian.kgb.show', compact('kgb'));
    }

    public function updateStatus(Request $request, GajiBerkala $kgb)
    {
        $validated = $request->validate([
            'status' => 'required|in:verifikasi,disetujui,selesai,ditolak',
            'catatan' => 'nullable|string',
        ]);

        $kgb->update([
            'status' => $validated['status'],
            'catatan' => $validated['catatan'] ?? $kgb->catatan,
        ]);

        return back()->with('status', 'Status KGB berhasil diperbarui.');
    }
}
