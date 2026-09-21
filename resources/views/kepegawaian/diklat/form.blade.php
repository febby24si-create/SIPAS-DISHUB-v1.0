<x-app-layout>
    <x-slot name="header">{{ $diklat->exists ? 'Edit' : 'Tambah' }} Riwayat Diklat</x-slot>

    <div style="max-width:760px;">
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:20px; font-size:13px; color:#94a3b8;">
            <a href="{{ route('pegawai.index') }}" style="color:#64748b; text-decoration:none; font-weight:500;">Master Pegawai</a>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            <a href="{{ route('pegawai.show', $pegawai) }}" style="color:#64748b; text-decoration:none; font-weight:500;">{{ $pegawai->nama }}</a>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            <a href="{{ route('pegawai.diklat.index', $pegawai) }}" style="color:#64748b; text-decoration:none; font-weight:500;">Riwayat Diklat</a>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            <span style="color:#1e293b; font-weight:600;">{{ $diklat->exists ? 'Edit' : 'Tambah Baru' }}</span>
        </div>

        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <div style="padding:18px 24px; border-bottom:1px solid rgba(0,0,0,0.05); background:linear-gradient(135deg,#f8fafc,#f0f9ff);">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:36px; height:36px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:15px; font-weight:700; color:#1e293b; margin:0;">{{ $diklat->exists ? 'Edit Riwayat Diklat' : 'Tambah Riwayat Diklat' }}</h3>
                        <p style="font-size:12px; color:#94a3b8; margin:0;">Untuk: <strong>{{ $pegawai->nama }}</strong></p>
                    </div>
                </div>
            </div>

            <form action="{{ $diklat->exists ? route('pegawai.diklat.update', [$pegawai, $diklat]) : route('pegawai.diklat.store', $pegawai) }}"
                  method="POST" enctype="multipart/form-data"
                  style="padding:24px; display:flex; flex-direction:column; gap:20px;">
                @csrf
                @if($diklat->exists) @method('PUT') @endif

                <div class="form-group">
                    <label class="form-label" for="nama_diklat">Nama Diklat / Pelatihan <span style="color:#ef4444;">*</span></label>
                    <input id="nama_diklat" type="text" name="nama_diklat"
                           value="{{ old('nama_diklat', $diklat->nama_diklat) }}"
                           class="form-control"
                           placeholder="Contoh: Diklat Teknis Pengelolaan Angkutan" required>
                    @error('nama_diklat') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="penyelenggara">Penyelenggara</label>
                    <input id="penyelenggara" type="text" name="penyelenggara"
                           value="{{ old('penyelenggara', $diklat->penyelenggara) }}"
                           class="form-control"
                           placeholder="Contoh: Balai Diklat Perhubungan">
                    @error('penyelenggara') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div class="form-group">
                        <label class="form-label" for="tahun">Tahun</label>
                        <input id="tahun" type="number" name="tahun"
                               value="{{ old('tahun', $diklat->tahun) }}"
                               class="form-control"
                               placeholder="{{ date('Y') }}"
                               min="1980" max="{{ date('Y') + 1 }}">
                        @error('tahun') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="jam_pelajaran">Jam Pelajaran (JP)</label>
                        <input id="jam_pelajaran" type="number" name="jam_pelajaran"
                               value="{{ old('jam_pelajaran', $diklat->jam_pelajaran) }}"
                               class="form-control"
                               placeholder="Contoh: 40"
                               min="1">
                        @error('jam_pelajaran') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="file_sertifikat">
                        File Sertifikat
                        <span style="font-size:11px; color:#64748b; font-weight:normal; margin-left:5px;">(PDF/JPG/PNG, maks. 2MB)</span>
                    </label>
                    @if($diklat->exists && $diklat->file_sertifikat)
                        <div style="margin-bottom:8px; padding:10px 14px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; display:flex; align-items:center; gap:10px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#15803d" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            <span style="font-size:13px; color:#15803d;">File sudah ada.</span>
                            <a href="{{ asset('storage/' . $diklat->file_sertifikat) }}" target="_blank" style="font-size:12px; color:#0369a1;">Lihat File</a>
                            <span style="font-size:12px; color:#64748b;">Upload baru untuk mengganti.</span>
                        </div>
                    @endif
                    <input id="file_sertifikat" type="file" name="file_sertifikat"
                           class="form-control"
                           accept=".pdf,.jpg,.jpeg,.png">
                    @error('file_sertifikat') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div style="display:flex; align-items:center; gap:12px; padding-top:4px; border-top:1px solid rgba(0,0,0,0.05); margin-top:4px;">
                    <button type="submit"
                            style="display:inline-flex; align-items:center; gap:8px; padding:11px 22px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; font-size:14px; font-weight:600; border-radius:12px; border:none; cursor:pointer;">
                        Simpan
                    </button>
                    <a href="{{ route('pegawai.diklat.index', $pegawai) }}"
                       style="display:inline-flex; align-items:center; gap:8px; padding:11px 22px; background:white; color:#64748b; font-size:14px; font-weight:600; border-radius:12px; border:1px solid rgba(0,0,0,0.1); text-decoration:none;">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
