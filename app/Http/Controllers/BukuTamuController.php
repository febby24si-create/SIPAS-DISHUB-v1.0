<?php

namespace App\Http\Controllers;

use App\Models\BukuTamu;
use App\Models\Pegawai;
use App\Models\UnitKerja;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

        $sysLogoPath = SystemSetting::getSetting('instansi_logo');
        $sysLogoUrl = ($sysLogoPath && Storage::disk('public')->exists($sysLogoPath))
            ? asset('storage/' . $sysLogoPath)
            : asset('logoDishub.png');
        $sysAppName = SystemSetting::getSetting('app_name', 'SIPAS-DISHUB');
        $sysInstansiNama = SystemSetting::getSetting('instansi_nama', 'Dinas Perhubungan Provinsi Riau');

        return view('buku-tamu.create', compact('pegawais', 'unitKerjas', 'sysLogoUrl', 'sysAppName', 'sysInstansiNama'));
    }

    /**
     * Simpan data tamu dari form publik
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'no_hp'         => 'required|string|max:20',
            'keperluan'     => 'required|string',
            'instansi'      => 'nullable|string|max:255',
            'pegawai_id'    => 'nullable|exists:pegawai,id',
            'unit_kerja_id' => 'nullable|exists:unit_kerja,id',
            'spt_file'      => [
                'nullable',
                'file',
                'max:10240',
                'mimes:pdf,jpg,jpeg,png',
            ],
        ]);

        // Server-side timestamp injection
        $validated['tanggal'] = now()->toDateString();
        $validated['jam']     = now()->toTimeString();

        // Handle upload file SPT (opsional)
        if ($request->hasFile('spt_file') && $request->file('spt_file')->isValid()) {
            $file      = $request->file('spt_file');
            $tahun     = now()->format('Y');
            $bulan     = now()->format('m');
            $namaFile  = \Illuminate\Support\Str::uuid() . '.' . strtolower($file->getClientOriginalExtension());
            $path      = $file->storeAs("buku-tamu/{$tahun}/{$bulan}", $namaFile, 'public');
            $validated['spt_file'] = $path;
        } else {
            unset($validated['spt_file']);
        }

        BukuTamu::create($validated);

        return redirect()->route('buku-tamu.sukses');
    }

    /**
     * Halaman sukses/konfirmasi (Publik)
     */
    public function sukses()
    {
        $sysLogoPath = SystemSetting::getSetting('instansi_logo');
        $sysLogoUrl = ($sysLogoPath && Storage::disk('public')->exists($sysLogoPath))
            ? asset('storage/' . $sysLogoPath)
            : asset('logoDishub.png');
        $sysAppName = SystemSetting::getSetting('app_name', 'SIPAS-DISHUB');
        $sysInstansiNama = SystemSetting::getSetting('instansi_nama', 'Dinas Perhubungan Provinsi Riau');

        return view('buku-tamu.sukses', compact('sysLogoUrl', 'sysAppName', 'sysInstansiNama'));
    }

    /**
     * Daftar Buku Tamu untuk Admin
     */
    public function index(Request $request)
    {
        // Validasi rentang tanggal filter
        if ($request->filled('start_date') && $request->filled('end_date') && $request->start_date > $request->end_date) {
            return redirect()->route('buku-tamu.index')->with('error', 'Tanggal mulai tidak boleh lebih besar dari tanggal selesai.');
        }

        // Total keseluruhan (tanpa filter)
        $totalSemua = BukuTamu::count();

        $adaFilter = $request->filled('start_date') || $request->filled('end_date');

        // Base query builder closure untuk reuse filter yang sama
        $applyFilter = function ($q) use ($request) {
            if ($request->filled('start_date')) {
                $q->whereDate('tanggal', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $q->whereDate('tanggal', '<=', $request->end_date);
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

        // Pastikan SEMUA tanggal dalam rentang muncul di grafik (termasuk yang 0 kunjungan)
        if ($chartData->isNotEmpty()) {
            $dateStart = $request->filled('start_date')
                ? \Carbon\Carbon::parse($request->start_date)
                : \Carbon\Carbon::parse($chartData->first()->tanggal);
            $dateEnd = $request->filled('end_date')
                ? \Carbon\Carbon::parse($request->end_date)
                : \Carbon\Carbon::parse($chartData->last()->tanggal);

            // Map data aktual ke array indexed by tanggal (Y-m-d)
            $dataByDate = $chartData->mapWithKeys(function ($item) {
                return [
                    \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d') => (int) $item->total
                ];
            });

            // Generate semua tanggal dalam rentang, isi 0 bila tidak ada data
            $allLabels = [];
            $allValues = [];
            $period = \Carbon\CarbonPeriod::create($dateStart, $dateEnd);
            foreach ($period as $date) {
                $key = $date->format('Y-m-d');
                $allLabels[] = $date->format('d/m');
                $allValues[] = $dataByDate->get($key, 0);
            }

            $chartLabels = $allLabels;
            $chartValues = $allValues;
        }

        // Metrik ringkasan aktual (tanpa data dummy)
        $totalHariIni = BukuTamu::whereDate('tanggal', now()->toDateString())->count();
        $totalBulanIni = BukuTamu::whereYear('tanggal', now()->year)->whereMonth('tanggal', now()->month)->count();
        $totalDenganSpt = (clone $applyFilter(BukuTamu::query()))->whereNotNull('spt_file')->where('spt_file', '!=', '')->count();
        $uniqueDaysCount = (clone $applyFilter(BukuTamu::query()))->distinct('tanggal')->count('tanggal');
        $avgPerHari = $uniqueDaysCount > 0 ? round($totalFilter / $uniqueDaysCount, 1) : 0;
        $maxKunjunganHari = !empty($chartValues) ? max($chartValues) : 0;

        return view('buku-tamu.index', compact(
            'bukuTamu',
            'totalSemua',
            'totalFilter',
            'adaFilter',
            'chartLabels',
            'chartValues',
            'totalHariIni',
            'totalBulanIni',
            'totalDenganSpt',
            'avgPerHari',
            'maxKunjunganHari'
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
     * Unduh berkas SPT kunjungan (Admin)
     */
    public function download(BukuTamu $bukuTamu)
    {
        if (!$bukuTamu->spt_file || !Storage::disk('public')->exists($bukuTamu->spt_file)) {
            return back()->with('error', 'Berkas SPT tidak ditemukan di penyimpanan server.');
        }

        $extension = pathinfo($bukuTamu->spt_file, PATHINFO_EXTENSION);
        $safeName = \Illuminate\Support\Str::slug($bukuTamu->nama ?: 'tamu');
        $fileName = "SPT-{$safeName}-{$bukuTamu->id}.{$extension}";

        return Storage::disk('public')->download($bukuTamu->spt_file, $fileName);
    }

    /**
     * Hapus data kunjungan (Admin)
     */
    public function destroy(BukuTamu $bukuTamu)
    {
        try {
            $filePath = $bukuTamu->spt_file;

            DB::transaction(function () use ($bukuTamu) {
                $bukuTamu->delete();
            });

            // Hapus berkas SPT fisik hanya setelah operasi database dipastikan berhasil
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return redirect()->route('buku-tamu.index')->with('status', 'Data kunjungan berhasil dihapus.');
        } catch (\Throwable $e) {
            return redirect()->route('buku-tamu.index')->with('error', 'Gagal menghapus data kunjungan: ' . $e->getMessage());
        }
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
