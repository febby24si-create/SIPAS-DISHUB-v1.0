<x-app-layout>
    <x-slot name="header">Detail Pegawai</x-slot>

    <div style="max-width:900px; display:flex; flex-direction:column; gap:24px;">
        <div style="display:flex; align-items:center; gap:8px; font-size:13px; color:#94a3b8;">
            <a href="{{ route('pegawai.index') }}" style="color:#64748b; text-decoration:none; font-weight:500; transition:color 0.15s;">Master Pegawai</a>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            <span style="color:#1e293b; font-weight:600;">Detail</span>
        </div>

        <div style="display:grid; grid-template-columns:1fr 2fr; gap:24px;">
            {{-- Profile Card --}}
            <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:24px; display:flex; flex-direction:column; align-items:center; text-align:center;">
                <div style="width:80px; height:80px; background:linear-gradient(135deg,#eff6ff,#dbeafe); border-radius:50%; display:flex; align-items:center; justify-content:center; margin-bottom:16px; border:4px solid white; box-shadow:0 4px 12px rgba(0,0,0,0.05);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <h3 style="font-size:18px; font-weight:700; color:#1e293b; margin:0 0 4px 0;">{{ $pegawai->nama }}</h3>
                <p style="font-family:monospace; font-size:13px; color:#64748b; margin:0 0 12px 0;">NIP: {{ $pegawai->nip }}</p>
                
                @if($pegawai->status_aktif)
                    <span style="display:inline-flex; align-items:center; padding:4px 12px; background:#ecfdf5; color:#059669; font-size:12px; font-weight:700; border-radius:20px; letter-spacing:0.5px; text-transform:uppercase;">Aktif</span>
                @else
                    <span style="display:inline-flex; align-items:center; padding:4px 12px; background:#f1f5f9; color:#475569; font-size:12px; font-weight:700; border-radius:20px; letter-spacing:0.5px; text-transform:uppercase;">Non-Aktif</span>
                @endif

                <div style="width:100%; height:1px; background:rgba(0,0,0,0.06); margin:20px 0;"></div>
                
                <a href="{{ route('pegawai.edit', $pegawai) }}" class="btn-primary" style="width:100%; text-decoration:none; display:inline-flex; justify-content:center; align-items:center; gap:8px; padding:10px 18px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; border-radius:12px; font-size:13px; font-weight:600; box-shadow:0 2px 8px rgba(29,78,216,0.3);">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Edit Pegawai
                </a>
            </div>

            {{-- Info Card --}}
            <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:24px;">
                <h4 style="font-size:15px; font-weight:700; color:#1e293b; margin:0 0 20px 0; display:flex; align-items:center; gap:8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    Informasi Karier Saat Ini
                </h4>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div>
                        <p style="font-size:12px; color:#94a3b8; margin:0 0 4px 0; font-weight:500; text-transform:uppercase; letter-spacing:0.5px;">Unit Kerja</p>
                        <p style="font-size:14px; color:#1e293b; font-weight:600; margin:0;">{{ $pegawai->unitKerja->nama ?? 'Belum diatur' }}</p>
                    </div>
                    <div>
                        <p style="font-size:12px; color:#94a3b8; margin:0 0 4px 0; font-weight:500; text-transform:uppercase; letter-spacing:0.5px;">Jabatan</p>
                        <p style="font-size:14px; color:#1e293b; font-weight:600; margin:0;">{{ $pegawai->jabatan ?? '-' }}</p>
                    </div>
                    <div>
                        <p style="font-size:12px; color:#94a3b8; margin:0 0 4px 0; font-weight:500; text-transform:uppercase; letter-spacing:0.5px;">Pangkat</p>
                        <p style="font-size:14px; color:#1e293b; font-weight:600; margin:0;">{{ $pegawai->pangkat ?? '-' }}</p>
                    </div>
                    <div>
                        <p style="font-size:12px; color:#94a3b8; margin:0 0 4px 0; font-weight:500; text-transform:uppercase; letter-spacing:0.5px;">Golongan</p>
                        <p style="font-size:14px; color:#1e293b; font-weight:600; margin:0;">{{ $pegawai->golongan ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Timeline Card --}}
        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:24px;">
            <div style="display:flex; align-items:center; justify-content:space-between; border-bottom:1px solid rgba(0,0,0,0.05); padding-bottom:16px; margin-bottom:20px;">
                <h4 style="font-size:15px; font-weight:700; color:#1e293b; margin:0; display:flex; align-items:center; gap:8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Histori Kepangkatan & Jabatan
                </h4>
            </div>

            @if($pegawai->riwayatJabatanPangkat->count() > 0)
                <div style="position:relative; padding-left:16px; border-left:2px solid #e2e8f0; margin-left:8px; display:flex; flex-direction:column; gap:24px;">
                    @foreach($pegawai->riwayatJabatanPangkat as $riwayat)
                        <div style="position:relative;">
                            <div style="position:absolute; left:-22px; top:4px; width:10px; height:10px; background:white; border:2px solid #3b82f6; border-radius:50%;"></div>
                            <div style="display:flex; flex-direction:column;">
                                <span style="font-size:13px; font-weight:700; color:#1e293b; display:inline-flex; align-items:center; gap:6px;">
                                    {{ $riwayat->tmt ? \Carbon\Carbon::parse($riwayat->tmt)->translatedFormat('d F Y') : '-' }}
                                    @if($loop->first)
                                        <span style="padding:2px 6px; background:#eff6ff; color:#1d4ed8; font-size:10px; font-weight:700; border-radius:4px;">TMT Saat Ini</span>
                                    @endif
                                </span>
                                <div style="margin-top:6px; padding:12px 16px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                                    <div>
                                        <p style="font-size:11px; color:#64748b; margin:0;">Jabatan</p>
                                        <p style="font-size:13px; font-weight:600; color:#334155; margin:0;">{{ $riwayat->jabatan ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p style="font-size:11px; color:#64748b; margin:0;">Pangkat / Golongan</p>
                                        <p style="font-size:13px; font-weight:600; color:#334155; margin:0;">{{ $riwayat->pangkat ?? '-' }} ({{ $riwayat->golongan ?? '-' }})</p>
                                    </div>
                                    <div style="grid-column:1 / -1;">
                                        <p style="font-size:11px; color:#64748b; margin:0;">Keterangan</p>
                                        <p style="font-size:13px; color:#475569; margin:0; font-style:italic;">{{ $riwayat->keterangan ?? 'Pembaruan data karier' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align:center; padding:32px 0;">
                    <p style="color:#94a3b8; font-size:14px; margin:0;">Belum ada histori jabatan/pangkat yang tercatat.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
