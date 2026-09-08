<x-app-layout>
    <x-slot name="header">Detail Pengajuan Cuti</x-slot>

    <div style="max-w:900px; margin:0 auto; display:flex; flex-direction:column; gap:20px;">
        
        <div style="display:flex; align-items:center; justify-content:space-between;">
            <a href="{{ route('kepegawaian.cuti.index') }}" style="display:inline-flex; align-items:center; gap:8px; font-size:14px; font-weight:600; color:#64748b; text-decoration:none;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali
            </a>
            
            <div style="display:flex; gap:12px;">
                @if($cuti->status === 'draft' && in_array(auth()->user()->role?->name, ['admin', 'staff']))
                    <a href="{{ route('kepegawaian.cuti.edit', $cuti) }}" style="display:inline-flex; align-items:center; gap:8px; padding:10px 18px; background:white; border:1px solid #cbd5e1; color:#334155; border-radius:12px; font-size:13px; font-weight:600; text-decoration:none;">
                        Edit Draft
                    </a>
                @endif
                
                @if($cuti->status === 'draft' && in_array(auth()->user()->role?->name, ['admin', 'staff']))
                    <form action="{{ route('kepegawaian.cuti.status', $cuti) }}" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="diajukan">
                        <button type="submit" style="cursor:pointer; display:inline-flex; align-items:center; padding:10px 18px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; border:none; border-radius:12px; font-size:13px; font-weight:600;">
                            Ajukan
                        </button>
                    </form>
                @endif

                @if($cuti->status === 'diajukan' && in_array(auth()->user()->role?->name, ['admin', 'verifikator']))
                    <form action="{{ route('kepegawaian.cuti.status', $cuti) }}" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="verifikasi">
                        <button type="submit" style="cursor:pointer; display:inline-flex; align-items:center; padding:10px 18px; background:linear-gradient(135deg,#b45309,#d97706); color:white; border:none; border-radius:12px; font-size:13px; font-weight:600;">
                            Verifikasi
                        </button>
                    </form>
                @endif

                @if($cuti->status === 'verifikasi' && in_array(auth()->user()->role?->name, ['admin', 'pimpinan']))
                    <form action="{{ route('kepegawaian.cuti.status', $cuti) }}" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="disetujui">
                        <button type="submit" style="cursor:pointer; display:inline-flex; align-items:center; padding:10px 18px; background:linear-gradient(135deg,#059669,#10b981); color:white; border:none; border-radius:12px; font-size:13px; font-weight:600;">
                            Setujui
                        </button>
                    </form>
                @endif

                @if($cuti->status === 'disetujui' && in_array(auth()->user()->role?->name, ['admin', 'staff', 'verifikator', 'pimpinan']))
                    <form action="{{ route('kepegawaian.cuti.status', $cuti) }}" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="diterbitkan">
                        <button type="submit" style="cursor:pointer; display:inline-flex; align-items:center; padding:10px 18px; background:linear-gradient(135deg,#a21caf,#c026d3); color:white; border:none; border-radius:12px; font-size:13px; font-weight:600;">
                            Terbitkan Surat
                        </button>
                    </form>
                @endif
                
                @if($cuti->status === 'diterbitkan' && in_array(auth()->user()->role?->name, ['admin', 'staff']))
                    <form action="{{ route('kepegawaian.cuti.status', $cuti) }}" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="selesai">
                        <button type="submit" style="cursor:pointer; display:inline-flex; align-items:center; padding:10px 18px; background:linear-gradient(135deg,#166534,#22c55e); color:white; border:none; border-radius:12px; font-size:13px; font-weight:600;">
                            Selesaikan
                        </button>
                    </form>
                @endif
                
                @if(in_array($cuti->status, ['diajukan', 'verifikasi']) && in_array(auth()->user()->role?->name, ['admin', 'verifikator', 'pimpinan']))
                    <form action="{{ route('kepegawaian.cuti.status', $cuti) }}" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="ditolak">
                        <button type="submit" style="cursor:pointer; display:inline-flex; align-items:center; padding:10px 18px; background:white; border:1px solid #ef4444; color:#ef4444; border-radius:12px; font-size:13px; font-weight:600;">
                            Tolak
                        </button>
                    </form>
                @endif
            </div>
        </div>

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

        <div style="display:grid; grid-template-columns:2fr 1fr; gap:24px;">
            <div style="display:flex; flex-direction:column; gap:24px;">
                {{-- Detail Cuti --}}
                <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:24px;">
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
                        <h3 style="margin:0; font-size:16px; font-weight:600; color:#1e293b; display:flex; align-items:center; gap:8px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            Informasi Pengajuan
                        </h3>
                        @php
                            $statusColors = [
                                'draft'=>['#f1f5f9','#475569'],
                                'diajukan'=>['#eff6ff','#1d4ed8'],
                                'verifikasi'=>['#fef3c7','#b45309'],
                                'disetujui'=>['#ecfdf5','#059669'],
                                'diterbitkan'=>['#fdf4ff','#a21caf'],
                                'selesai'=>['#f0fdf4','#166534'],
                                'ditolak'=>['#fef2f2','#b91c1c'],
                            ];
                            $sc = $statusColors[$cuti->status] ?? ['#f1f5f9','#475569'];
                        @endphp
                        <span style="display:inline-flex; padding:6px 14px; background:{{ $sc[0] }}; color:{{ $sc[1] }}; font-size:13px; font-weight:700; border-radius:20px; text-transform:uppercase; letter-spacing:0.5px;">
                            {{ $cuti->status }}
                        </span>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:24px;">
                        <div>
                            <div style="font-size:11px; font-weight:600; color:#64748b; text-transform:uppercase; margin-bottom:4px;">Nomor Pengajuan</div>
                            <div style="font-size:14px; font-weight:600; color:#1e293b; font-family:monospace;">{{ $cuti->nomor_pengajuan }}</div>
                        </div>
                        <div>
                            <div style="font-size:11px; font-weight:600; color:#64748b; text-transform:uppercase; margin-bottom:4px;">Tanggal Dibuat</div>
                            <div style="font-size:14px; font-weight:500; color:#1e293b;">{{ $cuti->created_at->format('d M Y, H:i') }}</div>
                        </div>
                    </div>

                    <div style="height:1px; background:rgba(0,0,0,0.05); margin:20px 0;"></div>

                    <h4 style="margin:0 0 12px; font-size:14px; font-weight:600; color:#1e293b;">Detail Pegawai</h4>
                    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:16px; display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div>
                            <div style="font-size:11px; font-weight:600; color:#64748b; margin-bottom:2px;">Nama / NIP</div>
                            <div style="font-size:13px; font-weight:600; color:#1e293b;">{{ $cuti->pegawai->nama }}</div>
                            <div style="font-size:13px; color:#475569;">{{ $cuti->pegawai->nip }}</div>
                        </div>
                        <div>
                            <div style="font-size:11px; font-weight:600; color:#64748b; margin-bottom:2px;">Jabatan</div>
                            <div style="font-size:13px; font-weight:500; color:#1e293b;">{{ $cuti->pegawai->jabatan }}</div>
                            <div style="font-size:13px; color:#475569;">{{ $cuti->pegawai->pangkat }} - {{ $cuti->pegawai->golongan }}</div>
                        </div>
                    </div>

                    <div style="height:1px; background:rgba(0,0,0,0.05); margin:20px 0;"></div>

                    <h4 style="margin:0 0 12px; font-size:14px; font-weight:600; color:#1e293b;">Isi Cuti</h4>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                        <div>
                            <div style="font-size:11px; font-weight:600; color:#64748b; margin-bottom:2px;">Jenis Cuti</div>
                            <div style="font-size:14px; font-weight:500; color:#1e293b;">{{ $cuti->jenis_cuti }}</div>
                        </div>
                        <div>
                            <div style="font-size:11px; font-weight:600; color:#64748b; margin-bottom:2px;">Lama Cuti</div>
                            <div style="font-size:14px; font-weight:500; color:#1e293b;">
                                {{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->format('d M Y') }}
                                <span style="color:#64748b;">({{ $cuti->lama_cuti }} hari)</span>
                            </div>
                        </div>
                    </div>
                    <div style="margin-bottom:16px;">
                        <div style="font-size:11px; font-weight:600; color:#64748b; margin-bottom:2px;">Alasan Cuti</div>
                        <div style="font-size:14px; color:#334155; line-height:1.5;">{{ $cuti->alasan }}</div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div>
                            <div style="font-size:11px; font-weight:600; color:#64748b; margin-bottom:2px;">Alamat Selama Cuti</div>
                            <div style="font-size:14px; color:#334155;">{{ $cuti->alamat_cuti ?: '-' }}</div>
                        </div>
                        <div>
                            <div style="font-size:11px; font-weight:600; color:#64748b; margin-bottom:2px;">No. Telepon</div>
                            <div style="font-size:14px; color:#334155;">{{ $cuti->no_telp ?: '-' }}</div>
                        </div>
                    </div>
                </div>

                {{-- Surat Keputusan --}}
                @if($cuti->surat)
                <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:24px; position:relative; overflow:hidden;">
                    <div style="position:absolute; top:0; left:0; width:4px; height:100%; background:#059669;"></div>
                    <h3 style="margin:0 0 20px; font-size:16px; font-weight:600; color:#1e293b; display:flex; align-items:center; gap:8px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Surat Cuti Berhasil Diterbitkan
                    </h3>
                    
                    <div style="display:flex; align-items:center; justify-content:space-between; padding:16px; border:1px solid #e2e8f0; border-radius:12px; background:#f8fafc; flex-wrap:wrap; gap:16px;">
                        <div>
                            <div style="font-size:11px; font-weight:600; color:#64748b; text-transform:uppercase; margin-bottom:4px;">Nomor Surat Resmi</div>
                            <div style="font-size:16px; font-weight:700; color:#1e293b; font-family:monospace; margin-bottom:4px;">{{ $cuti->surat->nomor_surat }}</div>
                            <div style="font-size:13px; color:#475569;">
                                Tgl. {{ \Carbon\Carbon::parse($cuti->surat->tanggal_surat)->translatedFormat('d F Y') }}
                            </div>
                        </div>
                        
                        <div style="display:flex; flex-direction:column; align-items:flex-end; gap:8px;">
                            <span style="display:inline-flex; align-items:center; gap:6px; padding:6px 12px; background:#ecfdf5; color:#065f46; font-size:12px; font-weight:700; border-radius:20px; border:1px solid #a7f3d0;">
                                <span style="width:6px; height:6px; background:#059669; border-radius:50%;"></span>
                                Tersimpan di Arsip
                            </span>
                        </div>
                    </div>
                    
                    <div style="margin-top:16px; display:flex; align-items:center; gap:8px; padding:12px 16px; background:#eff6ff; border-radius:12px; color:#1e40af; font-size:13px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        Surat pengajuan cuti ini telah terintegrasi dengan arsip persuratan Dinas Perhubungan.
                    </div>
                    
                    @if($cuti->surat->file_word || $cuti->surat->file_pdf)
                        <div style="margin-top:16px; text-align:right;">
                            <a href="{{ route('buat-surat.show', $cuti->surat->id) }}" style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; background:white; border:1px solid #cbd5e1; color:#1d4ed8; border-radius:10px; font-size:13px; font-weight:600; text-decoration:none;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                Lihat Dokumen Fisik
                            </a>
                        </div>
                    @endif
                </div>
                @endif
                
                {{-- Attachments --}}
                <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:24px;">
                    <h3 style="margin:0 0 20px; font-size:16px; font-weight:600; color:#1e293b; display:flex; align-items:center; gap:8px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                        Dokumen Pendukung
                    </h3>
                    
                    @if($cuti->attachments->count() > 0)
                        <div style="display:flex; flex-direction:column; gap:12px;">
                            @foreach($cuti->attachments as $att)
                                <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 16px; border:1px solid #e2e8f0; border-radius:12px; background:#f8fafc;">
                                    <div style="display:flex; align-items:center; gap:12px;">
                                        <div style="width:32px; height:32px; border-radius:8px; background:#e0e7ff; color:#4f46e5; display:flex; align-items:center; justify-content:center;">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                                        </div>
                                        <div>
                                            <div style="font-size:13px; font-weight:500; color:#1e293b;">{{ $att->original_name }}</div>
                                            <div style="font-size:11px; color:#64748b;">{{ round($att->size / 1024, 1) }} KB • {{ $att->created_at->format('d M Y, H:i') }}</div>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/'.$att->file_path) }}" target="_blank" style="padding:6px 12px; background:white; border:1px solid #cbd5e1; color:#334155; border-radius:8px; font-size:12px; font-weight:600; text-decoration:none;">
                                        Unduh
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="padding:20px; text-align:center; background:#f8fafc; border:1px dashed #cbd5e1; border-radius:12px; color:#94a3b8; font-size:13px;">
                            Tidak ada dokumen pendukung.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar Log --}}
            <div>
                <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:24px;">
                    <h3 style="margin:0 0 20px; font-size:14px; font-weight:600; color:#1e293b; display:flex; align-items:center; gap:8px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Riwayat Aktivitas
                    </h3>
                    
                    <div style="display:flex; flex-direction:column; gap:16px;">
                        @foreach($cuti->activityLogs()->latest()->get() as $log)
                            <div style="display:flex; gap:12px; position:relative;">
                                @if(!$loop->last)
                                    <div style="position:absolute; top:24px; bottom:-16px; left:11px; width:2px; background:#e2e8f0;"></div>
                                @endif
                                <div style="width:24px; height:24px; border-radius:50%; background:#f1f5f9; border:2px solid white; display:flex; align-items:center; justify-content:center; flex-shrink:0; position:relative; z-index:2; color:#64748b;">
                                    @if($log->action == 'created')
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                    @elseif($log->action == 'updated' && isset($log->new_values['status']))
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    @else
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="2"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <div style="font-size:13px; font-weight:600; color:#1e293b;">
                                        @if($log->action == 'created')
                                            Draft Dibuat
                                        @elseif($log->action == 'updated' && isset($log->new_values['status']))
                                            Status: <span style="text-transform:uppercase;">{{ $log->new_values['status'] }}</span>
                                        @else
                                            Pengajuan Diperbarui
                                        @endif
                                    </div>
                                    <div style="font-size:12px; color:#475569; margin-top:2px;">Oleh {{ $log->user?->name ?? 'Sistem' }}</div>
                                    <div style="font-size:11px; color:#94a3b8; margin-top:4px;">{{ $log->created_at->format('d M Y, H:i') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
