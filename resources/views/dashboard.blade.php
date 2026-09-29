<x-app-layout>
    <x-slot name="header">Dashboard Persuratan</x-slot>
    <x-slot name="breadcrumb">Dashboard Utama</x-slot>

    <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- 1. WELCOME PANEL --}}
        <div style="background-color:#1e3a8a; border-radius:10px; padding:24px 28px; display:flex; align-items:center; justify-content:space-between; color:white; box-shadow:0 2px 4px rgba(0,0,0,0.05);">
            <div>
                <h2 style="margin:0 0 4px 0; font-size:20px; font-weight:700;">Selamat datang, {{ Auth::user()->name ?? 'Administrator' }}</h2>
                <p style="margin:0; font-size:13px; color:#bfdbfe;">Sistem Informasi Persuratan Dinas Perhubungan Provinsi Riau</p>
            </div>
            <div style="text-align:right;">
                <div id="jam-waktu" style="font-size:20px; font-weight:700; font-variant-numeric:tabular-nums; letter-spacing:1px;">--:--:--</div>
                <div id="jam-tanggal" style="font-size:12px; color:#bfdbfe; font-weight:500; margin-top:2px;">Memuat...</div>
            </div>
        </div>

        {{-- 2. SEARCH --}}
        <form action="{{ route('pencarian.index') }}" method="GET" style="display:flex; gap:10px; background:white; padding:10px; border-radius:8px; border:1px solid #e2e8f0; box-shadow:0 1px 2px rgba(0,0,0,0.02);">
            <div style="display:flex; align-items:center; padding:0 10px; color:#94a3b8;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </div>
            <input type="text" name="q" placeholder="Cari nomor surat, perihal, instansi pengirim, atau tujuan naskah dinas..."
                   style="flex:1; border:none; outline:none; font-size:14px; color:#334155; background:transparent;">
            <button type="submit" style="padding:8px 20px; background:#1d4ed8; color:white; border:none; border-radius:6px; font-size:13px; font-weight:600; cursor:pointer;" onmouseover="this.style.background='#1e40af'" onmouseout="this.style.background='#1d4ed8'">Cari</button>
        </form>

        {{-- 3. STATISTIK (4 CARDS) --}}
        <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:16px;">
            <div style="background:white; border-radius:8px; border:1px solid #e2e8f0; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,0.02); display:flex; flex-direction:column; gap:12px;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div style="background:#eff6ff; color:#1d4ed8; width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                    </div>
                </div>
                <div>
                    <div style="font-size:28px; font-weight:800; color:#1e293b; line-height:1;">{{ $totalSurat }}</div>
                    <div style="font-size:12.5px; font-weight:600; color:#64748b; margin-top:4px;">Total Surat Resmi</div>
                </div>
            </div>

            <div style="background:white; border-radius:8px; border:1px solid #e2e8f0; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,0.02); display:flex; flex-direction:column; gap:12px;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div style="background:#f0fdf4; color:#16a34a; width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
                    </div>
                </div>
                <div>
                    <div style="font-size:28px; font-weight:800; color:#1e293b; line-height:1;">{{ $totalMasuk }}</div>
                    <div style="font-size:12.5px; font-weight:600; color:#64748b; margin-top:4px;">Surat Masuk</div>
                </div>
            </div>

            <div style="background:white; border-radius:8px; border:1px solid #e2e8f0; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,0.02); display:flex; flex-direction:column; gap:12px;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div style="background:#fff7ed; color:#ea580c; width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    </div>
                </div>
                <div>
                    <div style="font-size:28px; font-weight:800; color:#1e293b; line-height:1;">{{ $totalKeluar }}</div>
                    <div style="font-size:12.5px; font-weight:600; color:#64748b; margin-top:4px;">Surat Keluar</div>
                </div>
            </div>

            <div style="background:white; border-radius:8px; border:1px solid #e2e8f0; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,0.02); display:flex; flex-direction:column; gap:12px;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div style="background:#f1f5f9; color:#64748b; width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                    </div>
                </div>
                <div>
                    <div style="font-size:28px; font-weight:800; color:#1e293b; line-height:1;">{{ $totalDraft }}</div>
                    <div style="font-size:12.5px; font-weight:600; color:#64748b; margin-top:4px;">Draft Surat</div>
                </div>
            </div>
        </div>

        {{-- 4. GRAFIK UTAMA & DISTRIBUSI --}}
        <div style="display:grid; grid-template-columns:1.8fr 1fr; gap:16px;">
            <div style="background:white; border-radius:10px; border:1px solid #e2e8f0; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,0.02); display:flex; flex-direction:column;">
                <h3 style="margin:0 0 16px; font-size:14.5px; font-weight:700; color:#1e293b;">Tren Surat Masuk &amp; Keluar</h3>
                <div style="flex:1; position:relative; min-height:280px;">
                    <canvas id="chartTren"></canvas>
                </div>
            </div>
            
            <div style="background:white; border-radius:10px; border:1px solid #e2e8f0; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,0.02); display:flex; flex-direction:column;">
                <h3 style="margin:0 0 16px; font-size:14.5px; font-weight:700; color:#1e293b;">Distribusi Jenis Surat</h3>
                @if(count($distribusiData) > 0)
                    <div style="position:relative; width:100%; max-width:200px; height:200px; margin:0 auto 20px;">
                        <canvas id="chartDistribusi"></canvas>
                    </div>
                    @php $palDist = ['#1d4ed8','#22c55e','#ea580c','#7c3aed','#0ea5e9','#e11d48','#0d9488']; @endphp
                    <div style="display:flex; flex-direction:column; gap:8px;">
                        @foreach($distribusiLabels as $idx => $label)
                            <div style="display:flex; align-items:center; justify-content:space-between; font-size:12.5px; color:#475569;">
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <span style="width:8px; height:8px; border-radius:2px; background:{{ $palDist[$idx % count($palDist)] }};"></span>
                                    <span>{{ $label }}</span>
                                </div>
                                <span style="font-weight:600;">{{ $distribusiData[$idx] }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="flex:1; display:flex; align-items:center; justify-content:center; color:#94a3b8; font-size:13px;">Belum ada data final.</div>
                @endif
            </div>
        </div>

        {{-- 5. KLASIFIKASI & AGENDA --}}
        <div style="display:grid; grid-template-columns:1.8fr 1fr; gap:16px;">
            <div style="background:white; border-radius:10px; border:1px solid #e2e8f0; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,0.02); display:flex; flex-direction:column;">
                <h3 style="margin:0 0 16px; font-size:14.5px; font-weight:700; color:#1e293b;">Surat Berdasarkan Klasifikasi</h3>
                @if(count($klasifikasiData) > 0)
                    <div style="flex:1; position:relative; min-height:180px;">
                        <canvas id="chartKlasifikasi"></canvas>
                    </div>
                @else
                    <div style="flex:1; display:flex; align-items:center; justify-content:center; color:#94a3b8; font-size:13px;">Belum ada data klasifikasi.</div>
                @endif
            </div>

            <div style="background:white; border-radius:10px; border:1px solid #e2e8f0; padding:20px; box-shadow:0 1px 3px rgba(0,0,0,0.02); display:flex; flex-direction:column;">
                <h3 style="margin:0 0 16px; font-size:14.5px; font-weight:700; color:#1e293b;">Agenda Hari Ini</h3>
                <div style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; background:#f8fafc; border:1px dashed #cbd5e1; border-radius:8px; padding:20px;">
                    <span style="font-size:48px; font-weight:800; color:#1e3a8a; line-height:1;">{{ date('d') }}</span>
                    <span style="font-size:15px; font-weight:600; color:#64748b; margin-top:8px;">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</span>
                    <span style="font-size:13px; color:#94a3b8; margin-top:4px;">{{ \Carbon\Carbon::now()->translatedFormat('l') }}</span>
                </div>
            </div>
        </div>

        {{-- 6 & 7. KEPEGAWAIAN & EARLY WARNING --}}
        <div style="display:grid; grid-template-columns:1fr 1.5fr; gap:16px;">
            <div style="background:white; border-radius:10px; border:1px solid #e2e8f0; padding:0; box-shadow:0 1px 3px rgba(0,0,0,0.02); overflow:hidden;">
                <div style="padding:16px 20px; border-bottom:1px solid #e2e8f0; background:#f8fafc;">
                    <h3 style="margin:0; font-size:14.5px; font-weight:700; color:#1e293b;">Kepegawaian (Perlu Diproses)</h3>
                </div>
                <div style="padding:16px 20px; display:flex; flex-direction:column; gap:16px;">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="width:32px; height:32px; background:#f1f5f9; border-radius:6px; display:flex; align-items:center; justify-content:center; color:#64748b;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            </div>
                            <span style="font-size:13.5px; color:#334155; font-weight:500;">Cuti</span>
                        </div>
                        <span style="font-size:16px; font-weight:700; color:{{ $cutiPending > 0 ? '#1d4ed8' : '#94a3b8' }};">{{ $cutiPending }}</span>
                    </div>
                    
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="width:32px; height:32px; background:#f1f5f9; border-radius:6px; display:flex; align-items:center; justify-content:center; color:#64748b;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                            </div>
                            <span style="font-size:13.5px; color:#334155; font-weight:500;">Kenaikan Pangkat</span>
                        </div>
                        <span style="font-size:16px; font-weight:700; color:{{ $kenaikanPangkatPending > 0 ? '#1d4ed8' : '#94a3b8' }};">{{ $kenaikanPangkatPending }}</span>
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="width:32px; height:32px; background:#f1f5f9; border-radius:6px; display:flex; align-items:center; justify-content:center; color:#64748b;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                            </div>
                            <span style="font-size:13.5px; color:#334155; font-weight:500;">Kenaikan Gaji Berkala</span>
                        </div>
                        <span style="font-size:16px; font-weight:700; color:{{ $gajiBerkalaPending > 0 ? '#1d4ed8' : '#94a3b8' }};">{{ $gajiBerkalaPending }}</span>
                    </div>
                </div>
            </div>

            <div style="background:white; border-radius:10px; border:1px solid #e2e8f0; padding:0; box-shadow:0 1px 3px rgba(0,0,0,0.02); overflow:hidden;">
                <div style="padding:16px 20px; border-bottom:1px solid #e2e8f0; background:#f8fafc;">
                    <h3 style="margin:0; font-size:14.5px; font-weight:700; color:#1e293b;">Early Warning Kepegawaian</h3>
                </div>
                <table style="width:100%; border-collapse:collapse; font-size:13.5px;">
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:16px 20px; color:#334155; font-weight:500;">Kenaikan Gaji Berkala</td>
                        <td style="padding:16px 20px; text-align:right;">
                            @php
                                $kgbStatus = 'Aman';
                                $kgbColor = '#16a34a'; $kgbBg = '#f0fdf4';
                                if($kgbWarningData['tidak_lengkap'] > 0) { $kgbStatus = 'Data Belum Lengkap'; $kgbColor = '#dc2626'; $kgbBg = '#fef2f2'; }
                                elseif($kgbWarningData['jatuh_tempo'] > 0) { $kgbStatus = 'Jatuh Tempo'; $kgbColor = '#dc2626'; $kgbBg = '#fef2f2'; }
                                elseif($kgbWarningData['akan_jatuh_tempo'] > 0) { $kgbStatus = 'Akan Jatuh Tempo'; $kgbColor = '#ea580c'; $kgbBg = '#fff7ed'; }
                            @endphp
                            <span style="color:{{ $kgbColor }}; background:{{ $kgbBg }}; padding:4px 10px; border-radius:6px; font-size:12.5px; font-weight:600;">{{ $kgbStatus }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 20px; color:#334155; font-weight:500;">Kenaikan Pangkat</td>
                        <td style="padding:16px 20px; text-align:right;">
                            @php
                                $kpStatus = 'Aman';
                                $kpColor = '#16a34a'; $kpBg = '#f0fdf4';
                                if($kpWarningData['tidak_lengkap'] > 0) { $kpStatus = 'Data Belum Lengkap'; $kpColor = '#dc2626'; $kpBg = '#fef2f2'; }
                                elseif($kpWarningData['jatuh_tempo'] > 0) { $kpStatus = 'Jatuh Tempo'; $kpColor = '#dc2626'; $kpBg = '#fef2f2'; }
                                elseif($kpWarningData['akan_jatuh_tempo'] > 0) { $kpStatus = 'Akan Jatuh Tempo'; $kpColor = '#ea580c'; $kpBg = '#fff7ed'; }
                            @endphp
                            <span style="color:{{ $kpColor }}; background:{{ $kpBg }}; padding:4px 10px; border-radius:6px; font-size:12.5px; font-weight:600;">{{ $kpStatus }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- 8. AKTIVITAS TERBARU --}}
        <div style="background:white; border-radius:10px; border:1px solid #e2e8f0; box-shadow:0 1px 3px rgba(0,0,0,0.02); overflow:hidden; margin-bottom:20px;">
            <div style="padding:16px 20px; border-bottom:1px solid #e2e8f0; background:#f8fafc; display:flex; justify-content:space-between; align-items:center;">
                <h3 style="margin:0; font-size:14.5px; font-weight:700; color:#1e293b;">Aktivitas Terbaru</h3>
                <a href="{{ route('pencarian.index') }}" style="font-size:12.5px; color:#1d4ed8; text-decoration:none; font-weight:600;">Lihat Semua &rarr;</a>
            </div>
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; font-size:13px; text-align:left;">
                    <thead>
                        <tr style="border-bottom:1px solid #e2e8f0; background:#fff;">
                            <th style="padding:12px 20px; color:#64748b; font-weight:600;">Waktu</th>
                            <th style="padding:12px 20px; color:#64748b; font-weight:600;">Pengguna</th>
                            <th style="padding:12px 20px; color:#64748b; font-weight:600;">Aktivitas</th>
                            <th style="padding:12px 20px; color:#64748b; font-weight:600;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($aktivitasTerbaru as $log)
                        <tr style="border-bottom:1px solid #f1f5f9; transition:background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <td style="padding:12px 20px; color:#64748b; white-space:nowrap;">{{ $log->created_at?->translatedFormat('d M Y, H:i') }}</td>
                            <td style="padding:12px 20px; color:#1e293b; font-weight:600; white-space:nowrap;">{{ $log->user->name ?? 'Sistem' }}</td>
                            <td style="padding:12px 20px; color:#334155;">{{ $log->aktivitas ?? $log->description ?? 'Melakukan aksi sistem' }}</td>
                            <td style="padding:12px 20px; color:#64748b;">{{ $log->action ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="padding:30px 20px; text-align:center; color:#9ca3af;">Belum ada aktivitas tercatat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- FOOTER --}}
        <div style="border-top:1px solid #e2e8f0; padding-top:16px; font-size:12.5px; color:#94a3b8; display:flex; justify-content:space-between; flex-wrap:wrap; gap:10px;">
            <span>&copy; {{ date('Y') }} Pemerintah Provinsi Riau - Dinas Perhubungan.</span>
            <div style="display:flex; gap:16px;">
                <a href="{{ route('pengguna.index') }}" style="color:#94a3b8; text-decoration:none;">Panduan Pengguna</a>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        /* Jam Realtime */
        const HARI_ID  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const BULAN_ID = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        const elTgl = document.getElementById('jam-tanggal');
        const elJam = document.getElementById('jam-waktu');
        function updateJam() {
            const n = new Date();
            if(elJam) elJam.textContent = `${String(n.getHours()).padStart(2,'0')}:${String(n.getMinutes()).padStart(2,'0')}:${String(n.getSeconds()).padStart(2,'0')}`;
            if(elTgl) elTgl.textContent = `${HARI_ID[n.getDay()]}, ${n.getDate()} ${BULAN_ID[n.getMonth()]} ${n.getFullYear()}`;
        }
        updateJam(); setInterval(updateJam, 1000);

        /* Chart.js */
        const Chart = window.Chart;
        if (!Chart) return;

        const PALETTE = ['#1d4ed8','#22c55e','#ea580c','#7c3aed','#0ea5e9','#e11d48','#0d9488'];

        /* 1. Tren Surat */
        const trendLabels = @json($trendLabels);
        const trendMasuk = @json($trendMasuk);
        const trendKeluar = @json($trendKeluar);
        const ctxTren = document.getElementById('chartTren');
        if (ctxTren) {
            new Chart(ctxTren, {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [
                        { label:'Surat Masuk', data:trendMasuk, borderColor:'#16a34a', backgroundColor:'rgba(22, 163, 74, 0.05)', borderWidth:2.5, pointBackgroundColor:'#16a34a', pointRadius:4, tension:0.3, fill:true },
                        { label:'Surat Keluar', data:trendKeluar, borderColor:'#1d4ed8', backgroundColor:'rgba(29, 78, 216, 0.05)', borderWidth:2.5, pointBackgroundColor:'#1d4ed8', pointRadius:4, tension:0.3, fill:true }
                    ]
                },
                options: {
                    responsive:true, maintainAspectRatio:false,
                    interaction:{ mode:'index', intersect:false },
                    plugins:{
                        legend:{ display:true, position:'top', labels:{ font:{size:12}, boxWidth:12, usePointStyle:true } }
                    },
                    scales:{
                        x:{ grid:{display:false}, ticks:{font:{size:12},color:'#64748b'}, border:{display:false} },
                        y:{ grid:{color:'#f1f5f9'}, ticks:{font:{size:12},color:'#64748b',precision:0,stepSize:1}, border:{display:false}, beginAtZero:true }
                    }
                }
            });
        }

        /* 2. Distribusi (Donut) */
        const distLabels = @json($distribusiLabels);
        const distData = @json($distribusiData);
        const ctxDist = document.getElementById('chartDistribusi');
        if (ctxDist && distData.length > 0) {
            new Chart(ctxDist, {
                type: 'doughnut',
                data: { labels: distLabels, datasets: [{ data: distData, backgroundColor: PALETTE.slice(0, distData.length), borderWidth:2, borderColor:'#fff' }] },
                options: {
                    responsive:true, maintainAspectRatio:false, cutout:'65%',
                    plugins: { legend: { display: false } }
                }
            });
        }

        /* 3. Klasifikasi (Bar Horizontal) */
        const klasifLabels = @json($klasifikasiLabels);
        const klasifData = @json($klasifikasiData);
        const ctxKlasif = document.getElementById('chartKlasifikasi');
        if (ctxKlasif && klasifData.length > 0) {
            new Chart(ctxKlasif, {
                type: 'bar',
                data: {
                    labels: klasifLabels,
                    datasets: [{ data: klasifData, backgroundColor: '#3b82f6', borderRadius: 4 }]
                },
                options: {
                    indexAxis: 'y', responsive:true, maintainAspectRatio:false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 12 }, color: '#64748b', precision: 0, stepSize: 1 }, border: { display: false }, beginAtZero: true },
                        y: { grid: { display: false }, ticks: { font: { size: 12 }, color: '#334155' }, border: { display: false } }
                    }
                }
            });
        }
    });
    </script>
    @endpush
</x-app-layout>
