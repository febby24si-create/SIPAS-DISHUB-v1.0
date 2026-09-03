<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use Illuminate\Http\Request;

class JenisSuratController extends Controller
{
    public function index()
    {
        $jenisSurat = JenisSurat::latest()->paginate(10);
        return view('jenis-surat.index', compact('jenisSurat'));
    }

    public function create()
    {
        return view('jenis-surat.form', [
            'jenisSurat' => new JenisSurat(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:255|unique:jenis_surat,kode',
        ]);

        JenisSurat::create($validated);

        return redirect()->route('jenis-surat.index')->with('status', 'Jenis Surat berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JenisSurat $jenisSurat)
    {
        return view('jenis-surat.form', compact('jenisSurat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JenisSurat $jenisSurat)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:255|unique:jenis_surat,kode,' . $jenisSurat->id,
        ]);

        $jenisSurat->update($validated);

        return redirect()->route('jenis-surat.index')->with('status', 'Jenis Surat berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisSurat $jenisSurat)
    {
        $jenisSurat->delete();

        return redirect()->route('jenis-surat.index')->with('status', 'Jenis Surat berhasil dihapus.');
    }
}
