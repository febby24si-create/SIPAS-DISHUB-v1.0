<x-app-layout>
    <x-slot name="header">Unit Kerja</x-slot>

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
                    Kelola data master Unit Kerja Dinas Perhubungan
                </p>
            </div>
            <a href="{{ route('unit-kerja.create') }}" class="btn-primary" style="text-decoration:none; display:inline-flex; align-items:center; gap:8px; padding:10px 18px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; border-radius:12px; font-size:13px; font-weight:600; box-shadow:0 2px 8px rgba(29,78,216,0.3);">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Unit Kerja
            </a>
        </div>

        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <div style="padding:18px 24px; border-bottom:1px solid rgba(0,0,0,0.05); display:flex; align-items:center; gap:10px;">
                <div style="width:32px; height:32px; background:#eff6ff; border-radius:9px; display:flex; align-items:center; justify-content:center;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
                <span style="font-size:14px; font-weight:700; color:#1e293b;">Daftar Unit Kerja</span>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:80px;">No.</th>
                        <th>Kode Unit</th>
                        <th>Nama Unit Kerja</th>
                        <th>Kepala Unit</th>
                        <th style="text-align:right; padding-right:24px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($unitKerja as $item)
                        <tr>
                            <td style="color:#94a3b8; font-size:13px;">{{ $loop->iteration }}.</td>
                            <td>
                                <span style="display:inline-flex; align-items:center; padding:4px 10px; background:#eff6ff; color:#1d4ed8; font-size:12px; font-weight:700; border-radius:8px; font-family:monospace; letter-spacing:0.5px;">
                                    {{ $item->kode_unit }}
                                </span>
                            </td>
                            <td style="font-weight:500; color:#1e293b;">{{ $item->nama }}</td>
                            <td style="color:#64748b; font-size:13px;">
                                @if($item->kepala)
                                    <span style="font-weight:600; color:#334155;">{{ $item->kepala->nama }}</span><br>
                                    NIP: {{ $item->kepala->nip }}
                                @else
                                    <span style="color:#94a3b8; font-style:italic;">Belum diatur</span>
                                @endif
                            </td>
                            <td style="text-align:right; padding-right:20px;">
                                <div style="display:inline-flex; align-items:center; gap:6px;">
                                    <a href="{{ route('unit-kerja.edit', $item) }}"
                                       style="display:inline-flex; align-items:center; gap:5px; padding:7px 14px; background:#f0f9ff; color:#0369a1; font-size:12px; font-weight:600; border-radius:9px; text-decoration:none; border:1px solid #bae6fd; transition:all 0.15s;"
                                       onmouseover="this.style.background='#e0f2fe'" onmouseout="this.style.background='#f0f9ff'">
                                        Edit
                                    </a>
                                    <form action="{{ route('unit-kerja.destroy', $item) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Yakin ingin menghapus unit kerja ini?')">
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
                            <td colspan="5" style="text-align:center; padding:48px 24px; color:#94a3b8;">
                                <p style="margin:0; font-size:14px; font-weight:500;">Belum ada data unit kerja</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($unitKerja->hasPages())
                <div style="padding:14px 24px; border-top:1px solid rgba(0,0,0,0.05);">
                    {{ $unitKerja->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
