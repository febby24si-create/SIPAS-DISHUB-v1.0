<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; gap:12px;">
            <div style="width:40px; height:40px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#1d4ed8; flex-shrink:0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
            </div>
            <div>
                <div style="font-size:17px; font-weight:600; color:#1e293b; line-height:1.2;">Laporan Persuratan</div>
                <div style="margin-top:2px; font-size:12.5px; color:#64748b;">Rekapitulasi data surat masuk dan surat keluar</div>
            </div>
        </div>
    </x-slot>

    {{-- Header Tipis --}}
    <div style="margin-bottom:14px;">
        <p style="margin:0; font-size:13px; color:#64748b;">Rekapitulasi data surat masuk dan surat keluar — Dinas Perhubungan Provinsi Riau</p>
    </div>

    {{-- Ringkasan Compact --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:14px; margin-bottom:18px;">
        {{-- Total Surat --}}
        <div class="card" style="padding:14px 16px; display:flex; align-items:center; gap:14px;">
            <div style="width:38px; height:38px; border-radius:8px; background:var(--tc-accent-soft, #eff6ff); color:var(--tc-accent, #1d4ed8); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            </div>
            <div>
                <div style="font-size:12px; color:var(--text-muted, #64748b); font-weight:500;">Total Surat Resmi</div>
                <div style="font-size:22px; font-weight:700; color:var(--text-primary, #1e293b); line-height:1.2; margin-top:2px;">{{ $totalSemua }}</div>
            </div>
        </div>

        {{-- Surat Masuk --}}
        <div class="card" style="padding:14px 16px; display:flex; align-items:center; gap:14px;">
            <div style="width:38px; height:38px; border-radius:8px; background:#f0fdf4; color:#15803d; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/><polyline points="12 11 8 7 4 11"/><line x1="8" y1="18" x2="8" y2="7"/></svg>
            </div>
            <div>
                <div style="font-size:12px; color:var(--text-muted, #64748b); font-weight:500;">Surat Masuk</div>
                <div style="font-size:22px; font-weight:700; color:var(--text-primary, #1e293b); line-height:1.2; margin-top:2px;">{{ $totalMasuk }}</div>
            </div>
        </div>

        {{-- Surat Keluar --}}
        <div class="card" style="padding:14px 16px; display:flex; align-items:center; gap:14px;">
            <div style="width:38px; height:38px; border-radius:8px; background:#fff7ed; color:#c2410c; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2 11 13"/><path d="M22 2 15 22 11 13 2 9 22 2"/></svg>
            </div>
            <div>
                <div style="font-size:12px; color:var(--text-muted, #64748b); font-weight:500;">Surat Keluar</div>
                <div style="font-size:22px; font-weight:700; color:var(--text-primary, #1e293b); line-height:1.2; margin-top:2px;">{{ $totalKeluar }}</div>
            </div>
        </div>

        {{-- Draft --}}
        <div class="card" style="padding:14px 16px; display:flex; align-items:center; gap:14px;">
            <div style="width:38px; height:38px; border-radius:8px; background:#fffbeb; color:#b45309; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            </div>
            <div>
                <div style="font-size:12px; color:var(--text-muted, #64748b); font-weight:500;">Draft Belum Final</div>
                <div style="font-size:22px; font-weight:700; color:var(--text-primary, #1e293b); line-height:1.2; margin-top:2px;">{{ $totalDraft }}</div>
            </div>
        </div>
    </div>

    {{-- Filter Panel --}}
    <div class="card" style="padding:16px 20px; margin-bottom:14px;"
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
        <form method="GET" action="{{ route('laporan.index') }}" id="form-filter">
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:12px; align-items:end; margin-bottom:12px;">
                {{-- Tanggal Dari --}}
                <div>
                    <label style="font-size:11.5px; font-weight:600; color:#64748b; display:block; margin-bottom:4px;">Tanggal Mulai</label>
                    <input type="date" name="dari" value="{{ $dari ?? '' }}"
                           style="width:100%; border:1px solid #cbd5e1; border-radius:6px; padding:6px 10px; font-size:13px; color:#374151; outline:none;"
                           onfocus="this.style.borderColor='#1d4ed8'" onblur="this.style.borderColor='#cbd5e1'">
                </div>

                {{-- Tanggal Sampai --}}
                <div>
                    <label style="font-size:11.5px; font-weight:600; color:#64748b; display:block; margin-bottom:4px;">Tanggal Selesai</label>
                    <input type="date" name="sampai" value="{{ $sampai ?? '' }}"
                           style="width:100%; border:1px solid #cbd5e1; border-radius:6px; padding:6px 10px; font-size:13px; color:#374151; outline:none;"
                           onfocus="this.style.borderColor='#1d4ed8'" onblur="this.style.borderColor='#cbd5e1'">
                </div>

                {{-- Arah Surat --}}
                <div>
                    <label style="font-size:11.5px; font-weight:600; color:#64748b; display:block; margin-bottom:4px;">Arah Surat</label>
                    <select name="arah"
                            style="width:100%; border:1px solid #cbd5e1; border-radius:6px; padding:6px 10px; font-size:13px; color:#374151; outline:none; background:white;"
                            onfocus="this.style.borderColor='#1d4ed8'" onblur="this.style.borderColor='#cbd5e1'">
                        <option value="">Semua Arah</option>
                        <option value="masuk"  {{ ($arah ?? '') === 'masuk'  ? 'selected' : '' }}>Surat Masuk</option>
                        <option value="keluar" {{ ($arah ?? '') === 'keluar' ? 'selected' : '' }}>Surat Keluar</option>
                    </select>
                </div>
            
                {{-- Jenis Surat --}}
                <div>
                    <label style="font-size:11.5px; font-weight:600; color:#64748b; display:block; margin-bottom:4px;">Jenis Surat</label>
                    <select name="jenis_surat_id"
                            style="width:100%; border:1px solid #cbd5e1; border-radius:6px; padding:6px 10px; font-size:13px; color:#374151; outline:none; background:white;"
                            onfocus="this.style.borderColor='#1d4ed8'" onblur="this.style.borderColor='#cbd5e1'">
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
                    <label style="font-size:11.5px; font-weight:600; color:#64748b; display:block; margin-bottom:4px;">Klasifikasi</label>
                    <select name="klasifikasi_id"
                            style="width:100%; border:1px solid #cbd5e1; border-radius:6px; padding:6px 10px; font-size:13px; color:#374151; outline:none; background:white;"
                            onfocus="this.style.borderColor='#1d4ed8'" onblur="this.style.borderColor='#cbd5e1'">
                        <option value="">Semua Klasifikasi</option>
                        @foreach ($klasifikasiList as $k)
                            <option value="{{ $k->id }}" {{ ($klasifikasiId ?? '') == $k->id ? 'selected' : '' }}>
                                {{ $k->kode }} — {{ $k->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Bidang --}}
                <div>
                    <label style="font-size:11.5px; font-weight:600; color:#64748b; display:block; margin-bottom:4px;">Bidang</label>
                    <select name="bidang_id" x-model="bidangId" @change="onBidangChange()"
                            style="width:100%; border:1px solid #cbd5e1; border-radius:6px; padding:6px 10px; font-size:13px; color:#374151; outline:none; background:white;"
                            onfocus="this.style.borderColor='#1d4ed8'" onblur="this.style.borderColor='#cbd5e1'">
                        <option value="">Semua Bidang</option>
                        @foreach ($bidangs as $b)
                            <option value="{{ $b->id }}">{{ $b->nama }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Seksi --}}
                <div>
                    <label style="font-size:11.5px; font-weight:600; color:#64748b; display:block; margin-bottom:4px;">Seksi / Unit Kerja</label>
                    <select name="seksi_id" x-model="seksiId"
                            :disabled="!bidangId"
                            style="width:100%; border:1px solid #cbd5e1; border-radius:6px; padding:6px 10px; font-size:13px; color:#374151; outline:none; background:white;"
                            :style="!bidangId ? 'background:#f8fafc; color:#9ca3af;' : ''"
                            onfocus="this.style.borderColor='#1d4ed8'" onblur="this.style.borderColor='#cbd5e1'">
                        <option value="">-- Pilih Bidang dahulu --</option>
                        <template x-if="bidangId">
                            <template x-for="s in seksiList" :key="s.id">
                                <option :value="s.id" :selected="String(s.id) === String(seksiId)" x-text="s.nama"></option>
                            </template>
                        </template>
                    </select>
                    <input type="hidden" name="seksi_id_dummy" value="" x-show="false">
                </div>
            </div>

            <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; padding-top:8px; border-top:1px solid #f1f5f9;">
                <div style="display:flex; gap:8px;">
                    <button type="submit"
                            style="padding:8px 18px; background:var(--tc-accent, #1d4ed8); color:#fff; border:none; border-radius:6px; font-size:13px; font-weight:600; cursor:pointer;"
                            onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                        Terapkan Filter
                    </button>
                    @if($adaFilter)
                        <a href="{{ route('laporan.index') }}"
                           style="padding:8px 14px; background:transparent; color:var(--text-muted, #64748b); border-radius:6px; font-size:13px; font-weight:600; text-decoration:none; border:1px solid var(--border, #e2e8f0);">
                            Reset Filter
                        </a>
                    @endif
                </div>
                
                <a href="{{ route('laporan.print') }}?{{ http_build_query(request()->only(['dari','sampai','arah','jenis_surat_id','klasifikasi_id','bidang_id','seksi_id'])) }}"
                   target="_blank"
                   style="padding:8px 16px; background:rgba(22, 163, 74, 0.1); color:#16a34a; border:1px solid rgba(22, 163, 74, 0.2); border-radius:6px; font-size:13px; font-weight:600; text-decoration:none; display:flex; align-items:center; gap:6px;"
                   onmouseover="this.style.background='rgba(22, 163, 74, 0.15)'" onmouseout="this.style.background='rgba(22, 163, 74, 0.1)'">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    Cetak / Print
                </a>
            </div>
        </form>
    </div>

    {{-- Info Filter Aktif Sederhana --}}
    @if($adaFilter)
        <p style="margin:0 0 16px; font-size:12px; font-weight:500; color:var(--tc-accent, #1d4ed8); display:flex; align-items:center; gap:6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Menampilkan hasil pencarian filter (hanya mencakup data surat final).
        </p>
    @else
        <p style="margin:0 0 16px; font-size:12px; font-weight:500; color:var(--text-muted, #64748b); display:flex; align-items:center; gap:6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Menampilkan seluruh data surat final tanpa batasan periode.
        </p>
    @endif

    {{-- Tabel Rekap Surat --}}
    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:8px; overflow:hidden; margin-bottom:20px;">
        <div style="padding:12px 18px; border-bottom:1px solid #e2e8f0;">
            <span style="font-size:13.5px; font-weight:700; color:#1e293b;">Jumlah Surat per Jenis</span>
        </div>
        
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead style="background:var(--page-bg, #f8fafc);">
                    <tr>
                        <th style="padding:12px 20px; font-size:12px; color:var(--text-muted, #64748b); border-bottom:1px solid var(--border, #e2e8f0); text-align:left;">Jenis Surat</th>
                        <th style="padding:12px 20px; font-size:12px; color:var(--text-muted, #64748b); border-bottom:1px solid var(--border, #e2e8f0); text-align:right;">Masuk</th>
                        <th style="padding:12px 20px; font-size:12px; color:var(--text-muted, #64748b); border-bottom:1px solid var(--border, #e2e8f0); text-align:right;">Keluar</th>
                        <th style="padding:12px 20px; font-size:12px; color:var(--text-muted, #64748b); border-bottom:1px solid var(--border, #e2e8f0); text-align:right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($perJenis as $row)
                        <tr style="border-bottom:1px solid var(--border-light, #f1f5f9);">
                            <td style="font-size:13px; font-weight:500; color:var(--text-primary, #1e293b); padding:10px 20px;">
                                {{ $row['jenisSurat']->nama ?? '-' }}
                                @if($row['jenisSurat']?->kode)
                                    <span style="color:var(--text-muted, #94a3b8); margin-left:6px; font-size:12px;">({{ $row['jenisSurat']->kode }})</span>
                                @endif
                            </td>
                            <td style="text-align:right; font-size:13px; padding:10px 20px;">
                                @if($row['masuk'] > 0)
                                    <span style="color:#15803d; font-weight:600;">{{ $row['masuk'] }}</span>
                                @else
                                    <span style="color:var(--text-muted, #cbd5e1);">—</span>
                                @endif
                            </td>
                            <td style="text-align:right; font-size:13px; padding:10px 20px;">
                                @if($row['keluar'] > 0)
                                    <span style="color:#c2410c; font-weight:600;">{{ $row['keluar'] }}</span>
                                @else
                                    <span style="color:var(--text-muted, #cbd5e1);">—</span>
                                @endif
                            </td>
                            <td style="text-align:right; font-size:13px; font-weight:700; color:var(--text-primary, #0f172a); padding:10px 20px;">
                                {{ $row['total'] }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; padding:32px 18px; color:#64748b; font-size:13px;">
                                {{ $adaFilter ? 'Tidak ada surat final yang sesuai dengan filter.' : 'Belum ada surat yang difinalisasi.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($perJenis->isNotEmpty())
                <tfoot>
                    <tr style="background:var(--page-bg, #f8fafc); border-top:1px solid var(--border, #cbd5e1);">
                        <td style="font-size:13px; font-weight:700; color:var(--text-primary, #1e293b); padding:12px 20px;">Total Keseluruhan</td>
                        <td style="text-align:right; font-size:14px; font-weight:700; color:#15803d; padding:12px 20px;">{{ $totalMasuk }}</td>
                        <td style="text-align:right; font-size:14px; font-weight:700; color:#c2410c; padding:12px 20px;">{{ $totalKeluar }}</td>
                        <td style="text-align:right; font-size:15px; font-weight:800; color:var(--text-primary, #0f172a); padding:12px 20px;">{{ $totalSemua }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    {{-- Link Navigasi Cepat --}}
    <div style="display:flex; gap:12px; flex-wrap:wrap;">
        <a href="{{ route('surat-masuk.index') }}" class="card" style="display:flex; align-items:center; gap:8px; padding:10px 16px; font-size:13px; font-weight:600; color:var(--tc-accent, #1d4ed8); text-decoration:none; transition:box-shadow 0.2s;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            Daftar Surat Masuk
        </a>
        <a href="{{ route('surat-keluar.index') }}" class="card" style="display:flex; align-items:center; gap:8px; padding:10px 16px; font-size:13px; font-weight:600; color:#c2410c; text-decoration:none; transition:box-shadow 0.2s;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            Daftar Surat Keluar
        </a>
        <a href="{{ route('arsip.index') }}" class="card" style="display:flex; align-items:center; gap:8px; padding:10px 16px; font-size:13px; font-weight:600; color:#7c3aed; text-decoration:none; transition:box-shadow 0.2s;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8v13H3V8"/><path d="M1 3h22v5H1z"/><path d="M10 12h4"/></svg>
            Arsip Digital
        </a>
    </div>

</x-app-layout>
