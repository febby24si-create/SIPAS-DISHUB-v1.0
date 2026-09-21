<x-app-layout>
    <x-slot name="header">Riwayat Diklat — {{ $pegawai->nama }}</x-slot>

    <div style="display:flex; flex-direction:column; gap:20px;">
        @if (session('status'))
            <div style="display:flex; align-items:center; gap:10px; padding:14px 18px; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:14px; color:#065f46; font-size:14px; font-weight:500;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('status') }}
            </div>
        @endif

        {{-- Breadcrumb & Actions --}}
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
            <div style="display:flex; align-items:center; gap:8px; font-size:13px; color:#94a3b8;">
                <a href="{{ route('pegawai.index') }}" style="color:#64748b; text-decoration:none; font-weight:500;">Master Pegawai</a>
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                <a href="{{ route('pegawai.show', $pegawai) }}" style="color:#64748b; text-decoration:none; font-weight:500;">{{ $pegawai->nama }}</a>
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                <span style="color:#1e293b; font-weight:600;">Riwayat Diklat</span>
            </div>
            <a href="{{ route('pegawai.diklat.create', $pegawai) }}"
               style="text-decoration:none; display:inline-flex; align-items:center; gap:8px; padding:10px 18px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; border-radius:12px; font-size:13px; font-weight:600; box-shadow:0 2px 8px rgba(29,78,216,0.3);">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Diklat
            </a>
        </div>

        {{-- Info Pegawai --}}
        <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:14px; padding:14px 20px; display:flex; align-items:center; gap:14px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            <div>
                <p style="font-size:14px; font-weight:700; color:#1e293b; margin:0;">{{ $pegawai->nama }}</p>
                <p style="font-size:12px; color:#3b82f6; margin:0; font-family:monospace;">NIP: {{ $pegawai->nip }} &nbsp;·&nbsp; {{ $pegawai->jabatan ?? 'Jabatan belum diatur' }}</p>
            </div>
        </div>

        {{-- Table --}}
        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <div style="padding:16px 24px; border-bottom:1px solid rgba(0,0,0,0.05); display:flex; align-items:center; gap:10px;">
                <div style="width:32px; height:32px; background:#eff6ff; border-radius:9px; display:flex; align-items:center; justify-content:center;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                </div>
                <span style="font-size:14px; font-weight:700; color:#1e293b;">Daftar Riwayat Diklat ({{ $diklats->count() }} data)</span>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px;">No.</th>
                        <th>Nama Diklat</th>
                        <th>Penyelenggara</th>
                        <th>Tahun</th>
                        <th>JP</th>
                        <th>Sertifikat</th>
                        <th style="text-align:right; padding-right:24px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($diklats as $d)
                        <tr>
                            <td style="color:#94a3b8; font-size:13px;">{{ $loop->iteration }}.</td>
                            <td>
                                <span style="font-weight:600; color:#1e293b; font-size:14px;">{{ $d->nama_diklat }}</span>
                            </td>
                            <td style="color:#475569; font-size:13px;">{{ $d->penyelenggara ?? '-' }}</td>
                            <td style="color:#475569; font-size:13px;">{{ $d->tahun ?? '-' }}</td>
                            <td style="color:#475569; font-size:13px;">{{ $d->jam_pelajaran ? $d->jam_pelajaran . ' JP' : '-' }}</td>
                            <td>
                                @if($d->file_sertifikat)
                                    <a href="{{ asset('storage/' . $d->file_sertifikat) }}" target="_blank"
                                       style="display:inline-flex; align-items:center; gap:5px; padding:4px 10px; background:#f0fdf4; color:#15803d; font-size:12px; font-weight:600; border-radius:8px; text-decoration:none; border:1px solid #bbf7d0;">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                        Unduh
                                    </a>
                                @else
                                    <span style="color:#94a3b8; font-size:12px;">—</span>
                                @endif
                            </td>
                            <td style="text-align:right; padding-right:20px;">
                                <div style="display:inline-flex; align-items:center; gap:6px;">
                                    <a href="{{ route('pegawai.diklat.edit', [$pegawai, $d]) }}"
                                       style="display:inline-flex; align-items:center; gap:5px; padding:7px 14px; background:#f0f9ff; color:#0369a1; font-size:12px; font-weight:600; border-radius:9px; text-decoration:none; border:1px solid #bae6fd;"
                                       onmouseover="this.style.background='#e0f2fe'" onmouseout="this.style.background='#f0f9ff'">
                                        Edit
                                    </a>
                                    <form action="{{ route('pegawai.diklat.destroy', [$pegawai, $d]) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Yakin hapus riwayat diklat ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                style="display:inline-flex; align-items:center; gap:5px; padding:7px 14px; background:#fff1f2; color:#be123c; font-size:12px; font-weight:600; border-radius:9px; border:1px solid #fecdd3; cursor:pointer;"
                                                onmouseover="this.style.background='#ffe4e6'" onmouseout="this.style.background='#fff1f2'">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center; padding:48px 24px; color:#94a3b8;">
                                <p style="margin:0; font-size:14px; font-weight:500;">Belum ada riwayat diklat</p>
                                <p style="margin:4px 0 0; font-size:12px;">Klik tombol "Tambah Diklat" untuk menambahkan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
