<x-app-layout>
    <x-slot name="header">Buat Surat</x-slot>

    <div style="max-width:820px;">
        {{-- Breadcrumb --}}
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:20px; font-size:13px; color:#94a3b8;">
            <a href="{{ route('dashboard') }}" style="color:#64748b; text-decoration:none; font-weight:500;" onmouseover="this.style.color='#1d4ed8'" onmouseout="this.style.color='#64748b'">Dashboard</a>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            <a href="{{ route('buat-surat.create') }}" style="color:#64748b; text-decoration:none; font-weight:500;" onmouseover="this.style.color='#1d4ed8'" onmouseout="this.style.color='#64748b'">Buat Surat Baru</a>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            <span style="color:#1e293b; font-weight:600;">{{ $jenisSurat->nama }}</span>
        </div>

        {{-- Step Indicator --}}
        <div style="display:flex; gap:0; margin-bottom:24px; background:white; border-radius:16px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            @foreach ([['1','Pilih Jenis Surat','Tentukan kategori surat'],['2','Pilih Template','Pilih format resmi'],['3','Isi Data & Generate','Lengkapi informasi']] as $i => $step)
                <div style="flex:1; padding:16px 20px; display:flex; align-items:center; gap:12px; {{ $i < 2 ? 'border-right:1px solid rgba(0,0,0,0.06);' : '' }}">
                    <div style="width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; flex-shrink:0;
                                background:{{ $i <= 1 ? 'linear-gradient(135deg,#1d4ed8,#3b82f6)' : '#f1f5f9' }};
                                color:{{ $i <= 1 ? 'white' : '#94a3b8' }};">
                        {{ $step[0] }}
                    </div>
                    <div>
                        <p style="font-size:13px; font-weight:700; color:{{ $i <= 1 ? '#1e293b' : '#94a3b8' }}; margin:0;">{{ $step[1] }}</p>
                        <p style="font-size:11px; color:#cbd5e1; margin:0;">{{ $step[2] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <div style="padding:18px 24px; border-bottom:1px solid rgba(0,0,0,0.05); background:linear-gradient(135deg,#f8fafc,#f0f9ff); display:flex; align-items:center; justify-content:space-between;">
                <div>
                    <h3 style="font-size:15px; font-weight:700; color:#1e293b; margin:0;">Pilih Template — {{ $jenisSurat->nama }}</h3>
                    <p style="font-size:12px; color:#94a3b8; margin:2px 0 0 0;">Hanya menampilkan template aktif untuk jenis surat ini.</p>
                </div>
                <a href="{{ route('buat-surat.create') }}"
                   style="font-size:12px; font-weight:600; color:#64748b; text-decoration:none; padding:8px 14px; border:1px solid rgba(0,0,0,0.08); border-radius:10px;"
                   onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                    &larr; Ganti Jenis Surat
                </a>
            </div>

            <div style="padding:24px;">
                @if ($templates->isEmpty())
                    <div style="display:flex; align-items:flex-start; gap:12px; padding:16px 20px; background:#fffbeb; border:1px solid #fde68a; border-radius:14px; color:#92400e; font-size:13px; line-height:1.6;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:2px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span>
                            Belum ada template aktif untuk <strong>{{ $jenisSurat->nama }}</strong>.
                            Tambahkan dahulu di Master Data &rarr; Template Surat.
                        </span>
                    </div>
                @else
                    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(240px, 1fr)); gap:14px;">
                        @foreach ($templates as $template)
                            <a href="{{ route('buat-surat.form', $template) }}"
                               style="display:flex; flex-direction:column; gap:10px; padding:18px; border-radius:16px; border:1px solid rgba(0,0,0,0.07); text-decoration:none; cursor:pointer; transition:all 0.15s; background:#fafcff;"
                               onmouseover="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 4px 14px rgba(59,130,246,0.12)'; this.style.transform='translateY(-1px)'"
                               onmouseout="this.style.borderColor='rgba(0,0,0,0.07)'; this.style.boxShadow='none'; this.style.transform='translateY(0)'">
                                <div style="display:flex; align-items:center; justify-content:space-between;">
                                    <div style="width:36px; height:36px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); border-radius:10px; display:flex; align-items:center; justify-content:center;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                                    </div>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                                </div>
                                <div>
                                    <p style="font-size:14px; font-weight:700; color:#1e293b; margin:0;">{{ $template->nama_template }}</p>
                                    <p style="font-size:11.5px; color:#94a3b8; margin:4px 0 0 0;">
                                        {{ is_array($template->placeholder_json) ? count($template->placeholder_json) : 0 }} field placeholder
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>