<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Illuminate\Http\Request;

class ArsipController extends Controller
{
    public function index(Request $request)
    {
        $arsip = Surat::where('status', 'final')
            ->with('jenisSurat')
            ->when($request->filled('arah'), fn ($q) => $q->where('arah', $request->arah))
            ->when($request->filled('q'), fn ($q) => $q->where('perihal', 'like', '%' . $request->q . '%'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('arsip.index', compact('arsip'));
    }

    public function show(Surat $surat)
    {
        abort_if($surat->status !== 'final', 404, 'Arsip tidak ditemukan atau belum final.');

        $surat->load([
            'jenisSurat',
            'klasifikasi',
            'creator',
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
