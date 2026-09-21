<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\UnitKerja;
use Illuminate\Http\Request;

class ArsipController extends Controller
{
    public function index(Request $request)
    {
        $bidangs = UnitKerja::whereNull('parent_id')->with('children')->orderBy('nama')->get();

        // Validasi server-side: pastikan seksi_id memang anak dari bidang_id yang dikirim
        $validSeksiId = null;
        if ($request->filled('bidang_id') && $request->filled('seksi_id')) {
            $seksi = UnitKerja::find($request->seksi_id);
            // Hanya pakai seksi_id jika memang parent-nya sesuai bidang_id
            if ($seksi && $seksi->parent_id == $request->bidang_id) {
                $validSeksiId = $seksi->id;
            }
            // Jika tidak sesuai, abaikan seksi_id secara senyap (tetap gunakan bidang_id)
        } elseif ($request->filled('seksi_id')) {
            // Jika hanya seksi_id tanpa bidang_id, tetap pakai seksi_id
            $seksi = UnitKerja::find($request->seksi_id);
            if ($seksi && $seksi->parent_id !== null) {
                $validSeksiId = $seksi->id;
            }
        }

        // Ambil semua ID Seksi milik Bidang yang dipilih (untuk filter Bidang tanpa Seksi)
        $seksiIdsOfBidang = null;
        if ($request->filled('bidang_id')) {
            $bidang = UnitKerja::whereNull('parent_id')->find($request->bidang_id);
            if ($bidang) {
                $seksiIdsOfBidang = $bidang->children->pluck('id')->toArray();
            }
        }

        $arsip = Surat::where('status', 'final')
            ->with(['jenisSurat', 'unitKerja.parent'])
            ->when($request->filled('arah'), fn ($q) => $q->where('arah', $request->arah))
            ->when($request->filled('q'), fn ($q) => $q->where('perihal', 'like', '%' . $request->q . '%'))
            // Filter Seksi (prioritas utama jika keduanya terisi)
            ->when($validSeksiId, fn ($q) => $q->where('unit_kerja_id', $validSeksiId))
            // Filter Bidang (tanpa Seksi): ambil semua surat yang seksinya anak dari bidang ini
            ->when(!$validSeksiId && $seksiIdsOfBidang !== null,
                fn ($q) => $q->whereIn('unit_kerja_id', $seksiIdsOfBidang)
            )
            ->when($request->filled('tahun'), fn ($q) => $q->whereYear('tanggal_surat', $request->tahun)
                ->orWhereYear('tanggal_diterima', $request->tahun)
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Daftar tahun yang tersedia untuk dropdown
        $tahunList = Surat::where('status', 'final')
            ->selectRaw('YEAR(COALESCE(tanggal_surat, tanggal_diterima)) as tahun')
            ->whereNotNull('tanggal_surat')
            ->orWhereNotNull('tanggal_diterima')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun')
            ->filter()
            ->values();

        return view('arsip.index', compact('arsip', 'bidangs', 'tahunList'));
    }

    public function show(Surat $surat)
    {
        abort_if($surat->status !== 'final', 404, 'Arsip tidak ditemukan atau belum final.');

        $surat->load([
            'jenisSurat',
            'klasifikasi',
            'creator',
            'unitKerja.parent',
            'source.attachments',
            'attachments',
        ]);

        // Get activity logs, either from the source (e.g., PengajuanCuti) or from the Surat itself
        if ($surat->source_type) {
            $activityLogs = \App\Models\ActivityLog::where('subject_type', $surat->source_type)
                ->where('subject_id', $surat->source_id)
                ->with('user')
                ->latest()
                ->get();
        } else {
            $activityLogs = $surat->activityLogs()->with('user')->latest()->get();
        }

        return view('arsip.show', compact('surat', 'activityLogs'));
    }
}

