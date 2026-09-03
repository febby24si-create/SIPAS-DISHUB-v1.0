<x-app-layout>
    <x-slot name="header">Input Surat Masuk</x-slot>

    <div style="max-width:640px;">
        <form action="{{ route('surat-masuk.store') }}" method="POST" enctype="multipart/form-data"
              style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:28px; display:flex; flex-direction:column; gap:18px;">
            @csrf

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Jenis Surat</label>
                <select name="jenis_surat_id" style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:14px;">
                    <option value="">-- Pilih Jenis Surat --</option>
                    @foreach ($jenisSuratList as $jenis)
                        <option value="{{ $jenis->id }}" @selected(old('jenis_surat_id') == $jenis->id)>{{ $jenis->nama }}</option>
                    @endforeach
                </select>
                @error('jenis_surat_id') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
            </div>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Klasifikasi (opsional)</label>
                <select name="klasifikasi_id" style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:14px;">
                    <option value="">-- Tanpa Klasifikasi --</option>
                    @foreach ($klasifikasiList as $klasifikasi)
                        <option value="{{ $klasifikasi->id }}" @selected(old('klasifikasi_id') == $klasifikasi->id)>{{ $klasifikasi->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Nomor Surat (dari pengirim, opsional)</label>
                <input type="text" name="nomor_surat" value="{{ old('nomor_surat') }}"
                       style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:14px;">
            </div>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Perihal</label>
                <input type="text" name="perihal" value="{{ old('perihal') }}"
                       style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:14px;">
                @error('perihal') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
            </div>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Pengirim</label>
                <input type="text" name="pengirim" value="{{ old('pengirim') }}"
                       style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:14px;">
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Tanggal Surat</label>
                    <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat') }}"
                           style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:14px;">
                    @error('tanggal_surat') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Tanggal Diterima</label>
                    <input type="date" name="tanggal_diterima" value="{{ old('tanggal_diterima') }}"
                           style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:14px;">
                    @error('tanggal_diterima') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Scan/Dokumen Surat (opsional)</label>
                <input type="file" name="file_dokumen"
                       style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:13px;">
                @error('file_dokumen') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
            </div>

            <div style="display:flex; gap:10px; margin-top:8px;">
                <button type="submit" class="btn-primary" style="padding:10px 20px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; border:none; border-radius:12px; font-size:13px; font-weight:600; cursor:pointer;">
                    Simpan
                </button>
                <a href="{{ route('surat-masuk.index') }}" style="padding:10px 20px; color:#64748b; text-decoration:none; font-size:13px; font-weight:600;">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
