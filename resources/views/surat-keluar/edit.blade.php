<x-app-layout>
    <x-slot name="header">Edit Surat Keluar</x-slot>

    <div style="max-width:640px;">
        <form action="{{ route('surat-keluar.update', $surat) }}" method="POST" enctype="multipart/form-data"
              style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:28px; display:flex; flex-direction:column; gap:18px;">
            @csrf
            @method('PUT')

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Jenis Surat <span style="color:#dc2626;">*</span></label>
                <select name="jenis_surat_id" style="width:100%; padding:10px 14px; border:1px solid {{ $errors->has('jenis_surat_id') ? '#fca5a5' : '#e2e8f0' }}; border-radius:10px; font-size:14px; color:#334155;">
                    <option value="">-- Pilih Jenis Surat --</option>
                    @foreach ($jenisSuratList as $jenis)
                        <option value="{{ $jenis->id }}" @selected(old('jenis_surat_id', $surat->jenis_surat_id) == $jenis->id)>{{ $jenis->nama }}</option>
                    @endforeach
                </select>
                @error('jenis_surat_id') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
            </div>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Klasifikasi (opsional)</label>
                <select name="klasifikasi_id" style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:14px; color:#334155;">
                    <option value="">-- Tanpa Klasifikasi --</option>
                    @foreach ($klasifikasiList as $klasifikasi)
                        <option value="{{ $klasifikasi->id }}" @selected(old('klasifikasi_id', $surat->klasifikasi_id) == $klasifikasi->id)>
                            {{ $klasifikasi->kode }} – {{ $klasifikasi->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Nomor Surat (opsional)</label>
                <input type="text" name="nomor_surat" value="{{ old('nomor_surat', $surat->nomor_surat) }}"
                       style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:14px; box-sizing:border-box;">
            </div>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Perihal <span style="color:#dc2626;">*</span></label>
                <input type="text" name="perihal" value="{{ old('perihal', $surat->perihal) }}"
                       style="width:100%; padding:10px 14px; border:1px solid {{ $errors->has('perihal') ? '#fca5a5' : '#e2e8f0' }}; border-radius:10px; font-size:14px; box-sizing:border-box;">
                @error('perihal') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
            </div>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Tujuan</label>
                <input type="text" name="tujuan" value="{{ old('tujuan', $surat->tujuan) }}"
                       style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:14px; box-sizing:border-box;">
            </div>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Tanggal Surat <span style="color:#dc2626;">*</span></label>
                <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', $surat->tanggal_surat?->format('Y-m-d')) }}"
                       style="width:100%; padding:10px 14px; border:1px solid {{ $errors->has('tanggal_surat') ? '#fca5a5' : '#e2e8f0' }}; border-radius:10px; font-size:14px; box-sizing:border-box;">
                @error('tanggal_surat') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
            </div>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Status</label>
                <select name="status" style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:14px; color:#334155;">
                    <option value="final" @selected(old('status', $surat->status) == 'final')>Final</option>
                    <option value="draft" @selected(old('status', $surat->status) == 'draft')>Draft</option>
                </select>
            </div>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Ganti Dokumen <span style="color:#94a3b8; font-weight:400;">(opsional)</span></label>
                @php $existingFile = $surat->file_dokumen ?? $surat->file_word ?? $surat->file_pdf; @endphp
                @if($existingFile)
                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px; padding:8px 12px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                        <span style="font-size:12px; color:#475569; flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ basename($existingFile) }}</span>
                        <a href="{{ route('surat-keluar.download', $surat) }}" style="font-size:11px; color:#1d4ed8; font-weight:600; text-decoration:none;">Download</a>
                    </div>
                @endif
                <input type="file" name="file_dokumen" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                       style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:13px; box-sizing:border-box;">
                <p style="font-size:11px; color:#94a3b8; margin:4px 0 0;">Format: PDF, JPG, PNG, DOC, DOCX. Maks. 10MB.</p>
                @error('file_dokumen') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
            </div>

            <div style="display:flex; gap:10px; margin-top:8px;">
                <button type="submit" style="padding:10px 20px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; border:none; border-radius:12px; font-size:13px; font-weight:600; cursor:pointer;">
                    Simpan Perubahan
                </button>
                <a href="{{ route('surat-keluar.show', $surat) }}" style="padding:10px 20px; color:#64748b; text-decoration:none; font-size:13px; font-weight:600;">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
