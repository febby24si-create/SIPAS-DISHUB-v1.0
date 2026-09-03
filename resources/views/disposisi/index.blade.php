<x-app-layout>
    <x-slot name="header">Disposisi Surat</x-slot>

    <div style="display:flex; flex-direction:column; gap:20px;">

        <div style="display:flex; align-items:flex-start; gap:12px; padding:16px 20px; background:#fffbeb; border:1px solid #fde68a; border-radius:14px; color:#92400e; font-size:13px; line-height:1.6;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:2px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span>Fitur disposisi (penunjukan/instruksi atas surat masuk kepada pihak terkait) belum dikembangkan pada tahap ini karena alur dan pihak yang terlibat belum dikonfirmasi bersama admin Dishub. Halaman ini sementara menampilkan daftar surat masuk terbaru sebagai referensi.</span>
        </div>

        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <div style="padding:18px 24px; border-bottom:1px solid rgba(0,0,0,0.05);">
                <span style="font-size:14px; font-weight:700; color:#1e293b;">Surat Masuk Terbaru</span>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:60px;">No.</th>
                        <th>Perihal</th>
                        <th>Pengirim</th>
                        <th>Jenis</th>
                        <th>Tgl. Diterima</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suratMasuk as $item)
                        <tr>
                            <td style="color:#94a3b8; font-size:13px;">{{ $loop->iteration }}.</td>
                            <td style="font-weight:500; color:#1e293b;">{{ $item->perihal }}</td>
                            <td style="color:#475569;">{{ $item->pengirim ?? '-' }}</td>
                            <td>
                                <span style="display:inline-flex; padding:4px 10px; background:#eff6ff; color:#1d4ed8; font-size:11px; font-weight:600; border-radius:8px;">
                                    {{ $item->jenisSurat->kode ?? '-' }}
                                </span>
                            </td>
                            <td style="color:#64748b; font-size:13px;">{{ $item->tanggal_diterima?->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:48px 24px; color:#94a3b8;">
                                <p style="margin:0; font-size:14px; font-weight:500;">Belum ada surat masuk</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
