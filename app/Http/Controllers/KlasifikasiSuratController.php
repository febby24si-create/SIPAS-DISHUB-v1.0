<?php

namespace App\Http\Controllers;

use App\Models\KlasifikasiSurat;
use Illuminate\Http\Request;

class KlasifikasiSuratController extends Controller
{
    public function index()
    {
        $klasifikasiSurat = KlasifikasiSurat::latest()->paginate(10);
        return view('klasifikasi-surat.index', compact('klasifikasiSurat'));
    }

    public function create()
    {
        return view('klasifikasi-surat.form', [
            'klasifikasi' => new KlasifikasiSurat(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:255|unique:klasifikasi_surat,kode',
            'nama' => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        KlasifikasiSurat::create($validated);

        return redirect()->route('klasifikasi-surat.index')->with('status', 'Klasifikasi Surat berhasil ditambahkan.');
    }

    public function edit(KlasifikasiSurat $klasifikasiSurat)
    {
        return view('klasifikasi-surat.form', ['klasifikasi' => $klasifikasiSurat]);
    }

    public function update(Request $request, KlasifikasiSurat $klasifikasiSurat)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:255|unique:klasifikasi_surat,kode,' . $klasifikasiSurat->id,
            'nama' => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $klasifikasiSurat->update($validated);

        return redirect()->route('klasifikasi-surat.index')->with('status', 'Klasifikasi Surat berhasil diperbarui.');
    }

    public function destroy(KlasifikasiSurat $klasifikasiSurat)
    {
        $klasifikasiSurat->delete();

        return redirect()->route('klasifikasi-surat.index')->with('status', 'Klasifikasi Surat berhasil dihapus.');
    }
}
