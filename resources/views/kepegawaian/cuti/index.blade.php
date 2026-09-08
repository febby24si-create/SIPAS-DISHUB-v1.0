<x-app-layout>
    <x-slot name="header">Daftar Pengajuan Cuti</x-slot>

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

        {{-- Toolbar --}}
        <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
            <div style="flex:1;"></div>
            @if(in_array(auth()->user()->role?->name, ['admin', 'staff']))
                <a href="{{ route('kepegawaian.cuti.create') }}" style="text-decoration:none; display:inline-flex; align-items:center; gap:8px; padding:10px 18px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; border-radius:12px; font-size:13px; font-weight:600; white-space:nowrap;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Buat Pengajuan Cuti
                </a>
            @endif
        </div>

        {{-- Table --}}
        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <table class="data-table" style="width:100%; border-collapse:collapse; text-align:left;">
                <thead>
                    <tr style="border-bottom:1px solid rgba(0,0,0,0.05); background:#f8fafc;">
                        <th style="padding:16px 24px; font-size:12px; font-weight:600; color:#64748b; text-transform:uppercase; width:50px;">No.</th>
                        <th style="padding:16px 24px; font-size:12px; font-weight:600; color:#64748b; text-transform:uppercase;">Nomor & Jenis Cuti</th>
                        <th style="padding:16px 24px; font-size:12px; font-weight:600; color:#64748b; text-transform:uppercase;">Pegawai</th>
                        <th style="padding:16px 24px; font-size:12px; font-weight:600; color:#64748b; text-transform:uppercase;">Waktu Cuti</th>
                        <th style="padding:16px 24px; font-size:12px; font-weight:600; color:#64748b; text-transform:uppercase;">Lama</th>
                        <th style="padding:16px 24px; font-size:12px; font-weight:600; color:#64748b; text-transform:uppercase;">Status</th>
                        <th style="padding:16px 24px; width:100px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cuti as $item)
                        <tr style="border-bottom:1px solid rgba(0,0,0,0.05);">
                            <td style="padding:16px 24px; color:#94a3b8; font-size:13px;">{{ $cuti->firstItem() + $loop->index }}.</td>
                            <td style="padding:16px 24px;">
                                <div style="font-family:monospace; font-size:12px; color:#334155;">{{ $item->nomor_pengajuan }}</div>
                                <div style="font-weight:600; color:#1e293b; font-size:13px; margin-top:4px;">{{ $item->jenis_cuti }}</div>
                            </td>
                            <td style="padding:16px 24px;">
                                <div style="font-weight:500; color:#1e293b; font-size:13px;">{{ $item->pegawai->nama }}</div>
                                <div style="color:#64748b; font-size:12px; margin-top:2px;">NIP. {{ $item->pegawai->nip }}</div>
                            </td>
                            <td style="padding:16px 24px; font-size:13px; color:#475569;">
                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }} -<br>
                                {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}
                            </td>
                            <td style="padding:16px 24px; font-size:13px; color:#475569;">
                                {{ $item->lama_cuti }} hari
                            </td>
                            <td style="padding:16px 24px;">
                                @php
                                    $statusColors = [
                                        'draft'=>['#f1f5f9','#475569'],
                                        'diajukan'=>['#eff6ff','#1d4ed8'],
                                        'verifikasi'=>['#fef3c7','#b45309'],
                                        'disetujui'=>['#ecfdf5','#059669'],
                                        'diterbitkan'=>['#fdf4ff','#a21caf'],
                                        'selesai'=>['#f0fdf4','#166534'],
                                        'ditolak'=>['#fef2f2','#b91c1c'],
                                    ];
                                    $sc = $statusColors[$item->status] ?? ['#f1f5f9','#475569'];
                                @endphp
                                <span style="display:inline-flex; padding:4px 10px; background:{{ $sc[0] }}; color:{{ $sc[1] }}; font-size:11px; font-weight:600; border-radius:8px; text-transform:capitalize;">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td style="padding:16px 24px; text-align:right;">
                                <div style="display:flex; gap:8px; justify-content:flex-end;">
                                    <a href="{{ route('kepegawaian.cuti.show', $item) }}" title="Detail" style="font-size:12px; font-weight:600; color:#1d4ed8; text-decoration:none;">Detail</a>
                                    @if($item->status === 'draft' && in_array(auth()->user()->role?->name, ['admin', 'staff']))
                                        <a href="{{ route('kepegawaian.cuti.edit', $item) }}" title="Edit" style="font-size:12px; font-weight:600; color:#64748b; text-decoration:none;">Edit</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center; padding:48px 24px; color:#94a3b8;">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 12px;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                <p style="margin:0; font-size:14px; font-weight:500;">Belum ada pengajuan cuti</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($cuti->hasPages())
                <div style="padding:14px 24px; border-top:1px solid rgba(0,0,0,0.05);">
                    {{ $cuti->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
