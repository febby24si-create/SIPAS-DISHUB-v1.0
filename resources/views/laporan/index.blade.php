<x-app-layout>
    <x-slot name="header">Laporan Persuratan</x-slot>
    <x-slot name="breadcrumb">Laporan</x-slot>

    <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- ① FORM FILTER ────────────────────────────────────────────── --}}
        <div style="background:white; border-radius:14px; border:1px solid rgba(0,0,0,0.06); padding:20px 24px;">
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:16px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                <span style="font-size:13px; font-weight:700; color:#1e293b;">Filter Laporan</span>
            </div>
            <form method="GET" action="{{ route('laporan.index') }}" id="form-filter"
                  x-data="{
                    bidangId: '{{ $bidangId ?? '' }}',
                    seksiId: '{{ $seksiId ?? '' }}',
                    allSeksis: {{ $seksis->groupBy('parent_id')->map(fn($s) => $s->values())->toJson() }},
                    get seksiList() {
                        if (!this.bidangId) return [];
                        return this.allSeksis[this.bidangId] || [];
                    },
                    onBidangChange() {
                        if (!this.allSeksis[this.bidangId]) {
                            this.seksiId = '';
                        } else {
                            const valid = this.seksiList.find(s => String(s.id) === String(this.seksiId));
                            if (!valid) this.seksiId = '';
                        }
                    }
                  }">
                <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px; align-items:end; margin-bottom:12px;">

                    {{-- Tanggal Dari --}}
                    <div>
                        <label style="font-size:11.5px; font-weight:600; color:#64748b; display:block; margin-bottom:5px;">Dari Tanggal</label>
                        <input type="date" name="dari" id="filter-dari" value="{{ $dari ?? '' }}"
                               style="width:100%; border:1px solid #e2e8f0; border-radius:8px; padding:8px 10px; font-size:13px; color:#374151; outline:none; box-sizing:border-box;"
                               onfocus="this.style.borderColor='#1d4ed8'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    {{-- Tanggal Sampai --}}
                    <div>
                        <label style="font-size:11.5px; font-weight:600; color:#64748b; display:block; margin-bottom:5px;">Sampai Tanggal</label>
                        <input type="date" name="sampai" id="filter-sampai" value="{{ $sampai ?? '' }}"
                               style="width:100%; border:1px solid #e2e8f0; border-radius:8px; padding:8px 10px; font-size:13px; color:#374151; outline:none; box-sizing:border-box;"
                               onfocus="this.style.borderColor='#1d4ed8'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    {{-- Arah Surat --}}
                    <div>
                        <label style="font-size:11.5px; font-weight:600; color:#64748b; display:block; margin-bottom:5px;">Arah Surat</label>
                        <select name="arah" id="filter-arah"
                                style="width:100%; border:1px solid #e2e8f0; border-radius:8px; padding:8px 10px; font-size:13px; color:#374151; outline:none; background:white; box-sizing:border-box;"
                                onfocus="this.style.borderColor='#1d4ed8'" onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">Semua Arah</option>
                            <option value="masuk"  {{ ($arah ?? '') === 'masuk'  ? 'selected' : '' }}>Surat Masuk</option>
                            <option value="keluar" {{ ($arah ?? '') === 'keluar' ? 'selected' : '' }}>Surat Keluar</option>
                        </select>
                    </div>

                    {{-- Jenis Surat --}}
                    <div>
                        <label style="font-size:11.5px; font-weight:600; color:#64748b; display:block; margin-bottom:5px;">Jenis Surat</label>
                        <select name="jenis_surat_id" id="filter-jenis"
                                style="width:100%; border:1px solid #e2e8f0; border-radius:8px; padding:8px 10px; font-size:13px; color:#374151; outline:none; background:white; box-sizing:border-box;"
                                onfocus="this.style.borderColor='#1d4ed8'" onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">Semua Jenis</option>
                            @foreach ($jenisSuratList as $j)
                                <option value="{{ $j->id }}" {{ ($jenisSuratId ?? '') == $j->id ? 'selected' : '' }}>
                                    {{ $j->kode }} — {{ $j->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Klasifikasi --}}
                    <div>
                        <label style="font-size:11.5px; font-weight:600; color:#64748b; display:block; margin-bottom:5px;">Klasifikasi</label>
                        <select name="klasifikasi_id" id="filter-klasifikasi"
                                style="width:100%; border:1px solid #e2e8f0; border-radius:8px; padding:8px 10px; font-size:13px; color:#374151; outline:none; background:white; box-sizing:border-box;"
                                onfocus="this.style.borderColor='#1d4ed8'" onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">Semua Klasifikasi</option>
                            @foreach ($klasifikasiList as $k)
                                <option value="{{ $k->id }}" {{ ($klasifikasiId ?? '') == $k->id ? 'selected' : '' }}>
                                    {{ $k->kode }} — {{ $k->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Baris 2: Bidang & Seksi --}}
                <div style="display:grid; grid-template-columns:1fr 1fr 2fr; gap:12px; align-items:end; margin-bottom:0;">

                    {{-- Bidang --}}
                    <div>
                        <label style="font-size:11.5px; font-weight:600; color:#64748b; display:block; margin-bottom:5px;">Bidang</label>
                        <select name="bidang_id" id="filter-bidang" x-model="bidangId" @change="onBidangChange()"
                                style="width:100%; border:1px solid #e2e8f0; border-radius:8px; padding:8px 10px; font-size:13px; color:#374151; outline:none; background:white; box-sizing:border-box;"
                                onfocus="this.style.borderColor='#1d4ed8'" onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">Semua Bidang</option>
                            @foreach ($bidangs as $b)
                                <option value="{{ $b->id }}">{{ $b->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Seksi (dependent) --}}
                    <div>
                        <label style="font-size:11.5px; font-weight:600; color:#64748b; display:block; margin-bottom:5px;">Seksi / Unit Kerja</label>
                        <select name="seksi_id" id="filter-seksi" x-model="seksiId"
                                :disabled="!bidangId"
                                style="width:100%; border:1px solid #e2e8f0; border-radius:8px; padding:8px 10px; font-size:13px; color:#374151; outline:none; background:white; box-sizing:border-box;"
                                :style="!bidangId ? 'background:#f8fafc; color:#9ca3af; cursor:not-allowed;' : ''"
                                onfocus="this.style.borderColor='#1d4ed8'" onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">-- Pilih Bidang dahulu --</option>
                            <template x-if="bidangId">
                                <template x-for="s in seksiList" :key="s.id">
                                    <option :value="s.id" :selected="String(s.id) === String(seksiId)" x-text="s.nama"></option>
                                </template>
                            </template>
                        </select>
                        <input type="hidden" name="seksi_id_dummy" value="" x-show="false">
                    </div>

                    {{-- Spacer --}}
                    <div></div>
                </div>

                {{-- Tombol Aksi --}}
                <div style="display:flex; align-items:center; gap:10px; margin-top:14px; flex-wrap:wrap;">
                    <button type="submit"
                            style="padding:9px 22px; background:#1d4ed8; color:white; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:6px;"
                            onmouseover="this.style.background='#1e40af'" onmouseout="this.style.background='#1d4ed8'">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Terapkan Filter
                    </button>
                    @if($adaFilter)
                        <a href="{{ route('laporan.index') }}"
                           style="padding:9px 18px; background:#f1f5f9; color:#475569; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; display:flex; align-items:center; gap:6px;"
                           onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            Reset Filter
                        </a>
                    @endif
                    {{-- Tombol Print (buka di tab baru dengan filter yang sama) --}}
                    <a href="{{ route('laporan.print') }}?{{ http_build_query(request()->only(['dari','sampai','arah','jenis_surat_id','klasifikasi_id','bidang_id','seksi_id'])) }}"
                       target="_blank"
                       style="padding:9px 18px; background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; display:flex; align-items:center; gap:6px; margin-left:auto;"
                       onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fdf4'">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                        Cetak / Print
                    </a>
                </div>
            </form>
        </div>

        {{-- ② BANNER INFO FILTER AKTIF ───────────────────────────────── --}}
        @if($adaFilter)
        <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; padding:12px 18px; display:flex; align-items:center; gap:10px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <p style="font-size:12.5px; color:#1e40af; margin:0; font-weight:500;">
                <strong>Filter aktif:</strong>
                @if($dari && $sampai)
                    Periode {{ \Carbon\Carbon::parse($dari)->translatedFormat('d M Y') }} s.d. {{ \Carbon\Carbon::parse($sampai)->translatedFormat('d M Y') }}
                @elseif($dari)
                    Dari {{ \Carbon\Carbon::parse($dari)->translatedFormat('d M Y') }}
                @elseif($sampai)
                    Sampai {{ \Carbon\Carbon::parse($sampai)->translatedFormat('d M Y') }}
                @endif
                @if($arah)
                    · Arah: <strong>{{ ucfirst($arah) }}</strong>
                @endif
                @if($jenisSuratId && ($j = $jenisSuratList->firstWhere('id', $jenisSuratId)))
                    · Jenis: <strong>{{ $j->nama }}</strong>
                @endif
                @if($klasifikasiId && ($k = $klasifikasiList->firstWhere('id', $klasifikasiId)))
                    · Klasifikasi: <strong>{{ $k->nama }}</strong>
                @endif
                @if($bidangId && ($b = $bidangs->firstWhere('id', $bidangId)))
                    · Bidang: <strong>{{ $b->nama }}</strong>
                @endif
                @if($seksiId && ($s = $seksis->firstWhere('id', $seksiId)))
                    · Seksi: <strong>{{ $s->nama }}</strong>
                @endif
                &nbsp;·&nbsp; Hanya menampilkan surat <strong>final</strong>.
            </p>
        </div>
        @else
        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:11px 18px; display:flex; align-items:center; gap:10px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <p style="font-size:12px; color:#64748b; margin:0;">
                Menampilkan <strong>seluruh data surat final</strong> tanpa batasan periode. Gunakan filter di atas untuk mempersempit hasil.
            </p>
        </div>
        @endif

        {{-- ③ KARTU STATISTIK ─────────────────────────────────────────── --}}
        <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:14px;">

            {{-- Total Surat Final --}}
            <div style="background:white; border-radius:14px; border:1px solid rgba(0,0,0,0.06); padding:20px 22px;">
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
                    <div style="width:38px; height:38px; background:#eff6ff; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                    </div>
                    <span style="font-size:11.5px; font-weight:600; color:#6b7280;">Total Surat Resmi</span>
                </div>
                <p style="font-size:34px; font-weight:800; color:#111827; margin:0 0 2px 0; line-height:1;">{{ $totalSemua }}</p>
                <p style="font-size:11.5px; color:#9ca3af; margin:0;">Status: Final</p>
            </div>

            {{-- Surat Masuk --}}
            <div style="background:white; border-radius:14px; border:1px solid rgba(0,0,0,0.06); padding:20px 22px;">
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
                    <div style="width:38px; height:38px; background:#f0fdf4; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
                    </div>
                    <span style="font-size:11.5px; font-weight:600; color:#6b7280;">Surat Masuk</span>
                </div>
                <p style="font-size:34px; font-weight:800; color:#111827; margin:0 0 2px 0; line-height:1;">{{ $totalMasuk }}</p>
                <p style="font-size:11.5px; color:#9ca3af; margin:0;">Diterima &amp; Final</p>
            </div>

            {{-- Surat Keluar --}}
            <div style="background:white; border-radius:14px; border:1px solid rgba(0,0,0,0.06); padding:20px 22px;">
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
                    <div style="width:38px; height:38px; background:#fff7ed; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#c2410c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    </div>
                    <span style="font-size:11.5px; font-weight:600; color:#6b7280;">Surat Keluar</span>
                </div>
                <p style="font-size:34px; font-weight:800; color:#111827; margin:0 0 2px 0; line-height:1;">{{ $totalKeluar }}</p>
                <p style="font-size:11.5px; color:#9ca3af; margin:0;">Terkirim &amp; Final</p>
            </div>

            {{-- Draft (informatif, all-time) --}}
            <div style="background:white; border-radius:14px; border:1px solid rgba(0,0,0,0.06); padding:20px 22px;">
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
                    <div style="width:38px; height:38px; background:#f8fafc; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                    </div>
                    <span style="font-size:11.5px; font-weight:600; color:#6b7280;">Draft Belum Final</span>
                </div>
                <p style="font-size:34px; font-weight:800; color:#64748b; margin:0 0 2px 0; line-height:1;">{{ $totalDraft }}</p>
                <p style="font-size:11.5px; color:#9ca3af; margin:0;">Semua draft aktif</p>
            </div>
        </div>

        {{-- ④ TABEL PER JENIS SURAT ───────────────────────────────────── --}}
        <div style="background:white; border-radius:14px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <div style="padding:16px 24px; border-bottom:1px solid rgba(0,0,0,0.05); display:flex; align-items:center; justify-content:space-between;">
                <span style="font-size:13.5px; font-weight:700; color:#1e293b;">Jumlah Surat per Jenis</span>
                @if($totalSemua > 0)
                    <span style="font-size:12px; color:#6b7280; background:#f1f5f9; border-radius:6px; padding:3px 10px; font-weight:500;">
                        {{ $totalSemua }} surat
                    </span>
                @endif
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Jenis Surat</th>
                        <th style="text-align:right; padding-right:24px;">Masuk</th>
                        <th style="text-align:right; padding-right:24px;">Keluar</th>
                        <th style="text-align:right; padding-right:24px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($perJenis as $row)
                        <tr>
                            <td style="font-weight:500; color:#1e293b;">
                                {{ $row['jenisSurat']->nama ?? '-' }}
                                @if($row['jenisSurat']?->kode)
                                    <span style="font-size:11px; color:#9ca3af; margin-left:5px;">({{ $row['jenisSurat']->kode }})</span>
                                @endif
                            </td>
                            <td style="text-align:right; padding-right:24px;">
                                @if($row['masuk'] > 0)
                                    <span style="color:#16a34a; font-weight:600;">{{ $row['masuk'] }}</span>
                                @else
                                    <span style="color:#d1d5db;">—</span>
                                @endif
                            </td>
                            <td style="text-align:right; padding-right:24px;">
                                @if($row['keluar'] > 0)
                                    <span style="color:#c2410c; font-weight:600;">{{ $row['keluar'] }}</span>
                                @else
                                    <span style="color:#d1d5db;">—</span>
                                @endif
                            </td>
                            <td style="text-align:right; padding-right:24px; font-weight:700; color:#0f172a;">{{ $row['total'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; padding:48px 24px; color:#94a3b8;">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#e2e8f0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="display:block; margin:0 auto 10px;"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                                <p style="margin:0; font-size:13.5px; font-weight:500;">Belum ada data surat final</p>
                                <p style="margin:4px 0 0 0; font-size:12px;">
                                    {{ $adaFilter ? 'Coba ubah atau reset filter di atas.' : 'Belum ada surat yang difinalisasi.' }}
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                {{-- Footer Total --}}
                @if($perJenis->isNotEmpty())
                <tfoot>
                    <tr style="background:#f8fafc; border-top:2px solid #e2e8f0;">
                        <td style="font-weight:700; color:#1e293b; padding:12px 16px;">Total Keseluruhan</td>
                        <td style="text-align:right; padding-right:24px; font-weight:700; color:#16a34a;">{{ $totalMasuk }}</td>
                        <td style="text-align:right; padding-right:24px; font-weight:700; color:#c2410c;">{{ $totalKeluar }}</td>
                        <td style="text-align:right; padding-right:24px; font-weight:800; color:#0f172a; font-size:15px;">{{ $totalSemua }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        {{-- ⑤ LINK NAVIGASI ───────────────────────────────────────────── --}}
        <div style="display:flex; gap:12px; flex-wrap:wrap;">
            <a href="{{ route('surat-masuk.index') }}"
               style="display:inline-flex; align-items:center; gap:7px; padding:9px 18px; background:white; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; font-weight:600; color:#374151; text-decoration:none;"
               onmouseover="this.style.borderColor='#1d4ed8';this.style.color='#1d4ed8'" onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#374151'">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
                Lihat Daftar Surat Masuk
            </a>
            <a href="{{ route('surat-keluar.index') }}"
               style="display:inline-flex; align-items:center; gap:7px; padding:9px 18px; background:white; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; font-weight:600; color:#374151; text-decoration:none;"
               onmouseover="this.style.borderColor='#1d4ed8';this.style.color='#1d4ed8'" onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#374151'">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                Lihat Daftar Surat Keluar
            </a>
            <a href="{{ route('arsip.index') }}"
               style="display:inline-flex; align-items:center; gap:7px; padding:9px 18px; background:white; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; font-weight:600; color:#374151; text-decoration:none;"
               onmouseover="this.style.borderColor='#1d4ed8';this.style.color='#1d4ed8'" onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#374151'">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8v13H3V8"/><path d="M1 3h22v5H1z"/><path d="M10 12h4"/></svg>
                Lihat Arsip Digital
            </a>
        </div>

    </div>
</x-app-layout>
