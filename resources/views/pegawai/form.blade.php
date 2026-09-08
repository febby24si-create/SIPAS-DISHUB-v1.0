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
                        <label class="form-label" for="unit_kerja_id">Unit Kerja</label>
                        <select id="unit_kerja_id" name="unit_kerja_id" class="form-control">
                            <option value="">-- Pilih Unit Kerja --</option>
                            @foreach($unitKerjas as $uk)
                                <option value="{{ $uk->id }}" {{ old('unit_kerja_id', $pegawai->unit_kerja_id) == $uk->id ? 'selected' : '' }}>
                                    {{ $uk->nama }}
                                </option>
                            @endforeach
                        </select>
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
