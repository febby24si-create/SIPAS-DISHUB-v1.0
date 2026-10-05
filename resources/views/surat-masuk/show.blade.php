<x-app-layout>
    <x-slot name="header">Detail Surat Masuk</x-slot>

    <div style="max-width:800px; margin:0 auto; display:flex; flex-direction:column; gap:20px;">

        {{-- Page Header --}}
        <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:12px; flex-wrap:wrap;">
            <div>
                <h2 style="font-size:20px; font-weight:700; color:#1e293b; margin:0 0 4px;">Detail Surat Masuk</h2>
                <p style="font-size:14px; color:#64748b; margin:0;">Informasi lengkap surat masuk yang tercatat.</p>
            </div>
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <a href="{{ route('surat-masuk.index') }}" style="display:inline-flex; align-items:center; gap:8px; font-size:14px; font-weight:600; color:#64748b; text-decoration:none; padding:8px 16px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    Kembali
                </a>
                <a href="{{ route('surat-masuk.edit', $surat) }}" style="display:inline-flex; align-items:center; gap:8px; font-size:14px; font-weight:600; color:white; text-decoration:none; padding:8px 16px; background:#2563eb; border-radius:8px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Edit
                </a>
            </div>
        </div>

        @if (session('status'))
            <div style="display:flex; align-items:center; gap:10px; padding:14px 18px; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:8px; color:#065f46; font-size:14px; font-weight:500;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('status') }}
            </div>
        @endif

        {{-- Status & Identitas Surat --}}
        <div style="background:white; border-radius:16px; border:1px solid #e2e8f0; overflow:hidden;">
            <div style="padding:20px 24px; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                @php
                    $statusColors = [
                        'final'    => ['#ecfdf5','#065f46'],
                        'diproses' => ['#fffbeb','#92400e'],
                        'selesai'  => ['#f0fdf4','#166534'],
                        'draft'    => ['#f1f5f9','#475569'],
                    ];
                    $sc = $statusColors[$surat->status] ?? ['#f1f5f9','#475569'];
                @endphp
                <span style="display:inline-flex; padding:4px 12px; background:#eff6ff; color:#1d4ed8; font-size:11px; font-weight:700; border-radius:6px; letter-spacing:0.3px;">
                    {{ $surat->jenisSurat->kode ?? 'SURAT' }}
                </span>
                <span style="display:inline-flex; padding:4px 12px; background:{{ $sc[0] }}; color:{{ $sc[1] }}; font-size:11px; font-weight:700; border-radius:6px; text-transform:capitalize;">
                    {{ $surat->status }}
                </span>
                <span style="font-size:13px; color:#64748b; font-family:monospace;">
                    {{ $surat->nomor_surat ?? '-' }}
                </span>
            </div>
            <div style="padding:20px 24px;">
                <h3 style="font-size:16px; font-weight:700; color:#1e293b; margin:0;">{{ $surat->perihal }}</h3>
            </div>
        </div>

        {{-- Detail Surat --}}
        <div style="background:white; border-radius:16px; border:1px solid #e2e8f0; overflow:hidden;">
            <div style="padding:16px 24px; border-bottom:1px solid #e2e8f0;">
                <h3 style="margin:0; font-size:14px; font-weight:600; color:#1e293b; display:flex; align-items:center; gap:8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Informasi Surat
                </h3>
            </div>
            <div style="padding:20px 24px; display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:20px;">
                <div>
                    <p style="margin:0 0 4px; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Jenis Surat</p>
                    <p style="margin:0; font-size:14px; font-weight:500; color:#1e293b;">{{ $surat->jenisSurat->nama ?? '-' }}</p>
                </div>
                <div>
                    <p style="margin:0 0 4px; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Klasifikasi</p>
                    <p style="margin:0; font-size:14px; font-weight:500; color:#1e293b;">
                        @if($surat->klasifikasi)
                            <span style="font-family:monospace; color:#1d4ed8;">{{ $surat->klasifikasi->kode }}</span> - {{ $surat->klasifikasi->nama }}
                        @else
                            <span style="color:#94a3b8;">-</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p style="margin:0 0 4px; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Nomor Surat</p>
                    <p style="margin:0; font-size:14px; font-family:monospace; color:#1e293b;">{{ $surat->nomor_surat ?? '-' }}</p>
                </div>
                <div>
                    <p style="margin:0 0 4px; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Pengirim</p>
                    <p style="margin:0; font-size:14px; font-weight:500; color:#1e293b;">{{ $surat->pengirim ?? '-' }}</p>
                </div>
                <div>
                    <p style="margin:0 0 4px; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Tanggal Surat</p>
                    <p style="margin:0; font-size:14px; color:#1e293b;">{{ $surat->tanggal_surat?->translatedFormat('d F Y') ?? '-' }}</p>
                </div>
                <div>
                    <p style="margin:0 0 4px; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Tanggal Diterima</p>
                    <p style="margin:0; font-size:14px; color:#1e293b;">{{ $surat->tanggal_diterima?->translatedFormat('d F Y') ?? '-' }}</p>
                </div>
                <div>
                    <p style="margin:0 0 4px; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Dicatat Oleh</p>
                    <p style="margin:0; font-size:14px; color:#1e293b;">{{ $surat->creator->name ?? '-' }}</p>
                </div>
                <div>
                    <p style="margin:0 0 4px; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Tanggal Input</p>
                    <p style="margin:0; font-size:14px; color:#1e293b;">{{ $surat->created_at->translatedFormat('d F Y, H:i') }}</p>
                </div>
            </div>
        </div>

        {{-- Dokumen --}}
        @if($surat->file_dokumen)
            <div style="background:white; border-radius:16px; border:1px solid #e2e8f0; overflow:hidden;">
                <div style="padding:16px 24px; border-bottom:1px solid #e2e8f0;">
                    <h3 style="margin:0; font-size:14px; font-weight:600; color:#1e293b; display:flex; align-items:center; gap:8px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                        Dokumen Terlampir
                    </h3>
                </div>
                <div style="padding:20px 24px;">
                    <div style="display:inline-flex; align-items:center; gap:12px; padding:12px 16px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; max-width:100%;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                        <span style="font-size:13px; font-weight:500; color:#475569; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:300px;">{{ basename($surat->file_dokumen) }}</span>
                        <a href="{{ route('surat-masuk.download', $surat) }}" style="font-size:12px; font-weight:600; color:#2563eb; text-decoration:none; white-space:nowrap;">Download</a>
                    </div>
                </div>
            </div>
        @endif

        {{-- Action Hapus --}}
        <div style="display:flex; justify-content:flex-end;">
            <form method="POST" action="{{ route('surat-masuk.destroy', $surat) }}"
                  onsubmit="return confirm('Yakin ingin menghapus surat masuk ini? Tindakan ini tidak dapat dibatalkan.')">
                @csrf
                @method('DELETE')
                <button type="submit" style="display:inline-flex; align-items:center; gap:8px; padding:9px 16px; background:white; color:#dc2626; border:1px solid #fecaca; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                    Hapus Surat Masuk
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
