<x-app-layout>
    <x-slot name="header">Detail Surat Masuk</x-slot>

    <div style="max-width:800px; display:flex; flex-direction:column; gap:20px;">

        @if (session('status'))
            <div style="display:flex; align-items:center; gap:10px; padding:14px 18px; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:14px; color:#065f46; font-size:14px; font-weight:500;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('status') }}
            </div>
        @endif

        {{-- Kartu Detail --}}
        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            {{-- Header Kartu --}}
            <div style="padding:24px 28px; border-bottom:1px solid rgba(0,0,0,0.06); display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap;">
                <div>
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:6px;">
                        <span style="display:inline-flex; padding:4px 12px; background:#eff6ff; color:#1d4ed8; font-size:12px; font-weight:700; border-radius:8px;">
                            {{ $surat->jenisSurat->kode ?? 'SURAT' }}
                        </span>
                        @php
                            $statusColors = ['final'=>['#ecfdf5','#065f46'],'diproses'=>['#fffbeb','#92400e'],'selesai'=>['#f0fdf4','#166534'],'draft'=>['#f1f5f9','#475569']];
                            $sc = $statusColors[$surat->status] ?? ['#f1f5f9','#475569'];
                        @endphp
                        <span style="display:inline-flex; padding:4px 12px; background:{{ $sc[0] }}; color:{{ $sc[1] }}; font-size:12px; font-weight:700; border-radius:8px; text-transform:capitalize;">
                            {{ $surat->status }}
                        </span>
                    </div>
                    <h1 style="margin:0; font-size:18px; font-weight:700; color:#1e293b;">{{ $surat->perihal }}</h1>
                    @if($surat->nomor_surat)
                        <p style="margin:4px 0 0; font-size:13px; color:#64748b; font-family:monospace;">{{ $surat->nomor_surat }}</p>
                    @endif
                </div>
                <div style="display:flex; gap:8px; flex-shrink:0;">
                    @if($surat->file_dokumen)
                        <a href="{{ route('surat-masuk.download', $surat) }}"
                           style="display:inline-flex; align-items:center; gap:6px; padding:9px 16px; background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; border-radius:10px; font-size:13px; font-weight:600; text-decoration:none;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Download
                        </a>
                    @endif
                    <a href="{{ route('surat-masuk.edit', $surat) }}"
                       style="display:inline-flex; align-items:center; gap:6px; padding:9px 16px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; border-radius:10px; font-size:13px; font-weight:600; text-decoration:none;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Edit
                    </a>
                </div>
            </div>

            {{-- Body Detail --}}
            <div style="padding:28px; display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                <div>
                    <p style="margin:0 0 4px; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Jenis Surat</p>
                    <p style="margin:0; font-size:14px; font-weight:500; color:#1e293b;">{{ $surat->jenisSurat->nama ?? '-' }}</p>
                </div>
                <div>
                    <p style="margin:0 0 4px; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Klasifikasi</p>
                    <p style="margin:0; font-size:14px; font-weight:500; color:#1e293b;">
                        @if($surat->klasifikasi)
                            <span style="font-family:monospace; color:#1d4ed8;">{{ $surat->klasifikasi->kode }}</span> – {{ $surat->klasifikasi->nama }}
                        @else
                            <span style="color:#94a3b8;">—</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p style="margin:0 0 4px; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Nomor Surat</p>
                    <p style="margin:0; font-size:14px; font-family:monospace; color:#1e293b;">{{ $surat->nomor_surat ?? '—' }}</p>
                </div>
                <div>
                    <p style="margin:0 0 4px; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Pengirim</p>
                    <p style="margin:0; font-size:14px; font-weight:500; color:#1e293b;">{{ $surat->pengirim ?? '—' }}</p>
                </div>
                <div>
                    <p style="margin:0 0 4px; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Tanggal Surat</p>
                    <p style="margin:0; font-size:14px; color:#1e293b;">{{ $surat->tanggal_surat?->translatedFormat('d F Y') ?? '—' }}</p>
                </div>
                <div>
                    <p style="margin:0 0 4px; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Tanggal Diterima</p>
                    <p style="margin:0; font-size:14px; color:#1e293b;">{{ $surat->tanggal_diterima?->translatedFormat('d F Y') ?? '—' }}</p>
                </div>
                <div>
                    <p style="margin:0 0 4px; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Dicatat oleh</p>
                    <p style="margin:0; font-size:14px; color:#1e293b;">{{ $surat->creator->name ?? '—' }}</p>
                </div>
                <div>
                    <p style="margin:0 0 4px; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Tanggal Input</p>
                    <p style="margin:0; font-size:14px; color:#1e293b;">{{ $surat->created_at->translatedFormat('d F Y, H:i') }}</p>
                </div>

                @if($surat->file_dokumen)
                    <div style="grid-column:1/-1;">
                        <p style="margin:0 0 8px; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Dokumen</p>
                        <div style="display:flex; align-items:center; gap:12px; padding:12px 16px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                            <div style="flex:1; min-width:0;">
                                <p style="margin:0; font-size:13px; font-weight:500; color:#1e293b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ basename($surat->file_dokumen) }}</p>
                            </div>
                            <a href="{{ route('surat-masuk.download', $surat) }}" style="font-size:12px; font-weight:600; color:#1d4ed8; text-decoration:none;">Download</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Aksi --}}
        <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
            <a href="{{ route('surat-masuk.index') }}" style="display:inline-flex; align-items:center; gap:6px; font-size:13px; color:#64748b; text-decoration:none; font-weight:500;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                Kembali ke Daftar
            </a>
            <form method="POST" action="{{ route('surat-masuk.destroy', $surat) }}"
                  onsubmit="return confirm('Yakin ingin menghapus surat masuk ini? Tindakan ini tidak dapat dibatalkan.')">
                @csrf
                @method('DELETE')
                <button type="submit" style="display:inline-flex; align-items:center; gap:6px; padding:9px 16px; background:#fef2f2; color:#dc2626; border:1px solid #fecaca; border-radius:10px; font-size:13px; font-weight:600; cursor:pointer;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                    Hapus
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
