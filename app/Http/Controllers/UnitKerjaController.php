<?php

namespace App\Http\Controllers;

use App\Models\UnitKerja;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UnitKerjaController extends Controller
{
    public function index()
    {
        $unitKerja = UnitKerja::with('kepala')->latest()->paginate(10);
        return view('unit-kerja.index', compact('unitKerja'));
    }

    public function create()
    {
        // Only valid Pegawai can be chosen as kepala
        $pegawais = Pegawai::where('status_aktif', true)->orderBy('nama')->get();
        return view('unit-kerja.form', [
            'unitKerja' => new UnitKerja(),
            'pegawais' => $pegawais,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_unit' => 'required|string|max:255|unique:unit_kerja,kode_unit',
            'nama' => 'required|string|max:255',
            'kepala_id' => 'nullable|exists:pegawai,id',
        ]);

        UnitKerja::create($validated);

        return redirect()->route('unit-kerja.index')->with('status', 'Unit Kerja berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        // Not specifically requested for Phase 1 except for Pegawai
    }

    public function edit(UnitKerja $unitKerja)
    {
        $pegawais = Pegawai::where('status_aktif', true)->orderBy('nama')->get();
        return view('unit-kerja.form', compact('unitKerja', 'pegawais'));
    }

    public function update(Request $request, UnitKerja $unitKerja)
    {
        $validated = $request->validate([
            'kode_unit' => [
                'required',
                'string',
                'max:255',
                Rule::unique('unit_kerja')->ignore($unitKerja->id),
            ],
            'nama' => 'required|string|max:255',
            'kepala_id' => 'nullable|exists:pegawai,id',
        ]);

        $unitKerja->update($validated);

        return redirect()->route('unit-kerja.index')->with('status', 'Unit Kerja berhasil diperbarui.');
    }

    public function destroy(UnitKerja $unitKerja)
    {
        $unitKerja->delete();

        return redirect()->route('unit-kerja.index')->with('status', 'Unit Kerja berhasil dihapus.');
    }
}
