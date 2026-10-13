<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; gap:12px;">
            <div style="width:40px; height:40px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#1d4ed8; flex-shrink:0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
            </div>
            <div>
                <div style="font-size:17px; font-weight:600; color:#1e293b; line-height:1.2;">Jenis Surat</div>
                <div style="margin-top:2px; font-size:12.5px; color:#64748b;">Kelola kategori dan kode jenis surat</div>
            </div>
        </div>
    </x-slot>

    <div x-data="jenisSuratModalHandler({
        csrfToken: '{{ csrf_token() }}',
        baseUrl: '{{ url('/jenis-surat') }}'
    })" style="display:flex; flex-direction:column; gap:20px;">

        {{-- Alert sukses --}}
        @if (session('status'))
            <div style="display:flex; align-items:center; gap:10px; padding:14px 18px; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:14px; color:#065f46; font-size:14px; font-weight:500;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('status') }}
            </div>
        @endif

        {{-- Alert galat (mis. penghapusan yang masih dipakai surat) --}}
        @if (session('error'))
            <div style="display:flex; align-items:center; gap:10px; padding:14px 18px; background:#fef2f2; border:1px solid #fecaca; border-radius:14px; color:#991b1b; font-size:14px; font-weight:500;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Header Row --}}
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
            <div>
                <p style="font-size:13px; color:#94a3b8; margin:0;">
                    Kelola kategori dan kode jenis surat yang dipakai pada form Surat Masuk, Surat Keluar, dan Arsip
                </p>
            </div>

            <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                {{-- Pencarian --}}
                <form action="{{ route('jenis-surat.index') }}" method="GET" style="display:flex; align-items:center; gap:8px;">
                    <div style="position:relative;">
                        <input type="search" name="q" value="{{ $q }}" placeholder="Cari kode / nama jenis surat"
                               aria-label="Cari jenis surat"
                               style="height:34px; width:230px; padding:0 12px 0 34px; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; background:white; color:#334155; outline:none; box-sizing:border-box;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="position:absolute; left:11px; top:50%; transform:translateY(-50%); color:#94a3b8; pointer-events:none;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </div>
                    <button type="submit"
                            style="height:34px; padding:0 14px; background:white; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; font-weight:600; color:#334155; cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Cari
                    </button>
                    @if ($q !== '')
                        <a href="{{ route('jenis-surat.index') }}"
                           title="Hapus pencarian" aria-label="Hapus pencarian"
                           style="height:34px; padding:0 12px; background:white; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; font-weight:600; color:#64748b; display:inline-flex; align-items:center; gap:6px; text-decoration:none;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            Reset
                        </a>
                    @endif
                </form>

                <a href="{{ route('jenis-surat.create') }}" @click.prevent="openCreate()" class="btn-primary"
                   style="text-decoration:none; display:inline-flex; align-items:center; gap:8px; padding:10px 18px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; border-radius:12px; font-size:13px; font-weight:600; box-shadow:0 2px 8px rgba(29,78,216,0.3); cursor:pointer;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah Jenis Surat
                </a>
            </div>
        </div>

        {{-- Table Card --}}
        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">
            <div style="padding:18px 24px; border-bottom:1px solid rgba(0,0,0,0.05); display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                <div style="width:32px; height:32px; background:#eff6ff; border-radius:9px; display:flex; align-items:center; justify-content:center;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                </div>
                <span style="font-size:14px; font-weight:700; color:#1e293b;">Daftar Jenis Surat</span>
                @if ($q !== '')
                    <span style="font-size:12px; color:#94a3b8;">— {{ $jenisSurat->total() }} data untuk "{{ $q }}"</span>
                @endif
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:70px;">No.</th>
                        <th style="width:110px;">Kode</th>
                        <th>Nama Jenis Surat</th>
                        <th style="width:210px;">Penggunaan</th>
                        <th style="text-align:right; padding-right:24px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="jenis-surat-table-body">
                    @forelse ($jenisSurat as $item)
                        @php
                            $stat = $statistik[$item->id] ?? ['masuk' => 0, 'keluar' => 0, 'total' => 0];
                        @endphp
                        <tr id="jenis-surat-row-{{ $item->id }}"
                            data-kode="{{ $item->kode }}"
                            data-nama="{{ $item->nama }}"
                            data-total="{{ $stat['total'] }}"
                            data-masuk="{{ $stat['masuk'] }}"
                            data-keluar="{{ $stat['keluar'] }}">
                            <td style="color:#94a3b8; font-size:13px;">{{ $loop->iteration }}.</td>
                            <td>
                                <span style="display:inline-flex; align-items:center; padding:4px 10px; background:#eff6ff; color:#1d4ed8; font-size:12px; font-weight:700; border-radius:8px; font-family:monospace; letter-spacing:0.5px;">
                                    {{ $item->kode }}
                                </span>
                            </td>
                            <td style="font-weight:500; color:#1e293b;">{{ $item->nama }}</td>
                            <td>
                                @if ($stat['total'] > 0)
                                    <div style="display:flex; gap:6px; flex-wrap:wrap;">
                                        @if ($stat['masuk'] > 0)
                                            <span title="Jumlah surat masuk dengan jenis ini" style="display:inline-flex; align-items:center; gap:4px; padding:3px 9px; background:#ecfdf5; color:#047857; font-size:11px; font-weight:600; border-radius:20px;">
                                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 7 7 17"/><path d="M17 17H7V7"/></svg>
                                                Masuk {{ $stat['masuk'] }}
                                            </span>
                                        @endif
                                        @if ($stat['keluar'] > 0)
                                            <span title="Jumlah surat keluar dengan jenis ini" style="display:inline-flex; align-items:center; gap:4px; padding:3px 9px; background:#eff6ff; color:#1d4ed8; font-size:11px; font-weight:600; border-radius:20px;">
                                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 7h10v10"/><path d="M7 17 17 7"/></svg>
                                                Keluar {{ $stat['keluar'] }}
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span style="display:inline-flex; align-items:center; padding:3px 9px; background:#f1f5f9; color:#475569; font-size:11px; font-weight:600; border-radius:20px;">Belum digunakan</span>
                                @endif
                            </td>
                            <td style="text-align:right; padding-right:20px;">
                                <x-action-group>
                                    <x-action-btn type="view" url="{{ route('jenis-surat.show', $item) }}" title="Detail" />
                                    <x-action-btn type="edit" url="{{ route('jenis-surat.edit', $item) }}" title="Edit"
                                                  onclick="window.__jenisSuratHandler.openEdit({{ $item->id }}); return false;" />
                                    <x-action-delete action="{{ route('jenis-surat.destroy', $item) }}" confirmMessage="Yakin ingin menghapus jenis surat ini? Jenis yang masih dipakai surat tidak dapat dihapus." />
                                </x-action-group>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:48px 24px; color:#94a3b8;">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#e2e8f0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="margin:0 auto 12px; display:block;"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                                <p style="margin:0; font-size:14px; font-weight:500;">{{ $q !== '' ? 'Tidak ada jenis surat yang cocok dengan pencarian.' : 'Belum ada jenis surat' }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($jenisSurat->hasPages())
                <div style="padding:14px 24px; border-top:1px solid rgba(0,0,0,0.05);">
                    {{ $jenisSurat->links() }}
                </div>
            @endif
        </div>

        {{-- ══════════════════════════════════════════════════════
           MODAL TAMBAH JENIS SURAT
        ══════════════════════════════════════════════════════ --}}
        <div x-show="createModalOpen"
             x-transition.opacity.duration.200ms
             class="sipas-modal-overlay"
             @click.self="closeCreate()"
             @keydown.escape.window="closeCreate()"
             style="display: none;"
             role="dialog"
             aria-modal="true"
             aria-labelledby="js-modal-create-title">
            <div class="sipas-modal-dialog" style="max-width: 520px;" @click.stop>
                <div class="sipas-modal-header">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:34px; height:34px; background:#eff6ff; color:#1d4ed8; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        </div>
                        <div>
                            <h3 id="js-modal-create-title" style="font-size:15px; font-weight:700; color:var(--text-primary, #1e293b); margin:0; line-height:1.2;">Tambah Jenis Surat</h3>
                            <p style="font-size:12px; color:var(--text-muted, #94a3b8); margin:2px 0 0;">Lengkapi kode dan nama jenis surat</p>
                        </div>
                    </div>
                    <button type="button" @click="closeCreate()" class="sipas-modal-close" title="Tutup modal" aria-label="Tutup">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>

                <form @submit.prevent="submitCreate()">
                    <div class="sipas-modal-body" style="display:flex; flex-direction:column; gap:16px;">
                        <div x-show="createGeneralError" x-cloak x-text="createGeneralError"
                             style="display:none; padding:10px 14px; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; color:#991b1b; font-size:13px;"></div>

                        <div class="form-group">
                            <label class="form-label" for="c_kode">Kode Jenis Surat <span style="color:#ef4444;">*</span></label>
                            <input id="c_kode" type="text" x-model="createForm.kode"
                                   class="form-control" :class="{'sipas-input-error': createErrors.kode}"
                                   placeholder="Contoh: SK, SE, ST"
                                   style="font-family:monospace; letter-spacing:1px;">
                            <p style="font-size:11.5px; color:var(--text-muted, #94a3b8); margin:0;">Kode tampil sebagai lencana pada daftar surat, arsip, laporan, dan pencarian.</p>
                            <template x-if="createErrors.kode">
                                <p class="form-error" x-text="createErrors.kode[0]"></p>
                            </template>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="c_nama">Nama Jenis Surat <span style="color:#ef4444;">*</span></label>
                            <input id="c_nama" type="text" x-model="createForm.nama"
                                   class="form-control" :class="{'sipas-input-error': createErrors.nama}"
                                   placeholder="Contoh: Surat Keputusan">
                            <p style="font-size:11.5px; color:var(--text-muted, #94a3b8); margin:0;">Nama menjadi pilihan pada form Surat Masuk, Surat Keluar, dan filter laporan.</p>
                            <template x-if="createErrors.nama">
                                <p class="form-error" x-text="createErrors.nama[0]"></p>
                            </template>
                        </div>
                    </div>

                    <div class="sipas-modal-footer">
                        <button type="button" @click="closeCreate()" class="sipas-btn-secondary">Batal</button>
                        <button type="submit" class="sipas-btn-primary" :disabled="savingCreate">
                            <span x-show="savingCreate" style="display:none;" class="sipas-spin" aria-hidden="true">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="9" stroke-opacity="0.3"/><path d="M21 12a9 9 0 0 0-9-9"/></svg>
                            </span>
                            <span x-text="savingCreate ? 'Menyimpan...' : 'Simpan Jenis Surat'">Simpan Jenis Surat</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════
           MODAL EDIT JENIS SURAT
        ══════════════════════════════════════════════════════ --}}
        <div x-show="editModalOpen"
             x-transition.opacity.duration.200ms
             class="sipas-modal-overlay"
             @click.self="closeEdit()"
             @keydown.escape.window="closeEdit()"
             style="display: none;"
             role="dialog"
             aria-modal="true"
             aria-labelledby="js-modal-edit-title">
            <div class="sipas-modal-dialog" style="max-width: 520px;" @click.stop>
                <div class="sipas-modal-header">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:34px; height:34px; background:#ecfdf5; color:#047857; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </div>
                        <div>
                            <h3 id="js-modal-edit-title" style="font-size:15px; font-weight:700; color:var(--text-primary, #1e293b); margin:0; line-height:1.2;">Edit Jenis Surat</h3>
                            <p style="font-size:12px; color:var(--text-muted, #94a3b8); margin:2px 0 0;">Perbarui kode atau nama jenis surat</p>
                        </div>
                    </div>
                    <button type="button" @click="closeEdit()" class="sipas-modal-close" title="Tutup modal" aria-label="Tutup">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>

                <form @submit.prevent="submitEdit()">
                    <div class="sipas-modal-body" style="display:flex; flex-direction:column; gap:16px;">
                        <div x-show="editGeneralError" x-cloak x-text="editGeneralError"
                             style="display:none; padding:10px 14px; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; color:#991b1b; font-size:13px;"></div>

                        <div x-show="editUsage.total > 0"
                             style="display:none; padding:10px 14px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; color:#1e40af; font-size:12.5px;">
                            Jenis ini sudah digunakan oleh <strong x-text="editUsage.total"></strong> surat
                            (<span x-text="editUsage.masuk"></span> masuk, <span x-text="editUsage.keluar"></span> keluar).
                            Perubahan hanya memengaruhi tampilan berikutnya, data surat lama tetap utuh.
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="e_kode">Kode Jenis Surat <span style="color:#ef4444;">*</span></label>
                            <input id="e_kode" type="text" x-model="editForm.kode"
                                   class="form-control" :class="{'sipas-input-error': editErrors.kode}"
                                   placeholder="Contoh: SK, SE, ST"
                                   style="font-family:monospace; letter-spacing:1px;">
                            <p style="font-size:11.5px; color:var(--text-muted, #94a3b8); margin:0;">Kode tampil sebagai lencana pada daftar surat, arsip, laporan, dan pencarian.</p>
                            <template x-if="editErrors.kode">
                                <p class="form-error" x-text="editErrors.kode[0]"></p>
                            </template>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="e_nama">Nama Jenis Surat <span style="color:#ef4444;">*</span></label>
                            <input id="e_nama" type="text" x-model="editForm.nama"
                                   class="form-control" :class="{'sipas-input-error': editErrors.nama}"
                                   placeholder="Contoh: Surat Keputusan">
                            <p style="font-size:11.5px; color:var(--text-muted, #94a3b8); margin:0;">Nama menjadi pilihan pada form Surat Masuk, Surat Keluar, dan filter laporan.</p>
                            <template x-if="editErrors.nama">
                                <p class="form-error" x-text="editErrors.nama[0]"></p>
                            </template>
                        </div>
                    </div>

                    <div class="sipas-modal-footer">
                        <button type="button" @click="closeEdit()" class="sipas-btn-secondary">Batal</button>
                        <button type="submit" class="sipas-btn-primary" :disabled="savingEdit">
                            <span x-show="savingEdit" style="display:none;" class="sipas-spin" aria-hidden="true">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="9" stroke-opacity="0.3"/><path d="M21 12a9 9 0 0 0-9-9"/></svg>
                            </span>
                            <span x-text="savingEdit ? 'Menyimpan...' : 'Simpan Perubahan'">Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Toast --}}
        <div x-show="toast.show"
             x-transition.opacity.duration.200ms
             x-cloak
             style="display:none; position:fixed; bottom:24px; right:24px; z-index:10000; display:flex; align-items:center; gap:10px; padding:12px 18px; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:12px; color:#065f46; font-size:13.5px; font-weight:600; box-shadow:0 10px 15px -3px rgba(0,0,0,0.2), 0 4px 6px -4px rgba(0,0,0,0.2);"
             role="status" aria-live="polite">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <span x-text="toast.message"></span>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════
       MODAL & FORM STYLES (Light & Dark Mode, Responsive)
    ══════════════════════════════════════════════════════ --}}
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
        .sipas-btn-primary:hover:not(:disabled) { opacity: 0.92; }
        .sipas-btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }

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
        .sipas-btn-secondary:disabled { opacity: 0.6; cursor: not-allowed; }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-primary, #374151);
            letter-spacing: 0.2px;
        }

        .form-control {
            width: 100%;
            padding: 9px 12px;
            font-size: 13.5px;
            color: var(--text-primary, #1e293b);
            background: var(--card-bg, #ffffff);
            border: 1px solid var(--border, #d1d5db);
            border-radius: 8px;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            box-sizing: border-box;
        }
        .form-control:focus {
            border-color: var(--tc-accent, #2563eb);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
        }

        .form-error {
            font-size: 12px;
            color: #ef4444;
            margin: 0;
            font-weight: 500;
        }

        .sipas-input-error { border-color: #ef4444 !important; }

        .sipas-spin { animation: jsSpin 0.8s linear infinite; }
        @keyframes jsSpin {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        [x-cloak] { display: none !important; }

        /* ── Dark Mode Overrides ──────────────────────────── */
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
        [data-theme="dark"] .sipas-btn-secondary {
            background: #1e293b !important;
            border-color: #334155 !important;
            color: #cbd5e1 !important;
        }
        [data-theme="dark"] .sipas-btn-secondary:hover:not(:disabled) {
            background: #27354a !important;
            border-color: #475569 !important;
        }
        [data-theme="dark"] .form-control {
            background: #1a2740 !important;
            border-color: #334155 !important;
            color: #e2e8f0 !important;
        }
        [data-theme="dark"] .form-control:focus {
            border-color: var(--tc-accent, #3b82f6) !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
        }
        [data-theme="dark"] .form-label { color: #cbd5e1 !important; }

        /* ── Responsive ───────────────────────────────────── */
        @media (max-width: 640px) {
            .sipas-modal-dialog { max-height: calc(100vh - 24px); }
            .sipas-modal-header { padding: 14px 16px; }
            .sipas-modal-body { padding: 16px; }
            .sipas-modal-footer {
                padding: 12px 16px;
                flex-direction: column-reverse;
            }
            .sipas-btn-primary, .sipas-btn-secondary { width: 100%; }
        }
    </style>

    <script>
        function jenisSuratModalHandler(config) {
            return {
                csrfToken: config.csrfToken,
                baseUrl: config.baseUrl,

                createModalOpen: false,
                editModalOpen: false,
                savingCreate: false,
                savingEdit: false,

                createForm: { kode: '', nama: '' },
                editForm: { id: '', kode: '', nama: '' },
                editUsage: { total: 0, masuk: 0, keluar: 0 },

                createErrors: {},
                editErrors: {},
                createGeneralError: '',
                editGeneralError: '',

                toast: { show: false, message: '', timer: null },

                init() {
                    window.__jenisSuratHandler = this;
                    this.$watch('createModalOpen', () => this.updateBodyLock());
                    this.$watch('editModalOpen', () => this.updateBodyLock());
                },

                updateBodyLock() {
                    document.body.style.overflow = (this.createModalOpen || this.editModalOpen) ? 'hidden' : '';
                },

                openCreate() {
                    this.createForm = { kode: '', nama: '' };
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

                openEdit(id) {
                    const row = document.getElementById('jenis-surat-row-' + id);
                    if (!row) return;

                    this.editForm = {
                        id: id,
                        kode: row.dataset.kode || '',
                        nama: row.dataset.nama || ''
                    };
                    this.editUsage = {
                        total: parseInt(row.dataset.total || '0', 10) || 0,
                        masuk: parseInt(row.dataset.masuk || '0', 10) || 0,
                        keluar: parseInt(row.dataset.keluar || '0', 10) || 0
                    };
                    this.editErrors = {};
                    this.editGeneralError = '';
                    this.savingEdit = false;
                    this.editModalOpen = true;
                },

                closeEdit() {
                    this.editModalOpen = false;
                    this.editErrors = {};
                    this.editGeneralError = '';
                },

                async submitCreate() {
                    this.savingCreate = true;
                    this.createErrors = {};
                    this.createGeneralError = '';

                    const formData = new FormData();
                    formData.append('_token', this.csrfToken);
                    formData.append('kode', this.createForm.kode || '');
                    formData.append('nama', this.createForm.nama || '');

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
                            const data = await response.json();
                            this.createErrors = data.errors || {};
                            this.createGeneralError = data.message || 'Mohon periksa kesalahan input pada form.';
                            this.savingCreate = false;
                            return;
                        }

                        const json = await response.json().catch(() => null);

                        if (response.ok || response.status === 201) {
                            this.savingCreate = false;
                            this.closeCreate();
                            await this.refreshTable();
                            this.showToast((json && json.message) || 'Jenis Surat berhasil ditambahkan.');
                            return;
                        }

                        this.createGeneralError = (json && json.message) || 'Terjadi kesalahan saat menyimpan data.';
                    } catch (e) {
                        this.createGeneralError = 'Tidak dapat terhubung ke server. Silakan coba lagi.';
                    }

                    this.savingCreate = false;
                },

                async submitEdit() {
                    this.savingEdit = true;
                    this.editErrors = {};
                    this.editGeneralError = '';

                    const formData = new FormData();
                    formData.append('_token', this.csrfToken);
                    formData.append('_method', 'PUT');
                    formData.append('kode', this.editForm.kode || '');
                    formData.append('nama', this.editForm.nama || '');

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
                            const data = await response.json();
                            this.editErrors = data.errors || {};
                            this.editGeneralError = data.message || 'Mohon periksa kesalahan input pada form.';
                            this.savingEdit = false;
                            return;
                        }

                        const json = await response.json().catch(() => null);

                        if (response.ok) {
                            this.savingEdit = false;
                            this.closeEdit();
                            await this.refreshTable();
                            this.showToast((json && json.message) || 'Jenis Surat berhasil diperbarui.');
                            return;
                        }

                        this.editGeneralError = (json && json.message) || 'Terjadi kesalahan saat menyimpan data.';
                    } catch (e) {
                        this.editGeneralError = 'Tidak dapat terhubung ke server. Silakan coba lagi.';
                    }

                    this.savingEdit = false;
                },

                /**
                 * Muat ulang isi tabel dari server agar baris, urutan, pencarian,
                 * dan pagination selalu sama dengan data terbaru.
                 */
                async refreshTable() {
                    try {
                        const response = await fetch(window.location.href, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        if (!response.ok) return;

                        const html = await response.text();
                        const fresh = new DOMParser().parseFromString(html, 'text/html')
                            .getElementById('jenis-surat-table-body');
                        const current = document.getElementById('jenis-surat-table-body');

                        if (fresh && current) {
                            current.innerHTML = fresh.innerHTML;
                        }
                    } catch (e) {
                        // Biarkan tampilan seperti adanya bila gagal memuat ulang.
                    }
                },

                showToast(message) {
                    if (this.toast.timer) clearTimeout(this.toast.timer);
                    this.toast.message = message;
                    this.toast.show = true;
                    this.toast.timer = setTimeout(() => { this.toast.show = false; }, 3500);
                }
            };
        }
    </script>
</x-app-layout>
