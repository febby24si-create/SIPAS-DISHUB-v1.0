<x-app-layout>
    <x-slot name="header">{{ $pegawai->exists ? 'Edit' : 'Tambah' }} Pegawai</x-slot>

    <div style="max-width:800px;">
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:20px; font-size:13px; color:#94a3b8;">
            <a href="{{ route('pegawai.index') }}" style="color:#64748b; text-decoration:none; font-weight:500; transition:color 0.15s;">Master Pegawai</a>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            <span style="color:#1e293b; font-weight:600;">{{ $pegawai->exists ? 'Edit' : 'Tambah Baru' }}</span>
        </div>

        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <div style="padding:18px 24px; border-bottom:1px solid rgba(0,0,0,0.05); background:linear-gradient(135deg,#f8fafc,#f0f9ff);">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:36px; height:36px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:15px; font-weight:700; color:#1e293b; margin:0;">{{ $pegawai->exists ? 'Edit Data Pegawai' : 'Tambah Pegawai Baru' }}</h3>
                        <p style="font-size:12px; color:#94a3b8; margin:0;">Isi formulir di bawah ini dengan lengkap dan benar</p>
                    </div>
                </div>
            </div>

            <form action="{{ $pegawai->exists ? route('pegawai.update', $pegawai) : route('pegawai.store') }}"
                  method="POST" style="padding:24px; display:flex; flex-direction:column; gap:20px;">
                @csrf
                @if ($pegawai->exists) @method('PUT') @endif

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div class="form-group">
                        <label class="form-label" for="nip">NIP (Nomor Induk Pegawai)</label>
                        <input id="nip" type="text" name="nip"
                               value="{{ old('nip', $pegawai->nip) }}"
                               class="form-control"
                               placeholder="198001012005011001" required>
                        @error('nip') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="nama">Nama Lengkap (beserta gelar)</label>
                        <input id="nama" type="text" name="nama"
                               value="{{ old('nama', $pegawai->nama) }}"
                               class="form-control"
                               placeholder="Nama lengkap" required>
                        @error('nama') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div class="form-group">
                        <label class="form-label" for="tempat_lahir">Tempat Lahir</label>
                        <input id="tempat_lahir" type="text" name="tempat_lahir"
                               value="{{ old('tempat_lahir', $pegawai->tempat_lahir) }}"
                               class="form-control"
                               placeholder="Contoh: Jakarta">
                        @error('tempat_lahir') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="tanggal_lahir">Tanggal Lahir</label>
                        <input id="tanggal_lahir" type="date" name="tanggal_lahir"
                               value="{{ old('tanggal_lahir', $pegawai->tanggal_lahir?->format('Y-m-d')) }}"
                               class="form-control">
                        @error('tanggal_lahir') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div class="form-group">
                        <label class="form-label" for="jenis_kelamin">Jenis Kelamin</label>
                        <select id="jenis_kelamin" name="jenis_kelamin" class="form-control">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="pendidikan_terakhir">Pendidikan Terakhir</label>
                        <input id="pendidikan_terakhir" type="text" name="pendidikan_terakhir"
                               value="{{ old('pendidikan_terakhir', $pegawai->pendidikan_terakhir) }}"
                               class="form-control"
                               placeholder="Contoh: S1 Teknik Informatika">
                        @error('pendidikan_terakhir') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div style="border-top:1px dashed #e2e8f0; margin:10px 0;"></div>

                <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px;">
                    <div class="form-group">
                        <label class="form-label" for="pangkat">Pangkat</label>
                        <input id="pangkat" type="text" name="pangkat"
                               value="{{ old('pangkat', $pegawai->pangkat) }}"
                               class="form-control"
                               placeholder="Contoh: Penata Muda">
                        @error('pangkat') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="golongan">Golongan</label>
                        <input id="golongan" type="text" name="golongan"
                               value="{{ old('golongan', $pegawai->golongan) }}"
                               class="form-control"
                               placeholder="Contoh: III/a">
                        @error('golongan') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="jabatan">Jabatan</label>
                        <input id="jabatan" type="text" name="jabatan"
                               value="{{ old('jabatan', $pegawai->jabatan) }}"
                               class="form-control"
                               placeholder="Contoh: Staf Analis">
                        @error('jabatan') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div class="form-group">
                        <label class="form-label" for="tmt_pangkat">TMT Pangkat</label>
                        <input id="tmt_pangkat" type="date" name="tmt_pangkat"
                               value="{{ old('tmt_pangkat', $pegawai->tmt_pangkat?->format('Y-m-d')) }}"
                               class="form-control">
                        @error('tmt_pangkat') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="tmt_jabatan">TMT Jabatan</label>
                        <input id="tmt_jabatan" type="date" name="tmt_jabatan"
                               value="{{ old('tmt_jabatan', $pegawai->tmt_jabatan?->format('Y-m-d')) }}"
                               class="form-control">
                        @error('tmt_jabatan') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div class="form-group">
                        <label class="form-label" for="unit_kerja_id">Unit Kerja</label>
                        @php
                            $structuredUnits = [];
                            foreach($unitKerjas->where('parent_id', null) as $bidang) {
                                $seksis = [];
                                foreach($unitKerjas->where('parent_id', $bidang->id) as $seksi) {
                                    $seksis[] = [
                                        'id' => $seksi->id,
                                        'nama' => $seksi->nama
                                    ];
                                }
                                $structuredUnits[] = [
                                    'id' => $bidang->id,
                                    'nama' => $bidang->nama,
                                    'seksis' => $seksis
                                ];
                            }
                            $initialSeksiId = old('unit_kerja_id', $pegawai->unit_kerja_id);
                            $initialSeksiName = '';
                            if($initialSeksiId) {
                                $found = $unitKerjas->where('id', $initialSeksiId)->first();
                                if($found) {
                                    $initialSeksiName = $found->nama;
                                }
                            }
                        @endphp

                        <div x-data="{
                            open: false,
                            search: '',
                            value: '{{ $initialSeksiId }}',
                            label: '{{ addslashes($initialSeksiName) }}',
                            data: {{ json_encode($structuredUnits) }},
                            direction: 'down',
                            maxListHeight: 240,

                            get filteredData() {
                                if (this.search === '') {
                                    return this.data;
                                }
                                const lowerSearch = this.search.toLowerCase();
                                return this.data.map(bidang => {
                                    const filteredSeksis = bidang.seksis.filter(seksi =>
                                        seksi.nama.toLowerCase().includes(lowerSearch)
                                    );
                                    return { ...bidang, seksis: filteredSeksis };
                                }).filter(bidang => bidang.seksis.length > 0);
                            },

                            toggleOpen(triggerEl) {
                                if (this.open) { this.open = false; return; }
                                const rect = triggerEl.getBoundingClientRect();
                                const chrome = 110;
                                const spaceBelow = window.innerHeight - rect.bottom;
                                const spaceAbove = rect.top;
                                if (spaceBelow >= 200 && spaceBelow >= spaceAbove) {
                                    this.direction = 'down';
                                    this.maxListHeight = Math.max(80, Math.min(240, spaceBelow - chrome));
                                } else {
                                    this.direction = 'up';
                                    this.maxListHeight = Math.max(80, Math.min(240, spaceAbove - chrome));
                                }
                                this.open = true;
                            },

                            selectSeksi(id, nama) {
                                this.value = id;
                                this.label = nama;
                                this.open = false;
                                this.search = '';
                            }
                        }" style="position:relative;">

                            <input type="hidden" name="unit_kerja_id" :value="value">

                            <button type="button"
                                    @click="toggleOpen($el)"
                                    @click.outside="open = false"
                                    class="form-control"
                                    style="display:flex; justify-content:space-between; align-items:center; width:100%; text-align:left; background:#fff; cursor:pointer; min-height:42px; border: 1px solid {{ $errors->has('unit_kerja_id') ? '#fca5a5' : '#cbd5e1' }}; border-radius: 8px;">
                                <span x-text="label || '-- Pilih Unit Kerja --'" :style="!label ? 'color:#94a3b8;' : 'color:#0f172a;'"></span>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><polyline points="6 9 12 15 18 9"/></svg>
                            </button>

                            <div x-show="open"
                                 x-transition.opacity
                                 :style="direction === 'up'
                                     ? 'display:flex; flex-direction:column-reverse; position:absolute; z-index:50; width:100%; bottom:calc(100% + 4px); background:white; border:1px solid #e2e8f0; border-radius:8px; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1),0 4px 6px -2px rgba(0,0,0,0.05); overflow:hidden;'
                                     : 'display:flex; flex-direction:column; position:absolute; z-index:50; width:100%; top:calc(100% + 4px); background:white; border:1px solid #e2e8f0; border-radius:8px; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1),0 4px 6px -2px rgba(0,0,0,0.05); overflow:hidden;'"
                                 style="display:none;">

                                <div style="padding:10px; border-bottom:1px solid #e2e8f0; background:#f8fafc; border-top-left-radius:8px; border-top-right-radius:8px;">
                                    <div style="position:relative;">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute; left:12px; top:10px; color:#94a3b8;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                        <input type="text"
                                               x-model="search"
                                               placeholder="Cari unit kerja..."
                                               style="width:100%; padding:8px 12px 8px 36px; border:1px solid #cbd5e1; border-radius:6px; font-size:13px; outline:none; box-sizing:border-box;"
                                               @click.stop>
                                    </div>
                                </div>

                                <div :style="'overflow-y:auto; max-height:' + maxListHeight + 'px; padding:6px 0 10px 0;'">
                                    <template x-if="filteredData.length === 0">
                                        <div style="padding:16px; font-size:13px; color:#64748b; text-align:center;">
                                            Unit kerja tidak ditemukan.
                                        </div>
                                    </template>

                                    <template x-for="bidang in filteredData" :key="bidang.id">
                                        <div>
                                            <div style="padding:8px 16px; font-size:11px; font-weight:700; color:#64748b; background:#f8fafc; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #f1f5f9;" x-text="bidang.nama"></div>
                                            <template x-for="seksi in bidang.seksis" :key="seksi.id">
                                                <div @click="selectSeksi(seksi.id, seksi.nama)"
                                                     style="padding:10px 16px 10px 24px; font-size:13px; color:#334155; cursor:pointer; display:flex; align-items:center; gap:8px;"
                                                     onmouseover="this.style.backgroundColor='#eff6ff'"
                                                     onmouseout="this.style.backgroundColor='transparent'">
                                                    <span x-text="seksi.nama"></span>
                                                    <svg x-show="value == seksi.id" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left:auto;"><polyline points="20 6 9 17 4 12"/></svg>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>

                                <div style="padding:8px 16px; border-top:1px solid #e2e8f0; background:#f8fafc; flex-shrink:0;">
                                    <button type="button"
                                            @click.stop="value = ''; label = ''; search = '';"
                                            :disabled="!value"
                                            :style="!value ? 'opacity:0.4; cursor:not-allowed;' : 'cursor:pointer;'"
                                            style="display:flex; align-items:center; gap:5px; color:#ef4444; font-size:12px; font-weight:600; background:none; border:none; padding:4px 0; outline:none; width:100%;">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                        Hapus Pilihan
                                    </button>
                                </div>
                            </div>
                        </div>
                        @error('unit_kerja_id') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="tmt">
                            TMT (Terhitung Mulai Tanggal)
                            @if($pegawai->exists)
                                <span style="font-size:11px; color:#ef4444; font-weight:normal; margin-left:5px;">*wajib jika mengubah pangkat/golongan/jabatan</span>
                            @else
                                <span style="font-size:11px; color:#ef4444; font-weight:normal; margin-left:5px;">*wajib untuk data awal</span>
                            @endif
                        </label>
                        <input id="tmt" type="date" name="tmt"
                               value="{{ old('tmt') }}"
                               class="form-control"
                               {{ !$pegawai->exists ? 'required' : '' }}>
                        <p style="font-size:12px; color:#94a3b8; margin-top:6px;">Tanggal SK kepangkatan/jabatan terbaru.</p>
                        @error('tmt') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                        <input type="checkbox" name="status_aktif" value="1" {{ old('status_aktif', $pegawai->exists ? $pegawai->status_aktif : true) ? 'checked' : '' }} style="width:18px; height:18px; border-radius:4px; border:1px solid #cbd5e1;">
                        <span style="font-size:14px; font-weight:500; color:#1e293b;">Pegawai Aktif</span>
                    </label>
                </div>

                <div style="display:flex; align-items:center; gap:12px; padding-top:4px; border-top:1px solid rgba(0,0,0,0.05); margin-top:4px;">
                    <button type="submit"
                            style="display:inline-flex; align-items:center; gap:8px; padding:11px 22px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; font-size:14px; font-weight:600; border-radius:12px; border:none; cursor:pointer;">
                        Simpan
                    </button>
                    <a href="{{ route('pegawai.index') }}"
                       style="display:inline-flex; align-items:center; gap:8px; padding:11px 22px; background:white; color:#64748b; font-size:14px; font-weight:600; border-radius:12px; border:1px solid rgba(0,0,0,0.1); text-decoration:none;">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
