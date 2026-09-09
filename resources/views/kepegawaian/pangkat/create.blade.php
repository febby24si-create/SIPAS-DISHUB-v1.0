<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>Buat Usulan Kenaikan Pangkat</div>
            <a href="{{ route('kepegawaian.pangkat.index') }}" style="display:inline-flex; align-items:center; gap:8px; padding:10px 18px; background:white; border:1px solid #cbd5e1; color:#334155; border-radius:12px; font-size:13px; font-weight:600; text-decoration:none;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:32px; max-width:800px; margin:0 auto;">
        
        @if ($errors->any())
            <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:12px; padding:16px; margin-bottom:24px; color:#991b1b; font-size:14px;">
                <ul style="margin:0; padding-left:20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('kepegawaian.pangkat.store') }}" method="POST" enctype="multipart/form-data" x-data="pangkatForm()">
            @csrf

            {{-- Informasi Pegawai --}}
            <h3 style="font-size:16px; font-weight:600; color:#1e293b; margin:0 0 16px 0; padding-bottom:8px; border-bottom:1px solid #e2e8f0;">
                1. Data Pegawai
            </h3>
            
            <div style="margin-bottom:24px;">
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:8px;">Pilih Pegawai <span style="color:#ef4444;">*</span></label>
                <select name="pegawai_id" x-model="selectedPegawaiId" @change="updatePangkatLama" required style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:14px; color:#1e293b; background:#f8fafc;">
                    <option value="">-- Pilih Pegawai --</option>
                    @foreach($pegawais as $pegawai)
                        <option value="{{ $pegawai->id }}" data-pangkat="{{ $pegawai->pangkat }}" data-golongan="{{ $pegawai->golongan }}">
                            {{ $pegawai->nama }} (NIP: {{ $pegawai->nip }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:32px;">
                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:8px;">Pangkat Lama <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="pangkat_lama" x-model="pangkatLama" required readonly style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:14px; color:#64748b; background:#f1f5f9; cursor:not-allowed;">
                    <small style="color:#94a3b8; font-size:11px; margin-top:4px; display:block;">Terisi otomatis dari data master.</small>
                </div>
                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:8px;">Golongan Lama <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="golongan_lama" x-model="golonganLama" required readonly style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:14px; color:#64748b; background:#f1f5f9; cursor:not-allowed;">
                </div>
            </div>

            {{-- Informasi Kenaikan --}}
            <h3 style="font-size:16px; font-weight:600; color:#1e293b; margin:0 0 16px 0; padding-bottom:8px; border-bottom:1px solid #e2e8f0;">
                2. Detail Usulan Kenaikan
            </h3>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:8px;">Pangkat Baru <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="pangkat_baru" value="{{ old('pangkat_baru') }}" required placeholder="Contoh: Penata Tingkat I" style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:14px; color:#1e293b;">
                </div>
                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:8px;">Golongan Baru <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="golongan_baru" value="{{ old('golongan_baru') }}" required placeholder="Contoh: III/d" style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:14px; color:#1e293b;">
                </div>
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:8px;">TMT Kenaikan Pangkat <span style="color:#ef4444;">*</span></label>
                <input type="date" name="tmt" value="{{ old('tmt') }}" required style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:14px; color:#1e293b;">
            </div>

            <div style="margin-bottom:32px;">
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:8px;">Catatan Tambahan</label>
                <textarea name="catatan" rows="3" placeholder="Opsional..." style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:14px; color:#1e293b; resize:vertical;">{{ old('catatan') }}</textarea>
            </div>

            {{-- Lampiran --}}
            <h3 style="font-size:16px; font-weight:600; color:#1e293b; margin:0 0 16px 0; padding-bottom:8px; border-bottom:1px solid #e2e8f0;">
                3. Dokumen Pendukung
            </h3>

            <div style="margin-bottom:32px;">
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:8px;">File Pendukung (PDF/Doc/JPG/PNG)</label>
                <input type="file" name="file_pendukung" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:14px; color:#1e293b; background:#f8fafc;">
                <small style="color:#94a3b8; font-size:11px; margin-top:4px; display:block;">Maksimal ukuran file 10MB.</small>
            </div>

            <div style="display:flex; justify-content:flex-end;">
                <button type="submit" style="display:inline-flex; align-items:center; gap:8px; padding:12px 24px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; border:none; border-radius:12px; font-size:14px; font-weight:600; cursor:pointer; box-shadow:0 4px 6px -1px rgba(59,130,246,0.2);">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Simpan & Buat Usulan
                </button>
            </div>

        </form>
    </div>

    <script>
        function pangkatForm() {
            return {
                selectedPegawaiId: '{{ old('pegawai_id') }}',
                pangkatLama: '{{ old('pangkat_lama') }}',
                golonganLama: '{{ old('golongan_lama') }}',

                updatePangkatLama(event) {
                    const select = event.target;
                    const option = select.options[select.selectedIndex];
                    
                    if (option && option.value) {
                        this.pangkatLama = option.getAttribute('data-pangkat') || '';
                        this.golonganLama = option.getAttribute('data-golongan') || '';
                    } else {
                        this.pangkatLama = '';
                        this.golonganLama = '';
                    }
                }
            }
        }
    </script>
</x-app-layout>
