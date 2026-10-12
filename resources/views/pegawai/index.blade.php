<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; gap:12px;">
            <div style="width:40px; height:40px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#1d4ed8; flex-shrink:0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
                <div style="font-size:17px; font-weight:600; color:var(--text-primary, #1e293b); line-height:1.2;">Master Pegawai</div>
                <div style="margin-top:2px; font-size:12.5px; color:var(--text-muted, #64748b);">Kelola data pegawai Dinas Perhubungan</div>
            </div>
        </div>
    </x-slot>

    @php
        $structuredUnits = [];
        foreach($unitKerjas->where('parent_id', null) as $bidang) {
            $seksis = [];
            foreach($unitKerjas->where('parent_id', $bidang->id) as $seksi) {
                $seksis[] = ['id' => $seksi->id, 'nama' => $seksi->nama];
            }
            $structuredUnits[] = [
                'id' => $bidang->id,
                'nama' => $bidang->nama,
                'seksis' => $seksis
            ];
        }

        $filterInitialId = request('unit_kerja_id');
        $filterInitialName = '';
        if ($filterInitialId) {
            $found = $unitKerjas->where('id', $filterInitialId)->first();
            if ($found) $filterInitialName = $found->nama;
        }

        $pegawaisData = [];
        foreach($pegawais as $p) {
            $pegawaisData[$p->id] = [
                'id' => $p->id,
                'nip' => $p->nip,
                'nama' => $p->nama,
                'tempat_lahir' => $p->tempat_lahir,
                'tanggal_lahir' => $p->tanggal_lahir ? $p->tanggal_lahir->format('Y-m-d') : null,
                'tanggal_lahir_formatted' => $p->tanggal_lahir ? $p->tanggal_lahir->translatedFormat('d F Y') : '-',
                'jenis_kelamin' => $p->jenis_kelamin,
                'pendidikan_terakhir' => $p->pendidikan_terakhir,
                'pangkat' => $p->pangkat,
                'golongan' => $p->golongan,
                'tmt_pangkat' => $p->tmt_pangkat ? $p->tmt_pangkat->format('Y-m-d') : null,
                'tmt_pangkat_formatted' => $p->tmt_pangkat ? $p->tmt_pangkat->translatedFormat('d F Y') : '-',
                'jabatan' => $p->jabatan,
                'tmt_jabatan' => $p->tmt_jabatan ? $p->tmt_jabatan->format('Y-m-d') : null,
                'tmt_jabatan_formatted' => $p->tmt_jabatan ? $p->tmt_jabatan->translatedFormat('d F Y') : '-',
                'unit_kerja_id' => $p->unit_kerja_id,
                'unit_kerja_nama' => $p->unitKerja ? $p->unitKerja->nama : null,
                'induk_unit_kerja_nama' => ($p->unitKerja && $p->unitKerja->parent) ? $p->unitKerja->parent->nama : null,
                'status_aktif' => (bool)$p->status_aktif,
                'diklat_url' => route('pegawai.diklat.index', $p),
                'edit_url' => route('pegawai.edit', $p),
                'show_url' => route('pegawai.show', $p),
                'destroy_url' => route('pegawai.destroy', $p),
            ];
        }
    @endphp

    <div x-data="pegawaiModalHandler({
        initialData: {{ json_encode($pegawaisData) }},
        structuredUnits: {{ json_encode($structuredUnits) }},
        csrfToken: '{{ csrf_token() }}',
        baseUrl: '{{ url('/pegawai') }}'
    })"
    style="display:flex; flex-direction:column; gap:20px;">

        @if (session('status'))
            <div style="display:flex; align-items:center; gap:10px; padding:14px 18px; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:14px; color:#065f46; font-size:14px; font-weight:500;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('status') }}
            </div>
        @endif

        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
            <div>
                <p style="font-size:13px; color:var(--text-muted, #94a3b8); margin:0;">
                    Kelola data kepegawaian Dinas Perhubungan
                </p>
            </div>
            <a href="{{ route('pegawai.create') }}"
               @click.prevent="openCreate()"
               class="btn-primary"
               style="text-decoration:none; display:inline-flex; align-items:center; gap:8px; padding:10px 18px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; border-radius:12px; font-size:13px; font-weight:600; box-shadow:0 2px 8px rgba(29,78,216,0.3); cursor:pointer;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Pegawai
            </a>
        </div>

        <div style="background:var(--card-bg, #ffffff); border-radius:20px; border:1px solid var(--border, rgba(0,0,0,0.06)); overflow:hidden;">
            <div style="padding:18px 24px; border-bottom:1px solid var(--border, rgba(0,0,0,0.05)); display:flex; justify-content:space-between; align-items:center; gap:10px; flex-wrap:wrap;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:32px; height:32px; background:rgba(37,99,235,0.1); border-radius:9px; display:flex; align-items:center; justify-content:center;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <span style="font-size:14px; font-weight:700; color:var(--text-primary, #1e293b);">Daftar Pegawai</span>
                </div>

                {{-- Filter & Search --}}
                <form action="{{ route('pegawai.index') }}" method="GET" id="filter-pegawai-form" style="display:flex; gap:10px; flex-wrap:wrap;">
                    {{-- Custom Searchable Dropdown Unit Kerja --}}
                    <div x-data="{
                        open: false,
                        search: '',
                        value: '{{ $filterInitialId }}',
                        label: '{{ addslashes($filterInitialName) }}',
                        data: {{ json_encode($structuredUnits) }},
                        direction: 'down',
                        maxListHeight: 240,

                        get filteredData() {
                            if (this.search === '') return this.data;
                            const q = this.search.toLowerCase();
                            return this.data.map(b => ({
                                ...b,
                                seksis: b.seksis.filter(s => s.nama.toLowerCase().includes(q))
                            })).filter(b => b.seksis.length > 0);
                        },

                        toggleOpen(el) {
                            if (this.open) { this.open = false; return; }
                            const rect = el.getBoundingClientRect();
                            const chrome = 110;
                            const below = window.innerHeight - rect.bottom;
                            const above = rect.top;
                            if (below >= 200 && below >= above) {
                                this.direction = 'down';
                                this.maxListHeight = Math.max(80, Math.min(240, below - chrome));
                            } else {
                                this.direction = 'up';
                                this.maxListHeight = Math.max(80, Math.min(240, above - chrome));
                            }
                            this.open = true;
                        },

                        selectSeksi(id, nama) {
                            this.value = id;
                            this.label = nama;
                            this.open = false;
                            this.search = '';
                            this.$nextTick(() => document.getElementById('filter-pegawai-form').submit());
                        },

                        clearFilter() {
                            this.value = '';
                            this.label = '';
                            this.search = '';
                            this.open = false;
                            this.$nextTick(() => document.getElementById('filter-pegawai-form').submit());
                        }
                    }" style="position:relative; width:200px;">

                        <input type="hidden" name="unit_kerja_id" :value="value">

                        <button type="button"
                                @click="toggleOpen($el)"
                                @click.outside="open = false"
                                style="display:flex; justify-content:space-between; align-items:center; width:100%; text-align:left; background:var(--card-bg, #fff); cursor:pointer; height:34px; padding:0 10px; border:1px solid var(--border, #e2e8f0); border-radius:8px; font-size:13px; gap:6px;">
                            <span x-text="label || 'Semua Unit Kerja'"
                                  :style="!label ? 'color:var(--text-muted, #94a3b8); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:150px;' : 'color:var(--text-primary, #1e293b); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:150px;'"></span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; color:var(--text-muted, #64748b);"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>

                        <div x-show="open"
                             x-transition.opacity
                             :style="direction === 'up'
                                 ? 'display:flex; flex-direction:column-reverse; position:absolute; z-index:50; width:280px; bottom:calc(100% + 4px); left:0; background:var(--card-bg, #fff); border:1px solid var(--border, #e2e8f0); border-radius:8px; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1),0 4px 6px -2px rgba(0,0,0,0.05); overflow:hidden;'
                                 : 'display:flex; flex-direction:column; position:absolute; z-index:50; width:280px; top:calc(100% + 4px); left:0; background:var(--card-bg, #fff); border:1px solid var(--border, #e2e8f0); border-radius:8px; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1),0 4px 6px -2px rgba(0,0,0,0.05); overflow:hidden;'"
                             style="display:none;">

                            <div style="padding:10px; border-bottom:1px solid var(--border, #e2e8f0); background:var(--page-bg, #f8fafc); flex-shrink:0;">
                                <div style="position:relative;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute; left:10px; top:9px; color:var(--text-muted, #94a3b8);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                    <input type="text"
                                           x-model="search"
                                           placeholder="Cari unit kerja..."
                                           class="form-control"
                                           style="width:100%; padding:7px 10px 7px 32px; border:1px solid var(--border, #cbd5e1); border-radius:6px; font-size:13px; outline:none; box-sizing:border-box; background:var(--card-bg, #fff);"
                                           @click.stop>
                                </div>
                            </div>

                            <div :style="'overflow-y:auto; max-height:' + maxListHeight + 'px; padding:6px 0 10px 0; background:var(--card-bg, #fff);'">
                                <template x-if="filteredData.length === 0">
                                    <div style="padding:14px; font-size:13px; color:var(--text-muted, #64748b); text-align:center;">
                                        Unit kerja tidak ditemukan.
                                    </div>
                                </template>
                                <template x-for="bidang in filteredData" :key="bidang.id">
                                    <div>
                                        <div style="padding:7px 14px; font-size:11px; font-weight:700; color:var(--text-muted, #64748b); background:var(--page-bg, #f8fafc); text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid var(--border-light, #f1f5f9);" x-text="bidang.nama"></div>
                                        <template x-for="seksi in bidang.seksis" :key="seksi.id">
                                            <div @click="selectSeksi(seksi.id, seksi.nama)"
                                                 style="padding:9px 14px 9px 22px; font-size:13px; color:var(--text-primary, #334155); cursor:pointer; display:flex; align-items:center; gap:7px;"
                                                 class="sipas-dropdown-row">
                                                <span x-text="seksi.nama" style="flex:1;"></span>
                                                <svg x-show="value == seksi.id" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--tc-accent, #2563eb)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>

                            <div style="padding:7px 14px; border-top:1px solid var(--border, #e2e8f0); background:var(--page-bg, #f8fafc); flex-shrink:0;">
                                <button type="button"
                                        @click.stop="clearFilter()"
                                        :disabled="!value"
                                        :style="!value ? 'opacity:0.4; cursor:not-allowed;' : 'cursor:pointer;'"
                                        style="display:flex; align-items:center; gap:5px; color:#ef4444; font-size:12px; font-weight:600; background:none; border:none; padding:3px 0; outline:none; width:100%;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    Hapus Pilihan
                                </button>
                            </div>
                        </div>
                    </div>

                    <div style="position:relative;">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIP/Nama..." class="form-control" style="width:180px; font-size:13px; padding:6px 12px; padding-left:32px; border-radius:8px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position:absolute; left:10px; top:50%; transform:translateY(-50%);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </div>
                    <button type="submit" style="padding:6px 14px; background:#f1f5f9; color:#475569; font-size:13px; font-weight:600; border-radius:8px; border:1px solid #e2e8f0; cursor:pointer;">Cari</button>
                    @if(request()->hasAny(['search', 'unit_kerja_id']))
                        <a href="{{ route('pegawai.index') }}" style="padding:6px 14px; background:#fff1f2; color:#be123c; font-size:13px; font-weight:600; border-radius:8px; border:1px solid #fecdd3; cursor:pointer; text-decoration:none; display:flex; align-items:center;">Reset</a>
                    @endif
                </form>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:60px;">No.</th>
                        <th>Pegawai</th>
                        <th>Kepangkatan</th>
                        <th>Jabatan & Unit Kerja</th>
                        <th>Status</th>
                        <th style="text-align:right; padding-right:24px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="pegawai-table-body">
                    @forelse ($pegawais as $item)
                        <tr id="pegawai-row-{{ $item->id }}">
                            <td class="pegawai-row-number" style="color:#94a3b8; font-size:13px;">{{ $loop->iteration + $pegawais->firstItem() - 1 }}.</td>
                            <td>
                                <div style="display:flex; flex-direction:column;">
                                    <span class="pegawai-row-nama" style="font-weight:600; color:var(--text-primary, #1e293b); font-size:14px;">{{ $item->nama }}</span>
                                    <span class="pegawai-row-nip" style="font-family:monospace; color:var(--text-muted, #64748b); font-size:12px;">NIP: {{ $item->nip }}</span>
                                </div>
                            </td>
                            <td>
                                <div style="display:flex; flex-direction:column;">
                                    <span class="pegawai-row-pangkat" style="font-weight:500; color:var(--text-primary, #334155); font-size:13px;">{{ $item->pangkat ?? '-' }}</span>
                                    <span class="pegawai-row-golongan" style="color:var(--text-muted, #64748b); font-size:12px;">Gol. {{ $item->golongan ?? '-' }}</span>
                                </div>
                            </td>
                            <td>
                                <div style="display:flex; flex-direction:column;">
                                    <span class="pegawai-row-jabatan" style="font-weight:500; color:var(--text-primary, #334155); font-size:13px;">{{ $item->jabatan ?? '-' }}</span>
                                    <span class="pegawai-row-unit" style="color:var(--text-muted, #64748b); font-size:12px;">{{ $item->unitKerja ? $item->unitKerja->nama : 'Belum diatur' }}</span>
                                    @if($item->unitKerja && $item->unitKerja->parent)
                                        <span class="pegawai-row-parent" style="font-size:11px; color:var(--text-muted, #94a3b8); margin-top:2px;">{{ $item->unitKerja->parent->nama }}</span>
                                    @else
                                        <span class="pegawai-row-parent" style="font-size:11px; color:var(--text-muted, #94a3b8); margin-top:2px; display:none;"></span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="pegawai-row-status">
                                    @if($item->status_aktif)
                                        <span style="display:inline-flex; align-items:center; padding:3px 8px; background:#ecfdf5; color:#059669; font-size:11px; font-weight:700; border-radius:6px; letter-spacing:0.5px; text-transform:uppercase;">Aktif</span>
                                    @else
                                        <span style="display:inline-flex; align-items:center; padding:3px 8px; background:#f1f5f9; color:#475569; font-size:11px; font-weight:700; border-radius:6px; letter-spacing:0.5px; text-transform:uppercase;">Non-Aktif</span>
                                    @endif
                                </span>
                            </td>
                            <td style="text-align:right; padding-right:20px;">
                                <x-action-group>
                                    <x-action-btn type="view" url="{{ route('pegawai.show', $item) }}" @click.prevent="openDetail({{ $item->id }})" />
                                    <x-action-btn type="edit" url="{{ route('pegawai.edit', $item) }}" @click.prevent="openEdit({{ $item->id }})" />
                                    <x-action-delete action="{{ route('pegawai.destroy', $item) }}" confirmMessage="Yakin ingin menghapus pegawai ini?" />
                                </x-action-group>
                            </td>
                        </tr>
                    @empty
                        <tr id="empty-pegawai-row">
                            <td colspan="6" style="text-align:center; padding:48px 24px; color:#94a3b8;">
                                <p style="margin:0; font-size:14px; font-weight:500;">Belum ada data pegawai</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($pegawais->hasPages())
                <div style="padding:14px 24px; border-top:1px solid var(--border, rgba(0,0,0,0.05));">
                    {{ $pegawais->links() }}
                </div>
            @endif
        </div>

        {{-- ══════════════════════════════════════════════
           MODAL TAMBAH PEGAWAI
        ══════════════════════════════════════════════ --}}
        <div x-show="createModalOpen"
             x-transition.opacity.duration.200ms
             class="sipas-modal-overlay"
             @click.self="closeCreate()"
             @keydown.escape.window="closeCreate()"
             style="display: none;"
             role="dialog"
             aria-modal="true"
             aria-labelledby="modal-create-title">

            <div class="sipas-modal-dialog" style="max-width: 720px;" @click.stop>
                {{-- Header --}}
                <div class="sipas-modal-header">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:34px; height:34px; background:rgba(37,99,235,0.1); border-radius:8px; display:flex; align-items:center; justify-content:center; color:#2563eb; flex-shrink:0;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/>
                            </svg>
                        </div>
                        <div>
                            <h3 id="modal-create-title" style="font-size:15px; font-weight:700; color:var(--text-primary, #1e293b); margin:0; line-height:1.2;">Tambah Pegawai</h3>
                            <p style="font-size:12px; color:var(--text-muted, #64748b); margin:2px 0 0 0;">Isi formulir di bawah ini dengan lengkap dan benar</p>
                        </div>
                    </div>
                    <button type="button" @click="closeCreate()" class="sipas-modal-close" title="Tutup modal" aria-label="Tutup">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>

                {{-- Form / Body --}}
                <form @submit.prevent="submitCreate()" style="display:flex; flex-direction:column; flex:1; overflow:hidden;">
                    <div class="sipas-modal-body">
                        {{-- General Error Banner --}}
                        <div x-show="createGeneralError"
                             style="margin-bottom:16px; padding:12px 16px; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; color:#991b1b; font-size:13px; display:flex; align-items:center; gap:8px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span x-text="createGeneralError"></span>
                        </div>

                        {{-- Fields --}}
                        <div style="display:flex; flex-direction:column; gap:16px;">
                            {{-- Row 1: NIP & Nama --}}
                            <div class="sipas-form-row">
                                <div class="form-group" style="flex:1;">
                                    <label class="form-label" for="create_nip">NIP (Nomor Induk Pegawai) <span style="color:#ef4444;">*</span></label>
                                    <input id="create_nip" type="text" x-model="createForm.nip" class="form-control"
                                           :class="{ 'sipas-input-error': createErrors.nip }" placeholder="198001012005011001" required>
                                    <template x-if="createErrors.nip">
                                        <p class="form-error" x-text="createErrors.nip[0]"></p>
                                    </template>
                                </div>
                                <div class="form-group" style="flex:1;">
                                    <label class="form-label" for="create_nama">Nama Lengkap (beserta gelar) <span style="color:#ef4444;">*</span></label>
                                    <input id="create_nama" type="text" x-model="createForm.nama" class="form-control"
                                           :class="{ 'sipas-input-error': createErrors.nama }" placeholder="Nama lengkap" required>
                                    <template x-if="createErrors.nama">
                                        <p class="form-error" x-text="createErrors.nama[0]"></p>
                                    </template>
                                </div>
                            </div>

                            {{-- Row 2: Tempat & Tanggal Lahir --}}
                            <div class="sipas-form-row">
                                <div class="form-group" style="flex:1;">
                                    <label class="form-label" for="create_tempat_lahir">Tempat Lahir</label>
                                    <input id="create_tempat_lahir" type="text" x-model="createForm.tempat_lahir" class="form-control"
                                           placeholder="Contoh: Jakarta">
                                    <template x-if="createErrors.tempat_lahir">
                                        <p class="form-error" x-text="createErrors.tempat_lahir[0]"></p>
                                    </template>
                                </div>
                                <div class="form-group" style="flex:1;">
                                    <label class="form-label" for="create_tanggal_lahir">Tanggal Lahir</label>
                                    <input id="create_tanggal_lahir" type="date" x-model="createForm.tanggal_lahir" class="form-control">
                                    <template x-if="createErrors.tanggal_lahir">
                                        <p class="form-error" x-text="createErrors.tanggal_lahir[0]"></p>
                                    </template>
                                </div>
                            </div>

                            {{-- Row 3: Jenis Kelamin & Pendidikan Terakhir --}}
                            <div class="sipas-form-row">
                                <div class="form-group" style="flex:1;">
                                    <label class="form-label" for="create_jenis_kelamin">Jenis Kelamin</label>
                                    <select id="create_jenis_kelamin" x-model="createForm.jenis_kelamin" class="form-control">
                                        <option value="">-- Pilih Jenis Kelamin --</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                    <template x-if="createErrors.jenis_kelamin">
                                        <p class="form-error" x-text="createErrors.jenis_kelamin[0]"></p>
                                    </template>
                                </div>
                                <div class="form-group" style="flex:1;">
                                    <label class="form-label" for="create_pendidikan_terakhir">Pendidikan Terakhir</label>
                                    <input id="create_pendidikan_terakhir" type="text" x-model="createForm.pendidikan_terakhir" class="form-control"
                                           placeholder="Contoh: S1 Teknik Informatika">
                                    <template x-if="createErrors.pendidikan_terakhir">
                                        <p class="form-error" x-text="createErrors.pendidikan_terakhir[0]"></p>
                                    </template>
                                </div>
                            </div>

                            {{-- Divider --}}
                            <div style="border-top:1px dashed var(--border, #e2e8f0); margin:4px 0;"></div>

                            {{-- Row 4: Pangkat, Golongan, Jabatan --}}
                            <div class="sipas-form-row-3">
                                <div class="form-group">
                                    <label class="form-label" for="create_pangkat">Pangkat</label>
                                    <input id="create_pangkat" type="text" x-model="createForm.pangkat" class="form-control"
                                           placeholder="Contoh: Penata Muda">
                                    <template x-if="createErrors.pangkat">
                                        <p class="form-error" x-text="createErrors.pangkat[0]"></p>
                                    </template>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="create_golongan">Golongan</label>
                                    <input id="create_golongan" type="text" x-model="createForm.golongan" class="form-control"
                                           placeholder="Contoh: III/a">
                                    <template x-if="createErrors.golongan">
                                        <p class="form-error" x-text="createErrors.golongan[0]"></p>
                                    </template>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="create_jabatan">Jabatan</label>
                                    <input id="create_jabatan" type="text" x-model="createForm.jabatan" class="form-control"
                                           placeholder="Contoh: Staf Analis">
                                    <template x-if="createErrors.jabatan">
                                        <p class="form-error" x-text="createErrors.jabatan[0]"></p>
                                    </template>
                                </div>
                            </div>

                            {{-- Row 5: TMT Pangkat & TMT Jabatan --}}
                            <div class="sipas-form-row">
                                <div class="form-group" style="flex:1;">
                                    <label class="form-label" for="create_tmt_pangkat">TMT Pangkat</label>
                                    <input id="create_tmt_pangkat" type="date" x-model="createForm.tmt_pangkat" class="form-control">
                                    <template x-if="createErrors.tmt_pangkat">
                                        <p class="form-error" x-text="createErrors.tmt_pangkat[0]"></p>
                                    </template>
                                </div>
                                <div class="form-group" style="flex:1;">
                                    <label class="form-label" for="create_tmt_jabatan">TMT Jabatan</label>
                                    <input id="create_tmt_jabatan" type="date" x-model="createForm.tmt_jabatan" class="form-control">
                                    <template x-if="createErrors.tmt_jabatan">
                                        <p class="form-error" x-text="createErrors.tmt_jabatan[0]"></p>
                                    </template>
                                </div>
                            </div>

                            {{-- Row 6: Unit Kerja & TMT --}}
                            <div class="sipas-form-row">
                                {{-- Searchable Hierarchical Dropdown --}}
                                <div class="form-group" style="flex:1;" x-data="{
                                    open: false,
                                    search: '',
                                    direction: 'down',
                                    maxListHeight: 220,

                                    get filteredData() {
                                        if (this.search === '') return structuredUnits;
                                        const q = this.search.toLowerCase();
                                        return structuredUnits.map(b => ({
                                            ...b,
                                            seksis: b.seksis.filter(s => s.nama.toLowerCase().includes(q))
                                        })).filter(b => b.seksis.length > 0);
                                    },

                                    toggle(el) {
                                        if (this.open) { this.open = false; return; }
                                        const rect = el.getBoundingClientRect();
                                        const below = window.innerHeight - rect.bottom;
                                        if (below < 240) {
                                            this.direction = 'up';
                                        } else {
                                            this.direction = 'down';
                                        }
                                        this.open = true;
                                    },

                                    select(id, nama) {
                                        createForm.unit_kerja_id = id;
                                        createForm.unit_kerja_label = nama;
                                        this.open = false;
                                        this.search = '';
                                    },

                                    clear() {
                                        createForm.unit_kerja_id = '';
                                        createForm.unit_kerja_label = '';
                                        this.open = false;
                                        this.search = '';
                                    }
                                }">
                                    <label class="form-label">Unit Kerja</label>
                                    <div style="position:relative;">
                                        <button type="button"
                                                @click="toggle($el)"
                                                @click.outside="open = false"
                                                class="form-control"
                                                style="display:flex; justify-content:space-between; align-items:center; width:100%; text-align:left; cursor:pointer;">
                                            <span x-text="createForm.unit_kerja_label || '-- Pilih Unit Kerja --'"
                                                  :style="!createForm.unit_kerja_label ? 'color:var(--text-muted, #94a3b8);' : 'color:var(--text-primary, #1e293b);'"></span>
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; color:var(--text-muted, #64748b);"><polyline points="6 9 12 15 18 9"/></svg>
                                        </button>

                                        <div x-show="open"
                                             x-transition.opacity
                                             :style="direction === 'up'
                                                 ? 'display:flex; flex-direction:column-reverse; position:absolute; z-index:60; width:100%; bottom:calc(100% + 4px); left:0; background:var(--card-bg, #fff); border:1px solid var(--border, #e2e8f0); border-radius:8px; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1); overflow:hidden;'
                                                 : 'display:flex; flex-direction:column; position:absolute; z-index:60; width:100%; top:calc(100% + 4px); left:0; background:var(--card-bg, #fff); border:1px solid var(--border, #e2e8f0); border-radius:8px; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1); overflow:hidden;'"
                                             style="display:none;">

                                            <div style="padding:8px 10px; border-bottom:1px solid var(--border, #e2e8f0); background:var(--page-bg, #f8fafc); flex-shrink:0;">
                                                <div style="position:relative;">
                                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute; left:10px; top:9px; color:var(--text-muted, #94a3b8);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                                    <input type="text"
                                                           x-model="search"
                                                           placeholder="Cari unit kerja..."
                                                           class="form-control"
                                                           style="width:100%; padding:6px 10px 6px 30px; font-size:12.5px; border-radius:6px; background:var(--card-bg, #fff);"
                                                           @click.stop>
                                                </div>
                                            </div>

                                            <div :style="'overflow-y:auto; max-height:' + maxListHeight + 'px; padding:4px 0; background:var(--card-bg, #fff);'">
                                                <template x-if="filteredData.length === 0">
                                                    <div style="padding:12px; font-size:12.5px; color:var(--text-muted, #64748b); text-align:center;">
                                                        Unit kerja tidak ditemukan.
                                                    </div>
                                                </template>
                                                <template x-for="bidang in filteredData" :key="bidang.id">
                                                    <div>
                                                        <div style="padding:6px 12px; font-size:11px; font-weight:700; color:var(--text-muted, #64748b); background:var(--page-bg, #f8fafc); text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid var(--border-light, #f1f5f9);" x-text="bidang.nama"></div>
                                                        <template x-for="seksi in bidang.seksis" :key="seksi.id">
                                                            <div @click="select(seksi.id, seksi.nama)"
                                                                 style="padding:8px 12px 8px 20px; font-size:12.5px; color:var(--text-primary, #334155); cursor:pointer; display:flex; align-items:center; gap:6px;"
                                                                 class="sipas-dropdown-row">
                                                                <span x-text="seksi.nama" style="flex:1;"></span>
                                                                <svg x-show="createForm.unit_kerja_id == seksi.id" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--tc-accent, #2563eb)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </template>
                                            </div>

                                            <div style="padding:6px 12px; border-top:1px solid var(--border, #e2e8f0); background:var(--page-bg, #f8fafc); flex-shrink:0;">
                                                <button type="button"
                                                        @click.stop="clear()"
                                                        :disabled="!createForm.unit_kerja_id"
                                                        :style="!createForm.unit_kerja_id ? 'opacity:0.4; cursor:not-allowed;' : 'cursor:pointer;'"
                                                        style="display:flex; align-items:center; gap:5px; color:#ef4444; font-size:12px; font-weight:600; background:none; border:none; padding:2px 0; outline:none; width:100%;">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                                    Hapus Pilihan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <template x-if="createErrors.unit_kerja_id">
                                        <p class="form-error" x-text="createErrors.unit_kerja_id[0]"></p>
                                    </template>
                                </div>

                                {{-- TMT (Wajib untuk data awal) --}}
                                <div class="form-group" style="flex:1;">
                                    <label class="form-label" for="create_tmt">
                                        TMT (Terhitung Mulai Tanggal)
                                        <span style="color:#ef4444;">*</span>
                                        <span style="font-size:11px; color:#ef4444; font-weight:normal; margin-left:4px;">*wajib untuk data awal</span>
                                    </label>
                                    <input id="create_tmt" type="date" x-model="createForm.tmt" class="form-control"
                                           :class="{ 'sipas-input-error': createErrors.tmt }" required>
                                    <p style="font-size:11px; color:var(--text-muted, #94a3b8); margin-top:4px;">Tanggal SK kepangkatan/jabatan terbaru.</p>
                                    <template x-if="createErrors.tmt">
                                        <p class="form-error" x-text="createErrors.tmt[0]"></p>
                                    </template>
                                </div>
                            </div>

                            {{-- Row 7: Status Aktif --}}
                            <div class="form-group" style="margin-top:2px;">
                                <label style="display:inline-flex; align-items:center; gap:8px; cursor:pointer;">
                                    <input type="checkbox" x-model="createForm.status_aktif"
                                           style="width:18px; height:18px; border-radius:4px; border:1px solid #cbd5e1; accent-color:#2563eb;">
                                    <span style="font-size:13.5px; font-weight:500; color:var(--text-primary, #1e293b);">Pegawai Aktif</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="sipas-modal-footer">
                        <button type="button" @click="closeCreate()" class="sipas-btn-secondary" :disabled="savingCreate">
                            Batal
                        </button>
                        <button type="submit" class="sipas-btn-primary" :disabled="savingCreate">
                            <svg x-show="savingCreate" class="sipas-spin" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"/><path d="M12 2a10 10 0 0 1 10 10"/></svg>
                            <svg x-show="!savingCreate" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            <span x-text="savingCreate ? 'Menyimpan...' : 'Simpan Pegawai'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
           MODAL DETAIL PEGAWAI
        ══════════════════════════════════════════════ --}}
        <div x-show="detailModalOpen"
             x-transition.opacity.duration.200ms
             class="sipas-modal-overlay"
             @click.self="closeDetail()"
             @keydown.escape.window="closeDetail()"
             style="display: none;"
             role="dialog"
             aria-modal="true"
             aria-labelledby="modal-detail-title">

            <div class="sipas-modal-dialog" style="max-width: 680px;" @click.stop>
                {{-- Header --}}
                <div class="sipas-modal-header">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:34px; height:34px; background:rgba(37,99,235,0.1); border-radius:8px; display:flex; align-items:center; justify-content:center; color:#2563eb; flex-shrink:0;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                        </div>
                        <div>
                            <h3 id="modal-detail-title" style="font-size:15px; font-weight:700; color:var(--text-primary, #1e293b); margin:0; line-height:1.2;">Detail Pegawai</h3>
                            <p style="font-size:12px; color:var(--text-muted, #64748b); margin:2px 0 0 0;">Informasi data pegawai Dinas Perhubungan</p>
                        </div>
                    </div>
                    <button type="button" @click="closeDetail()" class="sipas-modal-close" title="Tutup modal" aria-label="Tutup">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="sipas-modal-body" x-show="activePegawai">
                    <template x-if="activePegawai">
                        <div>
                            {{-- Profile Header Card --}}
                            <div style="display:flex; align-items:center; gap:16px; padding:16px; background:var(--page-bg, #f8fafc); border:1px solid var(--border, #e2e8f0); border-radius:8px; margin-bottom:20px;">
                                <div style="width:56px; height:56px; border-radius:50%; background:linear-gradient(135deg,#eff6ff,#dbeafe); border:2px solid #bfdbfe; display:flex; align-items:center; justify-content:center; color:#1d4ed8; flex-shrink:0;">
                                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                    </svg>
                                </div>
                                <div style="flex:1; min-width:0;">
                                    <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                        <h4 style="font-size:16px; font-weight:700; color:var(--text-primary, #1e293b); margin:0;" x-text="activePegawai.nama"></h4>
                                        <span x-show="activePegawai.status_aktif" style="display:inline-flex; align-items:center; padding:2px 8px; background:#ecfdf5; color:#059669; font-size:11px; font-weight:700; border-radius:6px; letter-spacing:0.5px; text-transform:uppercase;">Aktif</span>
                                        <span x-show="!activePegawai.status_aktif" style="display:inline-flex; align-items:center; padding:2px 8px; background:#f1f5f9; color:#475569; font-size:11px; font-weight:700; border-radius:6px; letter-spacing:0.5px; text-transform:uppercase;">Non-Aktif</span>
                                    </div>
                                    <p style="font-family:monospace; font-size:12.5px; color:var(--text-muted, #64748b); margin:3px 0 0 0;" x-text="'NIP: ' + activePegawai.nip"></p>
                                </div>
                            </div>

                            {{-- Section 1: Informasi Dasar --}}
                            <div style="margin-bottom:20px;">
                                <div style="display:flex; align-items:center; gap:6px; margin-bottom:10px;">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                                    <span style="font-size:13px; font-weight:700; color:var(--text-primary, #1e293b); text-transform:uppercase; letter-spacing:0.5px;">Informasi Dasar</span>
                                </div>
                                <div class="sipas-info-grid">
                                    <div class="sipas-info-box">
                                        <span class="sipas-info-label">Tempat Lahir</span>
                                        <span class="sipas-info-val" x-text="activePegawai.tempat_lahir || '-'"></span>
                                    </div>
                                    <div class="sipas-info-box">
                                        <span class="sipas-info-label">Tanggal Lahir</span>
                                        <span class="sipas-info-val" x-text="activePegawai.tanggal_lahir_formatted || '-'"></span>
                                    </div>
                                    <div class="sipas-info-box">
                                        <span class="sipas-info-label">Jenis Kelamin</span>
                                        <span class="sipas-info-val" x-text="activePegawai.jenis_kelamin || '-'"></span>
                                    </div>
                                    <div class="sipas-info-box">
                                        <span class="sipas-info-label">Pendidikan Terakhir</span>
                                        <span class="sipas-info-val" x-text="activePegawai.pendidikan_terakhir || '-'"></span>
                                    </div>
                                </div>
                            </div>

                            {{-- Section 2: Karier & Unit Kerja --}}
                            <div style="margin-bottom:20px;">
                                <div style="display:flex; align-items:center; gap:6px; margin-bottom:10px;">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                    <span style="font-size:13px; font-weight:700; color:var(--text-primary, #1e293b); text-transform:uppercase; letter-spacing:0.5px;">Informasi Karier & Organisasi</span>
                                </div>
                                <div class="sipas-info-grid">
                                    <div class="sipas-info-box">
                                        <span class="sipas-info-label">Unit Kerja</span>
                                        <span class="sipas-info-val" x-text="activePegawai.unit_kerja_nama || 'Belum diatur'"></span>
                                    </div>
                                    <div class="sipas-info-box">
                                        <span class="sipas-info-label">Induk Unit Kerja</span>
                                        <span class="sipas-info-val" x-text="activePegawai.induk_unit_kerja_nama || '-'"></span>
                                    </div>
                                    <div class="sipas-info-box">
                                        <span class="sipas-info-label">Jabatan</span>
                                        <span class="sipas-info-val" x-text="activePegawai.jabatan || '-'"></span>
                                    </div>
                                    <div class="sipas-info-box">
                                        <span class="sipas-info-label">TMT Jabatan</span>
                                        <span class="sipas-info-val" x-text="activePegawai.tmt_jabatan_formatted || '-'"></span>
                                    </div>
                                    <div class="sipas-info-box">
                                        <span class="sipas-info-label">Pangkat</span>
                                        <span class="sipas-info-val" x-text="activePegawai.pangkat || '-'"></span>
                                    </div>
                                    <div class="sipas-info-box">
                                        <span class="sipas-info-label">Golongan</span>
                                        <span class="sipas-info-val" x-text="activePegawai.golongan || '-'"></span>
                                    </div>
                                    <div class="sipas-info-box">
                                        <span class="sipas-info-label">TMT Pangkat</span>
                                        <span class="sipas-info-val" x-text="activePegawai.tmt_pangkat_formatted || '-'"></span>
                                    </div>
                                    <div class="sipas-info-box">
                                        <span class="sipas-info-label">Status Kepegawaian</span>
                                        <span class="sipas-info-val" x-text="activePegawai.status_aktif ? 'Aktif Bekerja' : 'Non-Aktif'"></span>
                                    </div>
                                </div>
                            </div>

                            {{-- Diklat & Link --}}
                            <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 16px; background:var(--page-bg, #f8fafc); border:1px solid var(--border, #e2e8f0); border-radius:8px;">
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                                    <span style="font-size:13px; font-weight:600; color:var(--text-primary, #1e293b);">Riwayat Diklat</span>
                                    <span x-show="diklatCount > 0" style="padding:1px 7px; background:#dcfce7; color:#15803d; font-size:11px; font-weight:700; border-radius:12px;" x-text="diklatCount"></span>
                                </div>
                                <a :href="activePegawai.diklat_url" style="font-size:12px; font-weight:600; color:#2563eb; text-decoration:none; display:inline-flex; align-items:center; gap:4px;">
                                    Kelola Diklat
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                                </a>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Footer --}}
                <div class="sipas-modal-footer">
                    <button type="button" @click="closeDetail()" class="sipas-btn-secondary">
                        Tutup
                    </button>
                    <button type="button" @click="editFromDetail()" class="sipas-btn-primary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        Edit Pegawai
                    </button>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
           MODAL EDIT PEGAWAI
        ══════════════════════════════════════════════ --}}
        <div x-show="editModalOpen"
             x-transition.opacity.duration.200ms
             class="sipas-modal-overlay"
             @click.self="closeEdit()"
             @keydown.escape.window="closeEdit()"
             style="display: none;"
             role="dialog"
             aria-modal="true"
             aria-labelledby="modal-edit-title">

            <div class="sipas-modal-dialog" style="max-width: 720px;" @click.stop>
                {{-- Header --}}
                <div class="sipas-modal-header">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:34px; height:34px; background:rgba(16,185,129,0.1); border-radius:8px; display:flex; align-items:center; justify-content:center; color:#059669; flex-shrink:0;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 id="modal-edit-title" style="font-size:15px; font-weight:700; color:var(--text-primary, #1e293b); margin:0; line-height:1.2;">Edit Data Pegawai</h3>
                            <p style="font-size:12px; color:var(--text-muted, #64748b); margin:2px 0 0 0;">Perbarui formulir kepegawaian di bawah ini</p>
                        </div>
                    </div>
                    <button type="button" @click="closeEdit()" class="sipas-modal-close" title="Tutup modal" aria-label="Tutup">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>

                {{-- Form / Body --}}
                <form @submit.prevent="submitEdit()" style="display:flex; flex-direction:column; flex:1; overflow:hidden;">
                    <div class="sipas-modal-body">
                        {{-- General Error Banner --}}
                        <div x-show="editGeneralError"
                             style="margin-bottom:16px; padding:12px 16px; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; color:#991b1b; font-size:13px; display:flex; align-items:center; gap:8px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span x-text="editGeneralError"></span>
                        </div>

                        {{-- Fields --}}
                        <div style="display:flex; flex-direction:column; gap:16px;">
                            {{-- Row 1: NIP & Nama --}}
                            <div class="sipas-form-row">
                                <div class="form-group" style="flex:1;">
                                    <label class="form-label" for="modal_nip">NIP (Nomor Induk Pegawai) <span style="color:#ef4444;">*</span></label>
                                    <input id="modal_nip" type="text" x-model="editForm.nip" class="form-control"
                                           :class="{ 'sipas-input-error': editErrors.nip }" placeholder="198001012005011001" required>
                                    <template x-if="editErrors.nip">
                                        <p class="form-error" x-text="editErrors.nip[0]"></p>
                                    </template>
                                </div>
                                <div class="form-group" style="flex:1;">
                                    <label class="form-label" for="modal_nama">Nama Lengkap (beserta gelar) <span style="color:#ef4444;">*</span></label>
                                    <input id="modal_nama" type="text" x-model="editForm.nama" class="form-control"
                                           :class="{ 'sipas-input-error': editErrors.nama }" placeholder="Nama lengkap" required>
                                    <template x-if="editErrors.nama">
                                        <p class="form-error" x-text="editErrors.nama[0]"></p>
                                    </template>
                                </div>
                            </div>

                            {{-- Row 2: Tempat & Tanggal Lahir --}}
                            <div class="sipas-form-row">
                                <div class="form-group" style="flex:1;">
                                    <label class="form-label" for="modal_tempat_lahir">Tempat Lahir</label>
                                    <input id="modal_tempat_lahir" type="text" x-model="editForm.tempat_lahir" class="form-control"
                                           placeholder="Contoh: Jakarta">
                                    <template x-if="editErrors.tempat_lahir">
                                        <p class="form-error" x-text="editErrors.tempat_lahir[0]"></p>
                                    </template>
                                </div>
                                <div class="form-group" style="flex:1;">
                                    <label class="form-label" for="modal_tanggal_lahir">Tanggal Lahir</label>
                                    <input id="modal_tanggal_lahir" type="date" x-model="editForm.tanggal_lahir" class="form-control">
                                    <template x-if="editErrors.tanggal_lahir">
                                        <p class="form-error" x-text="editErrors.tanggal_lahir[0]"></p>
                                    </template>
                                </div>
                            </div>

                            {{-- Row 3: Jenis Kelamin & Pendidikan Terakhir --}}
                            <div class="sipas-form-row">
                                <div class="form-group" style="flex:1;">
                                    <label class="form-label" for="modal_jenis_kelamin">Jenis Kelamin</label>
                                    <select id="modal_jenis_kelamin" x-model="editForm.jenis_kelamin" class="form-control">
                                        <option value="">-- Pilih Jenis Kelamin --</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                    <template x-if="editErrors.jenis_kelamin">
                                        <p class="form-error" x-text="editErrors.jenis_kelamin[0]"></p>
                                    </template>
                                </div>
                                <div class="form-group" style="flex:1;">
                                    <label class="form-label" for="modal_pendidikan_terakhir">Pendidikan Terakhir</label>
                                    <input id="modal_pendidikan_terakhir" type="text" x-model="editForm.pendidikan_terakhir" class="form-control"
                                           placeholder="Contoh: S1 Teknik Informatika">
                                    <template x-if="editErrors.pendidikan_terakhir">
                                        <p class="form-error" x-text="editErrors.pendidikan_terakhir[0]"></p>
                                    </template>
                                </div>
                            </div>

                            {{-- Divider --}}
                            <div style="border-top:1px dashed var(--border, #e2e8f0); margin:4px 0;"></div>

                            {{-- Row 4: Pangkat, Golongan, Jabatan --}}
                            <div class="sipas-form-row-3">
                                <div class="form-group">
                                    <label class="form-label" for="modal_pangkat">Pangkat</label>
                                    <input id="modal_pangkat" type="text" x-model="editForm.pangkat" class="form-control"
                                           placeholder="Contoh: Penata Muda">
                                    <template x-if="editErrors.pangkat">
                                        <p class="form-error" x-text="editErrors.pangkat[0]"></p>
                                    </template>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="modal_golongan">Golongan</label>
                                    <input id="modal_golongan" type="text" x-model="editForm.golongan" class="form-control"
                                           placeholder="Contoh: III/a">
                                    <template x-if="editErrors.golongan">
                                        <p class="form-error" x-text="editErrors.golongan[0]"></p>
                                    </template>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="modal_jabatan">Jabatan</label>
                                    <input id="modal_jabatan" type="text" x-model="editForm.jabatan" class="form-control"
                                           placeholder="Contoh: Staf Analis">
                                    <template x-if="editErrors.jabatan">
                                        <p class="form-error" x-text="editErrors.jabatan[0]"></p>
                                    </template>
                                </div>
                            </div>

                            {{-- Row 5: TMT Pangkat & TMT Jabatan --}}
                            <div class="sipas-form-row">
                                <div class="form-group" style="flex:1;">
                                    <label class="form-label" for="modal_tmt_pangkat">TMT Pangkat</label>
                                    <input id="modal_tmt_pangkat" type="date" x-model="editForm.tmt_pangkat" class="form-control">
                                    <template x-if="editErrors.tmt_pangkat">
                                        <p class="form-error" x-text="editErrors.tmt_pangkat[0]"></p>
                                    </template>
                                </div>
                                <div class="form-group" style="flex:1;">
                                    <label class="form-label" for="modal_tmt_jabatan">TMT Jabatan</label>
                                    <input id="modal_tmt_jabatan" type="date" x-model="editForm.tmt_jabatan" class="form-control">
                                    <template x-if="editErrors.tmt_jabatan">
                                        <p class="form-error" x-text="editErrors.tmt_jabatan[0]"></p>
                                    </template>
                                </div>
                            </div>

                            {{-- Row 6: Unit Kerja & TMT --}}
                            <div class="sipas-form-row">
                                {{-- Searchable Hierarchical Dropdown --}}
                                <div class="form-group" style="flex:1;" x-data="{
                                    open: false,
                                    search: '',
                                    direction: 'down',
                                    maxListHeight: 220,

                                    get filteredData() {
                                        if (this.search === '') return structuredUnits;
                                        const q = this.search.toLowerCase();
                                        return structuredUnits.map(b => ({
                                            ...b,
                                            seksis: b.seksis.filter(s => s.nama.toLowerCase().includes(q))
                                        })).filter(b => b.seksis.length > 0);
                                    },

                                    toggle(el) {
                                        if (this.open) { this.open = false; return; }
                                        const rect = el.getBoundingClientRect();
                                        const below = window.innerHeight - rect.bottom;
                                        if (below < 240) {
                                            this.direction = 'up';
                                        } else {
                                            this.direction = 'down';
                                        }
                                        this.open = true;
                                    },

                                    select(id, nama) {
                                        editForm.unit_kerja_id = id;
                                        editForm.unit_kerja_label = nama;
                                        this.open = false;
                                        this.search = '';
                                    },

                                    clear() {
                                        editForm.unit_kerja_id = '';
                                        editForm.unit_kerja_label = '';
                                        this.open = false;
                                        this.search = '';
                                    }
                                }">
                                    <label class="form-label">Unit Kerja</label>
                                    <div style="position:relative;">
                                        <button type="button"
                                                @click="toggle($el)"
                                                @click.outside="open = false"
                                                class="form-control"
                                                style="display:flex; justify-content:space-between; align-items:center; width:100%; text-align:left; cursor:pointer;">
                                            <span x-text="editForm.unit_kerja_label || '-- Pilih Unit Kerja --'"
                                                  :style="!editForm.unit_kerja_label ? 'color:var(--text-muted, #94a3b8);' : 'color:var(--text-primary, #1e293b);'"></span>
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; color:var(--text-muted, #64748b);"><polyline points="6 9 12 15 18 9"/></svg>
                                        </button>

                                        <div x-show="open"
                                             x-transition.opacity
                                             :style="direction === 'up'
                                                 ? 'display:flex; flex-direction:column-reverse; position:absolute; z-index:60; width:100%; bottom:calc(100% + 4px); left:0; background:var(--card-bg, #fff); border:1px solid var(--border, #e2e8f0); border-radius:8px; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1); overflow:hidden;'
                                                 : 'display:flex; flex-direction:column; position:absolute; z-index:60; width:100%; top:calc(100% + 4px); left:0; background:var(--card-bg, #fff); border:1px solid var(--border, #e2e8f0); border-radius:8px; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1); overflow:hidden;'"
                                             style="display:none;">

                                            <div style="padding:8px 10px; border-bottom:1px solid var(--border, #e2e8f0); background:var(--page-bg, #f8fafc); flex-shrink:0;">
                                                <div style="position:relative;">
                                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute; left:10px; top:9px; color:var(--text-muted, #94a3b8);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                                    <input type="text"
                                                           x-model="search"
                                                           placeholder="Cari unit kerja..."
                                                           class="form-control"
                                                           style="width:100%; padding:6px 10px 6px 30px; font-size:12.5px; border-radius:6px; background:var(--card-bg, #fff);"
                                                           @click.stop>
                                                </div>
                                            </div>

                                            <div :style="'overflow-y:auto; max-height:' + maxListHeight + 'px; padding:4px 0; background:var(--card-bg, #fff);'">
                                                <template x-if="filteredData.length === 0">
                                                    <div style="padding:12px; font-size:12.5px; color:var(--text-muted, #64748b); text-align:center;">
                                                        Unit kerja tidak ditemukan.
                                                    </div>
                                                </template>
                                                <template x-for="bidang in filteredData" :key="bidang.id">
                                                    <div>
                                                        <div style="padding:6px 12px; font-size:11px; font-weight:700; color:var(--text-muted, #64748b); background:var(--page-bg, #f8fafc); text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid var(--border-light, #f1f5f9);" x-text="bidang.nama"></div>
                                                        <template x-for="seksi in bidang.seksis" :key="seksi.id">
                                                            <div @click="select(seksi.id, seksi.nama)"
                                                                 style="padding:8px 12px 8px 20px; font-size:12.5px; color:var(--text-primary, #334155); cursor:pointer; display:flex; align-items:center; gap:6px;"
                                                                 class="sipas-dropdown-row">
                                                                <span x-text="seksi.nama" style="flex:1;"></span>
                                                                <svg x-show="editForm.unit_kerja_id == seksi.id" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--tc-accent, #2563eb)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </template>
                                            </div>

                                            <div style="padding:6px 12px; border-top:1px solid var(--border, #e2e8f0); background:var(--page-bg, #f8fafc); flex-shrink:0;">
                                                <button type="button"
                                                        @click.stop="clear()"
                                                        :disabled="!editForm.unit_kerja_id"
                                                        :style="!editForm.unit_kerja_id ? 'opacity:0.4; cursor:not-allowed;' : 'cursor:pointer;'"
                                                        style="display:flex; align-items:center; gap:5px; color:#ef4444; font-size:12px; font-weight:600; background:none; border:none; padding:2px 0; outline:none; width:100%;">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                                    Hapus Pilihan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <template x-if="editErrors.unit_kerja_id">
                                        <p class="form-error" x-text="editErrors.unit_kerja_id[0]"></p>
                                    </template>
                                </div>

                                {{-- TMT --}}
                                <div class="form-group" style="flex:1;">
                                    <label class="form-label" for="modal_tmt">
                                        TMT Perubahan
                                        <span style="font-size:11px; color:#ef4444; font-weight:normal; margin-left:4px;">*wajib jika ubah karier</span>
                                    </label>
                                    <input id="modal_tmt" type="date" x-model="editForm.tmt" class="form-control">
                                    <p style="font-size:11px; color:var(--text-muted, #94a3b8); margin-top:4px;">Tanggal SK kepangkatan/jabatan terbaru.</p>
                                    <template x-if="editErrors.tmt">
                                        <p class="form-error" x-text="editErrors.tmt[0]"></p>
                                    </template>
                                </div>
                            </div>

                            {{-- Row 7: Status Aktif --}}
                            <div class="form-group" style="margin-top:2px;">
                                <label style="display:inline-flex; align-items:center; gap:8px; cursor:pointer;">
                                    <input type="checkbox" x-model="editForm.status_aktif"
                                           style="width:18px; height:18px; border-radius:4px; border:1px solid #cbd5e1; accent-color:#2563eb;">
                                    <span style="font-size:13.5px; font-weight:500; color:var(--text-primary, #1e293b);">Pegawai Aktif</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="sipas-modal-footer">
                        <button type="button" @click="closeEdit()" class="sipas-btn-secondary" :disabled="saving">
                            Batal
                        </button>
                        <button type="submit" class="sipas-btn-primary" :disabled="saving">
                            <svg x-show="saving" class="sipas-spin" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"/><path d="M12 2a10 10 0 0 1 10 10"/></svg>
                            <svg x-show="!saving" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            <span x-text="saving ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
           TOAST NOTIFICATION
        ══════════════════════════════════════════════ --}}
        <div x-show="toast.show"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform translate-y-2"
             class="sipas-toast"
             :class="'sipas-toast-' + toast.type"
             style="display:none;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            <span x-text="toast.message" style="font-size:13.5px; font-weight:600;"></span>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════
       MODAL STYLES (Light & Dark Mode, Responsive)
    ══════════════════════════════════════════════ --}}
    <style>
    .sipas-modal-overlay {
        position: fixed;
        inset: 0;
        background-color: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(3px);
        z-index: 9998;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        overflow-y: auto;
    }

    .sipas-modal-dialog {
        position: relative;
        width: 100%;
        background-color: var(--card-bg, #ffffff);
        color: var(--text-primary, #1e293b);
        border: 1px solid var(--border, #e2e8f0);
        border-radius: 8px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        max-height: calc(100vh - 48px);
        overflow: hidden;
        z-index: 9999;
    }

    .sipas-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border-bottom: 1px solid var(--border, #e2e8f0);
        background-color: var(--card-bg, #ffffff);
        flex-shrink: 0;
    }

    .sipas-modal-close {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: 1px solid transparent;
        border-radius: 6px;
        color: var(--text-muted, #64748b);
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .sipas-modal-close:hover {
        background: var(--page-bg, #f1f5f9);
        color: var(--text-primary, #1e293b);
        border-color: var(--border, #e2e8f0);
    }

    .sipas-modal-body {
        padding: 20px;
        overflow-y: auto;
        flex: 1;
    }

    .sipas-modal-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        padding: 14px 20px;
        border-top: 1px solid var(--border, #e2e8f0);
        background-color: var(--page-bg, #f8fafc);
        flex-shrink: 0;
    }

    .sipas-btn-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 9px 18px;
        background: var(--tc-accent, #2563eb);
        color: #ffffff;
        font-size: 13px;
        font-weight: 600;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: opacity 0.15s, background-color 0.15s;
        text-decoration: none;
    }
    .sipas-btn-primary:hover:not(:disabled) {
        opacity: 0.92;
    }
    .sipas-btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .sipas-btn-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 9px 18px;
        background: var(--card-bg, #ffffff);
        color: var(--text-primary, #334155);
        font-size: 13px;
        font-weight: 600;
        border-radius: 8px;
        border: 1px solid var(--border, #cbd5e1);
        cursor: pointer;
        transition: background 0.15s, border-color 0.15s;
        text-decoration: none;
    }
    .sipas-btn-secondary:hover:not(:disabled) {
        background: var(--page-bg, #f8fafc);
        border-color: var(--border, #94a3b8);
    }
    .sipas-btn-secondary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .sipas-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    .sipas-info-box {
        padding: 10px 14px;
        background: var(--page-bg, #f8fafc);
        border: 1px solid var(--border, #e2e8f0);
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .sipas-info-label {
        font-size: 11px;
        color: var(--text-muted, #64748b);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    .sipas-info-val {
        font-size: 13.5px;
        color: var(--text-primary, #1e293b);
        font-weight: 600;
    }

    .sipas-form-row {
        display: flex;
        gap: 16px;
    }
    .sipas-form-row-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }
    .sipas-input-error {
        border-color: #ef4444 !important;
    }

    .sipas-dropdown-row:hover {
        background: rgba(37, 99, 235, 0.05);
    }

    .sipas-toast {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 10000;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 20px;
        border-radius: 8px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15);
    }
    .sipas-toast-success {
        background: #ecfdf5;
        border: 1px solid #6ee7b7;
        color: #065f46;
    }
    .sipas-toast-error {
        background: #fef2f2;
        border: 1px solid #fca5a5;
        color: #991b1b;
    }

    .sipas-spin {
        animation: sipasSpin 0.8s linear infinite;
    }
    @keyframes sipasSpin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* Dark Mode Overrides */
    [data-theme="dark"] .sipas-modal-dialog {
        background-color: #1e293b !important;
        border-color: #334155 !important;
        color: #e2e8f0 !important;
    }
    [data-theme="dark"] .sipas-modal-header {
        background-color: #1e293b !important;
        border-bottom-color: #334155 !important;
    }
    [data-theme="dark"] .sipas-modal-footer {
        background-color: #162032 !important;
        border-top-color: #334155 !important;
    }
    [data-theme="dark"] .sipas-modal-close:hover {
        background: #334155 !important;
        color: #e2e8f0 !important;
    }
    [data-theme="dark"] .sipas-info-box {
        background-color: #162032 !important;
        border-color: #334155 !important;
    }
    [data-theme="dark"] .sipas-info-label {
        color: #94a3b8 !important;
    }
    [data-theme="dark"] .sipas-info-val {
        color: #f1f5f9 !important;
    }
    [data-theme="dark"] .sipas-btn-secondary {
        background: #1e293b !important;
        border-color: #334155 !important;
        color: #cbd5e1 !important;
    }
    [data-theme="dark"] .sipas-btn-secondary:hover:not(:disabled) {
        background: #27354a !important;
        border-color: #475569 !important;
    }
    [data-theme="dark"] .sipas-dropdown-row:hover {
        background: #1e3a5c !important;
    }
    [data-theme="dark"] .sipas-toast-success {
        background: #064e3b !important;
        border-color: #059669 !important;
        color: #a7f3d0 !important;
    }

    @media (max-width: 640px) {
        .sipas-form-row {
            flex-direction: column;
            gap: 12px;
        }
        .sipas-form-row-3 {
            grid-template-columns: 1fr;
            gap: 12px;
        }
        .sipas-info-grid {
            grid-template-columns: 1fr;
        }
        .sipas-modal-dialog {
            max-height: calc(100vh - 24px);
        }
        .sipas-modal-header {
            padding: 14px 16px;
        }
        .sipas-modal-body {
            padding: 16px;
        }
        .sipas-modal-footer {
            padding: 12px 16px;
            flex-direction: column-reverse;
        }
        .sipas-btn-primary, .sipas-btn-secondary {
            width: 100%;
        }
    }
    </style>

    {{-- ══════════════════════════════════════════════
       ALPINE.JS MODAL HANDLER
    ══════════════════════════════════════════════ --}}
    <script>
    function pegawaiModalHandler(config) {
        return {
            pegawaisMap: config.initialData || {},
            structuredUnits: config.structuredUnits || [],
            csrfToken: config.csrfToken,
            baseUrl: config.baseUrl,

            createModalOpen: false,
            detailModalOpen: false,
            editModalOpen: false,

            activePegawai: null,
            diklatCount: 0,

            createForm: {
                nip: '',
                nama: '',
                tempat_lahir: '',
                tanggal_lahir: '',
                jenis_kelamin: '',
                pendidikan_terakhir: '',
                pangkat: '',
                golongan: '',
                tmt_pangkat: '',
                jabatan: '',
                tmt_jabatan: '',
                unit_kerja_id: '',
                unit_kerja_label: '',
                tmt: '',
                status_aktif: true
            },
            createErrors: {},
            createGeneralError: '',
            savingCreate: false,

            editForm: {
                id: '',
                nip: '',
                nama: '',
                tempat_lahir: '',
                tanggal_lahir: '',
                jenis_kelamin: '',
                pendidikan_terakhir: '',
                pangkat: '',
                golongan: '',
                tmt_pangkat: '',
                jabatan: '',
                tmt_jabatan: '',
                unit_kerja_id: '',
                unit_kerja_label: '',
                tmt: '',
                status_aktif: true
            },
            editErrors: {},
            editGeneralError: '',
            saving: false,

            toast: {
                show: false,
                message: '',
                type: 'success',
                timer: null
            },

            init() {
                window.__pegawaiHandler = this;

                this.$watch('createModalOpen', val => this.updateBodyLock());
                this.$watch('detailModalOpen', val => this.updateBodyLock());
                this.$watch('editModalOpen', val => this.updateBodyLock());
            },

            updateBodyLock() {
                if (this.createModalOpen || this.detailModalOpen || this.editModalOpen) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            },

            openCreate() {
                this.createForm = {
                    nip: '',
                    nama: '',
                    tempat_lahir: '',
                    tanggal_lahir: '',
                    jenis_kelamin: '',
                    pendidikan_terakhir: '',
                    pangkat: '',
                    golongan: '',
                    tmt_pangkat: '',
                    jabatan: '',
                    tmt_jabatan: '',
                    unit_kerja_id: '',
                    unit_kerja_label: '',
                    tmt: '',
                    status_aktif: true
                };
                this.createErrors = {};
                this.createGeneralError = '';
                this.savingCreate = false;
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

                const formData = new FormData();
                formData.append('_token', this.csrfToken);
                formData.append('nip', this.createForm.nip);
                formData.append('nama', this.createForm.nama);
                formData.append('tempat_lahir', this.createForm.tempat_lahir || '');
                formData.append('tanggal_lahir', this.createForm.tanggal_lahir || '');
                formData.append('jenis_kelamin', this.createForm.jenis_kelamin || '');
                formData.append('pendidikan_terakhir', this.createForm.pendidikan_terakhir || '');
                formData.append('pangkat', this.createForm.pangkat || '');
                formData.append('golongan', this.createForm.golongan || '');
                formData.append('tmt_pangkat', this.createForm.tmt_pangkat || '');
                formData.append('jabatan', this.createForm.jabatan || '');
                formData.append('tmt_jabatan', this.createForm.tmt_jabatan || '');
                if (this.createForm.unit_kerja_id) {
                    formData.append('unit_kerja_id', this.createForm.unit_kerja_id);
                }
                formData.append('tmt', this.createForm.tmt || '');
                if (this.createForm.status_aktif) {
                    formData.append('status_aktif', '1');
                }

                try {
                    const response = await fetch(this.baseUrl, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    if (response.status === 422) {
                        const errorData = await response.json();
                        this.createErrors = errorData.errors || {};
                        this.createGeneralError = errorData.message || 'Mohon periksa kesalahan input pada form.';
                        this.savingCreate = false;
                        return;
                    }

                    if (response.ok || response.status === 201) {
                        let json = null;
                        try {
                            json = await response.json();
                        } catch(e) {}

                        let p = (json && json.pegawai) ? json.pegawai : null;
                        if (p) {
                            const newRecord = {
                                id: p.id,
                                nip: p.nip,
                                nama: p.nama,
                                tempat_lahir: p.tempat_lahir,
                                tanggal_lahir: p.tanggal_lahir ? p.tanggal_lahir.substring(0, 10) : null,
                                tanggal_lahir_formatted: p.tanggal_lahir ? this.formatDateDisplay(p.tanggal_lahir) : '-',
                                jenis_kelamin: p.jenis_kelamin,
                                pendidikan_terakhir: p.pendidikan_terakhir,
                                pangkat: p.pangkat,
                                golongan: p.golongan,
                                tmt_pangkat: p.tmt_pangkat ? p.tmt_pangkat.substring(0, 10) : null,
                                tmt_pangkat_formatted: p.tmt_pangkat ? this.formatDateDisplay(p.tmt_pangkat) : '-',
                                jabatan: p.jabatan,
                                tmt_jabatan: p.tmt_jabatan ? p.tmt_jabatan.substring(0, 10) : null,
                                tmt_jabatan_formatted: p.tmt_jabatan ? this.formatDateDisplay(p.tmt_jabatan) : '-',
                                unit_kerja_id: p.unit_kerja_id,
                                unit_kerja_nama: p.unit_kerja ? p.unit_kerja.nama : null,
                                induk_unit_kerja_nama: (p.unit_kerja && p.unit_kerja.parent) ? p.unit_kerja.parent.nama : null,
                                status_aktif: Boolean(p.status_aktif),
                                diklat_url: this.baseUrl + '/' + p.id + '/diklat',
                                edit_url: this.baseUrl + '/' + p.id + '/edit',
                                show_url: this.baseUrl + '/' + p.id,
                                destroy_url: this.baseUrl + '/' + p.id,
                            };

                            this.pegawaisMap[p.id] = newRecord;
                            this.insertTableRow(newRecord);
                        }

                        this.savingCreate = false;
                        this.createModalOpen = false;
                        this.showToast((json && json.message) ? json.message : 'Pegawai berhasil ditambahkan.', 'success');
                    } else {
                        this.savingCreate = false;
                        this.createGeneralError = 'Terjadi kesalahan sistem (' + response.status + '). Silakan coba lagi.';
                    }
                } catch(err) {
                    this.savingCreate = false;
                    this.createGeneralError = 'Gagal menghubungi server. Periksa koneksi internet Anda.';
                }
            },

            insertTableRow(p) {
                const tbody = document.getElementById('pegawai-table-body');
                if (!tbody) return;

                const emptyRow = document.getElementById('empty-pegawai-row');
                if (emptyRow) emptyRow.remove();

                const tr = document.createElement('tr');
                tr.id = 'pegawai-row-' + p.id;

                const statusBadge = p.status_aktif
                    ? '<span style="display:inline-flex; align-items:center; padding:3px 8px; background:#ecfdf5; color:#059669; font-size:11px; font-weight:700; border-radius:6px; letter-spacing:0.5px; text-transform:uppercase;">Aktif</span>'
                    : '<span style="display:inline-flex; align-items:center; padding:3px 8px; background:#f1f5f9; color:#475569; font-size:11px; font-weight:700; border-radius:6px; letter-spacing:0.5px; text-transform:uppercase;">Non-Aktif</span>';

                const parentBadge = p.induk_unit_kerja_nama
                    ? `<span class="pegawai-row-parent" style="font-size:11px; color:var(--text-muted, #94a3b8); margin-top:2px;">${p.induk_unit_kerja_nama}</span>`
                    : `<span class="pegawai-row-parent" style="font-size:11px; color:var(--text-muted, #94a3b8); margin-top:2px; display:none;"></span>`;

                tr.innerHTML = `
                    <td class="pegawai-row-number" style="color:#94a3b8; font-size:13px;">1.</td>
                    <td>
                        <div style="display:flex; flex-direction:column;">
                            <span class="pegawai-row-nama" style="font-weight:600; color:var(--text-primary, #1e293b); font-size:14px;">${p.nama}</span>
                            <span class="pegawai-row-nip" style="font-family:monospace; color:var(--text-muted, #64748b); font-size:12px;">NIP: ${p.nip}</span>
                        </div>
                    </td>
                    <td>
                        <div style="display:flex; flex-direction:column;">
                            <span class="pegawai-row-pangkat" style="font-weight:500; color:var(--text-primary, #334155); font-size:13px;">${p.pangkat || '-'}</span>
                            <span class="pegawai-row-golongan" style="color:var(--text-muted, #64748b); font-size:12px;">Gol. ${p.golongan || '-'}</span>
                        </div>
                    </td>
                    <td>
                        <div style="display:flex; flex-direction:column;">
                            <span class="pegawai-row-jabatan" style="font-weight:500; color:var(--text-primary, #334155); font-size:13px;">${p.jabatan || '-'}</span>
                            <span class="pegawai-row-unit" style="color:var(--text-muted, #64748b); font-size:12px;">${p.unit_kerja_nama || 'Belum diatur'}</span>
                            ${parentBadge}
                        </div>
                    </td>
                    <td>
                        <span class="pegawai-row-status">${statusBadge}</span>
                    </td>
                    <td style="text-align:right; padding-right:20px;">
                        <div style="display:flex; justify-content:flex-end; gap:6px;">
                            <a href="${p.show_url}" title="Detail" aria-label="Detail"
                               onclick="window.__pegawaiHandler.openDetail(${p.id}); return false;"
                               style="display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; background:#f8fafc; color:#3b82f6; border-radius:6px; border:1px solid #e2e8f0; text-decoration:none; transition:all 0.15s;"
                               onmouseover="this.style.background='#eff6ff'; this.style.borderColor='#bfdbfe';"
                               onmouseout="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0';">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </a>
                            <a href="${p.edit_url}" title="Edit" aria-label="Edit"
                               onclick="window.__pegawaiHandler.openEdit(${p.id}); return false;"
                               style="display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; background:#f8fafc; color:#10b981; border-radius:6px; border:1px solid #e2e8f0; text-decoration:none; transition:all 0.15s;"
                               onmouseover="this.style.background='#ecfdf5'; this.style.borderColor='#a7f3d0';"
                               onmouseout="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0';">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            <form action="${p.destroy_url}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus pegawai ini?');">
                                <input type="hidden" name="_token" value="${this.csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" title="Hapus" aria-label="Hapus"
                                        style="display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; background:#f8fafc; color:#ef4444; border-radius:6px; border:1px solid #e2e8f0; cursor:pointer; transition:all 0.15s;"
                                        onmouseover="this.style.background='#fef2f2'; this.style.borderColor='#fecaca';"
                                        onmouseout="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0';">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                `;

                tbody.insertBefore(tr, tbody.firstChild);

                // Renumber rows
                const rows = tbody.querySelectorAll('tr[id^="pegawai-row-"]');
                rows.forEach((r, idx) => {
                    const noCell = r.querySelector('.pegawai-row-number');
                    if (noCell) noCell.textContent = (idx + 1) + '.';
                });
            },

            openDetail(id) {
                const item = this.pegawaisMap[id];
                if (!item) return;

                this.activePegawai = { ...item };
                this.diklatCount = 0;
                this.detailModalOpen = true;

                fetch(this.baseUrl + '/' + id, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => {
                    if (res.ok) return res.json();
                    return null;
                })
                .then(data => {
                    if (data && data.pegawai) {
                        if (data.diklat_count !== undefined) {
                            this.diklatCount = data.diklat_count;
                        }
                    }
                })
                .catch(() => {});
            },

            closeDetail() {
                this.detailModalOpen = false;
            },

            editFromDetail() {
                if (!this.activePegawai) return;
                const id = this.activePegawai.id;
                this.closeDetail();
                this.openEdit(id);
            },

            openEdit(id) {
                const item = this.pegawaisMap[id];
                if (!item) return;

                this.editErrors = {};
                this.editGeneralError = '';
                this.saving = false;

                this.editForm = {
                    id: item.id,
                    nip: item.nip || '',
                    nama: item.nama || '',
                    tempat_lahir: item.tempat_lahir || '',
                    tanggal_lahir: item.tanggal_lahir || '',
                    jenis_kelamin: item.jenis_kelamin || '',
                    pendidikan_terakhir: item.pendidikan_terakhir || '',
                    pangkat: item.pangkat || '',
                    golongan: item.golongan || '',
                    tmt_pangkat: item.tmt_pangkat || '',
                    jabatan: item.jabatan || '',
                    tmt_jabatan: item.tmt_jabatan || '',
                    unit_kerja_id: item.unit_kerja_id ? String(item.unit_kerja_id) : '',
                    unit_kerja_label: item.unit_kerja_nama || '',
                    tmt: '',
                    status_aktif: Boolean(item.status_aktif)
                };

                this.editModalOpen = true;
            },

            closeEdit() {
                this.editModalOpen = false;
                this.editErrors = {};
                this.editGeneralError = '';
            },

            async submitEdit() {
                this.saving = true;
                this.editErrors = {};
                this.editGeneralError = '';

                const formData = new FormData();
                formData.append('_token', this.csrfToken);
                formData.append('_method', 'PUT');
                formData.append('nip', this.editForm.nip);
                formData.append('nama', this.editForm.nama);
                formData.append('tempat_lahir', this.editForm.tempat_lahir || '');
                formData.append('tanggal_lahir', this.editForm.tanggal_lahir || '');
                formData.append('jenis_kelamin', this.editForm.jenis_kelamin || '');
                formData.append('pendidikan_terakhir', this.editForm.pendidikan_terakhir || '');
                formData.append('pangkat', this.editForm.pangkat || '');
                formData.append('golongan', this.editForm.golongan || '');
                formData.append('tmt_pangkat', this.editForm.tmt_pangkat || '');
                formData.append('jabatan', this.editForm.jabatan || '');
                formData.append('tmt_jabatan', this.editForm.tmt_jabatan || '');
                if (this.editForm.unit_kerja_id) {
                    formData.append('unit_kerja_id', this.editForm.unit_kerja_id);
                }
                if (this.editForm.tmt) {
                    formData.append('tmt', this.editForm.tmt);
                }
                if (this.editForm.status_aktif) {
                    formData.append('status_aktif', '1');
                }

                try {
                    const response = await fetch(this.baseUrl + '/' + this.editForm.id, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    if (response.status === 422) {
                        const errorData = await response.json();
                        this.editErrors = errorData.errors || {};
                        this.editGeneralError = errorData.message || 'Mohon periksa kesalahan input pada form.';
                        this.saving = false;
                        return;
                    }

                    if (response.ok) {
                        let json = null;
                        try {
                            json = await response.json();
                        } catch(e) {}

                        let updatedData = {};
                        if (json && json.pegawai) {
                            const p = json.pegawai;
                            updatedData = {
                                id: p.id,
                                nip: p.nip,
                                nama: p.nama,
                                tempat_lahir: p.tempat_lahir,
                                tanggal_lahir: p.tanggal_lahir ? p.tanggal_lahir.substring(0, 10) : null,
                                jenis_kelamin: p.jenis_kelamin,
                                pendidikan_terakhir: p.pendidikan_terakhir,
                                pangkat: p.pangkat,
                                golongan: p.golongan,
                                tmt_pangkat: p.tmt_pangkat ? p.tmt_pangkat.substring(0, 10) : null,
                                jabatan: p.jabatan,
                                tmt_jabatan: p.tmt_jabatan ? p.tmt_jabatan.substring(0, 10) : null,
                                unit_kerja_id: p.unit_kerja_id,
                                unit_kerja_nama: p.unit_kerja ? p.unit_kerja.nama : null,
                                induk_unit_kerja_nama: (p.unit_kerja && p.unit_kerja.parent) ? p.unit_kerja.parent.nama : null,
                                status_aktif: Boolean(p.status_aktif),
                            };
                        } else {
                            updatedData = {
                                id: this.editForm.id,
                                nip: this.editForm.nip,
                                nama: this.editForm.nama,
                                tempat_lahir: this.editForm.tempat_lahir,
                                tanggal_lahir: this.editForm.tanggal_lahir,
                                jenis_kelamin: this.editForm.jenis_kelamin,
                                pendidikan_terakhir: this.editForm.pendidikan_terakhir,
                                pangkat: this.editForm.pangkat,
                                golongan: this.editForm.golongan,
                                tmt_pangkat: this.editForm.tmt_pangkat,
                                jabatan: this.editForm.jabatan,
                                tmt_jabatan: this.editForm.tmt_jabatan,
                                unit_kerja_id: this.editForm.unit_kerja_id,
                                unit_kerja_nama: this.editForm.unit_kerja_label || null,
                                status_aktif: Boolean(this.editForm.status_aktif),
                            };
                        }

                        this.updateTableRow(this.editForm.id, updatedData);

                        this.saving = false;
                        this.editModalOpen = false;
                        this.showToast((json && json.message) ? json.message : 'Pegawai berhasil diperbarui.', 'success');
                    } else {
                        this.saving = false;
                        this.editGeneralError = 'Terjadi kesalahan sistem (' + response.status + '). Silakan coba lagi.';
                    }
                } catch (err) {
                    this.saving = false;
                    this.editGeneralError = 'Gagal menghubungi server. Periksa koneksi internet Anda.';
                }
            },

            updateTableRow(id, data) {
                if (this.pegawaisMap[id]) {
                    this.pegawaisMap[id] = {
                        ...this.pegawaisMap[id],
                        ...data,
                        tanggal_lahir_formatted: data.tanggal_lahir ? this.formatDateDisplay(data.tanggal_lahir) : '-',
                        tmt_pangkat_formatted: data.tmt_pangkat ? this.formatDateDisplay(data.tmt_pangkat) : '-',
                        tmt_jabatan_formatted: data.tmt_jabatan ? this.formatDateDisplay(data.tmt_jabatan) : '-'
                    };
                }

                const row = document.getElementById('pegawai-row-' + id);
                if (!row) return;

                const elNama = row.querySelector('.pegawai-row-nama');
                if (elNama) elNama.textContent = data.nama;

                const elNip = row.querySelector('.pegawai-row-nip');
                if (elNip) elNip.textContent = 'NIP: ' + data.nip;

                const elPangkat = row.querySelector('.pegawai-row-pangkat');
                if (elPangkat) elPangkat.textContent = data.pangkat || '-';

                const elGolongan = row.querySelector('.pegawai-row-golongan');
                if (elGolongan) elGolongan.textContent = 'Gol. ' + (data.golongan || '-');

                const elJabatan = row.querySelector('.pegawai-row-jabatan');
                if (elJabatan) elJabatan.textContent = data.jabatan || '-';

                const elUnit = row.querySelector('.pegawai-row-unit');
                if (elUnit) elUnit.textContent = data.unit_kerja_nama || 'Belum diatur';

                const elParent = row.querySelector('.pegawai-row-parent');
                if (elParent) {
                    if (data.induk_unit_kerja_nama) {
                        elParent.textContent = data.induk_unit_kerja_nama;
                        elParent.style.display = 'block';
                    } else {
                        elParent.textContent = '';
                        elParent.style.display = 'none';
                    }
                }

                const elStatus = row.querySelector('.pegawai-row-status');
                if (elStatus) {
                    if (data.status_aktif) {
                        elStatus.innerHTML = '<span style="display:inline-flex; align-items:center; padding:3px 8px; background:#ecfdf5; color:#059669; font-size:11px; font-weight:700; border-radius:6px; letter-spacing:0.5px; text-transform:uppercase;">Aktif</span>';
                    } else {
                        elStatus.innerHTML = '<span style="display:inline-flex; align-items:center; padding:3px 8px; background:#f1f5f9; color:#475569; font-size:11px; font-weight:700; border-radius:6px; letter-spacing:0.5px; text-transform:uppercase;">Non-Aktif</span>';
                    }
                }
            },

            formatDateDisplay(dateStr) {
                if (!dateStr) return '-';
                try {
                    const parts = dateStr.substring(0, 10).split('-');
                    if (parts.length === 3) {
                        const d = parseInt(parts[2], 10);
                        const m = parseInt(parts[1], 10);
                        const y = parts[0];
                        const months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                        return d + ' ' + (months[m] || '') + ' ' + y;
                    }
                } catch(e) {}
                return dateStr;
            },

            showToast(msg, type = 'success') {
                if (this.toast.timer) clearTimeout(this.toast.timer);
                this.toast.message = msg;
                this.toast.type = type;
                this.toast.show = true;
                this.toast.timer = setTimeout(() => {
                    this.toast.show = false;
                }, 4000);
            }
        };
    }
    </script>
</x-app-layout>
