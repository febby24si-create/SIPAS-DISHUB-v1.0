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
        $unitKerja = UnitKerja::with(['kepala', 'children.kepala'])
            ->whereNull('parent_id')
            ->latest()
            ->paginate(10);
        return view('unit-kerja.index', compact('unitKerja'));
    }

    public function create()
    {
        // Only valid Pegawai can be chosen as kepala
        $pegawais = Pegawai::where('status_aktif', true)->orderBy('nama')->get();
        $bidangs = UnitKerja::whereNull('parent_id')->orderBy('nama')->get();
        return view('unit-kerja.form', [
            'unitKerja' => new UnitKerja(),
            'pegawais' => $pegawais,
            'bidangs' => $bidangs,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_unit' => 'required|string|max:255|unique:unit_kerja,kode_unit',
            'nama' => 'required|string|max:255',
            'kepala_id' => 'nullable|exists:pegawai,id',
            'parent_id' => [
                'nullable',
                'exists:unit_kerja,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $parent = UnitKerja::find($value);
                        if ($parent && $parent->parent_id !== null) {
                            $fail('Hanya Bidang yang dapat menjadi Induk Unit Kerja (Seksi tidak boleh menjadi induk).');
                        }
                    }
                },
            ],
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
        $bidangs = UnitKerja::whereNull('parent_id')->where('id', '!=', $unitKerja->id)->orderBy('nama')->get();
        return view('unit-kerja.form', compact('unitKerja', 'pegawais', 'bidangs'));
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
            'parent_id' => [
                'nullable',
                'exists:unit_kerja,id',
                function ($attribute, $value, $fail) use ($unitKerja) {
                    if ($value == $unitKerja->id) {
                        $fail('Unit Kerja tidak dapat menjadi induk bagi dirinya sendiri.');
                    }
                    if ($value) {
                        $parent = UnitKerja::find($value);
                        if ($parent && $parent->parent_id !== null) {
                            $fail('Hanya Bidang yang dapat menjadi Induk Unit Kerja (Seksi tidak boleh menjadi induk).');
                        }
                    }
                },
            ],
        ]);

        $unitKerja->update($validated);

        return redirect()->route('unit-kerja.index')->with('status', 'Unit Kerja berhasil diperbarui.');
    }

    public function destroy(UnitKerja $unitKerja)
    {
        if ($unitKerja->children()->count() > 0) {
            return redirect()->route('unit-kerja.index')->with('error', 'Unit Kerja tidak dapat dihapus karena masih memiliki Seksi di bawahnya.');
        }

        $unitKerja->delete();

        return redirect()->route('unit-kerja.index')->with('status', 'Unit Kerja berhasil dihapus.');
    }
}
