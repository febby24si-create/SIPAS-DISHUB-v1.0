<x-app-layout>
    <x-slot name="header">Master Pegawai</x-slot>

    <div style="display:flex; flex-direction:column; gap:20px;">
        @if (session('status'))
            <div style="display:flex; align-items:center; gap:10px; padding:14px 18px; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:14px; color:#065f46; font-size:14px; font-weight:500;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('status') }}
            </div>
        @endif

        <div style="display:flex; align-items:center; justify-content:space-between;">
            <div>
                <p style="font-size:13px; color:#94a3b8; margin:0;">
                    Kelola data kepegawaian Dinas Perhubungan
                </p>
            </div>
            <a href="{{ route('pegawai.create') }}" class="btn-primary" style="text-decoration:none; display:inline-flex; align-items:center; gap:8px; padding:10px 18px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; border-radius:12px; font-size:13px; font-weight:600; box-shadow:0 2px 8px rgba(29,78,216,0.3);">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Pegawai
            </a>
        </div>

        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <div style="padding:18px 24px; border-bottom:1px solid rgba(0,0,0,0.05); display:flex; justify-content:space-between; align-items:center; gap:10px; flex-wrap:wrap;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:32px; height:32px; background:#eff6ff; border-radius:9px; display:flex; align-items:center; justify-content:center;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <span style="font-size:14px; font-weight:700; color:#1e293b;">Daftar Pegawai</span>
                </div>
                
                {{-- Filter & Search --}}
                <form action="{{ route('pegawai.index') }}" method="GET" style="display:flex; gap:10px;">
                    <select name="unit_kerja_id" class="form-control" style="width:180px; font-size:13px; padding:6px 12px; border-radius:8px;" onchange="this.form.submit()">
                        <option value="">Semua Unit Kerja</option>
                        @foreach($unitKerjas as $uk)
                            <option value="{{ $uk->id }}" {{ request('unit_kerja_id') == $uk->id ? 'selected' : '' }}>{{ $uk->nama }}</option>
                        @endforeach
                    </select>
                    <div style="position:relative;">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIP/Nama..." class="form-control" style="width:180px; font-size:13px; padding:6px 12px; padding-left:32px; border-radius:8px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position:absolute; left:10px; top:50%; transform:translateY(-50%);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </div>
                    <button type="submit" style="padding:6px 14px; background:#f1f5f9; color:#475569; font-size:13px; font-weight:600; border-radius:8px; border:1px solid #e2e8f0; cursor:pointer;">Cari</button>
                    @if(request()->hasAny(['search', 'unit_kerja_id']))
                        <a href="{{ route('pegawai.index') }}" style="padding:6px 14px; background:#fff1f2; color:#be123c; font-size:13px; font-weight:600; border-radius:8px; border:1px solid #fecdd3; cursor:pointer; text-decoration:none; display:flex; align-items:center;">Reset</a>
                    @endif
                </form>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:60px;">No.</th>
                        <th>Pegawai</th>
                        <th>Kepangkatan</th>
                        <th>Jabatan & Unit Kerja</th>
                        <th>Status</th>
                        <th style="text-align:right; padding-right:24px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pegawais as $item)
                        <tr>
                            <td style="color:#94a3b8; font-size:13px;">{{ $loop->iteration + $pegawais->firstItem() - 1 }}.</td>
                            <td>
                                <div style="display:flex; flex-direction:column;">
                                    <span style="font-weight:600; color:#1e293b; font-size:14px;">{{ $item->nama }}</span>
                                    <span style="font-family:monospace; color:#64748b; font-size:12px;">NIP: {{ $item->nip }}</span>
                                </div>
                            </td>
                            <td>
                                <div style="display:flex; flex-direction:column;">
                                    <span style="font-weight:500; color:#334155; font-size:13px;">{{ $item->pangkat ?? '-' }}</span>
                                    <span style="color:#64748b; font-size:12px;">Gol. {{ $item->golongan ?? '-' }}</span>
                                </div>
                            </td>
                            <td>
                                <div style="display:flex; flex-direction:column;">
                                    <span style="font-weight:500; color:#334155; font-size:13px;">{{ $item->jabatan ?? '-' }}</span>
                                    <span style="color:#64748b; font-size:12px;">{{ $item->unitKerja->nama ?? 'Belum diatur' }}</span>
                                </div>
                            </td>
                            <td>
                                @if($item->status_aktif)
                                    <span style="display:inline-flex; align-items:center; padding:3px 8px; background:#ecfdf5; color:#059669; font-size:11px; font-weight:700; border-radius:6px; letter-spacing:0.5px; text-transform:uppercase;">Aktif</span>
                                @else
                                    <span style="display:inline-flex; align-items:center; padding:3px 8px; background:#f1f5f9; color:#475569; font-size:11px; font-weight:700; border-radius:6px; letter-spacing:0.5px; text-transform:uppercase;">Non-Aktif</span>
                                @endif
                            </td>
                            <td style="text-align:right; padding-right:20px;">
                                <div style="display:inline-flex; align-items:center; gap:6px;">
                                    <a href="{{ route('pegawai.show', $item) }}"
                                       style="display:inline-flex; align-items:center; gap:5px; padding:7px 14px; background:#f0fdf4; color:#15803d; font-size:12px; font-weight:600; border-radius:9px; text-decoration:none; border:1px solid #bbf7d0; transition:all 0.15s;"
                                       onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fdf4'">
                                        Detail
                                    </a>
                                    <a href="{{ route('pegawai.edit', $item) }}"
                                       style="display:inline-flex; align-items:center; gap:5px; padding:7px 14px; background:#f0f9ff; color:#0369a1; font-size:12px; font-weight:600; border-radius:9px; text-decoration:none; border:1px solid #bae6fd; transition:all 0.15s;"
                                       onmouseover="this.style.background='#e0f2fe'" onmouseout="this.style.background='#f0f9ff'">
                                        Edit
                                    </a>
                                    <form action="{{ route('pegawai.destroy', $item) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Yakin ingin menghapus pegawai ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                style="display:inline-flex; align-items:center; gap:5px; padding:7px 14px; background:#fff1f2; color:#be123c; font-size:12px; font-weight:600; border-radius:9px; border:1px solid #fecdd3; cursor:pointer; transition:all 0.15s;"
                                                onmouseover="this.style.background='#ffe4e6'" onmouseout="this.style.background='#fff1f2'">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:48px 24px; color:#94a3b8;">
                                <p style="margin:0; font-size:14px; font-weight:500;">Belum ada data pegawai</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($pegawais->hasPages())
                <div style="padding:14px 24px; border-top:1px solid rgba(0,0,0,0.05);">
                    {{ $pegawais->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
