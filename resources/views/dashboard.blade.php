<x-app-layout>
    <x-slot name="header">Dashboard Persuratan</x-slot>
    <x-slot name="breadcrumb">Dashboard Utama</x-slot>

    <div style="display:flex; flex-direction:column; gap:24px;">

        {{-- ① WELCOME BANNER ─────────────────────────────── --}}
        <div style="background:linear-gradient(135deg,#1a3560 0%,#1e4bb8 55%,#2563eb 100%);
                    border-radius:14px; padding:28px 34px;
                    display:flex; align-items:center; justify-content:space-between;
                    position:relative; overflow:hidden; min-height:118px;">
            <div style="position:absolute;top:-55px;right:250px;width:210px;height:210px;border-radius:50%;background:rgba(255,255,255,0.05);pointer-events:none;"></div>
            <div style="position:absolute;bottom:-70px;right:80px;width:260px;height:260px;border-radius:50%;background:rgba(255,255,255,0.04);pointer-events:none;"></div>

            <div style="position:relative;z-index:1;">
                <div style="display:inline-flex;align-items:center;gap:7px;background:rgba(255,255,255,0.14);border:1px solid rgba(255,255,255,0.2);border-radius:20px;padding:4px 12px;margin-bottom:10px;">
                    <span style="width:6px;height:6px;background:#4ade80;border-radius:50%;display:inline-block;flex-shrink:0;"></span>
                    <span style="font-size:11px;color:rgba(255,255,255,0.9);font-weight:600;letter-spacing:0.5px;text-transform:uppercase;">Portal Dispersip &amp; Arsip Resmi Riau</span>
                </div>
                <h2 style="color:white;font-size:24px;font-weight:700;margin:0 0 5px 0;line-height:1.3;">
                    Selamat datang, {{ Auth::user()->name }}
                </h2>
                <p style="color:rgba(255,255,255,0.60);font-size:13px;margin:0;line-height:1.5;">
                    Sistem Informasi Persuratan terpadu Dinas Perhubungan Provinsi Riau.
                </p>
            </div>

            {{-- Jam Realtime --}}
            <div style="position:relative;z-index:1;flex-shrink:0;text-align:right;">
                <div style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);border-radius:12px;padding:14px 22px;min-width:170px;">
                    <div id="jam-tanggal" style="font-size:11px;color:rgba(255,255,255,0.65);font-weight:500;margin-bottom:3px;letter-spacing:0.2px;">Memuat...</div>
                    <div id="jam-waktu" style="font-size:22px;font-weight:700;color:white;letter-spacing:1.5px;font-variant-numeric:tabular-nums;">--:--:--</div>
                    <div style="font-size:10px;color:rgba(255,255,255,0.5);margin-top:2px;font-weight:500;letter-spacing:0.5px;">WIB</div>
                </div>
            </div>
        </div>

        {{-- ② SEARCH ─────────────────────────────────────── --}}
        <form action="{{ route('pencarian.index') }}" method="GET"
              style="display:flex;align-items:center;gap:12px;background:white;border:1px solid #d1d5db;border-radius:10px;padding:11px 18px;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="q"
                   placeholder="Cari nomor surat, perihal, instansi pengirim, atau tujuan naskah dinas..."
                   style="flex:1;border:none;outline:none;font-size:14px;color:#374151;background:transparent;min-width:0;"
                   onfocus="this.parentElement.style.borderColor='#1d4ed8';this.parentElement.style.boxShadow='0 0 0 3px rgba(29,78,216,0.1)'"
                   onblur="this.parentElement.style.borderColor='#d1d5db';this.parentElement.style.boxShadow='0 1px 3px rgba(0,0,0,0.05)'">
            <button type="submit" style="padding:8px 20px;background:#1d4ed8;color:white;border:none;border-radius:7px;font-size:13.5px;font-weight:600;cursor:pointer;white-space:nowrap;flex-shrink:0;">Cari</button>
        </form>

        {{-- ③ STATISTIK ────────────────────────────────────── --}}
        <div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                <p style="font-size:11.5px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.8px;margin:0;">Statistik Persuratan Bulan Ini</p>
                <span style="font-size:12px;color:#9ca3af;">Sinkronisasi otomatis: <span style="color:#16a34a;font-weight:600;">Aktif</span></span>
            </div>
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;">

                {{-- Total --}}
                <div class="stat-card blue" style="border-radius:11px;">
                    <div style="padding:20px 20px 16px 20px;">
                        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
                            <div class="stat-icon-wrap" style="width:44px;height:44px;border-radius:10px;">
                                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                            </div>
                            <span style="background:#dbeafe;color:#1e40af;font-size:10.5px;font-weight:600;padding:2px 8px;border-radius:5px;">Final</span>
                        </div>
                        <p style="font-size:36px;font-weight:800;color:#111827;margin:0 0 3px 0;line-height:1;">{{ $totalSurat }}</p>
                        <p style="font-size:12.5px;font-weight:600;color:#374151;margin:0 0 4px 0;">Total Surat Resmi</p>
                        <p style="font-size:11.5px;color:#9ca3af;margin:0;">Bulan {{ now()->translatedFormat('F Y') }}</p>
                    </div>
                </div>

                {{-- Masuk --}}
                <div class="stat-card green" style="border-radius:11px;">
                    <div style="padding:20px 20px 16px 20px;">
                        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
                            <div class="stat-icon-wrap" style="width:44px;height:44px;border-radius:10px;">
                                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
                            </div>
                            <span style="background:#dcfce7;color:#15803d;font-size:10.5px;font-weight:600;padding:2px 8px;border-radius:5px;">● Masuk</span>
                        </div>
                        <p style="font-size:36px;font-weight:800;color:#111827;margin:0 0 3px 0;line-height:1;">{{ $totalMasuk }}</p>
                        <p style="font-size:12.5px;font-weight:600;color:#374151;margin:0 0 4px 0;">Surat Masuk</p>
                        <p style="font-size:11.5px;color:#9ca3af;margin:0;">Dari Dinsos &amp; Setprov</p>
                    </div>
                </div>

                {{-- Keluar --}}
                <div class="stat-card orange" style="border-radius:11px;">
                    <div style="padding:20px 20px 16px 20px;">
                        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
                            <div class="stat-icon-wrap" style="width:44px;height:44px;border-radius:10px;">
                                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                            </div>
                            <span style="background:#ffedd5;color:#c2410c;font-size:10.5px;font-weight:600;padding:2px 8px;border-radius:5px;">● Keluar</span>
                        </div>
                        <p style="font-size:36px;font-weight:800;color:#111827;margin:0 0 3px 0;line-height:1;">{{ $totalKeluar }}</p>
                        <p style="font-size:12.5px;font-weight:600;color:#374151;margin:0 0 4px 0;">Surat Keluar</p>
                        <p style="font-size:11.5px;color:#9ca3af;margin:0;">Terkirim &amp; Berlaku</p>
                    </div>
                </div>

                {{-- Draft --}}
                <div class="card" style="border-radius:11px;">
                    <div style="padding:20px 20px 16px 20px;">
                        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
                            <div style="width:44px;height:44px;background:#f1f5f9;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#64748b;">
                                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                            </div>
                            <span style="background:#f1f5f9;color:#64748b;font-size:10.5px;font-weight:600;padding:2px 8px;border-radius:5px;">Belum Final</span>
                        </div>
                        <p style="font-size:36px;font-weight:800;color:#64748b;margin:0 0 3px 0;line-height:1;">{{ $totalDraft }}</p>
                        <p style="font-size:12.5px;font-weight:600;color:#374151;margin:0 0 4px 0;">Draft Surat</p>
                        <p style="font-size:11.5px;color:#9ca3af;margin:0;">
                            @if($totalDraft === 0)
                                Tidak ada draft tertunda
                            @else
                                Perlu dilanjutkan
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ④ ROW GRAFIK 1 — Tren (65%) + Distribusi Jenis Surat (35%) ── --}}
        <div style="display:grid;grid-template-columns:1.86fr 1fr;gap:14px;align-items:stretch;">

            {{-- Tren Surat – Line Chart --}}
            <div class="card" style="border-radius:11px;padding:20px 24px;display:flex;flex-direction:column;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;gap:10px;flex-wrap:wrap;">
                    <div>
                        <h4 style="font-size:14px;font-weight:700;color:#111827;margin:0 0 3px 0;">Tren Surat Masuk &amp; Keluar</h4>
                        <p style="font-size:11.5px;color:#9ca3af;margin:0;">6 bulan terakhir · hanya menghitung surat final</p>
                    </div>
                    <div style="display:flex;align-items:center;gap:14px;flex-shrink:0;">
                        <span style="display:flex;align-items:center;gap:5px;font-size:12px;color:#374151;font-weight:500;">
                            <span style="width:20px;height:2.5px;background:#22c55e;border-radius:2px;display:inline-block;"></span>Surat Masuk
                        </span>
                        <span style="display:flex;align-items:center;gap:5px;font-size:12px;color:#374151;font-weight:500;">
                            <span style="width:20px;height:2.5px;background:#1d4ed8;border-radius:2px;display:inline-block;"></span>Surat Keluar
                        </span>
                    </div>
                </div>
                <div style="flex:1;position:relative;min-height:190px;">
                    <canvas id="chartTren"></canvas>
                </div>
            </div>

            {{-- Distribusi Jenis Surat – Donut --}}
            <div class="card" style="border-radius:11px;padding:20px 22px;display:flex;flex-direction:column;">
                <div style="margin-bottom:14px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <h4 style="font-size:14px;font-weight:700;color:#111827;margin:0 0 3px 0;">Distribusi Jenis Surat</h4>
                        @if($totalSurat > 0)
                            <span style="font-size:12px;color:#6b7280;font-weight:500;">Total: {{ $totalSurat }}</span>
                        @endif
                    </div>
                    <p style="font-size:11.5px;color:#9ca3af;margin:0;">Berdasarkan jenis naskah dinas final</p>
                </div>
                @if(count($distribusiData) > 0)
                    <div style="position:relative;width:150px;height:150px;margin:0 auto 14px auto;flex-shrink:0;">
                        <canvas id="chartDistribusi"></canvas>
                        <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;pointer-events:none;">
                            <div style="font-size:26px;font-weight:800;color:#111827;line-height:1;">{{ $totalSurat }}</div>
                            <div style="font-size:10px;color:#9ca3af;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;margin-top:2px;">Naskah</div>
                        </div>
                    </div>
                    @php $palDist=['#1d4ed8','#22c55e','#f59e0b','#7c3aed','#e11d48','#0284c7','#0d9488','#9333ea']; @endphp
                    <div style="display:flex;flex-wrap:wrap;gap:6px;margin-top:auto;">
                        @foreach($distribusiLabels as $idx => $label)
                            <div style="display:flex;align-items:center;gap:5px;background:#f8fafc;border:1px solid #e5e7eb;border-radius:6px;padding:4px 9px;">
                                <span style="width:8px;height:8px;border-radius:2px;background:{{ $palDist[$idx % count($palDist)] }};display:inline-block;flex-shrink:0;"></span>
                                <span style="font-size:11.5px;color:#374151;font-weight:500;">{{ $label }}: {{ $distribusiData[$idx] }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;background:#fafafa;border-radius:8px;border:1px dashed #e5e7eb;min-height:150px;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>
                        <p style="font-size:12.5px;color:#9ca3af;margin:0;">Belum ada surat final.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- ⑤ ROW GRAFIK 2 — Klasifikasi (65%) + Kalender (35%) ────── --}}
        <div style="display:grid;grid-template-columns:1.86fr 1fr;gap:14px;align-items:stretch;">

            {{-- Klasifikasi – Horizontal Bar --}}
            <div class="card" style="border-radius:11px;padding:20px 24px;display:flex;flex-direction:column;">
                <div style="margin-bottom:14px;">
                    <h4 style="font-size:14px;font-weight:700;color:#111827;margin:0 0 3px 0;">Surat Berdasarkan Klasifikasi</h4>
                    <p style="font-size:11.5px;color:#9ca3af;margin:0;">Top klasifikasi surat · hanya surat final</p>
                </div>
                @if(count($klasifikasiData) > 0)
                    <div style="flex:1;position:relative;min-height:170px;">
                        <canvas id="chartKlasifikasi"></canvas>
                    </div>
                @else
                    <div style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;background:#fafafa;border-radius:8px;border:1px dashed #e5e7eb;min-height:170px;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 9h6M9 12h6M9 15h4"/></svg>
                        <p style="font-size:12.5px;color:#9ca3af;margin:0;">Belum ada data klasifikasi surat.</p>
                    </div>
                @endif
            </div>

            {{-- Kalender --}}
            <div class="card" style="border-radius:11px;padding:20px 22px;display:flex;flex-direction:column;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                    <div>
                        <h4 style="font-size:14px;font-weight:700;color:#111827;margin:0 0 2px 0;" id="kal-judul">--</h4>
                        <p style="font-size:11.5px;color:#9ca3af;margin:0;">Kalender bulan berjalan</p>
                    </div>
                    <div style="width:34px;height:34px;background:#eff6ff;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                </div>
                {{-- Header hari --}}
                <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:2px;margin-bottom:4px;">
                    @foreach(['Sen','Sel','Rab','Kam','Jum','Sab','Min'] as $h)
                        <div style="text-align:center;font-size:10.5px;font-weight:600;color:#9ca3af;padding:3px 0;">{{ $h }}</div>
                    @endforeach
                </div>
                {{-- Grid tanggal (JS) --}}
                <div id="kal-grid" style="display:grid;grid-template-columns:repeat(7,1fr);gap:2px;flex:1;"></div>
            </div>
        </div>

        {{-- ⑥ KEPEGAWAIAN ─────────────────────────────────── --}}
        <div>
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;flex-wrap:wrap;gap:8px;">
                <div>
                    <p style="font-size:11.5px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.8px;margin:0 0 2px 0;">Kepegawaian — Perlu Diproses</p>
                    <p style="font-size:12.5px;color:#9ca3af;margin:0;">Antrean permohonan dinas pegawai yang membutuhkan approval pimpinan</p>
                </div>
                @if($cutiPending===0 && $kenaikanPangkatPending===0 && $gajiBerkalaPending===0)
                    <span style="font-size:12px;color:#16a34a;font-weight:600;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:7px;padding:5px 12px;flex-shrink:0;">Kondisi Normal (Clear)</span>
                @endif
            </div>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;">

                <div class="card" style="border-radius:11px;padding:18px 20px;">
                    <div style="display:flex;align-items:center;gap:14px;">
                        <div style="width:48px;height:48px;background:#fef2f2;border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e11d48" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <p style="font-size:12px;color:#6b7280;font-weight:500;margin:0 0 3px 0;">Cuti Perlu Diproses</p>
                            <p style="font-size:34px;font-weight:800;color:#111827;margin:0 0 3px 0;line-height:1;">{{ $cutiPending }}</p>
                            <p style="font-size:11.5px;color:#9ca3af;margin:0;">Status: Diajukan / Verifikasi</p>
                        </div>
                        @if($cutiPending > 0)
                            <a href="{{ route('kepegawaian.cuti.index') }}" style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;background:#fef2f2;border-radius:7px;flex-shrink:0;text-decoration:none;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#e11d48" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="card" style="border-radius:11px;padding:18px 20px;">
                    <div style="display:flex;align-items:center;gap:14px;">
                        <div style="width:48px;height:48px;background:#fdf4ff;border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#c026d3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <p style="font-size:12px;color:#6b7280;font-weight:500;margin:0 0 3px 0;">Pangkat Perlu Diproses</p>
                            <p style="font-size:34px;font-weight:800;color:#111827;margin:0 0 3px 0;line-height:1;">{{ $kenaikanPangkatPending }}</p>
                            <p style="font-size:11.5px;color:#9ca3af;margin:0;">Status: Diajukan / Diproses</p>
                        </div>
                        @if($kenaikanPangkatPending > 0)
                            <a href="{{ route('kepegawaian.pangkat.index') }}" style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;background:#fdf4ff;border-radius:7px;flex-shrink:0;text-decoration:none;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#c026d3" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="card" style="border-radius:11px;padding:18px 20px;">
                    <div style="display:flex;align-items:center;gap:14px;">
                        <div style="width:48px;height:48px;background:#f5f3ff;border-radius:11px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <p style="font-size:12px;color:#6b7280;font-weight:500;margin:0 0 3px 0;">KGB Perlu Diproses</p>
                            <p style="font-size:34px;font-weight:800;color:#111827;margin:0 0 3px 0;line-height:1;">{{ $gajiBerkalaPending }}</p>
                            <p style="font-size:11.5px;color:#9ca3af;margin:0;">Status: Verifikasi / Disetujui</p>
                        </div>
                        @if($gajiBerkalaPending > 0)
                            <a href="{{ route('kepegawaian.kgb.index') }}" style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;background:#f5f3ff;border-radius:7px;flex-shrink:0;text-decoration:none;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ⑦ AKTIVITAS TERBARU ──────────────────────────── --}}
        <div class="card" style="border-radius:11px;overflow:hidden;" x-data="{ tab:'semua' }">
            <div style="padding:16px 24px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:36px;height:36px;background:#f8fafc;border:1px solid #e5e7eb;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:14px;font-weight:700;color:#111827;margin:0 0 1px 0;">Aktivitas Terbaru</h3>
                        <p style="font-size:11.5px;color:#9ca3af;margin:0;">Rekam jejak operasional &amp; audit pergerakan naskah sistem</p>
                    </div>
                </div>
                <div style="display:flex;gap:4px;background:#f3f4f6;border-radius:8px;padding:3px;">
                    @foreach(['semua'=>'Semua','masuk'=>'Surat Masuk','keluar'=>'Surat Keluar','draft'=>'Draft'] as $k=>$lbl)
                        <button @click="tab='{{ $k }}'"
                                :style="tab==='{{ $k }}' ? 'background:white;color:#111827;box-shadow:0 1px 3px rgba(0,0,0,0.1);' : 'background:transparent;color:#6b7280;'"
                                style="font-size:12px;font-weight:600;padding:6px 13px;border-radius:6px;border:none;cursor:pointer;transition:all 0.15s;">
                            {{ $lbl }}
                        </button>
                    @endforeach
                </div>
            </div>

            @forelse($aktivitasTerbaru as $log)
                @php
                    $cc=[['#eff6ff','#bfdbfe','#2563eb'],['#f0fdf4','#bbf7d0','#16a34a'],['#fffbeb','#fde68a','#d97706'],['#fdf4ff','#e9d5ff','#9333ea'],['#fef2f2','#fecaca','#dc2626']];
                    $c=$cc[$loop->index % 5];
                @endphp
                <div style="display:flex;align-items:center;gap:14px;padding:14px 24px;border-bottom:1px solid #f9fafb;transition:background 0.1s;"
                     onmouseover="this.style.background='#fafbff'" onmouseout="this.style.background='transparent'">
                    <div style="flex-shrink:0;width:36px;height:36px;border-radius:9px;background:{{ $c[0] }};border:1px solid {{ $c[1] }};display:flex;align-items:center;justify-content:center;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="{{ $c[2] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            @if($loop->index % 3 === 0)
                                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                            @elseif($loop->index % 3 === 1)
                                <polyline points="20 6 9 17 4 12"/>
                            @else
                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                            @endif
                        </svg>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p style="font-size:13px;color:#374151;margin:0 0 4px 0;line-height:1.4;">
                            <span style="font-weight:700;color:#111827;">{{ $log->user->name ?? 'Sistem' }}</span>
                            {{ $log->aktivitas ?? $log->description ?? 'melakukan aksi pada sistem' }}
                        </p>
                        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                            <span style="font-size:11.5px;color:#9ca3af;">{{ $log->created_at?->diffForHumans() }}</span>
                            @if($log->action)
                                <span style="font-size:11px;color:#475569;background:#f3f4f6;border-radius:4px;padding:1px 7px;font-weight:500;">{{ $log->action }}</span>
                            @endif
                        </div>
                    </div>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><polyline points="9 18 15 12 9 6"/></svg>
                </div>
            @empty
                <div style="padding:44px 24px;text-align:center;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#e5e7eb" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 10px;display:block;"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    <p style="color:#9ca3af;font-size:13.5px;margin:0;">Belum ada aktivitas tercatat</p>
                </div>
            @endforelse

            <div style="padding:12px 24px;text-align:center;background:#fafafa;border-top:1px solid #f3f4f6;">
                <a href="{{ route('pencarian.index') }}" style="font-size:13px;color:#1d4ed8;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:5px;">
                    Lihat Semua Riwayat Operasional (Audit Log)
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                </a>
            </div>
        </div>

        {{-- ⑧ FOOTER ──────────────────────────────────────── --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding:4px 0 14px 0;flex-wrap:wrap;gap:8px;border-top:1px solid #f3f4f6;">
            <p style="font-size:12px;color:#9ca3af;margin:0;">© {{ date('Y') }} Pemerintah Provinsi Riau · Dinas Perhubungan. Hak Cipta Dilindungi.</p>
            <div style="display:flex;gap:16px;flex-wrap:wrap;">
                <a href="{{ route('pengguna.index') }}" style="font-size:12px;color:#9ca3af;text-decoration:none;" onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#9ca3af'">Panduan Pengguna</a>
                <a href="{{ route('pengaturan.index') }}" style="font-size:12px;color:#9ca3af;text-decoration:none;" onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#9ca3af'">Kebijakan Privasi</a>
                <a href="{{ route('pengaturan.index') }}" style="font-size:12px;color:#9ca3af;text-decoration:none;" onmouseover="this.style.color='#374151'" onmouseout="this.style.color='#9ca3af'">Bantuan Teknis</a>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        /* ── JAM REALTIME ──────────────────────────────────── */
        const HARI_ID  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const BULAN_ID = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        const elTgl = document.getElementById('jam-tanggal');
        const elJam = document.getElementById('jam-waktu');
        function updateJam() {
            const n = new Date();
            elJam.textContent = `${String(n.getHours()).padStart(2,'0')}:${String(n.getMinutes()).padStart(2,'0')}:${String(n.getSeconds()).padStart(2,'0')}`;
            elTgl.textContent = `${HARI_ID[n.getDay()]}, ${n.getDate()} ${BULAN_ID[n.getMonth()]} ${n.getFullYear()}`;
        }
        updateJam(); setInterval(updateJam, 1000);

        /* ── KALENDER ──────────────────────────────────────── */
        (function () {
            const now = new Date();
            const tahun = now.getFullYear(), bulan = now.getMonth(), hari = now.getDate();
            const judulEl = document.getElementById('kal-judul');
            const gridEl  = document.getElementById('kal-grid');
            if (!judulEl || !gridEl) return;
            judulEl.textContent = `${BULAN_ID[bulan]} ${tahun}`;
            const firstDay    = new Date(tahun, bulan, 1).getDay();
            const startOffset = firstDay === 0 ? 6 : firstDay - 1;
            const totalHari   = new Date(tahun, bulan + 1, 0).getDate();
            let html = '';
            for (let i = 0; i < startOffset; i++) html += '<div></div>';
            for (let d = 1; d <= totalHari; d++) {
                const isToday  = d === hari;
                const isSunday = new Date(tahun, bulan, d).getDay() === 0;
                const isSaturday = new Date(tahun, bulan, d).getDay() === 6;
                const color = isToday ? 'white' : (isSunday || isSaturday ? '#ef4444' : '#374151');
                const bg    = isToday ? '#1d4ed8' : 'transparent';
                const fw    = isToday ? '700' : '400';
                html += `<div style="text-align:center;padding:5px 2px;border-radius:6px;background:${bg};color:${color};font-size:12.5px;font-weight:${fw};cursor:default;">${d}</div>`;
            }
            gridEl.innerHTML = html;
        })();

        /* ── CHART.JS ──────────────────────────────────────── */
        const Chart = window.Chart;
        if (!Chart) return;

        const PALETTE   = ['#1d4ed8','#22c55e','#f59e0b','#7c3aed','#e11d48','#0284c7','#0d9488','#9333ea'];
        const trendLabels  = @json($trendLabels);
        const trendMasuk   = @json($trendMasuk);
        const trendKeluar  = @json($trendKeluar);
        const distLabels   = @json($distribusiLabels);
        const distData     = @json($distribusiData);
        const klasifLabels = @json($klasifikasiLabels);
        const klasifData   = @json($klasifikasiData);

        /* 1. Line Chart – Tren */
        const ctxTren = document.getElementById('chartTren');
        if (ctxTren) {
            new Chart(ctxTren, {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [
                        { label:'Surat Masuk',  data:trendMasuk,  borderColor:'#22c55e', backgroundColor:'rgba(34,197,94,0.07)',  borderWidth:2.5, pointBackgroundColor:'#22c55e', pointRadius:4, pointHoverRadius:6, tension:0.35, fill:true },
                        { label:'Surat Keluar', data:trendKeluar, borderColor:'#1d4ed8', backgroundColor:'rgba(29,78,216,0.07)',  borderWidth:2.5, pointBackgroundColor:'#1d4ed8', pointRadius:4, pointHoverRadius:6, tension:0.35, fill:true }
                    ]
                },
                options: {
                    responsive:true, maintainAspectRatio:false,
                    interaction:{ mode:'index', intersect:false },
                    plugins:{
                        legend:{ display:false },
                        tooltip:{ padding:10, callbacks:{ label: ctx => `  ${ctx.dataset.label}: ${ctx.raw} surat` } }
                    },
                    scales:{
                        x:{ grid:{display:false}, ticks:{font:{size:12},color:'#9ca3af'}, border:{display:false} },
                        y:{ grid:{color:'#f3f4f6'}, ticks:{font:{size:12},color:'#9ca3af',precision:0,stepSize:1}, border:{display:false}, beginAtZero:true }
                    }
                }
            });
        }

        /* 2. Horizontal Bar – Klasifikasi */
        const ctxKlasif = document.getElementById('chartKlasifikasi');
        if (ctxKlasif && klasifData.length > 0) {
            new Chart(ctxKlasif, {
                type: 'bar',
                data: {
                    labels: klasifLabels,
                    datasets:[{ label:'Jumlah Surat', data:klasifData, backgroundColor:PALETTE.slice(0,klasifData.length), borderRadius:4, borderSkipped:false }]
                },
                options: {
                    indexAxis:'y', responsive:true, maintainAspectRatio:false,
                    plugins:{
                        legend:{display:false},
                        tooltip:{ padding:10, callbacks:{ label: ctx => `  ${ctx.raw} surat` } }
                    },
                    scales:{
                        x:{ grid:{color:'#f3f4f6'}, ticks:{font:{size:12},color:'#9ca3af',precision:0,stepSize:1}, border:{display:false}, beginAtZero:true },
                        y:{ grid:{display:false}, ticks:{font:{size:12},color:'#374151'}, border:{display:false} }
                    }
                }
            });
        }

        /* 3. Doughnut – Distribusi */
        const ctxDist = document.getElementById('chartDistribusi');
        if (ctxDist && distData.length > 0) {
            new Chart(ctxDist, {
                type:'doughnut',
                data:{ labels:distLabels, datasets:[{ data:distData, backgroundColor:PALETTE.slice(0,distData.length), borderWidth:3, borderColor:'#fff', hoverOffset:5 }] },
                options:{
                    responsive:true, maintainAspectRatio:false, cutout:'66%',
                    plugins:{ legend:{display:false}, tooltip:{ padding:10, callbacks:{ label: ctx => `  ${ctx.label}: ${ctx.raw} surat` } } }
                }
            });
        }
    });
    </script>
    @endpush

</x-app-layout>
