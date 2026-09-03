<x-app-layout>
    <x-slot name="header">Preview Surat</x-slot>

    <div style="max-width:820px; display:flex; flex-direction:column; gap:20px;">

        {{-- Breadcrumb --}}
        <div style="display:flex; align-items:center; gap:8px; font-size:13px; color:#94a3b8;">
            <a href="{{ route('dashboard') }}" style="color:#64748b; text-decoration:none; font-weight:500;" onmouseover="this.style.color='#1d4ed8'" onmouseout="this.style.color='#64748b'">Dashboard</a>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            <a href="{{ route('surat-keluar.index') }}" style="color:#64748b; text-decoration:none; font-weight:500;" onmouseover="this.style.color='#1d4ed8'" onmouseout="this.style.color='#64748b'">Surat Keluar</a>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            <span style="color:#1e293b; font-weight:600;">Preview Surat</span>
        </div>

        {{-- Alert --}}
        @if (session('status'))
            <div style="display:flex; align-items:center; gap:10px; padding:14px 18px; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:14px; color:#065f46; font-size:14px; font-weight:500;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div style="display:flex; align-items:flex-start; gap:10px; padding:14px 18px; background:#fef2f2; border:1px solid #fecaca; border-radius:14px; color:#991b1b; font-size:14px; font-weight:500;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- ══ INFO SURAT ══ --}}
        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <div style="padding:18px 24px; border-bottom:1px solid rgba(0,0,0,0.05); background:linear-gradient(135deg,#f8fafc,#f0f9ff); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:36px; height:36px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:15px; font-weight:700; color:#1e293b; margin:0;">Informasi Surat</h3>
                        <p style="font-size:12px; color:#94a3b8; margin:0;">Detail dokumen yang dihasilkan</p>
                    </div>
                </div>
                @if ($surat->status === 'draft')
                    <span style="display:inline-flex; align-items:center; gap:6px; padding:6px 14px; background:#fef3c7; color:#92400e; font-size:12px; font-weight:700; border-radius:20px; border:1px solid #fde68a;">
                        <span style="width:7px; height:7px; background:#f59e0b; border-radius:50%;"></span>Draft
                    </span>
                @else
                    <span style="display:inline-flex; align-items:center; gap:6px; padding:6px 14px; background:#ecfdf5; color:#065f46; font-size:12px; font-weight:700; border-radius:20px; border:1px solid #a7f3d0;">
                        <span style="width:7px; height:7px; background:#059669; border-radius:50%;"></span>Final – Tersimpan di Arsip
                    </span>
                @endif
            </div>

            <div style="padding:24px; display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                <div>
                    <p style="margin:0 0 4px; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Nomor Surat</p>
                    <p style="margin:0; font-size:14px; font-weight:600; color:#1e293b; font-family:monospace;">{{ $surat->nomor_surat ?? '—' }}</p>
                </div>
                <div>
                    <p style="margin:0 0 4px; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Tanggal Surat</p>
                    <p style="margin:0; font-size:14px; font-weight:600; color:#1e293b;">{{ $surat->tanggal_surat?->translatedFormat('d F Y') ?? '—' }}</p>
                </div>
                <div style="grid-column:1/-1;">
                    <p style="margin:0 0 4px; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Perihal</p>
                    <p style="margin:0; font-size:14px; font-weight:600; color:#1e293b;">{{ $surat->perihal }}</p>
                </div>
                @if($surat->tujuan)
                <div style="grid-column:1/-1;">
                    <p style="margin:0 0 4px; font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em;">Kepada</p>
                    <p style="margin:0; font-size:14px; color:#1e293b;">{{ $surat->tujuan }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- ══ DOKUMEN ══ --}}
        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <div style="padding:16px 24px; border-bottom:1px solid rgba(0,0,0,0.05); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                    <span style="font-size:14px; font-weight:700; color:#1e293b;">Dokumen Surat</span>
                </div>
                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                    @if($surat->file_word)
                        <a href="{{ Storage::url($surat->file_word) }}" target="_blank"
                           style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; border-radius:10px; font-size:13px; font-weight:600; text-decoration:none;"
                           onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                            Buka Word
                        </a>
                        <a href="{{ route('surat-keluar.download', $surat) }}"
                           style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; background:#f0f9ff; color:#0369a1; border:1px solid #bae6fd; border-radius:10px; font-size:13px; font-weight:600; text-decoration:none;"
                           onmouseover="this.style.background='#e0f2fe'" onmouseout="this.style.background='#f0f9ff'">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Download Word
                        </a>
                    @endif
                    @if($surat->file_pdf)
                        <a href="{{ Storage::url($surat->file_pdf) }}" target="_blank"
                           style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; background:#fff1f2; color:#be123c; border:1px solid #fecdd3; border-radius:10px; font-size:13px; font-weight:600; text-decoration:none;"
                           onmouseover="this.style.background='#ffe4e6'" onmouseout="this.style.background='#fff1f2'">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                            Buka PDF
                        </a>
                    @endif
                </div>
            </div>

            {{-- Preview area --}}
            @if($surat->file_pdf)
                <iframe src="{{ Storage::url($surat->file_pdf) }}"
                        style="width:100%; height:600px; border:none; display:block; background:#f8fafc;">
                </iframe>
            @elseif($surat->file_word)
                <div style="padding:40px 24px; text-align:center; background:#f8fafc;">
                    <div style="width:64px; height:64px; background:#eff6ff; border-radius:16px; display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                    </div>
                    <p style="font-size:15px; font-weight:700; color:#1e293b; margin:0 0 6px;">Dokumen Word Siap</p>
                    <p style="font-size:13px; color:#64748b; margin:0 0 20px;">Preview PDF tidak tersedia (LibreOffice belum terpasang). Klik tombol di bawah untuk membuka atau mengunduh file Word.</p>
                    <div style="display:flex; gap:10px; justify-content:center; flex-wrap:wrap;">
                        <a href="{{ Storage::url($surat->file_word) }}" target="_blank"
                           style="display:inline-flex; align-items:center; gap:8px; padding:11px 22px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; border-radius:12px; font-size:13px; font-weight:600; text-decoration:none;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                            Buka / Lihat Dokumen
                        </a>
                        <a href="{{ route('surat-keluar.download', $surat) }}"
                           style="display:inline-flex; align-items:center; gap:8px; padding:11px 22px; background:white; color:#475569; border:1px solid #e2e8f0; border-radius:12px; font-size:13px; font-weight:600; text-decoration:none;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Download Word
                        </a>
                    </div>
                </div>
            @else
                <div style="padding:32px 24px; text-align:center; background:#fef2f2;">
                    <p style="font-size:14px; font-weight:600; color:#991b1b; margin:0 0 4px;">File dokumen tidak ditemukan</p>
                    <p style="font-size:13px; color:#b91c1c; margin:0;">Coba buat ulang surat ini.</p>
                </div>
            @endif
        </div>

        {{-- ══ AKSI ══ --}}
        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:20px 24px; display:flex; align-items:center; gap:12px; flex-wrap:wrap;">

            @if ($surat->status === 'draft')
                <form action="{{ route('buat-surat.finalize', $surat) }}" method="POST" style="display:inline;">
                    @csrf @method('PATCH')
                    <button type="submit"
                            style="display:inline-flex; align-items:center; gap:8px; padding:11px 22px; background:linear-gradient(135deg,#059669,#10b981); color:white; font-size:13px; font-weight:600; border-radius:12px; border:none; cursor:pointer; box-shadow:0 2px 8px rgba(5,150,105,0.25);"
                            onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Finalisasi & Simpan ke Arsip
                    </button>
                </form>
            @else
                <div style="display:inline-flex; align-items:center; gap:8px; padding:11px 20px; background:#ecfdf5; color:#065f46; font-size:13px; font-weight:600; border-radius:12px; border:1px solid #a7f3d0;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    Sudah final – tersimpan di arsip
                </div>
            @endif

            <a href="{{ route('surat-keluar.index') }}"
               style="display:inline-flex; align-items:center; gap:6px; padding:11px 18px; background:white; color:#64748b; border:1px solid #e2e8f0; border-radius:12px; font-size:13px; font-weight:600; text-decoration:none;"
               onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                Ke Surat Keluar
            </a>

            <a href="{{ route('buat-surat.create') }}"
               style="display:inline-flex; align-items:center; gap:6px; padding:11px 18px; background:white; color:#64748b; border:1px solid #e2e8f0; border-radius:12px; font-size:13px; font-weight:600; text-decoration:none;"
               onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Buat Surat Baru
            </a>
        </div>

    </div>
</x-app-layout>