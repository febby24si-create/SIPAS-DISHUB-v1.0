<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>Detail Kenaikan Pangkat</div>
            <a href="{{ route('kepegawaian.pangkat.index') }}" style="display:inline-flex; align-items:center; gap:8px; padding:10px 18px; background:white; border:1px solid #cbd5e1; color:#334155; border-radius:12px; font-size:13px; font-weight:600; text-decoration:none;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div style="display:flex; flex-direction:column; gap:24px;">

        @if (session('status'))
            <div style="display:flex; align-items:center; gap:10px; padding:14px 18px; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:14px; color:#065f46; font-size:14px; font-weight:500;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div style="display:flex; align-items:center; gap:10px; padding:14px 18px; background:#fef2f2; border:1px solid #fecaca; border-radius:14px; color:#991b1b; font-size:14px; font-weight:500;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Header Status & Nomor --}}
        <div style="background:linear-gradient(135deg, #1e293b, #0f172a); border-radius:24px; padding:32px; color:white; position:relative; overflow:hidden;">
            <div style="position:relative; z-index:2;">
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
                    <span style="background:rgba(255,255,255,0.2); padding:6px 14px; border-radius:20px; font-size:12px; font-weight:600; letter-spacing:0.5px; text-transform:uppercase;">
                        STATUS: {{ $pangkat->status }}
                    </span>
                </div>
                <h2 style="font-size:28px; font-weight:700; margin:0 0 8px 0; font-family:monospace; color:#60a5fa;">
                    {{ $pangkat->nomor_usulan }}
                </h2>
                <p style="margin:0; color:#94a3b8; font-size:15px;">
                    Usulan Kenaikan Pangkat Pegawai
                </p>
            </div>
            <div style="position:absolute; top:-50%; right:-10%; width:300px; height:300px; background:radial-gradient(circle, rgba(59,130,246,0.15) 0%, rgba(0,0,0,0) 70%); border-radius:50%; z-index:1;"></div>
        </div>

        {{-- Grid Info --}}
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(400px, 1fr)); gap:24px;">
            
            {{-- Informasi Pegawai --}}
            <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:24px;">
                <h3 style="font-size:16px; font-weight:600; color:#1e293b; margin:0 0 20px 0; display:flex; align-items:center; gap:10px;">
                    <span style="display:flex; align-items:center; justify-content:center; width:32px; height:32px; background:#eff6ff; color:#3b82f6; border-radius:10px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </span>
                    Informasi Pegawai & Saat Ini
                </h3>
                
                <div style="display:flex; flex-direction:column; gap:16px;">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div>
                            <span style="display:block; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">Nama Pegawai</span>
                            <div style="color:#334155; font-size:14px; font-weight:500;">{{ $pangkat->pegawai->nama }}</div>
                        </div>
                        <div>
                            <span style="display:block; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">NIP</span>
                            <div style="color:#334155; font-size:14px; font-weight:500;">{{ $pangkat->pegawai->nip }}</div>
                        </div>
                        <div>
                            <span style="display:block; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">Unit Kerja</span>
                            <div style="color:#334155; font-size:14px; font-weight:500;">{{ $pangkat->pegawai->unitKerja->nama ?? '-' }}</div>
                        </div>
                        <div></div>
                        <div>
                            <span style="display:block; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">Pangkat Lama</span>
                            <div style="color:#334155; font-size:14px; font-weight:500;">{{ $pangkat->pangkat_lama }}</div>
                        </div>
                        <div>
                            <span style="display:block; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">Golongan Lama</span>
                            <div style="color:#334155; font-size:14px; font-weight:500;">{{ $pangkat->golongan_lama }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Informasi Kenaikan --}}
            <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:24px;">
                <h3 style="font-size:16px; font-weight:600; color:#1e293b; margin:0 0 20px 0; display:flex; align-items:center; gap:10px;">
                    <span style="display:flex; align-items:center; justify-content:center; width:32px; height:32px; background:#f5f3ff; color:#8b5cf6; border-radius:10px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="13 2 13 9 20 9"/><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="9 16 12 13 15 16"/><line x1="12" y1="13" x2="12" y2="19"/></svg>
                    </span>
                    Informasi Kenaikan
                </h3>
                <div style="display:flex; flex-direction:column; gap:16px;">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div>
                            <span style="display:block; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">Pangkat Baru</span>
                            <div style="color:#059669; font-size:14px; font-weight:700;">{{ $pangkat->pangkat_baru }}</div>
                        </div>
                        <div>
                            <span style="display:block; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">Golongan Baru</span>
                            <div style="color:#059669; font-size:14px; font-weight:700;">{{ $pangkat->golongan_baru }}</div>
                        </div>
                        <div>
                            <span style="display:block; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">TMT Kenaikan</span>
                            <div style="color:#334155; font-size:14px; font-weight:500;">{{ $pangkat->tmt?->format('d M Y') }}</div>
                        </div>
                    </div>
                    <div>
                        <span style="display:block; font-size:12px; font-weight:600; color:#94a3b8; margin-bottom:4px; text-transform:uppercase;">Catatan Tambahan</span>
                        <div style="color:#334155; font-size:14px; font-weight:500;">{{ $pangkat->catatan ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(400px, 1fr)); gap:24px;">
            {{-- Lampiran Pendukung --}}
            <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:24px;">
                <h3 style="font-size:16px; font-weight:600; color:#1e293b; margin:0 0 20px 0; display:flex; align-items:center; gap:10px;">
                    <span style="display:flex; align-items:center; justify-content:center; width:32px; height:32px; background:#fff7ed; color:#ea580c; border-radius:10px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                    </span>
                    Lampiran Pendukung
                </h3>

                @if($pangkat->attachments->count() > 0)
                    <div style="display:flex; flex-direction:column; gap:12px;">
                        @foreach($pangkat->attachments as $attachment)
                            <div style="display:flex; align-items:center; justify-content:space-between; padding:12px; background:#f8fafc; border:1px solid rgba(0,0,0,0.05); border-radius:12px;">
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                                    <div>
                                        <div style="font-size:13px; font-weight:600; color:#334155;">{{ $attachment->original_name }}</div>
                                        <div style="font-size:11px; color:#94a3b8;">{{ number_format($attachment->size / 1024, 1) }} KB</div>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" style="padding:6px 12px; background:white; color:#3b82f6; border:1px solid #bfdbfe; border-radius:8px; font-size:12px; font-weight:600; text-decoration:none;">
                                    Lihat File
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

            {{-- Panel Workflow / Aksi --}}
            <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:24px;">
                <h3 style="font-size:16px; font-weight:600; color:#1e293b; margin:0 0 20px 0; display:flex; align-items:center; gap:10px;">
                    <span style="display:flex; align-items:center; justify-content:center; width:32px; height:32px; background:#ecfdf5; color:#10b981; border-radius:10px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </span>
                    Tindakan & Workflow
                </h3>

                @if($pangkat->status === 'selesai')
                    <div style="display:flex; align-items:center; justify-content:center; gap:10px; padding:20px; background:#f0fdf4; border:1px dashed #86efac; border-radius:12px; color:#166534; font-size:14px; font-weight:600;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Proses Kenaikan Pangkat Telah Selesai
                    </div>
                @else
                    <form action="{{ route('kepegawaian.pangkat.status', $pangkat) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div style="display:flex; flex-direction:column; gap:16px;">
                            <div>
                                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:8px;">Catatan (Opsional)</label>
                                <textarea name="catatan" rows="2" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:10px; font-size:14px; color:#1e293b;"></textarea>
                            </div>
                            
                            <div style="display:flex; gap:12px; flex-wrap:wrap;">
                                @if($pangkat->status === 'draft')
                                    <button type="submit" name="status" value="diajukan" style="flex:1; padding:12px; background:#3b82f6; color:white; border:none; border-radius:12px; font-weight:600; cursor:pointer;">Ajukan</button>
                                @elseif($pangkat->status === 'diajukan')
                                    <button type="submit" name="status" value="verifikasi" style="flex:1; padding:12px; background:#eab308; color:white; border:none; border-radius:12px; font-weight:600; cursor:pointer;">Verifikasi</button>
                                    <button type="submit" name="status" value="ditolak" style="flex:1; padding:12px; background:#ef4444; color:white; border:none; border-radius:12px; font-weight:600; cursor:pointer;">Tolak</button>
                                @elseif($pangkat->status === 'verifikasi')
                                    <button type="submit" name="status" value="diproses" style="flex:1; padding:12px; background:#a21caf; color:white; border:none; border-radius:12px; font-weight:600; cursor:pointer;">Proses</button>
                                    <button type="submit" name="status" value="ditolak" style="flex:1; padding:12px; background:#ef4444; color:white; border:none; border-radius:12px; font-weight:600; cursor:pointer;">Tolak</button>
                                @elseif($pangkat->status === 'diproses')
                                    <button type="submit" name="status" value="selesai" style="flex:1; padding:12px; background:#10b981; color:white; border:none; border-radius:12px; font-weight:600; cursor:pointer;">Selesaikan</button>
                                @elseif($pangkat->status === 'ditolak')
                                    <div style="width:100%; text-align:center; padding:12px; background:#fef2f2; color:#b91c1c; border-radius:12px; font-weight:600;">Usulan Telah Ditolak</div>
                                @endif
                            </div>
                        </div>
                    </form>
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
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pangkat->activityLogs as $log)
                        <tr>
                            <td style="color:#64748b; font-size:13px;">{{ $log->created_at->format('d M Y H:i') }}</td>
                            <td style="font-weight:600; color:#1e293b; font-size:13px;">{{ $log->user->name ?? 'Sistem' }}</td>
                            <td style="color:#334155; font-size:13px;">{{ $log->aktivitas }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align:center; padding:32px; color:#94a3b8;">
                                <p style="margin:0; font-size:14px; font-weight:500;">Tidak ada log aktivitas tercatat.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
