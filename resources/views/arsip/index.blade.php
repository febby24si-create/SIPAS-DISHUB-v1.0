<x-app-layout>
    <x-slot name="header">Arsip Digital</x-slot>

    <div style="display:flex; flex-direction:column; gap:20px;">

        <form method="GET"
              x-data="{
                  bidang: '{{ request('bidang_id') }}',
                  seksi: '{{ request('seksi_id') }}',
                  bidangs: {{ $bidangs->toJson() }}
              }"
              style="background:white; border:1px solid rgba(0,0,0,0.07); border-radius:14px; padding:14px 16px; display:flex; flex-direction:column; gap:10px;">

            {{-- Baris 1: Search + Arah --}}
            <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari perihal surat..."
                       style="flex:1; min-width:160px; border:none; outline:none; font-size:14px; color:#334155; background:transparent;">
                <select name="arah" style="border:1px solid #e2e8f0; border-radius:8px; padding:6px 10px; font-size:13px; color:#334155;">
                    <option value="">Semua Arah</option>
                    <option value="masuk" @selected(request('arah') === 'masuk')>Masuk</option>
                    <option value="keluar" @selected(request('arah') === 'keluar')>Keluar</option>
                </select>
            </div>

            {{-- Baris 2: Filter Bidang, Seksi, Tahun --}}
            <div style="display:flex; align-items:flex-start; gap:10px; flex-wrap:wrap;">
                {{-- Filter Bidang --}}
                <div style="display:flex; flex-direction:column; gap:4px; min-width:200px; flex:1;">
                    <label style="font-size:11px; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:.5px;">Bidang</label>
                    <select name="bidang_id" x-model="bidang" @change="seksi = ''"
                            style="width:100%; border:1px solid #e2e8f0; border-radius:8px; padding:7px 10px; font-size:13px; color:#334155;">
                        <option value="">Semua Bidang</option>
                        <template x-for="b in bidangs" :key="b.id">
                            <option :value="b.id" x-text="b.nama"></option>
                        </template>
                    </select>
                </div>

                {{-- Filter Seksi (bergantung Bidang) --}}
                <div style="display:flex; flex-direction:column; gap:4px; min-width:220px; flex:1;">
                    <label style="font-size:11px; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:.5px;">Seksi</label>
                    <select name="seksi_id" x-model="seksi"
                            style="width:100%; border:1px solid #e2e8f0; border-radius:8px; padding:7px 10px; font-size:13px; color:#334155;">
                        <option value="">Semua Seksi</option>
                        <template x-if="bidang">
                            <template x-for="s in bidangs.find(b => b.id == bidang)?.children || []" :key="s.id">
                                <option :value="s.id" x-text="s.nama"></option>
                            </template>
                        </template>
                    </select>
                </div>

                {{-- Filter Tahun --}}
                @if($tahunList->isNotEmpty())
                <div style="display:flex; flex-direction:column; gap:4px; min-width:120px;">
                    <label style="font-size:11px; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:.5px;">Tahun</label>
                    <select name="tahun" style="width:100%; border:1px solid #e2e8f0; border-radius:8px; padding:7px 10px; font-size:13px; color:#334155;">
                        <option value="">Semua Tahun</option>
                        @foreach($tahunList as $tahun)
                            <option value="{{ $tahun }}" @selected(request('tahun') == $tahun)>{{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div style="display:flex; flex-direction:column; gap:4px; justify-content:flex-end; align-self:flex-end;">
                    <button type="submit" style="padding:8px 16px; background:#1d4ed8; color:white; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; white-space:nowrap;">
                        Cari / Filter
                    </button>
                </div>
            </div>

            {{-- Indikator filter aktif --}}
            @if(request()->hasAny(['bidang_id','seksi_id','tahun','q','arah']))
            <div style="display:flex; align-items:center; gap:8px; padding-top:4px;">
                <span style="font-size:12px; color:#64748b;">Filter aktif:</span>
                @if(request('q'))<span style="padding:2px 8px; background:#eff6ff; color:#1d4ed8; border-radius:6px; font-size:11px; font-weight:600;">Pencarian: "{{ request('q') }}"</span>@endif
                @if(request('bidang_id'))
                    @php $activeBidang = $bidangs->firstWhere('id', request('bidang_id')); @endphp
                    @if($activeBidang)<span style="padding:2px 8px; background:#f0fdf4; color:#059669; border-radius:6px; font-size:11px; font-weight:600;">Bidang: {{ $activeBidang->nama }}</span>@endif
                @endif
                @if(request('seksi_id'))
                    @php $activeSeksi = $bidangs->flatMap->children->firstWhere('id', request('seksi_id')); @endphp
                    @if($activeSeksi)<span style="padding:2px 8px; background:#fefce8; color:#ca8a04; border-radius:6px; font-size:11px; font-weight:600;">Seksi: {{ $activeSeksi->nama }}</span>@endif
                @endif
                @if(request('tahun'))<span style="padding:2px 8px; background:#fdf4ff; color:#9333ea; border-radius:6px; font-size:11px; font-weight:600;">Tahun: {{ request('tahun') }}</span>@endif
                <a href="{{ route('arsip.index') }}" style="font-size:11px; color:#ef4444; font-weight:600; text-decoration:none; margin-left:4px;">✕ Reset</a>
            </div>
            @endif
        </form>

        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:60px;">No.</th>
                        <th>Nomor Surat</th>
                        <th>Perihal</th>
                        <th>Unit Kerja / Seksi</th>
                        <th>Jenis</th>
                        <th>Arah</th>
                        <th>Tanggal</th>
                        <th style="width:100px; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($arsip as $item)
                        <tr>
                            <td style="color:#94a3b8; font-size:13px;">{{ $loop->iteration }}.</td>
                            <td style="font-family:monospace; font-size:12px; color:#334155;">{{ $item->nomor_surat ?? '-' }}</td>
                            <td style="font-weight:500; color:#1e293b;">{{ $item->perihal }}</td>
                            <td style="font-size:12px; color:#475569;">
                                @if($item->unitKerja)
                                    @if($item->unitKerja->parent)
                                        <span style="color:#94a3b8; display:block; font-size:11px;">{{ $item->unitKerja->parent->nama }}</span>
                                    @endif
                                    <span style="font-weight:500;">{{ $item->unitKerja->nama }}</span>
                                @else
                                    <span style="color:#d1d5db;">—</span>
                                @endif
                            </td>
                            <td>
                                <span style="display:inline-flex; padding:4px 10px; background:#eff6ff; color:#1d4ed8; font-size:11px; font-weight:600; border-radius:8px;">
                                    {{ $item->jenisSurat->kode ?? '-' }}
                                </span>
                            </td>
                            <td>
                                @if ($item->arah === 'masuk')
                                    <span style="padding:4px 10px; background:#ecfdf5; color:#059669; font-size:11px; font-weight:600; border-radius:20px;">↓ Masuk</span>
                                @else
                                    <span style="padding:4px 10px; background:#fffbeb; color:#d97706; font-size:11px; font-weight:600; border-radius:20px;">↑ Keluar</span>
                                @endif
                            </td>
                            <td style="color:#64748b; font-size:13px;">{{ ($item->tanggal_surat ?? $item->tanggal_diterima)?->format('d M Y') }}</td>
                            <td style="text-align:center;">
                                <a href="{{ route('arsip.show', $item) }}" style="display:inline-flex; align-items:center; gap:6px; padding:6px 12px; background:white; color:#3b82f6; border:1px solid #bfdbfe; border-radius:8px; font-size:12px; font-weight:600; text-decoration:none;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center; padding:48px 24px; color:#94a3b8;">
                                <p style="margin:0; font-size:14px; font-weight:500;">Belum ada surat di arsip</p>
                                @if(request()->hasAny(['bidang_id','seksi_id','tahun','q','arah']))
                                    <p style="margin:8px 0 0; font-size:12px;">Coba hapus filter atau gunakan filter yang berbeda.</p>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($arsip->hasPages())
                <div style="padding:14px 24px; border-top:1px solid rgba(0,0,0,0.05);">
                    {{ $arsip->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

