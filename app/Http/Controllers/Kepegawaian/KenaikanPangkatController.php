<?php

namespace App\Http\Controllers\Kepegawaian;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\KenaikanPangkat;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KenaikanPangkatController extends Controller
{
    public function index()
    {
        $pangkat = KenaikanPangkat::with('pegawai')->latest()->paginate(10);
        return view('kepegawaian.pangkat.index', compact('pangkat'));
    }

    public function create()
    {
        $pegawais = Pegawai::where('status_aktif', true)->get();
        return view('kepegawaian.pangkat.create', compact('pegawais'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
            'pangkat_lama' => 'required|string',
            'golongan_lama' => 'required|string',
            'pangkat_baru' => 'required|string',
            'golongan_baru' => 'required|string',
            'tmt' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        $validated['status'] = 'draft';
        $validated['nomor_usulan'] = 'PKT-' . time();

        $pangkat = KenaikanPangkat::create($validated);

        if ($request->hasFile('file_pendukung')) {
            $path = $request->file('file_pendukung')->store('pangkat', 'public');
            $pangkat->attachments()->create([
                'original_name' => $request->file('file_pendukung')->getClientOriginalName(),
                'file_path' => $path,
                'mime_type' => $request->file('file_pendukung')->getClientMimeType(),
                'size' => $request->file('file_pendukung')->getSize(),
                'uploaded_by' => auth()->id(),
            ]);
        }

        $pangkat->activityLogs()->create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Membuat pengajuan kenaikan pangkat: ' . $pangkat->nomor_usulan,
        ]);

        return redirect()->route('kepegawaian.pangkat.show', $pangkat)->with('status', 'Usulan Kenaikan Pangkat (Draft) berhasil dibuat.');
    }

    public function show(KenaikanPangkat $pangkat)
    {
        $pangkat->load(['pegawai', 'attachments', 'activityLogs']);
        return view('kepegawaian.pangkat.show', compact('pangkat'));
    }

    public function updateStatus(Request $request, KenaikanPangkat $pangkat)
    {
        $validated = $request->validate([
            'status' => 'required|in:diajukan,verifikasi,diproses,selesai,ditolak',
            'catatan' => 'nullable|string',
        ]);

        $allowedTransitions = [
            'draft' => ['diajukan'],
            'diajukan' => ['verifikasi', 'ditolak'],
            'verifikasi' => ['diproses', 'ditolak'],
            'diproses' => ['selesai'],
            'selesai' => [],
            'ditolak' => [],
        ];

        if (!in_array($validated['status'], $allowedTransitions[$pangkat->status] ?? [])) {
            return back()->with('error', 'Transisi status tidak valid.');
        }

        $statusLama = $pangkat->status;

        if ($validated['status'] === 'selesai' && $pangkat->status !== 'selesai') {
            return $this->selesaikan($pangkat);
        }

        $pangkat->update([
            'status' => $validated['status'],
            'catatan' => $validated['catatan'] ?? $pangkat->catatan,
        ]);

        $pangkat->activityLogs()->create([
            'user_id' => auth()->id(),
            'aktivitas' => "Status kenaikan pangkat {$pangkat->nomor_usulan}: {$statusLama} → {$validated['status']}",
            'new_values' => ['status' => $validated['status']],
        ]);

        return back()->with('status', 'Status usulan berhasil diperbarui.');
    }

    protected function selesaikan(KenaikanPangkat $pangkat)
    {
        try {
            DB::transaction(function () use ($pangkat) {
                // Update Pegawai
                $pegawai = $pangkat->pegawai;
                
                // Memberi data virtual untuk Observer
                $pegawai->tmt_input = $pangkat->tmt;
                $pegawai->pangkat = $pangkat->pangkat_baru;
                $pegawai->golongan = $pangkat->golongan_baru;
                $pegawai->save(); // Ini akan memicu PegawaiObserver untuk mencatat riwayat

                $statusLama = $pangkat->status;
                $pangkat->update(['status' => 'selesai']);

                $pangkat->activityLogs()->create([
                    'user_id' => auth()->id(),
                    'aktivitas' => "Status kenaikan pangkat {$pangkat->nomor_usulan}: {$statusLama} → selesai",
                    'new_values' => ['status' => 'selesai'],
                ]);
            });

            return back()->with('status', 'Kenaikan Pangkat Selesai. Data master pegawai dan riwayat telah diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses penyelesaian: ' . $e->getMessage());
        }
    }
}
