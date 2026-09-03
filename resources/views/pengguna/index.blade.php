<x-app-layout>
    <x-slot name="header">Daftar Pengguna</x-slot>

    <div style="display:flex; flex-direction:column; gap:20px;">

        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:60px;">No.</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengguna as $item)
                        <tr>
                            <td style="color:#94a3b8; font-size:13px;">{{ $loop->iteration }}.</td>
                            <td style="font-weight:500; color:#1e293b;">{{ $item->name }}</td>
                            <td style="color:#475569;">{{ $item->email }}</td>
                            <td>
                                <span style="display:inline-flex; padding:4px 10px; background:#eff6ff; color:#1d4ed8; font-size:11px; font-weight:600; border-radius:8px;">
                                    {{ $item->role->name ?? '-' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; padding:48px 24px; color:#94a3b8;">
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

        <p style="font-size:12px; color:#94a3b8; margin:0;">
            Penambahan pengguna, pengaturan hak akses, dan aktivasi/nonaktivasi akun belum dikembangkan pada tahap ini.
        </p>
    </div>
</x-app-layout>
   