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
        
        // Fetch the last completed KGB for each active Pegawai
        $riwayatKgb = GajiBerkala::where('status', 'selesai')
            ->orderBy('tmt_berikutnya', 'desc')
            ->get()
            ->groupBy('pegawai_id')
            ->map(function($items) {
                return $items->first();
            });

        return view('kepegawaian.kgb.create', compact('pegawais', 'riwayatKgb'));
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
        
        // Cek jika tmt_sebelumnya melebihi tanggal wajar
        $tmtSebelumnya = Carbon::parse($validated['tmt_sebelumnya']);
        $validated['tmt_berikutnya'] = $tmtSebelumnya->copy()->addMonths(24);

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

        $kgb->activityLogs()->create([
            'user_id' => auth()->id() ?? 1,
            'aktivitas' => 'Membuat pengajuan gaji berkala: ' . $kgb->nomor_usulan,
        ]);

        return redirect()->route('kepegawaian.kgb.show', $kgb)->with('status', 'Usulan Gaji Berkala (Draft) berhasil dibuat.');
    }

    public function show(GajiBerkala $kgb)
    {
        $kgb->load(['pegawai', 'attachments', 'activityLogs' => function($query) {
            $query->latest();
        }]);

        $kgbSebelumnya = GajiBerkala::where('pegawai_id', $kgb->pegawai_id)
            ->where('status', 'selesai')
            ->where('id', '!=', $kgb->id)
            ->latest('tmt_berikutnya')
            ->first();

        return view('kepegawaian.kgb.show', compact('kgb', 'kgbSebelumnya'));
    }

    public function updateStatus(Request $request, GajiBerkala $kgb)
    {
        $validated = $request->validate([
            'status' => 'required|in:verifikasi,disetujui,selesai,ditolak',
            'catatan' => 'nullable|string',
        ]);

        $allowedTransitions = [
            'draft' => ['verifikasi'],
            'verifikasi' => ['disetujui', 'ditolak'],
            'disetujui' => ['selesai'],
            'selesai' => [],
            'ditolak' => [],
        ];

        if (!in_array($validated['status'], $allowedTransitions[$kgb->status] ?? [])) {
            return back()->with('error', 'Transisi status tidak valid.');
        }

        $statusLama = $kgb->status;
        $statusBaru = $validated['status'];

        $kgb->update([
            'status' => $statusBaru,
            'catatan' => $validated['catatan'] ?? $kgb->catatan,
        ]);

        $kgb->activityLogs()->create([
            'user_id' => auth()->id() ?? 1,
            'aktivitas' => "Status KGB {$kgb->nomor_usulan}: " . strtoupper($statusLama) . " → " . strtoupper($statusBaru),
            'new_values' => ['status' => $statusBaru],
        ]);

        return back()->with('status', 'Status KGB berhasil diperbarui.');
    }
}
