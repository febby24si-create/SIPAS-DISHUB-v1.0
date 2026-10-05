<x-app-layout>
    <x-slot name="header">Tambah KGB</x-slot>

    <div style="max-width:800px; margin:0 auto; display:flex; flex-direction:column; gap:20px;">

        <div style="display:flex; align-items:flex-start; justify-content:space-between;">
            <div>
                <h2 style="font-size:20px; font-weight:700; color:#1e293b; margin:0 0 4px;">Tambah Dokumen KGB</h2>
                <p style="font-size:14px; color:#64748b; margin:0;">Tambahkan data Kenaikan Gaji Berkala pegawai.</p>
            </div>
            <a href="{{ route('kepegawaian.kgb.index') }}" style="display:inline-flex; align-items:center; gap:8px; font-size:14px; font-weight:600; color:#64748b; text-decoration:none; padding:8px 16px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali
            </a>
        </div>

        @if ($errors->any())
            <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:12px; padding:16px; color:#991b1b; font-size:14px;">
                <div style="font-weight:600; margin-bottom:8px; display:flex; align-items:center; gap:8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    Terdapat kesalahan input:
                </div>
                <ul style="margin:0; padding-left:24px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Prepare pegawai list for dropdown --}}
        @php
            $kgbPegawaiList = $pegawais->map(fn($p) => [
                'id'  => $p->id,
                'nama'=> $p->nama,
                'nip' => $p->nip ?? '',
            ])->values()->toArray();
            $kgbInitId   = old('pegawai_id', '');
            $kgbInitName = '';
            if ($kgbInitId) {
                $fp = $pegawais->firstWhere('id', $kgbInitId);
                if ($fp) $kgbInitName = $fp->nama;
            }
        @endphp

        <form action="{{ route('kepegawaian.kgb.store') }}" method="POST" enctype="multipart/form-data"
              x-data="kgbForm()"
              style="background:white; border-radius:16px; border:1px solid #e2e8f0; box-shadow:0 1px 3px rgba(0,0,0,0.05); overflow:hidden;">
            @csrf

            {{-- INFORMASI PEGAWAI --}}
            <div style="padding:24px; border-bottom:1px solid #e2e8f0;">
                <h3 style="margin:0 0 16px; font-size:15px; font-weight:600; color:#1e293b; display:flex; align-items:center; gap:8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Informasi Pegawai
                </h3>

                {{-- Searchable Pegawai Dropdown --}}
                <div style="margin-bottom:16px;">
                    <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Pilih Pegawai *</label>

                    {{-- Hidden input tetap pakai name="pegawai_id" --}}
                    <input type="hidden" name="pegawai_id" :value="selectedPegawaiId" required>

                    <div style="position:relative;">
                        <button type="button"
                                @click="toggleDropdown($el)"
                                @click.outside="ddOpen = false"
                                style="display:flex; justify-content:space-between; align-items:center; width:100%; text-align:left; background:#fff; cursor:pointer; padding:10px 14px; border:1px solid {{ $errors->has('pegawai_id') ? '#fca5a5' : '#cbd5e1' }}; border-radius:8px; font-size:14px; gap:8px; outline:none; transition:border-color 0.15s ease;">
                            <span x-text="pegawaiLabel || '-- Pilih Pegawai --'"
                                  :style="!pegawaiLabel ? 'color:#94a3b8;' : 'color:#1e293b; font-weight:500;'"></span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>

                        <div x-show="ddOpen"
                             x-transition.opacity
                             :style="ddDirection === 'up'
                                 ? 'display:flex; flex-direction:column-reverse; position:absolute; z-index:50; width:100%; bottom:calc(100% + 4px); background:white; border:1px solid #cbd5e1; border-radius:8px; box-shadow:0 10px 25px -5px rgba(0,0,0,0.1); overflow:hidden;'
                                 : 'display:flex; flex-direction:column; position:absolute; z-index:50; width:100%; top:calc(100% + 4px); background:white; border:1px solid #cbd5e1; border-radius:8px; box-shadow:0 10px 25px -5px rgba(0,0,0,0.1); overflow:hidden;'"
                             style="display:none;">

                            <div style="padding:10px 12px; border-bottom:1px solid #e2e8f0; background:#f8fafc; flex-shrink:0;">
                                <div style="position:relative;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute; left:10px; top:9px; color:#94a3b8;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                    <input type="text" x-model="ddSearch" placeholder="Cari nama atau NIP..."
                                           style="width:100%; padding:7px 10px 7px 32px; border:1px solid #cbd5e1; border-radius:6px; font-size:13px; outline:none; box-sizing:border-box;"
                                           @click.stop>
                                </div>
                            </div>

                            <div :style="'overflow-y:auto; max-height:' + ddMaxHeight + 'px; padding-bottom:4px;'">
                                <template x-if="ddFiltered.length === 0">
                                    <div style="padding:16px; font-size:13px; color:#64748b; text-align:center;">Pegawai tidak ditemukan.</div>
                                </template>
                                <template x-for="p in ddFiltered" :key="p.id">
                                    <div @click="selectPegawai(p.id, p.nama)"
                                         style="padding:10px 16px; cursor:pointer; border-bottom:1px solid #f1f5f9;"
                                         onmouseover="this.style.backgroundColor='#eff6ff'"
                                         onmouseout="this.style.backgroundColor='transparent'">
                                        <div style="display:flex; align-items:center; justify-content:space-between;">
                                            <div>
                                                <div x-text="p.nama" style="font-size:13px; font-weight:600; color:#1e293b;"></div>
                                                <div x-show="p.nip" x-text="'NIP: ' + p.nip" style="font-size:11px; color:#64748b; margin-top:2px;"></div>
                                            </div>
                                            <svg x-show="selectedPegawaiId == p.id" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div style="padding:8px 14px; border-top:1px solid #e2e8f0; background:#f8fafc; flex-shrink:0;">
                                <button type="button" @click.stop="clearPegawai()"
                                        :disabled="!selectedPegawaiId"
                                        :style="!selectedPegawaiId ? 'opacity:0.4; cursor:not-allowed;' : 'cursor:pointer;'"
                                        style="display:flex; align-items:center; gap:5px; color:#ef4444; font-size:12px; font-weight:600; background:none; border:none; padding:3px 0; outline:none; width:100%;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    Hapus Pilihan
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Notifikasi riwayat ditemukan --}}
                    <div x-show="hasRiwayat" style="display:none; margin-top:8px; font-size:12px; color:#059669; font-weight:500; display:flex; align-items:center; gap:5px;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        Ditemukan riwayat KGB terakhir. Data gaji lama otomatis terisi.
                    </div>
                </div>

                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Nomor Dokumen KGB *</label>
                    <input type="text" name="nomor_dokumen" value="{{ old('nomor_dokumen') }}" required
                           placeholder="Contoh: 800.1.11.1/DISHUB/KGB/2026/001"
                           style="width:100%; padding:10px 14px; border:1px solid {{ $errors->has('nomor_dokumen') ? '#fca5a5' : '#cbd5e1' }}; border-radius:8px; font-size:14px; color:#1e293b; outline:none;">
                    <div style="font-size:12px; color:#64748b; margin-top:6px;">Isi sesuai nomor dokumen dari administrasi Dishub.</div>
                </div>
            </div>

            {{-- DATA GAJI LAMA --}}
            <div style="padding:24px; border-bottom:1px solid #e2e8f0;">
                <h3 style="margin:0 0 16px; font-size:15px; font-weight:600; color:#1e293b; display:flex; align-items:center; gap:8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Data Gaji Lama
                </h3>

                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:20px;">
                    <div>
                        <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Gaji Pokok Lama (Rp) *</label>
                        <input type="number" name="gaji_pokok_lama"
                               x-model="gajiPokokLama"
                               :readonly="hasRiwayat"
                               required min="0"
                               placeholder="Contoh: 3200000"
                               style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:8px; font-size:14px; outline:none;"
                               :style="hasRiwayat ? 'background:#f1f5f9; color:#64748b; cursor:not-allowed;' : 'background:#fff; color:#1e293b;'">
                    </div>
                    <div>
                        <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">TMT Sebelumnya *</label>
                        <input type="date" name="tmt_sebelumnya"
                               x-model="tmtSebelumnya"
                               :readonly="hasRiwayat"
                               required
                               style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:8px; font-size:14px; outline:none;"
                               :style="hasRiwayat ? 'background:#f1f5f9; color:#64748b; cursor:not-allowed;' : 'background:#fff; color:#1e293b;'">
                    </div>
                </div>
            </div>

            {{-- DETAIL USULAN GAJI BARU --}}
            <div style="padding:24px; border-bottom:1px solid #e2e8f0;">
                <h3 style="margin:0 0 16px; font-size:15px; font-weight:600; color:#1e293b; display:flex; align-items:center; gap:8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    Detail Usulan Gaji Baru
                </h3>

                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:20px; margin-bottom:20px;">
                    <div>
                        <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Gaji Pokok Baru (Rp) *</label>
                        <input type="number" name="gaji_pokok_baru"
                               value="{{ old('gaji_pokok_baru') }}"
                               required min="0"
                               placeholder="Contoh: 3500000"
                               style="width:100%; padding:10px 14px; border:1px solid {{ $errors->has('gaji_pokok_baru') ? '#fca5a5' : '#cbd5e1' }}; border-radius:8px; font-size:14px; color:#1e293b; outline:none;">
                    </div>
                    <div>
                        <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">TMT Berikutnya</label>
                        <input type="text"
                               x-model="tmtBerikutnyaDisplay"
                               readonly
                               placeholder="Dihitung otomatis +24 bulan"
                               style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:8px; font-size:14px; color:#64748b; background:#f1f5f9; cursor:not-allowed; outline:none;">
                        <div style="font-size:12px; color:#64748b; margin-top:6px;">Dihitung otomatis +24 bulan dari TMT Sebelumnya.</div>
                    </div>
                </div>

                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Catatan Tambahan</label>
                    <textarea name="catatan" rows="3"
                              placeholder="Opsional..."
                              style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:8px; font-size:14px; color:#1e293b; resize:vertical; min-height:80px; max-height:160px; outline:none;">{{ old('catatan') }}</textarea>
                </div>
            </div>

            {{-- DOKUMEN PENDUKUNG --}}
            <div style="padding:24px;">
                <h3 style="margin:0 0 16px; font-size:15px; font-weight:600; color:#1e293b; display:flex; align-items:center; gap:8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                    Dokumen Pendukung
                </h3>

                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">File Pendukung (PDF, DOC, JPG, PNG)</label>
                    <input type="file" name="file_pendukung"
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                           style="width:100%; max-width:400px; padding:10px 14px; border:1px solid #cbd5e1; border-radius:8px; font-size:14px; color:#1e293b; background:#f8fafc;">
                    <div style="font-size:12px; color:#64748b; margin-top:6px;">Maksimal ukuran file 10MB.</div>
                </div>
            </div>

            {{-- ACTION FOOTER --}}
            <div style="padding:16px 24px; background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end; gap:12px;">
                <a href="{{ route('kepegawaian.kgb.index') }}" style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center; padding:10px 18px; background:white; color:#475569; border:1px solid #cbd5e1; border-radius:8px; font-size:14px; font-weight:600;">
                    Batal
                </a>
                <button type="submit" style="cursor:pointer; display:inline-flex; align-items:center; gap:8px; justify-content:center; padding:10px 20px; background:#2563eb; color:white; border:none; border-radius:8px; font-size:14px; font-weight:600;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>

    <script>
        const riwayatKgb = @json($riwayatKgb);
        const kgbPegawaiData = @json($kgbPegawaiList);

        function kgbForm() {
            return {
                // === State lama (JANGAN diubah) ===
                selectedPegawaiId: '{{ $kgbInitId }}',
                gajiPokokLama: '{{ old('gaji_pokok_lama') }}',
                tmtSebelumnya: '{{ old('tmt_sebelumnya') }}',
                hasRiwayat: false,

                // === State baru untuk searchable dropdown ===
                ddOpen: false,
                ddSearch: '',
                pegawaiLabel: '{{ addslashes($kgbInitName) }}',
                ddDirection: 'down',
                ddMaxHeight: 240,

                get ddFiltered() {
                    if (!this.ddSearch) return kgbPegawaiData;
                    const q = this.ddSearch.toLowerCase();
                    return kgbPegawaiData.filter(p =>
                        p.nama.toLowerCase().includes(q) || p.nip.toLowerCase().includes(q)
                    );
                },

                // === Method dropdown ===
                toggleDropdown(el) {
                    if (this.ddOpen) { this.ddOpen = false; return; }
                    const rect = el.getBoundingClientRect();
                    const chrome = 90;
                    const below = window.innerHeight - rect.bottom;
                    const above = rect.top;
                    if (below >= 200 && below >= above) {
                        this.ddDirection = 'down';
                        this.ddMaxHeight = Math.max(80, Math.min(240, below - chrome));
                    } else {
                        this.ddDirection = 'up';
                        this.ddMaxHeight = Math.max(80, Math.min(240, above - chrome));
                    }
                    this.ddOpen = true;
                },

                selectPegawai(id, nama) {
                    this.selectedPegawaiId = String(id);
                    this.pegawaiLabel = nama;
                    this.ddOpen = false;
                    this.ddSearch = '';
                    // Tetap panggil updateDataLama setelah pegawai dipilih
                    this.updateDataLama();
                },

                clearPegawai() {
                    this.selectedPegawaiId = '';
                    this.pegawaiLabel = '';
                    this.ddSearch = '';
                    this.ddOpen = false;
                    // Reset data lama
                    this.gajiPokokLama = '';
                    this.tmtSebelumnya = '';
                    this.hasRiwayat = false;
                },

                // === Computed (JANGAN diubah) ===
                get tmtBerikutnyaDisplay() {
                    if (!this.tmtSebelumnya) return '';
                    let d = new Date(this.tmtSebelumnya);
                    if (isNaN(d.getTime())) return '';
                    d.setFullYear(d.getFullYear() + 2);
                    return d.toISOString().split('T')[0];
                },

                // === Method lama (JANGAN diubah) ===
                updateDataLama() {
                    if (this.selectedPegawaiId && riwayatKgb[this.selectedPegawaiId]) {
                        const histori = riwayatKgb[this.selectedPegawaiId];
                        this.gajiPokokLama = histori.gaji_pokok_baru;
                        // tmt_berikutnya dari histori menjadi tmt_sebelumnya saat ini
                        if (histori.tmt_berikutnya) {
                            this.tmtSebelumnya = histori.tmt_berikutnya.split('T')[0];
                        }
                        this.hasRiwayat = true;
                    } else {
                        this.gajiPokokLama = '';
                        this.tmtSebelumnya = '';
                        this.hasRiwayat = false;
                    }
                },

                init() {
                    if (this.selectedPegawaiId) {
                        this.updateDataLama();
                    }
                }
            }
        }
    </script>
</x-app-layout>
