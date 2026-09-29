<?php

namespace App\Http\Controllers;

use App\Models\BukuTamu;
use App\Models\Pegawai;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BukuTamuController extends Controller
{
    /**
     * Tampilkan form publik Buku Tamu
     */
    public function create()
    {
        $pegawais = Pegawai::where('status_aktif', true)->orderBy('nama')->get();
        $unitKerjas = UnitKerja::orderBy('nama')->get();

        return view('buku-tamu.create', compact('pegawais', 'unitKerjas'));
    }

    /**
     * Simpan data tamu dari form publik
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'keperluan' => 'required|string',
            'instansi' => 'nullable|string|max:255',
            'pegawai_id' => 'nullable|exists:pegawai,id',
            'unit_kerja_id' => 'nullable|exists:unit_kerja,id',
        ]);

        // Server-side timestamp injection
        $validated['tanggal'] = now()->toDateString();
        $validated['jam'] = now()->toTimeString();

        BukuTamu::create($validated);

        return redirect()->route('buku-tamu.sukses');
    }

    /**
     * Halaman sukses/konfirmasi (Publik)
     */
    public function sukses()
    {
        return view('buku-tamu.sukses');
    }

    /**
     * Daftar Buku Tamu untuk Admin
     */
    public function index(Request $request)
    {
        // Total keseluruhan (tanpa filter)
        $totalSemua = BukuTamu::count();

        $adaFilter = $request->filled('start_date') || $request->filled('end_date');

        // Base query builder closure untuk reuse filter yang sama
        $applyFilter = function ($q) use ($request) {
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $q->whereBetween('tanggal', [$request->start_date, $request->end_date]);
            } elseif ($request->filled('start_date')) {
                $q->where('tanggal', '>=', $request->start_date);
            } elseif ($request->filled('end_date')) {
                $q->where('tanggal', '<=', $request->end_date);
            }
            return $q;
        };

        // Tabel data
        $query = $applyFilter(BukuTamu::with(['pegawai', 'unitKerja'])->orderBy('tanggal', 'desc')->orderBy('jam', 'desc'));

        // Total setelah filter (sebelum pagination)
        $totalFilter = (clone $query)->count();

        $bukuTamu = $query->paginate(20)->withQueryString();

        // Statistik per tanggal (GROUP BY — satu query, no N+1)
        $chartData = $applyFilter(
            BukuTamu::select(DB::raw('tanggal, COUNT(*) as total'))
                ->groupBy('tanggal')
                ->orderBy('tanggal', 'asc')
        )->get();

        $chartLabels = $chartData->pluck('tanggal')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))->values()->toArray();
        $chartValues = $chartData->pluck('total')->values()->toArray();

        return view('buku-tamu.index', compact(
            'bukuTamu',
            'totalSemua',
            'totalFilter',
            'adaFilter',
            'chartLabels',
            'chartValues'
        ));
    }

    /**
     * Detail Kunjungan (Admin)
     */
    public function show(BukuTamu $bukuTamu)
    {
        $bukuTamu->load(['pegawai', 'unitKerja']);
        return view('buku-tamu.show', compact('bukuTamu'));
    }

    /**
     * Halaman QR Code Buku Tamu (Admin)
     */
    public function qr(Request $request)
    {
        // Bangun URL dari host request yang aktual.
        // Ini memastikan QR menggunakan IP/host yang benar-benar diakses:
        // - Development LAN  : http://10.27.14.53:8000/buku-tamu
        // - Production domain: https://sipas.dishub.riau.go.id/buku-tamu
        $url = $request->getSchemeAndHttpHost() . '/' . ltrim(route('buku-tamu.create', [], false), '/');

        // Generate SVG QR — tidak membutuhkan ekstensi Imagick/GD
        $qrSvg = QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate($url);

        return view('buku-tamu.qr', compact('url', 'qrSvg'));
    }
}
