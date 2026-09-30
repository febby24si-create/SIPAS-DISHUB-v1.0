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
            'nomor_dokumen' => 'required|string',
            'gaji_pokok_lama' => 'required|numeric|min:0',
            'gaji_pokok_baru' => 'required|numeric|min:0',
            'tmt_sebelumnya' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        $validated['status'] = 'selesai'; // Langsung selesai
        $validated['nomor_usulan'] = $validated['nomor_dokumen']; // Nomor manual dari Admin
        
        $tmtSebelumnya = Carbon::parse($validated['tmt_sebelumnya']);
        $validated['tmt_berikutnya'] = $tmtSebelumnya->copy()->addMonths(24);

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $request, &$kgb) {
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
                    'aktivitas' => 'Membuat dokumen gaji berkala: ' . $kgb->nomor_usulan,
                ]);

                // Buat entri Arsip Digital - KGB adalah dokumen kepegawaian internal, bukan surat keluar
                $pegawai = Pegawai::find($kgb->pegawai_id);
                $jenisDokumen = \App\Models\JenisSurat::firstOrCreate(
                    ['kode' => 'DK'],
                    ['nama' => 'Dokumen Kepegawaian']
                );

                \App\Models\Surat::firstOrCreate(
                    [
                        'source_type' => GajiBerkala::class,
                        'source_id'   => $kgb->id,
                    ],
                    [
                        'jenis_surat_id' => $jenisDokumen->id,
                        'klasifikasi_id' => null,
                        'arah'           => 'masuk',
                        'nomor_surat'    => $validated['nomor_dokumen'],
                        'perihal'        => 'Kenaikan Gaji Berkala – ' . $pegawai->nama,
                        'tanggal_surat'  => Carbon::now(),
                        'pengirim'       => $pegawai->nama,
                        'tujuan'         => null,
                        'status'         => 'final',
                        'created_by'     => auth()->id() ?? 1,
                    ]
                );
            });

            return redirect()->route('kepegawaian.kgb.show', $kgb)->with('status', 'Dokumen KGB berhasil disimpan dan masuk ke Arsip Digital.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menyimpan KGB: ' . $e->getMessage());
        }
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
