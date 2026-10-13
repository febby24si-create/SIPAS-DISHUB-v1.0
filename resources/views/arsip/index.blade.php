<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; gap:12px;">
            <div style="width:40px; height:40px; background:#fdf4ff; border:1px solid #e9d5ff; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#7c3aed; flex-shrink:0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 8v13H3V8"/><path d="M1 3h22v5H1z"/><path d="M10 12h4"/></svg>
            </div>
            <div>
                <div style="font-size:17px; font-weight:600; color:#1e293b; line-height:1.2;">Arsip Digital</div>
                <div style="margin-top:2px; font-size:12.5px; color:#64748b;">Arsip surat masuk dan surat keluar yang telah final</div>
            </div>
        </div>
    </x-slot>

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
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari perihal surat..."
                       aria-label="Cari arsip berdasarkan perihal surat"
                       style="flex:1; min-width:160px; border:none; outline:none; font-size:14px; color:#334155; background:transparent;">
                <label for="arsip-arah" style="font-size:11px; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:.5px;">Arah</label>
                <select id="arsip-arah" name="arah" aria-label="Filter arah surat" style="border:1px solid #e2e8f0; border-radius:8px; padding:6px 10px; font-size:13px; color:#334155;">
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
                    <button type="submit" style="display:inline-flex; align-items:center; gap:7px; padding:8px 16px; background:#1d4ed8; color:white; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; white-space:nowrap;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
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
                <a href="{{ route('arsip.index') }}" style="display:inline-flex; align-items:center; gap:4px; font-size:11px; color:#ef4444; font-weight:600; text-decoration:none; margin-left:4px;"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Reset</a>
            </div>
            @endif
        </form>

        @php
            // Kelompokkan baris berdasarkan tanggal input (created_at) dengan zona waktu Asia/Jakarta.
            $arsipGroups = \App\Helpers\DateGroupHelper::groupByInputDate($arsip);
        @endphp

        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:60px;">No.</th>
                        <th>
                            <span style="display:inline-flex; align-items:center; gap:6px;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="4" y1="9" x2="20" y2="9"/><line x1="4" y1="15" x2="20" y2="15"/><line x1="10" y1="3" x2="8" y2="21"/><line x1="16" y1="3" x2="14" y2="21"/></svg>
                                Nomor Surat
                            </span>
                        </th>
                        <th>
                            <span style="display:inline-flex; align-items:center; gap:6px;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                Perihal
                            </span>
                        </th>
                        <th>
                            <span style="display:inline-flex; align-items:center; gap:6px;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                                Unit Kerja / Seksi
                            </span>
                        </th>
                        <th>
                            <span style="display:inline-flex; align-items:center; gap:6px;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.83z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                                Jenis
                            </span>
                        </th>
                        <th>
                            <span style="display:inline-flex; align-items:center; gap:6px;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 3 4 7l4 4"/><path d="M4 7h16"/><path d="m16 21 4-4-4-4"/><path d="M20 17H4"/></svg>
                                Arah
                            </span>
                        </th>
                        <th>
                            <span style="display:inline-flex; align-items:center; gap:6px;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                Tanggal
                            </span>
                        </th>
                        <th style="width:100px; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="arsip-table-body">
                    @forelse ($arsipGroups as $group)
                        <x-date-group-row :group="$group" :colspan="8" :partial="$arsip->hasPages()" noun="arsip" />
                        @foreach ($group['items'] as $row)
                            @php
                                $item = $row['surat'];

                                // Aksen tipis di tepi baris agar arah terbaca sekilas,
                                // tanpa mewarnai seluruh baris.
                                $arahRow = strtolower(trim((string) $item->arah));
                                $rowAccent = $arahRow === 'masuk' ? '#10b981' : ($arahRow === 'keluar' ? '#3b82f6' : '#94a3b8');
                            @endphp
                            <tr>
                                <td style="border-left:3px solid {{ $rowAccent }}; color:#94a3b8; font-size:13px;">{{ $row['no'] }}.</td>
                                <td style="font-family:monospace; font-size:12px; color:#334155;">{{ $item->nomor_surat ?? '-' }}</td>
                                <td style="font-weight:500; color:#1e293b;">{{ $item->perihal }}</td>
                                <td style="font-size:12px; color:#475569;">
                                    @if($item->unitKerja)
                                        @if($item->unitKerja->parent)
                                            <span style="display:flex; align-items:center; gap:5px; color:#94a3b8; font-size:11px;">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                                                {{ $item->unitKerja->parent->nama }}
                                            </span>
                                        @endif
                                        <span style="display:flex; align-items:center; gap:5px; font-weight:500;">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="6" y1="3" x2="6" y2="15"/><circle cx="18" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M18 9a9 9 0 0 1-9 9"/></svg>
                                            {{ $item->unitKerja->nama }}
                                        </span>
                                    @else
                                        <span style="color:#d1d5db;">—</span>
                                    @endif
                                </td>
                                <td>
                                    {{-- Badge jenis surat memakai warna arah agar satu baris terlihat seragam. --}}
                                    <x-arah-badge :arah="$item->arah" icon="tag" :label="$item->jenisSurat->kode ?? '-'" />
                                </td>
                                <td>
                                    <x-arah-badge :arah="$item->arah" />
                                </td>
                                <td style="color:#64748b; font-size:13px;">
                                    <span style="display:inline-flex; align-items:center; gap:6px;">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                        {{ ($item->tanggal_surat ?? $item->tanggal_diterima)?->format('d M Y') }}
                                    </span>
                                </td>
                                <td style="text-align:center;">
                                    <x-action-group style="justify-content:center;">
                                        <x-action-btn type="view" url="{{ route('arsip.show', $item) }}" />
                                    </x-action-group>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center; padding:48px 24px; color:#94a3b8;">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="display:block; margin:0 auto 12px;" aria-hidden="true"><rect x="2" y="3" width="20" height="5" rx="1"/><path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"/><path d="M10 12h4"/></svg>
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

