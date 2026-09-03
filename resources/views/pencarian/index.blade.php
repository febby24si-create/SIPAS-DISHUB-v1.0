<x-app-layout>
    <x-slot name="header">Pencarian Surat</x-slot>

    <div style="display:flex; flex-direction:column; gap:20px;">

        <form method="GET" style="display:flex; align-items:center; gap:10px; background:white; border:1px solid rgba(0,0,0,0.07); border-radius:14px; padding:12px 18px; flex-wrap:wrap;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="q" value="{{ $q }}" placeholder="Cari berdasarkan nomor atau perihal surat..."
                   style="flex:1; min-width:200px; border:none; outline:none; font-size:14px; color:#334155; background:transparent;">
            <input type="date" name="tanggal" value="{{ $tanggal }}"
                   style="border:1px solid #e2e8f0; border-radius:8px; padding:8px 10px; font-size:13px; color:#334155;">
            <button type="submit" style="padding:9px 18px; background:#1d4ed8; color:white; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;">Cari</button>
        </form>

        @if ($hasil === null)
            <div style="text-align:center; padding:64px 24px; color:#94a3b8;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#e2e8f0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 12px; display:block;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <p style="margin:0; font-size:14px; font-weight:500;">Masukkan kata kunci atau tanggal untuk mencari surat</p>
            </div>
        @else
            <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:60px;">No.</th>
                            <th>Nomor Surat</th>
                            <th>Perihal</th>
                            <th>Jenis</th>
                            <th>Arah</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($hasil as $item)
                            <tr>
                                <td style="color:#94a3b8; font-size:13px;">{{ $loop->iteration }}.</td>
                                <td style="font-family:monospace; font-size:12px; color:#334155;">{{ $item->nomor_surat ?? '-' }}</td>
                                <td style="font-weight:500; color:#1e293b;">{{ $item->perihal }}</td>
                                <td>
                                    <span style="display:inline-flex; padding:4px 10px; background:#eff6ff; color:#1d4ed8; font-size:11px; font-weight:600; border-radius:8px;">
                                        {{ $item->jenisSurat->kode ?? '-' }}
                                    </span>
                                </td>
                                <td>{{ $item->arah === 'masuk' ? 'Masuk' : 'Keluar' }}</td>
                                <td style="color:#64748b; font-size:13px;">{{ $item->tanggal_surat?->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center; padding:48px 24px; color:#94a3b8;">
                                    <p style="margin:0; font-size:14px; font-weight:500;">Tidak ditemukan surat yang sesuai</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($hasil->hasPages())
                    <div style="padding:14px 24px; border-top:1px solid rgba(0,0,0,0.05);">
                        {{ $hasil->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>
