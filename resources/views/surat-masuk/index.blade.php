<x-app-layout>
    <x-slot name="header">Daftar Surat Masuk</x-slot>

    <div style="display:flex; flex-direction:column; gap:20px;">

        @if (session('status'))
            <div style="display:flex; align-items:center; gap:10px; padding:14px 18px; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:14px; color:#065f46; font-size:14px; font-weight:500;">
                {{ session('status') }}
            </div>
        @endif

        <div style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
            <form method="GET" style="flex:1; display:flex; align-items:center; gap:10px; background:white; border:1px solid rgba(0,0,0,0.07); border-radius:14px; padding:10px 16px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari perihal surat masuk..."
                       style="flex:1; border:none; outline:none; font-size:14px; color:#334155; background:transparent;">
            </form>
            <a href="{{ route('surat-masuk.create') }}" class="btn-primary" style="text-decoration:none; display:inline-flex; align-items:center; gap:8px; padding:10px 18px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; border-radius:12px; font-size:13px; font-weight:600; white-space:nowrap;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Input Surat Masuk
            </a>
        </div>

        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:60px;">No.</th>
                        <th>Nomor Surat</th>
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
                            <td style="font-family:monospace; font-size:12px; color:#334155;">{{ $item->nomor_surat ?? '-' }}</td>
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
                            <td colspan="6" style="text-align:center; padding:48px 24px; color:#94a3b8;">
                                <p style="margin:0; font-size:14px; font-weight:500;">Belum ada surat masuk</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($suratMasuk->hasPages())
                <div style="padding:14px 24px; border-top:1px solid rgba(0,0,0,0.05);">
                    {{ $suratMasuk->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
