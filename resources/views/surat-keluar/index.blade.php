<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; gap:12px;">
            <div style="width:40px; height:40px; background:#fff7ed; border:1px solid #fed7aa; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#c2410c; flex-shrink:0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            </div>
            <div>
                <div style="font-size:17px; font-weight:600; color:var(--text-primary,#1e293b); line-height:1.2;">Daftar Surat Keluar</div>
                <div style="margin-top:2px; font-size:12.5px; color:var(--text-muted,#64748b);">Kelola dan pantau seluruh surat keluar</div>
            </div>
        </div>
    </x-slot>

    @php
        // Build structured units for searchable dropdown
        $skStructuredUnits = [];
        foreach ($bidangs as $bidang) {
            $seksis = [];
            foreach ($bidang->children as $seksi) {
                $seksis[] = ['id' => $seksi->id, 'nama' => $seksi->nama, 'bidang_id' => $bidang->id];
            }
            $skStructuredUnits[] = ['id' => $bidang->id, 'nama' => $bidang->nama, 'seksis' => $seksis];
        }

        // Build suratMap for Alpine.js
        $suratMap = [];
        foreach ($suratKeluar as $item) {
            $bestFile = $item->file_pdf ?? $item->file_word ?? $item->file_dokumen;
            $suratMap[$item->id] = [
                'id'                    => $item->id,
                'jenis_surat_id'        => $item->jenis_surat_id,
                'jenis_surat_nama'      => $item->jenisSurat?->nama,
                'jenis_surat_kode'      => $item->jenisSurat?->kode,
                'klasifikasi_id'        => $item->klasifikasi_id,
                'klasifikasi_nama'      => $item->klasifikasi?->nama,
                'klasifikasi_kode'      => $item->klasifikasi?->kode,
                'unit_kerja_id'         => $item->unit_kerja_id,
                'unit_kerja_nama'       => $item->unitKerja?->nama,
                'induk_unit_kerja_nama' => $item->unitKerja?->parent?->nama,
                'bidang_id'             => $item->unitKerja?->parent_id,
                'nomor_surat'           => $item->nomor_surat,
                'perihal'               => $item->perihal,
                'tujuan'                => $item->tujuan,
                'tanggal_surat'         => $item->tanggal_surat?->format('Y-m-d'),
                'tanggal_surat_formatted' => $item->tanggal_surat?->translatedFormat('d F Y'),
                'status'                => $item->status,
                'is_final'              => $item->status === 'final',
                'has_template'          => (bool) $item->template_surat_id,
                'file_dokumen_basename' => $item->file_dokumen ? basename($item->file_dokumen) : null,
                'file_word_basename'    => $item->file_word ? basename($item->file_word) : null,
                'file_pdf_basename'     => $item->file_pdf ? basename($item->file_pdf) : null,
                'best_file_basename'    => $bestFile ? basename($bestFile) : null,
                'download_url'          => $bestFile ? route('surat-keluar.download', $item) : null,
                'creator_name'          => $item->creator?->name,
                'created_at_formatted'  => $item->created_at?->translatedFormat('d F Y, H:i'),
                'show_url'              => route('surat-keluar.show', $item),
                'edit_url'              => route('surat-keluar.edit', $item),
                'destroy_url'           => route('surat-keluar.destroy', $item),
            ];
        }
    @endphp

    <div x-data="suratKeluarModalHandler({
        initialData: {{ json_encode($suratMap) }},
        structuredUnits: {{ json_encode($skStructuredUnits) }},
        jenisSuratList: {{ json_encode($jenisSuratList->map(fn($j) => ['id' => $j->id, 'nama' => $j->nama])->values()) }},
        klasifikasiList: {{ json_encode($klasifikasiList->map(fn($k) => ['id' => $k->id, 'nama' => $k->nama, 'kode' => $k->kode])->values()) }},
        csrfToken: '{{ csrf_token() }}',
        baseUrl: '{{ url('/surat-keluar') }}'
    })"
    style="display:flex; flex-direction:column; gap:20px;">

        {{-- ── Flash messages ── --}}
        @if (session('status'))
            <div style="display:flex; align-items:center; gap:10px; padding:14px 18px; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:14px; color:#065f46; font-size:14px; font-weight:500;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div style="display:flex; align-items:center; gap:10px; padding:14px 18px; background:#fef2f2; border:1px solid #fecaca; border-radius:14px; color:#991b1b; font-size:14px; font-weight:500;">
                {{ session('error') }}
            </div>
        @endif

        {{-- ── Toolbar ── --}}
        <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
            <form method="GET" style="flex:1; min-width:240px; display:flex; align-items:center; gap:10px; background:var(--card-bg,white); border:1px solid rgba(0,0,0,0.07); border-radius:14px; padding:10px 16px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nomor, perihal, tujuan..."
                       style="flex:1; border:none; outline:none; font-size:14px; color:#334155; background:transparent;">
                @if(request()->hasAny(['q','jenis_surat_id','status','dari','sampai']))
                    <a href="{{ route('surat-keluar.index') }}" style="font-size:11px; color:#94a3b8; text-decoration:none; white-space:nowrap;">Hapus filter</a>
                @endif
            </form>
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <a href="{{ route('surat-keluar.draft') }}" style="text-decoration:none; display:inline-flex; align-items:center; gap:6px; padding:10px 16px; background:var(--card-bg,white); color:#64748b; border:1px solid #e2e8f0; border-radius:12px; font-size:13px; font-weight:600; white-space:nowrap;">
                    Draft
                </a>
                <a href="{{ route('surat-keluar.create') }}"
                   @click.prevent="openCreate()"
                   style="text-decoration:none; display:inline-flex; align-items:center; gap:8px; padding:10px 18px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; border-radius:12px; font-size:13px; font-weight:600; white-space:nowrap; box-shadow:0 2px 8px rgba(29,78,216,0.3); cursor:pointer;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Input Surat Keluar
                </a>
            </div>
        </div>

        {{-- ── Filter ── --}}
        <form method="GET" style="display:flex; gap:10px; flex-wrap:wrap; align-items:flex-end;">
            @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
            <div>
                <label style="display:block; font-size:11px; font-weight:600; color:#64748b; margin-bottom:4px;">Jenis Surat</label>
                <select name="jenis_surat_id" style="padding:8px 12px; border:1px solid #e2e8f0; border-radius:10px; font-size:13px; color:#334155;" onchange="this.form.submit()">
                    <option value="">Semua Jenis</option>
                    @foreach($jenisSuratList as $js)
                        <option value="{{ $js->id }}" @selected(request('jenis_surat_id') == $js->id)>{{ $js->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:600; color:#64748b; margin-bottom:4px;">Status</label>
                <select name="status" style="padding:8px 12px; border:1px solid #e2e8f0; border-radius:10px; font-size:13px; color:#334155;" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="final" @selected(request('status')=='final')>Final</option>
                    <option value="draft" @selected(request('status')=='draft')>Draft</option>
                </select>
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:600; color:#64748b; margin-bottom:4px;">Dari</label>
                <input type="date" name="dari" value="{{ request('dari') }}" style="padding:8px 12px; border:1px solid #e2e8f0; border-radius:10px; font-size:13px;" onchange="this.form.submit()">
            </div>
            <div>
                <label style="display:block; font-size:11px; font-weight:600; color:#64748b; margin-bottom:4px;">Sampai</label>
                <input type="date" name="sampai" value="{{ request('sampai') }}" style="padding:8px 12px; border:1px solid #e2e8f0; border-radius:10px; font-size:13px;" onchange="this.form.submit()">
            </div>
        </form>

        {{-- ── Table ── --}}
        @php
            // Kelompokkan baris berdasarkan tanggal input (created_at) memakai zona waktu aplikasi.
            $skGroups = \App\Helpers\DateGroupHelper::groupByInputDate($suratKeluar);
        @endphp

        {{-- Template header kelompok "Hari Ini" untuk baris yang disisipkan lewat AJAX --}}
        <template id="sk-today-group-template">
            <x-date-group-row :group="\App\Helpers\DateGroupHelper::todayGroup()" :colspan="8" />
        </template>

        <div style="background:var(--card-bg,white); border-radius:20px; border:1px solid var(--border,rgba(0,0,0,0.06)); overflow:hidden;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px;">No.</th>
                        <th>Nomor Surat</th>
                        <th>Perihal</th>
                        <th>Tujuan</th>
                        <th>Jenis</th>
                        <th>Tgl. Surat</th>
                        <th>Status</th>
                        <th style="width:100px;"></th>
                    </tr>
                </thead>
                <tbody id="sk-table-body">
                    @forelse ($skGroups as $group)
                        <x-date-group-row :group="$group" :colspan="8" :partial="$suratKeluar->hasPages()" />
                        @foreach ($group['items'] as $row)
                            @php $item = $row['surat']; @endphp
                            <tr data-surat-row id="sk-row-{{ $item->id }}">
                            <td style="color:#94a3b8; font-size:13px;">{{ $row['no'] }}.</td>
                            <td style="font-family:monospace; font-size:12px; color:#334155;">{{ $item->nomor_surat ?? '-' }}</td>
                            <td style="font-weight:500; color:var(--text-primary,#1e293b); max-width:220px;">
                                <a href="{{ route('surat-keluar.show', $item) }}"
                                   @click.prevent="openDetail({{ $item->id }})"
                                   style="color:var(--text-primary,#1e293b); text-decoration:none; cursor:pointer;">
                                    {{ Str::limit($item->perihal, 60) }}
                                </a>
                            </td>
                            <td style="color:#475569;">{{ $item->tujuan ?? '-' }}</td>
                            <td>
                                <span style="display:inline-flex; padding:4px 10px; background:#fffbeb; color:#d97706; font-size:11px; font-weight:600; border-radius:8px;">
                                    {{ $item->jenisSurat?->kode ?? '-' }}
                                </span>
                            </td>
                            <td style="color:#64748b; font-size:13px;">{{ $item->tanggal_surat?->format('d M Y') }}</td>
                            <td>
                                @php
                                    $sc = $item->status === 'final' ? ['#ecfdf5','#065f46'] : ['#f1f5f9','#475569'];
                                @endphp
                                <span style="display:inline-flex; padding:4px 10px; background:{{ $sc[0] }}; color:{{ $sc[1] }}; font-size:11px; font-weight:600; border-radius:8px; text-transform:capitalize;">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td style="text-align:right; padding-right:16px;">
                                <x-action-group>
                                    <x-action-btn type="view" url="{{ route('surat-keluar.show', $item) }}" @click.prevent="openDetail({{ $item->id }})" />
                                    @if($item->status === 'draft')
                                        <x-action-btn type="edit" url="{{ route('surat-keluar.edit', $item) }}" @click.prevent="openEdit({{ $item->id }})" />
                                    @endif
                                </x-action-group>
                            </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr id="sk-empty-row">
                            <td colspan="8" style="text-align:center; padding:48px 24px; color:#94a3b8;">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 12px;"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                                <p style="margin:0; font-size:14px; font-weight:500;">Belum ada surat keluar</p>
                                <p style="margin:4px 0 0; font-size:13px; color:#cbd5e1;">@if(request()->hasAny(['q','jenis_surat_id','status','dari','sampai']))Tidak ada surat yang cocok dengan filter.@else Mulai catat surat keluar baru.@endif</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($suratKeluar->hasPages())
                <div style="padding:14px 24px; border-top:1px solid rgba(0,0,0,0.05);">
                    {{ $suratKeluar->links() }}
                </div>
            @endif
        </div>

        <p style="font-size:12px; color:#94a3b8; margin:0;">
            Menampilkan {{ $suratKeluar->count() }} dari {{ $suratKeluar->total() }} surat keluar.
        </p>

        {{-- ══════════════════════════════════════════════════════
           MODAL TAMBAH SURAT KELUAR
        ══════════════════════════════════════════════════════ --}}
        <div x-show="createModalOpen"
             x-transition.opacity.duration.200ms
             class="sipas-modal-overlay"
             @click.self="closeCreate()"
             @keydown.escape.window="closeCreate()"
             style="display:none;"
             role="dialog" aria-modal="true" aria-labelledby="sk-modal-create-title">

            <div class="sipas-modal-dialog" style="max-width:720px;" @click.stop>
                <div class="sipas-modal-header">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:34px; height:34px; background:rgba(29,78,216,0.1); border-radius:8px; display:flex; align-items:center; justify-content:center; color:#1d4ed8; flex-shrink:0;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        </div>
                        <div>
                            <h3 id="sk-modal-create-title" style="font-size:15px; font-weight:700; color:var(--text-primary,#1e293b); margin:0; line-height:1.2;">Input Surat Keluar</h3>
                            <p style="font-size:12px; color:var(--text-muted,#64748b); margin:2px 0 0 0;">Isi formulir di bawah ini dengan lengkap dan benar</p>
                        </div>
                    </div>
                    <button type="button" @click="closeCreate()" class="sipas-modal-close" aria-label="Tutup">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>

                <form id="sk-create-form" @submit.prevent="submitCreate()" enctype="multipart/form-data" style="display:flex; flex-direction:column; flex:1; overflow:hidden;">
                    <div class="sipas-modal-body">

                        <div x-show="createGeneralError" style="margin-bottom:16px; padding:12px 16px; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; color:#991b1b; font-size:13px; display:flex; align-items:center; gap:8px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span x-text="createGeneralError"></span>
                        </div>

                        <div style="display:flex; flex-direction:column; gap:16px;">

                            {{-- Jenis + Klasifikasi --}}
                            <div class="sipas-form-row">
                                <div class="form-group" style="flex:1;">
                                    <label class="form-label" for="ck_jenis_surat_id">Jenis Surat <span style="color:#ef4444;">*</span></label>
                                    <div class="sk-input-wrap">
                                        <svg class="sk-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                        <select id="ck_jenis_surat_id" x-model="createForm.jenis_surat_id" class="form-control"
                                                :class="{'sipas-input-error': createErrors.jenis_surat_id}">
                                            <option value="">-- Pilih Jenis Surat --</option>
                                            <template x-for="j in jenisSuratList" :key="j.id">
                                                <option :value="j.id" x-text="j.nama"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <template x-if="createErrors.jenis_surat_id">
                                        <p class="form-error" x-text="createErrors.jenis_surat_id[0]"></p>
                                    </template>
                                </div>
                                <div class="form-group" style="flex:1;">
                                    <label class="form-label" for="ck_klasifikasi_id">Klasifikasi <span style="font-weight:400; color:#94a3b8;">(opsional)</span></label>
                                    <div class="sk-input-wrap">
                                        <svg class="sk-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.83z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                                        <select id="ck_klasifikasi_id" x-model="createForm.klasifikasi_id" class="form-control">
                                            <option value="">-- Tanpa Klasifikasi --</option>
                                            <template x-for="k in klasifikasiList" :key="k.id">
                                                <option :value="k.id" x-text="k.kode + ' - ' + k.nama"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Unit Kerja Searchable --}}
                            <div class="form-group"
                                 x-data="{
                                     open: false, search: '', direction: 'down', maxListHeight: 200,
                                     get filteredData() {
                                         if (!this.search) return structuredUnits;
                                         const q = this.search.toLowerCase();
                                         return structuredUnits.map(b => ({...b, seksis: b.seksis.filter(s => s.nama.toLowerCase().includes(q))})).filter(b => b.seksis.length > 0);
                                     },
                                     toggle(el) {
                                         if (this.open) { this.open = false; return; }
                                         const rect = el.getBoundingClientRect();
                                         const below = window.innerHeight - rect.bottom;
                                         this.direction = (below >= 220) ? 'down' : 'up';
                                         this.maxListHeight = Math.max(80, Math.min(200, below >= 220 ? below - 80 : rect.top - 80));
                                         this.open = true;
                                     },
                                     select(id, bidangId, nama) { createForm.unit_kerja_id = id; createForm.bidang_id = bidangId; createForm.unit_kerja_label = nama; this.open = false; this.search = ''; },
                                     clear() { createForm.unit_kerja_id = ''; createForm.bidang_id = ''; createForm.unit_kerja_label = ''; this.open = false; }
                                 }">
                                <label class="form-label">Unit Kerja Asal (Seksi) <span style="color:#ef4444;">*</span></label>
                                <div style="position:relative;">
                                    <button type="button" @click="toggle($el)" @click.outside="open = false"
                                            class="form-control" :class="{'sipas-input-error': createErrors.unit_kerja_id || createErrors.bidang_id}"
                                            style="display:flex; justify-content:space-between; align-items:center; gap:8px; width:100%; text-align:left; cursor:pointer;">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; color:var(--text-muted,#94a3b8);"><rect x="9" y="2" width="6" height="6" rx="1"/><rect x="2" y="16" width="6" height="6" rx="1"/><rect x="16" y="16" width="6" height="6" rx="1"/><path d="M5 16v-3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3"/><line x1="12" y1="12" x2="12" y2="8"/></svg>
                                        <span x-text="createForm.unit_kerja_label || '-- Pilih Seksi/Unit Kerja --'"
                                              :style="!createForm.unit_kerja_label ? 'color:var(--text-muted,#94a3b8); flex:1;' : 'color:var(--text-primary,#1e293b); flex:1;'"></span>
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><polyline points="6 9 12 15 18 9"/></svg>
                                    </button>
                                    <div x-show="open" x-transition.opacity
                                         :style="direction==='up'
                                             ? 'display:flex; flex-direction:column-reverse; position:absolute; z-index:60; width:100%; bottom:calc(100% + 4px); background:var(--card-bg,#fff); border:1px solid var(--border,#e2e8f0); border-radius:8px; box-shadow:0 10px 25px -5px rgba(0,0,0,0.15); overflow:hidden;'
                                             : 'display:flex; flex-direction:column; position:absolute; z-index:60; width:100%; top:calc(100% + 4px); background:var(--card-bg,#fff); border:1px solid var(--border,#e2e8f0); border-radius:8px; box-shadow:0 10px 25px -5px rgba(0,0,0,0.15); overflow:hidden;'"
                                         style="display:none;">
                                        <div style="padding:8px 10px; border-bottom:1px solid var(--border,#e2e8f0); background:var(--page-bg,#f8fafc); flex-shrink:0;">
                                            <div style="position:relative;">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute; left:10px; top:9px; color:#94a3b8;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                                <input type="text" x-model="search" placeholder="Cari unit kerja..." class="form-control" style="padding:6px 10px 6px 30px; font-size:12.5px;" @click.stop>
                                            </div>
                                        </div>
                                        <div :style="'overflow-y:auto; max-height:' + maxListHeight + 'px; padding:4px 0; background:var(--card-bg,#fff);'">
                                            <template x-if="filteredData.length === 0">
                                                <div style="padding:12px; font-size:13px; color:#64748b; text-align:center;">Unit kerja tidak ditemukan.</div>
                                            </template>
                                            <template x-for="bidang in filteredData" :key="bidang.id">
                                                <div>
                                                    <div x-text="bidang.nama" style="padding:6px 12px; font-size:11px; font-weight:700; color:#64748b; background:var(--page-bg,#f8fafc); text-transform:uppercase; letter-spacing:.5px; border-bottom:1px solid var(--border-light,#f1f5f9);"></div>
                                                    <template x-for="seksi in bidang.seksis" :key="seksi.id">
                                                        <div @click="select(seksi.id, seksi.bidang_id, seksi.nama)"
                                                             style="padding:8px 12px 8px 20px; font-size:13px; color:var(--text-primary,#334155); cursor:pointer; display:flex; align-items:center; gap:6px;"
                                                             class="sipas-dropdown-row">
                                                            <span x-text="seksi.nama" style="flex:1;"></span>
                                                            <svg x-show="createForm.unit_kerja_id == seksi.id" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                        <div style="padding:6px 12px; border-top:1px solid var(--border,#e2e8f0); background:var(--page-bg,#f8fafc); flex-shrink:0;">
                                            <button type="button" @click.stop="clear()" :disabled="!createForm.unit_kerja_id"
                                                    :style="!createForm.unit_kerja_id ? 'opacity:.4; cursor:not-allowed;' : 'cursor:pointer;'"
                                                    style="display:flex; align-items:center; gap:5px; color:#ef4444; font-size:12px; font-weight:600; background:none; border:none; padding:2px 0; outline:none;">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                                Hapus Pilihan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <template x-if="createErrors.unit_kerja_id">
                                    <p class="form-error" x-text="createErrors.unit_kerja_id[0]"></p>
                                </template>
                                <template x-if="createErrors.bidang_id">
                                    <p class="form-error" x-text="createErrors.bidang_id[0]"></p>
                                </template>
                            </div>

                            {{-- Nomor Surat --}}
                            <div class="form-group">
                                <label class="form-label" for="ck_nomor_surat">Nomor Surat <span style="font-weight:400; color:#94a3b8;">(opsional, otomatis jika kosong &amp; status Final)</span></label>
                                <div class="sk-input-wrap">
                                    <svg class="sk-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="9" x2="20" y2="9"/><line x1="4" y1="15" x2="20" y2="15"/><line x1="10" y1="3" x2="8" y2="21"/><line x1="16" y1="3" x2="14" y2="21"/></svg>
                                    <input id="ck_nomor_surat" type="text" x-model="createForm.nomor_surat" class="form-control" placeholder="Contoh: 001/DISHUB/10/2026">
                                </div>
                            </div>

                            {{-- Perihal --}}
                            <div class="form-group">
                                <label class="form-label" for="ck_perihal">Perihal <span style="color:#ef4444;">*</span></label>
                                <div class="sk-input-wrap">
                                    <svg class="sk-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/></svg>
                                    <input id="ck_perihal" type="text" x-model="createForm.perihal" class="form-control"
                                           :class="{'sipas-input-error': createErrors.perihal}" placeholder="Perihal surat...">
                                </div>
                                <template x-if="createErrors.perihal">
                                    <p class="form-error" x-text="createErrors.perihal[0]"></p>
                                </template>
                            </div>

                            {{-- Tujuan --}}
                            <div class="form-group">
                                <label class="form-label" for="ck_tujuan">Tujuan</label>
                                <div class="sk-input-wrap">
                                    <svg class="sk-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                                    <input id="ck_tujuan" type="text" x-model="createForm.tujuan" class="form-control" placeholder="Nama instansi / penerima">
                                </div>
                            </div>

                            {{-- Tanggal + Status --}}
                            <div class="sipas-form-row">
                                <div class="form-group" style="flex:1;">
                                    <label class="form-label" for="ck_tanggal_surat">Tanggal Surat <span style="color:#ef4444;">*</span></label>
                                    <div class="sk-input-wrap">
                                        <svg class="sk-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                        <input id="ck_tanggal_surat" type="date" x-model="createForm.tanggal_surat" class="form-control"
                                               :class="{'sipas-input-error': createErrors.tanggal_surat}">
                                    </div>
                                    <template x-if="createErrors.tanggal_surat">
                                        <p class="form-error" x-text="createErrors.tanggal_surat[0]"></p>
                                    </template>
                                </div>
                                <div class="form-group" style="flex:1;">
                                    <label class="form-label" for="ck_status">Status</label>
                                    <div class="sk-input-wrap">
                                        <svg class="sk-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                                        <select id="ck_status" x-model="createForm.status" class="form-control">
                                            <option value="final">Final (nomor otomatis jika kosong)</option>
                                            <option value="draft">Draft (simpan tanpa nomor)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Upload Dokumen --}}
                            <div class="form-group">
                                <label class="form-label" for="ck_file_dokumen">Upload Dokumen <span style="font-weight:400; color:#94a3b8;">(opsional)</span></label>

                                <div class="sk-file-card" x-show="!createFile.name">
                                    <input id="ck_file_dokumen" type="file" name="file_dokumen" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                           class="sk-file-input" x-ref="createFileInput" @change="onCreateFile($event)">
                                    <label for="ck_file_dokumen" class="sk-file-drop">
                                        <span class="sk-file-pdf">
                                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                        </span>
                                        <span class="sk-file-title">Unggah Dokumen Surat</span>
                                        <span class="sk-file-hint">Format: PDF, JPG, PNG, DOC, DOCX &middot; Maks 10MB</span>
                                        <span class="sk-file-btn">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                            Pilih File
                                        </span>
                                    </label>
                                </div>

                                <div class="sk-file-selected" x-show="createFile.name" style="display:none;">
                                    <div class="sk-file-icon">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                        <span class="sk-file-ext" x-text="createFile.ext"></span>
                                    </div>
                                    <div class="sk-file-meta">
                                        <span class="sk-file-name" x-text="createFile.name"></span>
                                        <span class="sk-file-size" x-text="createFile.sizeLabel"></span>
                                        <span class="sk-file-status">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                            File siap diunggah
                                        </span>
                                    </div>
                                    <div class="sk-file-actions">
                                        <button type="button" @click="openCreateFilePicker()">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10"/><path d="M20.49 15a9 9 0 0 1-14.85 3.36L1 14"/></svg>
                                            Ganti
                                        </button>
                                        <button type="button" class="sk-file-cancel" @click="clearCreateFile()">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                            Batal
                                        </button>
                                    </div>
                                </div>

                                <template x-if="createErrors.file_dokumen">
                                    <p class="form-error" x-text="createErrors.file_dokumen[0]"></p>
                                </template>
                            </div>

                            {{-- Info: Template --}}
                            <div style="padding:12px 14px; background:#f5f3ff; border:1px solid #ddd6fe; border-radius:8px; display:flex; gap:10px; align-items:flex-start;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                <div style="font-size:12.5px; color:#5b21b6; line-height:1.5;">
                                    <strong>Generate dari Template Word/PDF?</strong> Gunakan halaman
                                    <a href="{{ route('surat-keluar.create') }}" target="_blank" style="color:#7c3aed; font-weight:600;">Buat Surat</a>
                                    yang dilengkapi fitur pemilihan template dan generate dokumen otomatis.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sipas-modal-footer">
                        <button type="button" @click="closeCreate()" class="sipas-btn-secondary" :disabled="savingCreate">Batal</button>
                        <button type="submit" class="sipas-btn-primary" :disabled="savingCreate">
                            <svg x-show="savingCreate" class="sipas-spin" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10" stroke-opacity=".25"/><path d="M12 2a10 10 0 0 1 10 10"/></svg>
                            <svg x-show="!savingCreate" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            <span x-text="savingCreate ? 'Menyimpan...' : 'Simpan Surat Keluar'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════
           MODAL DETAIL SURAT KELUAR
        ══════════════════════════════════════════════════════ --}}
        <div x-show="detailModalOpen"
             x-transition.opacity.duration.200ms
             class="sipas-modal-overlay"
             @click.self="closeDetail()"
             @keydown.escape.window="closeDetail()"
             style="display:none;"
             role="dialog" aria-modal="true" aria-labelledby="sk-modal-detail-title">

            <div class="sipas-modal-dialog sk-detail-dialog" style="max-width:700px;" @click.stop>
                <div class="sipas-modal-header">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div class="sk-detail-head-ic">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.2 8.4c.5.38.8.97.8 1.6v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V10a2 2 0 0 1 .8-1.6l8-6a2 2 0 0 1 2.4 0l8 6Z"/><path d="m22 10-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 10"/></svg>
                        </div>
                        <div>
                            <h3 id="sk-modal-detail-title" style="font-size:15px; font-weight:700; color:var(--text-primary,#1e293b); margin:0; line-height:1.2;">Detail Surat Keluar</h3>
                            <p style="font-size:12px; color:var(--text-muted,#64748b); margin:2px 0 0 0;">Informasi lengkap surat keluar yang tercatat</p>
                        </div>
                    </div>
                    <button type="button" @click="closeDetail()" class="sipas-modal-close" aria-label="Tutup">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>

                <div class="sipas-modal-body" x-show="activeSurat">
                    <template x-if="activeSurat">
                        <div>
                            {{-- Badge baris atas --}}
                            <div style="display:flex; align-items:center; gap:8px; margin-bottom:16px; flex-wrap:wrap;">
                                <span style="display:inline-flex; padding:4px 12px; background:#fffbeb; color:#d97706; font-size:11px; font-weight:700; border-radius:6px; letter-spacing:.3px;" x-text="activeSurat.jenis_surat_kode || 'SURAT'"></span>
                                <span style="display:inline-flex; padding:4px 12px; font-size:11px; font-weight:700; border-radius:6px; text-transform:capitalize;"
                                      :style="activeSurat.is_final ? 'background:#ecfdf5; color:#065f46;' : 'background:#f1f5f9; color:#475569;'"
                                      x-text="activeSurat.status"></span>
                                <template x-if="activeSurat.has_template">
                                    <span style="display:inline-flex; padding:4px 12px; background:#f5f3ff; color:#7c3aed; font-size:11px; font-weight:700; border-radius:6px;">Dari Template</span>
                                </template>
                                <span style="font-size:13px; color:var(--text-muted,#64748b); font-family:monospace;" x-text="activeSurat.nomor_surat || '-'"></span>
                            </div>

                            {{-- ── Informasi Surat ── --}}
                            <div class="sk-section">
                                <div class="sk-section-head">
                                    <span class="sk-section-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></span>
                                    <span class="sk-section-title">Informasi Surat</span>
                                </div>

                                <div class="sk-perihal" style="margin-bottom:10px;">
                                    <span class="sk-meta-ic" style="margin-top:3px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/></svg></span>
                                    <div class="sk-meta-body">
                                        <span class="sk-meta-label">Perihal</span>
                                        <p class="sk-perihal-text" x-text="activeSurat.perihal || '-'"></p>
                                    </div>
                                </div>

                                <div class="sk-meta-grid">
                                    <div class="sk-meta-card">
                                        <span class="sk-meta-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="9" x2="20" y2="9"/><line x1="4" y1="15" x2="20" y2="15"/><line x1="10" y1="3" x2="8" y2="21"/><line x1="16" y1="3" x2="14" y2="21"/></svg></span>
                                        <div class="sk-meta-body">
                                            <span class="sk-meta-label">Nomor Surat</span>
                                            <span class="sk-meta-val" style="font-family:monospace;" x-text="activeSurat.nomor_surat || '-'"></span>
                                        </div>
                                    </div>
                                    <div class="sk-meta-card">
                                        <span class="sk-meta-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></span>
                                        <div class="sk-meta-body">
                                            <span class="sk-meta-label">Jenis Surat</span>
                                            <span class="sk-meta-val" x-text="activeSurat.jenis_surat_nama || '-'"></span>
                                        </div>
                                    </div>
                                    <div class="sk-meta-card">
                                        <span class="sk-meta-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.83z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg></span>
                                        <div class="sk-meta-body">
                                            <span class="sk-meta-label">Klasifikasi</span>
                                            <span class="sk-meta-val" x-text="activeSurat.klasifikasi_kode ? (activeSurat.klasifikasi_kode + ' - ' + activeSurat.klasifikasi_nama) : '-'"></span>
                                        </div>
                                    </div>
                                    <div class="sk-meta-card">
                                        <span class="sk-meta-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg></span>
                                        <div class="sk-meta-body">
                                            <span class="sk-meta-label">Tujuan</span>
                                            <span class="sk-meta-val" x-text="activeSurat.tujuan || '-'"></span>
                                        </div>
                                    </div>
                                    <div class="sk-meta-card">
                                        <span class="sk-meta-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span>
                                        <div class="sk-meta-body">
                                            <span class="sk-meta-label">Tanggal Surat</span>
                                            <span class="sk-meta-val" x-text="activeSurat.tanggal_surat_formatted || '-'"></span>
                                        </div>
                                    </div>
                                    <div class="sk-meta-card">
                                        <span class="sk-meta-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></span>
                                        <div class="sk-meta-body">
                                            <span class="sk-meta-label">Status</span>
                                            <span class="sk-meta-val" x-text="activeSurat.status || '-'"></span>
                                        </div>
                                    </div>
                                    <template x-if="activeSurat.has_template && activeSurat.template_nama">
                                        <div class="sk-meta-card">
                                            <span class="sk-meta-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg></span>
                                            <div class="sk-meta-body">
                                                <span class="sk-meta-label">Template Digunakan</span>
                                                <span class="sk-meta-val" x-text="activeSurat.template_nama"></span>
                                            </div>
                                        </div>
                                    </template>
                                    <div class="sk-meta-card">
                                        <span class="sk-meta-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>
                                        <div class="sk-meta-body">
                                            <span class="sk-meta-label">Dicatat Oleh</span>
                                            <span class="sk-meta-val" x-text="activeSurat.creator_name || '-'"></span>
                                        </div>
                                    </div>
                                    <div class="sk-meta-card">
                                        <span class="sk-meta-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span>
                                        <div class="sk-meta-body">
                                            <span class="sk-meta-label">Tanggal Input</span>
                                            <span class="sk-meta-val" x-text="activeSurat.created_at_formatted || '-'"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ── Unit Kerja ── --}}
                            <div class="sk-section">
                                <div class="sk-section-head">
                                    <span class="sk-section-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="2" width="6" height="6" rx="1"/><rect x="2" y="16" width="6" height="6" rx="1"/><rect x="16" y="16" width="6" height="6" rx="1"/><path d="M5 16v-3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3"/><line x1="12" y1="12" x2="12" y2="8"/></svg></span>
                                    <span class="sk-section-title">Unit Kerja Asal</span>
                                </div>
                                <div class="sk-meta-grid">
                                    <div class="sk-meta-card">
                                        <span class="sk-meta-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="22" x2="21" y2="22"/><line x1="6" y1="18" x2="6" y2="11"/><line x1="10" y1="18" x2="10" y2="11"/><line x1="14" y1="18" x2="14" y2="11"/><line x1="18" y1="18" x2="18" y2="11"/><polygon points="12 2 20 7 4 7"/></svg></span>
                                        <div class="sk-meta-body">
                                            <span class="sk-meta-label">Bidang</span>
                                            <span class="sk-meta-val" x-text="activeSurat.induk_unit_kerja_nama || '-'"></span>
                                        </div>
                                    </div>
                                    <div class="sk-meta-card">
                                        <span class="sk-meta-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="6" y1="3" x2="6" y2="15"/><circle cx="18" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M18 9a9 9 0 0 1-9 9"/></svg></span>
                                        <div class="sk-meta-body">
                                            <span class="sk-meta-label">Seksi / Sub Bagian</span>
                                            <span class="sk-meta-val" x-text="activeSurat.unit_kerja_nama || '-'"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ── Dokumen Lampiran ── --}}
                            <div class="sk-section" style="margin-bottom:0;">
                                <div class="sk-section-head">
                                    <span class="sk-section-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7h-3a2 2 0 0 1-2-2V2"/><path d="M9 18a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h7l5 5v9a2 2 0 0 1-2 2Z"/><path d="M3 7.6v12.8A1.6 1.6 0 0 0 4.6 22h9.8"/></svg></span>
                                    <span class="sk-section-title">Dokumen Lampiran</span>
                                </div>

                                <template x-if="activeSurat.best_file_basename">
                                    <div class="sk-pdf-card">
                                        <span class="sk-pdf-ic">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                            <span class="sk-pdf-ext" x-text="fileExt(activeSurat.best_file_basename)"></span>
                                        </span>
                                        <div class="sk-pdf-meta">
                                            <span class="sk-pdf-name" x-text="activeSurat.best_file_basename"></span>
                                            <span class="sk-pdf-status">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                                Dokumen tersedia
                                            </span>
                                        </div>
                                        <div class="sk-pdf-actions">
                                            <a :href="activeSurat.download_url" class="sk-doc-btn is-primary">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                                Unduh
                                            </a>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="!activeSurat.best_file_basename">
                                    <div class="sk-empty">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                        Belum ada dokumen lampiran untuk surat ini.
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="sipas-modal-footer">
                    <button type="button" @click="closeDetail()" class="sipas-btn-secondary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        Tutup
                    </button>
                    <a :href="activeSurat ? activeSurat.show_url : '#'" style="text-decoration:none;" class="sipas-btn-secondary">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                        Buka Halaman Detail
                    </a>
                    <template x-if="activeSurat && !activeSurat.is_final">
                        <button type="button" @click="editFromDetail()" class="sipas-btn-primary">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            Edit / Finalisasi
                        </button>
                    </template>
                    <template x-if="activeSurat && !activeSurat.is_final">
                        <button type="button" @click="askDeleteActive()" class="sk-btn-danger">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                            Hapus
                        </button>
                    </template>
                    <template x-if="activeSurat && activeSurat.is_final">
                        <span style="font-size:12px; color:var(--text-muted,#94a3b8); display:inline-flex; align-items:center; gap:5px;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            Surat final tidak dapat diedit atau dihapus
                        </span>
                    </template>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════
           MODAL EDIT SURAT KELUAR (Hanya untuk DRAFT)
        ══════════════════════════════════════════════════════ --}}
        <div x-show="editModalOpen"
             x-transition.opacity.duration.200ms
             class="sipas-modal-overlay"
             @click.self="closeEdit()"
             @keydown.escape.window="closeEdit()"
             style="display:none;"
             role="dialog" aria-modal="true" aria-labelledby="sk-modal-edit-title">

            <div class="sipas-modal-dialog" style="max-width:720px;" @click.stop>
                <div class="sipas-modal-header">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:34px; height:34px; background:rgba(16,185,129,0.1); border-radius:8px; display:flex; align-items:center; justify-content:center; color:#059669; flex-shrink:0;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </div>
                        <div>
                            <h3 id="sk-modal-edit-title" style="font-size:15px; font-weight:700; color:var(--text-primary,#1e293b); margin:0; line-height:1.2;">Edit / Finalisasi Surat Keluar</h3>
                            <p style="font-size:12px; color:var(--text-muted,#64748b); margin:2px 0 0 0;">Perbarui atau finalkan draft surat keluar</p>
                        </div>
                    </div>
                    <button type="button" @click="closeEdit()" class="sipas-modal-close" aria-label="Tutup">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>

                <form id="sk-edit-form" @submit.prevent="submitEdit()" enctype="multipart/form-data" style="display:flex; flex-direction:column; flex:1; overflow:hidden;">
                    <div class="sipas-modal-body">

                        <div x-show="editGeneralError" style="margin-bottom:16px; padding:12px 16px; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; color:#991b1b; font-size:13px; display:flex; align-items:center; gap:8px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span x-text="editGeneralError"></span>
                        </div>

                        <div style="display:flex; flex-direction:column; gap:16px;">

                            {{-- Jenis + Klasifikasi --}}
                            <div class="sipas-form-row">
                                <div class="form-group" style="flex:1;">
                                    <label class="form-label" for="ek_jenis_surat_id">Jenis Surat <span style="color:#ef4444;">*</span></label>
                                    <div class="sk-input-wrap">
                                        <svg class="sk-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                        <select id="ek_jenis_surat_id" x-model="editForm.jenis_surat_id" class="form-control"
                                                :class="{'sipas-input-error': editErrors.jenis_surat_id}">
                                            <option value="">-- Pilih Jenis Surat --</option>
                                            <template x-for="j in jenisSuratList" :key="j.id">
                                                <option :value="j.id" x-text="j.nama"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <template x-if="editErrors.jenis_surat_id">
                                        <p class="form-error" x-text="editErrors.jenis_surat_id[0]"></p>
                                    </template>
                                </div>
                                <div class="form-group" style="flex:1;">
                                    <label class="form-label" for="ek_klasifikasi_id">Klasifikasi <span style="font-weight:400; color:#94a3b8;">(opsional)</span></label>
                                    <div class="sk-input-wrap">
                                        <svg class="sk-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.83z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                                        <select id="ek_klasifikasi_id" x-model="editForm.klasifikasi_id" class="form-control">
                                            <option value="">-- Tanpa Klasifikasi --</option>
                                            <template x-for="k in klasifikasiList" :key="k.id">
                                                <option :value="k.id" x-text="k.kode + ' - ' + k.nama"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Unit Kerja Searchable Edit --}}
                            <div class="form-group"
                                 x-data="{
                                     open: false, search: '', direction: 'down', maxListHeight: 200,
                                     get filteredData() {
                                         if (!this.search) return structuredUnits;
                                         const q = this.search.toLowerCase();
                                         return structuredUnits.map(b => ({...b, seksis: b.seksis.filter(s => s.nama.toLowerCase().includes(q))})).filter(b => b.seksis.length > 0);
                                     },
                                     toggle(el) {
                                         if (this.open) { this.open = false; return; }
                                         const rect = el.getBoundingClientRect();
                                         const below = window.innerHeight - rect.bottom;
                                         this.direction = (below >= 220) ? 'down' : 'up';
                                         this.maxListHeight = Math.max(80, Math.min(200, below >= 220 ? below - 80 : rect.top - 80));
                                         this.open = true;
                                     },
                                     select(id, bidangId, nama) { editForm.unit_kerja_id = id; editForm.bidang_id = bidangId; editForm.unit_kerja_label = nama; this.open = false; this.search = ''; },
                                     clear() { editForm.unit_kerja_id = ''; editForm.bidang_id = ''; editForm.unit_kerja_label = ''; this.open = false; }
                                 }">
                                <label class="form-label">Unit Kerja Asal (Seksi) <span style="color:#ef4444;">*</span></label>
                                <div style="position:relative;">
                                    <button type="button" @click="toggle($el)" @click.outside="open = false"
                                            class="form-control" :class="{'sipas-input-error': editErrors.unit_kerja_id || editErrors.bidang_id}"
                                            style="display:flex; justify-content:space-between; align-items:center; gap:8px; width:100%; text-align:left; cursor:pointer;">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; color:var(--text-muted,#94a3b8);"><rect x="9" y="2" width="6" height="6" rx="1"/><rect x="2" y="16" width="6" height="6" rx="1"/><rect x="16" y="16" width="6" height="6" rx="1"/><path d="M5 16v-3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3"/><line x1="12" y1="12" x2="12" y2="8"/></svg>
                                        <span x-text="editForm.unit_kerja_label || '-- Pilih Seksi/Unit Kerja --'"
                                              :style="!editForm.unit_kerja_label ? 'color:var(--text-muted,#94a3b8); flex:1;' : 'color:var(--text-primary,#1e293b); flex:1;'"></span>
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><polyline points="6 9 12 15 18 9"/></svg>
                                    </button>
                                    <div x-show="open" x-transition.opacity
                                         :style="direction==='up'
                                             ? 'display:flex; flex-direction:column-reverse; position:absolute; z-index:60; width:100%; bottom:calc(100% + 4px); background:var(--card-bg,#fff); border:1px solid var(--border,#e2e8f0); border-radius:8px; box-shadow:0 10px 25px -5px rgba(0,0,0,0.15); overflow:hidden;'
                                             : 'display:flex; flex-direction:column; position:absolute; z-index:60; width:100%; top:calc(100% + 4px); background:var(--card-bg,#fff); border:1px solid var(--border,#e2e8f0); border-radius:8px; box-shadow:0 10px 25px -5px rgba(0,0,0,0.15); overflow:hidden;'"
                                         style="display:none;">
                                        <div style="padding:8px 10px; border-bottom:1px solid var(--border,#e2e8f0); background:var(--page-bg,#f8fafc); flex-shrink:0;">
                                            <div style="position:relative;">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute; left:10px; top:9px; color:#94a3b8;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                                <input type="text" x-model="search" placeholder="Cari unit kerja..." class="form-control" style="padding:6px 10px 6px 30px; font-size:12.5px;" @click.stop>
                                            </div>
                                        </div>
                                        <div :style="'overflow-y:auto; max-height:' + maxListHeight + 'px; padding:4px 0; background:var(--card-bg,#fff);'">
                                            <template x-if="filteredData.length === 0">
                                                <div style="padding:12px; font-size:13px; color:#64748b; text-align:center;">Unit kerja tidak ditemukan.</div>
                                            </template>
                                            <template x-for="bidang in filteredData" :key="bidang.id">
                                                <div>
                                                    <div x-text="bidang.nama" style="padding:6px 12px; font-size:11px; font-weight:700; color:#64748b; background:var(--page-bg,#f8fafc); text-transform:uppercase; letter-spacing:.5px; border-bottom:1px solid var(--border-light,#f1f5f9);"></div>
                                                    <template x-for="seksi in bidang.seksis" :key="seksi.id">
                                                        <div @click="select(seksi.id, seksi.bidang_id, seksi.nama)"
                                                             style="padding:8px 12px 8px 20px; font-size:13px; color:var(--text-primary,#334155); cursor:pointer; display:flex; align-items:center; gap:6px;"
                                                             class="sipas-dropdown-row">
                                                            <span x-text="seksi.nama" style="flex:1;"></span>
                                                            <svg x-show="editForm.unit_kerja_id == seksi.id" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                        <div style="padding:6px 12px; border-top:1px solid var(--border,#e2e8f0); background:var(--page-bg,#f8fafc); flex-shrink:0;">
                                            <button type="button" @click.stop="clear()" :disabled="!editForm.unit_kerja_id"
                                                    :style="!editForm.unit_kerja_id ? 'opacity:.4; cursor:not-allowed;' : 'cursor:pointer;'"
                                                    style="display:flex; align-items:center; gap:5px; color:#ef4444; font-size:12px; font-weight:600; background:none; border:none; padding:2px 0; outline:none;">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                                Hapus Pilihan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <template x-if="editErrors.unit_kerja_id">
                                    <p class="form-error" x-text="editErrors.unit_kerja_id[0]"></p>
                                </template>
                                <template x-if="editErrors.bidang_id">
                                    <p class="form-error" x-text="editErrors.bidang_id[0]"></p>
                                </template>
                            </div>

                            {{-- Nomor Surat --}}
                            <div class="form-group">
                                <label class="form-label" for="ek_nomor_surat">Nomor Surat <span style="font-weight:400; color:#94a3b8;">(opsional)</span></label>
                                <div class="sk-input-wrap">
                                    <svg class="sk-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="9" x2="20" y2="9"/><line x1="4" y1="15" x2="20" y2="15"/><line x1="10" y1="3" x2="8" y2="21"/><line x1="16" y1="3" x2="14" y2="21"/></svg>
                                    <input id="ek_nomor_surat" type="text" x-model="editForm.nomor_surat" class="form-control" placeholder="Contoh: 001/DISHUB/10/2026">
                                </div>
                            </div>

                            {{-- Perihal --}}
                            <div class="form-group">
                                <label class="form-label" for="ek_perihal">Perihal <span style="color:#ef4444;">*</span></label>
                                <div class="sk-input-wrap">
                                    <svg class="sk-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/></svg>
                                    <input id="ek_perihal" type="text" x-model="editForm.perihal" class="form-control"
                                           :class="{'sipas-input-error': editErrors.perihal}" placeholder="Perihal surat...">
                                </div>
                                <template x-if="editErrors.perihal">
                                    <p class="form-error" x-text="editErrors.perihal[0]"></p>
                                </template>
                            </div>

                            {{-- Tujuan --}}
                            <div class="form-group">
                                <label class="form-label" for="ek_tujuan">Tujuan</label>
                                <div class="sk-input-wrap">
                                    <svg class="sk-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                                    <input id="ek_tujuan" type="text" x-model="editForm.tujuan" class="form-control" placeholder="Nama instansi / penerima">
                                </div>
                            </div>

                            {{-- Tanggal + Status --}}
                            <div class="sipas-form-row">
                                <div class="form-group" style="flex:1;">
                                    <label class="form-label" for="ek_tanggal_surat">Tanggal Surat <span style="color:#ef4444;">*</span></label>
                                    <div class="sk-input-wrap">
                                        <svg class="sk-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                        <input id="ek_tanggal_surat" type="date" x-model="editForm.tanggal_surat" class="form-control"
                                               :class="{'sipas-input-error': editErrors.tanggal_surat}">
                                    </div>
                                    <template x-if="editErrors.tanggal_surat">
                                        <p class="form-error" x-text="editErrors.tanggal_surat[0]"></p>
                                    </template>
                                </div>
                                <div class="form-group" style="flex:1;">
                                    <label class="form-label" for="ek_status">Status</label>
                                    <div class="sk-input-wrap">
                                        <svg class="sk-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                                        <select id="ek_status" x-model="editForm.status" class="form-control">
                                            <option value="final">Final (terbitkan & beri nomor jika kosong)</option>
                                            <option value="draft">Draft (simpan tanpa nomor)</option>
                                        </select>
                                    </div>
                                    <p style="font-size:11px; color:#f59e0b; margin:4px 0 0; font-weight:500;" x-show="editForm.status === 'final'">
                                        ⚠ Jika status Final dipilih, nomor surat akan diterbitkan dan tidak bisa diedit kembali.
                                    </p>
                                </div>
                            </div>

                            {{-- Dokumen saat ini --}}
                            <template x-if="editForm.best_file_basename">
                                <div class="form-group">
                                    <label class="form-label">Dokumen Saat Ini</label>
                                    <div class="sk-pdf-card">
                                        <span class="sk-pdf-ic">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                            <span class="sk-pdf-ext" x-text="fileExt(editForm.best_file_basename)"></span>
                                        </span>
                                        <div class="sk-pdf-meta">
                                            <span class="sk-pdf-name" x-text="editForm.best_file_basename"></span>
                                            <span class="sk-pdf-status">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                                Dokumen tersimpan
                                            </span>
                                        </div>
                                        <div class="sk-pdf-actions">
                                            <a :href="editForm.download_url" class="sk-doc-btn">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                                Unduh
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            {{-- Upload / ganti dokumen --}}
                            <div class="form-group">
                                <label class="form-label" for="ek_file_dokumen">
                                    <span x-text="editForm.best_file_basename ? 'Ganti Dokumen' : 'Upload Dokumen'"></span>
                                    <span style="font-weight:400; color:#94a3b8;">(opsional)</span>
                                </label>

                                <div class="sk-file-selected" x-show="editNewFile.name" style="display:none;">
                                    <div class="sk-file-icon">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                        <span class="sk-file-ext" x-text="editNewFile.ext"></span>
                                    </div>
                                    <div class="sk-file-meta">
                                        <span class="sk-file-name" x-text="editNewFile.name"></span>
                                        <span class="sk-file-size" x-text="editNewFile.sizeLabel"></span>
                                        <span class="sk-file-status">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                            File pengganti siap diunggah
                                        </span>
                                    </div>
                                    <div class="sk-file-actions">
                                        <button type="button" @click="openEditFilePicker()">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10"/><path d="M20.49 15a9 9 0 0 1-14.85 3.36L1 14"/></svg>
                                            Ganti
                                        </button>
                                        <button type="button" class="sk-file-cancel" @click="clearEditFile()">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                            Batal
                                        </button>
                                    </div>
                                </div>

                                <div class="sk-file-card" x-show="!editNewFile.name">
                                    <input id="ek_file_dokumen" type="file" name="file_dokumen" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                           class="sk-file-input" x-ref="editFileInput" @change="onEditFile($event)">
                                    <label for="ek_file_dokumen" class="sk-file-drop">
                                        <span class="sk-file-pdf">
                                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                        </span>
                                        <span class="sk-file-title" x-text="editForm.best_file_basename ? 'Pilih Dokumen Pengganti' : 'Unggah Dokumen Surat'"></span>
                                        <span class="sk-file-hint">Format: PDF, JPG, PNG, DOC, DOCX &middot; Maks 10MB</span>
                                        <span class="sk-file-btn">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                            Pilih File
                                        </span>
                                    </label>
                                </div>

                                <p class="sk-note">Biarkan kosong untuk mempertahankan dokumen lama. Pilih file baru untuk menggantinya.</p>

                                <template x-if="editErrors.file_dokumen">
                                    <p class="form-error" x-text="editErrors.file_dokumen[0]"></p>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="sipas-modal-footer">
                        <button type="button" @click="closeEdit()" class="sipas-btn-secondary" :disabled="saving">Batal</button>
                        <button type="submit" class="sipas-btn-primary" :disabled="saving">
                            <svg x-show="saving" class="sipas-spin" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10" stroke-opacity=".25"/><path d="M12 2a10 10 0 0 1 10 10"/></svg>
                            <svg x-show="!saving" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            <span x-text="saving ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════
           KONFIRMASI HAPUS SURAT (DRAFT)
        ══════════════════════════════════════════════════════ --}}
        <div x-show="deleteConfirmOpen"
             x-transition.opacity.duration.200ms
             class="sipas-modal-overlay"
             @click.self="closeDeleteConfirm()"
             @keydown.escape.window="closeDeleteConfirm()"
             style="display:none;"
             role="dialog" aria-modal="true" aria-labelledby="sk-modal-delete-title">
            <div class="sipas-modal-dialog" style="max-width:440px;" @click.stop>
                <div class="sipas-modal-header">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:34px; height:34px; background:#fef2f2; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#dc2626; flex-shrink:0;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                        </div>
                        <div>
                            <h3 id="sk-modal-delete-title" style="font-size:15px; font-weight:700; color:var(--text-primary,#1e293b); margin:0; line-height:1.2;">Hapus Surat Keluar</h3>
                            <p style="font-size:12px; color:var(--text-muted,#64748b); margin:2px 0 0 0;">Tindakan ini tidak dapat dibatalkan.</p>
                        </div>
                    </div>
                    <button type="button" @click="closeDeleteConfirm()" class="sipas-modal-close" aria-label="Tutup">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>

                <div class="sipas-modal-body">
                    <p style="font-size:13px; color:var(--text-primary,#334155); margin:0 0 12px;">Anda akan menghapus draft surat berikut:</p>
                    <div class="sk-delete-target">
                        <div style="font-size:11px; font-weight:700; letter-spacing:.4px; text-transform:uppercase; color:var(--text-muted,#64748b); margin-bottom:4px;">Perihal</div>
                        <div style="font-weight:600;" x-text="deleteTarget ? deleteTarget.perihal : '-'"></div>
                        <div style="margin-top:6px; font-size:12px; color:var(--text-muted,#64748b);">
                            Nomor: <span style="font-family:monospace;" x-text="deleteTarget && deleteTarget.nomor_surat ? deleteTarget.nomor_surat : '(draft tanpa nomor)'"></span>
                        </div>
                    </div>
                    <p x-show="deleteError" style="margin:12px 0 0; padding:10px 12px; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; color:#991b1b; font-size:12.5px;" x-text="deleteError"></p>
                </div>

                <div class="sipas-modal-footer">
                    <button type="button" @click="closeDeleteConfirm()" class="sipas-btn-secondary" :disabled="deleting">Batal</button>
                    <button type="button" @click="confirmDelete()" class="sk-btn-danger" :disabled="deleting">
                        <svg x-show="deleting" class="sipas-spin" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10" stroke-opacity=".25"/><path d="M12 2a10 10 0 0 1 10 10"/></svg>
                        <svg x-show="!deleting" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        <span x-text="deleting ? 'Menghapus...' : 'Hapus'"></span>
                    </button>
                </div>
            </div>
        </div>

        {{-- ══ Toast Notification ══ --}}
        <div x-show="toast.show"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-2"
             :style="toast.type === 'success'
                 ? 'position:fixed; bottom:24px; right:24px; z-index:9999; display:flex; align-items:center; gap:10px; padding:14px 20px; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:12px; color:#065f46; font-size:14px; font-weight:500; box-shadow:0 8px 24px rgba(0,0,0,0.12);'
                 : 'position:fixed; bottom:24px; right:24px; z-index:9999; display:flex; align-items:center; gap:10px; padding:14px 20px; background:#fef2f2; border:1px solid #fecaca; border-radius:12px; color:#991b1b; font-size:14px; font-weight:500; box-shadow:0 8px 24px rgba(0,0,0,0.12);'"
             style="display:none;">
            <svg x-show="toast.type === 'success'" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <svg x-show="toast.type !== 'success'" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span x-text="toast.message"></span>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════
       MODAL & FORM STYLES
    ══════════════════════════════════════════════════════ --}}
    <style>
    .sipas-modal-overlay {
        position: fixed; inset: 0;
        background-color: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(3px);
        z-index: 9998;
        display: flex; align-items: center; justify-content: center;
        padding: 16px; overflow-y: auto;
    }
    .sipas-modal-dialog {
        position: relative; width: 100%;
        background-color: var(--card-bg, #ffffff);
        color: var(--text-primary, #1e293b);
        border: 1px solid var(--border, #e2e8f0);
        border-radius: 8px;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2), 0 10px 10px -5px rgba(0,0,0,0.1);
        display: flex; flex-direction: column;
        max-height: calc(100vh - 48px); overflow: hidden; z-index: 9999;
    }
    .sipas-modal-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px 20px; border-bottom: 1px solid var(--border, #e2e8f0);
        background-color: var(--card-bg, #ffffff); flex-shrink: 0;
    }
    .sipas-modal-close {
        width: 32px; height: 32px;
        display: flex; align-items: center; justify-content: center;
        background: transparent; border: 1px solid transparent; border-radius: 6px;
        color: var(--text-muted, #64748b); cursor: pointer; transition: all 0.15s ease;
    }
    .sipas-modal-close:hover { background: var(--page-bg, #f1f5f9); color: var(--text-primary, #1e293b); border-color: var(--border, #e2e8f0); }
    .sipas-modal-body { padding: 20px; overflow-y: auto; flex: 1; }
    .sipas-modal-footer {
        display: flex; align-items: center; justify-content: flex-end; gap: 10px;
        padding: 14px 20px; border-top: 1px solid var(--border, #e2e8f0);
        background-color: var(--page-bg, #f8fafc); flex-shrink: 0; flex-wrap: wrap;
    }
    .sipas-btn-primary {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        padding: 9px 18px; background: var(--tc-accent, #2563eb); color: #fff;
        font-size: 13px; font-weight: 600; border-radius: 8px; border: none;
        cursor: pointer; transition: opacity 0.15s; text-decoration: none;
    }
    .sipas-btn-primary:hover:not(:disabled) { opacity: 0.92; }
    .sipas-btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }
    .sipas-btn-secondary {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        padding: 9px 18px; background: var(--card-bg, #ffffff);
        color: var(--text-primary, #334155); font-size: 13px; font-weight: 600;
        border-radius: 8px; border: 1px solid var(--border, #cbd5e1);
        cursor: pointer; transition: background 0.15s; text-decoration: none;
    }
    .sipas-btn-secondary:hover:not(:disabled) { background: var(--page-bg, #f8fafc); border-color: #94a3b8; }
    .sipas-btn-secondary:disabled { opacity: 0.6; cursor: not-allowed; }
    .sipas-info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .sipas-info-box {
        padding: 10px 14px; background: var(--page-bg, #f8fafc);
        border: 1px solid var(--border, #e2e8f0); border-radius: 8px;
        display: flex; flex-direction: column; gap: 2px;
    }
    .sipas-info-label { font-size: 11px; color: var(--text-muted, #64748b); font-weight: 600; text-transform: uppercase; letter-spacing: 0.4px; }
    .sipas-info-val { font-size: 13.5px; color: var(--text-primary, #1e293b); font-weight: 600; }
    .sipas-form-row { display: flex; gap: 16px; }
    .form-group { display: flex; flex-direction: column; gap: 5px; }
    .form-label { font-size: 12px; font-weight: 600; color: var(--text-primary, #374151); letter-spacing: 0.2px; }
    .form-control {
        width: 100%; padding: 9px 12px; font-size: 13.5px;
        color: var(--text-primary, #1e293b); background: var(--card-bg, #ffffff);
        border: 1px solid var(--border, #d1d5db); border-radius: 8px;
        outline: none; transition: border-color 0.15s, box-shadow 0.15s; box-sizing: border-box;
    }
    .form-control:focus { border-color: var(--tc-accent, #2563eb); box-shadow: 0 0 0 3px rgba(37,99,235,0.08); }
    select.form-control { cursor: pointer; }
    .form-error { font-size: 12px; color: #ef4444; margin: 0; font-weight: 500; }
    .sipas-input-error { border-color: #ef4444 !important; }
    .sipas-dropdown-row:hover { background: rgba(37,99,235,0.05); }
    .sipas-spin { animation: sipasSpin 0.8s linear infinite; }
    @keyframes sipasSpin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

    /* ── Input dengan ikon (konsisten dengan Surat Masuk) ── */
    .sk-input-wrap { position: relative; display: block; }
    .sk-input-wrap > .form-control { padding-left: 36px; }
    .sk-input-icon {
        position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
        width: 16px; height: 16px; color: var(--text-muted, #94a3b8); pointer-events: none; z-index: 1;
    }
    .sk-input-wrap:focus-within .sk-input-icon { color: #15803d; }
    .sk-input-wrap:focus-within > .form-control { border-color: #15803d !important; box-shadow: 0 0 0 3px rgba(21,128,61,0.10) !important; }

    /* ── Detail modal (konsisten dengan Surat Masuk) ── */
    .sk-detail-dialog { --sk-green: #15803d; --sk-green-soft: rgba(21,128,61,0.10); }
    .sk-detail-head-ic {
        width: 34px; height: 34px; flex-shrink: 0; border-radius: 8px;
        background: var(--sk-green-soft); color: var(--sk-green);
        display: flex; align-items: center; justify-content: center;
    }
    .sk-section { margin-bottom: 20px; }
    .sk-section-head { display: flex; align-items: center; gap: 8px; margin-bottom: 10px; }
    .sk-section-ic {
        width: 26px; height: 26px; flex-shrink: 0; border-radius: 7px;
        background: var(--sk-green-soft); color: var(--sk-green);
        display: flex; align-items: center; justify-content: center;
    }
    .sk-section-ic svg { width: 15px; height: 15px; }
    .sk-section-title { font-size: 13px; font-weight: 700; color: var(--text-primary, #1e293b); letter-spacing: .2px; }
    .sk-meta-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
    .sk-meta-card {
        display: flex; align-items: flex-start; gap: 10px; padding: 10px 12px; min-width: 0;
        background: var(--page-bg, #f8fafc); border: 1px solid var(--border, #e2e8f0); border-radius: 8px;
    }
    .sk-meta-ic { flex-shrink: 0; margin-top: 1px; color: var(--text-muted, #64748b); display: inline-flex; }
    .sk-meta-ic svg { width: 15px; height: 15px; }
    .sk-meta-body { min-width: 0; display: flex; flex-direction: column; gap: 2px; }
    .sk-meta-label { font-size: 10.5px; font-weight: 700; letter-spacing: .4px; text-transform: uppercase; color: var(--text-muted, #64748b); }
    .sk-meta-val { font-size: 13px; font-weight: 600; color: var(--text-primary, #1e293b); overflow-wrap: anywhere; word-break: break-word; }
    .sk-perihal {
        display: flex; align-items: flex-start; gap: 12px; padding: 14px 16px;
        background: var(--page-bg, #f8fafc); border: 1px solid var(--border, #e2e8f0);
        border-left: 3px solid var(--sk-green); border-radius: 8px;
    }
    .sk-perihal-text { font-size: 15px; font-weight: 700; line-height: 1.45; color: var(--text-primary, #1e293b); margin: 0; overflow-wrap: anywhere; word-break: break-word; }

    /* ── Kartu dokumen PDF ── */
    .sk-pdf-card {
        display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
        padding: 12px 14px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px;
    }
    .sk-pdf-ic {
        position: relative; width: 40px; height: 40px; flex-shrink: 0; border-radius: 9px;
        background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center;
    }
    .sk-pdf-ext {
        position: absolute; bottom: -4px; right: -5px; font-size: 8px; font-weight: 800; letter-spacing: .3px;
        background: #dc2626; color: #fff; border-radius: 4px; padding: 1px 4px; line-height: 1.3;
    }
    .sk-pdf-meta { flex: 1; min-width: 140px; display: flex; flex-direction: column; gap: 3px; }
    .sk-pdf-name { font-size: 13px; font-weight: 600; color: var(--text-primary, #1e293b); overflow-wrap: anywhere; word-break: break-word; }
    .sk-pdf-status { font-size: 11px; font-weight: 600; color: #16a34a; display: inline-flex; align-items: center; gap: 4px; }
    .sk-pdf-actions { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; flex-shrink: 0; }

    /* ── Tombol aksi dokumen & hapus ── */
    .sk-doc-btn {
        display: inline-flex; align-items: center; gap: 6px; padding: 7px 12px; border-radius: 7px;
        font-size: 12px; font-weight: 600; cursor: pointer; border: 1px solid var(--border, #cbd5e1);
        background: var(--card-bg, #fff); color: var(--text-primary, #334155); text-decoration: none; white-space: nowrap;
    }
    .sk-doc-btn:hover { background: var(--page-bg, #f1f5f9); }
    .sk-doc-btn.is-primary { color: var(--tc-accent, #2563eb); }
    .sk-btn-danger {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        padding: 9px 18px; background: #fef2f2; color: #dc2626; border: 1px solid #fecaca;
        font-size: 13px; font-weight: 600; border-radius: 8px; cursor: pointer; transition: background .15s, border-color .15s;
    }
    .sk-btn-danger:hover:not(:disabled) { background: #fee2e2; border-color: #fca5a5; }
    .sk-btn-danger:disabled { opacity: .6; cursor: not-allowed; }
    .sk-empty {
        display: flex; align-items: center; gap: 10px; padding: 12px 16px;
        background: var(--page-bg, #f8fafc); border: 1px dashed var(--border, #e2e8f0);
        border-radius: 8px; color: var(--text-muted, #64748b); font-size: 12.5px;
    }
    .sk-note { font-size: 11.5px; color: var(--text-muted, #64748b); margin: 8px 0 0; }

    /* ── Kartu upload dokumen (create/edit) ── */
    .sk-file-card {
        position: relative; border: 1.5px dashed var(--border, #cbd5e1); border-radius: 10px;
        background: var(--page-bg, #f8fafc); padding: 18px 16px; text-align: center; transition: border-color .15s, background .15s;
    }
    .sk-file-card:hover { border-color: #15803d; background: rgba(21,128,61,0.03); }
    .sk-file-input { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0; }
    .sk-file-drop { display: flex; flex-direction: column; align-items: center; gap: 6px; cursor: pointer; }
    .sk-file-pdf { width: 46px; height: 46px; border-radius: 12px; background: #fef2f2; color: #dc2626; display: flex; align-items: center; justify-content: center; }
    .sk-file-title { font-size: 13.5px; font-weight: 600; color: var(--text-primary, #1e293b); }
    .sk-file-hint { font-size: 11.5px; color: var(--text-muted, #64748b); }
    .sk-file-btn { margin-top: 6px; display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; background: #15803d; color: #fff; border: none; border-radius: 7px; font-size: 12.5px; font-weight: 600; cursor: pointer; }
    .sk-file-selected { display: flex; align-items: center; gap: 12px; padding: 12px 14px; background: var(--page-bg, #f8fafc); border: 1px solid var(--border, #e2e8f0); border-radius: 10px; }
    .sk-file-selected + .sk-file-card, .sk-file-card + .sk-file-selected { margin-top: 8px; }
    .sk-file-icon { position: relative; width: 38px; height: 38px; flex-shrink: 0; border-radius: 9px; background: #fef2f2; color: #dc2626; display: flex; align-items: center; justify-content: center; }
    .sk-file-ext { position: absolute; bottom: -4px; right: -5px; font-size: 8px; font-weight: 800; letter-spacing: .3px; background: #dc2626; color: #fff; border-radius: 4px; padding: 1px 4px; line-height: 1.3; }
    .sk-file-meta { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 2px; }
    .sk-file-name { font-size: 13px; font-weight: 600; color: var(--text-primary, #1e293b); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .sk-file-size { font-size: 11.5px; color: var(--text-muted, #64748b); }
    .sk-file-status { font-size: 11px; font-weight: 600; color: #16a34a; display: inline-flex; align-items: center; gap: 4px; }
    .sk-file-actions { display: flex; gap: 6px; flex-shrink: 0; align-items: center; }
    .sk-file-actions button { display: inline-flex; align-items: center; gap: 5px; padding: 6px 10px; border-radius: 7px; font-size: 11.5px; font-weight: 600; cursor: pointer; border: 1px solid var(--border, #cbd5e1); background: var(--card-bg, #fff); color: var(--text-primary, #334155); }
    .sk-file-actions button:hover { background: var(--page-bg, #f1f5f9); }
    .sk-file-actions .sk-file-cancel { color: #ef4444; border-color: #fecaca; }
    .sk-delete-target {
        padding: 12px 14px; background: var(--page-bg, #f8fafc); border: 1px solid var(--border, #e2e8f0);
        border-radius: 8px; font-size: 13px; color: var(--text-primary, #1e293b);
    }
    .sk-delete-target strong { font-weight: 700; }

    [data-theme="dark"] .sipas-modal-dialog { background-color: #1e293b !important; border-color: #334155 !important; color: #e2e8f0 !important; }
    [data-theme="dark"] .sipas-modal-header { background-color: #1e293b !important; border-bottom-color: #334155 !important; }
    [data-theme="dark"] .sipas-modal-footer { background-color: #162032 !important; border-top-color: #334155 !important; }
    [data-theme="dark"] .sipas-modal-close:hover { background: #334155 !important; color: #e2e8f0 !important; }
    [data-theme="dark"] .sipas-info-box { background-color: #162032 !important; border-color: #334155 !important; }
    [data-theme="dark"] .sipas-info-label { color: #94a3b8 !important; }
    [data-theme="dark"] .sipas-info-val { color: #f1f5f9 !important; }
    [data-theme="dark"] .sipas-btn-secondary { background: #1e293b !important; border-color: #334155 !important; color: #cbd5e1 !important; }
    [data-theme="dark"] .sipas-btn-secondary:hover:not(:disabled) { background: #27354a !important; border-color: #475569 !important; }
    [data-theme="dark"] .sipas-dropdown-row:hover { background: #1e3a5c !important; }
    [data-theme="dark"] .form-control { background: #1a2740 !important; border-color: #334155 !important; color: #e2e8f0 !important; }
    [data-theme="dark"] .form-control:focus { border-color: var(--tc-accent, #3b82f6) !important; box-shadow: 0 0 0 3px rgba(59,130,246,0.15) !important; }
    [data-theme="dark"] .form-label { color: #cbd5e1 !important; }
    [data-theme="dark"] .sk-input-icon { color: #64748b; }
    [data-theme="dark"] .sk-detail-dialog { --sk-green: #4ade80; --sk-green-soft: rgba(74,222,128,0.12); }
    [data-theme="dark"] .sk-meta-card,
    [data-theme="dark"] .sk-perihal,
    [data-theme="dark"] .sk-empty,
    [data-theme="dark"] .sk-delete-target { background: #162032 !important; border-color: #334155 !important; }
    [data-theme="dark"] .sk-meta-val,
    [data-theme="dark"] .sk-perihal-text,
    [data-theme="dark"] .sk-delete-target { color: #e2e8f0 !important; }
    [data-theme="dark"] .sk-meta-label,
    [data-theme="dark"] .sk-meta-ic,
    [data-theme="dark"] .sk-empty,
    [data-theme="dark"] .sk-note { color: #94a3b8 !important; }
    [data-theme="dark"] .sk-section-title { color: #e2e8f0 !important; }
    [data-theme="dark"] .sk-pdf-card { background: rgba(220,38,38,0.10) !important; border-color: rgba(248,113,113,0.35) !important; }
    [data-theme="dark"] .sk-pdf-ic { background: rgba(220,38,38,0.20) !important; color: #f87171 !important; }
    [data-theme="dark"] .sk-pdf-name,
    [data-theme="dark"] .sk-file-name { color: #e2e8f0 !important; }
    [data-theme="dark"] .sk-pdf-status,
    [data-theme="dark"] .sk-file-status { color: #4ade80 !important; }
    [data-theme="dark"] .sk-btn-danger { background: rgba(220,38,38,0.12) !important; border-color: rgba(248,113,113,0.35) !important; color: #f87171 !important; }
    [data-theme="dark"] .sk-doc-btn { background: #1e293b !important; border-color: #334155 !important; color: #cbd5e1 !important; }
    [data-theme="dark"] .sk-doc-btn.is-primary { color: #60a5fa !important; }
    [data-theme="dark"] .sk-file-card { background: #162032 !important; border-color: #334155 !important; }
    [data-theme="dark"] .sk-file-selected { background: #162032 !important; border-color: #334155 !important; }
    [data-theme="dark"] .sk-file-actions button { background: #1e293b !important; border-color: #334155 !important; color: #cbd5e1 !important; }

    @media (max-width: 640px) {
        .sipas-form-row { flex-direction: column; gap: 12px; }
        .sipas-info-grid { grid-template-columns: 1fr; }
        .sk-meta-grid { grid-template-columns: 1fr; }
        .sk-pdf-actions { width: 100%; }
        .sk-doc-btn { flex: 1; justify-content: center; }
        .sipas-modal-dialog { max-height: calc(100vh - 24px); }
        .sipas-modal-header { padding: 14px 16px; }
        .sipas-modal-body { padding: 16px; }
        .sipas-modal-footer { padding: 12px 16px; flex-direction: column-reverse; }
        .sipas-btn-primary, .sipas-btn-secondary { width: 100%; }
    }
    </style>

    @push('scripts')
    <script>
    function suratKeluarModalHandler({ initialData, structuredUnits, jenisSuratList, klasifikasiList, csrfToken, baseUrl }) {
        return {
            suratMap: initialData,
            structuredUnits,
            jenisSuratList,
            klasifikasiList,
            csrfToken,
            baseUrl,

            createModalOpen: false,
            detailModalOpen: false,
            editModalOpen: false,
            activeSurat: null,

            deleteConfirmOpen: false,
            deleteTarget: null,
            deleting: false,
            deleteError: '',

            createFile: { name: null, size: null, sizeLabel: '', ext: '' },
            editNewFile: { name: null, size: null, sizeLabel: '', ext: '' },

            createForm: { jenis_surat_id: '', klasifikasi_id: '', unit_kerja_id: '', bidang_id: '', unit_kerja_label: '', nomor_surat: '', perihal: '', tujuan: '', tanggal_surat: '', status: 'final' },
            createErrors: {},
            createGeneralError: '',
            savingCreate: false,

            editForm: { id: '', jenis_surat_id: '', klasifikasi_id: '', unit_kerja_id: '', bidang_id: '', unit_kerja_label: '', nomor_surat: '', perihal: '', tujuan: '', tanggal_surat: '', status: 'draft', best_file_basename: null, download_url: null },
            editErrors: {},
            editGeneralError: '',
            saving: false,

            toast: { show: false, message: '', type: 'success', timer: null },

            init() {
                window.__skHandler = this;
                this.$watch('createModalOpen', () => this.updateBodyLock());
                this.$watch('detailModalOpen', () => this.updateBodyLock());
                this.$watch('editModalOpen',   () => this.updateBodyLock());
            },

            updateBodyLock() {
                document.body.style.overflow = (this.createModalOpen || this.detailModalOpen || this.editModalOpen || this.deleteConfirmOpen) ? 'hidden' : '';
            },

            // ── CREATE ──────────────────────────────────────
            openCreate() {
                this.createForm = { jenis_surat_id: '', klasifikasi_id: '', unit_kerja_id: '', bidang_id: '', unit_kerja_label: '', nomor_surat: '', perihal: '', tujuan: '', tanggal_surat: '', status: 'final' };
                this.createErrors = {};
                this.createGeneralError = '';
                this.savingCreate = false;
                this.createFile = { name: null, size: null, sizeLabel: '', ext: '' };
                const fi = this.$refs.createFileInput;
                if (fi) fi.value = '';
                this.createModalOpen = true;
            },

            closeCreate() {
                this.createModalOpen = false;
                this.createErrors = {};
                this.createGeneralError = '';
            },

            async submitCreate() {
                this.savingCreate = true;
                this.createErrors = {};
                this.createGeneralError = '';

                const fd = new FormData();
                fd.append('_token', this.csrfToken);
                fd.append('jenis_surat_id', this.createForm.jenis_surat_id || '');
                if (this.createForm.klasifikasi_id) fd.append('klasifikasi_id', this.createForm.klasifikasi_id);
                fd.append('unit_kerja_id', this.createForm.unit_kerja_id || '');
                fd.append('bidang_id', this.createForm.bidang_id || '');
                fd.append('nomor_surat', this.createForm.nomor_surat || '');
                fd.append('perihal', this.createForm.perihal || '');
                fd.append('tujuan', this.createForm.tujuan || '');
                fd.append('tanggal_surat', this.createForm.tanggal_surat || '');
                fd.append('status', this.createForm.status || 'final');
                const fi = document.getElementById('ck_file_dokumen');
                if (fi && fi.files[0]) fd.append('file_dokumen', fi.files[0]);

                try {
                    const res = await fetch(this.baseUrl, { method: 'POST', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: fd });
                    if (res.status === 422) {
                        const err = await res.json();
                        this.createErrors = err.errors || {};
                        this.createGeneralError = err.message || 'Mohon periksa kesalahan input pada form.';
                        this.savingCreate = false;
                        return;
                    }
                    if (res.ok || res.status === 201) {
                        let json = null;
                        try { json = await res.json(); } catch(e) {}
                        if (json && json.surat) {
                            this.suratMap[json.surat.id] = json.surat;
                            if (json.surat.status !== 'draft') this.insertTableRow(json.surat);
                        }
                        this.savingCreate = false;
                        this.createModalOpen = false;
                        if (fi) fi.value = '';
                        this.showToast((json && json.message) ? json.message : 'Surat keluar berhasil dicatat.', 'success');
                    } else {
                        this.savingCreate = false;
                        this.createGeneralError = 'Terjadi kesalahan sistem (' + res.status + '). Silakan coba lagi.';
                    }
                } catch(e) {
                    this.savingCreate = false;
                    this.createGeneralError = 'Gagal menghubungi server. Periksa koneksi internet Anda.';
                }
            },

            insertTableRow(s) {
                const tbody = document.getElementById('sk-table-body');
                if (!tbody) return;
                const empty = document.getElementById('sk-empty-row');
                if (empty) empty.remove();
                const sc = s.is_final ? ['#ecfdf5','#065f46'] : ['#f1f5f9','#475569'];
                const tr = document.createElement('tr');
                tr.setAttribute('data-surat-row', '');
                tr.id = 'sk-row-' + s.id;
                tr.innerHTML = `
                    <td style="color:#94a3b8; font-size:13px; padding-left:16px;">—</td>
                    <td style="font-family:monospace; font-size:12px; color:#334155;">${s.nomor_surat || '-'}</td>
                    <td style="font-weight:500; color:#1e293b; max-width:220px;">
                        <a href="${s.show_url}" onclick="window.__skHandler.openDetail(${s.id}); return false;" style="color:#1e293b; text-decoration:none;">${this.truncate(s.perihal, 60)}</a>
                    </td>
                    <td style="color:#475569;">${s.tujuan || '-'}</td>
                    <td><span style="display:inline-flex; padding:4px 10px; background:#fffbeb; color:#d97706; font-size:11px; font-weight:600; border-radius:8px;">${s.jenis_surat_kode || '-'}</span></td>
                    <td style="color:#64748b; font-size:13px;">${s.tanggal_surat || '-'}</td>
                    <td><span style="display:inline-flex; padding:4px 10px; background:${sc[0]}; color:${sc[1]}; font-size:11px; font-weight:600; border-radius:8px; text-transform:capitalize;">${s.status}</span></td>
                    <td style="text-align:right; padding-right:16px;">
                        <div style="display:flex; justify-content:flex-end; gap:6px;">
                            <a href="${s.show_url}" title="Detail" onclick="window.__skHandler.openDetail(${s.id}); return false;"
                               style="display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; background:#f8fafc; color:#3b82f6; border-radius:6px; border:1px solid #e2e8f0; text-decoration:none;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </a>
                            ${!s.is_final ? `<a href="${s.edit_url}" title="Edit" onclick="window.__skHandler.openEdit(${s.id}); return false;"
                               style="display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; background:#f8fafc; color:#10b981; border-radius:6px; border:1px solid #e2e8f0; text-decoration:none;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>` : ''}
                        </div>
                    </td>
                `;
                // Sisipkan ke kelompok tanggal input hari ini agar header tanggal tetap benar.
                const groupTemplate = document.getElementById('sk-today-group-template');
                if (window.DateGroupTable && groupTemplate) {
                    window.DateGroupTable.insertRow({ tbody, template: groupTemplate, emptyRow: empty, row: tr });
                } else {
                    tbody.insertBefore(tr, tbody.firstChild);
                }
            },

            // ── DETAIL ──────────────────────────────────────
            openDetail(id) {
                const item = this.suratMap[id];
                if (!item) return;
                this.activeSurat = { ...item };
                this.detailModalOpen = true;
                // Refresh dari server untuk data terbaru
                fetch(this.baseUrl + '/' + id, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(r => r.ok ? r.json() : null)
                    .then(data => { if (data && data.surat) { this.suratMap[id] = data.surat; this.activeSurat = { ...data.surat }; } })
                    .catch(() => {});
            },

            closeDetail() { this.detailModalOpen = false; },

            editFromDetail() {
                if (!this.activeSurat) return;
                const id = this.activeSurat.id;
                this.closeDetail();
                this.openEdit(id);
            },

            // ── EDIT ────────────────────────────────────────
            openEdit(id) {
                const item = this.suratMap[id];
                if (!item) return;

                this.editErrors = {};
                this.editGeneralError = '';
                this.saving = false;

                const bestFile = item.file_pdf || item.file_word || item.file_dokumen;
                const bestFileBase = item.file_pdf_basename || item.file_word_basename || item.file_dokumen_basename || null;

                this.editForm = {
                    id:               item.id,
                    jenis_surat_id:   item.jenis_surat_id ? String(item.jenis_surat_id) : '',
                    klasifikasi_id:   item.klasifikasi_id ? String(item.klasifikasi_id) : '',
                    unit_kerja_id:    item.unit_kerja_id ? String(item.unit_kerja_id) : '',
                    bidang_id:        item.bidang_id ? String(item.bidang_id) : '',
                    unit_kerja_label: item.unit_kerja_nama || '',
                    nomor_surat:      item.nomor_surat || '',
                    perihal:          item.perihal || '',
                    tujuan:           item.tujuan || '',
                    tanggal_surat:    item.tanggal_surat || '',
                    status:           item.status || 'draft',
                    best_file_basename: bestFileBase,
                    download_url:     item.download_url || null,
                };

                this.editNewFile = { name: null, size: null, sizeLabel: '', ext: '' };
                const fi = this.$refs.editFileInput;
                if (fi) fi.value = '';
                this.editModalOpen = true;
            },

            closeEdit() { this.editModalOpen = false; this.editErrors = {}; this.editGeneralError = ''; },

            async submitEdit() {
                this.saving = true;
                this.editErrors = {};
                this.editGeneralError = '';

                const fd = new FormData();
                fd.append('_token', this.csrfToken);
                fd.append('_method', 'PUT');
                fd.append('jenis_surat_id', this.editForm.jenis_surat_id || '');
                if (this.editForm.klasifikasi_id) fd.append('klasifikasi_id', this.editForm.klasifikasi_id);
                fd.append('unit_kerja_id', this.editForm.unit_kerja_id || '');
                fd.append('bidang_id', this.editForm.bidang_id || '');
                fd.append('nomor_surat', this.editForm.nomor_surat || '');
                fd.append('perihal', this.editForm.perihal || '');
                fd.append('tujuan', this.editForm.tujuan || '');
                fd.append('tanggal_surat', this.editForm.tanggal_surat || '');
                fd.append('status', this.editForm.status || 'draft');
                const fi = document.getElementById('ek_file_dokumen');
                if (fi && fi.files[0]) fd.append('file_dokumen', fi.files[0]);

                try {
                    const res = await fetch(this.baseUrl + '/' + this.editForm.id, { method: 'POST', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: fd });
                    if (res.status === 403) {
                        this.editGeneralError = 'Surat final tidak dapat diedit.';
                        this.saving = false;
                        return;
                    }
                    if (res.status === 422) {
                        const err = await res.json();
                        this.editErrors = err.errors || {};
                        this.editGeneralError = err.message || 'Mohon periksa kesalahan input pada form.';
                        this.saving = false;
                        return;
                    }
                    if (res.ok) {
                        let json = null;
                        try { json = await res.json(); } catch(e) {}
                        if (json && json.surat) {
                            this.suratMap[json.surat.id] = json.surat;
                            this.updateTableRow(json.surat);
                        }
                        this.saving = false;
                        this.editModalOpen = false;
                        this.showToast((json && json.message) ? json.message : 'Surat keluar berhasil diperbarui.', 'success');
                    } else {
                        this.saving = false;
                        this.editGeneralError = 'Terjadi kesalahan sistem (' + res.status + '). Silakan coba lagi.';
                    }
                } catch(e) {
                    this.saving = false;
                    this.editGeneralError = 'Gagal menghubungi server. Periksa koneksi internet Anda.';
                }
            },

            updateTableRow(s) {
                const row = document.getElementById('sk-row-' + s.id);
                if (!row) return;
                const cells = row.querySelectorAll('td');
                if (cells[1]) cells[1].textContent = s.nomor_surat || '-';
                if (cells[2]) { const a = cells[2].querySelector('a'); if (a) a.textContent = this.truncate(s.perihal, 60); }
                if (cells[3]) cells[3].textContent = s.tujuan || '-';
                if (cells[4]) { const span = cells[4].querySelector('span'); if (span) span.textContent = s.jenis_surat_kode || '-'; }
                if (cells[5]) cells[5].textContent = s.tanggal_surat || '-';
                if (cells[6]) {
                    const sc = s.is_final ? ['#ecfdf5','#065f46'] : ['#f1f5f9','#475569'];
                    const span = cells[6].querySelector('span');
                    if (span) { span.textContent = s.status; span.style.background = sc[0]; span.style.color = sc[1]; }
                }
                // Jika sudah final: hapus tombol edit dari action cell
                if (s.is_final && cells[7]) {
                    const editBtn = cells[7].querySelector('a[title="Edit"]');
                    if (editBtn) editBtn.remove();
                }
            },

            formatBytes(bytes) {
                if (!bytes && bytes !== 0) return '';
                const units = ['B', 'KB', 'MB', 'GB'];
                let n = bytes;
                let i = 0;
                while (n >= 1024 && i < units.length - 1) { n /= 1024; i++; }
                const val = (i === 0 || n >= 10) ? Math.round(n) : n.toFixed(1);
                return val + ' ' + units[i];
            },

            fileExt(name) {
                if (!name) return 'FILE';
                const parts = name.split('.');
                const ext = parts.length > 1 ? parts.pop() : 'FILE';
                return ext.toUpperCase().slice(0, 4);
            },

            onCreateFile(e) {
                const f = e.target.files && e.target.files[0];
                if (!f) { this.clearCreateFile(); return; }
                this.createFile = { name: f.name, size: f.size, sizeLabel: this.formatBytes(f.size), ext: this.fileExt(f.name) };
            },

            clearCreateFile() {
                const fi = this.$refs.createFileInput;
                if (fi) fi.value = '';
                this.createFile = { name: null, size: null, sizeLabel: '', ext: '' };
            },

            openCreateFilePicker() {
                if (this.$refs.createFileInput) this.$refs.createFileInput.click();
            },

            onEditFile(e) {
                const f = e.target.files && e.target.files[0];
                if (!f) { this.clearEditFile(); return; }
                this.editNewFile = { name: f.name, size: f.size, sizeLabel: this.formatBytes(f.size), ext: this.fileExt(f.name) };
            },

            clearEditFile() {
                const fi = this.$refs.editFileInput;
                if (fi) fi.value = '';
                this.editNewFile = { name: null, size: null, sizeLabel: '', ext: '' };
            },

            openEditFilePicker() {
                if (this.$refs.editFileInput) this.$refs.editFileInput.click();
            },

            // ── DELETE (draft only) ───────────────────────
            askDelete(id) {
                const item = this.suratMap[id];
                if (!item) return;
                this.deleteTarget = {
                    id: item.id,
                    perihal: item.perihal || '-',
                    nomor_surat: item.nomor_surat || null,
                    destroy_url: item.destroy_url,
                };
                this.deleteError = '';
                this.deleting = false;
                this.deleteConfirmOpen = true;
            },

            askDeleteActive() {
                if (!this.activeSurat) return;
                this.askDelete(this.activeSurat.id);
            },

            closeDeleteConfirm() {
                if (this.deleting) return;
                this.deleteConfirmOpen = false;
                this.deleteTarget = null;
                this.deleteError = '';
            },

            async confirmDelete() {
                if (!this.deleteTarget || this.deleting) return;
                this.deleting = true;
                this.deleteError = '';
                const target = this.deleteTarget;
                try {
                    const res = await fetch(target.destroy_url, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': this.csrfToken,
                        },
                    });
                    if (res.status === 403) {
                        const err = await res.json().catch(() => null);
                        this.deleteError = (err && err.message) ? err.message : 'Surat ini tidak dapat dihapus.';
                        this.deleting = false;
                        return;
                    }
                    if (!res.ok) {
                        this.deleteError = 'Gagal menghapus surat (' + res.status + '). Silakan coba lagi.';
                        this.deleting = false;
                        return;
                    }
                    const json = await res.json().catch(() => null);
                    const row = document.getElementById('sk-row-' + target.id);
                    if (row && window.DateGroupTable) {
                        window.DateGroupTable.removeRow(row);
                    } else if (row) {
                        row.remove();
                    }
                    delete this.suratMap[target.id];
                    if (this.activeSurat && this.activeSurat.id === target.id) this.closeDetail();
                    this.deleting = false;
                    this.deleteConfirmOpen = false;
                    this.deleteTarget = null;
                    this.showToast((json && json.message) ? json.message : 'Draft surat keluar berhasil dihapus.', 'success');
                } catch (e) {
                    this.deleting = false;
                    this.deleteError = 'Gagal menghubungi server. Periksa koneksi internet Anda.';
                }
            },

            truncate(str, maxLen) {
                if (!str) return '';
                return str.length > maxLen ? str.substring(0, maxLen) + '...' : str;
            },

            showToast(msg, type = 'success') {
                if (this.toast.timer) clearTimeout(this.toast.timer);
                this.toast.message = msg;
                this.toast.type = type;
                this.toast.show = true;
                this.toast.timer = setTimeout(() => { this.toast.show = false; }, 4000);
            }
        };
    }
    </script>
    @endpush

</x-app-layout>
