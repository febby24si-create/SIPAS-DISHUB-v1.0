<?php

namespace App\Http\Controllers;

use App\Models\KategoriBup;
use Illuminate\Http\Request;

class KategoriBupController extends Controller
{
    public function index()
    {
        return redirect()->route('pengaturan.kepegawaian.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'usia_pensiun' => 'required|integer|min:1',
            'status' => 'boolean',
        ]);

        $validated['status'] = $request->has('status');

        KategoriBup::create($validated);

        return back()->with('status', 'Kategori BUP berhasil ditambahkan.');
    }

    public function edit(KategoriBup $kategoriBup)
    {
        // For simplicity in UI, we might handle edit via a modal or direct form, but let's return a simple view or just back with data if needed.
        // The instruction says "edit, update", usually means a separate page or modal. Let's create a minimal edit view or just handle it.
        // Actually, let's just make a simple edit view.
        return view('pengaturan.kepegawaian.bup-edit', compact('kategoriBup'));
    }

    public function update(Request $request, KategoriBup $kategoriBup)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'usia_pensiun' => 'required|integer|min:1',
            'status' => 'boolean',
        ]);

        $validated['status'] = $request->has('status');

        $kategoriBup->update($validated);

        return redirect()->route('pengaturan.kepegawaian.index')->with('status', 'Kategori BUP berhasil diperbarui.');
    }

    public function destroy(KategoriBup $kategoriBup)
    {
        if ($kategoriBup->pegawai()->count() > 0) {
            return back()->with('error', 'Kategori BUP tidak dapat dihapus karena masih digunakan oleh data Pegawai.');
        }

        $kategoriBup->delete();

        return back()->with('status', 'Kategori BUP berhasil dihapus.');
    }
}
