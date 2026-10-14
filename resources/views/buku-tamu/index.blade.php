<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:14px; width:100%;">
            {{-- Icon + Title (Mirrors Surat Masuk header pattern) --}}
            <div style="display:flex; align-items:center; gap:12px;">
                <div style="width:40px; height:40px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#1d4ed8; flex-shrink:0;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                </div>
                <div>
                    <div style="font-size:17px; font-weight:600; color:var(--text-primary,#1e293b); line-height:1.2;">Buku Tamu</div>
                    <div style="margin-top:2px; font-size:12.5px; color:var(--text-muted,#64748b);">Pantau dan kelola data kunjungan tamu dinas</div>
                </div>
            </div>

            {{-- Actions & Counter --}}
            <div style="display:flex; align-items:center; gap:14px;">
                <a href="{{ route('buku-tamu.qr') }}"
                   style="display:inline-flex; align-items:center; gap:7px; height:36px; padding:0 14px; background:var(--card-bg,white); color:#0369a1; font-size:13px; font-weight:600; border-radius:8px; text-decoration:none; border:1px solid #bae6fd; transition:all 0.15s; box-shadow:0 1px 2px rgba(0,0,0,0.03);"
                   onmouseover="this.style.background='#e0f2fe'" onmouseout="this.style.background='var(--card-bg,white)'">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    QR Buku Tamu
                </a>

                <div style="text-align:right; border-left:1px solid var(--border,#e2e8f0); padding-left:14px;">
                    <div style="font-size:11px; font-weight:600; color:var(--text-muted,#94a3b8); text-transform:uppercase; letter-spacing:0.5px;">Total Kunjungan</div>
                    <div style="font-size:18px; font-weight:700; color:var(--text-primary,#1e293b); line-height:1.2;">
                        {{ number_format($totalSemua, 0, ',', '.') }} <span style="font-size:12.5px; font-weight:500; color:var(--text-muted,#64748b);">kunjungan</span>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- ── Modal & Layout Styles ── --}}
    <style>
        .sipas-modal-overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 9998;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            overflow-y: auto;
        }

        .sipas-modal-dialog {
            position: relative;
            width: 100%;
            background-color: var(--card-bg, #ffffff);
            color: var(--text-primary, #1e293b);
            border: 1px solid var(--border, #e2e8f0);
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            max-height: calc(100vh - 48px);
            overflow: hidden;
            z-index: 9999;
        }

        .sipas-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid var(--border, #e2e8f0);
            background-color: var(--card-bg, #ffffff);
            flex-shrink: 0;
        }

        .sipas-modal-close {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: 1px solid transparent;
            border-radius: 6px;
            color: var(--text-muted, #64748b);
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .sipas-modal-close:hover {
            background: var(--page-bg, #f1f5f9);
            color: var(--text-primary, #1e293b);
            border-color: var(--border, #e2e8f0);
        }

        .sipas-modal-body {
            padding: 20px;
            overflow-y: auto;
            flex: 1;
        }

        .sipas-modal-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            padding: 14px 20px;
            border-top: 1px solid var(--border, #e2e8f0);
            background-color: var(--card-bg, #ffffff);
            flex-shrink: 0;
        }

        .spt-card-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 12px 16px;
        }

        [data-theme="dark"] .spt-card-box {
            background: rgba(30, 58, 138, 0.22);
            border-color: rgba(59, 130, 246, 0.35);
        }

        [data-theme="dark"] .spt-file-name {
            color: #93c5fd !important;
        }

        [data-theme="dark"] .spt-file-sub {
            color: #60a5fa !important;
        }

        .spt-btn-view {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 13px;
            background: var(--card-bg, #ffffff);
            color: #1d4ed8;
            border: 1px solid #93c5fd;
            font-size: 12px;
            font-weight: 600;
            border-radius: 7px;
            text-decoration: none;
            transition: all 0.15s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
            cursor: pointer;
        }

        [data-theme="dark"] .spt-btn-view {
            background: #1e293b;
            color: #60a5fa;
            border-color: #3b82f6;
        }

        .spt-btn-view:hover {
            background: #dbeafe;
            border-color: #2563eb;
            color: #1e40af;
        }

        [data-theme="dark"] .spt-btn-view:hover {
            background: rgba(59, 130, 246, 0.25);
            color: #93c5fd;
        }

        .spt-btn-download {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            background: #1d4ed8;
            color: #ffffff;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid transparent;
            border-radius: 7px;
            text-decoration: none;
            transition: background 0.15s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            cursor: pointer;
        }

        .spt-btn-download:hover {
            background: #1e40af;
            color: #ffffff;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

    @php
        // Build bukuTamuMap for in-page modal management (Mirrors Surat Masuk pattern)
        $bukuTamuMap = [];
        foreach ($bukuTamu as $item) {
            $bukuTamuMap[$item->id] = [
                'id'            => $item->id,
                'nama'          => $item->nama,
                'no_hp'         => $item->no_hp,
                'instansi'      => $item->instansi ?: '-',
                'keperluan'     => $item->keperluan,
                'pegawai'       => $item->pegawai?->nama ?: '-',
                'unit_kerja'    => $item->unitKerja?->nama ?: '-',
                'tanggal'       => \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d F Y'),
                'jam'           => substr($item->jam, 0, 5) . ' WIB',
                'spt_file'      => $item->spt_file,
                'spt_url'       => $item->spt_file ? asset('storage/' . $item->spt_file) : null,
                'spt_download'  => $item->spt_file ? route('buku-tamu.download', $item->id) : null,
                'spt_name'      => $item->spt_file ? basename($item->spt_file) : null,
                'destroy_url'   => route('buku-tamu.destroy', $item->id),
            ];
        }

        // Group rows by visit date using DateGroupHelper
        $btGroups = \App\Helpers\DateGroupHelper::groupByDate($bukuTamu, 'tanggal');
    @endphp

    {{-- Safe JSON Dataset (Immune to HTML attribute escaping issues) --}}
    <script id="buku-tamu-dataset" type="application/json">
        {!! json_encode($bukuTamuMap, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}
    </script>

    {{-- Alpine Component Handler Definition --}}
    <script>
    function bukuTamuHandler() {
        let initialMap = {};
        const datasetEl = document.getElementById('buku-tamu-dataset');
        if (datasetEl) {
            try {
                initialMap = JSON.parse(datasetEl.textContent || '{}');
            } catch (e) {
                console.error('Gagal membaca dataset buku tamu:', e);
            }
        }

        return {
            detailModalOpen: false,
            deleteModalOpen: false,
            activeItem: {},
            deleteTarget: {},
            bukuTamuMap: initialMap,

            init() {
                window.__bukuTamuHandler = this;
                this.$watch('detailModalOpen', () => this.updateBodyLock());
                this.$watch('deleteModalOpen', () => this.updateBodyLock());
            },

            updateBodyLock() {
                document.body.style.overflow = (this.detailModalOpen || this.deleteModalOpen) ? 'hidden' : '';
            },

            openDetail(id) {
                const item = this.bukuTamuMap[id];
                if (!item) {
                    console.warn('Data buku tamu #' + id + ' tidak ditemukan.');
                    return;
                }
                this.activeItem = { ...item };
                this.detailModalOpen = true;
            },

            closeDetail() {
                this.detailModalOpen = false;
            },

            confirmDelete(id) {
                const item = this.bukuTamuMap[id];
                if (!item) {
                    console.warn('Data buku tamu #' + id + ' tidak ditemukan.');
                    return;
                }
                this.deleteTarget = { ...item };
                const form = document.getElementById('deleteBukuTamuForm');
                if (form && item.destroy_url) {
                    form.action = item.destroy_url;
                }
                this.deleteModalOpen = true;
            },

            closeDelete() {
                this.deleteModalOpen = false;
                this.deleteTarget = {};
            }
        };
    }
    window.bukuTamuHandler = bukuTamuHandler;
    </script>

    <div x-data="bukuTamuHandler()" style="display:flex; flex-direction:column; gap:20px;">

        {{-- ── Flash messages ── --}}
        @if (session('status'))
            <div style="display:flex; align-items:center; gap:10px; padding:12px 16px; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:10px; color:#065f46; font-size:13.5px; font-weight:500;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div style="display:flex; align-items:center; gap:10px; padding:12px 16px; background:#fef2f2; border:1px solid #fecaca; border-radius:10px; color:#991b1b; font-size:13.5px; font-weight:500;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- ── Filter Toolbar ── --}}
        <div style="background:var(--card-bg,white); border:1px solid var(--border,rgba(0,0,0,0.06)); border-radius:12px; padding:14px 18px;">
            <form id="filterBukuTamuForm" method="GET" action="{{ route('buku-tamu.index') }}" style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <label for="filter_start_date" style="font-size:12.5px; font-weight:600; color:var(--text-primary,#475569); white-space:nowrap;">Tanggal Mulai</label>
                    <input type="date"
                           id="filter_start_date"
                           name="start_date"
                           value="{{ request('start_date') }}"
                           style="border:1px solid var(--border,#cbd5e1); border-radius:8px; padding:6px 10px; font-size:13px; color:var(--text-primary,#334155); background:var(--card-bg,white); outline:none; height:34px; box-sizing:border-box;">
                </div>

                <div style="display:flex; align-items:center; gap:8px;">
                    <label for="filter_end_date" style="font-size:12.5px; font-weight:600; color:var(--text-primary,#475569); white-space:nowrap;">Tanggal Selesai</label>
                    <input type="date"
                           id="filter_end_date"
                           name="end_date"
                           value="{{ request('end_date') }}"
                           style="border:1px solid var(--border,#cbd5e1); border-radius:8px; padding:6px 10px; font-size:13px; color:var(--text-primary,#334155); background:var(--card-bg,white); outline:none; height:34px; box-sizing:border-box;">
                </div>

                <div style="display:flex; align-items:center; gap:8px;">
                    <button type="submit" style="height:34px; padding:0 16px; background:#1d4ed8; color:white; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:6px; transition:background 0.15s;"
                            onmouseover="this.style.background='#1e40af'" onmouseout="this.style.background='#1d4ed8'">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Tampilkan
                    </button>

                    @if($adaFilter)
                        <a href="{{ route('buku-tamu.index') }}" style="height:34px; display:inline-flex; align-items:center; padding:0 14px; background:var(--page-bg,#f8fafc); color:var(--text-muted,#475569); border:1px solid var(--border,#e2e8f0); border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; transition:all 0.15s;"
                           onmouseover="this.style.borderColor='#cbd5e1'" onmouseout="this.style.borderColor='var(--border,#e2e8f0)'">
                            Reset Filter
                        </a>
                    @endif
                </div>

                @if($adaFilter)
                    <div style="font-size:12.5px; font-weight:500; color:#1d4ed8; margin-left:auto; display:flex; align-items:center; gap:6px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        Menampilkan hasil filter: <strong>{{ $totalFilter }}</strong> data
                    </div>
                @endif
            </form>
        </div>

        {{-- ── 2-Column Section: Grafik & Ringkasan Metrik ── --}}
        <div style="display:grid; grid-template-columns:2fr 1fr; gap:16px; align-items:stretch;">
            {{-- Kolom Kiri: Grafik Tren Kunjungan --}}
            <div style="background:var(--card-bg,white); border:1px solid var(--border,rgba(0,0,0,0.06)); border-radius:12px; padding:16px 20px; display:flex; flex-direction:column;">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <div style="width:26px; height:26px; background:#eff6ff; border-radius:6px; display:flex; align-items:center; justify-content:center; color:#1d4ed8;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        </div>
                        <span style="font-size:13.5px; font-weight:600; color:var(--text-primary,#1e293b);">Tren Kunjungan</span>
                    </div>
                    <span style="font-size:11.5px; color:var(--text-muted,#94a3b8);">Frekuensi kunjungan per tanggal</span>
                </div>

                @if(count($chartLabels) > 0)
                    <div style="position:relative; height:180px; flex:1;">
                        <canvas id="chartKunjungan"></canvas>
                    </div>
                @else
                    <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; height:180px; color:var(--text-muted,#94a3b8); font-size:13px; font-weight:500; gap:8px;">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.5;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Belum ada data kunjungan pada periode ini.
                    </div>
                @endif
            </div>

            {{-- Kolom Kanan: Metrik Ringkasan Aktual (Data Nyata) --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                <!-- Card 1: Hari Ini -->
                <div style="background:var(--card-bg,white); border:1px solid var(--border,rgba(0,0,0,0.06)); border-radius:12px; padding:14px 16px; display:flex; flex-direction:column; justify-content:space-between;">
                    <div style="display:flex; align-items:center; justify-content:space-between;">
                        <span style="font-size:11.5px; font-weight:600; color:var(--text-muted,#64748b); text-transform:uppercase; letter-spacing:0.4px;">Hari Ini</span>
                        <div style="width:26px; height:26px; background:#eff6ff; color:#2563eb; border-radius:6px; display:flex; align-items:center; justify-content:center;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </div>
                    </div>
                    <div style="margin-top:10px;">
                        <div style="font-size:22px; font-weight:700; color:var(--text-primary,#0f172a); line-height:1;">{{ $totalHariIni }}</div>
                        <div style="font-size:11px; color:var(--text-muted,#94a3b8); margin-top:4px;">Kunjungan tercatat</div>
                    </div>
                </div>

                <!-- Card 2: Bulan Ini -->
                <div style="background:var(--card-bg,white); border:1px solid var(--border,rgba(0,0,0,0.06)); border-radius:12px; padding:14px 16px; display:flex; flex-direction:column; justify-content:space-between;">
                    <div style="display:flex; align-items:center; justify-content:space-between;">
                        <span style="font-size:11.5px; font-weight:600; color:var(--text-muted,#64748b); text-transform:uppercase; letter-spacing:0.4px;">Bulan Ini</span>
                        <div style="width:26px; height:26px; background:#f0fdf4; color:#16a34a; border-radius:6px; display:flex; align-items:center; justify-content:center;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 20V10"/><path d="M12 20V4"/><path d="M6 20v-6"/></svg>
                        </div>
                    </div>
                    <div style="margin-top:10px;">
                        <div style="font-size:22px; font-weight:700; color:var(--text-primary,#0f172a); line-height:1;">{{ $totalBulanIni }}</div>
                        <div style="font-size:11px; color:var(--text-muted,#94a3b8); margin-top:4px;">{{ now()->translatedFormat('F Y') }}</div>
                    </div>
                </div>

                <!-- Card 3: Berkas SPT -->
                <div style="background:var(--card-bg,white); border:1px solid var(--border,rgba(0,0,0,0.06)); border-radius:12px; padding:14px 16px; display:flex; flex-direction:column; justify-content:space-between;">
                    <div style="display:flex; align-items:center; justify-content:space-between;">
                        <span style="font-size:11.5px; font-weight:600; color:var(--text-muted,#64748b); text-transform:uppercase; letter-spacing:0.4px;">Berkas SPT</span>
                        <div style="width:26px; height:26px; background:#f5f3ff; color:#8b5cf6; border-radius:6px; display:flex; align-items:center; justify-content:center;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        </div>
                    </div>
                    <div style="margin-top:10px;">
                        <div style="font-size:22px; font-weight:700; color:var(--text-primary,#0f172a); line-height:1;">{{ $totalDenganSpt }}</div>
                        <div style="font-size:11px; color:var(--text-muted,#94a3b8); margin-top:4px;">Kunjungan dengan SPT</div>
                    </div>
                </div>

                <!-- Card 4: Rata-rata / Hari -->
                <div style="background:var(--card-bg,white); border:1px solid var(--border,rgba(0,0,0,0.06)); border-radius:12px; padding:14px 16px; display:flex; flex-direction:column; justify-content:space-between;">
                    <div style="display:flex; align-items:center; justify-content:space-between;">
                        <span style="font-size:11.5px; font-weight:600; color:var(--text-muted,#64748b); text-transform:uppercase; letter-spacing:0.4px;">Rata-rata</span>
                        <div style="width:26px; height:26px; background:#fff7ed; color:#ea580c; border-radius:6px; display:flex; align-items:center; justify-content:center;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                        </div>
                    </div>
                    <div style="margin-top:10px;">
                        <div style="font-size:22px; font-weight:700; color:var(--text-primary,#0f172a); line-height:1;">{{ $avgPerHari }}</div>
                        <div style="font-size:11px; color:var(--text-muted,#94a3b8); margin-top:4px;">Tamu / hari aktif</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Tabel Daftar Kunjungan (9 Kolom + Date Grouping) ── --}}
        <div style="background:var(--card-bg,white); border:1px solid var(--border,rgba(0,0,0,0.06)); border-radius:12px; overflow:hidden;">
            <div style="padding:14px 18px; border-bottom:1px solid var(--border,rgba(0,0,0,0.05)); display:flex; align-items:center; justify-content:space-between; gap:10px; flex-wrap:wrap;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <span style="font-size:14px; font-weight:700; color:var(--text-primary,#1e293b);">Daftar Riwayat Kunjungan</span>
                    @if($bukuTamu->total() > 0)
                        <span style="background:var(--page-bg,#f1f5f9); color:var(--text-muted,#475569); padding:2px 8px; border-radius:20px; font-size:11.5px; font-weight:600;">
                            {{ $bukuTamu->total() }} data
                        </span>
                    @endif
                </div>
                <div style="font-size:12px; color:var(--text-muted,#94a3b8);">
                    Klik tombol aksi <strong style="color:var(--text-primary,#475569);">Detail</strong> untuk informasi lengkap & dokumen SPT.
                </div>
            </div>

            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:45px;">No.</th>
                            <th style="width:115px;">Tanggal</th>
                            <th style="width:90px;">Jam</th>
                            <th>Nama Tamu</th>
                            <th>Instansi</th>
                            <th>No. HP / WhatsApp</th>
                            <th>Bertemu Dengan</th>
                            <th>Unit / Seksi Tujuan</th>
                            <th style="text-align:right; width:80px; padding-right:18px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($btGroups as $group)
                            {{-- Baris pemisah kelompok tanggal --}}
                            <x-date-group-row :group="$group" :colspan="9" :noun="'kunjungan'" :partial="$bukuTamu->hasPages()" />

                            @foreach ($group['items'] as $row)
                                @php $tamu = $row['item']; @endphp
                                <tr>
                                    {{-- 1. No --}}
                                    <td style="color:var(--text-muted,#94a3b8); font-size:13px;">{{ $row['no'] }}.</td>

                                    {{-- 2. Tanggal Kunjungan --}}
                                    <td style="font-size:13px; color:var(--text-primary,#334155); white-space:nowrap; font-weight:500;">
                                        {{ \Carbon\Carbon::parse($tamu->tanggal)->translatedFormat('d M Y') }}
                                    </td>

                                    {{-- 3. Jam Kunjungan --}}
                                    <td style="font-size:12.5px; color:var(--text-muted,#64748b); font-family:monospace; white-space:nowrap;">
                                        {{ substr($tamu->jam, 0, 5) }} WIB
                                    </td>

                                    {{-- 4. Nama Tamu --}}
                                    <td style="font-size:13.5px; font-weight:600; color:var(--text-primary,#1e293b);">
                                        <a href="#" @click.prevent="openDetail({{ $tamu->id }})"
                                           onclick="window.__bukuTamuHandler?.openDetail({{ $tamu->id }}); return false;"
                                           style="color:var(--text-primary,#1e293b); text-decoration:none; cursor:pointer;"
                                           onmouseover="this.style.color='#1d4ed8'" onmouseout="this.style.color='var(--text-primary,#1e293b)'">
                                            {{ $tamu->nama }}
                                        </a>
                                        @if($tamu->spt_file)
                                            <span title="Terdapat lampiran berkas SPT" style="display:inline-block; margin-left:4px; vertical-align:middle; color:#16a34a;">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                                            </span>
                                        @endif
                                    </td>

                                    {{-- 5. Instansi --}}
                                    <td style="font-size:12.5px; color:var(--text-primary,#475569);">
                                        {!! $tamu->instansi ? e($tamu->instansi) : '<span style="color:var(--text-muted,#cbd5e1);">—</span>' !!}
                                    </td>

                                    {{-- 6. No. HP / WhatsApp --}}
                                    <td style="font-size:12px; color:var(--text-primary,#475569); font-family:monospace; white-space:nowrap;">
                                        {{ $tamu->no_hp }}
                                    </td>

                                    {{-- 7. Bertemu Dengan --}}
                                    <td style="font-size:12.5px; color:var(--text-primary,#475569);">
                                        {!! $tamu->pegawai?->nama ? e($tamu->pegawai->nama) : '<span style="color:var(--text-muted,#cbd5e1);">—</span>' !!}
                                    </td>

                                    {{-- 8. Unit / Seksi Tujuan --}}
                                    <td style="font-size:12.5px; color:var(--text-primary,#475569);">
                                        {!! $tamu->unitKerja?->nama ? e($tamu->unitKerja->nama) : '<span style="color:var(--text-muted,#cbd5e1);">—</span>' !!}
                                    </td>

                                    {{-- 9. Aksi: HANYA DETAIL DAN HAPUS --}}
                                    <td style="text-align:right; padding-right:16px;">
                                        <x-action-group>
                                            {{-- Detail (Eye Icon -> Buka Modal In-Page) --}}
                                            <x-action-btn type="view" url="#"
                                                          @click.prevent="openDetail({{ $tamu->id }})"
                                                          onclick="window.__bukuTamuHandler?.openDetail({{ $tamu->id }}); return false;"
                                                          title="Lihat Detail Kunjungan" />

                                            {{-- Hapus (Trash Icon -> Buka Dialog Konfirmasi) --}}
                                            <x-action-btn type="delete" url="#"
                                                          @click.prevent="confirmDelete({{ $tamu->id }})"
                                                          onclick="window.__bukuTamuHandler?.confirmDelete({{ $tamu->id }}); return false;"
                                                          title="Hapus Data Kunjungan" />
                                        </x-action-group>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="9" style="text-align:center; padding:48px 24px; color:var(--text-muted,#94a3b8);">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 12px; opacity:0.4;"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                                    <p style="margin:0; font-size:14px; font-weight:600; color:var(--text-primary,#475569);">Belum ada kunjungan tamu</p>
                                    <p style="margin:4px 0 0; font-size:13px; color:var(--text-muted,#94a3b8);">
                                        {{ $adaFilter ? 'Tidak ada data kunjungan yang cocok dengan filter tanggal di atas.' : 'Data kunjungan dari form publik akan otomatis muncul di sini.' }}
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($bukuTamu->hasPages())
                <div style="padding:14px 20px; border-top:1px solid var(--border,rgba(0,0,0,0.05));">
                    {{ $bukuTamu->links() }}
                </div>
            @endif
        </div>

        {{-- ══════════════════════════════════════════════════════
           MODAL 1: DETAIL KUNJUNGAN TAMU (IN-PAGE MODAL)
        ══════════════════════════════════════════════════════ --}}
        <div x-cloak
             x-show="detailModalOpen"
             x-transition.opacity.duration.200ms
             class="sipas-modal-overlay"
             @click.self="closeDetail()"
             @keydown.escape.window="closeDetail()"
             style="display: none;"
             role="dialog"
             aria-modal="true">
            <div class="sipas-modal-dialog" style="max-width: 580px;" @click.stop>
                {{-- Header Modal --}}
                <div class="sipas-modal-header">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:34px; height:34px; background:#eff6ff; color:#1d4ed8; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </div>
                        <div>
                            <div style="font-size:15px; font-weight:700; color:var(--text-primary,#1e293b);">Detail Kunjungan Tamu</div>
                            <div style="font-size:12px; color:var(--text-muted,#64748b);">Informasi lengkap pengunjung dinas</div>
                        </div>
                    </div>
                    <button type="button" @click="closeDetail()" onclick="window.__bukuTamuHandler?.closeDetail(); return false;" class="sipas-modal-close" title="Tutup">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>

                {{-- Body Modal --}}
                <div class="sipas-modal-body" style="padding:22px; display:flex; flex-direction:column; gap:18px; overflow-y:auto; max-height:calc(85vh - 120px);">
                    {{-- Nama Tamu & Instansi Banner --}}
                    <div style="background:var(--page-bg,#f8fafc); border:1px solid var(--border,#e2e8f0); border-radius:10px; padding:16px;">
                        <div style="font-size:11px; font-weight:600; text-transform:uppercase; color:var(--text-muted,#94a3b8); letter-spacing:0.5px;">Nama Pengunjung</div>
                        <div style="font-size:17px; font-weight:700; color:var(--text-primary,#0f172a); margin-top:2px;" x-text="activeItem.nama || '—'"></div>
                        <div style="display:flex; align-items:center; gap:6px; margin-top:6px;">
                            <span style="font-size:12.5px; color:var(--text-muted,#64748b);">Instansi / Lembaga:</span>
                            <span style="font-size:12.5px; font-weight:600; color:var(--text-primary,#334155);" x-text="activeItem.instansi || '—'"></span>
                        </div>
                    </div>

                    {{-- Grid 2 Kolom Info --}}
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
                        <!-- Tanggal & Waktu -->
                        <div style="border:1px solid var(--border,#e2e8f0); border-radius:10px; padding:12px 14px;">
                            <div style="font-size:11px; font-weight:600; color:var(--text-muted,#94a3b8); text-transform:uppercase;">Waktu Kunjungan</div>
                            <div style="font-size:13px; font-weight:600; color:var(--text-primary,#1e293b); margin-top:4px;" x-text="activeItem.tanggal || '—'"></div>
                            <div style="font-size:12px; color:#2563eb; font-weight:600; font-family:monospace; margin-top:2px;" x-text="activeItem.jam || '—'"></div>
                        </div>

                        <!-- No Kontak -->
                        <div style="border:1px solid var(--border,#e2e8f0); border-radius:10px; padding:12px 14px;">
                            <div style="font-size:11px; font-weight:600; color:var(--text-muted,#94a3b8); text-transform:uppercase;">No. HP / WhatsApp</div>
                            <div style="font-size:13.5px; font-weight:600; font-family:monospace; color:var(--text-primary,#1e293b); margin-top:4px;" x-text="activeItem.no_hp || '—'"></div>
                            <template x-if="activeItem.no_hp && activeItem.no_hp !== '-'">
                                <a :href="'https://wa.me/' + (activeItem.no_hp ? activeItem.no_hp.replace(/[^0-9]/g, '') : '')"
                                   target="_blank"
                                   style="font-size:11.5px; color:#16a34a; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:4px; margin-top:4px;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.18 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.61A16 16 0 0 0 16 16.61l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 23.92 18z"/></svg>
                                    Hubungi WhatsApp
                                </a>
                            </template>
                        </div>

                        <!-- Bertemu Dengan -->
                        <div style="border:1px solid var(--border,#e2e8f0); border-radius:10px; padding:12px 14px;">
                            <div style="font-size:11px; font-weight:600; color:var(--text-muted,#94a3b8); text-transform:uppercase;">Bertemu Dengan</div>
                            <div style="font-size:13px; font-weight:600; color:var(--text-primary,#1e293b); margin-top:4px;" x-text="activeItem.pegawai || '—'"></div>
                        </div>

                        <!-- Unit / Seksi -->
                        <div style="border:1px solid var(--border,#e2e8f0); border-radius:10px; padding:12px 14px;">
                            <div style="font-size:11px; font-weight:600; color:var(--text-muted,#94a3b8); text-transform:uppercase;">Unit / Seksi Tujuan</div>
                            <div style="font-size:13px; font-weight:600; color:var(--text-primary,#1e293b); margin-top:4px;" x-text="activeItem.unit_kerja || '—'"></div>
                        </div>
                    </div>

                    {{-- Keperluan Kunjungan --}}
                    <div>
                        <div style="font-size:12px; font-weight:600; color:var(--text-muted,#64748b); margin-bottom:6px;">Keperluan Kunjungan:</div>
                        <div style="background:var(--page-bg,#f8fafc); border:1px solid var(--border,#e2e8f0); border-radius:8px; padding:14px 16px; font-size:13.5px; color:var(--text-primary,#334155); line-height:1.6; white-space:pre-wrap;"
                             x-text="activeItem.keperluan || '—'"></div>
                    </div>

                    {{-- Lampiran Berkas SPT --}}
                    <div>
                        <div style="font-size:12px; font-weight:600; color:var(--text-muted,#64748b); margin-bottom:6px;">Berkas SPT (Surat Tugas):</div>
                        <template x-if="activeItem.spt_url">
                            <div class="spt-card-box">
                                <div style="display:flex; align-items:center; gap:10px; min-width:0; flex:1;">
                                    <div style="width:34px; height:34px; background:#dbeafe; color:#1d4ed8; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    </div>
                                    <div style="min-width:0; overflow:hidden;">
                                        <div style="font-size:13px; font-weight:600; color:#1e3a8a; text-overflow:ellipsis; overflow:hidden; white-space:nowrap;" class="spt-file-name" x-text="activeItem.spt_name"></div>
                                        <div style="font-size:11px; color:#3b82f6;" class="spt-file-sub">Berkas terunggah</div>
                                    </div>
                                </div>
                                <div style="display:flex; align-items:center; gap:8px; flex-shrink:0;">
                                    {{-- Tombol 1: Lihat File (Buka di tab baru untuk pratinjau) --}}
                                    <a :href="activeItem.spt_url"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="spt-btn-view"
                                       title="Buka dan lihat berkas SPT di tab baru">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        Lihat File
                                    </a>

                                    {{-- Tombol 2: Unduh File (Download langsung file fisik ke komputer) --}}
                                    <a :href="activeItem.spt_download"
                                       class="spt-btn-download"
                                       title="Unduh berkas SPT langsung ke perangkat komputer">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                        Unduh File
                                    </a>
                                </div>
                            </div>
                        </template>
                        <template x-if="!activeItem.spt_url">
                            <div style="font-size:12.5px; color:var(--text-muted,#94a3b8); font-style:italic; padding:8px 12px; background:var(--page-bg,#f8fafc); border:1px solid var(--border,#e2e8f0); border-radius:8px;">
                                Tidak ada dokumen SPT yang dilampirkan oleh pengunjung.
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Footer Modal --}}
                <div class="sipas-modal-footer">
                    <button type="button" @click="closeDetail()" onclick="window.__bukuTamuHandler?.closeDetail(); return false;"
                            style="padding:8px 18px; background:var(--page-bg,#f1f5f9); color:var(--text-primary,#334155); border:1px solid var(--border,#cbd5e1); border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;"
                            onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='var(--page-bg,#f1f5f9)'">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════
           MODAL 2: KONFIRMASI HAPUS DATA KUNJUNGAN
        ══════════════════════════════════════════════════════ --}}
        <div x-cloak
             x-show="deleteModalOpen"
             x-transition.opacity.duration.200ms
             class="sipas-modal-overlay"
             @click.self="closeDelete()"
             @keydown.escape.window="closeDelete()"
             style="display: none;"
             role="dialog"
             aria-modal="true">
            <div class="sipas-modal-dialog" style="max-width: 440px;" @click.stop>
                <div class="sipas-modal-body" style="padding:24px 24px 16px; text-align:center;">
                    <div style="width:50px; height:50px; border-radius:50%; background:#fee2e2; color:#ef4444; display:flex; align-items:center; justify-content:center; margin:0 auto 14px;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    </div>

                    <h3 style="font-size:16px; font-weight:700; color:var(--text-primary,#1e293b); margin:0 0 8px;">Hapus Data Kunjungan?</h3>
                    <p style="font-size:13px; color:var(--text-muted,#64748b); margin:0; line-height:1.5;">
                        Apakah Anda yakin ingin menghapus catatan kunjungan dari <strong style="color:var(--text-primary,#0f172a);" x-text="deleteTarget.nama || 'pengunjung'"></strong>?
                        Tindakan ini tidak dapat dibatalkan dan berkas SPT terkait (jika ada) akan dihapus secara permanen.
                    </p>
                </div>

                <div class="sipas-modal-footer" style="padding:14px 20px; border-top:1px solid var(--border,#e2e8f0); display:flex; justify-content:flex-end; gap:10px; background:var(--card-bg,#ffffff);">
                    <button type="button" @click="closeDelete()" onclick="window.__bukuTamuHandler?.closeDelete(); return false;"
                            style="padding:8px 16px; background:var(--page-bg,#f1f5f9); color:var(--text-primary,#475569); border:1px solid var(--border,#cbd5e1); border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;">
                        Batal
                    </button>

                    <form id="deleteBukuTamuForm" :action="deleteTarget.destroy_url || '#'" method="POST" style="margin:0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                style="padding:8px 18px; background:#dc2626; color:white; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:6px;"
                                onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            Ya, Hapus Data
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Script Validasi Filter & Chart.js ── --}}
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Validasi client-side rentang tanggal
        var filterForm = document.getElementById('filterBukuTamuForm');
        if (filterForm) {
            filterForm.addEventListener('submit', function (e) {
                var startInput = document.getElementById('filter_start_date');
                var endInput = document.getElementById('filter_end_date');
                if (startInput && endInput && startInput.value && endInput.value) {
                    if (startInput.value > endInput.value) {
                        e.preventDefault();
                        alert('Tanggal mulai tidak boleh lebih besar dari tanggal selesai.');
                        startInput.focus();
                    }
                }
            });
        }

        // Inisialisasi Chart
        @if(count($chartLabels) > 0)
        (function () {
            var canvas = document.getElementById('chartKunjungan');
            if (!canvas || typeof Chart === 'undefined') return;

            new Chart(canvas, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Kunjungan',
                        data: @json($chartValues),
                        borderColor: '#1d4ed8',
                        borderWidth: 2,
                        backgroundColor: 'rgba(29, 78, 216, 0.08)',
                        pointBackgroundColor: '#1d4ed8',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 3.5,
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: '#1d4ed8',
                        tension: 0.35,
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#1e293b',
                            bodyColor: '#475569',
                            borderColor: '#e2e8f0',
                            borderWidth: 1,
                            padding: 10,
                            callbacks: {
                                title: function(ctx) { return ctx[0].label; },
                                label: function(ctx) { return '  ' + ctx.parsed.y + ' kunjungan'; }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            border: { display: false },
                            ticks: {
                                font: { size: 10.5 },
                                color: '#94a3b8',
                                maxTicksLimit: {{ count($chartLabels) <= 31 ? count($chartLabels) : 16 }},
                                autoSkip: {{ count($chartLabels) > 31 ? 'true' : 'false' }},
                                maxRotation: 45,
                                minRotation: 0
                            }
                        },
                        y: {
                            beginAtZero: true,
                            border: { display: false },
                            ticks: { stepSize: 1, precision: 0, font: { size: 10.5 }, color: '#94a3b8' },
                            grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false }
                        }
                    }
                }
            });
        })();
        @endif
    });
    </script>
    @endpush

</x-app-layout>
