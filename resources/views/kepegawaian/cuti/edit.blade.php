<x-app-layout>
    <x-slot name="header">Edit Pengajuan Cuti</x-slot>

    <div style="max-w:800px; margin:0 auto; display:flex; flex-direction:column; gap:20px;">
        
        <div style="display:flex; align-items:center; justify-content:space-between;">
            <a href="{{ route('kepegawaian.cuti.index') }}" style="display:inline-flex; align-items:center; gap:8px; font-size:14px; font-weight:600; color:#64748b; text-decoration:none;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali
            </a>
        </div>

        @if ($errors->any())
            <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:14px; padding:16px; color:#991b1b; font-size:14px;">
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

        <form action="{{ route('kepegawaian.cuti.update', $cuti) }}" method="POST" enctype="multipart/form-data" style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            @csrf
            @method('PUT')
            
            <div style="padding:24px; display:flex; flex-direction:column; gap:20px;">
                <h3 style="margin:0; font-size:16px; font-weight:600; color:#1e293b; display:flex; align-items:center; gap:8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Informasi Pegawai
                </h3>

                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Pilih Pegawai *</label>
                    @php
                        $editCutiPegawaiList = $pegawais->map(fn($p) => [
                            'id'  => $p->id,
                            'nama'=> $p->nama,
                            'nip' => $p->nip ?? '',
                        ])->values()->toArray();
                        $editCutiInitId   = old('pegawai_id', $cuti->pegawai_id);
                        $editCutiInitName = '';
                        if ($editCutiInitId) {
                            $fp = $pegawais->firstWhere('id', $editCutiInitId);
                            if ($fp) $editCutiInitName = $fp->nama;
                        }
                    @endphp
                    <div x-data="{
                        open: false,
                        search: '',
                        value: '{{ $editCutiInitId }}',
                        label: '{{ addslashes($editCutiInitName) }}',
                        data: {{ json_encode($editCutiPegawaiList) }},
                        direction: 'down',
                        maxListHeight: 260,

                        get filtered() {
                            if (!this.search) return this.data;
                            const q = this.search.toLowerCase();
                            return this.data.filter(p =>
                                p.nama.toLowerCase().includes(q) || p.nip.toLowerCase().includes(q)
                            );
                        },

                        toggleOpen(el) {
                            if (this.open) { this.open = false; return; }
                            const rect = el.getBoundingClientRect();
                            const chrome = 90;
                            const below = window.innerHeight - rect.bottom;
                            const above = rect.top;
                            if (below >= 200 && below >= above) {
                                this.direction = 'down';
                                this.maxListHeight = Math.max(80, Math.min(260, below - chrome));
                            } else {
                                this.direction = 'up';
                                this.maxListHeight = Math.max(80, Math.min(260, above - chrome));
                            }
                            this.open = true;
                        },

                        select(id, nama) {
                            this.value = id; this.label = nama;
                            this.open = false; this.search = '';
                        },

                        clear() {
                            this.value = ''; this.label = ''; this.search = ''; this.open = false;
                        }
                    }" style="position:relative;">
                        <input type="hidden" name="pegawai_id" :value="value" required>
                        <button type="button"
                                @click="toggleOpen($el)"
                                @click.outside="open = false"
                                style="display:flex; justify-content:space-between; align-items:center; width:100%; text-align:left; background:#fff; cursor:pointer; padding:10px 14px; border:1px solid {{ $errors->has('pegawai_id') ? '#fca5a5' : '#e2e8f0' }}; border-radius:12px; font-size:14px; gap:8px;">
                            <span x-text="label || '-- Pilih Pegawai --'"
                                  :style="!label ? 'color:#94a3b8;' : 'color:#1e293b;'"></span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>

                        <div x-show="open"
                             x-transition.opacity
                             :style="direction === 'up'
                                 ? 'display:flex; flex-direction:column-reverse; position:absolute; z-index:50; width:100%; bottom:calc(100% + 4px); background:white; border:1px solid #e2e8f0; border-radius:12px; box-shadow:0 10px 25px -5px rgba(0,0,0,0.12); overflow:hidden;'
                                 : 'display:flex; flex-direction:column; position:absolute; z-index:50; width:100%; top:calc(100% + 4px); background:white; border:1px solid #e2e8f0; border-radius:12px; box-shadow:0 10px 25px -5px rgba(0,0,0,0.12); overflow:hidden;'"
                             style="display:none;">

                            <div style="padding:10px 12px; border-bottom:1px solid #e2e8f0; background:#f8fafc; flex-shrink:0;">
                                <div style="position:relative;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute; left:10px; top:9px; color:#94a3b8;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                    <input type="text" x-model="search" placeholder="Cari nama atau NIP..."
                                           style="width:100%; padding:7px 10px 7px 32px; border:1px solid #cbd5e1; border-radius:8px; font-size:13px; outline:none; box-sizing:border-box;"
                                           @click.stop>
                                </div>
                            </div>

                            <div :style="'overflow-y:auto; max-height:' + maxListHeight + 'px;'">
                                <template x-if="filtered.length === 0">
                                    <div style="padding:16px; font-size:13px; color:#64748b; text-align:center;">Pegawai tidak ditemukan.</div>
                                </template>
                                <template x-for="p in filtered" :key="p.id">
                                    <div @click="select(p.id, p.nama)"
                                         style="padding:10px 16px; cursor:pointer; border-bottom:1px solid #f1f5f9;"
                                         onmouseover="this.style.backgroundColor='#eff6ff'"
                                         onmouseout="this.style.backgroundColor='transparent'">
                                        <div style="display:flex; align-items:center; justify-content:space-between;">
                                            <div>
                                                <div x-text="p.nama" style="font-size:13px; font-weight:600; color:#1e293b;"></div>
                                                <div x-show="p.nip" x-text="'NIP: ' + p.nip" style="font-size:11px; color:#64748b; margin-top:2px;"></div>
                                            </div>
                                            <svg x-show="value == p.id" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        </div>
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
                    </div>
                </div>
            </div>

            <div style="height:1px; background:rgba(0,0,0,0.05);"></div>

            <div style="padding:24px; display:flex; flex-direction:column; gap:20px;">
                <h3 style="margin:0; font-size:16px; font-weight:600; color:#1e293b; display:flex; align-items:center; gap:8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Detail Cuti
                </h3>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
                    <div>
                        <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Nomor Surat Cuti *</label>
                        <input type="text" name="nomor_surat_cuti" value="{{ old('nomor_surat_cuti', $cuti->surat->nomor_surat ?? $cuti->nomor_pengajuan) }}" required placeholder="Contoh: B/123/800.1.11.1/DISHUB/2026" style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:12px; font-size:14px; color:#1e293b;">
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div>
                        <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Jenis Cuti *</label>
                        <select name="jenis_cuti" required style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:12px; font-size:14px; color:#1e293b;">
                            <option value="Tahunan" @selected(old('jenis_cuti', $cuti->jenis_cuti) == 'Tahunan')>Cuti Tahunan</option>
                            <option value="Besar" @selected(old('jenis_cuti', $cuti->jenis_cuti) == 'Besar')>Cuti Besar</option>
                            <option value="Sakit" @selected(old('jenis_cuti', $cuti->jenis_cuti) == 'Sakit')>Cuti Sakit</option>
                            <option value="Melahirkan" @selected(old('jenis_cuti', $cuti->jenis_cuti) == 'Melahirkan')>Cuti Melahirkan</option>
                            <option value="Alasan Penting" @selected(old('jenis_cuti', $cuti->jenis_cuti) == 'Alasan Penting')>Cuti Alasan Penting</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Alasan Cuti *</label>
                    <textarea name="alasan" required rows="3" style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:12px; font-size:14px; color:#1e293b; resize:vertical;">{{ old('alasan', $cuti->alasan) }}</textarea>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div>
                        <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Tanggal Mulai *</label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('Y-m-d')) }}" required style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:12px; font-size:14px; color:#1e293b;">
                    </div>
                    <div>
                        <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Tanggal Selesai *</label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', \Carbon\Carbon::parse($cuti->tanggal_selesai)->format('Y-m-d')) }}" required style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:12px; font-size:14px; color:#1e293b;">
                        <span style="font-size:11px; color:#94a3b8; display:block; margin-top:4px;">Lama cuti akan dihitung otomatis oleh sistem.</span>
                    </div>
                </div>



                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div>
                        <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Nomor Telepon</label>
                        <input type="text" name="no_telp" value="{{ old('no_telp', $cuti->no_telp) }}" style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:12px; font-size:14px; color:#1e293b;">
                    </div>
                </div>
            </div>

            <div style="height:1px; background:rgba(0,0,0,0.05);"></div>

            <div style="padding:24px; display:flex; flex-direction:column; gap:20px;">
                <h3 style="margin:0; font-size:16px; font-weight:600; color:#1e293b; display:flex; align-items:center; gap:8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                    Dokumen Pendukung
                </h3>
                
                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Upload Lampiran Baru (Opsional)</label>
                    <input type="file" name="file_pendukung" accept=".pdf,.jpg,.jpeg,.png" style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:12px; font-size:14px; color:#1e293b; background:#f8fafc;">
                    <span style="font-size:11px; color:#94a3b8; display:block; margin-top:4px;">Upload ulang akan menambahkan lampiran baru (bukan menimpa). Maks 2MB.</span>
                </div>
            </div>

            <div style="padding:24px; background:#f8fafc; border-top:1px solid rgba(0,0,0,0.05); display:flex; justify-content:flex-end; gap:12px;">
                <a href="{{ route('kepegawaian.cuti.index') }}" style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center; padding:12px 20px; background:white; color:#475569; border:1px solid #cbd5e1; border-radius:12px; font-size:14px; font-weight:600;">
                    Batal
                </a>
                <button type="submit" style="cursor:pointer; display:inline-flex; align-items:center; justify-content:center; padding:12px 20px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; border:none; border-radius:12px; font-size:14px; font-weight:600; box-shadow:0 4px 12px rgba(59,130,246,0.3);">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
