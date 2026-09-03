<x-app-layout>
    <x-slot name="header">Buat Surat</x-slot>

    <div style="max-width:680px;">
        {{-- Breadcrumb --}}
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:20px; font-size:13px; color:#94a3b8;">
            <a href="{{ route('dashboard') }}" style="color:#64748b; text-decoration:none; font-weight:500;" onmouseover="this.style.color='#1d4ed8'" onmouseout="this.style.color='#64748b'">Dashboard</a>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            <a href="{{ route('buat-surat.create') }}" style="color:#64748b; text-decoration:none; font-weight:500;" onmouseover="this.style.color='#1d4ed8'" onmouseout="this.style.color='#64748b'">Buat Surat Baru</a>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            <a href="{{ route('buat-surat.pilih-template', $template->jenisSurat) }}" style="color:#64748b; text-decoration:none; font-weight:500;" onmouseover="this.style.color='#1d4ed8'" onmouseout="this.style.color='#64748b'">{{ $template->jenisSurat->nama }}</a>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            <span style="color:#1e293b; font-weight:600;">{{ $template->nama_template }}</span>
        </div>

        {{-- Step Indicator --}}
        <div style="display:flex; gap:0; margin-bottom:24px; background:white; border-radius:16px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            @foreach ([['1','Pilih Jenis Surat','Tentukan kategori surat'],['2','Pilih Template','Pilih format resmi'],['3','Isi Data & Generate','Lengkapi informasi']] as $i => $step)
                <div style="flex:1; padding:16px 20px; display:flex; align-items:center; gap:12px; {{ $i < 2 ? 'border-right:1px solid rgba(0,0,0,0.06);' : '' }}">
                    <div style="width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; flex-shrink:0;
                                background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white;">
                        {{ $step[0] }}
                    </div>
                    <div>
                        <p style="font-size:13px; font-weight:700; color:#1e293b; margin:0;">{{ $step[1] }}</p>
                        <p style="font-size:11px; color:#cbd5e1; margin:0;">{{ $step[2] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <div style="padding:18px 24px; border-bottom:1px solid rgba(0,0,0,0.05); background:linear-gradient(135deg,#f8fafc,#f0f9ff);">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:36px; height:36px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:15px; font-weight:700; color:#1e293b; margin:0;">{{ $template->jenisSurat->nama }} — {{ $template->nama_template }}</h3>
                        <p style="font-size:12px; color:#94a3b8; margin:0;">Isi semua field yang diperlukan</p>
                    </div>
                </div>
            </div>

            @if (session('error'))
                <div style="display:flex; align-items:flex-start; gap:10px; margin:16px 24px 0; padding:14px 18px; background:#fef2f2; border:1px solid #fecaca; border-radius:12px; color:#991b1b; font-size:13px; font-weight:500;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:1px"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('buat-surat.store') }}" method="POST" style="padding:24px; display:flex; flex-direction:column; gap:20px;">
                @csrf
                <input type="hidden" name="template_surat_id" value="{{ $template->id }}">

                {{-- Grid: Perihal + Tanggal --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label class="form-label" for="perihal">
                            Perihal <span style="color:#ef4444;">*</span>
                        </label>
                        <input id="perihal" type="text" name="perihal"
                               value="{{ old('perihal') }}"
                               class="form-control"
                               placeholder="Perihal surat...">
                        @error('perihal')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="tanggal_surat">
                            Tanggal Surat <span style="color:#ef4444;">*</span>
                        </label>
                        <input id="tanggal_surat" type="date" name="tanggal_surat"
                               value="{{ old('tanggal_surat', now()->toDateString()) }}"
                               class="form-control">
                        @error('tanggal_surat')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Grid: Klasifikasi + Tujuan --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label class="form-label">Klasifikasi <span style="font-weight:400; color:#94a3b8;">(opsional)</span></label>
                        <select name="klasifikasi_id" class="form-control">
                            <option value="">— Tanpa Klasifikasi —</option>
                            @foreach ($klasifikasiList as $k)
                                <option value="{{ $k->id }}" @selected(old('klasifikasi_id') == $k->id)>{{ $k->nama }} ({{ $k->kode }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="tujuan">Tujuan <span style="font-weight:400; color:#94a3b8;">(opsional)</span></label>
                        <input id="tujuan" type="text" name="tujuan"
                               value="{{ old('tujuan') }}"
                               class="form-control"
                               placeholder="Kepada Yth...">
                    </div>
                </div>

                {{-- Field dinamis sesuai placeholder_json milik template ini --}}
                @php $placeholders = is_array($template->placeholder_json) ? $template->placeholder_json : []; @endphp
                @if (count($placeholders) > 0)
                    <div style="padding:16px 20px; background:#f8fafc; border:1px solid rgba(0,0,0,0.06); border-radius:14px;">
                        <p style="font-size:13px; font-weight:700; color:#475569; margin:0 0 14px 0; display:flex; align-items:center; gap:6px;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            Data Placeholder Template
                        </p>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                            @foreach ($placeholders as $ph)
                                @php $label = str(str_replace('_', ' ', $ph))->title(); @endphp
                                <div style="display:flex; flex-direction:column; gap:6px;">
                                    <label style="font-size:13px; font-weight:600; color:#475569;">{{ $label }}</label>
                                    <input type="text" name="data[{{ $ph }}]"
                                           value="{{ old('data.' . $ph) }}"
                                           placeholder="Isi {{ strtolower($label) }}..."
                                           style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:13px; color:#334155; background:white; outline:none; transition:all 0.2s; box-shadow:0 1px 2px rgba(0,0,0,0.04);"
                                           onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.12)'"
                                           onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.04)'">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Submit --}}
                <div style="display:flex; align-items:center; gap:12px; padding-top:4px; border-top:1px solid rgba(0,0,0,0.05); margin-top:4px;">
                    <button type="submit"
                            style="display:inline-flex; align-items:center; gap:8px; padding:12px 24px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; font-size:14px; font-weight:600; border-radius:12px; border:none; cursor:pointer; box-shadow:0 2px 8px rgba(29,78,216,0.3); transition:all 0.2s;"
                            onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                        Preview &amp; Generate Surat
                    </button>
                    <a href="{{ route('buat-surat.pilih-template', $template->jenisSurat) }}"
                       style="display:inline-flex; align-items:center; gap:8px; padding:12px 22px; background:white; color:#64748b; font-size:14px; font-weight:600; border-radius:12px; border:1px solid rgba(0,0,0,0.1); text-decoration:none; transition:all 0.15s;"
                       onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                        &larr; Ganti Template
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>