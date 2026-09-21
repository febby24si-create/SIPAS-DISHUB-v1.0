<x-app-layout>
    <x-slot name="header">Daftar Pengguna</x-slot>

    <div style="display:flex; flex-direction:column; gap:20px;">

        @if (session('status'))
            <div style="display:flex; align-items:center; gap:10px; padding:14px 18px; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:14px; color:#065f46; font-size:14px; font-weight:500;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div style="display:flex; align-items:center; gap:10px; padding:14px 18px; background:#fef2f2; border:1px solid #fecaca; border-radius:14px; color:#991b1b; font-size:14px; font-weight:500;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <div style="display:flex; justify-content:flex-end;">
            {{-- Tombol Tambah Pengguna telah dinonaktifkan (Single Admin) --}}
        </div>

        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:60px;">No.</th>
                        <th>Nama &amp; Email</th>
                        <th>Role</th>
                        <th>Pegawai Terhubung</th>
                        <th>Status</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengguna as $item)
                        <tr>
                            <td style="color:#94a3b8; font-size:13px;">{{ $loop->iteration }}.</td>
                            <td>
                                <div style="font-weight:600; color:#1e293b; font-size:14px;">{{ $item->name }}</div>
                                <div style="color:#64748b; font-size:12px;">{{ $item->email }}</div>
                            </td>
                            <td>
                                <span style="display:inline-flex; padding:4px 10px; background:#eff6ff; color:#1d4ed8; font-size:11px; font-weight:600; border-radius:8px;">
                                    {{ $item->role->name ?? '-' }}
                                </span>
                            </td>
                            <td>
                                @if($item->pegawai)
                                    <div style="font-weight:500; color:#1e293b; font-size:13px;">{{ $item->pegawai->nama }}</div>
                                    <div style="color:#64748b; font-size:11px; font-family:monospace;">NIP: {{ $item->pegawai->nip ?? '-' }}</div>
                                @else
                                    <span style="color:#94a3b8; font-size:12px; font-style:italic;">Belum terhubung</span>
                                @endif
                            </td>
                            <td>
                                @if($item->is_active)
                                    <span style="display:inline-flex; align-items:center; gap:4px; color:#16a34a; font-size:12px; font-weight:600;">
                                        <div style="width:6px; height:6px; border-radius:50%; background:#16a34a;"></div> Aktif
                                    </span>
                                @else
                                    <span style="display:inline-flex; align-items:center; gap:4px; color:#dc2626; font-size:12px; font-weight:600;">
                                        <div style="width:6px; height:6px; border-radius:50%; background:#dc2626;"></div> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td style="text-align:right; display:flex; justify-content:flex-end; gap:6px;">
                                <a href="{{ route('pengguna.edit', $item) }}" style="padding:6px 12px; background:#f1f5f9; color:#475569; border-radius:6px; font-size:12px; font-weight:600; text-decoration:none;">
                                    Edit
                                </a>
                                @if(auth()->id() !== $item->id)
                                    <form method="POST" action="{{ route('pengguna.toggle-status', $item) }}" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" onclick="return confirm('Yakin ingin {{ $item->is_active ? 'menonaktifkan' : 'mengaktifkan' }} akun ini?')"
                                                style="padding:6px 12px; background:{{ $item->is_active ? '#fef2f2' : '#f0fdf4' }}; color:{{ $item->is_active ? '#dc2626' : '#166534' }}; border:none; border-radius:6px; font-size:12px; font-weight:600; cursor:pointer;">
                                            {{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:48px 24px; color:#94a3b8;">
                                <p style="margin:0; font-size:14px; font-weight:500;">Belum ada pengguna</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($pengguna->hasPages())
                <div style="padding:14px 24px; border-top:1px solid rgba(0,0,0,0.05);">
                    {{ $pengguna->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>