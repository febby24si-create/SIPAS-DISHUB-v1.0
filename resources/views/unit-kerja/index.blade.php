<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; gap:12px;">
            <div style="width:40px; height:40px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#15803d; flex-shrink:0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><line x1="12" y1="12" x2="12" y2="12.01"/><line x1="12" y1="16" x2="12" y2="16.01"/></svg>
            </div>
            <div>
                <div style="font-size:17px; font-weight:600; color:#1e293b; line-height:1.2;">Unit Kerja</div>
                <div style="margin-top:2px; font-size:12.5px; color:#64748b;">Kelola data bidang, seksi, dan unit kerja</div>
            </div>
        </div>
    </x-slot>

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
                    @forelse ($unitKerja as $bidang)
                        <!-- Render Bidang -->
                        <tr style="background:#f8fafc;">
                            <td style="color:#94a3b8; font-size:13px; font-weight:600;">{{ $loop->iteration }}.</td>
                            <td>
                                <span style="display:inline-flex; align-items:center; padding:4px 10px; background:#eff6ff; color:#1d4ed8; font-size:12px; font-weight:700; border-radius:8px; font-family:monospace; letter-spacing:0.5px;">
                                    {{ $bidang->kode_unit }}
                                </span>
                            </td>
                            <td style="font-weight:700; color:#1e293b;">{{ $bidang->nama }}</td>
                            <td style="color:#64748b; font-size:13px;">
                                @if($bidang->kepala)
                                    <span style="font-weight:600; color:#334155;">{{ $bidang->kepala->nama }}</span><br>
                                    NIP: {{ $bidang->kepala->nip }}
                                @else
                                    <span style="color:#94a3b8; font-style:italic;">Belum diatur</span>
                                @endif
                            </td>
                            <td style="text-align:right; padding-right:20px;">
                                <x-action-group>
                                    <x-action-btn type="edit" url="{{ route('unit-kerja.edit', $bidang) }}" />
                                    <x-action-delete action="{{ route('unit-kerja.destroy', $bidang) }}" confirmMessage="Yakin ingin menghapus Bidang ini?" />
                                </x-action-group>
                            </td>
                        </tr>
                        <!-- Render Seksi / Children -->
                        @foreach($bidang->children as $seksi)
                        <tr>
                            <td></td>
                            <td>
                                <span style="display:inline-flex; align-items:center; padding:4px 8px; background:#f1f5f9; color:#475569; font-size:11px; font-weight:600; border-radius:6px; font-family:monospace; margin-left:15px;">
                                    {{ $seksi->kode_unit }}
                                </span>
                            </td>
                            <td style="font-weight:500; color:#334155;">
                                <span style="color:#cbd5e1; font-family:monospace; margin-right:5px; margin-left:10px;">{{ $loop->last ? '└─' : '├─' }}</span>
                                {{ $seksi->nama }}
                            </td>
                            <td style="color:#64748b; font-size:13px;">
                                @if($seksi->kepala)
                                    <span style="font-weight:600; color:#334155;">{{ $seksi->kepala->nama }}</span><br>
                                    NIP: {{ $seksi->kepala->nip }}
                                @else
                                    <span style="color:#94a3b8; font-style:italic;">Belum diatur</span>
                                @endif
                            </td>
                            <td style="text-align:right; padding-right:20px;">
                                <x-action-group>
                                    <x-action-btn type="edit" url="{{ route('unit-kerja.edit', $seksi) }}" />
                                    <x-action-delete action="{{ route('unit-kerja.destroy', $seksi) }}" confirmMessage="Yakin ingin menghapus Seksi ini?" />
                                </x-action-group>
                            </td>
                        </tr>
                        @endforeach
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
