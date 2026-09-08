<x-app-layout>
    <x-slot name="header">{{ $unitKerja->exists ? 'Edit' : 'Tambah' }} Unit Kerja</x-slot>

    <div style="max-width:560px;">
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:20px; font-size:13px; color:#94a3b8;">
            <a href="{{ route('unit-kerja.index') }}" style="color:#64748b; text-decoration:none; font-weight:500; transition:color 0.15s;">Unit Kerja</a>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            <span style="color:#1e293b; font-weight:600;">{{ $unitKerja->exists ? 'Edit' : 'Tambah Baru' }}</span>
        </div>

        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <div style="padding:18px 24px; border-bottom:1px solid rgba(0,0,0,0.05); background:linear-gradient(135deg,#f8fafc,#f0f9ff);">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:36px; height:36px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:15px; font-weight:700; color:#1e293b; margin:0;">{{ $unitKerja->exists ? 'Edit Unit Kerja' : 'Tambah Unit Kerja Baru' }}</h3>
                        <p style="font-size:12px; color:#94a3b8; margin:0;">Isi formulir di bawah ini dengan benar</p>
                    </div>
                </div>
            </div>

            <form action="{{ $unitKerja->exists ? route('unit-kerja.update', $unitKerja) : route('unit-kerja.store') }}"
                  method="POST" style="padding:24px; display:flex; flex-direction:column; gap:20px;">
                @csrf
                @if ($unitKerja->exists) @method('PUT') @endif

                <div class="form-group">
                    <label class="form-label" for="kode_unit">Kode Unit Kerja</label>
                    <input id="kode_unit" type="text" name="kode_unit"
                           value="{{ old('kode_unit', $unitKerja->kode_unit) }}"
                           class="form-control"
                           placeholder="Contoh: B.01" required>
                    @error('kode_unit') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="nama">Nama Unit Kerja</label>
                    <input id="nama" type="text" name="nama"
                           value="{{ old('nama', $unitKerja->nama) }}"
                           class="form-control"
                           placeholder="Contoh: Bidang Lalu Lintas" required>
                    @error('nama') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="kepala_id">Kepala Unit (Opsional)</label>
                    <select id="kepala_id" name="kepala_id" class="form-control">
                        <option value="">-- Pilih Kepala Unit --</option>
                        @foreach($pegawais as $pegawai)
                            <option value="{{ $pegawai->id }}" {{ old('kepala_id', $unitKerja->kepala_id) == $pegawai->id ? 'selected' : '' }}>
                                {{ $pegawai->nama }} (NIP: {{ $pegawai->nip }})
                            </option>
                        @endforeach
                    </select>
                    @error('kepala_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div style="display:flex; align-items:center; gap:12px; padding-top:4px; border-top:1px solid rgba(0,0,0,0.05); margin-top:4px;">
                    <button type="submit"
                            style="display:inline-flex; align-items:center; gap:8px; padding:11px 22px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; font-size:14px; font-weight:600; border-radius:12px; border:none; cursor:pointer;">
                        Simpan
                    </button>
                    <a href="{{ route('unit-kerja.index') }}"
                       style="display:inline-flex; align-items:center; gap:8px; padding:11px 22px; background:white; color:#64748b; font-size:14px; font-weight:600; border-radius:12px; border:1px solid rgba(0,0,0,0.1); text-decoration:none;">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
