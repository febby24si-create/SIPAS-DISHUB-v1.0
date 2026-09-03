<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    <div style="display:flex; flex-direction:column; gap:24px;">

        {{-- ── WELCOME BANNER ───────────────────────────── --}}
        <div style="background:linear-gradient(135deg,#1d4ed8 0%,#0ea5e9 100%); border-radius:20px; padding:28px 32px; display:flex; align-items:center; justify-content:space-between; overflow:hidden; position:relative;">
            <div style="position:absolute; top:-30px; right:120px; width:180px; height:180px; background:rgba(255,255,255,0.07); border-radius:50%;"></div>
            <div style="position:absolute; bottom:-40px; right:20px; width:220px; height:220px; background:rgba(255,255,255,0.05); border-radius:50%;"></div>
            <div style="position:relative; z-index:1;">
                <p style="color:rgba(255,255,255,0.75); font-size:13px; font-weight:500; margin:0 0 6px 0; letter-spacing:0.3px;">
                    {{ now()->translatedFormat('l, d F Y') }}
                </p>
                <h2 style="color:white; font-size:24px; font-weight:800; margin:0 0 6px 0;">
                    Selamat datang, {{ Auth::user()->name }}! 👋
                </h2>
                <p style="color:rgba(255,255,255,0.65); font-size:14px; margin:0;">
                    Sistem Informasi Persuratan – Dinas Perhubungan
                </p>
            </div>
            <a href="{{ route('buat-surat.create') }}"
               style="position:relative; z-index:1; display:inline-flex; align-items:center; gap:8px; padding:12px 22px; background:rgba(255,255,255,0.15); backdrop-filter:blur(8px); border:1px solid rgba(255,255,255,0.25); border-radius:14px; color:white; font-size:14px; font-weight:600; text-decoration:none; transition:all 0.2s; white-space:nowrap;"
               onmouseover="this.style.background='rgba(255,255,255,0.25)'"
               onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Buat Surat
            </a>
        </div>

        {{-- ── SEARCH BAR ────────────────────────────────── --}}
        <div style="display:flex; gap:12px; align-items:center;">
            <form action="#" method="GET" style="flex:1; display:flex; align-items:center; gap:12px; background:white; border:1px solid rgba(0,0,0,0.07); border-radius:16px; padding:12px 18px; box-shadow:0 1px 4px rgba(0,0,0,0.05); transition:all 0.2s;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="q"
                       placeholder="Cari surat berdasarkan nomor, perihal, tanggal, atau kata kunci..."
                       style="flex:1; border:none; outline:none; font-size:14px; color:#334155; background:transparent;"
                       onfocus="this.parentElement.style.borderColor='#3b82f6'; this.parentElement.style.boxShadow='0 0 0 3px rgba(59,130,246,0.12)'"
                       onblur="this.parentElement.style.borderColor='rgba(0,0,0,0.07)'; this.parentElement.style.boxShadow='0 1px 4px rgba(0,0,0,0.05)'">
            </form>
        </div>

        {{-- ── STAT CARDS ────────────────────────────────── --}}
        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px;">

            {{-- Total Surat --}}
            <div class="stat-card blue" style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06);">
                <div style="padding:22px 24px; display:flex; align-items:flex-start; justify-content:space-between;">
                    <div>
                        <p style="font-size:13px; font-weight:500; color:#64748b; margin:0 0 8px 0;">Total Surat</p>
                        <p style="font-size:36px; font-weight:800; color:#0f172a; margin:0; line-height:1;">{{ $totalSurat }}</p>
                        <p style="font-size:12px; color:#64748b; margin:8px 0 0 0; display:flex; align-items:center; gap:4px;">
                            <span style="color:#059669;">●</span> Semua kategori
                        </p>
                    </div>
                    <div style="width:52px; height:52px; background:#eff6ff; border-radius:16px; display:flex; align-items:center; justify-content:center;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                    </div>
                </div>
                <div style="height:4px; background:linear-gradient(90deg,#1d4ed8,#3b82f6); border-radius:0 0 20px 20px;"></div>
            </div>

            {{-- Surat Masuk --}}
            <div class="stat-card green" style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06);">
                <div style="padding:22px 24px; display:flex; align-items:flex-start; justify-content:space-between;">
                    <div>
                        <p style="font-size:13px; font-weight:500; color:#64748b; margin:0 0 8px 0;">Surat Masuk</p>
                        <p style="font-size:36px; font-weight:800; color:#0f172a; margin:0; line-height:1;">{{ $totalMasuk }}</p>
                        <p style="font-size:12px; color:#64748b; margin:8px 0 0 0; display:flex; align-items:center; gap:4px;">
                            <span style="color:#059669;">↓</span> Diterima
                        </p>
                    </div>
                    <div style="width:52px; height:52px; background:#ecfdf5; border-radius:16px; display:flex; align-items:center; justify-content:center;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
                    </div>
                </div>
                <div style="height:4px; background:linear-gradient(90deg,#059669,#10b981); border-radius:0 0 20px 20px;"></div>
            </div>

            {{-- Surat Keluar --}}
            <div class="stat-card orange" style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06);">
                <div style="padding:22px 24px; display:flex; align-items:flex-start; justify-content:space-between;">
                    <div>
                        <p style="font-size:13px; font-weight:500; color:#64748b; margin:0 0 8px 0;">Surat Keluar</p>
                        <p style="font-size:36px; font-weight:800; color:#0f172a; margin:0; line-height:1;">{{ $totalKeluar }}</p>
                        <p style="font-size:12px; color:#64748b; margin:8px 0 0 0; display:flex; align-items:center; gap:4px;">
                            <span style="color:#d97706;">↑</span> Dikirim
                        </p>
                    </div>
                    <div style="width:52px; height:52px; background:#fffbeb; border-radius:16px; display:flex; align-items:center; justify-content:center;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    </div>
                </div>
                <div style="height:4px; background:linear-gradient(90deg,#d97706,#f59e0b); border-radius:0 0 20px 20px;"></div>
            </div>
        </div>

        {{-- ── RECENT SURAT ──────────────────────────────── --}}
        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <div style="padding:20px 24px; border-bottom:1px solid rgba(0,0,0,0.05); display:flex; align-items:center; justify-content:space-between;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:36px; height:36px; background:#eff6ff; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:15px; font-weight:700; color:#0f172a; margin:0;">Surat Terbaru</h3>
                        <p style="font-size:12px; color:#94a3b8; margin:0;">Aktivitas persuratan terkini</p>
                    </div>
                </div>
                <a href="{{ route('buat-surat.create') }}"
                   style="font-size:13px; color:#1d4ed8; font-weight:600; text-decoration:none; display:flex; align-items:center; gap:4px;">
                    Lihat semua
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
            </div>

            @forelse ($suratTerbaru as $surat)
                <div style="display:flex; align-items:center; gap:16px; padding:16px 24px; border-bottom:1px solid rgba(0,0,0,0.04); transition:background 0.15s;"
                     onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                    {{-- Ikon arah surat --}}
                    <div style="flex-shrink:0; width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center;
                                background:{{ $surat->arah === 'masuk' ? '#ecfdf5' : '#fffbeb' }};">
                        @if ($surat->arah === 'masuk')
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
                        @else
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        @endif
                    </div>

                    <div style="flex:1; min-width:0;">
                        <p style="font-size:14px; font-weight:600; color:#1e293b; margin:0 0 3px 0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $surat->perihal }}
                        </p>
                        <p style="font-size:12px; color:#94a3b8; margin:0; display:flex; align-items:center; gap:6px;">
                            <span style="font-family:monospace; background:#f1f5f9; padding:1px 6px; border-radius:4px; color:#64748b; font-size:11px;">
                                {{ $surat->jenisSurat->kode ?? '-' }}
                            </span>
                            <span>·</span>
                            <span>{{ $surat->tanggal_surat?->format('d M Y') }}</span>
                        </p>
                    </div>

                    <div>
                        @if ($surat->arah === 'masuk')
                            <span style="display:inline-flex; align-items:center; padding:4px 10px; background:#ecfdf5; color:#059669; font-size:11px; font-weight:600; border-radius:20px;">
                                ↓ Masuk
                            </span>
                        @else
                            <span style="display:inline-flex; align-items:center; padding:4px 10px; background:#fffbeb; color:#d97706; font-size:11px; font-weight:600; border-radius:20px;">
                                ↑ Keluar
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <div style="padding:48px 24px; text-align:center;">
                    <div style="width:64px; height:64px; background:#f8fafc; border-radius:20px; display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <p style="color:#94a3b8; font-size:14px; font-weight:500; margin:0 0 4px 0;">Belum ada surat</p>
                    <p style="color:#cbd5e1; font-size:13px; margin:0;">Mulai dengan membuat surat pertama Anda</p>
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>
