<x-app-layout>
    <x-slot name="header">Dashboard Persuratan</x-slot>
    <x-slot name="breadcrumb">Dashboard Utama</x-slot>

    <div style="display:flex; flex-direction:column; gap:28px;">

        {{-- ① WELCOME BANNER ──────────────────────────────────── --}}
        <div style="
            background: linear-gradient(135deg, #1a3560 0%, #1e4bb8 55%, #2563eb 100%);
            border-radius: 16px;
            padding: 32px 36px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            min-height: 130px;
        ">
            {{-- Decorative circles --}}
            <div style="position:absolute; top:-60px; right:280px; width:220px; height:220px; border-radius:50%; background:rgba(255,255,255,0.05); pointer-events:none;"></div>
            <div style="position:absolute; bottom:-70px; right:100px; width:260px; height:260px; border-radius:50%; background:rgba(255,255,255,0.04); pointer-events:none;"></div>

            <div style="position:relative; z-index:1;">
                <div style="display:inline-flex; align-items:center; gap:7px; background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.2); border-radius:20px; padding:4px 12px; margin-bottom:12px;">
                    <span style="width:7px; height:7px; background:#4ade80; border-radius:50%; display:inline-block; flex-shrink:0;"></span>
                    <span style="font-size:11px; color:rgba(255,255,255,0.9); font-weight:600; letter-spacing:0.5px; text-transform:uppercase;">Portal Dispersip &amp; Arsip Resmi Riau</span>
                </div>
                <h2 style="color:white; font-size:26px; font-weight:700; margin:0 0 6px 0; line-height:1.3;">
                    Selamat datang, {{ Auth::user()->name }}
                </h2>
                <p style="color:rgba(255,255,255,0.65); font-size:13.5px; margin:0; max-width:520px; line-height:1.6;">
                    Sistem Informasi Persuratan terpadu Dinas Perhubungan Provinsi Riau. Kelola tata naskah dinas, agenda surat, dan arsip digital secara akuntabel.
                </p>
            </div>

            <div style="position:relative; z-index:1; display:flex; align-items:center; gap:12px; flex-shrink:0;">
                <div style="text-align:center; background:rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.2); border-radius:12px; padding:14px 24px;">
                    <div style="font-size:11px; color:rgba(255,255,255,0.65); margin-bottom:4px; letter-spacing:0.5px; text-transform:uppercase; font-weight:500;">Periode</div>
                    <div style="font-size:18px; font-weight:700; color:white;">{{ now()->translatedFormat('M Y') }}</div>
                </div>
            </div>
        </div>

        {{-- ② SEARCH BAR ──────────────────────────────────────── --}}
        <form action="{{ route('pencarian.index') }}" method="GET"
              style="display:flex; align-items:center; gap:12px; background:white; border:1px solid #d1d5db; border-radius:10px; padding:12px 18px; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="q"
                   placeholder="Cari nomor surat, perihal, instansi pengirim, atau tujuan naskah dinas..."
                   style="flex:1; border:none; outline:none; font-size:14px; color:#374151; background:transparent; min-width:0;"
                   onfocus="this.parentElement.style.borderColor='#1d4ed8'; this.parentElement.style.boxShadow='0 0 0 3px rgba(29,78,216,0.1)'"
                   onblur="this.parentElement.style.borderColor='#d1d5db'; this.parentElement.style.boxShadow='0 1px 3px rgba(0,0,0,0.05)'">
            <button type="submit" style="padding:9px 22px; background:#1d4ed8; color:white; border:none; border-radius:7px; font-size:14px; font-weight:600; cursor:pointer; white-space:nowrap; flex-shrink:0;">Cari</button>
        </form>

        {{-- ③ STATISTIK PERSURATAN ─────────────────────────────── --}}
        <div>
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px;">
                <p style="font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.8px; margin:0;">Statistik Persuratan Bulan Ini</p>
                <span style="font-size:12px; color:#9ca3af;">Sinkronisasi otomatis: <span style="color:#16a34a; font-weight:600;">Aktif</span></span>
            </div>
            <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px;">

                {{-- Total Surat Resmi --}}
                <div class="stat-card blue" style="border-radius:12px;">
                    <div style="padding:22px 22px 18px 22px;">
                        <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:16px;">
                            <div class="stat-icon-wrap" style="width:46px; height:46px; border-radius:10px;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                            </div>
                            <span style="background:#dbeafe; color:#1e40af; font-size:11px; font-weight:600; padding:3px 9px; border-radius:5px;">Final</span>
                        </div>
                        <p style="font-size:38px; font-weight:800; color:#111827; margin:0 0 4px 0; line-height:1;">{{ $totalSurat }}</p>
                        <p style="font-size:13px; font-weight:600; color:#374151; margin:0 0 6px 0;">Total Surat Resmi</p>
                        <p style="font-size:12px; color:#9ca3af; margin:0;">Bulan {{ now()->translatedFormat('F Y') }}</p>
                    </div>
                </div>

                {{-- Surat Masuk --}}
                <div class="stat-card green" style="border-radius:12px;">
                    <div style="padding:22px 22px 18px 22px;">
                        <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:16px;">
                            <div class="stat-icon-wrap" style="width:46px; height:46px; border-radius:10px;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
                            </div>
                            <span style="background:#dcfce7; color:#15803d; font-size:11px; font-weight:600; padding:3px 9px; border-radius:5px;">● Masuk</span>
                        </div>
                        <p style="font-size:38px; font-weight:800; color:#111827; margin:0 0 4px 0; line-height:1;">{{ $totalMasuk }}</p>
                        <p style="font-size:13px; font-weight:600; color:#374151; margin:0 0 6px 0;">Surat Masuk</p>
                        <p style="font-size:12px; color:#9ca3af; margin:0;">Dari Dinsos &amp; Setprov</p>
                    </div>
                </div>

                {{-- Surat Keluar --}}
                <div class="stat-card orange" style="border-radius:12px;">
                    <div style="padding:22px 22px 18px 22px;">
                        <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:16px;">
                            <div class="stat-icon-wrap" style="width:46px; height:46px; border-radius:10px;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                            </div>
                            <span style="background:#ffedd5; color:#c2410c; font-size:11px; font-weight:600; padding:3px 9px; border-radius:5px;">● Keluar</span>
                        </div>
                        <p style="font-size:38px; font-weight:800; color:#111827; margin:0 0 4px 0; line-height:1;">{{ $totalKeluar }}</p>
                        <p style="font-size:13px; font-weight:600; color:#374151; margin:0 0 6px 0;">Surat Keluar</p>
                        <p style="font-size:12px; color:#9ca3af; margin:0;">Terkirim &amp; Berlaku</p>
                    </div>
                </div>

                {{-- Draft Surat --}}
                <div class="card" style="border-radius:12px;">
                    <div style="padding:22px 22px 18px 22px;">
                        <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:16px;">
                            <div style="width:46px; height:46px; background:#f1f5f9; border-radius:10px; display:flex; align-items:center; justify-content:center; color:#64748b;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                            </div>
                            <span style="background:#f1f5f9; color:#64748b; font-size:11px; font-weight:600; padding:3px 9px; border-radius:5px;">Belum Final</span>
                        </div>
                        <p style="font-size:38px; font-weight:800; color:#64748b; margin:0 0 4px 0; line-height:1;">{{ $totalDraft }}</p>
                        <p style="font-size:13px; font-weight:600; color:#374151; margin:0 0 6px 0;">Draft Surat</p>
                        <p style="font-size:12px; color:#9ca3af; margin:0;">
                            @if($totalDraft === 0)Tidak ada draft tertunda @else Perlu dilanjutkan @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ④ GRAFIK ──────────────────────────────────────────── --}}
        <div style="display:grid; grid-template-columns:1.6fr 1fr; gap:16px;">

            {{-- Tren Surat --}}
            <div class="card" style="border-radius:12px; padding:24px 26px;">
                <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:20px;">
                    <div>
                        <h4 style="font-size:15px; font-weight:700; color:#111827; margin:0 0 4px 0;">Tren Surat Masuk &amp; Keluar</h4>
                        <p style="font-size:12px; color:#9ca3af; margin:0;">6 bulan terakhir · hanya menghitung surat final</p>
                    </div>
                    <div style="display:flex; align-items:center; gap:16px; flex-shrink:0;">
                        <span style="display:flex; align-items:center; gap:6px; font-size:12px; color:#374151; font-weight:500;">
                            <span style="width:12px; height:12px; border-radius:3px; background:#22c55e; display:inline-block; flex-shrink:0;"></span>Surat Masuk
                        </span>
                        <span style="display:flex; align-items:center; gap:6px; font-size:12px; color:#374151; font-weight:500;">
                            <span style="width:12px; height:12px; border-radius:3px; background:#1d4ed8; display:inline-block; flex-shrink:0;"></span>Surat Keluar
                        </span>
                    </div>
                </div>
                <div style="position:relative; height:240px;">
                    <canvas id="chartTren"></canvas>
                </div>
            </div>

            {{-- Distribusi Jenis Surat --}}
            <div class="card" style="border-radius:12px; padding:24px 26px;">
                <div style="margin-bottom:18px;">
                    <div style="display:flex; align-items:center; justify-content:space-between;">
                        <h4 style="font-size:15px; font-weight:700; color:#111827; margin:0 0 4px 0;">Distribusi Jenis Surat</h4>
                        @if($totalSurat > 0)
                            <span style="font-size:12px; color:#6b7280; font-weight:500;">Total: {{ $totalSurat }}</span>
                        @endif
                    </div>
                    <p style="font-size:12px; color:#9ca3af; margin:0;">Berdasarkan klasifikasi naskah dinas final</p>
                </div>

                @if(count($distribusiData) > 0)
                    {{-- Donut chart dengan total di tengah --}}
                    <div style="position:relative; height:180px; display:flex; align-items:center; justify-content:center; margin-bottom:18px;">
                        <canvas id="chartDistribusi"></canvas>
                        <div style="position:absolute; text-align:center; pointer-events:none;">
                            <div style="font-size:28px; font-weight:800; color:#111827; line-height:1;">{{ $totalSurat }}</div>
                            <div style="font-size:11px; color:#9ca3af; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; margin-top:2px;">Naskah</div>
                        </div>
                    </div>
                    {{-- Legend chips --}}
                    @php $palette = ['#1d4ed8','#22c55e','#f59e0b','#7c3aed','#e11d48','#0284c7','#0d9488','#9333ea']; @endphp
                    <div style="display:flex; flex-wrap:wrap; gap:7px;">
                        @foreach($distribusiLabels as $idx => $label)
                            <div style="display:flex; align-items:center; gap:5px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; padding:4px 10px;">
                                <span style="width:8px; height:8px; border-radius:2px; background:{{ $palette[$idx % count($palette)] }}; display:inline-block; flex-shrink:0;"></span>
                                <span style="font-size:12px; color:#374151; font-weight:500;">{{ $label }}: {{ $distribusiData[$idx] }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="height:180px; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:10px;">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#e2e8f0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                        <p style="font-size:13px; color:#cbd5e1; margin:0;">Belum ada surat final</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- ⑤ KEPEGAWAIAN ──────────────────────────────────────── --}}
        <div>
            <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:14px;">
                <div>
                    <p style="font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.8px; margin:0 0 3px 0;">Kepegawaian — Perlu Diproses</p>
                    <p style="font-size:13px; color:#9ca3af; margin:0;">Antrean permohonan dinas pegawai yang membutuhkan approval pimpinan</p>
                </div>
                @if($cutiPending === 0 && $kenaikanPangkatPending === 0 && $gajiBerkalaPending === 0)
                    <span style="font-size:12px; color:#16a34a; font-weight:600; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:7px; padding:6px 14px; flex-shrink:0;">Kondisi Normal (Clear)</span>
                @endif
            </div>
            <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px;">

                {{-- Cuti --}}
                <div class="card" style="border-radius:12px; padding:22px 24px;">
                    <div style="display:flex; align-items:center; gap:16px;">
                        <div style="width:52px; height:52px; background:#fef2f2; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#e11d48" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </div>
                        <div style="flex:1; min-width:0;">
                            <p style="font-size:13px; color:#6b7280; font-weight:500; margin:0 0 4px 0;">Cuti Perlu Diproses</p>
                            <p style="font-size:36px; font-weight:800; color:#111827; margin:0 0 4px 0; line-height:1;">{{ $cutiPending }}</p>
                            <p style="font-size:12px; color:#9ca3af; margin:0;">Status: Diajukan / Verifikasi</p>
                        </div>
                        @if($cutiPending > 0)
                            <a href="{{ route('kepegawaian.cuti.index') }}" style="width:32px; height:32px; display:flex; align-items:center; justify-content:center; background:#fef2f2; border-radius:7px; flex-shrink:0; text-decoration:none;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#e11d48" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Kenaikan Pangkat --}}
                <div class="card" style="border-radius:12px; padding:22px 24px;">
                    <div style="display:flex; align-items:center; gap:16px;">
                        <div style="width:52px; height:52px; background:#fdf4ff; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#c026d3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                        </div>
                        <div style="flex:1; min-width:0;">
                            <p style="font-size:13px; color:#6b7280; font-weight:500; margin:0 0 4px 0;">Pangkat Perlu Diproses</p>
                            <p style="font-size:36px; font-weight:800; color:#111827; margin:0 0 4px 0; line-height:1;">{{ $kenaikanPangkatPending }}</p>
                            <p style="font-size:12px; color:#9ca3af; margin:0;">Status: Diajukan / Kekelehan</p>
                        </div>
                        @if($kenaikanPangkatPending > 0)
                            <a href="{{ route('kepegawaian.pangkat.index') }}" style="width:32px; height:32px; display:flex; align-items:center; justify-content:center; background:#fdf4ff; border-radius:7px; flex-shrink:0; text-decoration:none;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#c026d3" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- KGB --}}
                <div class="card" style="border-radius:12px; padding:22px 24px;">
                    <div style="display:flex; align-items:center; gap:16px;">
                        <div style="width:52px; height:52px; background:#f5f3ff; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                        </div>
                        <div style="flex:1; min-width:0;">
                            <p style="font-size:13px; color:#6b7280; font-weight:500; margin:0 0 4px 0;">KGB Perlu Diproses</p>
                            <p style="font-size:36px; font-weight:800; color:#111827; margin:0 0 4px 0; line-height:1;">{{ $gajiBerkalaPending }}</p>
                            <p style="font-size:12px; color:#9ca3af; margin:0;">Status: Verifikasi / Disetujui</p>
                        </div>
                        @if($gajiBerkalaPending > 0)
                            <a href="{{ route('kepegawaian.kgb.index') }}" style="width:32px; height:32px; display:flex; align-items:center; justify-content:center; background:#f5f3ff; border-radius:7px; flex-shrink:0; text-decoration:none;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ⑥ AKTIVITAS TERBARU ──────────────────────────────── --}}
        <div class="card" style="border-radius:12px; overflow:hidden;" x-data="{ tab: 'semua' }">

            {{-- Card Header --}}
            <div style="padding:20px 26px; border-bottom:1px solid #f3f4f6; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="width:38px; height:38px; background:#f8fafc; border:1px solid #e5e7eb; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:15px; font-weight:700; color:#111827; margin:0 0 2px 0;">Aktivitas Terbaru</h3>
                        <p style="font-size:12px; color:#9ca3af; margin:0;">Rekam jejak operasional &amp; audit pergerakan naskah sistem</p>
                    </div>
                </div>
                {{-- Tab filter (UI state via Alpine) --}}
                <div style="display:flex; gap:4px; background:#f3f4f6; border-radius:9px; padding:4px;">
                    @foreach(['semua' => 'Semua', 'masuk' => 'Surat Masuk', 'keluar' => 'Surat Keluar', 'draft' => 'Draft'] as $key => $label)
                        <button @click="tab = '{{ $key }}'"
                                :style="tab === '{{ $key }}'
                                    ? 'background:white; color:#111827; box-shadow:0 1px 3px rgba(0,0,0,0.1);'
                                    : 'background:transparent; color:#6b7280;'"
                                style="font-size:12px; font-weight:600; padding:6px 14px; border-radius:6px; border:none; cursor:pointer; transition:all 0.15s; line-height:1;">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Activity rows --}}
            @forelse($aktivitasTerbaru as $log)
                <div style="display:flex; align-items:center; gap:16px; padding:16px 26px; border-bottom:1px solid #f9fafb; transition:background 0.1s; cursor:default;"
                     onmouseover="this.style.background='#fafbff'" onmouseout="this.style.background='transparent'">

                    {{-- Icon --}}
                    @php
                        $iconBg = ['#eff6ff','#f0fdf4','#fffbeb','#fdf4ff','#fef2f2'];
                        $iconBorder = ['#bfdbfe','#bbf7d0','#fde68a','#e9d5ff','#fecaca'];
                        $iconColor = ['#2563eb','#16a34a','#d97706','#9333ea','#dc2626'];
                        $i = $loop->index % 5;
                    @endphp
                    <div style="flex-shrink:0; width:40px; height:40px; border-radius:10px;
                                background:{{ $iconBg[$i] }}; border:1px solid {{ $iconBorder[$i] }};
                                display:flex; align-items:center; justify-content:center;">
                        @if($loop->index % 3 === 0)
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="{{ $iconColor[$i] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        @elseif($loop->index % 3 === 1)
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="{{ $iconColor[$i] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        @else
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="{{ $iconColor[$i] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div style="flex:1; min-width:0;">
                        <p style="font-size:13.5px; color:#374151; margin:0 0 5px 0; line-height:1.5;">
                            <span style="font-weight:600; color:#111827;">{{ $log->user->name ?? 'Sistem' }}</span>
                            {{ $log->aktivitas ?? $log->description ?? 'melakukan aksi pada sistem' }}
                        </p>
                        <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                            <span style="font-size:12px; color:#9ca3af;">{{ $log->created_at?->diffForHumans() }}</span>
                            @if($log->action)
                                <span style="font-size:11px; color:#475569; background:#f3f4f6; border-radius:4px; padding:2px 8px; font-weight:500;">{{ $log->action }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Chevron --}}
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><polyline points="9 18 15 12 9 6"/></svg>
                </div>
            @empty
                <div style="padding:52px 26px; text-align:center;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#e5e7eb" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 12px; display:block;"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    <p style="color:#9ca3af; font-size:14px; margin:0;">Belum ada aktivitas tercatat</p>
                </div>
            @endforelse

            {{-- Footer --}}
            <div style="padding:14px 26px; text-align:center; background:#fafafa; border-top:1px solid #f3f4f6;">
                <a href="{{ route('pencarian.index') }}" style="font-size:13px; color:#1d4ed8; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:5px;">
                    Lihat Semua Riwayat Operasional (Audit Log)
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                </a>
            </div>
        </div>

        {{-- ⑦ FOOTER ─────────────────────────────────────────── --}}
        <div style="display:flex; align-items:center; justify-content:space-between; padding:6px 0 16px 0; flex-wrap:wrap; gap:10px; border-top:1px solid #f3f4f6;">
            <p style="font-size:12px; color:#9ca3af; margin:0;">
                © {{ date('Y') }} Pemerintah Provinsi Riau · Dinas Perhubungan. Hak Cipta Dilindungi.
            </p>
            <div style="display:flex; gap:18px; flex-wrap:wrap;">
                <a href="{{ route('pengguna.index') }}" style="font-size:12px; color:#9ca3af; text-decoration:none; transition:color 0.15s;" onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#9ca3af'">Panduan Pengguna</a>
                <a href="{{ route('pengaturan.index') }}" style="font-size:12px; color:#9ca3af; text-decoration:none; transition:color 0.15s;" onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#9ca3af'">Kebijakan Privasi</a>
                <a href="{{ route('pengaturan.index') }}" style="font-size:12px; color:#9ca3af; text-decoration:none; transition:color 0.15s;" onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#9ca3af'">Bantuan Teknis</a>
            </div>
        </div>

    </div>{{-- /dashboard wrapper --}}

    {{-- ══ CHART SCRIPTS ══════════════════════════════════════ --}}
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const Chart = window.Chart;
        if (!Chart) { console.warn('Chart.js belum dimuat.'); return; }

        const PALETTE = ['#1d4ed8','#22c55e','#f59e0b','#7c3aed','#e11d48','#0284c7','#0d9488','#9333ea'];

        const trendLabels = @json($trendLabels);
        const trendMasuk  = @json($trendMasuk);
        const trendKeluar = @json($trendKeluar);
        const distLabels  = @json($distribusiLabels);
        const distData    = @json($distribusiData);

        // ── Bar chart: Tren Surat ────────────────────────────────
        const ctxTren = document.getElementById('chartTren');
        if (ctxTren) {
            new Chart(ctxTren, {
                type: 'bar',
                data: {
                    labels: trendLabels,
                    datasets: [
                        {
                            label: 'Surat Masuk',
                            data: trendMasuk,
                            backgroundColor: 'rgba(34,197,94,0.75)',
                            borderColor: '#22c55e',
                            borderWidth: 1,
                            borderRadius: 5,
                            borderSkipped: false,
                        },
                        {
                            label: 'Surat Keluar',
                            data: trendKeluar,
                            backgroundColor: 'rgba(29,78,216,0.75)',
                            borderColor: '#1d4ed8',
                            borderWidth: 1,
                            borderRadius: 5,
                            borderSkipped: false,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            padding: 10,
                            callbacks: {
                                label: ctx => `  ${ctx.dataset.label}: ${ctx.raw} surat`
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 12 }, color: '#9ca3af' },
                            border: { display: false }
                        },
                        y: {
                            grid: { color: '#f3f4f6', drawBorder: false },
                            ticks: { font: { size: 12 }, color: '#9ca3af', precision: 0, stepSize: 1 },
                            border: { display: false },
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // ── Doughnut: Distribusi Jenis Surat ──────────────────────
        const ctxDist = document.getElementById('chartDistribusi');
        if (ctxDist && distData.length > 0) {
            new Chart(ctxDist, {
                type: 'doughnut',
                data: {
                    labels: distLabels,
                    datasets: [{
                        data: distData,
                        backgroundColor: PALETTE.slice(0, distData.length),
                        borderWidth: 3,
                        borderColor: '#ffffff',
                        hoverOffset: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '66%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            padding: 10,
                            callbacks: {
                                label: ctx => `  ${ctx.label}: ${ctx.raw} surat`
                            }
                        }
                    }
                }
            });
        }
    });
    </script>
    @endpush

</x-app-layout>
