<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>Buat Usulan Gaji Berkala</div>
            <a href="{{ route('kepegawaian.kgb.index') }}" style="display:inline-flex; align-items:center; gap:8px; padding:10px 18px; background:white; border:1px solid #cbd5e1; color:#334155; border-radius:12px; font-size:13px; font-weight:600; text-decoration:none;">
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

        <form action="{{ route('kepegawaian.kgb.store') }}" method="POST" enctype="multipart/form-data" x-data="kgbForm()">
            @csrf

            {{-- Informasi Pegawai --}}
            <h3 style="font-size:16px; font-weight:600; color:#1e293b; margin:0 0 16px 0; padding-bottom:8px; border-bottom:1px solid #e2e8f0;">
                1. Data Pegawai & Riwayat Gaji
            </h3>
            
            <div style="margin-bottom:24px;">
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:8px;">Pilih Pegawai <span style="color:#ef4444;">*</span></label>
                <select name="pegawai_id" x-model="selectedPegawaiId" @change="updateDataLama" required style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:14px; color:#1e293b; background:#f8fafc;">
                    <option value="">-- Pilih Pegawai --</option>
                    @foreach($pegawais as $pegawai)
                        <option value="{{ $pegawai->id }}">
                            {{ $pegawai->nama }} (NIP: {{ $pegawai->nip }})
                        </option>
                    @endforeach
                </select>
                <div x-show="hasRiwayat" style="display:none; margin-top:8px; font-size:12px; color:#059669; font-weight:500;">
                    ✓ Ditemukan riwayat KGB terakhir. Data gaji lama otomatis terisi.
                </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:32px;">
                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:8px;">Gaji Pokok Lama (Rp) <span style="color:#ef4444;">*</span></label>
                    <input type="number" name="gaji_pokok_lama" x-model="gajiPokokLama" :readonly="hasRiwayat" required min="0" style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:14px; color:#1e293b;" :style="hasRiwayat ? 'background:#f1f5f9; color:#64748b;' : 'background:#ffffff;'">
                </div>
                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:8px;">TMT Sebelumnya <span style="color:#ef4444;">*</span></label>
                    <input type="date" name="tmt_sebelumnya" x-model="tmtSebelumnya" :readonly="hasRiwayat" required style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:14px; color:#1e293b;" :style="hasRiwayat ? 'background:#f1f5f9; color:#64748b;' : 'background:#ffffff;'">
                </div>
            </div>

            {{-- Informasi Kenaikan KGB --}}
            <h3 style="font-size:16px; font-weight:600; color:#1e293b; margin:0 0 16px 0; padding-bottom:8px; border-bottom:1px solid #e2e8f0;">
                2. Detail Usulan Gaji Baru
            </h3>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:8px;">Gaji Pokok Baru (Rp) <span style="color:#ef4444;">*</span></label>
                    <input type="number" name="gaji_pokok_baru" value="{{ old('gaji_pokok_baru') }}" required min="0" placeholder="Contoh: 3500000" style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:14px; color:#1e293b;">
                </div>
                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:8px;">TMT Berikutnya (Otomatis)</label>
                    <input type="text" x-model="tmtBerikutnyaDisplay" readonly style="width:100%; padding:10px 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:14px; color:#64748b; background:#f1f5f9; cursor:not-allowed;" placeholder="Dihitung otomatis +24 bulan">
                    <small style="color:#94a3b8; font-size:11px; margin-top:4px; display:block;">Otomatis +24 bulan dari TMT Sebelumnya.</small>
                </div>
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
        const riwayatKgb = @json($riwayatKgb);

        function kgbForm() {
            return {
                selectedPegawaiId: '{{ old('pegawai_id') }}',
                gajiPokokLama: '{{ old('gaji_pokok_lama') }}',
                tmtSebelumnya: '{{ old('tmt_sebelumnya') }}',
                hasRiwayat: false,

                get tmtBerikutnyaDisplay() {
                    if (!this.tmtSebelumnya) return '';
                    let d = new Date(this.tmtSebelumnya);
                    if (isNaN(d.getTime())) return '';
                    d.setFullYear(d.getFullYear() + 2);
                    return d.toISOString().split('T')[0];
                },

                updateDataLama() {
                    if (this.selectedPegawaiId && riwayatKgb[this.selectedPegawaiId]) {
                        const histori = riwayatKgb[this.selectedPegawaiId];
                        this.gajiPokokLama = histori.gaji_pokok_baru;
                        // tmt_berikutnya dari histori menjadi tmt_sebelumnya saat ini
                        if (histori.tmt_berikutnya) {
                            this.tmtSebelumnya = histori.tmt_berikutnya.split('T')[0];
                        }
                        this.hasRiwayat = true;
                    } else {
                        this.gajiPokokLama = '';
                        this.tmtSebelumnya = '';
                        this.hasRiwayat = false;
                    }
                },

                init() {
                    if (this.selectedPegawaiId) {
                        this.updateDataLama();
                    }
                }
            }
        }
    </script>
</x-app-layout>
