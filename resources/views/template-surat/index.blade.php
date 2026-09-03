<x-app-layout>
    <x-slot name="header">Template Surat</x-slot>

    <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- Alert --}}
        @if (session('status'))
            <div style="display:flex; align-items:center; gap:10px; padding:14px 18px; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:14px; color:#065f46; font-size:14px; font-weight:500;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('status') }}
            </div>
        @endif

        {{-- Header Row --}}
        <div style="display:flex; align-items:center; justify-content:space-between;">
            <div>
                <p style="font-size:13px; color:#94a3b8; margin:0;">
                    Kelola template dokumen surat
                </p>
            </div>
            <a href="{{ route('template-surat.create') }}"
               style="text-decoration:none; display:inline-flex; align-items:center; gap:8px; padding:10px 18px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; border-radius:12px; font-size:13px; font-weight:600; box-shadow:0 2px 8px rgba(29,78,216,0.3);">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Template
            </a>
        </div>

        {{-- Table Card --}}
        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <div style="padding:18px 24px; border-bottom:1px solid rgba(0,0,0,0.05); display:flex; align-items:center; gap:10px;">
                <div style="width:32px; height:32px; background:#f0f9ff; border-radius:9px; display:flex; align-items:center; justify-content:center;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#0369a1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                </div>
                <span style="font-size:14px; font-weight:700; color:#1e293b;">Daftar Template Surat</span>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:60px;">No.</th>
                        <th>Nama Template</th>
                        <th>Jenis Surat</th>
                        <th>Status</th>
                        <th style="text-align:right; padding-right:24px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($templates as $item)
                        <tr>
                            <td style="color:#94a3b8; font-size:13px;">{{ $loop->iteration }}.</td>
                            <td>
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <div style="width:32px; height:32px; background:#f8fafc; border:1px solid rgba(0,0,0,0.06); border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                                    </div>
                                    <span style="font-weight:600; color:#1e293b; font-size:14px;">{{ $item->nama_template }}</span>
                                </div>
                            </td>
                            <td>
                                <span style="display:inline-flex; align-items:center; padding:4px 10px; background:#eff6ff; color:#1d4ed8; font-size:12px; font-weight:600; border-radius:8px;">
                                    {{ $item->jenisSurat->nama }}
                                </span>
                            </td>
                            <td>
                                @if ($item->status === 'aktif')
                                    <span style="display:inline-flex; align-items:center; gap:5px; padding:4px 10px; background:#ecfdf5; color:#059669; font-size:12px; font-weight:600; border-radius:20px;">
                                        <span style="width:6px; height:6px; background:#059669; border-radius:50%;"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span style="display:inline-flex; align-items:center; gap:5px; padding:4px 10px; background:#f1f5f9; color:#64748b; font-size:12px; font-weight:600; border-radius:20px;">
                                        <span style="width:6px; height:6px; background:#94a3b8; border-radius:50%;"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td style="text-align:right; padding-right:20px;">
                                <div style="display:inline-flex; align-items:center; gap:6px;">
                                    <a href="{{ route('template-surat.edit', $item) }}"
                                       style="display:inline-flex; align-items:center; gap:5px; padding:7px 14px; background:#f0f9ff; color:#0369a1; font-size:12px; font-weight:600; border-radius:9px; text-decoration:none; border:1px solid #bae6fd; transition:all 0.15s;"
                                       onmouseover="this.style.background='#e0f2fe'" onmouseout="this.style.background='#f0f9ff'">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('template-surat.destroy', $item) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Yakin ingin menghapus template ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                style="display:inline-flex; align-items:center; gap:5px; padding:7px 14px; background:#fff1f2; color:#be123c; font-size:12px; font-weight:600; border-radius:9px; border:1px solid #fecdd3; cursor:pointer; transition:all 0.15s;"
                                                onmouseover="this.style.background='#ffe4e6'" onmouseout="this.style.background='#fff1f2'">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:48px 24px; color:#94a3b8;">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#e2e8f0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 12px; display:block;"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                                <p style="margin:0; font-size:14px; font-weight:500;">Belum ada template surat</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($templates->hasPages())
                <div style="padding:14px 24px; border-top:1px solid rgba(0,0,0,0.05);">
                    {{ $templates->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
