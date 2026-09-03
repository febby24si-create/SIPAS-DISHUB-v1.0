<x-app-layout>
    <x-slot name="header">Input Surat Keluar</x-slot>

    <div style="max-width:640px;">
        <form action="{{ route('surat-keluar.store') }}" method="POST" enctype="multipart/form-data"
              style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:28px; display:flex; flex-direction:column; gap:18px;">
            @csrf

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Jenis Surat <span style="color:#dc2626;">*</span></label>
                <select name="jenis_surat_id" style="width:100%; padding:10px 14px; border:1px solid {{ $errors->has('jenis_surat_id') ? '#fca5a5' : '#e2e8f0' }}; border-radius:10px; font-size:14px; color:#334155;">
                    <option value="">-- Pilih Jenis Surat --</option>
                    @foreach ($jenisSuratList as $jenis)
                        <option value="{{ $jenis->id }}" @selected(old('jenis_surat_id') == $jenis->id)>{{ $jenis->nama }}</option>
                    @endforeach
                </select>
                @error('jenis_surat_id') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
            </div>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Klasifikasi (opsional)</label>
                <select name="klasifikasi_id" style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:14px; color:#334155;">
                    <option value="">-- Tanpa Klasifikasi --</option>
                    @foreach ($klasifikasiList as $klasifikasi)
                        <option value="{{ $klasifikasi->id }}" @selected(old('klasifikasi_id') == $klasifikasi->id)>
                            {{ $klasifikasi->kode }} – {{ $klasifikasi->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Nomor Surat (opsional)</label>
                <input type="text" name="nomor_surat" value="{{ old('nomor_surat') }}"
                       style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:14px; box-sizing:border-box;" placeholder="Contoh: 001/DISHUB/2024">
            </div>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Perihal <span style="color:#dc2626;">*</span></label>
                <input type="text" name="perihal" value="{{ old('perihal') }}"
                       style="width:100%; padding:10px 14px; border:1px solid {{ $errors->has('perihal') ? '#fca5a5' : '#e2e8f0' }}; border-radius:10px; font-size:14px; box-sizing:border-box;" placeholder="Perihal surat...">
                @error('perihal') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
            </div>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Tujuan</label>
                <input type="text" name="tujuan" value="{{ old('tujuan') }}"
                       style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:14px; box-sizing:border-box;" placeholder="Nama instansi / penerima">
            </div>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Tanggal Surat <span style="color:#dc2626;">*</span></label>
                <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', now()->format('Y-m-d')) }}"
                       style="width:100%; padding:10px 14px; border:1px solid {{ $errors->has('tanggal_surat') ? '#fca5a5' : '#e2e8f0' }}; border-radius:10px; font-size:14px; box-sizing:border-box;">
                @error('tanggal_surat') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
            </div>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Status</label>
                <select name="status" style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:14px; color:#334155;">
                    <option value="final" @selected(old('status') == 'final')>Final</option>
                    <option value="draft" @selected(old('status') == 'draft')>Draft</option>
                </select>
            </div>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Upload Dokumen (opsional)</label>
                <input type="file" name="file_dokumen" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                       style="width:100%; padding:10px 14px; border:1px solid {{ $errors->has('file_dokumen') ? '#fca5a5' : '#e2e8f0' }}; border-radius:10px; font-size:13px; box-sizing:border-box;">
                <p style="font-size:11px; color:#94a3b8; margin:4px 0 0;">Format: PDF, JPG, PNG, DOC, DOCX. Maks. 10MB.</p>
                @error('file_dokumen') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
            </div>

            <div style="padding:12px 16px; background:#fffbeb; border:1px solid #fde68a; border-radius:10px;">
                <p style="margin:0; font-size:12px; color:#92400e;">
                    <strong>Tip:</strong> Untuk membuat surat dengan template Word/PDF otomatis, gunakan menu
                    <a href="{{ route('buat-surat.create') }}" style="color:#1d4ed8; font-weight:600;">Buat Surat</a>.
                </p>
            </div>

            <div style="display:flex; gap:10px; margin-top:8px;">
                <button type="submit" style="padding:10px 20px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; border:none; border-radius:12px; font-size:13px; font-weight:600; cursor:pointer;">
                    Simpan
                </button>
                <a href="{{ route('surat-keluar.index') }}" style="padding:10px 20px; color:#64748b; text-decoration:none; font-size:13px; font-weight:600;">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
