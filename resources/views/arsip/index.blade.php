<x-app-layout>
    <x-slot name="header">Arsip Digital</x-slot>

    <div style="display:flex; flex-direction:column; gap:20px;">

        <form method="GET" style="display:flex; align-items:center; gap:10px; background:white; border:1px solid rgba(0,0,0,0.07); border-radius:14px; padding:10px 16px; flex-wrap:wrap;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari perihal surat..."
                   style="flex:1; min-width:160px; border:none; outline:none; font-size:14px; color:#334155; background:transparent;">
            <select name="arah" style="border:1px solid #e2e8f0; border-radius:8px; padding:6px 10px; font-size:13px; color:#334155;">
                <option value="">Semua Arah</option>
                <option value="masuk" @selected(request('arah') === 'masuk')>Masuk</option>
                <option value="keluar" @selected(request('arah') === 'keluar')>Keluar</option>
            </select>
            <button type="submit" style="padding:8px 16px; background:#1d4ed8; color:white; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;">Cari</button>
        </form>

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
                        <th style="width:100px; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($arsip as $item)
                        <tr>
                            <td style="color:#94a3b8; font-size:13px;">{{ $loop->iteration }}.</td>
                            <td style="font-family:monospace; font-size:12px; color:#334155;">{{ $item->nomor_surat ?? '-' }}</td>
                            <td style="font-weight:500; color:#1e293b;">{{ $item->perihal }}</td>
                            <td>
                                <span style="display:inline-flex; padding:4px 10px; background:#eff6ff; color:#1d4ed8; font-size:11px; font-weight:600; border-radius:8px;">
                                    {{ $item->jenisSurat->kode ?? '-' }}
                                </span>
                            </td>
                            <td>
                                @if ($item->arah === 'masuk')
                                    <span style="padding:4px 10px; background:#ecfdf5; color:#059669; font-size:11px; font-weight:600; border-radius:20px;">↓ Masuk</span>
                                @else
                                    <span style="padding:4px 10px; background:#fffbeb; color:#d97706; font-size:11px; font-weight:600; border-radius:20px;">↑ Keluar</span>
                                @endif
                            </td>
                            <td style="color:#64748b; font-size:13px;">{{ ($item->tanggal_surat ?? $item->tanggal_diterima)?->format('d M Y') }}</td>
                            <td style="text-align:center;">
                                <a href="{{ route('arsip.show', $item) }}" style="display:inline-flex; align-items:center; gap:6px; padding:6px 12px; background:white; color:#3b82f6; border:1px solid #bfdbfe; border-radius:8px; font-size:12px; font-weight:600; text-decoration:none;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center; padding:48px 24px; color:#94a3b8;">
                                <p style="margin:0; font-size:14px; font-weight:500;">Belum ada surat di arsip</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($arsip->hasPages())
                <div style="padding:14px 24px; border-top:1px solid rgba(0,0,0,0.05);">
                    {{ $arsip->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
