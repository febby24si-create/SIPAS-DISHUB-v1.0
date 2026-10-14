<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; min-width:0;">
            <div style="display:flex; align-items:center; gap:12px; min-width:0;">
                <div style="width:40px; height:40px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; display:flex; align-items:center; justify-content:center; color:#1d4ed8; flex-shrink:0;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="3" width="7" height="7"/>
                        <rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/>
                    </svg>
                </div>
                <div style="min-width:0;">
                    <div style="font-size:17px; font-weight:600; color:#1e293b; line-height:1.25;">Dashboard Persuratan</div>
                    <div style="margin-top:3px; font-size:12.5px; color:#64748b; line-height:1.35;">Ringkasan aktivitas, statistik, dan dinamika naskah dinas SIPAS Dishub</div>
                </div>
            </div>

            <div class="dashboard-header-badge" style="display:inline-flex; align-items:center; gap:6px; padding:6px 12px; background:white; border:1px solid #e2e8f0; border-radius:8px; font-size:12px; color:#475569; font-weight:500;">
                <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#10b981;"></span>
                <span>Data Realtime &bull; {{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</span>
            </div>
        </div>
    </x-slot>
    <x-slot name="breadcrumb">Dashboard Utama</x-slot>

    <div class="dashboard-container" style="display:flex; flex-direction:column; gap:18px;"
         x-data="{
            bannerModalOpen: false,
            bannerPreviewUrl: null,
            bannerFileName: '',
            handleBannerSelect(e) {
                const file = e.target.files[0];
                if (file) {
                    this.bannerFileName = file.name;
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        this.bannerPreviewUrl = event.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            },
            cancelBannerSelect() {
                this.bannerPreviewUrl = null;
                this.bannerFileName = '';
                const el = document.getElementById('dash-banner-file');
                if (el) el.value = '';
            }
         }">

        {{-- ═══ 1. BANNER SELAMAT DATANG DENGAN GAMBAR LATAR DAPAT DIGANTI ═══ --}}
        <div class="dashboard-banner"
             style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.76) 0%, rgba(30, 58, 138, 0.85) 100%), url('{{ $bannerUrl }}') center/cover no-repeat;">

            {{-- Kolom Kiri: Greeting & Informasi Instansi --}}
            <div class="dashboard-banner-content">
                <div class="banner-pill">
                    <span class="banner-pill-dot"></span>
                    <span>Sistem Informasi Persuratan &amp; Administrasi (SIPAS)</span>
                </div>

                <h2 class="banner-title">
                    Selamat datang, {{ Auth::user()->name ?? 'Administrator' }}
                </h2>

                <p class="banner-desc">
                    Dinas Perhubungan Provinsi Riau &mdash; Kelola naskah dinas, surat masuk, surat keluar, dan pengarsipan digital secara transparan dan tertib administrasi.
                </p>

                <div class="banner-meta-tags">
                    <span class="banner-tag">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        {{ Auth::user()->role?->name ? ucfirst(Auth::user()->role->name) : 'Administrator' }}
                    </span>
                    <span class="banner-tag">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Naskah Terverifikasi
                    </span>
                    @if(isset($isCustomBanner) && $isCustomBanner)
                        <span class="banner-tag" style="background:rgba(34, 197, 94, 0.2); border-color:rgba(74, 222, 128, 0.4); color:#bbf7d0;">
                            Banner Kustom Aktif
                        </span>
                    @endif
                </div>
            </div>

            {{-- Kolom Kanan: Jam Digital & Tombol Ganti Banner (Admin) --}}
            <div class="dashboard-banner-aside">
                <div class="banner-clock-box">
                    <div id="jam-waktu" class="banner-clock-time">--:--:--</div>
                    <div id="jam-tanggal" class="banner-clock-date">Memuat waktu...</div>
                </div>

                @if(Auth::user()->role?->name === 'admin' || Auth::user()->role_id == 1)
                    <div style="margin-top:12px;">
                        <button type="button" @click="bannerModalOpen = true" class="btn-banner-action" title="Ganti gambar latar banner dashboard">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                            <span>Ganti Banner</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>

        {{-- ═══ 2. PENCARIAN CEPAT PERSURATAN ═══ --}}
        <form action="{{ route('pencarian.index') }}" method="GET" class="card dashboard-search-bar">
            <div class="dashboard-search-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
            </div>
            <input type="text" name="q" placeholder="Cari nomor surat, perihal, instansi pengirim, atau tujuan naskah dinas..."
                   class="dashboard-search-input"
                   autocomplete="off">
            <button type="submit" class="dashboard-search-btn">
                <span>Cari Arsip</span>
            </button>
        </form>

        {{-- ═══ 3. KARTU STATISTIK UTAMA (4 CARDS) ═══ --}}
        <div class="dashboard-stats-grid">
            {{-- 1. Total Surat Resmi --}}
            <a href="{{ route('arsip.index') }}" class="card stat-card" style="text-decoration:none;">
                <div class="stat-card-header">
                    <div class="stat-card-label">Total Surat Resmi</div>
                    <div class="stat-icon stat-icon--total">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                            <polyline points="10 9 9 9 8 9"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-card-value">
                    {{ number_format($totalSurat, 0, ',', '.') }}
                </div>
                <div class="stat-card-footer">
                    <span class="stat-card-sub">Naskah dinas berstatus final</span>
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="color:#94a3b8;"><polyline points="9 18 15 12 9 6"/></svg>
                </div>
            </a>

            {{-- 2. Surat Masuk --}}
            <a href="{{ route('surat-masuk.index') }}" class="card stat-card" style="text-decoration:none;">
                <div class="stat-card-header">
                    <div class="stat-card-label">Surat Masuk</div>
                    <div class="stat-icon stat-icon--masuk">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/>
                            <path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-card-value" style="color:#15803d;">
                    {{ number_format($totalMasuk, 0, ',', '.') }}
                </div>
                <div class="stat-card-footer">
                    <span class="stat-card-sub">Naskah dinas diterima</span>
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="color:#94a3b8;"><polyline points="9 18 15 12 9 6"/></svg>
                </div>
            </a>

            {{-- 3. Surat Keluar --}}
            <a href="{{ route('surat-keluar.index') }}" class="card stat-card" style="text-decoration:none;">
                <div class="stat-card-header">
                    <div class="stat-card-label">Surat Keluar</div>
                    <div class="stat-icon stat-icon--keluar">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="22" y1="2" x2="11" y2="13"/>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-card-value" style="color:#ea580c;">
                    {{ number_format($totalKeluar, 0, ',', '.') }}
                </div>
                <div class="stat-card-footer">
                    <span class="stat-card-sub">Naskah dinas diterbitkan</span>
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="color:#94a3b8;"><polyline points="9 18 15 12 9 6"/></svg>
                </div>
            </a>

            {{-- 4. Draft Surat --}}
            <a href="{{ route('surat-keluar.draft') }}" class="card stat-card" style="text-decoration:none;">
                <div class="stat-card-header">
                    <div class="stat-card-label">Draft Belum Final</div>
                    <div class="stat-icon stat-icon--draft">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 20h9"/>
                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                        </svg>
                    </div>
                </div>
                <div class="stat-card-value" style="color:#b45309;">
                    {{ number_format($totalDraft, 0, ',', '.') }}
                </div>
                <div class="stat-card-footer">
                    <span class="stat-card-sub">Menunggu verifikasi / ttd</span>
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="color:#94a3b8;"><polyline points="9 18 15 12 9 6"/></svg>
                </div>
            </a>
        </div>

        {{-- ═══ 4. GRID GRAFIK UTAMA & DISTRIBUSI (ROW 1) ═══ --}}
        <div class="dashboard-grid-2col">
            {{-- Tren Dokumen & Surat --}}
            <div class="card dashboard-panel">
                <div class="dashboard-panel-header">
                    <div>
                        <div class="dashboard-panel-title">
                            Tren Dokumen &amp; Persuratan
                            <span id="periodeTrendLabel" class="dashboard-panel-badge">6 Bulan Terakhir</span>
                        </div>
                        <div class="dashboard-panel-sub">Volume naskah dinas masuk, keluar, dan kepegawaian</div>
                    </div>

                    <div style="display:flex; align-items:center; gap:8px;">
                        <select id="periodeTrend" class="dashboard-select">
                            <option value="7_hari">7 Hari</option>
                            <option value="30_hari">30 Hari</option>
                            <option value="bulan" selected>Per Bulan</option>
                        </select>
                    </div>
                </div>

                <div class="chart-container" style="min-height:280px; position:relative;">
                    <canvas id="chartTren"></canvas>
                    <div id="trendLoading" class="chart-loading-overlay" style="display:none;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="spin-icon"><line x1="12" y1="2" x2="12" y2="6"/><line x1="12" y1="18" x2="12" y2="22"/><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"/><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"/><line x1="2" y1="12" x2="6" y2="12"/><line x1="18" y1="12" x2="22" y2="12"/><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"/><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"/></svg>
                        <span>Memuat data tren...</span>
                    </div>
                </div>
            </div>

            {{-- Distribusi Jenis Dokumen --}}
            <div class="card dashboard-panel">
                <div class="dashboard-panel-header">
                    <div>
                        <div class="dashboard-panel-title">Distribusi Jenis Dokumen</div>
                        <div class="dashboard-panel-sub">Proporsi naskah dinas final terdaftar</div>
                    </div>
                </div>

                <div style="flex:1; display:flex; flex-direction:column; justify-content:center;">
                    @if(count($distribusiData) > 0)
                        <div style="position:relative; width:100%; max-width:200px; height:200px; margin:0 auto 16px;">
                            <canvas id="chartDistribusi"></canvas>
                        </div>
                        @php $palDist = ['#1d4ed8','#22c55e','#ea580c','#7c3aed','#0ea5e9','#e11d48','#0d9488']; @endphp
                        <div style="display:flex; flex-direction:column; gap:6px; max-height:140px; overflow-y:auto; padding-right:4px;">
                            @foreach($distribusiLabels as $idx => $label)
                                <div style="display:flex; align-items:center; justify-content:space-between; font-size:12px; color:#475569;"
                                     title="{{ $distribusiFullLabels[$idx] ?? $label }}">
                                    <div style="display:flex; align-items:center; gap:8px; min-width:0; flex:1;">
                                        <span style="min-width:8px; width:8px; height:8px; border-radius:2px; background:{{ $palDist[$idx % count($palDist)] }};"></span>
                                        <span style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; font-weight:500;">
                                            {{ $distribusiFullLabels[$idx] ?? $label }}
                                        </span>
                                    </div>
                                    <span style="font-weight:700; color:var(--text-primary, #1e293b); margin-left:8px; font-variant-numeric:tabular-nums;">
                                        {{ $distribusiData[$idx] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="chart-empty-box">
                            <p>Belum ada data surat final untuk ditampilkan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ═══ 5. KLASIFIKASI & AGENDA (ROW 2) ═══ --}}
        <div class="dashboard-grid-2col">
            {{-- Surat Berdasarkan Klasifikasi --}}
            <div class="card dashboard-panel">
                <div class="dashboard-panel-header">
                    <div>
                        <div class="dashboard-panel-title">Surat Berdasarkan Klasifikasi</div>
                        <div class="dashboard-panel-sub">Top 8 kode klasifikasi surat final</div>
                    </div>
                </div>

                <div class="chart-container" style="min-height:190px;">
                    @if(count($klasifikasiData) > 0)
                        <canvas id="chartKlasifikasi"></canvas>
                    @else
                        <div class="chart-empty-box">
                            <p>Belum ada data klasifikasi surat.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Agenda Hari Ini --}}
            <div class="card dashboard-panel">
                <div class="dashboard-panel-header">
                    <div>
                        <div class="dashboard-panel-title">Agenda Hari Ini</div>
                        <div class="dashboard-panel-sub">Kalender kedinasan &amp; waktu kerja</div>
                    </div>
                </div>

                <div class="agenda-box">
                    <div class="agenda-date-big">{{ date('d') }}</div>
                    <div class="agenda-month">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</div>
                    <div class="agenda-day">{{ \Carbon\Carbon::now()->translatedFormat('l') }}</div>
                    <div class="agenda-note">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <span>Hari Kerja Aktif &bull; Dinas Perhubungan Riau</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ 6. UNIT KERJA & REKAP KEPEGAWAIAN (ROW 3) ═══ --}}
        <div class="dashboard-grid-2col">
            {{-- Surat Berdasarkan Unit Kerja --}}
            <div class="card dashboard-panel">
                <div class="dashboard-panel-header">
                    <div>
                        <div class="dashboard-panel-title">Surat Berdasarkan Unit Kerja</div>
                        <div class="dashboard-panel-sub">Distribusi volume naskah dinas per unit kerja</div>
                    </div>
                </div>

                <div class="chart-container" style="min-height:190px;">
                    @if(count($unitKerjaData) > 0)
                        <canvas id="chartUnitKerja"></canvas>
                    @else
                        <div class="chart-empty-box">
                            <p>Belum ada data unit kerja tercatat.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Rekap Kepegawaian --}}
            <div class="card dashboard-panel">
                <div class="dashboard-panel-header">
                    <div>
                        <div class="dashboard-panel-title">Rekapitulasi Kepegawaian</div>
                        <div class="dashboard-panel-sub">Statistik Cuti &amp; Kenaikan Gaji Berkala</div>
                    </div>
                </div>

                <div class="chart-container" style="min-height:190px;">
                    <canvas id="chartRekapKepegawaian"></canvas>
                </div>
            </div>
        </div>

        {{-- ═══ 7. TABEL AKTIVITAS TERBARU ═══ --}}
        <div class="card" style="border-radius:14px; overflow:hidden; border:1px solid rgba(0,0,0,0.07);">
            <div style="padding:14px 20px; border-bottom:1px solid var(--border-light, #f1f5f9); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:32px; height:32px; border-radius:8px; background:#eff6ff; color:#1d4ed8; display:flex; align-items:center; justify-content:center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                    <div>
                        <h3 style="margin:0; font-size:14px; font-weight:700; color:var(--text-primary, #1e293b);">Aktivitas Sistem Terbaru</h3>
                        <div style="font-size:11.5px; color:var(--text-muted, #64748b);">Riwayat tindakan pengguna pada naskah dinas dan administrasi</div>
                    </div>
                </div>

                <a href="{{ route('pencarian.index') }}" style="font-size:12.5px; color:#1d4ed8; text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:4px;">
                    <span>Lihat di Pencarian</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
            </div>

            <div class="table-wrapper">
                <table class="data-table dashboard-table" style="width:100%; min-width:640px;">
                    <thead>
                        <tr>
                            <th style="padding:11px 20px; width:170px;">Waktu</th>
                            <th style="padding:11px 20px; width:180px;">Pengguna</th>
                            <th style="padding:11px 20px;">Aktivitas</th>
                            <th style="padding:11px 20px; width:160px; text-align:right;">Status / Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aktivitasTerbaru as $log)
                        <tr class="aktivitas-row">
                            <td style="padding:11px 20px; font-size:12.5px; color:#64748b; white-space:nowrap; font-variant-numeric:tabular-nums;">
                                {{ $log->created_at?->translatedFormat('d M Y, H:i') }}
                            </td>
                            <td style="padding:11px 20px;">
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <span class="user-avatar-badge">
                                        {{ strtoupper(substr($log->user->name ?? 'S', 0, 1)) }}
                                    </span>
                                    <span style="font-size:13px; font-weight:600; color:var(--text-primary, #1e293b); white-space:nowrap;">
                                        {{ $log->user->name ?? 'Sistem' }}
                                    </span>
                                </div>
                            </td>
                            <td style="padding:11px 20px; font-size:13px; color:var(--text-primary, #334155);">
                                {{ $log->aktivitas ?? $log->description ?? 'Melakukan aksi sistem' }}
                            </td>
                            <td style="padding:11px 20px; text-align:right;">
                                <span class="activity-action-pill">
                                    {{ $log->action ?? 'Selesai' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="padding:32px 20px; text-align:center; color:#9ca3af; font-size:13px;">
                                Belum ada catatan aktivitas terbaru.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ═══ MODAL GANTI GAMBAR BANNER (ADMINISTRATOR) ═══ --}}
        @if(Auth::user()->role?->name === 'admin' || Auth::user()->role_id == 1)
        <div x-show="bannerModalOpen" x-cloak
             class="modal-backdrop"
             @keydown.escape.window="bannerModalOpen = false">

            <div class="modal-card" @click.outside="bannerModalOpen = false">
                {{-- Modal Header --}}
                <div class="modal-header">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:34px; height:34px; border-radius:8px; background:#eff6ff; color:#1d4ed8; display:flex; align-items:center; justify-content:center;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                        </div>
                        <div>
                            <h4 style="margin:0; font-size:15px; font-weight:700; color:var(--text-primary, #1e293b);">Ganti Gambar Latar Banner</h4>
                            <p style="margin:2px 0 0; font-size:12px; color:var(--text-muted, #64748b);">Perbarui foto latar banner selamat datang di Dashboard</p>
                        </div>
                    </div>
                    <button type="button" @click="bannerModalOpen = false" class="modal-close-btn" aria-label="Tutup modal">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <form action="{{ route('pengaturan.banner.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        {{-- Live Preview Box in Modal --}}
                        <div style="margin-bottom:14px;">
                            <div style="font-size:11.5px; font-weight:600; color:#475569; text-transform:uppercase; letter-spacing:0.4px; margin-bottom:6px;">
                                Pratinjau Tampilan
                            </div>
                            <div style="position:relative; width:100%; height:130px; border-radius:10px; overflow:hidden; border:1px solid rgba(0,0,0,0.1); display:flex; align-items:center; padding:16px 20px; box-sizing:border-box;">
                                <div :style="bannerPreviewUrl ? 'background: linear-gradient(135deg, rgba(15, 23, 42, 0.78) 0%, rgba(30, 58, 138, 0.85) 100%), url(' + bannerPreviewUrl + ') center/cover no-repeat;' : 'background: linear-gradient(135deg, rgba(15, 23, 42, 0.78) 0%, rgba(30, 58, 138, 0.85) 100%), url(\'{{ $bannerUrl }}\') center/cover no-repeat;'"
                                     style="position:absolute; inset:0; z-index:1; transition:all 0.3s ease;"></div>
                                <div style="position:relative; z-index:2; color:white;">
                                    <div style="font-size:15px; font-weight:700;">Selamat datang, {{ Auth::user()->name ?? 'Administrator' }}</div>
                                    <div style="font-size:11.5px; color:#bfdbfe; margin-top:2px;">Sistem Informasi Persuratan Dinas Perhubungan Provinsi Riau</div>
                                </div>
                            </div>
                        </div>

                        {{-- File Input --}}
                        <div style="margin-bottom:14px;">
                            <label for="dash-banner-file" style="display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:6px;">
                                Pilih File Gambar Baru
                            </label>
                            <input type="file" id="dash-banner-file" name="banner_image" accept="image/jpeg,image/png,image/webp"
                                   @change="handleBannerSelect($event)"
                                   required
                                   style="width:100%; box-sizing:border-box; font-size:12.5px; color:#475569; padding:8px 10px; background:white; border:1px solid #cbd5e1; border-radius:8px; outline:none;">
                            <div style="font-size:11px; color:#64748b; margin-top:5px; line-height:1.4;">
                                Format: <strong>JPG, JPEG, PNG, WebP</strong> &bull; Maks. <strong>3 MB</strong> &bull; Disarankan rasio lebar (1600 &times; 400 px).
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="modal-footer">
                        <div style="display:flex; gap:8px;">
                            <button type="submit" class="btn-primary" style="padding:8px 16px; font-size:12.5px; font-weight:600; border-radius:8px;">
                                Simpan Banner Baru
                            </button>
                            <button type="button" @click="bannerModalOpen = false; cancelBannerSelect();" class="btn-secondary" style="padding:8px 14px; font-size:12.5px; border-radius:8px;">
                                Batal
                            </button>
                        </div>

                        <div style="display:flex; align-items:center; gap:8px;">
                            @if(isset($isCustomBanner) && $isCustomBanner)
                                <button type="button" onclick="document.getElementById('form-modal-reset-banner').submit();"
                                        style="font-size:12px; color:#dc2626; background:none; border:none; text-decoration:underline; cursor:pointer;">
                                    Kembalikan ke Default
                                </button>
                            @endif
                            <a href="{{ route('pengaturan.index') }}" style="font-size:12px; color:#1d4ed8; text-decoration:none;">
                                Pengaturan Lengkap &rarr;
                            </a>
                        </div>
                    </div>
                </form>

                @if(isset($isCustomBanner) && $isCustomBanner)
                    <form id="form-modal-reset-banner" action="{{ route('pengaturan.banner.reset') }}" method="POST" style="display:none;" onsubmit="return confirm('Kembalikan banner ke gambar bawaan?');">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            </div>
        </div>
        @endif

        {{-- FOOTER --}}
        <div style="border-top:1px solid var(--border-light, #e2e8f0); padding-top:16px; font-size:12px; color:#94a3b8; display:flex; justify-content:space-between; flex-wrap:wrap; gap:10px;">
            <span>&copy; {{ date('Y') }} Pemerintah Provinsi Riau &mdash; Dinas Perhubungan. Sistem Informasi Persuratan &amp; Administrasi (SIPAS).</span>
            <div style="display:flex; gap:16px;">
                <a href="{{ route('pencarian.index') }}" style="color:#94a3b8; text-decoration:none;">Pencarian Arsip</a>
                <a href="{{ route('laporan.index') }}" style="color:#94a3b8; text-decoration:none;">Laporan Persuratan</a>
            </div>
        </div>

    </div>

    {{-- ═══ 8. CHART.JS SCRIPT (KOMPATIBEL LIGHT & DARK MODE) ═══ --}}
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        /* ── Jam Realtime ── */
        const HARI_ID  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const BULAN_ID = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        const elTgl = document.getElementById('jam-tanggal');
        const elJam = document.getElementById('jam-waktu');
        function updateJam() {
            const n = new Date();
            if (elJam) elJam.textContent = `${String(n.getHours()).padStart(2,'0')}:${String(n.getMinutes()).padStart(2,'0')}:${String(n.getSeconds()).padStart(2,'0')}`;
            if (elTgl) elTgl.textContent = `${HARI_ID[n.getDay()]}, ${n.getDate()} ${BULAN_ID[n.getMonth()]} ${n.getFullYear()}`;
        }
        updateJam(); setInterval(updateJam, 1000);

        /* ── Chart.js ── */
        const Chart = window.Chart;
        if (!Chart) return;

        const PALETTE = ['#1d4ed8','#22c55e','#ea580c','#7c3aed','#0ea5e9','#e11d48','#0d9488'];

        /* Deteksi dark mode */
        function isDark() {
            return document.documentElement.getAttribute('data-theme') === 'dark';
        }

        /* Palette warna chart berdasarkan tema */
        function chartColors() {
            if (isDark()) {
                return {
                    tickColor:     '#94a3b8',
                    gridColor:     '#293548',
                    labelColor:    '#94a3b8',
                    tooltipBg:     '#1e293b',
                    tooltipTitle:  '#e2e8f0',
                    tooltipBody:   '#cbd5e1',
                    tooltipBorder: '#334155',
                    doughnutBorder:'#1e293b',
                };
            }
            return {
                tickColor:     '#64748b',
                gridColor:     '#f1f5f9',
                labelColor:    '#64748b',
                tooltipBg:     '#ffffff',
                tooltipTitle:  '#1e293b',
                tooltipBody:   '#475569',
                tooltipBorder: '#e2e8f0',
                doughnutBorder:'#ffffff',
            };
        }

        /* Terapkan warna ke semua chart yang sudah dibuat */
        function applyThemeToAllCharts() {
            const c = chartColors();
            if (!Chart.instances) return;
            Object.values(Chart.instances).forEach(function(chart) {
                if (!chart || !chart.config) return;
                const opts = chart.config.options || {};

                // Scales (x, y, r)
                if (opts.scales) {
                    Object.keys(opts.scales).forEach(function(axis) {
                        const sc = opts.scales[axis];
                        if (!sc) return;
                        if (sc.ticks) sc.ticks.color = c.tickColor;
                        if (sc.grid && sc.grid.display !== false) sc.grid.color = c.gridColor;
                    });
                }

                // Legend labels color
                if (opts.plugins && opts.plugins.legend && opts.plugins.legend.labels) {
                    opts.plugins.legend.labels.color = c.labelColor;
                }

                // Tooltip
                if (opts.plugins) {
                    if (!opts.plugins.tooltip) opts.plugins.tooltip = {};
                    const tt = opts.plugins.tooltip;
                    tt.backgroundColor = c.tooltipBg;
                    tt.titleColor      = c.tooltipTitle;
                    tt.bodyColor       = c.tooltipBody;
                    tt.borderColor     = c.tooltipBorder;
                    tt.borderWidth     = 1;
                }

                // Doughnut border
                if (chart.config.type === 'doughnut' && chart.data && chart.data.datasets) {
                    chart.data.datasets.forEach(function(ds) { ds.borderColor = c.doughnutBorder; });
                }

                chart.update('none');
            });
        }

        /* MutationObserver: update chart saat tema berubah */
        new MutationObserver(function(mutations) {
            mutations.forEach(function(m) {
                if (m.type === 'attributes' && m.attributeName === 'data-theme') {
                    setTimeout(applyThemeToAllCharts, 80);
                }
            });
        }).observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });

        /* Warna saat init */
        const c = chartColors();

        /* 1. Tren Surat + Cuti + KGB */
        const trendLabels = @json($trendLabels);
        const trendMasuk  = @json($trendMasuk);
        const trendKeluar = @json($trendKeluar);
        const trendCuti   = @json($trendCuti);
        const trendKgb    = @json($trendKgb);
        const TREND_URL   = '{{ route('dashboard.trend') }}';

        const ctxTren = document.getElementById('chartTren');
        let chartTren = null;
        if (ctxTren) {
            chartTren = new Chart(ctxTren, {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [
                        { label:'Surat Masuk',  data:trendMasuk,  borderColor:'#16a34a', backgroundColor:'rgba(22,163,74,0.06)',   borderWidth:2.5, pointBackgroundColor:'#16a34a', pointRadius:4, tension:0.3, fill:true },
                        { label:'Surat Keluar', data:trendKeluar, borderColor:'#1d4ed8', backgroundColor:'rgba(29,78,216,0.06)',   borderWidth:2.5, pointBackgroundColor:'#1d4ed8', pointRadius:4, tension:0.3, fill:true },
                        { label:'Cuti',         data:trendCuti,   borderColor:'#ea580c', backgroundColor:'rgba(234,88,12,0.05)',   borderWidth:2,   pointBackgroundColor:'#ea580c', pointRadius:4, tension:0.3, fill:false, borderDash:[4,3] },
                        { label:'KGB',          data:trendKgb,    borderColor:'#7c3aed', backgroundColor:'rgba(124,58,237,0.05)', borderWidth:2,   pointBackgroundColor:'#7c3aed', pointRadius:4, tension:0.3, fill:false, borderDash:[4,3] }
                    ]
                },
                options: {
                    responsive:true, maintainAspectRatio:false,
                    interaction:{ mode:'index', intersect:false },
                    plugins:{
                        legend:{ display:true, position:'top', labels:{ font:{size:12, weight:'500'}, boxWidth:12, usePointStyle:true, color:c.labelColor } },
                        tooltip:{ backgroundColor:c.tooltipBg, titleColor:c.tooltipTitle, bodyColor:c.tooltipBody, borderColor:c.tooltipBorder, borderWidth:1 }
                    },
                    scales:{
                        x:{ grid:{display:false}, ticks:{font:{size:11.5},color:c.tickColor,maxTicksLimit:10}, border:{display:false} },
                        y:{ grid:{color:c.gridColor}, ticks:{font:{size:11.5},color:c.tickColor,precision:0,stepSize:1}, border:{display:false}, beginAtZero:true }
                    }
                }
            });
        }

        /* Dropdown periode — fetch tanpa reload */
        const periodeSelect = document.getElementById('periodeTrend');
        const periodeLabel  = document.getElementById('periodeTrendLabel');
        const loadingEl     = document.getElementById('trendLoading');
        const labelMap = { '7_hari':'7 Hari Terakhir', '30_hari':'30 Hari Terakhir', 'bulan':'6 Bulan Terakhir' };

        function fetchTrend(periode) {
            if (!chartTren) return;
            if (loadingEl) { loadingEl.style.display = 'flex'; }
            fetch(TREND_URL + '?periode=' + encodeURIComponent(periode), {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(function(res) { if (!res.ok) throw new Error('HTTP ' + res.status); return res.json(); })
            .then(function(data) {
                chartTren.data.labels           = data.labels;
                chartTren.data.datasets[0].data = data.masuk;
                chartTren.data.datasets[1].data = data.keluar;
                chartTren.data.datasets[2].data = data.cuti;
                chartTren.data.datasets[3].data = data.kgb;
                chartTren.update();
                if (periodeLabel) periodeLabel.textContent = labelMap[periode] || '';
            })
            .catch(function(err) {
                console.error('Trend fetch error:', err);
                if (periodeLabel) periodeLabel.textContent = 'Gagal memuat data';
            })
            .finally(function() { if (loadingEl) { loadingEl.style.display = 'none'; } });
        }
        if (periodeSelect) periodeSelect.addEventListener('change', function() { fetchTrend(this.value); });

        /* 2. Distribusi (Donut) */
        const distLabels = @json($distribusiLabels);
        const distData   = @json($distribusiData);
        const ctxDist    = document.getElementById('chartDistribusi');
        if (ctxDist && distData.length > 0) {
            new Chart(ctxDist, {
                type: 'doughnut',
                data: { labels: distLabels, datasets: [{ data: distData, backgroundColor: PALETTE.slice(0, distData.length), borderWidth:2, borderColor: c.doughnutBorder }] },
                options: {
                    responsive:true, maintainAspectRatio:false, cutout:'65%',
                    plugins: {
                        legend: { display: false },
                        tooltip:{ backgroundColor:c.tooltipBg, titleColor:c.tooltipTitle, bodyColor:c.tooltipBody, borderColor:c.tooltipBorder, borderWidth:1 }
                    }
                }
            });
        }

        /* 3. Klasifikasi (Bar Horizontal) */
        const klasifLabels = @json($klasifikasiLabels);
        const klasifData   = @json($klasifikasiData);
        const ctxKlasif    = document.getElementById('chartKlasifikasi');
        if (ctxKlasif && klasifData.length > 0) {
            new Chart(ctxKlasif, {
                type: 'bar',
                data: { labels: klasifLabels, datasets: [{ data: klasifData, backgroundColor: '#3b82f6', borderRadius: 4 }] },
                options: {
                    indexAxis: 'y', responsive:true, maintainAspectRatio:false,
                    plugins: {
                        legend: { display: false },
                        tooltip:{ backgroundColor:c.tooltipBg, titleColor:c.tooltipTitle, bodyColor:c.tooltipBody, borderColor:c.tooltipBorder, borderWidth:1 }
                    },
                    scales: {
                        x: { grid:{ color:c.gridColor }, ticks:{ font:{size:11.5}, color:c.tickColor, precision:0, stepSize:1 }, border:{display:false}, beginAtZero:true },
                        y: { grid:{ display:false }, ticks:{ font:{size:11.5}, color:c.tickColor }, border:{display:false} }
                    }
                }
            });
        }

        /* 4. Unit Kerja (Bar Horizontal) */
        const unitLabels = @json($unitKerjaLabels);
        const unitData   = @json($unitKerjaData);
        const ctxUnit    = document.getElementById('chartUnitKerja');
        if (ctxUnit && unitData.length > 0) {
            new Chart(ctxUnit, {
                type: 'bar',
                data: { labels: unitLabels, datasets: [{ data: unitData, backgroundColor: '#6366f1', borderRadius: 4 }] },
                options: {
                    indexAxis: 'y', responsive:true, maintainAspectRatio:false,
                    plugins: {
                        legend: { display: false },
                        tooltip:{ backgroundColor:c.tooltipBg, titleColor:c.tooltipTitle, bodyColor:c.tooltipBody, borderColor:c.tooltipBorder, borderWidth:1 }
                    },
                    scales: {
                        x: { grid:{ color:c.gridColor }, ticks:{ font:{size:11.5}, color:c.tickColor, precision:0, stepSize:1 }, border:{display:false}, beginAtZero:true },
                        y: { grid:{ display:false }, ticks:{ font:{size:11.5}, color:c.tickColor }, border:{display:false} }
                    }
                }
            });
        }

        /* 5. Rekap Kepegawaian (Bar Vertikal) */
        const rekapLabels = @json($rekapKepegawaianLabels);
        const rekapData   = @json($rekapKepegawaianData);
        const ctxRekap    = document.getElementById('chartRekapKepegawaian');
        if (ctxRekap) {
            new Chart(ctxRekap, {
                type: 'bar',
                data: { labels: rekapLabels, datasets: [{ data: rekapData, backgroundColor: ['#ea580c', '#7c3aed'], borderRadius: 4 }] },
                options: {
                    responsive:true, maintainAspectRatio:false,
                    plugins: {
                        legend: { display: false },
                        tooltip:{ backgroundColor:c.tooltipBg, titleColor:c.tooltipTitle, bodyColor:c.tooltipBody, borderColor:c.tooltipBorder, borderWidth:1 }
                    },
                    scales: {
                        x: { grid:{ display:false }, ticks:{ font:{size:11.5}, color:c.tickColor }, border:{display:false} },
                        y: { grid:{ color:c.gridColor }, ticks:{ font:{size:11.5}, color:c.tickColor, precision:0, stepSize:1 }, border:{display:false}, beginAtZero:true }
                    }
                }
            });
        }
    });
    </script>
    @endpush

    {{-- ═══ 9. SCOPED STYLES (REFERENSI ARSIP DIGITAL & LAPORAN) ═══ --}}
    <style>
        /* ── Banner Selamat Datang ── */
        .dashboard-banner {
            border-radius: 14px;
            padding: 24px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            color: white;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .dashboard-banner-content {
            flex: 1;
            min-width: 280px;
        }

        .banner-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 3px 10px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            color: #e0f2fe;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .banner-pill-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #38bdf8;
        }

        .banner-title {
            margin: 0 0 4px 0;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.2px;
            text-shadow: 0 1px 3px rgba(0,0,0,0.4);
            line-height: 1.25;
        }

        .banner-desc {
            margin: 0;
            font-size: 13px;
            color: #bfdbfe;
            line-height: 1.5;
            max-width: 620px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }

        .banner-meta-tags {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 12px;
            flex-wrap: wrap;
        }

        .banner-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 9px;
            border-radius: 6px;
            font-size: 11.5px;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
            backdrop-filter: blur(4px);
        }

        .dashboard-banner-aside {
            text-align: right;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: center;
        }

        .banner-clock-box {
            background: rgba(0, 0, 0, 0.22);
            border: 1px solid rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(6px);
            padding: 10px 16px;
            border-radius: 10px;
            text-align: center;
            min-width: 170px;
        }

        .banner-clock-time {
            font-size: 22px;
            font-weight: 700;
            font-variant-numeric: tabular-nums;
            letter-spacing: 1px;
            color: #ffffff;
            line-height: 1.1;
        }

        .banner-clock-date {
            font-size: 11.5px;
            color: #bfdbfe;
            font-weight: 500;
            margin-top: 4px;
        }

        .btn-banner-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            color: white;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            backdrop-filter: blur(4px);
            transition: all 0.15s ease;
        }
        .btn-banner-action:hover {
            background: rgba(255, 255, 255, 0.28);
            border-color: rgba(255, 255, 255, 0.45);
        }

        /* ── Pencarian Cepat ── */
        .dashboard-search-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 12px;
            border: 1px solid rgba(0,0,0,0.07);
            background: white;
        }

        .dashboard-search-icon {
            display: flex;
            align-items: center;
            padding-left: 6px;
            color: #94a3b8;
            flex-shrink: 0;
        }

        .dashboard-search-input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 13.5px;
            color: #334155;
            background: transparent;
            padding: 4px 0;
        }
        .dashboard-search-input::placeholder {
            color: #94a3b8;
        }

        .dashboard-search-btn {
            display: inline-flex;
            align-items: center;
            padding: 7px 18px;
            background: #1d4ed8;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.15s ease;
        }
        .dashboard-search-btn:hover {
            background: #1e40af;
        }

        /* ── 4 Kartu Statistik ── */
        .dashboard-stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
        }

        .stat-card {
            padding: 16px 18px;
            border-radius: 12px;
            border: 1px solid rgba(0,0,0,0.07);
            background: white;
            display: flex;
            flex-direction: column;
            gap: 6px;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-color: #cbd5e1;
        }

        .stat-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-card-label {
            font-size: 11.5px;
            font-weight: 600;
            color: var(--text-muted, #64748b);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .stat-icon--total  { background: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe; }
        .stat-icon--masuk  { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; }
        .stat-icon--keluar { background: #fff7ed; color: #ea580c; border: 1px solid #ffedd5; }
        .stat-icon--draft  { background: #fffbeb; color: #b45309; border: 1px solid #fef3c7; }

        .stat-card-value {
            font-size: 26px;
            font-weight: 800;
            color: var(--text-primary, #1e293b);
            line-height: 1.1;
            font-variant-numeric: tabular-nums;
        }

        .stat-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 2px;
        }

        .stat-card-sub {
            font-size: 11.5px;
            color: #94a3b8;
        }

        /* ── Grid 2 Kolom Panel ── */
        .dashboard-grid-2col {
            display: grid;
            grid-template-columns: 1.8fr 1fr;
            gap: 14px;
        }

        .dashboard-panel {
            padding: 18px 20px;
            border-radius: 14px;
            border: 1px solid rgba(0,0,0,0.07);
            background: white;
            display: flex;
            flex-direction: column;
        }

        .dashboard-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .dashboard-panel-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-primary, #1e293b);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .dashboard-panel-sub {
            font-size: 11.5px;
            color: var(--text-muted, #64748b);
            margin-top: 2px;
        }

        .dashboard-panel-badge {
            font-size: 11px;
            font-weight: 500;
            color: #64748b;
            background: #f1f5f9;
            padding: 2px 8px;
            border-radius: 6px;
        }

        .dashboard-select {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 5px 10px;
            font-size: 12px;
            font-weight: 500;
            color: #334155;
            cursor: pointer;
            background: #f8fafc;
            outline: none;
        }

        .chart-container {
            flex: 1;
            position: relative;
        }

        .chart-loading-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.75);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 12.5px;
            color: #64748b;
            border-radius: 8px;
            z-index: 5;
        }

        .chart-empty-box {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 160px;
            color: #94a3b8;
            font-size: 12.5px;
            font-style: italic;
        }

        /* ── Agenda Box ── */
        .agenda-box {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
            padding: 24px 20px;
            text-align: center;
        }

        .agenda-date-big {
            font-size: 52px;
            font-weight: 800;
            color: #1e3a8a;
            line-height: 1;
        }

        .agenda-month {
            font-size: 15px;
            font-weight: 700;
            color: #475569;
            margin-top: 6px;
        }

        .agenda-day {
            font-size: 13px;
            color: #94a3b8;
            margin-top: 2px;
        }

        .agenda-note {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 14px;
            font-size: 11px;
            color: #15803d;
            background: #f0fdf4;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 600;
            border: 1px solid #dcfce7;
        }

        /* ── Tabel Aktivitas ── */
        .dashboard-table thead th {
            font-size: 11.5px;
            font-weight: 600;
            color: var(--text-muted, #64748b);
            background: var(--page-bg, #f8fafc);
            border-bottom: 1px solid var(--border-light, #f1f5f9);
        }

        .dashboard-table tbody tr {
            border-bottom: 1px solid var(--border-light, #f1f5f9);
            transition: background 0.15s ease;
        }
        .dashboard-table tbody tr:hover {
            background: rgba(0, 0, 0, 0.015);
        }

        .user-avatar-badge {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 11px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 1px solid #bfdbfe;
        }

        .activity-action-pill {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            background: #f1f5f9;
            color: #475569;
        }

        /* ── Modal Ganti Banner ── */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 1050;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .modal-card {
            background: white;
            border-radius: 14px;
            max-width: 520px;
            width: 100%;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            border: 1px solid rgba(0,0,0,0.1);
            animation: modalFadeIn 0.2s ease;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.96); }
            to   { opacity: 1; transform: scale(1); }
        }

        .modal-header {
            padding: 16px 20px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-close-btn {
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-close-btn:hover {
            color: #1e293b;
            background: #f1f5f9;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            padding: 14px 20px;
            border-top: 1px solid #f1f5f9;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .spin-icon {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        /* ── Aksesibilitas ── */
        .dashboard-container a:focus-visible,
        .dashboard-container button:focus-visible,
        .dashboard-container input:focus-visible,
        .dashboard-container select:focus-visible {
            outline: 2px solid #2563eb;
            outline-offset: 2px;
        }

        /* ── Responsif Breakpoints ── */
        @media (max-width: 1024px) {
            .dashboard-stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .dashboard-grid-2col {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .dashboard-banner {
                padding: 18px 20px;
            }
            .dashboard-banner-aside {
                width: 100%;
                align-items: stretch;
                text-align: left;
            }
            .banner-clock-box {
                width: 100%;
            }
            .dashboard-stats-grid {
                grid-template-columns: 1fr;
            }
            .dashboard-search-bar {
                flex-wrap: wrap;
            }
            .dashboard-search-btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* ── Dark Mode Overrides ── */
        [data-theme="dark"] .dashboard-search-bar {
            background: #1e293b !important;
            border-color: #334155 !important;
        }
        [data-theme="dark"] .dashboard-search-input {
            color: #e2e8f0 !important;
        }
        [data-theme="dark"] .dashboard-header-badge {
            background: #1e293b !important;
            border-color: #334155 !important;
            color: #94a3b8 !important;
        }
        [data-theme="dark"] .dashboard-panel {
            background: #1e293b !important;
            border-color: #334155 !important;
        }
        [data-theme="dark"] .dashboard-panel-badge {
            background: #162032 !important;
            color: #94a3b8 !important;
        }
        [data-theme="dark"] .dashboard-select {
            background: #162032 !important;
            border-color: #334155 !important;
            color: #e2e8f0 !important;
        }
        [data-theme="dark"] .agenda-box {
            background: #162032 !important;
            border-color: #334155 !important;
        }
        [data-theme="dark"] .agenda-month {
            color: #cbd5e1 !important;
        }
        [data-theme="dark"] .agenda-note {
            background: rgba(22, 163, 74, 0.15) !important;
            border-color: rgba(22, 163, 74, 0.3) !important;
            color: #4ade80 !important;
        }
        [data-theme="dark"] .chart-loading-overlay {
            background: rgba(15, 23, 42, 0.75) !important;
            color: #94a3b8 !important;
        }
        [data-theme="dark"] .modal-card {
            background: #1e293b !important;
            border-color: #334155 !important;
        }
        [data-theme="dark"] .modal-header,
        [data-theme="dark"] .modal-footer {
            background: #162032 !important;
            border-color: #334155 !important;
        }
        [data-theme="dark"] .modal-close-btn:hover {
            color: #e2e8f0 !important;
            background: #293548 !important;
        }
        [data-theme="dark"] .dashboard-table thead th {
            background: #162032 !important;
            border-color: #334155 !important;
        }
        [data-theme="dark"] .dashboard-table tbody tr {
            border-color: #293548 !important;
        }
        [data-theme="dark"] .dashboard-table tbody tr:hover {
            background: #162032 !important;
        }
        [data-theme="dark"] .activity-action-pill {
            background: #162032 !important;
            color: #94a3b8 !important;
        }
        [data-theme="dark"] .user-avatar-badge {
            background: #1e293b !important;
            border-color: #334155 !important;
            color: #93c5fd !important;
        }
    </style>
</x-app-layout>
