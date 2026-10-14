<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Persuratan — Dinas Perhubungan Provinsi Riau</title>
    <style>
        /* ── Reset & Typography ── */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            background: #fff;
            line-height: 1.35;
        }

        /* ── Screen Preview Layout ── */
        @media screen {
            body {
                background: #f1f5f9;
                padding-top: 60px;
                padding-bottom: 40px;
            }

            .sheet {
                background: #fff;
                width: 210mm;
                min-height: 297mm;
                margin: 0 auto;
                padding: 18mm 22mm 20mm 22mm;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
                border-radius: 2px;
            }

            .print-bar {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                background: #1e3a8a;
                color: white;
                padding: 10px 24px;
                display: flex;
                align-items: center;
                gap: 14px;
                z-index: 1000;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 13px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            }

            .print-bar button {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 7px 18px;
                background: #22c55e;
                color: white;
                border: none;
                border-radius: 6px;
                font-size: 13px;
                font-weight: 700;
                cursor: pointer;
                transition: background 0.15s ease;
            }
            .print-bar button:hover {
                background: #16a34a;
            }

            .print-bar a {
                color: #bfdbfe;
                text-decoration: none;
                font-size: 13px;
                margin-left: auto;
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }
            .print-bar a:hover {
                color: #ffffff;
                text-decoration: underline;
            }
        }

        /* ── Print Specifics ── */
        @page {
            size: A4 portrait;
            margin: 15mm 20mm 15mm 20mm;
        }

        @media print {
            body {
                background: #fff !important;
                padding: 0 !important;
            }
            .sheet {
                width: 100% !important;
                min-height: auto !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
            }
            .no-print {
                display: none !important;
            }
        }

        /* ── Kop Surat Resmi Dinas Perhubungan ── */
        .kop-wrapper {
            display: flex;
            align-items: center;
            gap: 16px;
            padding-bottom: 10px;
        }

        .kop-logo {
            width: 75px;
            height: 75px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .kop-text {
            flex: 1;
            text-align: center;
        }

        .kop-text .instansi-prov {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kop-text .instansi-dinas {
            font-size: 15pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 1px;
        }

        .kop-text .instansi-alamat {
            font-size: 9pt;
            margin-top: 3px;
            color: #111;
        }

        .kop-text .instansi-kontak {
            font-size: 8.5pt;
            margin-top: 1px;
            color: #222;
        }

        /* Garis Ganda Khas Surat Resmi */
        .kop-divider {
            border: none;
            border-top: 2.5px solid #000;
            border-bottom: 1px solid #000;
            height: 4px;
            margin-bottom: 16px;
        }

        /* ── Judul Laporan ── */
        .judul-section {
            text-align: center;
            margin-bottom: 14px;
        }

        .judul-section h2 {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .judul-section .periode-text {
            font-size: 10.5pt;
            margin-top: 3px;
            font-style: italic;
        }

        /* ── Box Parameter Filter ── */
        .meta-box {
            border: 1px solid #666;
            background: #fafafa;
            border-radius: 4px;
            padding: 8px 12px;
            margin-bottom: 14px;
            font-size: 9.5pt;
            page-break-inside: avoid;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px 16px;
        }

        .meta-item {
            display: flex;
        }
        .meta-item .meta-label {
            width: 130px;
            font-weight: bold;
            flex-shrink: 0;
        }
        .meta-item .meta-val {
            flex: 1;
        }

        /* ── Ringkasan Statistik ── */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 16px;
            page-break-inside: avoid;
        }

        .stat-card {
            border: 1px solid #666;
            border-radius: 4px;
            padding: 8px 10px;
            text-align: center;
            background: #fff;
        }
        .stat-card .num {
            font-size: 20pt;
            font-weight: bold;
            line-height: 1.1;
        }
        .stat-card .lbl {
            font-size: 9pt;
            color: #333;
            margin-top: 2px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .stat-card.masuk .num  { color: #15803d; }
        .stat-card.keluar .num { color: #c2410c; }

        /* ── Tabel Rekapitulasi ── */
        .table-title {
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        table.print-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
            margin-bottom: 16px;
        }

        table.print-table th,
        table.print-table td {
            border: 1px solid #444;
            padding: 5px 8px;
        }

        table.print-table th {
            background: #f1f5f9;
            font-weight: bold;
            text-align: left;
        }

        table.print-table tfoot td {
            background: #f8fafc;
            font-weight: bold;
        }

        table.print-table .text-center {
            text-align: center;
        }

        table.print-table .text-right {
            text-align: right;
            font-variant-numeric: tabular-nums;
        }

        table.print-table tbody tr {
            page-break-inside: avoid;
        }

        .empty-box {
            text-align: center;
            padding: 24px;
            font-style: italic;
            color: #666;
            border: 1px dashed #999;
            margin-bottom: 16px;
        }

        /* ── Tanda Tangan ── */
        .ttd-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 24px;
            page-break-inside: avoid;
            font-size: 10.5pt;
        }

        .ttd-block {
            text-align: center;
            min-width: 220px;
        }

        .ttd-block .tanggal {
            margin-bottom: 4px;
        }

        .ttd-block .jabatan {
            font-weight: bold;
            margin-bottom: 64px;
        }

        .ttd-block .nama {
            font-weight: bold;
            text-decoration: underline;
        }

        .ttd-block .nip {
            font-size: 9.5pt;
            margin-top: 2px;
        }
    </style>
</head>
<body>

    {{-- ═══ TOOLBAR PREVIEW (TIDAK TERCETAK) ═══ --}}
    <div class="print-bar no-print">
        <button onclick="window.print()" title="Cetak Dokumen Sekarang (A4)">
            🖨️ Cetak / Simpan PDF
        </button>
        <span style="font-weight:600;">Preview Cetak Laporan Persuratan — Dinas Perhubungan Provinsi Riau</span>
        <a href="{{ route('laporan.index') }}?{{ http_build_query(request()->only(['dari','sampai','arah','jenis_surat_id','klasifikasi_id','bidang_id','seksi_id'])) }}">
            &larr; Kembali ke Laporan
        </a>
    </div>

    {{-- ═══ HALAMAN LEMBAR A4 ═══ --}}
    <div class="sheet">

        @php
            $printLogo = \App\Models\SystemSetting::getSetting('instansi_logo');
            $printLogoUrl = ($printLogo && \Illuminate\Support\Facades\Storage::disk('public')->exists($printLogo))
                ? asset('storage/' . $printLogo)
                : asset('logoDishub.png');
            $printInstansiProv = \App\Models\SystemSetting::getSetting('instansi_sub', 'Pemerintah Provinsi Riau');
            $printInstansiDinas = \App\Models\SystemSetting::getSetting('instansi_nama', 'Dinas Perhubungan');
            $printInstansiAlamat = \App\Models\SystemSetting::getSetting('instansi_alamat', 'Jl. Jenderal Sudirman No. 474, Pekanbaru, Riau • Kode Pos 28126');
            $printInstansiTelp = \App\Models\SystemSetting::getSetting('instansi_telepon', '(0761) 32155');
            $printInstansiEmail = \App\Models\SystemSetting::getSetting('instansi_email', 'dishub@riau.go.id');
        @endphp
        {{-- ── Kop Surat Resmi ── --}}
        <div class="kop-wrapper">
            <img src="{{ $printLogoUrl }}" alt="Logo {{ $printInstansiDinas }}" class="kop-logo">
            <div class="kop-text">
                <div class="instansi-prov">{{ $printInstansiProv }}</div>
                <div class="instansi-dinas">{{ $printInstansiDinas }}</div>
                <div class="instansi-alamat">{{ $printInstansiAlamat }}</div>
                <div class="instansi-kontak">Telepon: {{ $printInstansiTelp }} &bull; Pos-el: {{ $printInstansiEmail }}</div>
            </div>
        </div>

        <hr class="kop-divider">

        {{-- ── Judul Laporan ── --}}
        <div class="judul-section">
            <h2>Laporan Rekapitulasi Persuratan Resmi</h2>
            @php
                $periodeTeks = 'Seluruh Periode Terdaftar';
                if ($dari && $sampai) {
                    $periodeTeks = \Carbon\Carbon::parse($dari)->translatedFormat('d F Y') . ' s.d. ' . \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y');
                } elseif ($dari) {
                    $periodeTeks = 'Mulai ' . \Carbon\Carbon::parse($dari)->translatedFormat('d F Y');
                } elseif ($sampai) {
                    $periodeTeks = 'Sampai dengan ' . \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y');
                }
            @endphp
            <div class="periode-text">Periode: {{ $periodeTeks }}</div>
        </div>

        {{-- ── Parameter & Filter Aktif ── --}}
        <div class="meta-box">
            <div class="meta-grid">
                <div class="meta-item">
                    <span class="meta-label">Periode Laporan</span>
                    <span class="meta-val">: {{ $periodeTeks }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Tanggal Cetak</span>
                    <span class="meta-val">: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Arah Surat</span>
                    <span class="meta-val">: {{ $arah ? ($arah === 'masuk' ? 'Surat Masuk' : 'Surat Keluar') : 'Semua Arah' }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Status Naskah</span>
                    <span class="meta-val">: Final Resmi</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Jenis Surat</span>
                    <span class="meta-val">: {{ $jenisSuratNama ?: 'Semua Jenis Surat' }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Klasifikasi</span>
                    <span class="meta-val">: {{ $klasifikasiNama ?: 'Semua Klasifikasi' }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Bidang</span>
                    <span class="meta-val">: {{ $bidangNama ?: 'Semua Bidang' }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Seksi / Unit Kerja</span>
                    <span class="meta-val">: {{ $seksiNama ?: 'Semua Seksi' }}</span>
                </div>
            </div>
        </div>

        {{-- ── Statistik Ringkas ── --}}
        <div class="stat-grid">
            <div class="stat-card">
                <div class="num">{{ number_format($totalSemua, 0, ',', '.') }}</div>
                <div class="lbl">Total Surat Resmi Final</div>
            </div>
            <div class="stat-card masuk">
                <div class="num">{{ number_format($totalMasuk, 0, ',', '.') }}</div>
                <div class="lbl">Surat Masuk</div>
            </div>
            <div class="stat-card keluar">
                <div class="num">{{ number_format($totalKeluar, 0, ',', '.') }}</div>
                <div class="lbl">Surat Keluar</div>
            </div>
        </div>

        {{-- ── Tabel Rekapitulasi per Jenis Surat ── --}}
        <div class="table-title">Rekapitulasi Surat Berdasarkan Jenis Surat</div>
        @if ($perJenis->isNotEmpty())
            <table class="print-table">
                <thead>
                    <tr>
                        <th style="width:38px;" class="text-center">No.</th>
                        <th style="width:80px;" class="text-center">Kode</th>
                        <th>Jenis Naskah Dinas / Surat</th>
                        <th style="width:85px;" class="text-right">Masuk</th>
                        <th style="width:85px;" class="text-right">Keluar</th>
                        <th style="width:85px;" class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($perJenis as $i => $row)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}.</td>
                            <td class="text-center" style="font-family:monospace; font-size:9.5pt;">
                                {{ $row['jenisSurat']?->kode ?: '—' }}
                            </td>
                            <td>
                                {{ $row['jenisSurat']?->nama ?: 'Tanpa Jenis' }}
                            </td>
                            <td class="text-right" style="color:#15803d; font-weight:{{ $row['masuk'] > 0 ? 'bold' : 'normal' }};">
                                {{ $row['masuk'] > 0 ? number_format($row['masuk'], 0, ',', '.') : '—' }}
                            </td>
                            <td class="text-right" style="color:#c2410c; font-weight:{{ $row['keluar'] > 0 ? 'bold' : 'normal' }};">
                                {{ $row['keluar'] > 0 ? number_format($row['keluar'], 0, ',', '.') : '—' }}
                            </td>
                            <td class="text-right" style="font-weight:bold;">
                                {{ number_format($row['total'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align:center; font-weight:bold;">
                            TOTAL KESELURUHAN
                        </td>
                        <td class="text-right" style="color:#15803d; font-weight:bold;">
                            {{ number_format($totalMasuk, 0, ',', '.') }}
                        </td>
                        <td class="text-right" style="color:#c2410c; font-weight:bold;">
                            {{ number_format($totalKeluar, 0, ',', '.') }}
                        </td>
                        <td class="text-right" style="font-weight:bold; font-size:10.5pt;">
                            {{ number_format($totalSemua, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        @else
            <div class="empty-box">
                Tidak ada data surat final yang sesuai dengan parameter filter yang diterapkan.
            </div>
        @endif

        {{-- ── Lembar Pengesahan / Tanda Tangan ── --}}
        <div class="ttd-section">
            <div class="ttd-block">
                <div class="tanggal">Pekanbaru, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                <div class="jabatan">
                    Pengelola Administrasi Persuratan<br>
                    Dinas Perhubungan Provinsi Riau
                </div>
                <div class="nama">Administrator SIPAS</div>
                <div class="nip">NIP. ....................................................</div>
            </div>
        </div>

    </div>

</body>
</html>
