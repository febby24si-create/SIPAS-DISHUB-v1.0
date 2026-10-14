<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; min-width:0;">
            <div style="display:flex; align-items:center; gap:12px; min-width:0;">
                <div style="width:40px; height:40px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; display:flex; align-items:center; justify-content:center; color:#1d4ed8; flex-shrink:0;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="18" y1="20" x2="18" y2="10"/>
                        <line x1="12" y1="20" x2="12" y2="4"/>
                        <line x1="6" y1="20" x2="6" y2="14"/>
                    </svg>
                </div>
                <div style="min-width:0;">
                    <div style="font-size:17px; font-weight:600; color:#1e293b; line-height:1.25;">Laporan Persuratan</div>
                    <div style="margin-top:3px; font-size:12.5px; color:#64748b; line-height:1.35;">Rekapitulasi data surat masuk dan surat keluar resmi Dinas Perhubungan Provinsi Riau</div>
                </div>
            </div>

            <div class="laporan-header-badge" style="display:inline-flex; align-items:center; gap:6px; padding:6px 12px; background:white; border:1px solid #e2e8f0; border-radius:8px; font-size:12px; color:#475569; font-weight:500;">
                <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#10b981;"></span>
                <span>Data Final Realtime &bull; {{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="laporan-container" style="display:flex; flex-direction:column; gap:18px;">

        {{-- ═══ 1. RINGKASAN STATISTIK (4 KARTU) ═══ --}}
        <div class="laporan-stats-grid">
            {{-- Total Surat --}}
            <div class="card stat-card" style="padding:16px 18px; display:flex; align-items:center; gap:14px; position:relative; overflow:hidden;">
                <div class="stat-icon stat-icon--total" style="width:42px; height:42px; border-radius:10px; background:#eff6ff; border:1px solid #dbeafe; color:#1d4ed8; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10 9 9 9 8 9"/>
                    </svg>
                </div>
                <div style="min-width:0; flex:1;">
                    <div style="font-size:11.5px; font-weight:600; color:var(--text-muted, #64748b); text-transform:uppercase; letter-spacing:0.4px;">Total Surat Resmi</div>
                    <div style="font-size:24px; font-weight:700; color:var(--text-primary, #1e293b); line-height:1.2; margin-top:2px; font-variant-numeric:tabular-nums;">
                        {{ number_format($totalSemua, 0, ',', '.') }}
                    </div>
                    <div style="margin-top:2px; font-size:11.5px; color:#94a3b8;">Surat berstatus final</div>
                </div>
            </div>

            {{-- Surat Masuk --}}
            <div class="card stat-card" style="padding:16px 18px; display:flex; align-items:center; gap:14px; position:relative; overflow:hidden;">
                <div class="stat-icon stat-icon--masuk" style="width:42px; height:42px; border-radius:10px; background:#f0fdf4; border:1px solid #dcfce7; color:#15803d; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/>
                        <path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>
                    </svg>
                </div>
                <div style="min-width:0; flex:1;">
                    <div style="font-size:11.5px; font-weight:600; color:var(--text-muted, #64748b); text-transform:uppercase; letter-spacing:0.4px;">Surat Masuk</div>
                    <div style="font-size:24px; font-weight:700; color:#15803d; line-height:1.2; margin-top:2px; font-variant-numeric:tabular-nums;">
                        {{ number_format($totalMasuk, 0, ',', '.') }}
                    </div>
                    <div style="margin-top:2px; font-size:11.5px; color:#94a3b8;">Naskah dinas diterima</div>
                </div>
            </div>

            {{-- Surat Keluar --}}
            <div class="card stat-card" style="padding:16px 18px; display:flex; align-items:center; gap:14px; position:relative; overflow:hidden;">
                <div class="stat-icon stat-icon--keluar" style="width:42px; height:42px; border-radius:10px; background:#fff7ed; border:1px solid #ffedd5; color:#ea580c; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="22" y1="2" x2="11" y2="13"/>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                    </svg>
                </div>
                <div style="min-width:0; flex:1;">
                    <div style="font-size:11.5px; font-weight:600; color:var(--text-muted, #64748b); text-transform:uppercase; letter-spacing:0.4px;">Surat Keluar</div>
                    <div style="font-size:24px; font-weight:700; color:#ea580c; line-height:1.2; margin-top:2px; font-variant-numeric:tabular-nums;">
                        {{ number_format($totalKeluar, 0, ',', '.') }}
                    </div>
                    <div style="margin-top:2px; font-size:11.5px; color:#94a3b8;">Naskah dinas diterbitkan</div>
                </div>
            </div>

            {{-- Draft Belum Final --}}
            <div class="card stat-card" style="padding:16px 18px; display:flex; align-items:center; gap:14px; position:relative; overflow:hidden;">
                <div class="stat-icon stat-icon--draft" style="width:42px; height:42px; border-radius:10px; background:#fffbeb; border:1px solid #fef3c7; color:#b45309; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 20h9"/>
                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                    </svg>
                </div>
                <div style="min-width:0; flex:1;">
                    <div style="font-size:11.5px; font-weight:600; color:var(--text-muted, #64748b); text-transform:uppercase; letter-spacing:0.4px;">Draft Belum Final</div>
                    <div style="font-size:24px; font-weight:700; color:#b45309; line-height:1.2; margin-top:2px; font-variant-numeric:tabular-nums;">
                        {{ number_format($totalDraft, 0, ',', '.') }}
                    </div>
                    <div style="margin-top:2px; font-size:11.5px; color:#94a3b8;">Menunggu verifikasi/ttd</div>
                </div>
            </div>
        </div>

        {{-- ═══ 2. AREA FILTER TERPADU (REFERENSI ARSIP DIGITAL) ═══ --}}
        <div class="card laporan-filter-card"
             x-data="{
                bidangId: '{{ $bidangId ?? '' }}',
                seksiId: '{{ $seksiId ?? '' }}',
                dari: '{{ $dari ?? '' }}',
                sampai: '{{ $sampai ?? '' }}',
                arah: '{{ $arah ?? '' }}',
                jenisSuratId: '{{ $jenisSuratId ?? '' }}',
                klasifikasiId: '{{ $klasifikasiId ?? '' }}',
                bidangs: {{ $bidangs->toJson() }},
                isDirty: false,
                markDirty() { this.isDirty = true; },
                get seksiList() {
                    if (!this.bidangId) return [];
                    const found = this.bidangs.find(b => String(b.id) === String(this.bidangId));
                    return found ? (found.children || []) : [];
                },
                onBidangChange() {
                    this.markDirty();
                    if (!this.bidangId) {
                        this.seksiId = '';
                        return;
                    }
                    const exists = this.seksiList.some(s => String(s.id) === String(this.seksiId));
                    if (!exists) {
                        this.seksiId = '';
                    }
                },
                get isDateInvalid() {
                    return Boolean(this.dari && this.sampai && this.dari > this.sampai);
                },
                handleFormSubmit(e) {
                    if (this.isDateInvalid) {
                        e.preventDefault();
                        alert('Rentang tanggal tidak valid: Tanggal Mulai tidak boleh melampaui Tanggal Selesai.');
                        return false;
                    }
                }
             }"
             style="background:white; border:1px solid rgba(0,0,0,0.07); border-radius:14px; padding:18px 20px;">

            <form method="GET" action="{{ route('laporan.index') }}" id="form-filter-laporan" @submit="handleFormSubmit($event)">
                {{-- Grid Input Filter --}}
                <div class="laporan-filter-grid">
                    {{-- Tanggal Mulai --}}
                    <div class="laporan-field">
                        <label for="filter-dari" class="laporan-label">Tanggal Mulai</label>
                        <input id="filter-dari" type="date" name="dari" x-model="dari" @change="markDirty()"
                               :max="sampai || null"
                               class="laporan-control">
                    </div>

                    {{-- Tanggal Selesai --}}
                    <div class="laporan-field">
                        <label for="filter-sampai" class="laporan-label">Tanggal Selesai</label>
                        <input id="filter-sampai" type="date" name="sampai" x-model="sampai" @change="markDirty()"
                               :min="dari || null"
                               class="laporan-control">
                    </div>

                    {{-- Arah Surat --}}
                    <div class="laporan-field">
                        <label for="filter-arah" class="laporan-label">Arah Surat</label>
                        <select id="filter-arah" name="arah" x-model="arah" @change="markDirty()" class="laporan-control">
                            <option value="">Semua Arah</option>
                            <option value="masuk">Surat Masuk</option>
                            <option value="keluar">Surat Keluar</option>
                        </select>
                    </div>

                    {{-- Jenis Surat --}}
                    <div class="laporan-field">
                        <label for="filter-jenis" class="laporan-label">Jenis Surat</label>
                        <select id="filter-jenis" name="jenis_surat_id" x-model="jenisSuratId" @change="markDirty()" class="laporan-control">
                            <option value="">Semua Jenis Surat</option>
                            @foreach ($jenisSuratList as $j)
                                <option value="{{ $j->id }}">
                                    {{ $j->kode ? $j->kode . ' — ' : '' }}{{ $j->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Klasifikasi --}}
                    <div class="laporan-field">
                        <label for="filter-klasifikasi" class="laporan-label">Klasifikasi Surat</label>
                        <select id="filter-klasifikasi" name="klasifikasi_id" x-model="klasifikasiId" @change="markDirty()" class="laporan-control">
                            <option value="">Semua Klasifikasi</option>
                            @foreach ($klasifikasiList as $k)
                                <option value="{{ $k->id }}">
                                    {{ $k->kode }} — {{ $k->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Bidang --}}
                    <div class="laporan-field">
                        <label for="filter-bidang" class="laporan-label">Bidang</label>
                        <select id="filter-bidang" name="bidang_id" x-model="bidangId" @change="onBidangChange()" class="laporan-control">
                            <option value="">Semua Bidang</option>
                            <template x-for="b in bidangs" :key="b.id">
                                <option :value="b.id" x-text="b.nama" :selected="String(b.id) === String(bidangId)"></option>
                            </template>
                        </select>
                    </div>

                    {{-- Seksi / Unit Kerja --}}
                    <div class="laporan-field">
                        <label for="filter-seksi" class="laporan-label">Seksi / Unit Kerja</label>
                        <select id="filter-seksi" name="seksi_id" x-model="seksiId" @change="markDirty()"
                                :disabled="!bidangId"
                                class="laporan-control"
                                :class="{'laporan-control--disabled': !bidangId}">
                            <option value="" x-text="bidangId ? 'Semua Seksi' : '-- Pilih Bidang dahulu --'"></option>
                            <template x-for="s in seksiList" :key="s.id">
                                <option :value="s.id" x-text="s.nama" :selected="String(s.id) === String(seksiId)"></option>
                            </template>
                        </select>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="laporan-field laporan-field--actions">
                        <label class="laporan-label" style="visibility:hidden; display:block;">Aksi</label>
                        <div class="laporan-actions-wrap">
                            <button type="submit" class="btn-filter-submit" :disabled="isDateInvalid">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                                </svg>
                                <span>Terapkan Filter</span>
                            </button>

                            @if($adaFilter)
                                <a href="{{ route('laporan.index') }}" class="btn-filter-reset" title="Kembalikan filter ke kondisi awal">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <line x1="18" y1="6" x2="6" y2="18"/>
                                        <line x1="6" y1="6" x2="18" y2="18"/>
                                    </svg>
                                    <span>Reset</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Pesan Validasi Tanggal --}}
                <div x-show="isDateInvalid" x-cloak
                     style="display:flex; align-items:center; gap:8px; margin-top:12px; padding:10px 14px; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; color:#991b1b; font-size:12.5px; font-weight:500;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="flex-shrink:0;">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <span><strong>Rentang Tanggal Tidak Valid:</strong> Tanggal Mulai tidak boleh melampaui Tanggal Selesai. Mohon perbaiki tanggal sebelum menerapkan filter.</span>
                </div>

                {{-- Status Bar Filter (Filter Aktif & Tombol Cetak) --}}
                <div class="laporan-filter-footer" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; margin-top:14px; padding-top:12px; border-top:1px solid #f1f5f9;">
                    <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                        @if($adaFilter)
                            <span style="font-size:12px; font-weight:600; color:var(--text-muted, #64748b);">Filter Aktif:</span>
                            @if($dari || $sampai)
                                <span class="filter-chip">
                                    Periode:
                                    @if($dari && $sampai)
                                        {{ \Carbon\Carbon::parse($dari)->translatedFormat('d M Y') }} — {{ \Carbon\Carbon::parse($sampai)->translatedFormat('d M Y') }}
                                    @elseif($dari)
                                        Mulai {{ \Carbon\Carbon::parse($dari)->translatedFormat('d M Y') }}
                                    @else
                                        Sampai {{ \Carbon\Carbon::parse($sampai)->translatedFormat('d M Y') }}
                                    @endif
                                </span>
                            @endif

                            @if($arah)
                                <span class="filter-chip">
                                    Arah: {{ $arah === 'masuk' ? 'Surat Masuk' : 'Surat Keluar' }}
                                </span>
                            @endif

                            @if($jenisSuratId)
                                @php $activeJenis = $jenisSuratList->firstWhere('id', $jenisSuratId); @endphp
                                @if($activeJenis)
                                    <span class="filter-chip">
                                        Jenis: {{ $activeJenis->nama }}
                                    </span>
                                @endif
                            @endif

                            @if($klasifikasiId)
                                @php $activeKlas = $klasifikasiList->firstWhere('id', $klasifikasiId); @endphp
                                @if($activeKlas)
                                    <span class="filter-chip">
                                        Klasifikasi: {{ $activeKlas->kode }}
                                    </span>
                                @endif
                            @endif

                            @if($bidangId)
                                @php $activeBidang = $bidangs->firstWhere('id', $bidangId); @endphp
                                @if($activeBidang)
                                    <span class="filter-chip">
                                        Bidang: {{ $activeBidang->nama }}
                                    </span>
                                @endif
                            @endif

                            @if($seksiId)
                                @php $activeSeksi = $seksis->firstWhere('id', $seksiId); @endphp
                                @if($activeSeksi)
                                    <span class="filter-chip">
                                        Seksi: {{ $activeSeksi->nama }}
                                    </span>
                                @endif
                            @endif
                        @else
                            <span style="font-size:12px; color:#64748b; display:inline-flex; align-items:center; gap:6px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="16" x2="12" y2="12"/>
                                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                                </svg>
                                Menampilkan seluruh data surat resmi berstatus final tanpa pembatasan periode.
                            </span>
                        @endif

                        <span x-show="isDirty" x-cloak
                              style="font-size:11.5px; color:#d97706; background:#fef3c7; border:1px solid #fde68a; padding:2px 8px; border-radius:6px; font-weight:600;">
                            Pilihan diubah &bull; Klik &lsquo;Terapkan Filter&rsquo;
                        </span>
                    </div>

                    {{-- Tombol Cetak / Print --}}
                    <div>
                        <a href="{{ route('laporan.print') }}?{{ http_build_query(request()->only(['dari','sampai','arah','jenis_surat_id','klasifikasi_id','bidang_id','seksi_id'])) }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="btn-print"
                           title="Cetak atau simpan laporan ke PDF (A4)">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="6 9 6 2 18 2 18 9"/>
                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                                <rect x="6" y="14" width="12" height="8"/>
                            </svg>
                            <span>Cetak Laporan</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- ═══ 3. VISUALISASI DATA (2 GRAFIK AKTUAL) ═══ --}}
        <div class="laporan-charts-grid">
            {{-- Grafik A: Perbandingan per Jenis Surat --}}
            <div class="card" style="padding:18px 20px; display:flex; flex-direction:column; border-radius:14px;">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; gap:8px; flex-wrap:wrap;">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <div style="width:28px; height:28px; border-radius:6px; background:#eff6ff; color:#1d4ed8; display:flex; align-items:center; justify-content:center;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <line x1="9" y1="17" x2="9" y2="10"/>
                                <line x1="15" y1="17" x2="15" y2="7"/>
                            </svg>
                        </div>
                        <div>
                            <h3 style="margin:0; font-size:13.5px; font-weight:700; color:var(--text-primary, #1e293b);">Perbandingan per Jenis Surat</h3>
                            <p style="margin:2px 0 0; font-size:11.5px; color:var(--text-muted, #64748b);">Surat masuk vs surat keluar (data teratas)</p>
                        </div>
                    </div>
                    @if(count($chartJenisLabels) > 0)
                        <span style="font-size:11px; font-weight:600; padding:2px 8px; background:#f1f5f9; color:#475569; border-radius:6px;">
                            {{ count($chartJenisLabels) }} Jenis Terbanyak
                        </span>
                    @endif
                </div>

                <div style="flex:1; min-height:260px; position:relative;">
                    @if(count($chartJenisLabels) > 0 && ($totalMasuk > 0 || $totalKeluar > 0))
                        <canvas id="chartPerbandinganJenis"></canvas>
                    @else
                        <div class="chart-empty-state">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            <p>Belum ada data surat final yang memenuhi filter untuk visualisasi ini.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Grafik B: Tren Surat Masuk & Keluar --}}
            <div class="card" style="padding:18px 20px; display:flex; flex-direction:column; border-radius:14px;">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; gap:8px; flex-wrap:wrap;">
                    <div style="display:flex; align-items:center; gap:8px;">
                        <div style="width:28px; height:28px; border-radius:6px; background:#f0fdf4; color:#15803d; display:flex; align-items:center; justify-content:center;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                                <polyline points="17 6 23 6 23 12"/>
                            </svg>
                        </div>
                        <div>
                            <h3 style="margin:0; font-size:13.5px; font-weight:700; color:var(--text-primary, #1e293b);">Tren Persuratan</h3>
                            <p style="margin:2px 0 0; font-size:11.5px; color:var(--text-muted, #64748b);">Dinamika volume surat masuk &amp; keluar</p>
                        </div>
                    </div>
                    <span style="font-size:11px; font-weight:600; padding:2px 8px; background:#f0fdf4; color:#166534; border-radius:6px;">
                        {{ $trendSubtitle }}
                    </span>
                </div>

                <div style="flex:1; min-height:260px; position:relative;">
                    @if(count($trendLabels) > 0 && ($totalMasuk > 0 || $totalKeluar > 0))
                        <canvas id="chartTrenPersuratan"></canvas>
                    @else
                        <div class="chart-empty-state">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            <p>Belum ada data tren surat pada periode ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ═══ 4. TABEL REKAPITULASI JUMLAH SURAT PER JENIS ═══ --}}
        <div class="card" style="border-radius:14px; overflow:hidden; border:1px solid rgba(0,0,0,0.07);">
            {{-- Header Tabel --}}
            <div style="padding:14px 20px; border-bottom:1px solid var(--border-light, #f1f5f9); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:32px; height:32px; background:#eff6ff; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#1d4ed8;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="8" y1="6" x2="21" y2="6"/>
                            <line x1="8" y1="12" x2="21" y2="12"/>
                            <line x1="8" y1="18" x2="21" y2="18"/>
                            <line x1="3" y1="6" x2="3.01" y2="6"/>
                            <line x1="3" y1="12" x2="3.01" y2="12"/>
                            <line x1="3" y1="18" x2="3.01" y2="18"/>
                        </svg>
                    </div>
                    <div>
                        <span style="font-size:14px; font-weight:700; color:var(--text-primary, #1e293b);">Rekapitulasi Surat per Jenis</span>
                        <div style="font-size:11.5px; color:var(--text-muted, #64748b);">Jumlah naskah dinas final terdistribusi berdasarkan jenis surat</div>
                    </div>
                </div>

                <div style="display:flex; align-items:center; gap:8px;">
                    <span style="display:inline-flex; align-items:center; gap:6px; padding:3px 10px; background:#eff6ff; color:#1d4ed8; font-size:12px; font-weight:600; border-radius:20px;">
                        {{ $perJenis->count() }} Jenis Terdata
                    </span>
                </div>
            </div>

            {{-- Body Tabel --}}
            <div class="table-wrapper">
                <table class="data-table laporan-table" style="min-width:640px; width:100%;">
                    <thead>
                        <tr>
                            <th style="padding:12px 20px; font-size:12px; font-weight:600; text-align:left; color:var(--text-muted, #64748b);">Jenis Surat</th>
                            <th style="padding:12px 20px; font-size:12px; font-weight:600; text-align:right; width:140px; color:var(--text-muted, #64748b);">Surat Masuk</th>
                            <th style="padding:12px 20px; font-size:12px; font-weight:600; text-align:right; width:140px; color:var(--text-muted, #64748b);">Surat Keluar</th>
                            <th style="padding:12px 20px; font-size:12px; font-weight:600; text-align:right; width:140px; color:var(--text-muted, #64748b);">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($perJenis as $row)
                            <tr>
                                <td style="padding:11px 20px;">
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        @if($row['jenisSurat']?->kode)
                                            <span class="badge-kode" title="Kode Jenis Surat">
                                                {{ $row['jenisSurat']->kode }}
                                            </span>
                                        @endif
                                        @if($row['jenisSurat'])
                                            <a href="{{ route('jenis-surat.show', $row['jenisSurat']) }}"
                                               class="link-jenis-surat"
                                               title="Klik untuk membuka detail jenis surat &amp; daftar arsip terkait">
                                                {{ $row['jenisSurat']->nama }}
                                            </a>
                                        @else
                                            <span style="color:var(--text-muted, #64748b); font-style:italic;">Tanpa Jenis Surat</span>
                                        @endif
                                    </div>
                                </td>
                                <td style="text-align:right; padding:11px 20px;">
                                    @if($row['masuk'] > 0)
                                        <span style="color:#15803d; font-weight:700; font-size:13.5px; font-variant-numeric:tabular-nums;">
                                            {{ number_format($row['masuk'], 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span style="color:var(--text-muted, #94a3b8); font-size:13px;">—</span>
                                    @endif
                                </td>
                                <td style="text-align:right; padding:11px 20px;">
                                    @if($row['keluar'] > 0)
                                        <span style="color:#ea580c; font-weight:700; font-size:13.5px; font-variant-numeric:tabular-nums;">
                                            {{ number_format($row['keluar'], 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span style="color:var(--text-muted, #94a3b8); font-size:13px;">—</span>
                                    @endif
                                </td>
                                <td style="text-align:right; padding:11px 20px;">
                                    @if($row['total'] > 0)
                                        <span style="font-weight:800; font-size:14px; font-variant-numeric:tabular-nums; color:var(--text-primary, #0f172a);">
                                            {{ number_format($row['total'], 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span style="color:var(--text-muted, #94a3b8); font-size:13px;">0</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center; padding:38px 20px; color:#64748b;">
                                    <div style="display:flex; flex-direction:column; align-items:center; gap:8px;">
                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <circle cx="11" cy="11" r="8"/>
                                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                        </svg>
                                        <span style="font-size:13.5px; font-weight:600; color:#475569;">
                                            {{ $adaFilter ? 'Tidak ada surat final yang sesuai dengan filter yang diterapkan.' : 'Belum ada data surat berstatus final dalam sistem.' }}
                                        </span>
                                        @if($adaFilter)
                                            <a href="{{ route('laporan.index') }}" style="font-size:12.5px; color:#1d4ed8; text-decoration:none; font-weight:600; margin-top:4px;">
                                                &larr; Reset filter untuk melihat seluruh data
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($perJenis->isNotEmpty())
                        <tfoot>
                            <tr class="laporan-tfoot-row">
                                <td style="font-size:13px; font-weight:700; color:var(--text-primary, #1e293b); padding:13px 20px;">
                                    Total Keseluruhan
                                </td>
                                <td style="text-align:right; font-size:14.5px; font-weight:800; color:#15803d; padding:13px 20px; font-variant-numeric:tabular-nums;">
                                    {{ number_format($totalMasuk, 0, ',', '.') }}
                                </td>
                                <td style="text-align:right; font-size:14.5px; font-weight:800; color:#ea580c; padding:13px 20px; font-variant-numeric:tabular-nums;">
                                    {{ number_format($totalKeluar, 0, ',', '.') }}
                                </td>
                                <td style="text-align:right; font-size:15.5px; font-weight:900; color:var(--text-primary, #0f172a); padding:13px 20px; font-variant-numeric:tabular-nums;">
                                    {{ number_format($totalSemua, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>

        {{-- ═══ 5. PINTASAN NAVIGASI PERSURATAN ═══ --}}
        <div style="margin-top:2px;">
            <div style="font-size:11.5px; font-weight:600; color:var(--text-muted, #64748b); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px;">
                Pintasan Navigasi Persuratan
            </div>
            <div class="laporan-nav-grid">
                <a href="{{ route('surat-masuk.index') }}" class="card nav-shortcut-card">
                    <div class="nav-shortcut-icon nav-shortcut-icon--masuk">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/>
                            <path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>
                        </svg>
                    </div>
                    <div style="min-width:0; flex:1;">
                        <div class="nav-shortcut-title">Daftar Surat Masuk</div>
                        <div class="nav-shortcut-sub">Agenda &amp; naskah dinas masuk</div>
                    </div>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="color:#94a3b8; flex-shrink:0;">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                </a>

                <a href="{{ route('surat-keluar.index') }}" class="card nav-shortcut-card">
                    <div class="nav-shortcut-icon nav-shortcut-icon--keluar">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="22" y1="2" x2="11" y2="13"/>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                        </svg>
                    </div>
                    <div style="min-width:0; flex:1;">
                        <div class="nav-shortcut-title">Daftar Surat Keluar</div>
                        <div class="nav-shortcut-sub">Penomoran &amp; naskah dinas keluar</div>
                    </div>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="color:#94a3b8; flex-shrink:0;">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                </a>

                <a href="{{ route('arsip.index') }}" class="card nav-shortcut-card">
                    <div class="nav-shortcut-icon nav-shortcut-icon--arsip">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M21 8v13H3V8"/>
                            <path d="M1 3h22v5H1z"/>
                            <path d="M10 12h4"/>
                        </svg>
                    </div>
                    <div style="min-width:0; flex:1;">
                        <div class="nav-shortcut-title">Arsip Digital</div>
                        <div class="nav-shortcut-sub">Pencarian naskah dinas final</div>
                    </div>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="color:#94a3b8; flex-shrink:0;">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                </a>
            </div>
        </div>

    </div>

    {{-- ═══ 6. CHART.JS SCRIPT (KOMPATIBEL LIGHT & DARK MODE) ═══ --}}
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Chart === 'undefined') {
            console.warn('Chart.js belum dimuat.');
            return;
        }

        /* Helper warna tema light / dark mode */
        function getChartTheme() {
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            return {
                isDark: isDark,
                text:       isDark ? '#cbd5e1' : '#475569',
                tickColor:  isDark ? '#94a3b8' : '#64748b',
                gridColor:  isDark ? '#293548' : '#f1f5f9',
                tooltipBg:  isDark ? '#1e293b' : '#ffffff',
                tooltipTitle: isDark ? '#f8fafc' : '#1e293b',
                tooltipBody:  isDark ? '#cbd5e1' : '#475569',
                tooltipBorder:isDark ? '#334155' : '#e2e8f0',
            };
        }

        let chartBar = null;
        let chartLine = null;

        /* Inisialisasi Chart A: Bar Perbandingan Jenis Surat */
        const ctxBar = document.getElementById('chartPerbandinganJenis');
        const barLabels     = @json($chartJenisLabels ?? []);
        const barFullLabels = @json($chartJenisFullLabels ?? []);
        const barMasuk      = @json($chartJenisMasuk ?? []);
        const barKeluar     = @json($chartJenisKeluar ?? []);

        if (ctxBar && barLabels.length > 0) {
            const theme = getChartTheme();
            chartBar = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: barLabels,
                    datasets: [
                        {
                            label: 'Surat Masuk',
                            data: barMasuk,
                            backgroundColor: '#16a34a',
                            borderRadius: 4,
                            barPercentage: 0.65,
                            categoryPercentage: 0.7,
                        },
                        {
                            label: 'Surat Keluar',
                            data: barKeluar,
                            backgroundColor: '#ea580c',
                            borderRadius: 4,
                            barPercentage: 0.65,
                            categoryPercentage: 0.7,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                color: theme.text,
                                font: { size: 12, weight: '500' },
                                boxWidth: 12,
                                usePointStyle: true,
                            }
                        },
                        tooltip: {
                            backgroundColor: theme.tooltipBg,
                            titleColor: theme.tooltipTitle,
                            bodyColor: theme.tooltipBody,
                            borderColor: theme.tooltipBorder,
                            borderWidth: 1,
                            callbacks: {
                                title: function(context) {
                                    const idx = context[0].dataIndex;
                                    return barFullLabels[idx] || context[0].label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: theme.tickColor, font: { size: 11.5 } },
                            border: { display: false }
                        },
                        y: {
                            grid: { color: theme.gridColor },
                            ticks: { color: theme.tickColor, font: { size: 11.5 }, precision: 0, stepSize: 1 },
                            border: { display: false },
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        /* Inisialisasi Chart B: Tren Surat Masuk & Keluar */
        const ctxLine = document.getElementById('chartTrenPersuratan');
        const trendLabels = @json($trendLabels ?? []);
        const trendMasuk  = @json($trendMasuk ?? []);
        const trendKeluar = @json($trendKeluar ?? []);

        if (ctxLine && trendLabels.length > 0) {
            const theme = getChartTheme();
            chartLine = new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [
                        {
                            label: 'Surat Masuk',
                            data: trendMasuk,
                            borderColor: '#16a34a',
                            backgroundColor: 'rgba(22, 163, 74, 0.08)',
                            borderWidth: 2.5,
                            pointBackgroundColor: '#16a34a',
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            tension: 0.3,
                            fill: true,
                        },
                        {
                            label: 'Surat Keluar',
                            data: trendKeluar,
                            borderColor: '#ea580c',
                            backgroundColor: 'rgba(234, 88, 12, 0.08)',
                            borderWidth: 2.5,
                            pointBackgroundColor: '#ea580c',
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            tension: 0.3,
                            fill: true,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                color: theme.text,
                                font: { size: 12, weight: '500' },
                                boxWidth: 12,
                                usePointStyle: true,
                            }
                        },
                        tooltip: {
                            backgroundColor: theme.tooltipBg,
                            titleColor: theme.tooltipTitle,
                            bodyColor: theme.tooltipBody,
                            borderColor: theme.tooltipBorder,
                            borderWidth: 1
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: theme.tickColor, font: { size: 11.5 } },
                            border: { display: false }
                        },
                        y: {
                            grid: { color: theme.gridColor },
                            ticks: { color: theme.tickColor, font: { size: 11.5 }, precision: 0, stepSize: 1 },
                            border: { display: false },
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        /* Update warna otomatis saat pengguna berganti tema dark / light */
        const observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (m) {
                if (m.type === 'attributes' && m.attributeName === 'data-theme') {
                    const theme = getChartTheme();
                    [chartBar, chartLine].forEach(function (chart) {
                        if (!chart) return;
                        if (chart.options.scales.x) {
                            chart.options.scales.x.ticks.color = theme.tickColor;
                        }
                        if (chart.options.scales.y) {
                            chart.options.scales.y.ticks.color = theme.tickColor;
                            chart.options.scales.y.grid.color  = theme.gridColor;
                        }
                        if (chart.options.plugins.legend) {
                            chart.options.plugins.legend.labels.color = theme.text;
                        }
                        if (chart.options.plugins.tooltip) {
                            chart.options.plugins.tooltip.backgroundColor = theme.tooltipBg;
                            chart.options.plugins.tooltip.titleColor       = theme.tooltipTitle;
                            chart.options.plugins.tooltip.bodyColor        = theme.tooltipBody;
                            chart.options.plugins.tooltip.borderColor      = theme.tooltipBorder;
                        }
                        chart.update('none');
                    });
                }
            });
        });

        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
    });
    </script>
    @endpush

    {{-- ═══ 7. SCOPED STYLES (KONSISTEN DENGAN ARSIP DIGITAL & JENIS SURAT) ═══ --}}
    <style>
        /* ── Grid Kartu Statistik ── */
        .laporan-stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
        }

        .stat-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        /* ── Grid Form Filter ── */
        .laporan-filter-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            align-items: end;
        }

        .laporan-field {
            display: flex;
            flex-direction: column;
            gap: 5px;
            min-width: 0;
        }

        .laporan-label {
            font-size: 11px;
            font-weight: 600;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .laporan-control {
            height: 36px;
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: white;
            color: #334155;
            font-size: 13.5px;
            padding: 0 10px;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .laporan-control:hover:not(:disabled) {
            border-color: #94a3b8 !important;
        }

        .laporan-control:focus {
            border-color: #1d4ed8 !important;
            box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.12);
        }

        .laporan-control--disabled {
            background: #f8fafc !important;
            color: #94a3b8 !important;
            cursor: not-allowed;
            border-color: #e2e8f0 !important;
        }

        /* ── Tombol Filter & Cetak ── */
        .laporan-actions-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            height: 36px;
        }

        .btn-filter-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            height: 36px;
            padding: 0 16px;
            background: #1d4ed8;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.15s ease, opacity 0.15s ease;
            flex: 1;
        }
        .btn-filter-submit:hover:not(:disabled) {
            background: #1e40af;
        }
        .btn-filter-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-filter-reset {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            height: 36px;
            padding: 0 12px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            text-decoration: none;
            white-space: nowrap;
            transition: background 0.15s ease, border-color 0.15s ease;
        }
        .btn-filter-reset:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 16px;
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.15s ease, border-color 0.15s ease;
        }
        .btn-print:hover {
            background: #dcfce7;
            border-color: #86efac;
        }

        .filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 9px;
            background: #eff6ff;
            color: #1d4ed8;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            border: 1px solid #dbeafe;
        }

        /* ── Grid Grafik ── */
        .laporan-charts-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
        }

        .chart-empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            min-height: 240px;
            color: #94a3b8;
            font-size: 13px;
            text-align: center;
            gap: 8px;
        }

        /* ── Tabel & Badges ── */
        .badge-kode {
            display: inline-flex;
            align-items: center;
            padding: 3px 8px;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 11.5px;
            font-weight: 700;
            border-radius: 6px;
            font-family: monospace;
            letter-spacing: 0.4px;
            flex-shrink: 0;
        }

        .link-jenis-surat {
            color: var(--text-primary, #1e293b);
            font-weight: 600;
            font-size: 13.5px;
            text-decoration: none;
            transition: color 0.15s ease;
        }
        .link-jenis-surat:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        .laporan-table tbody tr {
            border-bottom: 1px solid var(--border-light, #f1f5f9);
            transition: background 0.15s ease;
        }
        .laporan-table tbody tr:hover {
            background: rgba(0, 0, 0, 0.015);
        }

        .laporan-tfoot-row {
            background: var(--page-bg, #f8fafc);
            border-top: 2px solid var(--border, #cbd5e1);
        }

        /* ── Navigasi Cepat ── */
        .laporan-nav-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .nav-shortcut-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            text-decoration: none;
            border-radius: 12px;
            transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
        }
        .nav-shortcut-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border-color: #cbd5e1;
        }

        .nav-shortcut-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .nav-shortcut-icon--masuk  { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; }
        .nav-shortcut-icon--keluar { background: #fff7ed; color: #ea580c; border: 1px solid #ffedd5; }
        .nav-shortcut-icon--arsip  { background: #faf5ff; color: #7c3aed; border: 1px solid #f3e8ff; }

        .nav-shortcut-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary, #1e293b);
            line-height: 1.25;
        }
        .nav-shortcut-sub {
            font-size: 11.5px;
            color: var(--text-muted, #64748b);
            margin-top: 2px;
        }

        /* ── Aksesibilitas: Outline Fokus Keyboard ── */
        .laporan-container a:focus-visible,
        .laporan-container button:focus-visible,
        .laporan-container input:focus-visible,
        .laporan-container select:focus-visible {
            outline: 2px solid #2563eb;
            outline-offset: 2px;
        }

        /* ── Responsif Breakpoints ── */
        @media (max-width: 1024px) {
            .laporan-stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .laporan-filter-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .laporan-charts-grid {
                grid-template-columns: 1fr;
            }
            .laporan-nav-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .laporan-stats-grid {
                grid-template-columns: 1fr;
            }
            .laporan-filter-grid {
                grid-template-columns: 1fr;
            }
            .laporan-actions-wrap {
                width: 100%;
            }
            .laporan-actions-wrap button,
            .laporan-actions-wrap a {
                flex: 1;
            }
            .laporan-filter-footer {
                flex-direction: column;
                align-items: stretch;
            }
            .btn-print {
                width: 100%;
                justify-content: center;
            }
        }

        /* ── Dark Mode Overrides ── */
        [data-theme="dark"] .laporan-control {
            background: #162032 !important;
            border-color: #334155 !important;
            color: #e2e8f0 !important;
        }
        [data-theme="dark"] .laporan-control:hover:not(:disabled) {
            border-color: #475569 !important;
        }
        [data-theme="dark"] .laporan-control option {
            background: #1e293b !important;
            color: #e2e8f0 !important;
        }
        [data-theme="dark"] .laporan-control--disabled {
            background: #111827 !important;
            color: #64748b !important;
            border-color: #1e293b !important;
        }
        [data-theme="dark"] .laporan-header-badge {
            background: #1e293b !important;
            border-color: #334155 !important;
            color: #94a3b8 !important;
        }
        [data-theme="dark"] .laporan-label {
            color: #94a3b8 !important;
        }
        [data-theme="dark"] .btn-filter-reset {
            background: #1e293b !important;
            border-color: #475569 !important;
            color: #cbd5e1 !important;
        }
        [data-theme="dark"] .btn-filter-reset:hover {
            background: #293548 !important;
        }
        [data-theme="dark"] .btn-print {
            background: rgba(22, 163, 74, 0.15) !important;
            border-color: rgba(22, 163, 74, 0.35) !important;
            color: #4ade80 !important;
        }
        [data-theme="dark"] .btn-print:hover {
            background: rgba(22, 163, 74, 0.25) !important;
        }
        [data-theme="dark"] .filter-chip {
            background: rgba(29, 78, 216, 0.15) !important;
            border-color: rgba(29, 78, 216, 0.3) !important;
            color: #93c5fd !important;
        }
        [data-theme="dark"] .badge-kode {
            background: rgba(29, 78, 216, 0.2) !important;
            color: #93c5fd !important;
        }
        [data-theme="dark"] .link-jenis-surat {
            color: #e2e8f0 !important;
        }
        [data-theme="dark"] .link-jenis-surat:hover {
            color: #60a5fa !important;
        }
        [data-theme="dark"] .laporan-table tbody tr:hover {
            background: #162032 !important;
        }
        [data-theme="dark"] .laporan-tfoot-row {
            background: #162032 !important;
            border-top-color: #334155 !important;
        }
        [data-theme="dark"] .nav-shortcut-card:hover {
            border-color: #475569 !important;
        }
    </style>
</x-app-layout>
