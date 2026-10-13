<?php

namespace App\Http\Controllers;

use App\Models\KlasifikasiSurat;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KlasifikasiSuratController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $klasifikasiSurat = KlasifikasiSurat::query()
            ->when($q !== '', fn ($query) => $query->where(function ($where) use ($q) {
                $where->where('kode', 'like', "%{$q}%")
                    ->orWhere('nama', 'like', "%{$q}%");
            }))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Jumlah surat per klasifikasi (turunan dari tabel surat, bukan field baru).
        $jumlahSurat = Surat::query()
            ->whereNotNull('klasifikasi_id')
            ->select('klasifikasi_id', DB::raw('COUNT(*) as total'))
            ->groupBy('klasifikasi_id')
            ->pluck('total', 'klasifikasi_id');

        return view('klasifikasi-surat.index', [
            'klasifikasiSurat' => $klasifikasiSurat,
            'jumlahSurat' => $jumlahSurat,
            'q' => $q,
        ]);
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

        $klasifikasi = KlasifikasiSurat::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Klasifikasi Surat berhasil ditambahkan.',
                'klasifikasi' => $klasifikasi,
            ], 201);
        }

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

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Klasifikasi Surat berhasil diperbarui.',
                'klasifikasi' => $klasifikasiSurat->fresh(),
            ]);
        }

        return redirect()->route('klasifikasi-surat.index')->with('status', 'Klasifikasi Surat berhasil diperbarui.');
    }

    public function destroy(Request $request, KlasifikasiSurat $klasifikasiSurat)
    {
        // surat.klasifikasi_id memakai nullOnDelete: menghapus klasifikasi yang masih
        // dipakai akan mengosongkan klasifikasi surat lama secara diam-diam.
        // Penghapusan karena itu dibatasi agar arsip lama tidak kehilangan klasifikasi.
        $jumlahSurat = Surat::where('klasifikasi_id', $klasifikasiSurat->id)->count();

        if ($jumlahSurat > 0) {
            $message = "Klasifikasi \"{$klasifikasiSurat->nama}\" tidak dapat dihapus karena masih digunakan oleh {$jumlahSurat} surat.";

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 409);
            }

            return redirect()->route('klasifikasi-surat.index')->with('error', $message);
        }

        $klasifikasiSurat->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Klasifikasi Surat berhasil dihapus.',
            ]);
        }

        return redirect()->route('klasifikasi-surat.index')->with('status', 'Klasifikasi Surat berhasil dihapus.');
    }
}
