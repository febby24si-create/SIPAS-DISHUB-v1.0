<x-app-layout>
    <x-slot name="header">Draft Surat</x-slot>

    <div style="display:flex; flex-direction:column; gap:20px;">

        <p style="font-size:13px; color:#94a3b8; margin:0;">
            Surat keluar yang sudah dibuat namun belum difinalisasi.
        </p>

        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:60px;">No.</th>
                        <th>Nomor Surat</th>
                        <th>Perihal</th>
                        <th>Jenis</th>
                        <th>Tgl. Surat</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($draftSurat as $item)
                        <tr>
                            <td style="color:#94a3b8; font-size:13px;">{{ $loop->iteration }}.</td>
                            <td style="font-family:monospace; font-size:12px; color:#334155;">{{ $item->nomor_surat ?? '-' }}</td>
                            <td style="font-weight:500; color:#1e293b;">{{ $item->perihal }}</td>
                            <td>
                                <span style="display:inline-flex; padding:4px 10px; background:#f1f5f9; color:#64748b; font-size:11px; font-weight:600; border-radius:8px;">
                                    {{ $item->jenisSurat->kode ?? '-' }}
                                </span>
                            </td>
                            <td style="color:#64748b; font-size:13px;">{{ $item->tanggal_surat?->format('d M Y') }}</td>
                            <td style="text-align:right; padding-right:16px;">
                                <div style="display:inline-flex; gap:8px;">
                                    <a href="{{ route('surat-keluar.edit', $item) }}" style="font-size:12px; font-weight:600; color:#1d4ed8; text-decoration:none;">Edit Draft</a>
                                    <a href="{{ route('buat-surat.show', $item) }}" style="font-size:12px; font-weight:600; color:#64748b; text-decoration:none;">Preview</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:48px 24px; color:#94a3b8;">
                                <p style="margin:0; font-size:14px; font-weight:500;">Tidak ada draft surat</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($draftSurat->hasPages())
                <div style="padding:14px 24px; border-top:1px solid rgba(0,0,0,0.05);">
                    {{ $draftSurat->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
