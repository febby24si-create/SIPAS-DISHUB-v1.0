<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Pegawai::with('unitKerja')->latest();

        // Optional filtering support
        if ($request->filled('unit_kerja_id')) {
            $query->where('unit_kerja_id', $request->unit_kerja_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $pegawais = $query->paginate(10)->withQueryString();
        $unitKerjas = UnitKerja::orderBy('nama')->get();

        return view('pegawai.index', compact('pegawais', 'unitKerjas'));
    }

    public function create()
    {
        $unitKerjas = UnitKerja::orderBy('nama')->get();
        return view('pegawai.form', [
            'pegawai' => new Pegawai(),
            'unitKerjas' => $unitKerjas,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'required|string|max:255|unique:pegawai,nip',
            'nama' => 'required|string|max:255',
            'pangkat' => 'nullable|string|max:255',
            'golongan' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'unit_kerja_id' => 'nullable|exists:unit_kerja,id',
            'status_aktif' => 'boolean',
            'tmt' => 'required|date',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $pegawai = new Pegawai();
            $pegawai->nip = $validated['nip'];
            $pegawai->nama = $validated['nama'];
            $pegawai->pangkat = $validated['pangkat'] ?? null;
            $pegawai->golongan = $validated['golongan'] ?? null;
            $pegawai->jabatan = $validated['jabatan'] ?? null;
            $pegawai->unit_kerja_id = $validated['unit_kerja_id'] ?? null;
            $pegawai->status_aktif = $request->has('status_aktif') ? true : false;
            
            // Pass the TMT input for the Observer to use
            $pegawai->tmt_input = $validated['tmt'];
            
            // This will trigger the created event in PegawaiObserver
            $pegawai->save();
        });

        return redirect()->route('pegawai.index')->with('status', 'Pegawai berhasil ditambahkan beserta histori awalnya.');
    }

    public function show(Pegawai $pegawai)
    {
        $pegawai->load(['unitKerja', 'riwayatJabatanPangkat' => function($q) {
            $q->orderBy('tmt', 'desc')->orderBy('created_at', 'desc');
        }]);

        return view('pegawai.show', compact('pegawai'));
    }

    public function edit(Pegawai $pegawai)
    {
        $unitKerjas = UnitKerja::orderBy('nama')->get();
        return view('pegawai.form', compact('pegawai', 'unitKerjas'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $validated = $request->validate([
            'nip' => [
                'required',
                'string',
                'max:255',
                Rule::unique('pegawai')->ignore($pegawai->id),
            ],
            'nama' => 'required|string|max:255',
            'pangkat' => 'nullable|string|max:255',
            'golongan' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'unit_kerja_id' => 'nullable|exists:unit_kerja,id',
            'status_aktif' => 'boolean',
            'tmt' => 'nullable|date', // TMT only needed if career fields changed, but making it nullable is safer. We'll enforce conditionally if needed.
        ]);

        // If career fields are dirty, we'll need TMT. The observer will fallback to now() if not provided, 
        // but it's better to use user input if they changed it.
        if ($request->has('tmt') && $request->filled('tmt')) {
            $pegawai->tmt_input = $validated['tmt'];
        }

        $pegawai->nip = $validated['nip'];
        $pegawai->nama = $validated['nama'];
        $pegawai->pangkat = $validated['pangkat'] ?? null;
        $pegawai->golongan = $validated['golongan'] ?? null;
        $pegawai->jabatan = $validated['jabatan'] ?? null;
        $pegawai->unit_kerja_id = $validated['unit_kerja_id'] ?? null;
        $pegawai->status_aktif = $request->has('status_aktif') ? true : false;
        
        $pegawai->save(); // This triggers updated event and isDirty checks

        return redirect()->route('pegawai.index')->with('status', 'Pegawai berhasil diperbarui.');
    }

    public function destroy(Pegawai $pegawai)
    {
        $pegawai->delete();

        return redirect()->route('pegawai.index')->with('status', 'Pegawai berhasil dihapus.');
    }
}
