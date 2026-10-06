<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            {{-- Icon + Title --}}
            <div style="display:flex; align-items:center; gap:12px;">
                <div style="width:40px; height:40px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#1d4ed8; flex-shrink:0;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                </div>
                <div>
                    <div style="font-size:17px; font-weight:600; color:#1e293b; line-height:1.2;">Buku Tamu</div>
                    <div style="margin-top:2px; font-size:12.5px; color:#64748b;">Data kunjungan tamu — Dinas Perhubungan Provinsi Riau</div>
                </div>
            </div>
            {{-- Counter --}}
            <div style="text-align:right;">
                <div style="font-size:11px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px;">Total Kunjungan</div>
                <div style="font-size:18px; font-weight:700; color:#1e293b; line-height:1.2;">{{ $totalSemua }} <span style="font-size:13px; font-weight:500; color:#64748b;">kunjungan</span></div>
            </div>
        </div>
    </x-slot>

    {{-- Filter --}}
    <div style="background:#fff; border:1px solid rgba(0,0,0,0.06); border-radius:10px; padding:12px 16px; margin-bottom:16px;">
        <form method="GET" action="{{ route('buku-tamu.index') }}" style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
            <div style="display:flex; align-items:center; gap:8px;">
                <label style="font-size:12px; font-weight:600; color:#475569;">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" style="border:1px solid #cbd5e1; border-radius:6px; padding:4px 8px; font-size:13px; color:#334155; outline:none; height:32px; box-sizing:border-box;">
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
                <label style="font-size:12px; font-weight:600; color:#475569;">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" style="border:1px solid #cbd5e1; border-radius:6px; padding:4px 8px; font-size:13px; color:#334155; outline:none; height:32px; box-sizing:border-box;">
            </div>
            <button type="submit" style="height:32px; padding:0 16px; background:#1d4ed8; color:white; border:none; border-radius:6px; font-size:13px; font-weight:600; cursor:pointer;">
                Tampilkan
            </button>
            @if($adaFilter)
                <a href="{{ route('buku-tamu.index') }}" style="height:32px; display:inline-flex; align-items:center; padding:0 16px; background:#f8fafc; color:#475569; border:1px solid #e2e8f0; border-radius:6px; font-size:13px; font-weight:600; text-decoration:none;">
                    Reset
                </a>
                <span style="font-size:12px; font-weight:500; color:#1d4ed8; margin-left:auto;">
                    Menampilkan hasil filter ({{ $totalFilter }} data)
                </span>
            @endif
        </form>
    </div>

    {{-- Grafik kunjungan per tanggal --}}
    <div style="background:#fff; border:1px solid rgba(0,0,0,0.06); border-radius:10px; padding:14px 18px; margin-bottom:16px;">
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:14px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            <span style="font-size:13px; font-weight:600; color:#334155;">Tren Kunjungan</span>
        </div>

        @if(count($chartLabels) > 0)
            <div style="position:relative; height:220px;">
                <canvas id="chartKunjungan"></canvas>
            </div>
        @else
            <div style="display:flex; align-items:center; justify-content:center; height:220px; color:#94a3b8; font-size:13px; font-weight:500;">
                Belum ada data kunjungan pada periode ini.
            </div>
        @endif
    </div>

    {{-- Tabel Daftar Kunjungan --}}
    <div style="background:#fff; border:1px solid rgba(0,0,0,0.06); border-radius:10px; overflow:hidden;">
        <div style="padding:12px 16px; border-bottom:1px solid rgba(0,0,0,0.05); display:flex; align-items:center; justify-content:space-between; gap:10px; flex-wrap:wrap;">
            <div style="display:flex; align-items:center; gap:8px;">
                <span style="font-size:14px; font-weight:600; color:#1e293b;">Daftar Kunjungan Tamu</span>
                @if($bukuTamu->total() > 0)
                    <span style="background:#f1f5f9; color:#475569; padding:2px 8px; border-radius:20px; font-size:11px; font-weight:600;">{{ $bukuTamu->total() }} data</span>
                @endif
            </div>
            <a href="{{ route('buku-tamu.qr') }}"
               style="display:inline-flex; align-items:center; gap:6px; height:28px; padding:0 12px; background:#f8fafc; color:#0369a1; font-size:12px; font-weight:600; border-radius:6px; text-decoration:none; border:1px solid #bae6fd; transition:all 0.15s;"
               onmouseover="this.style.background='#e0f2fe'" onmouseout="this.style.background='#f8fafc'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                QR Buku Tamu
            </a>
        </div>

        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:44px;">No.</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Nama Tamu</th>
                        <th>Instansi</th>
                        <th>No. HP/Kontak</th>
                        <th>Bertemu Dengan</th>
                        <th>Unit/Seksi Tujuan</th>
                        <th style="text-align:right; padding-right:18px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bukuTamu as $tamu)
                        <tr>
                            <td style="color:#94a3b8; font-size:13px;">{{ $loop->iteration + $bukuTamu->firstItem() - 1 }}.</td>
                            <td style="font-size:13px; color:#334155; white-space:nowrap;">
                                {{ \Carbon\Carbon::parse($tamu->tanggal)->translatedFormat('d M Y') }}
                            </td>
                            <td style="font-size:13px; color:#64748b; font-family:monospace; white-space:nowrap;">
                                {{ substr($tamu->jam, 0, 5) }}
                            </td>
                            <td style="font-size:13px; font-weight:600; color:#1e293b;">{{ $tamu->nama }}</td>
                            <td style="font-size:12px; color:#475569;">{!! $tamu->instansi ? e($tamu->instansi) : '<span style="color:#cbd5e1;">—</span>' !!}</td>
                            <td style="font-size:12px; color:#475569; font-family:monospace; white-space:nowrap;">{{ $tamu->no_hp }}</td>
                            <td style="font-size:12px; color:#475569;">{!! $tamu->pegawai?->nama ? e($tamu->pegawai->nama) : '<span style="color:#cbd5e1;">—</span>' !!}</td>
                            <td style="font-size:12px; color:#475569;">{!! $tamu->unitKerja?->nama ? e($tamu->unitKerja->nama) : '<span style="color:#cbd5e1;">—</span>' !!}</td>
                            <td style="text-align:right; padding-right:16px;">
                                <x-action-group>
                                    <x-action-btn type="view" url="{{ route('buku-tamu.show', $tamu->id) }}" />
                                </x-action-group>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align:center; padding:36px 24px; color:#94a3b8; font-size:13px;">
                                {{ $adaFilter ? 'Tidak ada kunjungan pada periode ini.' : 'Belum ada data kunjungan.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bukuTamu->hasPages())
            <div style="padding:12px 18px; border-top:1px solid #f1f5f9;">
                {{ $bukuTamu->links() }}
            </div>
        @endif
    </div>

    {{-- Chart.js — tampilkan jika ada data --}}
    @if(count($chartLabels) > 0)
    @push('scripts')
    <script>
    (function () {
        function initChart() {
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
                        backgroundColor: 'rgba(29, 78, 216, 0.06)',
                        pointBackgroundColor: '#1d4ed8',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
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
                                font: { size: 11 },
                                color: '#94a3b8',
                                // Tampilkan semua label untuk rentang ≤31 hari
                                // Untuk rentang lebih panjang, batasi agar tidak terlalu padat
                                maxTicksLimit: {{ count($chartLabels) <= 31 ? count($chartLabels) : 16 }},
                                autoSkip: {{ count($chartLabels) > 31 ? 'true' : 'false' }},
                                maxRotation: 45,
                                minRotation: 0
                            }
                        },
                        y: {
                            beginAtZero: true,
                            border: { display: false },
                            ticks: { stepSize: 1, precision: 0, font: { size: 11 }, color: '#94a3b8' },
                            grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false }
                        }
                    }
                }
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initChart);
        } else {
            initChart();
        }
    })();
    </script>
    @endpush
    @endif

</x-app-layout>
