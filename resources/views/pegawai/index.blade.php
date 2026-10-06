<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; gap:12px;">
            <div style="width:40px; height:40px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#1d4ed8; flex-shrink:0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
                <div style="font-size:17px; font-weight:600; color:#1e293b; line-height:1.2;">Master Pegawai</div>
                <div style="margin-top:2px; font-size:12.5px; color:#64748b;">Kelola data pegawai Dinas Perhubungan</div>
            </div>
        </div>
    </x-slot>

    <div style="display:flex; flex-direction:column; gap:20px;">
        @if (session('status'))
            <div style="display:flex; align-items:center; gap:10px; padding:14px 18px; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:14px; color:#065f46; font-size:14px; font-weight:500;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('status') }}
            </div>
        @endif

        <div style="display:flex; align-items:center; justify-content:space-between;">
            <div>
                <p style="font-size:13px; color:#94a3b8; margin:0;">
                    Kelola data kepegawaian Dinas Perhubungan
                </p>
            </div>
            <a href="{{ route('pegawai.create') }}" class="btn-primary" style="text-decoration:none; display:inline-flex; align-items:center; gap:8px; padding:10px 18px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; border-radius:12px; font-size:13px; font-weight:600; box-shadow:0 2px 8px rgba(29,78,216,0.3);">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Pegawai
            </a>
        </div>

        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <div style="padding:18px 24px; border-bottom:1px solid rgba(0,0,0,0.05); display:flex; justify-content:space-between; align-items:center; gap:10px; flex-wrap:wrap;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:32px; height:32px; background:#eff6ff; border-radius:9px; display:flex; align-items:center; justify-content:center;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <span style="font-size:14px; font-weight:700; color:#1e293b;">Daftar Pegawai</span>
                </div>
                
                {{-- Filter & Search --}}
                @php
                    $filterStructuredUnits = [];
                    foreach($unitKerjas->where('parent_id', null) as $bidang) {
                        $filterSeksis = [];
                        foreach($unitKerjas->where('parent_id', $bidang->id) as $seksi) {
                            $filterSeksis[] = ['id' => $seksi->id, 'nama' => $seksi->nama];
                        }
                        $filterStructuredUnits[] = [
                            'id' => $bidang->id,
                            'nama' => $bidang->nama,
                            'seksis' => $filterSeksis
                        ];
                    }
                    $filterInitialId = request('unit_kerja_id');
                    $filterInitialName = '';
                    if ($filterInitialId) {
                        $found = $unitKerjas->where('id', $filterInitialId)->first();
                        if ($found) $filterInitialName = $found->nama;
                    }
                @endphp

                <form action="{{ route('pegawai.index') }}" method="GET" id="filter-pegawai-form" style="display:flex; gap:10px;">
                    {{-- Custom Searchable Dropdown Unit Kerja --}}
                    <div x-data="{
                        open: false,
                        search: '',
                        value: '{{ $filterInitialId }}',
                        label: '{{ addslashes($filterInitialName) }}',
                        data: {{ json_encode($filterStructuredUnits) }},
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
                                style="display:flex; justify-content:space-between; align-items:center; width:100%; text-align:left; background:#fff; cursor:pointer; height:34px; padding:0 10px; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; gap:6px;">
                            <span x-text="label || 'Semua Unit Kerja'"
                                  :style="!label ? 'color:#94a3b8; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:150px;' : 'color:#1e293b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:150px;'"></span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>

                        <div x-show="open"
                             x-transition.opacity
                             :style="direction === 'up'
                                 ? 'display:flex; flex-direction:column-reverse; position:absolute; z-index:50; width:280px; bottom:calc(100% + 4px); left:0; background:white; border:1px solid #e2e8f0; border-radius:8px; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1),0 4px 6px -2px rgba(0,0,0,0.05); overflow:hidden;'
                                 : 'display:flex; flex-direction:column; position:absolute; z-index:50; width:280px; top:calc(100% + 4px); left:0; background:white; border:1px solid #e2e8f0; border-radius:8px; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1),0 4px 6px -2px rgba(0,0,0,0.05); overflow:hidden;'"
                             style="display:none;">

                            <div style="padding:10px; border-bottom:1px solid #e2e8f0; background:#f8fafc; flex-shrink:0;">
                                <div style="position:relative;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute; left:10px; top:9px; color:#94a3b8;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                    <input type="text"
                                           x-model="search"
                                           placeholder="Cari unit kerja..."
                                           style="width:100%; padding:7px 10px 7px 32px; border:1px solid #cbd5e1; border-radius:6px; font-size:13px; outline:none; box-sizing:border-box;"
                                           @click.stop>
                                </div>
                            </div>

                            <div :style="'overflow-y:auto; max-height:' + maxListHeight + 'px; padding:6px 0 10px 0;'">
                                <template x-if="filteredData.length === 0">
                                    <div style="padding:14px; font-size:13px; color:#64748b; text-align:center;">
                                        Unit kerja tidak ditemukan.
                                    </div>
                                </template>
                                <template x-for="bidang in filteredData" :key="bidang.id">
                                    <div>
                                        <div style="padding:7px 14px; font-size:11px; font-weight:700; color:#64748b; background:#f8fafc; text-transform:uppercase; letter-spacing:0.5px; border-bottom:1px solid #f1f5f9;" x-text="bidang.nama"></div>
                                        <template x-for="seksi in bidang.seksis" :key="seksi.id">
                                            <div @click="selectSeksi(seksi.id, seksi.nama)"
                                                 style="padding:9px 14px 9px 22px; font-size:13px; color:#334155; cursor:pointer; display:flex; align-items:center; gap:7px;"
                                                 onmouseover="this.style.backgroundColor='#eff6ff'"
                                                 onmouseout="this.style.backgroundColor='transparent'">
                                                <span x-text="seksi.nama" style="flex:1;"></span>
                                                <svg x-show="value == seksi.id" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>

                            <div style="padding:7px 14px; border-top:1px solid #e2e8f0; background:#f8fafc; flex-shrink:0;">
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
                <tbody>
                    @forelse ($pegawais as $item)
                        <tr>
                            <td style="color:#94a3b8; font-size:13px;">{{ $loop->iteration + $pegawais->firstItem() - 1 }}.</td>
                            <td>
                                <div style="display:flex; flex-direction:column;">
                                    <span style="font-weight:600; color:#1e293b; font-size:14px;">{{ $item->nama }}</span>
                                    <span style="font-family:monospace; color:#64748b; font-size:12px;">NIP: {{ $item->nip }}</span>
                                </div>
                            </td>
                            <td>
                                <div style="display:flex; flex-direction:column;">
                                    <span style="font-weight:500; color:#334155; font-size:13px;">{{ $item->pangkat ?? '-' }}</span>
                                    <span style="color:#64748b; font-size:12px;">Gol. {{ $item->golongan ?? '-' }}</span>
                                </div>
                            </td>
                            <td>
                                <div style="display:flex; flex-direction:column;">
                                    <span style="font-weight:500; color:#334155; font-size:13px;">{{ $item->jabatan ?? '-' }}</span>
                                    <span style="color:#64748b; font-size:12px;">{{ $item->unitKerja->nama ?? 'Belum diatur' }}</span>
                                </div>
                            </td>
                            <td>
                                @if($item->status_aktif)
                                    <span style="display:inline-flex; align-items:center; padding:3px 8px; background:#ecfdf5; color:#059669; font-size:11px; font-weight:700; border-radius:6px; letter-spacing:0.5px; text-transform:uppercase;">Aktif</span>
                                @else
                                    <span style="display:inline-flex; align-items:center; padding:3px 8px; background:#f1f5f9; color:#475569; font-size:11px; font-weight:700; border-radius:6px; letter-spacing:0.5px; text-transform:uppercase;">Non-Aktif</span>
                                @endif
                            </td>
                            <td style="text-align:right; padding-right:20px;">
                                <x-action-group>
                                    <x-action-btn type="view" url="{{ route('pegawai.show', $item) }}" />
                                    <x-action-btn type="edit" url="{{ route('pegawai.edit', $item) }}" />
                                    <x-action-delete action="{{ route('pegawai.destroy', $item) }}" confirmMessage="Yakin ingin menghapus pegawai ini?" />
                                </x-action-group>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:48px 24px; color:#94a3b8;">
                                <p style="margin:0; font-size:14px; font-weight:500;">Belum ada data pegawai</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($pegawais->hasPages())
                <div style="padding:14px 24px; border-top:1px solid rgba(0,0,0,0.05);">
                    {{ $pegawais->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
