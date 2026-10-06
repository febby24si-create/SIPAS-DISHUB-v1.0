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
    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:8px; padding:12px 18px; margin-bottom:14px; display:flex; gap:24px; flex-wrap:wrap;">
        <span style="font-size:13px; color:#374151;">Total Surat Resmi: <strong>{{ $totalSemua }}</strong></span>
        <span style="font-size:13px; color:#16a34a;">Surat Masuk: <strong>{{ $totalMasuk }}</strong></span>
        <span style="font-size:13px; color:#c2410c;">Surat Keluar: <strong>{{ $totalKeluar }}</strong></span>
        <span style="font-size:13px; color:#64748b;">Draft Belum Final: <strong>{{ $totalDraft }}</strong></span>
    </div>

    {{-- Filter Panel --}}
    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:8px; padding:14px 18px; margin-bottom:14px;"
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
                            style="padding:7px 18px; background:#1d4ed8; color:white; border:none; border-radius:6px; font-size:13px; font-weight:600; cursor:pointer;"
                            onmouseover="this.style.background='#1e40af'" onmouseout="this.style.background='#1d4ed8'">
                        Terapkan Filter
                    </button>
                    @if($adaFilter)
                        <a href="{{ route('laporan.index') }}"
                           style="padding:7px 14px; background:#f1f5f9; color:#475569; border-radius:6px; font-size:13px; font-weight:600; text-decoration:none; border:1px solid #e2e8f0;">
                            Reset Filter
                        </a>
                    @endif
                </div>
                
                <a href="{{ route('laporan.print') }}?{{ http_build_query(request()->only(['dari','sampai','arah','jenis_surat_id','klasifikasi_id','bidang_id','seksi_id'])) }}"
                   target="_blank"
                   style="padding:7px 16px; background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; border-radius:6px; font-size:13px; font-weight:600; text-decoration:none;"
                   onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fdf4'">
                    Cetak / Print
                </a>
            </div>
        </form>
    </div>

    {{-- Info Filter Aktif Sederhana --}}
    @if($adaFilter)
        <p style="margin:0 0 14px; font-size:12.5px; color:#1d4ed8;">
            Menampilkan hasil filter. Hanya mencakup data surat final.
        </p>
    @else
        <p style="margin:0 0 14px; font-size:12.5px; color:#64748b;">
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
                <thead>
                    <tr>
                        <th style="padding:10px 18px;">Jenis Surat</th>
                        <th style="text-align:right; padding:10px 18px;">Masuk</th>
                        <th style="text-align:right; padding:10px 18px;">Keluar</th>
                        <th style="text-align:right; padding:10px 18px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($perJenis as $row)
                        <tr>
                            <td style="font-size:13px; color:#1e293b; padding:8px 18px;">
                                {{ $row['jenisSurat']->nama ?? '-' }}
                                @if($row['jenisSurat']?->kode)
                                    <span style="color:#64748b; margin-left:4px;">({{ $row['jenisSurat']->kode }})</span>
                                @endif
                            </td>
                            <td style="text-align:right; font-size:13px; padding:8px 18px;">
                                @if($row['masuk'] > 0)
                                    <span style="color:#16a34a; font-weight:600;">{{ $row['masuk'] }}</span>
                                @else
                                    <span style="color:#94a3b8;">—</span>
                                @endif
                            </td>
                            <td style="text-align:right; font-size:13px; padding:8px 18px;">
                                @if($row['keluar'] > 0)
                                    <span style="color:#c2410c; font-weight:600;">{{ $row['keluar'] }}</span>
                                @else
                                    <span style="color:#94a3b8;">—</span>
                                @endif
                            </td>
                            <td style="text-align:right; font-size:13px; font-weight:600; color:#0f172a; padding:8px 18px;">
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
                    <tr style="background:#f8fafc; border-top:1px solid #cbd5e1;">
                        <td style="font-size:13px; font-weight:700; color:#1e293b; padding:10px 18px;">Total Keseluruhan</td>
                        <td style="text-align:right; font-size:13px; font-weight:700; color:#16a34a; padding:10px 18px;">{{ $totalMasuk }}</td>
                        <td style="text-align:right; font-size:13px; font-weight:700; color:#c2410c; padding:10px 18px;">{{ $totalKeluar }}</td>
                        <td style="text-align:right; font-size:14px; font-weight:700; color:#0f172a; padding:10px 18px;">{{ $totalSemua }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    {{-- Link Navigasi Cepat --}}
    <div style="display:flex; gap:12px; flex-wrap:wrap; font-size:12.5px;">
        <a href="{{ route('surat-masuk.index') }}" style="color:#1d4ed8; text-decoration:none; font-weight:500;">&rarr; Daftar Surat Masuk</a>
        <a href="{{ route('surat-keluar.index') }}" style="color:#1d4ed8; text-decoration:none; font-weight:500;">&rarr; Daftar Surat Keluar</a>
        <a href="{{ route('arsip.index') }}" style="color:#1d4ed8; text-decoration:none; font-weight:500;">&rarr; Arsip Digital</a>
    </div>

</x-app-layout>
