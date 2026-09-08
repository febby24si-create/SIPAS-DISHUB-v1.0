<?php

namespace App\Http\Controllers\Kepegawaian;

use App\Http\Controllers\Controller;
use App\Models\PengajuanCuti;
use App\Models\Attachment;
use App\Models\Surat;
use App\Models\JenisSurat;
use App\Models\KlasifikasiSurat;
use App\Services\DocumentNumberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CutiController extends Controller
{
    public function index()
    {
        $cuti = PengajuanCuti::with(['pegawai', 'approver'])->latest()->paginate(10);
        return view('kepegawaian.cuti.index', compact('cuti'));
    }

    public function create()
    {
        $pegawais = \App\Models\Pegawai::where('status_aktif', true)->get();
        return view('kepegawaian.cuti.create', compact('pegawais'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pegawai_id' => 'required|exists:pegawai,id',
            'jenis_cuti' => 'required|string',
            'alasan' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'alamat_cuti' => 'nullable|string',
            'no_telp' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $tanggalMulai = Carbon::parse($validated['tanggal_mulai']);
        $tanggalSelesai = Carbon::parse($validated['tanggal_selesai']);
        $validated['lama_cuti'] = $tanggalMulai->diffInDays($tanggalSelesai) + 1;
        $validated['status'] = 'draft'; // default
        $validated['nomor_pengajuan'] = 'CUTI-' . time();

        $cuti = PengajuanCuti::create($validated);

        if ($request->hasFile('file_pendukung')) {
            $path = $request->file('file_pendukung')->store('cuti', 'public');
            $cuti->attachments()->create([
                'original_name' => $request->file('file_pendukung')->getClientOriginalName(),
                'file_path' => $path,
                'mime_type' => $request->file('file_pendukung')->getClientMimeType(),
                'size' => $request->file('file_pendukung')->getSize(),
                'uploaded_by' => auth()->id(),
            ]);
        }

        return redirect()->route('kepegawaian.cuti.show', $cuti)->with('status', 'Pengajuan cuti draft berhasil dibuat.');
    }

    public function show(PengajuanCuti $cuti)
    {
        $cuti->load(['pegawai', 'approver', 'surat', 'attachments', 'activityLogs']);
        return view('kepegawaian.cuti.show', compact('cuti'));
    }

    public function updateStatus(Request $request, PengajuanCuti $cuti)
    {
        $validated = $request->validate([
            'status' => 'required|in:diajukan,verifikasi,disetujui,diterbitkan,selesai,ditolak',
            'catatan' => 'nullable|string',
        ]);

        if ($validated['status'] === 'diterbitkan') {
            return $this->terbitkan($cuti);
        }

        $cuti->update([
            'status' => $validated['status'],
            'catatan' => $validated['catatan'] ?? $cuti->catatan,
        ]);

        return back()->with('status', 'Status cuti berhasil diperbarui menjadi ' . $validated['status']);
    }

    protected function terbitkan(PengajuanCuti $cuti)
    {
        if ($cuti->status === 'diterbitkan') {
            return back()->with('error', 'Cuti sudah diterbitkan.');
        }

        try {
            DB::transaction(function () use ($cuti) {
                // Ensure jenis surat and klasifikasi exist or fetch defaults
                $jenisSurat = JenisSurat::firstOrCreate(['kode' => 'CUTI'], ['nama' => 'Surat Cuti']);
                $klasifikasi = KlasifikasiSurat::firstOrCreate(['kode' => '850'], ['nama' => 'Kepegawaian', 'status' => 'aktif']);

                $nomorSurat = DocumentNumberService::generate($klasifikasi->id, Carbon::now());

                $surat = Surat::create([
                    'jenis_surat_id' => $jenisSurat->id,
                    'klasifikasi_id' => $klasifikasi->id,
                    'arah' => 'keluar',
                    'nomor_surat' => $nomorSurat,
                    'perihal' => 'Persetujuan Cuti ' . $cuti->pegawai->nama,
                    'tanggal_surat' => Carbon::now(),
                    'tujuan' => $cuti->pegawai->nama,
                    'status' => 'final',
                    'created_by' => auth()->id() ?? 1,
                    'source_type' => PengajuanCuti::class,
                    'source_id' => $cuti->id,
                ]);

                $cuti->update(['status' => 'diterbitkan', 'approved_by' => auth()->id()]);
            });

            return back()->with('status', 'Surat Cuti berhasil diterbitkan dengan nomor resmi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menerbitkan cuti: ' . $e->getMessage());
        }
    }
}
