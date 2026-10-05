<x-app-layout>
    <x-slot name="header">Input Surat Keluar</x-slot>

    <div style="max-width:800px; margin:0 auto; display:flex; flex-direction:column; gap:20px;">

        {{-- Page Header --}}
        <div style="display:flex; align-items:flex-start; justify-content:space-between;">
            <div>
                <h2 style="font-size:20px; font-weight:700; color:#1e293b; margin:0 0 4px;">Input Surat Keluar</h2>
                <p style="font-size:14px; color:#64748b; margin:0;">Tambah data surat keluar baru ke dalam sistem.</p>
            </div>
            <a href="{{ route('surat-keluar.index') }}" style="display:inline-flex; align-items:center; gap:8px; font-size:14px; font-weight:600; color:#64748b; text-decoration:none; padding:8px 16px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px;">
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

        {{-- Prepare searchable dropdown data --}}
        @php
            $skUnits = [];
            foreach ($bidangs as $bidang) {
                $seksis = [];
                foreach ($bidang->children as $seksi) {
                    $seksis[] = ['id' => $seksi->id, 'nama' => $seksi->nama, 'bidang_id' => $bidang->id];
                }
                $skUnits[] = ['id' => $bidang->id, 'nama' => $bidang->nama, 'seksis' => $seksis];
            }

            $oldUnitId = old('unit_kerja_id', '');
            $oldBidangId = old('bidang_id', '');
            $oldUnitName = '';
            if ($oldUnitId) {
                foreach ($bidangs as $b) {
                    $found = $b->children->firstWhere('id', $oldUnitId);
                    if ($found) { $oldUnitName = $found->nama; break; }
                }
            }
        @endphp

        <form action="{{ route('surat-keluar.store') }}" method="POST" enctype="multipart/form-data"
              style="background:white; border-radius:16px; border:1px solid #e2e8f0; box-shadow:0 1px 3px rgba(0,0,0,0.05); overflow:hidden;">
            @csrf

            {{-- KLASIFIKASI SURAT --}}
            <div style="padding:24px; border-bottom:1px solid #e2e8f0;">
                <h3 style="margin:0 0 16px; font-size:15px; font-weight:600; color:#1e293b; display:flex; align-items:center; gap:8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    Klasifikasi Surat
                </h3>

                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:20px;">
                    <div>
                        <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Jenis Surat *</label>
                        <select name="jenis_surat_id" style="width:100%; padding:10px 14px; border:1px solid {{ $errors->has('jenis_surat_id') ? '#fca5a5' : '#cbd5e1' }}; border-radius:8px; font-size:14px; color:#1e293b; background:#fff; outline:none;">
                            <option value="">-- Pilih Jenis Surat --</option>
                            @foreach ($jenisSuratList as $jenis)
                                <option value="{{ $jenis->id }}" @selected(old('jenis_surat_id') == $jenis->id)>{{ $jenis->nama }}</option>
                            @endforeach
                        </select>
                        @error('jenis_surat_id') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Klasifikasi <span style="font-weight:400; color:#94a3b8;">(opsional)</span></label>
                        <select name="klasifikasi_id" style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:8px; font-size:14px; color:#1e293b; background:#fff; outline:none;">
                            <option value="">-- Tanpa Klasifikasi --</option>
                            @foreach ($klasifikasiList as $klasifikasi)
                                <option value="{{ $klasifikasi->id }}" @selected(old('klasifikasi_id') == $klasifikasi->id)>
                                    {{ $klasifikasi->kode }} - {{ $klasifikasi->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- UNIT KERJA --}}
            <div style="padding:24px; border-bottom:1px solid #e2e8f0;">
                <h3 style="margin:0 0 16px; font-size:15px; font-weight:600; color:#1e293b; display:flex; align-items:center; gap:8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Unit Kerja
                </h3>

                {{-- Searchable Unit Kerja (Bidang -> Seksi) --}}
                <div x-data="{
                    open: false,
                    search: '',
                    value: '{{ $oldUnitId }}',
                    bidangValue: '{{ $oldBidangId }}',
                    label: '{{ addslashes($oldUnitName) }}',
                    data: {{ json_encode($skUnits) }},
                    direction: 'down',
                    maxListHeight: 240,

                    get filteredData() {
                        if (!this.search) return this.data;
                        const q = this.search.toLowerCase();
                        return this.data.map(b => ({
                            ...b,
                            seksis: b.seksis.filter(s => s.nama.toLowerCase().includes(q))
                        })).filter(b => b.seksis.length > 0);
                    },

                    toggleOpen(el) {
                        if (this.open) { this.open = false; return; }
                        const rect = el.getBoundingClientRect();
                        const chrome = 90;
                        const below = window.innerHeight - rect.bottom;
                        const above = rect.top;
                        if (below >= 200 && below >= above) {
                            this.direction = 'down';
                            this.maxListHeight = Math.max(80, Math.min(240, below - chrome));
                        } else {
                            this.direction = 'up';
                            this.maxListHeight = Math.max(80, Math.min(240, above - chrome));
                        }
                        this.open = true;
                    },

                    selectSeksi(id, bidangId, nama) {
                        this.value = id;
                        this.bidangValue = bidangId;
                        this.label = nama;
                        this.open = false;
                        this.search = '';
                    },

                    clear() {
                        this.value = '';
                        this.bidangValue = '';
                        this.label = '';
                        this.search = '';
                        this.open = false;
                    }
                }" style="position:relative;">

                    <input type="hidden" name="unit_kerja_id" :value="value">
                    <input type="hidden" name="bidang_id" :value="bidangValue">

                    <button type="button"
                            @click="toggleOpen($el)"
                            @click.outside="open = false"
                            style="display:flex; justify-content:space-between; align-items:center; width:100%; text-align:left; background:#fff; cursor:pointer; padding:10px 14px; border:1px solid {{ $errors->has('unit_kerja_id') || $errors->has('bidang_id') ? '#fca5a5' : '#cbd5e1' }}; border-radius:8px; font-size:14px; gap:8px; outline:none; transition:border-color 0.15s ease;">
                        <span x-text="label || '-- Pilih Seksi/Unit Kerja --'"
                              :style="!label ? 'color:#94a3b8;' : 'color:#1e293b; font-weight:500;'"></span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>

                    <div x-show="open"
                         x-transition.opacity
                         :style="direction === 'up'
                             ? 'display:flex; flex-direction:column-reverse; position:absolute; z-index:50; width:100%; bottom:calc(100% + 4px); background:white; border:1px solid #cbd5e1; border-radius:8px; box-shadow:0 10px 25px -5px rgba(0,0,0,0.1); overflow:hidden;'
                             : 'display:flex; flex-direction:column; position:absolute; z-index:50; width:100%; top:calc(100% + 4px); background:white; border:1px solid #cbd5e1; border-radius:8px; box-shadow:0 10px 25px -5px rgba(0,0,0,0.1); overflow:hidden;'"
                         style="display:none;">

                        <div style="padding:10px 12px; border-bottom:1px solid #e2e8f0; background:#f8fafc; flex-shrink:0;">
                            <div style="position:relative;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute; left:10px; top:9px; color:#94a3b8;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                <input type="text" x-model="search" placeholder="Cari unit kerja..."
                                       style="width:100%; padding:7px 10px 7px 32px; border:1px solid #cbd5e1; border-radius:6px; font-size:13px; outline:none; box-sizing:border-box;"
                                       @click.stop>
                            </div>
                        </div>

                        <div :style="'overflow-y:auto; max-height:' + maxListHeight + 'px; padding-bottom:4px;'">
                            <template x-if="filteredData.length === 0">
                                <div style="padding:16px; font-size:13px; color:#64748b; text-align:center;">Unit kerja tidak ditemukan.</div>
                            </template>
                            <template x-for="bidang in filteredData" :key="bidang.id">
                                <div>
                                    <div x-text="bidang.nama"
                                         style="padding:8px 14px 4px; font-size:11px; font-weight:700; color:#94a3b8; letter-spacing:0.5px; text-transform:uppercase; background:#f8fafc; border-bottom:1px solid #f1f5f9; cursor:default;"></div>
                                    <template x-for="seksi in bidang.seksis" :key="seksi.id">
                                        <div @click="selectSeksi(seksi.id, seksi.bidang_id, seksi.nama)"
                                             style="padding:9px 16px 9px 24px; cursor:pointer; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between;"
                                             onmouseover="this.style.backgroundColor='#eff6ff'"
                                             onmouseout="this.style.backgroundColor='transparent'">
                                            <span x-text="seksi.nama" style="font-size:13px; color:#1e293b; padding-left:8px;"></span>
                                            <svg x-show="value == seksi.id" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <div style="padding:8px 14px; border-top:1px solid #e2e8f0; background:#f8fafc; flex-shrink:0;">
                            <button type="button" @click.stop="clear()"
                                    :disabled="!value"
                                    :style="!value ? 'opacity:0.4; cursor:not-allowed;' : 'cursor:pointer;'"
                                    style="display:flex; align-items:center; gap:5px; color:#ef4444; font-size:12px; font-weight:600; background:none; border:none; padding:3px 0; outline:none; width:100%;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                Hapus Pilihan
                            </button>
                        </div>
                    </div>

                    @error('unit_kerja_id') <p style="color:#be123c; font-size:12px; margin:6px 0 0;">{{ $message }}</p> @enderror
                    @error('bidang_id') <p style="color:#be123c; font-size:12px; margin:6px 0 0;">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- DETAIL SURAT --}}
            <div style="padding:24px; border-bottom:1px solid #e2e8f0;">
                <h3 style="margin:0 0 16px; font-size:15px; font-weight:600; color:#1e293b; display:flex; align-items:center; gap:8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Detail Surat
                </h3>

                <div style="margin-bottom:20px;">
                    <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Nomor Surat <span style="font-weight:400; color:#94a3b8;">(opsional, otomatis jika kosong)</span></label>
                    <input type="text" name="nomor_surat" value="{{ old('nomor_surat') }}"
                           placeholder="Contoh: 001/DISHUB/2024"
                           style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:8px; font-size:14px; color:#1e293b; outline:none;">
                </div>

                <div style="margin-bottom:20px;">
                    <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Perihal *</label>
                    <input type="text" name="perihal" value="{{ old('perihal') }}"
                           placeholder="Perihal surat..."
                           style="width:100%; padding:10px 14px; border:1px solid {{ $errors->has('perihal') ? '#fca5a5' : '#cbd5e1' }}; border-radius:8px; font-size:14px; color:#1e293b; outline:none;">
                    @error('perihal') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
                </div>

                <div style="margin-bottom:20px;">
                    <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Tujuan</label>
                    <input type="text" name="tujuan" value="{{ old('tujuan') }}"
                           placeholder="Nama instansi / penerima"
                           style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:8px; font-size:14px; color:#1e293b; outline:none;">
                </div>

                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:20px;">
                    <div>
                        <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Tanggal Surat *</label>
                        <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', now()->format('Y-m-d')) }}"
                               style="width:100%; padding:10px 14px; border:1px solid {{ $errors->has('tanggal_surat') ? '#fca5a5' : '#cbd5e1' }}; border-radius:8px; font-size:14px; color:#1e293b; outline:none;">
                        @error('tanggal_surat') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Status</label>
                        <select name="status" style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:8px; font-size:14px; color:#1e293b; background:#fff; outline:none;">
                            <option value="final" @selected(old('status') == 'final')>Final</option>
                            <option value="draft" @selected(old('status') == 'draft')>Draft</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- DOKUMEN --}}
            <div style="padding:24px;">
                <h3 style="margin:0 0 16px; font-size:15px; font-weight:600; color:#1e293b; display:flex; align-items:center; gap:8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                    Dokumen
                </h3>

                <div style="margin-bottom:16px;">
                    <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">
                        Upload Dokumen <span style="font-weight:400; color:#94a3b8;">(opsional)</span>
                    </label>
                    <input type="file" name="file_dokumen" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                           style="width:100%; max-width:400px; padding:10px 14px; border:1px solid {{ $errors->has('file_dokumen') ? '#fca5a5' : '#cbd5e1' }}; border-radius:8px; font-size:13px; color:#1e293b; background:#f8fafc;">
                    <div style="font-size:12px; color:#64748b; margin-top:6px;">Format: PDF, JPG, PNG, DOC, DOCX. Maksimal 10MB.</div>
                    @error('file_dokumen') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
                </div>

                <div style="padding:14px 16px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; display:flex; gap:12px; align-items:flex-start;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:2px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <div>
                        <p style="margin:0 0 4px; font-size:13px; font-weight:600; color:#1d4ed8;">Tips Templating</p>
                        <p style="margin:0; font-size:13px; color:#1e3a8a;">
                            Untuk membuat surat dengan template Word/PDF otomatis, gunakan menu
                            <a href="{{ route('surat-keluar.create') }}" style="color:#2563eb; font-weight:600; text-decoration:underline;">Buat Surat</a>.
                        </p>
                    </div>
                </div>
            </div>

            {{-- ACTION FOOTER --}}
            <div style="padding:16px 24px; background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end; gap:12px;">
                <a href="{{ route('surat-keluar.index') }}" style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center; padding:10px 18px; background:white; color:#475569; border:1px solid #cbd5e1; border-radius:8px; font-size:14px; font-weight:600;">
                    Batal
                </a>
                <button type="submit" style="cursor:pointer; display:inline-flex; align-items:center; gap:8px; justify-content:center; padding:10px 20px; background:#2563eb; color:white; border:none; border-radius:8px; font-size:14px; font-weight:600;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Simpan Surat Keluar
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
