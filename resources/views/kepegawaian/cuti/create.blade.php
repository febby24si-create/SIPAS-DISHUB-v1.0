<x-app-layout>
    <x-slot name="header">Buat Pengajuan Cuti</x-slot>

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

        <form action="{{ route('kepegawaian.cuti.store') }}" method="POST" enctype="multipart/form-data" style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            @csrf
            
            <div style="padding:24px; display:flex; flex-direction:column; gap:20px;">
                <h3 style="margin:0; font-size:16px; font-weight:600; color:#1e293b; display:flex; align-items:center; gap:8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Informasi Pegawai
                </h3>

                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Pilih Pegawai *</label>
                    <select name="pegawai_id" required style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:12px; font-size:14px; color:#1e293b;">
                        <option value="">-- Pilih Pegawai --</option>
                        @foreach($pegawais as $pegawai)
                            <option value="{{ $pegawai->id }}" @selected(old('pegawai_id') == $pegawai->id)>
                                {{ $pegawai->nama }} (NIP. {{ $pegawai->nip }}) - {{ $pegawai->jabatan }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="height:1px; background:rgba(0,0,0,0.05);"></div>

            <div style="padding:24px; display:flex; flex-direction:column; gap:20px;">
                <h3 style="margin:0; font-size:16px; font-weight:600; color:#1e293b; display:flex; align-items:center; gap:8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Detail Cuti
                </h3>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div>
                        <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Jenis Cuti *</label>
                        <select name="jenis_cuti" required style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:12px; font-size:14px; color:#1e293b;">
                            <option value="Tahunan" @selected(old('jenis_cuti') == 'Tahunan')>Cuti Tahunan</option>
                            <option value="Besar" @selected(old('jenis_cuti') == 'Besar')>Cuti Besar</option>
                            <option value="Sakit" @selected(old('jenis_cuti') == 'Sakit')>Cuti Sakit</option>
                            <option value="Melahirkan" @selected(old('jenis_cuti') == 'Melahirkan')>Cuti Melahirkan</option>
                            <option value="Alasan Penting" @selected(old('jenis_cuti') == 'Alasan Penting')>Cuti Alasan Penting</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Alasan Cuti *</label>
                    <textarea name="alasan" required rows="3" style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:12px; font-size:14px; color:#1e293b; resize:vertical;">{{ old('alasan') }}</textarea>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div>
                        <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Tanggal Mulai *</label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:12px; font-size:14px; color:#1e293b;">
                    </div>
                    <div>
                        <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Tanggal Selesai *</label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:12px; font-size:14px; color:#1e293b;">
                        <span style="font-size:11px; color:#94a3b8; display:block; margin-top:4px;">Lama cuti akan dihitung otomatis oleh sistem.</span>
                    </div>
                </div>

                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Alamat Selama Cuti</label>
                    <textarea name="alamat_cuti" rows="2" style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:12px; font-size:14px; color:#1e293b; resize:vertical;">{{ old('alamat_cuti') }}</textarea>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div>
                        <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Nomor Telepon</label>
                        <input type="text" name="no_telp" value="{{ old('no_telp') }}" style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:12px; font-size:14px; color:#1e293b;">
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
                    <label style="display:block; font-size:13px; font-weight:600; color:#475569; margin-bottom:6px;">Upload Lampiran (PDF, JPG, PNG)</label>
                    <input type="file" name="file_pendukung" accept=".pdf,.jpg,.jpeg,.png" style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:12px; font-size:14px; color:#1e293b; background:#f8fafc;">
                    <span style="font-size:11px; color:#94a3b8; display:block; margin-top:4px;">File surat dokter atau dokumen relevan lainnya. Maks 2MB.</span>
                </div>
            </div>

            <div style="padding:24px; background:#f8fafc; border-top:1px solid rgba(0,0,0,0.05); display:flex; justify-content:flex-end; gap:12px;">
                <a href="{{ route('kepegawaian.cuti.index') }}" style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center; padding:12px 20px; background:white; color:#475569; border:1px solid #cbd5e1; border-radius:12px; font-size:14px; font-weight:600;">
                    Batal
                </a>
                <button type="submit" style="cursor:pointer; display:inline-flex; align-items:center; justify-content:center; padding:12px 20px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; border:none; border-radius:12px; font-size:14px; font-weight:600; box-shadow:0 4px 12px rgba(59,130,246,0.3);">
                    Simpan Draft
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
