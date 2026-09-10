<x-app-layout>
    <x-slot name="header">Pencarian Surat Resmi / Arsip</x-slot>

    <div style="display:flex; flex-direction:column; gap:20px;">

        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:24px; box-shadow:0 4px 6px -1px rgba(0,0,0,0.02);">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <h3 style="margin:0; font-size:16px; font-weight:700; color:#1e293b;">Filter Pencarian</h3>
            </div>

            <form method="GET" action="{{ route('pencarian.index') }}">
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:16px; margin-bottom:20px;">
                    <div>
                        <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Kata Kunci (Nomor/Perihal)</label>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Ketik kata kunci..."
                               style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:14px; color:#334155; box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Tanggal Surat</label>
                        <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                               style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:14px; color:#334155; box-sizing:border-box;">
                    </div>
                    <div>
                        <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Arah Surat</label>
                        <select name="arah" style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:14px; color:#334155;">
                            <option value="">Semua Arah</option>
                            <option value="masuk" @selected(request('arah') === 'masuk')>Surat Masuk</option>
                            <option value="keluar" @selected(request('arah') === 'keluar')>Surat Keluar</option>
                        </select>
                    </div>
                    <div>
                        <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Jenis Surat</label>
                        <select name="jenis_surat_id" style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:14px; color:#334155;">
                            <option value="">Semua Jenis</option>
                            @foreach($jenisSuratList as $jenis)
                                <option value="{{ $jenis->id }}" @selected(request('jenis_surat_id') == $jenis->id)>{{ $jenis->kode }} - {{ $jenis->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div x-data="{ open: {{ request()->filled('klasifikasi_id') || request()->filled('pengirim') || request()->filled('tujuan') ? 'true' : 'false' }} }" style="grid-column:1/-1;">
                        <button type="button" @click="open = !open" style="background:none; border:none; color:#3b82f6; font-size:13px; font-weight:600; cursor:pointer; padding:0; display:inline-flex; align-items:center; gap:4px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" :style="open ? 'transform:rotate(180deg)' : ''" style="transition:0.2s;"><polyline points="6 9 12 15 18 9"/></svg>
                            Filter Lanjutan
                        </button>
                        <div x-show="open" style="display:none; margin-top:16px; padding-top:16px; border-top:1px dashed #e2e8f0; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:16px;" :style="{ display: open ? 'grid' : 'none' }">
                            <div>
                                <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Klasifikasi Surat</label>
                                <select name="klasifikasi_id" style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:14px; color:#334155;">
                                    <option value="">Semua Klasifikasi</option>
                                    @foreach($klasifikasiList as $klas)
                                        <option value="{{ $klas->id }}" @selected(request('klasifikasi_id') == $klas->id)>{{ $klas->kode }} - {{ $klas->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Pengirim</label>
                                <input type="text" name="pengirim" value="{{ request('pengirim') }}" placeholder="Asal surat..."
                                       style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:14px; color:#334155; box-sizing:border-box;">
                            </div>
                            <div>
                                <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Tujuan</label>
                                <input type="text" name="tujuan" value="{{ request('tujuan') }}" placeholder="Tujuan surat..."
                                       style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:14px; color:#334155; box-sizing:border-box;">
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display:flex; gap:10px;">
                    <button type="submit" style="padding:10px 24px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; border:none; border-radius:10px; font-size:14px; font-weight:600; cursor:pointer;">
                        Cari Surat
                    </button>
                    @if(request()->hasAny(['q', 'tanggal', 'arah', 'jenis_surat_id', 'klasifikasi_id', 'pengirim', 'tujuan']))
                        <a href="{{ route('pencarian.index') }}" style="padding:10px 24px; background:white; color:#64748b; border:1px solid #e2e8f0; border-radius:10px; font-size:14px; font-weight:600; text-decoration:none;">
                            Reset Filter
                        </a>
                    @endif
                </div>
            </form>
        </div>

        @if ($hasil === null)
            <div style="text-align:center; padding:64px 24px; color:#94a3b8;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#e2e8f0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 12px; display:block;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <p style="margin:0; font-size:14px; font-weight:500;">Masukkan filter pencarian lalu klik Cari Surat.</p>
                <p style="margin:4px 0 0; font-size:12px;">Hanya surat yang sudah final (arsip) yang akan ditampilkan.</p>
            </div>
        @else
            <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:60px;">No.</th>
                            <th>Nomor & Jenis</th>
                            <th>Perihal</th>
                            <th>Arah</th>
                            <th>Pengirim/Tujuan</th>
                            <th>Tanggal</th>
                            <th style="text-align:right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($hasil as $item)
                            <tr>
                                <td style="color:#94a3b8; font-size:13px;">{{ $loop->iteration + ($hasil->currentPage() - 1) * $hasil->perPage() }}.</td>
                                <td>
                                    <div style="font-family:monospace; font-size:13px; font-weight:600; color:#1e293b; margin-bottom:4px;">{{ $item->nomor_surat ?? '-' }}</div>
                                    <span style="display:inline-flex; padding:3px 8px; background:#f1f5f9; color:#475569; font-size:11px; font-weight:600; border-radius:6px;">
                                        {{ $item->jenisSurat->kode ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight:500; color:#1e293b;">{{ $item->perihal }}</div>
                                    @if($item->klasifikasi)
                                        <div style="font-size:11px; color:#94a3b8; margin-top:4px;">Klasifikasi: {{ $item->klasifikasi->kode }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($item->arah === 'masuk')
                                        <span style="display:inline-flex; padding:4px 10px; background:#ecfdf5; color:#059669; font-size:12px; font-weight:600; border-radius:20px;">Masuk</span>
                                    @else
                                        <span style="display:inline-flex; padding:4px 10px; background:#f0f9ff; color:#0284c7; font-size:12px; font-weight:600; border-radius:20px;">Keluar</span>
                                    @endif
                                </td>
                                <td style="font-size:13px; color:#475569;">
                                    @if($item->arah === 'masuk')
                                        <div><span style="color:#94a3b8; font-size:11px; display:block;">Dari:</span> {{ $item->pengirim ?? '-' }}</div>
                                    @else
                                        <div><span style="color:#94a3b8; font-size:11px; display:block;">Ke:</span> {{ $item->tujuan ?? '-' }}</div>
                                    @endif
                                </td>
                                <td style="color:#64748b; font-size:13px;">{{ $item->tanggal_surat?->format('d M Y') }}</td>
                                <td style="text-align:right;">
                                    @php
                                        $routeDetail = $item->arah === 'masuk' ? route('surat-masuk.show', $item) : route('surat-keluar.show', $item);
                                    @endphp
                                    <a href="{{ $routeDetail }}"
                                       style="display:inline-flex; align-items:center; gap:6px; padding:6px 14px; background:#f8fafc; color:#1d4ed8; border:1px solid #e2e8f0; border-radius:8px; font-size:12px; font-weight:600; text-decoration:none;"
                                       onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center; padding:48px 24px; color:#94a3b8;">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#e2e8f0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 12px; display:block;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                                    <p style="margin:0; font-size:14px; font-weight:500;">Data surat tidak ditemukan.</p>
                                    <p style="margin:4px 0 0; font-size:13px;">Coba gunakan kata kunci atau filter lain.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($hasil->hasPages())
                    <div style="padding:14px 24px; border-top:1px solid rgba(0,0,0,0.05);">
                        {{ $hasil->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>
