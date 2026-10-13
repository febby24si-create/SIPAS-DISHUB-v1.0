<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;">
            <div style="display:flex; align-items:center; gap:12px;">
                <div style="width:40px; height:40px; background:#fdf4ff; border:1px solid #e9d5ff; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#7c3aed; flex-shrink:0;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 8v13H3V8"/><path d="M1 3h22v5H1z"/><path d="M10 12h4"/></svg>
                </div>
                <div>
                    <div style="font-size:17px; font-weight:600; color:#1e293b; line-height:1.2;">Detail Arsip Digital</div>
                    <div style="margin-top:2px; font-size:12.5px; color:#64748b;">Informasi lengkap arsip surat yang telah final</div>
                </div>
            </div>
            <a href="{{ route('arsip.index') }}" style="display:inline-flex; align-items:center; gap:8px; padding:10px 18px; background:white; border:1px solid #cbd5e1; color:#334155; border-radius:12px; font-size:13px; font-weight:600; text-decoration:none;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali ke Arsip Digital
            </a>
        </div>
    </x-slot>

    <div style="display:flex; flex-direction:column; gap:24px;">

        {{-- Header Status & Nomor --}}
        <div style="background:linear-gradient(135deg, #1e293b, #0f172a); border-radius:24px; padding:32px; color:white; position:relative; overflow:hidden;">
            <div style="position:relative; z-index:2;">
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px; flex-wrap:wrap;">
                    <span style="display:inline-flex; align-items:center; gap:7px; background:rgba(255,255,255,0.2); padding:6px 14px; border-radius:20px; font-size:12px; font-weight:600; letter-spacing:0.5px; backdrop-filter:blur(4px);">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                        STATUS: FINAL – TERSIMPAN DI ARSIP
                    </span>
                </div>
                <h2 style="display:flex; align-items:center; gap:10px; font-size:28px; font-weight:700; margin:0 0 8px 0; font-family:monospace; color:#60a5fa;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;" aria-hidden="true"><line x1="4" y1="9" x2="20" y2="9"/><line x1="4" y1="15" x2="20" y2="15"/><line x1="10" y1="3" x2="8" y2="21"/><line x1="16" y1="3" x2="14" y2="21"/></svg>
                    <span>{{ $surat->nomor_surat ?? 'NOMOR BELUM TERSEDIA' }}</span>
                </h2>
                <p style="margin:0; color:#94a3b8; font-size:15px;">
                    Arsip Resmi Dinas Perhubungan
                </p>
            </div>
            
            {{-- Decorative circles --}}
            {{-- Decorative circle: aksen kecil yang diwarnai menurut arah surat --}}
            @php
                $arahHero = strtolower(trim((string) $surat->arah));
                $glowColor = $arahHero === 'masuk' ? 'rgba(16,185,129,0.18)'
                    : ($arahHero === 'keluar' ? 'rgba(59,130,246,0.18)' : 'rgba(148,163,184,0.15)');
            @endphp
            <div style="position:absolute; top:-50%; right:-10%; width:300px; height:300px; background:radial-gradient(circle, {{ $glowColor }} 0%, rgba(0,0,0,0) 70%); border-radius:50%; z-index:1;"></div>
        </div>

        {{-- Grid Info --}}
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(400px, 1fr)); gap:24px;">
            
            {{-- Informasi Surat --}}
            <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:24px;">
                <h3 style="font-size:16px; font-weight:600; color:#1e293b; margin:0 0 20px 0; display:flex; align-items:center; gap:10px;">
                    <span style="display:flex; align-items:center; justify-content:center; width:32px; height:32px; background:#eff6ff; color:#3b82f6; border-radius:10px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    </span>
                    Informasi Surat
                </h3>
                
                <div style="display:flex; flex-direction:column; gap:16px;">
                    <div>
                        <span style="display:flex; align-items:center; gap:6px; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/></svg>
                            Perihal
                        </span>
                        <div style="color:#334155; font-size:14px; font-weight:500;">{{ $surat->perihal }}</div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div>
                            <span style="display:flex; align-items:center; gap:6px; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.83z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                                Jenis Surat
                            </span>
                            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                {{-- Badge jenis surat sama dengan halaman daftar: warna & ikon mengikuti arah surat. --}}
                                <x-arah-badge :arah="$surat->arah" icon="tag" :label="$surat->jenisSurat->kode ?? '-'" />
                                <span style="color:#334155; font-size:14px; font-weight:500;">{{ $surat->jenisSurat->nama ?? '-' }}</span>
                            </div>
                        </div>
                        <div>
                            <span style="display:flex; align-items:center; gap:6px; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.83z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                                Klasifikasi
                            </span>
                            <div style="color:#334155; font-size:14px; font-weight:500;">{{ $surat->klasifikasi->kode ?? '-' }} - {{ $surat->klasifikasi->nama ?? '-' }}</div>
                        </div>
                        <div>
                            <span style="display:flex; align-items:center; gap:6px; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                Tanggal Surat
                            </span>
                            <div style="color:#334155; font-size:14px; font-weight:500;">{{ $surat->tanggal_surat?->format('d M Y') ?? '-' }}</div>
                        </div>
                        <div>
                            <span style="display:flex; align-items:center; gap:6px; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/></svg>
                                Tanggal Pencatatan
                            </span>
                            <div style="color:#334155; font-size:14px; font-weight:500;">{{ $surat->created_at?->format('d M Y H:i') ?? '-' }}</div>
                        </div>
                        <div>
                            <span style="display:flex; align-items:center; gap:6px; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 3 4 7l4 4"/><path d="M4 7h16"/><path d="m16 21 4-4-4-4"/><path d="M20 17H4"/></svg>
                                Arah Surat
                            </span>
                            {{-- Badge arah sama persis dengan kolom "Arah" pada halaman daftar. --}}
                            <x-arah-badge :arah="$surat->arah" />
                        </div>
                        <div>
                            <span style="display:flex; align-items:center; gap:6px; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                                Unit Kerja
                            </span>
                            @if ($surat->unitKerja)
                                <div style="display:flex; flex-direction:column; gap:4px; color:#334155; font-size:14px; font-weight:500;">
                                    @if ($surat->unitKerja->parent)
                                        <span style="display:flex; align-items:center; gap:6px; font-size:13px; color:#64748b;">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                                            {{ $surat->unitKerja->parent->nama }}
                                        </span>
                                    @endif
                                    <span style="display:flex; align-items:center; gap:6px;">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="6" y1="3" x2="6" y2="15"/><circle cx="18" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M18 9a9 9 0 0 1-9 9"/></svg>
                                        {{ $surat->unitKerja->nama }}
                                    </span>
                                </div>
                            @else
                                <div style="color:#334155; font-size:14px; font-weight:500;">-</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Informasi Sumber --}}
            @if($surat->source_type === \App\Models\PengajuanCuti::class && $surat->source)
                <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:24px;">
                    <h3 style="font-size:16px; font-weight:600; color:#1e293b; margin:0 0 20px 0; display:flex; align-items:center; gap:10px;">
                        <span style="display:flex; align-items:center; justify-content:center; width:32px; height:32px; background:#f5f3ff; color:#8b5cf6; border-radius:10px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </span>
                        Informasi Pengajuan Cuti
                    </h3>
                    <div style="display:flex; flex-direction:column; gap:16px;">
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                            <div>
                                <span style="display:flex; align-items:center; gap:6px; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    Pegawai
                                </span>
                                <div style="color:#334155; font-size:14px; font-weight:500;">{{ $surat->source->pegawai->nama ?? '-' }}</div>
                            </div>
                            <div>
                                <span style="display:flex; align-items:center; gap:6px; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="4" y1="9" x2="20" y2="9"/><line x1="4" y1="15" x2="20" y2="15"/><line x1="10" y1="3" x2="8" y2="21"/><line x1="16" y1="3" x2="14" y2="21"/></svg>
                                    NIP
                                </span>
                                <div style="color:#334155; font-size:14px; font-weight:500;">{{ $surat->source->pegawai->nip ?? '-' }}</div>
                            </div>
                            <div>
                                <span style="display:flex; align-items:center; gap:6px; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                                    Unit Kerja
                                </span>
                                <div style="color:#334155; font-size:14px; font-weight:500;">{{ $surat->source->pegawai->unitKerja->nama ?? '-' }}</div>
                            </div>
                            <div>
                                <span style="display:flex; align-items:center; gap:6px; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.83z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                                    Jenis Cuti
                                </span>
                                <div style="color:#334155; font-size:14px; font-weight:500;">{{ $surat->source->jenis_cuti ?? '-' }}</div>
                            </div>
                            <div>
                                <span style="display:flex; align-items:center; gap:6px; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    Tanggal Mulai
                                </span>
                                <div style="color:#334155; font-size:14px; font-weight:500;">{{ $surat->source->tanggal_mulai?->format('d M Y') ?? '-' }}</div>
                            </div>
                            <div>
                                <span style="display:flex; align-items:center; gap:6px; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="m9 16 2 2 4-4"/></svg>
                                    Tanggal Selesai
                                </span>
                                <div style="color:#334155; font-size:14px; font-weight:500;">{{ $surat->source->tanggal_selesai?->format('d M Y') ?? '-' }}</div>
                            </div>
                            <div>
                                <span style="display:flex; align-items:center; gap:6px; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    Lama Cuti
                                </span>
                                <div style="color:#334155; font-size:14px; font-weight:500;">{{ $surat->source->lama_cuti ?? '-' }} Hari</div>
                            </div>
                            <div>
                                <span style="display:flex; align-items:center; gap:6px; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                    No. Telepon
                                </span>
                                <div style="color:#334155; font-size:14px; font-weight:500;">{{ $surat->source->no_telp ?? '-' }}</div>
                            </div>
                        </div>
                        <div>
                            <span style="display:flex; align-items:center; gap:6px; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                Alasan Cuti
                            </span>
                            <div style="color:#334155; font-size:14px; font-weight:500;">{{ $surat->source->alasan ?? '-' }}</div>
                        </div>
                        <div>
                            <span style="display:flex; align-items:center; gap:6px; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                Alamat Selama Cuti
                            </span>
                            <div style="color:#334155; font-size:14px; font-weight:500;">{{ $surat->source->alamat_cuti ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Dokumen & Lampiran --}}
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(400px, 1fr)); gap:24px;">
            {{-- Dokumen Surat --}}
            <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:24px;">
                <h3 style="font-size:16px; font-weight:600; color:#1e293b; margin:0 0 20px 0; display:flex; align-items:center; gap:10px;">
                    <span style="display:flex; align-items:center; justify-content:center; width:32px; height:32px; background:#f0fdf4; color:#16a34a; border-radius:10px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    </span>
                    Dokumen Surat Resmi
                </h3>

                @if($surat->file_word || $surat->file_pdf || $surat->file_dokumen)
                    <div style="display:flex; gap:12px; flex-wrap:wrap;">
                        @if($surat->file_pdf)
                            <a href="{{ Storage::url($surat->file_pdf) }}" target="_blank" style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:12px; background:#fee2e2; color:#dc2626; border-radius:12px; font-size:14px; font-weight:600; text-decoration:none;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                Buka PDF
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                            </a>
                        @endif
                        @if($surat->file_word)
                            <a href="{{ Storage::url($surat->file_word) }}" target="_blank" style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:12px; background:#e0f2fe; color:#0284c7; border-radius:12px; font-size:14px; font-weight:600; text-decoration:none;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                Unduh Word
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            </a>
                        @endif
                        @if($surat->file_dokumen)
                            <a href="{{ Storage::url($surat->file_dokumen) }}" target="_blank" style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:12px; background:#f0fdf4; color:#16a34a; border-radius:12px; font-size:14px; font-weight:600; text-decoration:none;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                Lihat Dokumen
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                            </a>
                        @endif
                    </div>
                @else
                    <div style="padding:20px; text-align:center; background:#f8fafc; border:1px dashed #cbd5e1; border-radius:12px; color:#94a3b8; font-size:14px;">
                        File dokumen surat tidak tersedia di arsip.
                    </div>
                @endif
            </div>

            {{-- Lampiran (Polymorphic) --}}
            @php
                $allAttachments = $surat->attachments->merge($surat->source_type ? $surat->source->attachments ?? [] : []);
            @endphp
            <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:24px;">
                <h3 style="font-size:16px; font-weight:600; color:#1e293b; margin:0 0 20px 0; display:flex; align-items:center; gap:10px;">
                    <span style="display:flex; align-items:center; justify-content:center; width:32px; height:32px; background:#fff7ed; color:#ea580c; border-radius:10px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                    </span>
                    Lampiran/Dokumen Pendukung
                </h3>

                @if($allAttachments->count() > 0)
                    <div style="display:flex; flex-direction:column; gap:12px;">
                        @foreach($allAttachments as $attachment)
                            <div style="display:flex; align-items:center; justify-content:space-between; padding:12px; background:#f8fafc; border:1px solid rgba(0,0,0,0.05); border-radius:12px;">
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                                    <div>
                                        <div style="font-size:13px; font-weight:600; color:#334155;">{{ $attachment->original_name }}</div>
                                        <div style="font-size:11px; color:#94a3b8;">{{ number_format($attachment->size / 1024, 1) }} KB</div>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" style="display:inline-flex; align-items:center; gap:6px; padding:6px 12px; background:white; color:#3b82f6; border:1px solid #bfdbfe; border-radius:8px; font-size:12px; font-weight:600; text-decoration:none;">
                                    Lihat
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="padding:20px; text-align:center; background:#f8fafc; border:1px dashed #cbd5e1; border-radius:12px; color:#94a3b8; font-size:14px;">
                        Tidak ada file lampiran pendukung.
                    </div>
                @endif
            </div>
        </div>

        {{-- Activity Logs --}}
        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <div style="padding:24px; border-bottom:1px solid rgba(0,0,0,0.05);">
                <h3 style="font-size:16px; font-weight:600; color:#1e293b; margin:0; display:flex; align-items:center; gap:10px;">
                    <span style="display:flex; align-items:center; justify-content:center; width:32px; height:32px; background:#f3f4f6; color:#4b5563; border-radius:10px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </span>
                    Riwayat / Log Aktivitas
                </h3>
            </div>
            
            <table class="data-table" style="width:100%;">
                <thead>
                    <tr>
                        <th style="width:180px;">
                            <span style="display:inline-flex; align-items:center; gap:6px;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                Waktu
                            </span>
                        </th>
                        <th>
                            <span style="display:inline-flex; align-items:center; gap:6px;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                User
                            </span>
                        </th>
                        <th>
                            <span style="display:inline-flex; align-items:center; gap:6px;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                                Aktivitas
                            </span>
                        </th>
                        <th>
                            <span style="display:inline-flex; align-items:center; gap:6px;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                Status
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activityLogs as $log)
                        <tr>
                            <td style="color:#64748b; font-size:13px;">{{ $log->created_at->format('d M Y H:i') }}</td>
                            <td style="font-weight:600; color:#1e293b; font-size:13px;">{{ $log->user->name ?? 'Sistem' }}</td>
                            <td style="color:#334155; font-size:13px;">{{ $log->description ?? $log->aktivitas ?? 'Memperbarui status pengajuan' }}</td>
                            <td>
                                @if(isset($log->new_values['status']))
                                    <span style="display:inline-block; padding:4px 10px; background:#f1f5f9; color:#475569; font-size:11px; font-weight:600; border-radius:8px; text-transform:uppercase;">
                                        {{ $log->new_values['status'] }}
                                    </span>
                                @else
                                    <span style="color:#94a3b8; font-size:12px;">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; padding:32px; color:#94a3b8;">
                                <p style="margin:0; font-size:14px; font-weight:500;">Tidak ada log aktivitas tercatat.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
