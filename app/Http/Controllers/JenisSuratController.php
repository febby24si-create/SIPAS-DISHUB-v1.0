<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JenisSuratController extends Controller
{
    /**
     * Statistik penggunaan tiap jenis surat pada tabel surat (masuk / keluar / total).
     *
     * Bersifat turunan dari data surat yang sudah ada — bukan field baru pada master,
     * sehingga tidak memerlukan perubahan struktur database.
     *
     * @return array<int, array{masuk: int, keluar: int, total: int}>
     */
    private function statistikPenggunaan(): array
    {
        $statistik = [];

        Surat::query()
            ->select('jenis_surat_id', 'arah', DB::raw('COUNT(*) as total'))
            ->groupBy('jenis_surat_id', 'arah')
            ->get()
            ->each(function ($row) use (&$statistik) {
                $id = (int) $row->jenis_surat_id;
                $total = (int) $row->total;

                if (! isset($statistik[$id])) {
                    $statistik[$id] = ['masuk' => 0, 'keluar' => 0, 'total' => 0];
                }

                if ($row->arah === 'masuk' || $row->arah === 'keluar') {
                    $statistik[$id][$row->arah] += $total;
                }

                $statistik[$id]['total'] += $total;
            });

        return $statistik;
    }

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $jenisSurat = JenisSurat::query()
            ->when($q !== '', fn ($query) => $query->where(function ($where) use ($q) {
                $where->where('kode', 'like', "%{$q}%")
                    ->orWhere('nama', 'like', "%{$q}%");
            }))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('jenis-surat.index', [
            'jenisSurat' => $jenisSurat,
            'statistik' => $this->statistikPenggunaan(),
            'q' => $q,
        ]);
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

        $jenisSurat = JenisSurat::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Jenis Surat berhasil ditambahkan.',
                'jenisSurat' => $jenisSurat,
            ], 201);
        }

        return redirect()->route('jenis-surat.index')->with('status', 'Jenis Surat berhasil ditambahkan.');
    }

    public function show(JenisSurat $jenisSurat)
    {
        $statistik = $this->statistikPenggunaan()[$jenisSurat->id]
            ?? ['masuk' => 0, 'keluar' => 0, 'total' => 0];

        // Daftar surat terkait (maks. 10 terbaru) untuk informasi ringkas.
        $daftarSurat = $jenisSurat->surat()
            ->orderByDesc('tanggal_surat')
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        return view('jenis-surat.show', compact('jenisSurat', 'statistik', 'daftarSurat'));
    }

    public function edit(JenisSurat $jenisSurat)
    {
        return view('jenis-surat.form', compact('jenisSurat'));
    }

    public function update(Request $request, JenisSurat $jenisSurat)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:255|unique:jenis_surat,kode,' . $jenisSurat->id,
        ]);

        $jenisSurat->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Jenis Surat berhasil diperbarui.',
                'jenisSurat' => $jenisSurat->fresh(),
            ]);
        }

        return redirect()->route('jenis-surat.index')->with('status', 'Jenis Surat berhasil diperbarui.');
    }

    public function destroy(Request $request, JenisSurat $jenisSurat)
    {
        // surat.jenis_surat_id bertipe NOT NULL tanpa cascade, sedangkan template_surat
        // memakai cascadeOnDelete — hapus pakai akan membuat error 500 atau menghapus
        // template secara diam-diam. Karena itu penghapusan dibatasi bila masih dipakai.
        $jumlahSurat = Surat::where('jenis_surat_id', $jenisSurat->id)->count();
        $jumlahTemplate = $jenisSurat->templates()->count();

        if ($jumlahSurat > 0 || $jumlahTemplate > 0) {
            $rincian = $jumlahSurat > 0 ? "{$jumlahSurat} surat" : '';
            $rincian .= $jumlahSurat > 0 && $jumlahTemplate > 0 ? ' dan ' : '';
            $rincian .= $jumlahTemplate > 0 ? "{$jumlahTemplate} template surat" : '';
            $message = "Jenis Surat \"{$jenisSurat->nama}\" tidak dapat dihapus karena masih digunakan oleh {$rincian}.";

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 409);
            }

            return redirect()->route('jenis-surat.index')->with('error', $message);
        }

        $jenisSurat->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Jenis Surat berhasil dihapus.',
            ]);
        }

        return redirect()->route('jenis-surat.index')->with('status', 'Jenis Surat berhasil dihapus.');
    }
}
