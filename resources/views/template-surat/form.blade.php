<x-app-layout>
    <x-slot name="header">{{ $template->exists ? 'Edit' : 'Tambah' }} Template Surat</x-slot>

    <div style="max-width:640px;">
        {{-- Breadcrumb --}}
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:20px; font-size:13px; color:#94a3b8;">
            <a href="{{ route('template-surat.index') }}" style="color:#64748b; text-decoration:none; font-weight:500;" onmouseover="this.style.color='#1d4ed8'" onmouseout="this.style.color='#64748b'">Template Surat</a>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            <span style="color:#1e293b; font-weight:600;">{{ $template->exists ? 'Edit' : 'Tambah Baru' }}</span>
        </div>

        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <div style="padding:18px 24px; border-bottom:1px solid rgba(0,0,0,0.05); background:linear-gradient(135deg,#f8fafc,#f0f9ff);">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:36px; height:36px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:15px; font-weight:700; color:#1e293b; margin:0;">{{ $template->exists ? 'Edit Template' : 'Template Baru' }}</h3>
                        <p style="font-size:12px; color:#94a3b8; margin:0;">Upload file .docx sebagai template surat</p>
                    </div>
                </div>
            </div>

            <form action="{{ $template->exists ? route('template-surat.update', $template) : route('template-surat.store') }}"
                  method="POST" enctype="multipart/form-data"
                  style="padding:24px; display:flex; flex-direction:column; gap:20px;">
                @csrf
                @if ($template->exists) @method('PUT') @endif

                {{-- Jenis Surat --}}
                <div class="form-group">
                    <label class="form-label">Jenis Surat</label>
                    <select name="jenis_surat_id" class="form-control">
                        @foreach ($jenisSuratList as $jenis)
                            <option value="{{ $jenis->id }}" @selected(old('jenis_surat_id', $template->jenis_surat_id) == $jenis->id)>
                                {{ $jenis->nama }} ({{ $jenis->kode }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nama Template --}}
                <div class="form-group">
                    <label class="form-label" for="nama_template">Nama Template</label>
                    <input id="nama_template" type="text" name="nama_template"
                           value="{{ old('nama_template', $template->nama_template) }}"
                           class="form-control"
                           placeholder="Contoh: Surat Keputusan Pengangkatan Pegawai">
                    @error('nama_template')
                        <p class="form-error">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- File Upload --}}
                <div class="form-group">
                    <label class="form-label">File Template (.docx)</label>
                    <div style="border:2px dashed #e2e8f0; border-radius:14px; padding:24px; text-align:center; cursor:pointer; transition:all 0.2s; background:#fafbfc;"
                         onmouseover="this.style.borderColor='#3b82f6'; this.style.background='#f0f9ff'"
                         onmouseout="this.style.borderColor='#e2e8f0'; this.style.background='#fafbfc'">
                        <input type="file" name="file_template" id="file_template"
                               style="position:absolute; opacity:0; width:100%; height:100%; cursor:pointer; top:0; left:0;"
                               onchange="document.getElementById('file-name').textContent = this.files[0]?.name || 'Belum ada file dipilih'">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 10px; display:block;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        <p style="font-size:14px; font-weight:600; color:#475569; margin:0 0 4px 0;">Klik atau drag file ke sini</p>
                        <p id="file-name" style="font-size:12px; color:#94a3b8; margin:0;">
                            @if ($template->exists) File lama tetap digunakan jika kosong @else Belum ada file dipilih @endif
                        </p>
                    </div>
                    @error('file_template')
                        <p class="form-error">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

{{-- Placeholder --}}
<div class="form-group">
    <label class="form-label">
        Placeholder Template
    </label>

    <input
        type="text"
        name="placeholder"
        value="{{ old('placeholder', implode(', ', $template->placeholder_json ?? [])) }}"
        class="form-control"
        placeholder="nama_pegawai, nip, jabatan, keperluan"
    >

    <div style="
        margin-top:10px;
        padding:12px 14px;
        background:#eff6ff;
        border:1px solid #bfdbfe;
        border-radius:10px;
        font-size:12px;
        color:#475569;
        line-height:1.6;
    ">
        <strong style="color:#1d4ed8;">
            Cara menggunakan placeholder
        </strong>

        <div style="margin-top:5px;">
            Di file Word gunakan format:
        </div>

        <code style="
            display:inline-block;
            margin-top:5px;
            padding:4px 8px;
            background:white;
            border-radius:6px;
            color:#334155;
        ">
            ${nama_pegawai}
        </code>

        <code style="
            display:inline-block;
            margin-top:5px;
            padding:4px 8px;
            background:white;
            border-radius:6px;
            color:#334155;
        ">
            ${nip}
        </code>

        <code style="
            display:inline-block;
            margin-top:5px;
            padding:4px 8px;
            background:white;
            border-radius:6px;
            color:#334155;
        ">
            ${jabatan}
        </code>

        <div style="margin-top:6px;">
            Kemudian masukkan nama placeholder tanpa
            <code>${...}</code> di kolom ini, dipisahkan dengan koma.
        </div>

        <div style="
            margin-top:8px;
            padding:8px 10px;
            background:white;
            border-radius:8px;
        ">
            <strong>Contoh:</strong><br>
            nama_pegawai, nip, jabatan, keperluan, tempat_tujuan
        </div>
    </div>

    @error('placeholder')
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>

                {{-- Status (edit only) --}}
                @if ($template->exists)
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="aktif" @selected($template->status === 'aktif')>Aktif</option>
                            <option value="nonaktif" @selected($template->status === 'nonaktif')>Nonaktif</option>
                        </select>
                    </div>
                @endif

                {{-- Actions --}}
                <div style="display:flex; align-items:center; gap:12px; padding-top:4px; border-top:1px solid rgba(0,0,0,0.05); margin-top:4px;">
                    <button type="submit"
                            style="display:inline-flex; align-items:center; gap:8px; padding:11px 22px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; font-size:14px; font-weight:600; border-radius:12px; border:none; cursor:pointer; box-shadow:0 2px 8px rgba(29,78,216,0.3); transition:all 0.2s;"
                            onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Simpan
                    </button>
                    <a href="{{ route('template-surat.index') }}"
                       style="display:inline-flex; align-items:center; gap:8px; padding:11px 22px; background:white; color:#64748b; font-size:14px; font-weight:600; border-radius:12px; border:1px solid rgba(0,0,0,0.1); text-decoration:none; transition:all 0.15s;"
                       onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>