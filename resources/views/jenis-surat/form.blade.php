<x-app-layout>
    <x-slot name="header">{{ $jenisSurat->exists ? 'Edit' : 'Tambah' }} Jenis Surat</x-slot>

    <div style="max-width:560px;">
        {{-- Breadcrumb --}}
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:20px; font-size:13px; color:#94a3b8;">
            <a href="{{ route('jenis-surat.index') }}" style="color:#64748b; text-decoration:none; font-weight:500; transition:color 0.15s;" onmouseover="this.style.color='#1d4ed8'" onmouseout="this.style.color='#64748b'">Jenis Surat</a>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            <span style="color:#1e293b; font-weight:600;">{{ $jenisSurat->exists ? 'Edit' : 'Tambah Baru' }}</span>
        </div>

        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <div style="padding:18px 24px; border-bottom:1px solid rgba(0,0,0,0.05); background:linear-gradient(135deg,#f8fafc,#f0f9ff);">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:36px; height:36px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:15px; font-weight:700; color:#1e293b; margin:0;">{{ $jenisSurat->exists ? 'Edit Jenis Surat' : 'Tambah Jenis Surat Baru' }}</h3>
                        <p style="font-size:12px; color:#94a3b8; margin:0;">Isi formulir di bawah ini dengan benar</p>
                    </div>
                </div>
            </div>

            <form action="{{ $jenisSurat->exists ? route('jenis-surat.update', $jenisSurat) : route('jenis-surat.store') }}"
                  method="POST" style="padding:24px; display:flex; flex-direction:column; gap:20px;">
                @csrf
                @if ($jenisSurat->exists) @method('PUT') @endif

                {{-- Nama --}}
                <div class="form-group">
                    <label class="form-label" for="nama">Nama Jenis Surat</label>
                    <input id="nama" type="text" name="nama"
                           value="{{ old('nama', $jenisSurat->nama) }}"
                           class="form-control"
                           placeholder="Contoh: Surat Keputusan">
                    @error('nama')
                        <p class="form-error">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Kode --}}
                <div class="form-group">
                    <label class="form-label" for="kode">Kode Surat</label>
                    <input id="kode" type="text" name="kode"
                           value="{{ old('kode', $jenisSurat->kode) }}"
                           class="form-control"
                           placeholder="Contoh: SK, SP, SE"
                           style="font-family:monospace; letter-spacing:1px; text-transform:uppercase;">
                    @error('kode')
                        <p class="form-error">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div style="display:flex; align-items:center; gap:12px; padding-top:4px; border-top:1px solid rgba(0,0,0,0.05); margin-top:4px;">
                    <button type="submit"
                            style="display:inline-flex; align-items:center; gap:8px; padding:11px 22px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; font-size:14px; font-weight:600; border-radius:12px; border:none; cursor:pointer; box-shadow:0 2px 8px rgba(29,78,216,0.3); transition:all 0.2s;"
                            onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 14px rgba(29,78,216,0.4)'"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(29,78,216,0.3)'">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Simpan
                    </button>
                    <a href="{{ route('jenis-surat.index') }}"
                       style="display:inline-flex; align-items:center; gap:8px; padding:11px 22px; background:white; color:#64748b; font-size:14px; font-weight:600; border-radius:12px; border:1px solid rgba(0,0,0,0.1); text-decoration:none; transition:all 0.15s;"
                       onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
