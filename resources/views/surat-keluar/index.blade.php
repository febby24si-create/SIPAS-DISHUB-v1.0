<x-app-layout>
    <x-slot name="header">Daftar Surat Keluar</x-slot>

    <div style="display:flex; flex-direction:column; gap:20px;">

        @if (session('status'))
            <div style="display:flex; align-items:center; gap:10px; padding:14px 18px; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:14px; color:#065f46; font-size:14px; font-weight:500;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div style="display:flex; align-items:center; gap:10px; padding:14px 18px; background:#fef2f2; border:1px solid #fecaca; border-radius:14px; color:#991b1b; font-size:14px; font-weight:500;">
                {{ session('error') }}
            </div>
        @endif

        {{-- Toolbar --}}
        <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
            <form method="GET" style="flex:1; min-width:240px; display:flex; align-items:center; gap:10px; background:white; border:1px solid rgba(0,0,0,0.07); border-radius:14px; padding:10px 16px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nomor, perihal, tujuan..."
                       style="flex:1; border:none; outline:none; font-size:14px; color:#334155; background:transparent;">
                @if(request()->hasAny(['q','jenis_surat_id','status','dari','sampai']))
                    <a href="{{ route('surat-keluar.index') }}" style="font-size:11px; color:#94a3b8; text-decoration:none; white-space:nowrap;">Hapus filter</a>
                @endif
            </form>
            <div style="display:flex; gap:8px;">
                <a href="{{ route('surat-keluar.draft') }}" style="text-decoration:none; display:inline-flex; align-items:center; gap:6px; padding:10px 16px; background:white; color:#64748b; border:1px solid #e2e8f0; border-radius:12px; font-size:13px; font-weight:600; white-space:nowrap;">
                    Draft
                </a>
                <a href="{{ route('surat-keluar.create') }}" style="text-decoration:none; display:inline-flex; align-items:center; gap:8px; padding:10px 18px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; border-radius:12px; font-size:13px; font-weight:600; white-space:nowrap;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Input Surat Keluar
                </a>
                <a href="{{ route('buat-surat.create') }}" style="text-decoration:none; display:inline-flex; align-items:center; gap:8px; padding:10px 18px; background:linear-gradient(135deg,#7c3aed,#a855f7); color:white; border-radius:12px; font-size:13px; font-weight:600; white-space:nowrap;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Buat dari Template
                </a>
            </div>
        </div>

        {{-- Filter --}}
        <form method="GET" style="display:flex; gap:10px; flex-wrap:wrap; align-items:flex-end;">
            @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
            <div>
                <label style="display:block; font-size:11px; font-weight:600; color:#64748b; margin-bottom:4px;">Jenis Surat</label>
                <select name="jenis_surat_id" style="padding:8px 12px; border:1px solid #e2e8f0; border-radius:10px; font-size:13px; color:#334155;" onchange="this.form.submit()">
                    <option value="">Semua Jenis</option>
                    @foreach($jenisSuratList as $js)
                        <option value="{{ $js->id }}" @selected(request('jenis_surat_id') == $js->id)>{{ $js->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:600; color:#64748b; margin-bottom:4px;">Status</label>
                <select name="status" style="padding:8px 12px; border:1px solid #e2e8f0; border-radius:10px; font-size:13px; color:#334155;" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="final" @selected(request('status')=='final')>Final</option>
                    <option value="draft" @selected(request('status')=='draft')>Draft</option>
                </select>
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:600; color:#64748b; margin-bottom:4px;">Dari</label>
                <input type="date" name="dari" value="{{ request('dari') }}" style="padding:8px 12px; border:1px solid #e2e8f0; border-radius:10px; font-size:13px;" onchange="this.form.submit()">
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:600; color:#64748b; margin-bottom:4px;">Sampai</label>
                <input type="date" name="sampai" value="{{ request('sampai') }}" style="padding:8px 12px; border:1px solid #e2e8f0; border-radius:10px; font-size:13px;" onchange="this.form.submit()">
            </div>
        </form>

        {{-- Table --}}
        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px;">No.</th>
                        <th>Nomor Surat</th>
                        <th>Perihal</th>
                        <th>Tujuan</th>
                        <th>Jenis</th>
                        <th>Tgl. Surat</th>
                        <th>Status</th>
                        <th style="width:100px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suratKeluar as $item)
                        <tr>
                            <td style="color:#94a3b8; font-size:13px;">{{ $suratKeluar->firstItem() + $loop->index }}.</td>
                            <td style="font-family:monospace; font-size:12px; color:#334155;">{{ $item->nomor_surat ?? '-' }}</td>
                            <td style="font-weight:500; color:#1e293b; max-width:220px;">
                                <a href="{{ route('surat-keluar.show', $item) }}" style="color:#1e293b; text-decoration:none;">
                                    {{ Str::limit($item->perihal, 60) }}
                                </a>
                            </td>
                            <td style="color:#475569;">{{ $item->tujuan ?? '-' }}</td>
                            <td>
                                <span style="display:inline-flex; padding:4px 10px; background:#fffbeb; color:#d97706; font-size:11px; font-weight:600; border-radius:8px;">
                                    {{ $item->jenisSurat->kode ?? '-' }}
                                </span>
                            </td>
                            <td style="color:#64748b; font-size:13px;">{{ $item->tanggal_surat?->format('d M Y') }}</td>
                            <td>
                                @php
                                    $sc = $item->status === 'final' ? ['#ecfdf5','#065f46'] : ['#f1f5f9','#475569'];
                                @endphp
                                <span style="display:inline-flex; padding:4px 10px; background:{{ $sc[0] }}; color:{{ $sc[1] }}; font-size:11px; font-weight:600; border-radius:8px; text-transform:capitalize;">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td style="text-align:right; padding-right:16px;">
                                <div style="display:flex; gap:8px; justify-content:flex-end;">
                                    <a href="{{ route('surat-keluar.show', $item) }}" style="font-size:12px; font-weight:600; color:#1d4ed8; text-decoration:none;">Detail</a>
                                    <a href="{{ route('surat-keluar.edit', $item) }}" style="font-size:12px; font-weight:600; color:#64748b; text-decoration:none;">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center; padding:48px 24px; color:#94a3b8;">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 12px;"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                                <p style="margin:0; font-size:14px; font-weight:500;">Belum ada surat keluar</p>
                                <p style="margin:4px 0 0; font-size:13px; color:#cbd5e1;">Mulai catat atau buat surat keluar baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($suratKeluar->hasPages())
                <div style="padding:14px 24px; border-top:1px solid rgba(0,0,0,0.05);">
                    {{ $suratKeluar->links() }}
                </div>
            @endif
        </div>

        <p style="font-size:12px; color:#94a3b8; margin:0;">
            Menampilkan {{ $suratKeluar->count() }} dari {{ $suratKeluar->total() }} surat keluar.
        </p>
    </div>
</x-app-layout>
