<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Persuratan — SIPAS DISHUB</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000;
            background: #fff;
            padding: 20mm 25mm;
        }

        /* ── Kop Surat ── */
        .kop {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 18px;
        }
        .kop h1 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .kop p {
            font-size: 10pt;
            margin-top: 3px;
        }

        /* ── Judul Laporan ── */
        .judul-laporan {
            text-align: center;
            margin: 16px 0 8px;
        }
        .judul-laporan h2 {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .judul-laporan p {
            font-size: 10pt;
            color: #333;
            margin-top: 4px;
        }

        /* ── Divider ── */
        hr { border: none; border-top: 1px solid #000; margin: 10px 0; }

        /* ── Info Filter ── */
        .filter-box {
            background: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 8px 14px;
            margin-bottom: 16px;
            font-size: 10pt;
        }
        .filter-box table { width: 100%; border-collapse: collapse; }
        .filter-box td { padding: 2px 6px; }
        .filter-box td:first-child { font-weight: bold; width: 160px; }

        /* ── Statistik Ringkas ── */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        .stat-card {
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 10px 14px;
            text-align: center;
        }
        .stat-card .num {
            font-size: 24pt;
            font-weight: bold;
            line-height: 1.1;
        }
        .stat-card .label {
            font-size: 9pt;
            color: #555;
            margin-top: 3px;
        }
        .stat-card.masuk .num  { color: #166534; }
        .stat-card.keluar .num { color: #9a3412; }

        /* ── Tabel ── */
        .section-title {
            font-size: 11pt;
            font-weight: bold;
            margin: 16px 0 6px;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
        }

        table.laporan-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5pt;
        }
        table.laporan-table th {
            background: #e5e7eb;
            border: 1px solid #999;
            padding: 6px 8px;
            text-align: left;
            font-weight: bold;
        }
        table.laporan-table td {
            border: 1px solid #999;
            padding: 5px 8px;
        }
        table.laporan-table tfoot td {
            font-weight: bold;
            background: #f3f4f6;
        }
        table.laporan-table .num-col {
            text-align: right;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            font-style: italic;
            color: #555;
            border: 1px solid #ccc;
        }

        /* ── Footer ── */
        .footer {
            margin-top: 30px;
            font-size: 9.5pt;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .footer .tanggal {
            color: #444;
        }
        .footer .ttd-box {
            text-align: center;
        }
        .footer .ttd-box .garis {
            border-top: 1px solid #000;
            margin-top: 60px;
            padding-top: 3px;
            font-weight: bold;
            min-width: 180px;
        }

        /* ── Print-specific ── */
        @media print {
            body { padding: 10mm 15mm; }
            .no-print { display: none !important; }
        }

        /* ── Screen preview: tombol print ── */
        @media screen {
            .print-bar {
                position: fixed;
                top: 0; left: 0; right: 0;
                background: #1d4ed8;
                color: white;
                padding: 10px 24px;
                display: flex;
                align-items: center;
                gap: 12px;
                z-index: 1000;
                font-family: 'Arial', sans-serif;
                font-size: 13px;
            }
            .print-bar button {
                padding: 6px 18px;
                background: white;
                color: #1d4ed8;
                border: none;
                border-radius: 6px;
                font-size: 13px;
                font-weight: 700;
                cursor: pointer;
            }
            .print-bar a {
                color: #bfdbfe;
                text-decoration: none;
                font-size: 13px;
                margin-left: auto;
            }
            body { margin-top: 50px; }
        }
    </style>
</head>
<body>

    {{-- Toolbar Print (hanya tampil di layar, tidak tercetak) --}}
    <div class="print-bar no-print">
        <button onclick="window.print()">🖨️ Cetak Sekarang</button>
        <span>Preview Laporan Persuratan</span>
        <a href="{{ url()->previous() }}">← Kembali ke Laporan</a>
    </div>

    {{-- KOP --}}
    <div class="kop">
        <h1>Dinas Perhubungan</h1>
        <p>Sistem Informasi Persuratan &amp; Administrasi (SIPAS)</p>
    </div>

    {{-- Judul --}}
    <div class="judul-laporan">
        <h2>Laporan Persuratan</h2>
        @php
            $periodeTeks = 'Seluruh Data';
            if ($dari && $sampai)
                $periodeTeks = \Carbon\Carbon::parse($dari)->translatedFormat('d F Y')
                    . ' s.d. '
                    . \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y');
            elseif ($dari)
                $periodeTeks = 'Mulai ' . \Carbon\Carbon::parse($dari)->translatedFormat('d F Y');
            elseif ($sampai)
                $periodeTeks = 'Sampai ' . \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y');
        @endphp
        <p>{{ $periodeTeks }}</p>
    </div>

    <hr>

    {{-- Info Filter Aktif --}}
    @php
        $adaFilterInfo = $dari || $sampai || $arah || $jenisSuratNama || $klasifikasiNama || $bidangNama || $seksiNama;
    @endphp
    @if ($adaFilterInfo)
    <div class="filter-box">
        <table>
            @if($dari || $sampai)
            <tr>
                <td>Periode</td>
                <td>: {{ $periodeTeks }}</td>
            </tr>
            @endif
            @if($arah)
            <tr>
                <td>Arah Surat</td>
                <td>: {{ ucfirst($arah) }}</td>
            </tr>
            @endif
            @if($jenisSuratNama)
            <tr>
                <td>Jenis Surat</td>
                <td>: {{ $jenisSuratNama }}</td>
            </tr>
            @endif
            @if($klasifikasiNama)
            <tr>
                <td>Klasifikasi</td>
                <td>: {{ $klasifikasiNama }}</td>
            </tr>
            @endif
            @if($bidangNama)
            <tr>
                <td>Bidang</td>
                <td>: {{ $bidangNama }}</td>
            </tr>
            @endif
            @if($seksiNama)
            <tr>
                <td>Seksi / Unit Kerja</td>
                <td>: {{ $seksiNama }}</td>
            </tr>
            @endif
        </table>
    </div>
    @endif

    {{-- Statistik Ringkas --}}
    <p class="section-title">Ringkasan</p>
    <div class="stat-grid">
        <div class="stat-card">
            <div class="num">{{ $totalSemua }}</div>
            <div class="label">Total Surat Final</div>
        </div>
        <div class="stat-card masuk">
            <div class="num">{{ $totalMasuk }}</div>
            <div class="label">Surat Masuk</div>
        </div>
        <div class="stat-card keluar">
            <div class="num">{{ $totalKeluar }}</div>
            <div class="label">Surat Keluar</div>
        </div>
    </div>

    {{-- Rekap Per Jenis --}}
    <p class="section-title">Rekap per Jenis Surat</p>
    @if ($perJenis->isNotEmpty())
    <table class="laporan-table">
        <thead>
            <tr>
                <th style="width:40px;">No.</th>
                <th>Jenis Surat</th>
                <th style="width:80px;" class="num-col">Masuk</th>
                <th style="width:80px;" class="num-col">Keluar</th>
                <th style="width:80px;" class="num-col">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($perJenis as $i => $row)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                    {{ $row['jenisSurat']->nama ?? '-' }}
                    @if($row['jenisSurat']?->kode)
                        <span style="color:#6b7280; font-size:9.5pt;">({{ $row['jenisSurat']->kode }})</span>
                    @endif
                </td>
                <td class="num-col" style="color:#166534;">{{ $row['masuk'] > 0 ? $row['masuk'] : '—' }}</td>
                <td class="num-col" style="color:#9a3412;">{{ $row['keluar'] > 0 ? $row['keluar'] : '—' }}</td>
                <td class="num-col"><strong>{{ $row['total'] }}</strong></td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">Total Keseluruhan</td>
                <td class="num-col" style="color:#166534;">{{ $totalMasuk }}</td>
                <td class="num-col" style="color:#9a3412;">{{ $totalKeluar }}</td>
                <td class="num-col">{{ $totalSemua }}</td>
            </tr>
        </tfoot>
    </table>
    @else
    <div class="no-data">Tidak ada data surat final sesuai filter yang diterapkan.</div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <div class="tanggal">
            Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB
        </div>
        <div class="ttd-box">
            <p>Mengetahui,</p>
            <div class="garis">Admin SIPAS</div>
        </div>
    </div>

</body>
</html>
