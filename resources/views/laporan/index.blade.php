<x-app-layout>
    <x-slot name="header">Laporan Persuratan</x-slot>

    <div style="display:flex; flex-direction:column; gap:20px;">

        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px;">
            <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:22px 24px;">
                <p style="font-size:13px; font-weight:500; color:#64748b; margin:0 0 8px 0;">Total Surat Masuk</p>
                <p style="font-size:32px; font-weight:800; color:#0f172a; margin:0;">{{ $totalMasuk }}</p>
            </div>
            <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:22px 24px;">
                <p style="font-size:13px; font-weight:500; color:#64748b; margin:0 0 8px 0;">Total Surat Keluar (Final)</p>
                <p style="font-size:32px; font-weight:800; color:#0f172a; margin:0;">{{ $totalKeluar }}</p>
            </div>
            <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:22px 24px;">
                <p style="font-size:13px; font-weight:500; color:#64748b; margin:0 0 8px 0;">Draft Surat Keluar</p>
                <p style="font-size:32px; font-weight:800; color:#0f172a; margin:0;">{{ $totalDraft }}</p>
            </div>
        </div>

        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <div style="padding:18px 24px; border-bottom:1px solid rgba(0,0,0,0.05);">
                <span style="font-size:14px; font-weight:700; color:#1e293b;">Jumlah Surat per Jenis</span>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Jenis Surat</th>
                        <th style="text-align:right; padding-right:24px;">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($perJenis as $row)
                        <tr>
                            <td style="font-weight:500; color:#1e293b;">{{ $row->jenisSurat->nama ?? '-' }}</td>
                            <td style="text-align:right; padding-right:24px; color:#334155;">{{ $row->total }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" style="text-align:center; padding:48px 24px; color:#94a3b8;">
                                <p style="margin:0; font-size:14px; font-weight:500;">Belum ada data</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <p style="font-size:12px; color:#94a3b8; margin:0;">
            Laporan detail (periode, ekspor PDF/Excel, filter lanjutan) belum dikembangkan pada tahap ini.
        </p>
    </div>
</x-app-layout>
