<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; gap:12px; min-width:0;">
            <div style="width:40px; height:40px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; display:flex; align-items:center; justify-content:center; color:#1d4ed8; flex-shrink:0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 8v13H3V8"/><path d="M1 3h22v5H1z"/><path d="M10 12h4"/></svg>
            </div>
            <div style="min-width:0;">
                <div style="font-size:17px; font-weight:600; color:#1e293b; line-height:1.25;">Arsip Digital</div>
                <div style="margin-top:3px; font-size:12.5px; color:#64748b; line-height:1.35;">Arsip surat masuk dan surat keluar yang telah final</div>
            </div>
        </div>
    </x-slot>

    <div class="arsip-index" style="display:flex; flex-direction:column; gap:16px;">

        {{-- ═══ Pencarian & filter: satu panel ringkas ═══ --}}
        <form method="GET"
              x-data="{
                  bidang: '{{ request('bidang_id') }}',
                  seksi: '{{ request('seksi_id') }}',
                  bidangs: {{ $bidangs->toJson() }}
              }"
              style="background:white; border:1px solid rgba(0,0,0,0.07); border-radius:14px; padding:14px 16px; display:flex; flex-direction:column; gap:12px;">

            <div class="arsip-filter-row">
                {{-- Pencarian perihal --}}
                <div class="arsip-field arsip-field--search">
                    <label for="arsip-q" style="font-size:11px; font-weight:600; color:#475569; text-transform:uppercase; letter-spacing:.5px;">Perihal Surat</label>
                    <div class="arsip-input">
                        <svg class="arsip-input-ic" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input id="arsip-q" type="text" name="q" value="{{ request('q') }}" placeholder="Cari perihal surat..."
                               aria-label="Cari arsip berdasarkan perihal surat"
                               autocomplete="off"
                               style="width:100%; height:36px; box-sizing:border-box; border:1px solid #e2e8f0; border-radius:8px; background:white; color:#334155; font-size:13.5px; padding:0 12px 0 34px; outline:none;">
                    </div>
                </div>

                {{-- Arah surat --}}
                <div class="arsip-field arsip-field--arah">
                    <label for="arsip-arah" style="font-size:11px; font-weight:600; color:#475569; text-transform:uppercase; letter-spacing:.5px;">Arah Surat</label>
                    <select id="arsip-arah" name="arah" aria-label="Filter arah surat"
                            style="height:36px; box-sizing:border-box; border:1px solid #e2e8f0; border-radius:8px; background:white; color:#334155; font-size:13.5px; padding:0 10px; outline:none; cursor:pointer;">
                        <option value="">Semua Arah</option>
                        <option value="masuk" @selected(request('arah') === 'masuk')>Masuk</option>
                        <option value="keluar" @selected(request('arah') === 'keluar')>Keluar</option>
                    </select>
                </div>

                {{-- Bidang --}}
                <div class="arsip-field arsip-field--bidang">
                    <label for="arsip-bidang" style="font-size:11px; font-weight:600; color:#475569; text-transform:uppercase; letter-spacing:.5px;">Bidang</label>
                    <select id="arsip-bidang" name="bidang_id" x-model="bidang" @change="seksi = ''"
                            style="height:36px; box-sizing:border-box; border:1px solid #e2e8f0; border-radius:8px; background:white; color:#334155; font-size:13.5px; padding:0 10px; outline:none; cursor:pointer;">
                        <option value="">Semua Bidang</option>
                        <template x-for="b in bidangs" :key="b.id">
                            <option :value="b.id" x-text="b.nama"></option>
                        </template>
                    </select>
                </div>

                {{-- Seksi (bergantung Bidang) --}}
                <div class="arsip-field arsip-field--seksi">
                    <label for="arsip-seksi" style="font-size:11px; font-weight:600; color:#475569; text-transform:uppercase; letter-spacing:.5px;">Seksi</label>
                    <select id="arsip-seksi" name="seksi_id" x-model="seksi"
                            style="height:36px; box-sizing:border-box; border:1px solid #e2e8f0; border-radius:8px; background:white; color:#334155; font-size:13.5px; padding:0 10px; outline:none; cursor:pointer;">
                        <option value="">Semua Seksi</option>
                        <template x-if="bidang">
                            <template x-for="s in bidangs.find(b => b.id == bidang)?.children || []" :key="s.id">
                                <option :value="s.id" x-text="s.nama"></option>
                            </template>
                        </template>
                    </select>
                </div>

                {{-- Tahun --}}
                @if($tahunList->isNotEmpty())
                <div class="arsip-field arsip-field--tahun">
                    <label for="arsip-tahun" style="font-size:11px; font-weight:600; color:#475569; text-transform:uppercase; letter-spacing:.5px;">Tahun</label>
                    <select id="arsip-tahun" name="tahun"
                            style="height:36px; box-sizing:border-box; border:1px solid #e2e8f0; border-radius:8px; background:white; color:#334155; font-size:13.5px; padding:0 10px; outline:none; cursor:pointer;">
                        <option value="">Semua Tahun</option>
                        @foreach($tahunList as $tahun)
                            <option value="{{ $tahun }}" @selected(request('tahun') == $tahun)>{{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Aksi filter --}}
                <div class="arsip-actions">
                    <button type="submit"
                            style="display:inline-flex; align-items:center; justify-content:center; gap:7px; height:36px; padding:0 18px; background:#1d4ed8; color:white; border:none; border-radius:8px; font-size:13.5px; font-weight:600; cursor:pointer; white-space:nowrap;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                        Cari / Filter
                    </button>

                    @if(request()->hasAny(['bidang_id','seksi_id','tahun','q','arah']))
                        <a href="{{ route('arsip.index') }}"
                           style="display:inline-flex; align-items:center; justify-content:center; gap:6px; height:36px; padding:0 14px; background:white; border:1px solid #e2e8f0; border-radius:8px; font-size:13.5px; font-weight:600; color:#475569; text-decoration:none; white-space:nowrap;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            Reset
                        </a>
                    @endif
                </div>
            </div>

            {{-- Indikator filter aktif --}}
            @if(request()->hasAny(['bidang_id','seksi_id','tahun','q','arah']))
            <div class="arsip-filter-chips" style="display:flex; align-items:center; gap:8px; flex-wrap:wrap; padding-top:2px; border-top:1px solid #f1f5f9;">
                <span style="font-size:12px; color:#475569;">Filter aktif:</span>
                @if(request('q'))<span style="padding:3px 9px; background:#eff6ff; color:#1d4ed8; border-radius:6px; font-size:11px; font-weight:600;">Pencarian: "{{ request('q') }}"</span>@endif
                @if(request('bidang_id'))
                    @php $activeBidang = $bidangs->firstWhere('id', request('bidang_id')); @endphp
                    @if($activeBidang)<span style="padding:3px 9px; background:#f0fdf4; color:#059669; border-radius:6px; font-size:11px; font-weight:600;">Bidang: {{ $activeBidang->nama }}</span>@endif
                @endif
                @if(request('seksi_id'))
                    @php $activeSeksi = $bidangs->flatMap->children->firstWhere('id', request('seksi_id')); @endphp
                    @if($activeSeksi)<span style="padding:3px 9px; background:#fefce8; color:#ca8a04; border-radius:6px; font-size:11px; font-weight:600;">Seksi: {{ $activeSeksi->nama }}</span>@endif
                @endif
                @if(request('tahun'))<span style="padding:3px 9px; background:#fdf4ff; color:#9333ea; border-radius:6px; font-size:11px; font-weight:600;">Tahun: {{ request('tahun') }}</span>@endif
            </div>
            @endif
        </form>

        @php
            // Kelompokkan baris berdasarkan tanggal input (created_at) dengan zona waktu Asia/Jakarta.
            $arsipGroups = \App\Helpers\DateGroupHelper::groupByInputDate($arsip);
        @endphp

        {{-- ═══ Tabel arsip (fokus halaman) ═══ --}}
        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            {{-- Wrapper memakai utilitas .table-wrapper yang sudah ada (scroll horizontal
                 pada layar sempit, bukan memaksa seluruh kolom menjadi sempit). --}}
            <div class="table-wrapper">
                <table class="data-table arsip-table" style="min-width:960px;">
                    <thead>
                        <tr>
                            <th style="width:56px;">No.</th>
                            <th style="width:156px;">
                                <span style="display:inline-flex; align-items:center; gap:6px;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="4" y1="9" x2="20" y2="9"/><line x1="4" y1="15" x2="20" y2="15"/><line x1="10" y1="3" x2="8" y2="21"/><line x1="16" y1="3" x2="14" y2="21"/></svg>
                                    Nomor Surat
                                </span>
                            </th>
                            <th style="width:24%;">
                                <span style="display:inline-flex; align-items:center; gap:6px;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                    Perihal
                                </span>
                            </th>
                            <th style="width:19%;">
                                <span style="display:inline-flex; align-items:center; gap:6px;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                                    Unit Kerja / Seksi
                                </span>
                            </th>
                            <th style="width:92px;">
                                <span style="display:inline-flex; align-items:center; gap:6px;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.83z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                                    Jenis
                                </span>
                            </th>
                            <th style="width:96px;">
                                <span style="display:inline-flex; align-items:center; gap:6px;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 3 4 7l4 4"/><path d="M4 7h16"/><path d="m16 21 4-4-4-4"/><path d="M20 17H4"/></svg>
                                    Arah
                                </span>
                            </th>
                            <th style="width:116px;">
                                <span style="display:inline-flex; align-items:center; gap:6px;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    Tanggal
                                </span>
                            </th>
                            <th style="width:72px; text-align:center;">Aksi</th>
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

                                    // Tanggal mengikuti nilai tersimpan; kosong → placeholder, tidak dikarang.
                                    $tanggalArsip = ($item->tanggal_surat ?? $item->tanggal_diterima)?->format('d M Y');
                                @endphp
                                <tr>
                                    <td style="border-left:3px solid {{ $rowAccent }}; color:#94a3b8; font-size:13px;">{{ $row['no'] }}.</td>
                                    {{-- Nomor surat ditampilkan persis seperti tersimpan (nol di depan,
                                         garis miring, dll. tidak diformat ulang). --}}
                                    <td @if($item->nomor_surat) title="{{ $item->nomor_surat }}" @endif style="font-family:monospace; font-size:12.5px; color:#334155; overflow-wrap:anywhere;">{{ $item->nomor_surat ?? '-' }}</td>
                                    <td style="font-weight:500; color:#1e293b; line-height:1.4; overflow-wrap:anywhere;">{{ $item->perihal }}</td>
                                    <td style="font-size:12px;">
                                        @if($item->unitKerja)
                                            @if($item->unitKerja->parent)
                                                <span style="display:flex; align-items:center; gap:5px; color:#94a3b8; font-size:11.5px;">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                                                    {{ $item->unitKerja->parent->nama }}
                                                </span>
                                            @endif
                                            <span style="display:flex; align-items:center; gap:5px; color:#334155; font-weight:500; line-height:1.35;">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="flex-shrink:0;"><line x1="6" y1="3" x2="6" y2="15"/><circle cx="18" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M18 9a9 9 0 0 1-9 9"/></svg>
                                                <span style="overflow-wrap:anywhere;">{{ $item->unitKerja->nama }}</span>
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
                                    <td style="color:#475569; font-size:13px; white-space:nowrap;">{{ $tanggalArsip ?? '-' }}</td>
                                    <td style="text-align:center;">
                                        <x-action-group>
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
            </div>

            @if ($arsip->hasPages())
                <div style="padding:14px 24px; border-top:1px solid rgba(0,0,0,0.05);">
                    {{ $arsip->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         GAYA HALAMAN (ringkas & terbatas pada halaman ini)
         Warna memakai literal yang sama dengan override dark mode
         di app.css, sehingga mode terang/gelap tetap konsisten.
    ══════════════════════════════════════════════════════════ --}}
    <style>
        /* ── Panel filter: satu baris efisien, wrap otomatis di layar sempit ── */
        .arsip-filter-row {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            gap: 10px;
        }

        .arsip-field {
            display: flex;
            flex-direction: column;
            gap: 5px;
            min-width: 0;
        }

        .arsip-field--search { flex: 2 1 240px; }
        .arsip-field--arah   { flex: 1 1 130px; }
        .arsip-field--bidang { flex: 1 1 170px; }
        .arsip-field--seksi  { flex: 1 1 170px; }
        .arsip-field--tahun  { flex: 0 1 118px; }

        .arsip-input { position: relative; }

        .arsip-input-ic {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .arsip-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
        }

        /* State hover / focus yang jelas untuk seluruh kontrol filter */
        .arsip-filter-row input:hover,
        .arsip-filter-row select:hover {
            border-color: #94a3b8 !important;
        }

        .arsip-filter-row input:focus,
        .arsip-filter-row select:focus,
        .arsip-input:focus-within input {
            border-color: #1d4ed8 !important;
            box-shadow: 0 0 0 3px rgba(29, 78, 216, .12);
        }

        .arsip-filter-row button[type="submit"]:hover { background: #1e40af !important; }

        .arsip-filter-chips { border-top-color: #f1f5f9; }

        /* ── Tabel: keterbacaan & kepadatan khas tabel administrasi ── */
        .arsip-table th,
        .arsip-table td { padding-left: 12px; padding-right: 12px; }

        .arsip-index a:focus-visible,
        .arsip-index button:focus-visible,
        .arsip-index input:focus-visible,
        .arsip-index select:focus-visible {
            outline: 2px solid #2563eb;
            outline-offset: 2px;
        }

        /* ── Responsif: filter disusun vertikal, tombol selebar layar ── */
        @media (max-width: 640px) {
            .arsip-field--search { flex: 1 1 100%; }
            .arsip-field--arah,
            .arsip-field--tahun { flex: 1 1 calc(50% - 5px); }
            .arsip-field--bidang,
            .arsip-field--seksi { flex: 1 1 calc(50% - 5px); }

            .arsip-actions {
                margin-left: 0;
                width: 100%;
            }

            .arsip-actions > a,
            .arsip-actions > button { flex: 1; }
        }

        @media (max-width: 400px) {
            .arsip-field--arah,
            .arsip-field--tahun,
            .arsip-field--bidang,
            .arsip-field--seksi { flex: 1 1 100%; }
        }

        /* ── Dark mode: panel & chip mengikuti override app.css,
             hanya warna eksplisit yang perlu disesuaikan di sini ── */
        [data-theme="dark"] .arsip-filter-chips { border-top-color: #293548 !important; }

        [data-theme="dark"] .arsip-filter-row input:hover,
        [data-theme="dark"] .arsip-filter-row select:hover {
            border-color: #475569 !important;
        }

        /* Tombol Reset memakai color:#475569; app.css tidak punya override untuk
           a[style*="color:#475569"], jadi kontras mode gelap diatur di sini. */
        [data-theme="dark"] .arsip-actions a {
            color: #94a3b8 !important;
        }
    </style>
</x-app-layout>
