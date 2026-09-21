<?php

namespace App\Http\Controllers\Kepegawaian;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\RiwayatDiklat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RiwayatDiklatController extends Controller
{
    public function index(Pegawai $pegawai)
    {
        $diklats = $pegawai->riwayatDiklat()->orderByDesc('tahun')->orderByDesc('created_at')->get();

        return view('kepegawaian.diklat.index', compact('pegawai', 'diklats'));
    }

    public function create(Pegawai $pegawai)
    {
        return view('kepegawaian.diklat.form', [
            'pegawai' => $pegawai,
            'diklat' => new RiwayatDiklat(),
        ]);
    }

    public function store(Request $request, Pegawai $pegawai)
    {
        $validated = $request->validate([
            'nama_diklat'   => 'required|string|max:255',
            'penyelenggara' => 'nullable|string|max:255',
            'tahun'         => 'nullable|digits:4|integer',
            'jam_pelajaran' => 'nullable|integer|min:1',
            'file_sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('file_sertifikat')) {
            $validated['file_sertifikat'] = $request->file('file_sertifikat')
                ->store("sertifikat/{$pegawai->id}", 'public');
        }

        $pegawai->riwayatDiklat()->create($validated);

        return redirect()
            ->route('pegawai.diklat.index', $pegawai)
            ->with('status', 'Riwayat diklat berhasil ditambahkan.');
    }

    public function edit(Pegawai $pegawai, RiwayatDiklat $diklat)
    {
        return view('kepegawaian.diklat.form', compact('pegawai', 'diklat'));
    }

    public function update(Request $request, Pegawai $pegawai, RiwayatDiklat $diklat)
    {
        $validated = $request->validate([
            'nama_diklat'   => 'required|string|max:255',
            'penyelenggara' => 'nullable|string|max:255',
            'tahun'         => 'nullable|digits:4|integer',
            'jam_pelajaran' => 'nullable|integer|min:1',
            'file_sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('file_sertifikat')) {
            // Remove old file
            if ($diklat->file_sertifikat) {
                Storage::disk('public')->delete($diklat->file_sertifikat);
            }
            $validated['file_sertifikat'] = $request->file('file_sertifikat')
                ->store("sertifikat/{$pegawai->id}", 'public');
        }

        $diklat->update($validated);

        return redirect()
            ->route('pegawai.diklat.index', $pegawai)
            ->with('status', 'Riwayat diklat berhasil diperbarui.');
    }

    public function destroy(Pegawai $pegawai, RiwayatDiklat $diklat)
    {
        if ($diklat->file_sertifikat) {
            Storage::disk('public')->delete($diklat->file_sertifikat);
        }

        $diklat->delete();

        return redirect()
            ->route('pegawai.diklat.index', $pegawai)
            ->with('status', 'Riwayat diklat berhasil dihapus.');
    }
}
