<x-app-layout>
    <x-slot name="header">Buku Tamu</x-slot>

    {{-- Ringkasan tipis --}}
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; flex-wrap:wrap; gap:8px;">
        <p style="margin:0; font-size:13px; color:#64748b;">Data kunjungan tamu — Dinas Perhubungan Provinsi Riau</p>
        <div style="display:flex; gap:16px; font-size:13px; color:#374151;">
            <span>Total Kunjungan: <strong>{{ $totalSemua }}</strong></span>
            @if($adaFilter)
                <span style="color:#1d4ed8;">Hasil Filter: <strong>{{ $totalFilter }}</strong></span>
            @endif
        </div>
    </div>

    {{-- Filter --}}
    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:8px; padding:14px 18px; margin-bottom:14px;">
        <form method="GET" action="{{ route('buku-tamu.index') }}">
            <div style="display:flex; align-items:flex-end; gap:10px; flex-wrap:wrap;">
                <div>
                    <label style="display:block; font-size:11.5px; font-weight:600; color:#64748b; margin-bottom:4px;">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                           style="border:1px solid #cbd5e1; border-radius:6px; padding:6px 10px; font-size:13px; color:#374151; outline:none;"
                           onfocus="this.style.borderColor='#1d4ed8'" onblur="this.style.borderColor='#cbd5e1'">
                </div>
                <div>
                    <label style="display:block; font-size:11.5px; font-weight:600; color:#64748b; margin-bottom:4px;">Tanggal Selesai</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                           style="border:1px solid #cbd5e1; border-radius:6px; padding:6px 10px; font-size:13px; color:#374151; outline:none;"
                           onfocus="this.style.borderColor='#1d4ed8'" onblur="this.style.borderColor='#cbd5e1'">
                </div>
                <button type="submit"
                        style="padding:7px 18px; background:#1d4ed8; color:white; border:none; border-radius:6px; font-size:13px; font-weight:600; cursor:pointer;"
                        onmouseover="this.style.background='#1e40af'" onmouseout="this.style.background='#1d4ed8'">
                    Tampilkan
                </button>
                @if($adaFilter)
                    <a href="{{ route('buku-tamu.index') }}"
                       style="padding:7px 14px; background:#f1f5f9; color:#475569; border-radius:6px; font-size:13px; font-weight:600; text-decoration:none; border:1px solid #e2e8f0;">
                        Reset
                    </a>
                @endif
            </div>
        </form>
        @if($adaFilter)
            <p style="margin:10px 0 0; font-size:12px; color:#1d4ed8;">
                Filter aktif:
                @if(request('start_date') && request('end_date'))
                    {{ \Carbon\Carbon::parse(request('start_date'))->translatedFormat('d M Y') }} s.d. {{ \Carbon\Carbon::parse(request('end_date'))->translatedFormat('d M Y') }}
                @elseif(request('start_date'))
                    Mulai {{ \Carbon\Carbon::parse(request('start_date'))->translatedFormat('d M Y') }}
                @else
                    Sampai {{ \Carbon\Carbon::parse(request('end_date'))->translatedFormat('d M Y') }}
                @endif
            </p>
        @endif
    </div>

    {{-- Grafik kunjungan per tanggal --}}
    @if(count($chartValues) > 0)
    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:8px; padding:14px 18px; margin-bottom:14px;">
        <p style="margin:0 0 12px; font-size:13px; font-weight:600; color:#1e293b;">Jumlah Kunjungan per Tanggal</p>
        <canvas id="chartKunjungan" style="max-height:220px;"></canvas>
    </div>
    @endif

    {{-- Tabel Daftar Kunjungan --}}
    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:8px; overflow:hidden;">
        <div style="padding:12px 18px; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between; gap:10px; flex-wrap:wrap;">
            <span style="font-size:13.5px; font-weight:700; color:#1e293b;">
                Daftar Kunjungan Tamu
                @if($bukuTamu->total() > 0)
                    <span style="font-size:12px; font-weight:400; color:#64748b; margin-left:6px;">({{ $bukuTamu->total() }} data)</span>
                @endif
            </span>
            <a href="{{ route('buku-tamu.qr') }}"
               style="display:inline-flex; align-items:center; gap:5px; padding:6px 13px; background:#f0f9ff; color:#0369a1; font-size:12px; font-weight:600; border-radius:6px; text-decoration:none; border:1px solid #bae6fd;"
               onmouseover="this.style.background='#e0f2fe'" onmouseout="this.style.background='#f0f9ff'">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
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
                            <td style="font-size:13.5px; font-weight:600; color:#1e293b;">{{ $tamu->nama }}</td>
                            <td style="font-size:13px; color:#64748b;">{{ $tamu->instansi ?? '—' }}</td>
                            <td style="font-size:13px; color:#475569; font-family:monospace; white-space:nowrap;">{{ $tamu->no_hp }}</td>
                            <td style="font-size:13px; color:#475569;">{{ $tamu->pegawai?->nama ?? '—' }}</td>
                            <td style="font-size:13px; color:#475569;">{{ $tamu->unitKerja?->nama ?? '—' }}</td>
                            <td style="text-align:right; padding-right:16px;">
                                <a href="{{ route('buku-tamu.show', $tamu->id) }}"
                                   style="display:inline-flex; align-items:center; padding:5px 12px; background:#f0fdf4; color:#15803d; font-size:12px; font-weight:600; border-radius:6px; text-decoration:none; border:1px solid #bbf7d0;"
                                   onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fdf4'">
                                    Detail
                                </a>
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

    {{-- Chart.js --}}
    @if(count($chartValues) > 0)
    @push('scripts')
    <script>
    (function () {
        function initChart() {
            var canvas = document.getElementById('chartKunjungan');
            if (!canvas || typeof Chart === 'undefined') return;

            new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Kunjungan',
                        data: @json($chartValues),
                        backgroundColor: 'rgba(29, 78, 216, 0.12)',
                        borderColor: 'rgba(29, 78, 216, 0.7)',
                        borderWidth: 1.5,
                        borderRadius: 4,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) { return ' ' + ctx.parsed.y + ' kunjungan'; }
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#64748b' } },
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, precision: 0, font: { size: 11 }, color: '#64748b' },
                            grid: { color: 'rgba(0,0,0,0.05)' }
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
