<x-app-layout>
    <x-slot name="header">Buat Surat</x-slot>

    <div style="max-width:820px;">
        {{-- Breadcrumb --}}
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:20px; font-size:13px; color:#94a3b8;">
            <a href="{{ route('dashboard') }}" style="color:#64748b; text-decoration:none; font-weight:500;" onmouseover="this.style.color='#1d4ed8'" onmouseout="this.style.color='#64748b'">Dashboard</a>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            <span style="color:#1e293b; font-weight:600;">Buat Surat Baru</span>
        </div>

        @if (session('status'))
            <div style="display:flex; align-items:center; gap:10px; padding:14px 18px; margin-bottom:20px; background:#fffbeb; border:1px solid #fde68a; border-radius:14px; color:#92400e; font-size:14px; font-weight:500;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ session('status') }}
            </div>
        @endif

        {{-- Step Indicator --}}
        <div style="display:flex; gap:0; margin-bottom:24px; background:white; border-radius:16px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            @foreach ([['1','Pilih Jenis Surat','Tentukan kategori surat'],['2','Pilih Template','Pilih format resmi'],['3','Isi Data & Generate','Lengkapi informasi']] as $i => $step)
                <div style="flex:1; padding:16px 20px; display:flex; align-items:center; gap:12px; {{ $i < 2 ? 'border-right:1px solid rgba(0,0,0,0.06);' : '' }}">
                    <div style="width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; flex-shrink:0;
                                background:{{ $i === 0 ? 'linear-gradient(135deg,#1d4ed8,#3b82f6)' : '#f1f5f9' }};
                                color:{{ $i === 0 ? 'white' : '#94a3b8' }};">
                        {{ $step[0] }}
                    </div>
                    <div>
                        <p style="font-size:13px; font-weight:700; color:{{ $i === 0 ? '#1e293b' : '#94a3b8' }}; margin:0;">{{ $step[1] }}</p>
                        <p style="font-size:11px; color:#cbd5e1; margin:0;">{{ $step[2] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <div style="padding:18px 24px; border-bottom:1px solid rgba(0,0,0,0.05); background:linear-gradient(135deg,#f8fafc,#f0f9ff);">
                <h3 style="font-size:15px; font-weight:700; color:#1e293b; margin:0;">Pilih Jenis Surat</h3>
                <p style="font-size:12px; color:#94a3b8; margin:2px 0 0 0;">Pilih jenis surat yang ingin dibuat. Daftar di bawah diambil langsung dari Master Data &rarr; Jenis Surat.</p>
            </div>

            <div style="padding:24px;">
                @if ($jenisSuratList->isEmpty())
                    <div style="padding:20px; text-align:center; color:#94a3b8; font-size:13px;">
                        Belum ada Jenis Surat. Tambahkan terlebih dahulu di Master Data &rarr; Jenis Surat.
                    </div>
                @else
                    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(220px, 1fr)); gap:14px;">
                        @foreach ($jenisSuratList as $jenis)
                            @php $tersedia = $jenis->templates_count > 0; @endphp
                            <a href="{{ $tersedia ? route('buat-surat.pilih-template', $jenis) : '#' }}"
                               style="display:flex; flex-direction:column; gap:10px; padding:18px; border-radius:16px; border:1px solid rgba(0,0,0,0.07); text-decoration:none;
                                      {{ $tersedia ? 'cursor:pointer;' : 'cursor:not-allowed; opacity:0.55;' }}
                                      transition:all 0.15s; background:#fafcff;"
                               @if($tersedia)
                               onmouseover="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 4px 14px rgba(59,130,246,0.12)'; this.style.transform='translateY(-1px)'"
                               onmouseout="this.style.borderColor='rgba(0,0,0,0.07)'; this.style.boxShadow='none'; this.style.transform='translateY(0)'"
                               @endif>
                                <div style="display:flex; align-items:center; justify-content:space-between;">
                                    <span style="display:inline-flex; align-items:center; justify-content:center; width:38px; height:38px; border-radius:10px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; font-size:12px; font-weight:800;">
                                        {{ $jenis->kode }}
                                    </span>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                                </div>
                                <div>
                                    <p style="font-size:14px; font-weight:700; color:#1e293b; margin:0;">{{ $jenis->nama }}</p>
                                    <p style="font-size:11.5px; color:#94a3b8; margin:4px 0 0 0;">
                                        {{ $tersedia ? $jenis->templates_count . ' template aktif tersedia' : 'Belum ada template aktif' }}
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