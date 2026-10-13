<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; gap:12px;">
            <div style="width:40px; height:40px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#1d4ed8; flex-shrink:0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
            </div>
            <div>
                <div style="font-size:17px; font-weight:600; color:#1e293b; line-height:1.2;">Detail Jenis Surat</div>
                <div style="margin-top:2px; font-size:12.5px; color:#64748b;">{{ $jenisSurat->nama }}</div>
            </div>
        </div>
    </x-slot>

    <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- Breadcrumb / kembali --}}
        <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
            <div style="display:flex; align-items:center; gap:8px; font-size:13px; color:#94a3b8;">
                <a href="{{ route('jenis-surat.index') }}" style="color:#64748b; text-decoration:none; font-weight:500;">Jenis Surat</a>
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
                <span style="color:#1e293b; font-weight:600;">Detail</span>
            </div>

            <div style="display:flex; align-items:center; gap:8px;">
                <a href="{{ route('jenis-surat.index') }}"
                   style="display:inline-flex; align-items:center; gap:7px; padding:9px 16px; background:white; border:1px solid #e2e8f0; border-radius:10px; font-size:13px; font-weight:600; color:#334155; text-decoration:none;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    Kembali
                </a>
                <a href="{{ route('jenis-surat.edit', $jenisSurat) }}"
                   style="display:inline-flex; align-items:center; gap:7px; padding:9px 16px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); border:1px solid transparent; border-radius:10px; font-size:13px; font-weight:600; color:white; text-decoration:none;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Edit Jenis Surat
                </a>
            </div>
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:20px; align-items:start;">

            {{-- Informasi dasar --}}
            <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
                <div style="padding:16px 22px; border-bottom:1px solid rgba(0,0,0,0.05); display:flex; align-items:center; gap:10px;">
                    <div style="width:30px; height:30px; background:#eff6ff; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    </div>
                    <span style="font-size:14px; font-weight:700; color:#1e293b;">Informasi Jenis Surat</span>
                </div>

                <div style="padding:20px 22px; display:flex; flex-direction:column; gap:18px;">
                    <div style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
                        <div>
                            <div style="font-size:11.5px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px;">Kode</div>
                            <div style="margin-top:5px; display:inline-flex; align-items:center; padding:5px 12px; background:#eff6ff; color:#1d4ed8; font-size:14px; font-weight:700; border-radius:8px; font-family:monospace; letter-spacing:1px;">
                                {{ $jenisSurat->kode }}
                            </div>
                        </div>
                        <div style="font-size:12px; color:#94a3b8; text-align:right; max-width:220px;">
                            Tampil sebagai lencana pada daftar surat, arsip, dan laporan
                        </div>
                    </div>

                    <div style="border-top:1px dashed rgba(0,0,0,0.06); padding-top:16px;">
                        <div style="font-size:11.5px; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px;">Nama Jenis Surat</div>
                        <div style="margin-top:5px; font-size:15.5px; font-weight:600; color:#1e293b;">{{ $jenisSurat->nama }}</div>
                        <p style="margin:6px 0 0; font-size:12.5px; color:#64748b;">Dipakai sebagai pilihan <em>Jenis Surat</em> pada form Surat Masuk, Surat Keluar, dan filter laporan.</p>
                    </div>

                    <div style="border-top:1px dashed rgba(0,0,0,0.06); padding-top:16px; display:flex; gap:8px; font-size:12px; color:#94a3b8; flex-wrap:wrap;">
                        <span>Terakhir diperbarui: {{ optional($jenisSurat->updated_at)->format('d/m/Y H:i') ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- Statistik penggunaan --}}
            <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
                <div style="padding:16px 22px; border-bottom:1px solid rgba(0,0,0,0.05); display:flex; align-items:center; gap:10px;">
                    <div style="width:30px; height:30px; background:#ecfdf5; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#047857" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    </div>
                    <span style="font-size:14px; font-weight:700; color:#1e293b;">Penggunaan</span>
                </div>

                <div style="padding:20px 22px; display:flex; flex-direction:column; gap:14px;">
                    @if ($statistik['total'] > 0)
                        <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:10px;">
                            <div style="padding:14px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; text-align:center;">
                                <div style="font-size:22px; font-weight:700; color:#1e293b;">{{ $statistik['total'] }}</div>
                                <div style="font-size:11.5px; color:#64748b; margin-top:2px;">Total</div>
                            </div>
                            <div style="padding:14px; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:12px; text-align:center;">
                                <div style="font-size:22px; font-weight:700; color:#047857;">{{ $statistik['masuk'] }}</div>
                                <div style="font-size:11.5px; color:#065f46; margin-top:2px; display:flex; align-items:center; justify-content:center; gap:4px;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 7 7 17"/><path d="M17 17H7V7"/></svg>
                                    Masuk
                                </div>
                            </div>
                            <div style="padding:14px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:12px; text-align:center;">
                                <div style="font-size:22px; font-weight:700; color:#1d4ed8;">{{ $statistik['keluar'] }}</div>
                                <div style="font-size:11.5px; color:#1e40af; margin-top:2px; display:flex; align-items:center; justify-content:center; gap:4px;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 7h10v10"/><path d="M7 17 17 7"/></svg>
                                    Keluar
                                </div>
                            </div>
                        </div>
                        <p style="margin:0; font-size:12.5px; color:#64748b;">
                            Jenis surat ini dipakai pada surat masuk maupun surat keluar.
                        </p>
                    @else
                        <div style="padding:26px 16px; background:#f8fafc; border:1px dashed #e2e8f0; border-radius:12px; text-align:center; color:#94a3b8; font-size:13px;">
                            Jenis surat ini belum dipakai pada surat mana pun.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Daftar surat terkait (ringkas) --}}
        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <div style="padding:18px 24px; border-bottom:1px solid rgba(0,0,0,0.05); display:flex; align-items:center; justify-content:space-between; gap:10px; flex-wrap:wrap;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:32px; height:32px; background:#eff6ff; border-radius:9px; display:flex; align-items:center; justify-content:center;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    </div>
                    <span style="font-size:14px; font-weight:700; color:#1e293b;">Surat Terkait</span>
                    <span style="font-size:12px; color:#94a3b8;">menampilkan maksimal 10 terbaru</span>
                </div>
                <span style="font-size:12.5px; color:#64748b;">Total <strong style="color:#1e293b;">{{ $statistik['total'] }}</strong> surat</span>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:60px;">No.</th>
                        <th style="width:120px;">Arah</th>
                        <th style="width:180px;">Nomor Surat</th>
                        <th>Perihal</th>
                        <th style="width:130px;">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftarSurat as $surat)
                        <tr>
                            <td style="color:#94a3b8; font-size:13px;">{{ $loop->iteration }}.</td>
                            <td>
                                @if ($surat->arah === 'masuk')
                                    <span style="display:inline-flex; align-items:center; gap:5px; padding:4px 10px; background:#ecfdf5; color:#047857; font-size:11.5px; font-weight:600; border-radius:20px;">
                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 7 7 17"/><path d="M17 17H7V7"/></svg>
                                        Masuk
                                    </span>
                                @elseif ($surat->arah === 'keluar')
                                    <span style="display:inline-flex; align-items:center; gap:5px; padding:4px 10px; background:#eff6ff; color:#1d4ed8; font-size:11.5px; font-weight:600; border-radius:20px;">
                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 7h10v10"/><path d="M7 17 17 7"/></svg>
                                        Keluar
                                    </span>
                                @else
                                    <span style="display:inline-flex; align-items:center; padding:4px 10px; background:#f1f5f9; color:#475569; font-size:11.5px; font-weight:600; border-radius:20px;">Tidak Diketahui</span>
                                @endif
                            </td>
                            <td style="font-family:monospace; font-size:13px; color:#334155;">{{ $surat->nomor_surat ?: '-' }}</td>
                            <td style="color:#1e293b;">{{ $surat->perihal ?: '-' }}</td>
                            <td style="color:#64748b; font-size:13px;">{{ optional($surat->tanggal_surat)->format('d/m/Y') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:40px 24px; color:#94a3b8;">
                                <p style="margin:0; font-size:14px; font-weight:500;">Belum ada surat dengan jenis ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
