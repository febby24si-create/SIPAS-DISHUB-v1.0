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

    public function edit(PengajuanCuti $cuti)
    {
        if ($cuti->status !== 'draft') {
            return redirect()->route('kepegawaian.cuti.index')->with('error', 'Hanya pengajuan berstatus draft yang dapat diedit.');
        }
        $pegawais = \App\Models\Pegawai::where('status_aktif', true)->get();
        return view('kepegawaian.cuti.edit', compact('cuti', 'pegawais'));
    }

    public function update(Request $request, PengajuanCuti $cuti)
    {
        if ($cuti->status !== 'draft') {
            return redirect()->route('kepegawaian.cuti.index')->with('error', 'Hanya pengajuan berstatus draft yang dapat diedit.');
        }

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

        $cuti->update($validated);

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

        return redirect()->route('kepegawaian.cuti.show', $cuti)->with('status', 'Pengajuan cuti berhasil diperbarui.');
    }

    public function destroy(PengajuanCuti $cuti)
    {
        if ($cuti->status !== 'draft') {
            return redirect()->route('kepegawaian.cuti.index')->with('error', 'Hanya pengajuan berstatus draft yang dapat dihapus.');
        }

        $cuti->delete();
        return redirect()->route('kepegawaian.cuti.index')->with('status', 'Pengajuan cuti draft berhasil dihapus.');
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

        $role = auth()->user()->role?->name;
        $targetStatus = $validated['status'];

        // Cek Otorisasi berdasarkan role
        if ($targetStatus === 'diajukan' && !in_array($role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized action.');
        }
        if ($targetStatus === 'verifikasi' && !in_array($role, ['admin', 'verifikator'])) {
            abort(403, 'Unauthorized action.');
        }
        if ($targetStatus === 'disetujui' && !in_array($role, ['admin', 'pimpinan'])) {
            abort(403, 'Unauthorized action.');
        }
        if ($targetStatus === 'diterbitkan' && !in_array($role, ['admin', 'staff', 'verifikator', 'pimpinan'])) {
            abort(403, 'Unauthorized action.');
        }
        if ($targetStatus === 'selesai' && !in_array($role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized action.');
        }
        if ($targetStatus === 'ditolak' && !in_array($role, ['admin', 'verifikator', 'pimpinan'])) {
            abort(403, 'Unauthorized action.');
        }

        if ($targetStatus === 'diterbitkan') {
            return $this->terbitkan($cuti);
        }

        $cuti->update([
            'status' => $targetStatus,
            'catatan' => $validated['catatan'] ?? $cuti->catatan,
        ]);

        return back()->with('status', 'Status cuti berhasil diperbarui menjadi ' . $targetStatus);
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
