<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>Detail Arsip Digital</div>
            <a href="{{ route('arsip.index') }}" style="display:inline-flex; align-items:center; gap:8px; padding:10px 18px; background:white; border:1px solid #cbd5e1; color:#334155; border-radius:12px; font-size:13px; font-weight:600; text-decoration:none;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali ke Arsip Digital
            </a>
        </div>
    </x-slot>

    <div style="display:flex; flex-direction:column; gap:24px;">

        {{-- Header Status & Nomor --}}
        <div style="background:linear-gradient(135deg, #1e293b, #0f172a); border-radius:24px; padding:32px; color:white; position:relative; overflow:hidden;">
            <div style="position:relative; z-index:2;">
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
                    <span style="background:rgba(255,255,255,0.2); padding:6px 14px; border-radius:20px; font-size:12px; font-weight:600; letter-spacing:0.5px; backdrop-filter:blur(4px);">
                        STATUS: FINAL – TERSIMPAN DI ARSIP
                    </span>
                </div>
                <h2 style="font-size:28px; font-weight:700; margin:0 0 8px 0; font-family:monospace; color:#60a5fa;">
                    {{ $surat->nomor_surat ?? 'NOMOR BELUM TERSEDIA' }}
                </h2>
                <p style="margin:0; color:#94a3b8; font-size:15px;">
                    Arsip Resmi Dinas Perhubungan
                </p>
            </div>
            
            {{-- Decorative circles --}}
            <div style="position:absolute; top:-50%; right:-10%; width:300px; height:300px; background:radial-gradient(circle, rgba(59,130,246,0.15) 0%, rgba(0,0,0,0) 70%); border-radius:50%; z-index:1;"></div>
        </div>

        {{-- Grid Info --}}
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(400px, 1fr)); gap:24px;">
            
            {{-- Informasi Surat --}}
            <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:24px;">
                <h3 style="font-size:16px; font-weight:600; color:#1e293b; margin:0 0 20px 0; display:flex; align-items:center; gap:10px;">
                    <span style="display:flex; align-items:center; justify-content:center; width:32px; height:32px; background:#eff6ff; color:#3b82f6; border-radius:10px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    </span>
                    Informasi Surat
                </h3>
                
                <div style="display:flex; flex-direction:column; gap:16px;">
                    <div>
                        <span style="display:block; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">Perihal</span>
                        <div style="color:#334155; font-size:14px; font-weight:500;">{{ $surat->perihal }}</div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div>
                            <span style="display:block; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">Jenis Surat</span>
                            <div style="color:#334155; font-size:14px; font-weight:500;">{{ $surat->jenisSurat->nama ?? '-' }}</div>
                        </div>
                        <div>
                            <span style="display:block; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">Klasifikasi</span>
                            <div style="color:#334155; font-size:14px; font-weight:500;">{{ $surat->klasifikasi->kode ?? '-' }} - {{ $surat->klasifikasi->nama ?? '-' }}</div>
                        </div>
                        <div>
                            <span style="display:block; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">Tanggal Surat</span>
                            <div style="color:#334155; font-size:14px; font-weight:500;">{{ $surat->tanggal_surat?->format('d M Y') ?? '-' }}</div>
                        </div>
                        <div>
                            <span style="display:block; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">Arah Surat</span>
                            <div style="color:#334155; font-size:14px; font-weight:500; text-transform:capitalize;">
                                @if ($surat->arah === 'masuk')
                                    <span style="color:#059669;">↓ Masuk</span>
                                @else
                                    <span style="color:#d97706;">↑ Keluar</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Informasi Sumber --}}
            @if($surat->source_type === \App\Models\PengajuanCuti::class && $surat->source)
                <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:24px;">
                    <h3 style="font-size:16px; font-weight:600; color:#1e293b; margin:0 0 20px 0; display:flex; align-items:center; gap:10px;">
                        <span style="display:flex; align-items:center; justify-content:center; width:32px; height:32px; background:#f5f3ff; color:#8b5cf6; border-radius:10px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </span>
                        Informasi Pengajuan Cuti
                    </h3>
                    <div style="display:flex; flex-direction:column; gap:16px;">
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                            <div>
                                <span style="display:block; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">Pegawai</span>
                                <div style="color:#334155; font-size:14px; font-weight:500;">{{ $surat->source->pegawai->nama ?? '-' }}</div>
                            </div>
                            <div>
                                <span style="display:block; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">NIP</span>
                                <div style="color:#334155; font-size:14px; font-weight:500;">{{ $surat->source->pegawai->nip ?? '-' }}</div>
                            </div>
                            <div>
                                <span style="display:block; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">Unit Kerja</span>
                                <div style="color:#334155; font-size:14px; font-weight:500;">{{ $surat->source->pegawai->unitKerja->nama ?? '-' }}</div>
                            </div>
                            <div>
                                <span style="display:block; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">Jenis Cuti</span>
                                <div style="color:#334155; font-size:14px; font-weight:500;">{{ $surat->source->jenis_cuti ?? '-' }}</div>
                            </div>
                            <div>
                                <span style="display:block; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">Tanggal Mulai</span>
                                <div style="color:#334155; font-size:14px; font-weight:500;">{{ $surat->source->tanggal_mulai?->format('d M Y') ?? '-' }}</div>
                            </div>
                            <div>
                                <span style="display:block; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">Tanggal Selesai</span>
                                <div style="color:#334155; font-size:14px; font-weight:500;">{{ $surat->source->tanggal_selesai?->format('d M Y') ?? '-' }}</div>
                            </div>
                            <div>
                                <span style="display:block; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">Lama Cuti</span>
                                <div style="color:#334155; font-size:14px; font-weight:500;">{{ $surat->source->lama_cuti ?? '-' }} Hari</div>
                            </div>
                            <div>
                                <span style="display:block; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">No. Telepon</span>
                                <div style="color:#334155; font-size:14px; font-weight:500;">{{ $surat->source->no_telp ?? '-' }}</div>
                            </div>
                        </div>
                        <div>
                            <span style="display:block; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">Alasan Cuti</span>
                            <div style="color:#334155; font-size:14px; font-weight:500;">{{ $surat->source->alasan ?? '-' }}</div>
                        </div>
                        <div>
                            <span style="display:block; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">Alamat Selama Cuti</span>
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
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    </span>
                    Dokumen Surat Resmi
                </h3>

                @if($surat->file_word || $surat->file_pdf || $surat->file_dokumen)
                    <div style="display:flex; gap:12px; flex-wrap:wrap;">
                        @if($surat->file_pdf)
                            <a href="{{ Storage::url($surat->file_pdf) }}" target="_blank" style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:12px; background:#fee2e2; color:#dc2626; border-radius:12px; font-size:14px; font-weight:600; text-decoration:none;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                Buka PDF
                            </a>
                        @endif
                        @if($surat->file_word)
                            <a href="{{ Storage::url($surat->file_word) }}" target="_blank" style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:12px; background:#e0f2fe; color:#0284c7; border-radius:12px; font-size:14px; font-weight:600; text-decoration:none;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                Unduh Word
                            </a>
                        @endif
                        @if($surat->file_dokumen)
                            <a href="{{ Storage::url($surat->file_dokumen) }}" target="_blank" style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; padding:12px; background:#f0fdf4; color:#16a34a; border-radius:12px; font-size:14px; font-weight:600; text-decoration:none;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                Lihat Dokumen
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
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                    </span>
                    Lampiran/Dokumen Pendukung
                </h3>

                @if($allAttachments->count() > 0)
                    <div style="display:flex; flex-direction:column; gap:12px;">
                        @foreach($allAttachments as $attachment)
                            <div style="display:flex; align-items:center; justify-content:space-between; padding:12px; background:#f8fafc; border:1px solid rgba(0,0,0,0.05); border-radius:12px;">
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                                    <div>
                                        <div style="font-size:13px; font-weight:600; color:#334155;">{{ $attachment->original_name }}</div>
                                        <div style="font-size:11px; color:#94a3b8;">{{ number_format($attachment->size / 1024, 1) }} KB</div>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" style="padding:6px 12px; background:white; color:#3b82f6; border:1px solid #bfdbfe; border-radius:8px; font-size:12px; font-weight:600; text-decoration:none;">
                                    Lihat
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
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </span>
                    Riwayat / Log Aktivitas
                </h3>
            </div>
            
            <table class="data-table" style="width:100%;">
                <thead>
                    <tr>
                        <th style="width:180px;">Waktu</th>
                        <th>User</th>
                        <th>Aktivitas</th>
                        <th>Status</th>
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
