<x-app-layout>
    <x-slot name="header">Draft Surat</x-slot>

    <div x-data="suratKeluarDraftHandler({ csrfToken: '{{ csrf_token() }}' })"
         style="display:flex; flex-direction:column; gap:20px;">

        <p style="font-size:13px; color:var(--text-muted,#94a3b8); margin:0;">
            Surat keluar yang sudah dibuat namun belum difinalisasi.
        </p>

        @php
            // Kelompokkan baris berdasarkan tanggal input (created_at) memakai zona waktu aplikasi.
            $draftGroups = \App\Helpers\DateGroupHelper::groupByInputDate($draftSurat);
        @endphp

        {{-- Template header kelompok "Hari Ini" (dipakai bila baris disisipkan lewat AJAX) --}}
        <template id="skd-today-group-template">
            <x-date-group-row :group="\App\Helpers\DateGroupHelper::todayGroup()" :colspan="6" />
        </template>

        <div style="background:var(--card-bg,white); border-radius:20px; border:1px solid var(--border,rgba(0,0,0,0.06)); overflow:hidden;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:60px;">No.</th>
                        <th>Nomor Surat</th>
                        <th>Perihal</th>
                        <th>Jenis</th>
                        <th>Tgl. Surat</th>
                        <th style="width:130px; text-align:right; padding-right:20px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="skd-tbody">
                    @forelse ($draftGroups as $group)
                        <x-date-group-row :group="$group" :colspan="6" :partial="$draftSurat->hasPages()" />
                        @foreach ($group['items'] as $row)
                            @php $item = $row['surat']; @endphp
                            <tr data-surat-row id="skd-row-{{ $item->id }}">
                            <td style="color:#94a3b8; font-size:13px;">{{ $row['pos'] }}.</td>
                            <td style="font-family:monospace; font-size:12px; color:#334155;">{{ $item->nomor_surat ?? '-' }}</td>
                            <td style="font-weight:500; color:var(--text-primary,#1e293b);">{{ $item->perihal }}</td>
                            <td>
                                <span style="display:inline-flex; padding:4px 10px; background:#f1f5f9; color:#64748b; font-size:11px; font-weight:600; border-radius:8px;">
                                    {{ $item->jenisSurat->kode ?? '-' }}
                                </span>
                            </td>
                            <td style="color:#64748b; font-size:13px;">{{ $item->tanggal_surat?->format('d M Y') }}</td>
                            <td style="text-align:right; padding-right:20px;">
                                <div style="display:inline-flex; gap:6px;">
                                    <a href="{{ route('buat-surat.show', $item) }}" title="Preview" aria-label="Preview"
                                       style="display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; background:#f8fafc; color:#3b82f6; border-radius:6px; border:1px solid #e2e8f0; text-decoration:none;"
                                       onmouseover="this.style.background='#eff6ff'; this.style.borderColor='#bfdbfe';"
                                       onmouseout="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0';">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    <a href="{{ route('surat-keluar.edit', $item) }}" title="Edit" aria-label="Edit"
                                       style="display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; background:#f8fafc; color:#10b981; border-radius:6px; border:1px solid #e2e8f0; text-decoration:none;"
                                       onmouseover="this.style.background='#ecfdf5'; this.style.borderColor='#a7f3d0';"
                                       onmouseout="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0';">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </a>
                                    <button type="button" title="Hapus" aria-label="Hapus"
                                            data-id="{{ $item->id }}"
                                            data-perihal="{{ $item->perihal }}"
                                            data-nomor="{{ $item->nomor_surat }}"
                                            data-url="{{ route('surat-keluar.destroy', $item) }}"
                                            @click="askDelete($el)"
                                            style="display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; background:#f8fafc; color:#ef4444; border-radius:6px; border:1px solid #e2e8f0; cursor:pointer;"
                                            onmouseover="this.style.background='#fef2f2'; this.style.borderColor='#fecaca';"
                                            onmouseout="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0';">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                    </button>
                                </div>
                            </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:48px 24px; color:#94a3b8;">
                                <p style="margin:0; font-size:14px; font-weight:500;">Tidak ada draft surat</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($draftSurat->hasPages())
                <div style="padding:14px 24px; border-top:1px solid rgba(0,0,0,0.05);">
                    {{ $draftSurat->links() }}
                </div>
            @endif
        </div>

        {{-- ══ Konfirmasi Hapus Draft ══ --}}
        <div x-show="confirmOpen"
             x-transition.opacity.duration.200ms
             class="skd-overlay"
             @click.self="closeConfirm()"
             @keydown.escape.window="closeConfirm()"
             style="display:none;"
             role="dialog" aria-modal="true" aria-labelledby="skd-delete-title">
            <div class="skd-dialog" @click.stop>
                <div class="skd-header">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div class="skd-head-ic">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                        </div>
                        <div>
                            <h3 id="skd-delete-title" style="font-size:15px; font-weight:700; color:var(--text-primary,#1e293b); margin:0; line-height:1.2;">Hapus Draft Surat</h3>
                            <p style="font-size:12px; color:var(--text-muted,#64748b); margin:2px 0 0 0;">Tindakan ini tidak dapat dibatalkan.</p>
                        </div>
                    </div>
                    <button type="button" @click="closeConfirm()" class="skd-close" aria-label="Tutup">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>

                <div class="skd-body">
                    <p style="font-size:13px; color:var(--text-primary,#334155); margin:0 0 12px;">Anda akan menghapus draft surat berikut:</p>
                    <div class="skd-target">
                        <div style="font-size:11px; font-weight:700; letter-spacing:.4px; text-transform:uppercase; color:var(--text-muted,#64748b); margin-bottom:4px;">Perihal</div>
                        <div style="font-weight:600;" x-text="target ? target.perihal : '-'"></div>
                        <div style="margin-top:6px; font-size:12px; color:var(--text-muted,#64748b);">
                            Nomor: <span style="font-family:monospace;" x-text="target && target.nomor ? target.nomor : '(draft tanpa nomor)'"></span>
                        </div>
                    </div>
                    <p x-show="error" style="margin:12px 0 0; padding:10px 12px; background:#fef2f2; border:1px solid #fecaca; border-radius:8px; color:#991b1b; font-size:12.5px;" x-text="error"></p>
                </div>

                <div class="skd-footer">
                    <button type="button" @click="closeConfirm()" class="skd-btn-secondary" :disabled="deleting">Batal</button>
                    <button type="button" @click="confirmDelete()" class="skd-btn-danger" :disabled="deleting">
                        <svg x-show="deleting" class="skd-spin" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10" stroke-opacity=".25"/><path d="M12 2a10 10 0 0 1 10 10"/></svg>
                        <svg x-show="!deleting" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        <span x-text="deleting ? 'Menghapus...' : 'Hapus'"></span>
                    </button>
                </div>
            </div>
        </div>

        {{-- ══ Toast ══ --}}
        <div x-show="toast.show"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             :style="toast.type === 'success'
                 ? 'position:fixed; bottom:24px; right:24px; z-index:9999; display:flex; align-items:center; gap:10px; padding:14px 20px; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:12px; color:#065f46; font-size:14px; font-weight:500; box-shadow:0 8px 24px rgba(0,0,0,0.12);'
                 : 'position:fixed; bottom:24px; right:24px; z-index:9999; display:flex; align-items:center; gap:10px; padding:14px 20px; background:#fef2f2; border:1px solid #fecaca; border-radius:12px; color:#991b1b; font-size:14px; font-weight:500; box-shadow:0 8px 24px rgba(0,0,0,0.12);'"
             style="display:none;">
            <svg x-show="toast.type === 'success'" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <svg x-show="toast.type !== 'success'" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span x-text="toast.message"></span>
        </div>
    </div>

    <style>
    .skd-overlay {
        position: fixed; inset: 0; background-color: rgba(15,23,42,0.65); backdrop-filter: blur(3px);
        z-index: 9998; display: flex; align-items: center; justify-content: center; padding: 16px; overflow-y: auto;
    }
    .skd-dialog {
        position: relative; width: 100%; max-width: 440px;
        background-color: var(--card-bg, #ffffff); color: var(--text-primary, #1e293b);
        border: 1px solid var(--border, #e2e8f0); border-radius: 8px;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2), 0 10px 10px -5px rgba(0,0,0,0.1);
        display: flex; flex-direction: column; max-height: calc(100vh - 48px); overflow: hidden; z-index: 9999;
    }
    .skd-header {
        display: flex; align-items: center; justify-content: space-between; padding: 16px 20px;
        border-bottom: 1px solid var(--border, #e2e8f0); background-color: var(--card-bg, #ffffff); flex-shrink: 0;
    }
    .skd-head-ic {
        width: 34px; height: 34px; flex-shrink: 0; border-radius: 8px;
        background: #fef2f2; color: #dc2626; display: flex; align-items: center; justify-content: center;
    }
    .skd-close {
        width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;
        background: transparent; border: 1px solid transparent; border-radius: 6px; color: var(--text-muted, #64748b); cursor: pointer;
    }
    .skd-close:hover { background: var(--page-bg, #f1f5f9); color: var(--text-primary, #1e293b); border-color: var(--border, #e2e8f0); }
    .skd-body { padding: 20px; overflow-y: auto; }
    .skd-target {
        padding: 12px 14px; background: var(--page-bg, #f8fafc); border: 1px solid var(--border, #e2e8f0);
        border-radius: 8px; font-size: 13px; color: var(--text-primary, #1e293b);
    }
    .skd-footer {
        display: flex; align-items: center; justify-content: flex-end; gap: 10px; padding: 14px 20px;
        border-top: 1px solid var(--border, #e2e8f0); background-color: var(--page-bg, #f8fafc); flex-shrink: 0; flex-wrap: wrap;
    }
    .skd-btn-secondary {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 9px 18px;
        background: var(--card-bg, #ffffff); color: var(--text-primary, #334155); font-size: 13px; font-weight: 600;
        border-radius: 8px; border: 1px solid var(--border, #cbd5e1); cursor: pointer;
    }
    .skd-btn-secondary:hover:not(:disabled) { background: var(--page-bg, #f8fafc); border-color: #94a3b8; }
    .skd-btn-danger {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 9px 18px;
        background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; font-size: 13px; font-weight: 600;
        border-radius: 8px; cursor: pointer;
    }
    .skd-btn-danger:hover:not(:disabled) { background: #fee2e2; border-color: #fca5a5; }
    .skd-btn-secondary:disabled, .skd-btn-danger:disabled { opacity: .6; cursor: not-allowed; }
    .skd-spin { animation: skdSpin .8s linear infinite; }
    @keyframes skdSpin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

    [data-theme="dark"] .skd-dialog { background-color: #1e293b !important; border-color: #334155 !important; color: #e2e8f0 !important; }
    [data-theme="dark"] .skd-header { background-color: #1e293b !important; border-bottom-color: #334155 !important; }
    [data-theme="dark"] .skd-footer { background-color: #162032 !important; border-top-color: #334155 !important; }
    [data-theme="dark"] .skd-target { background: #162032 !important; border-color: #334155 !important; color: #e2e8f0 !important; }
    [data-theme="dark"] .skd-btn-secondary { background: #1e293b !important; border-color: #334155 !important; color: #cbd5e1 !important; }
    [data-theme="dark"] .skd-close:hover { background: #334155 !important; color: #e2e8f0 !important; }

    @media (max-width: 640px) {
        .skd-dialog { max-height: calc(100vh - 24px); }
        .skd-footer { flex-direction: column-reverse; }
        .skd-btn-secondary, .skd-btn-danger { width: 100%; }
    }
    </style>

    @push('scripts')
    <script>
    function suratKeluarDraftHandler({ csrfToken }) {
        return {
            confirmOpen: false,
            target: null,
            deleting: false,
            error: '',
            toast: { show: false, message: '', type: 'success', timer: null },

            askDelete(el) {
                const d = el.dataset;
                this.target = {
                    id: d.id,
                    perihal: d.perihal || '-',
                    nomor: d.nomor || null,
                    url: d.url,
                };
                this.error = '';
                this.deleting = false;
                this.confirmOpen = true;
            },

            closeConfirm() {
                if (this.deleting) return;
                this.confirmOpen = false;
                this.target = null;
                this.error = '';
            },

            async confirmDelete() {
                if (!this.target || this.deleting) return;
                this.deleting = true;
                this.error = '';
                const target = this.target;
                try {
                    const res = await fetch(target.url, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                    });
                    if (res.status === 403) {
                        const err = await res.json().catch(() => null);
                        this.error = (err && err.message) ? err.message : 'Draft ini tidak dapat dihapus.';
                        this.deleting = false;
                        return;
                    }
                    if (!res.ok) {
                        this.error = 'Gagal menghapus draft (' + res.status + '). Silakan coba lagi.';
                        this.deleting = false;
                        return;
                    }
                    const json = await res.json().catch(() => null);
                    const row = document.getElementById('skd-row-' + target.id);
                    if (row && window.DateGroupTable) {
                        window.DateGroupTable.removeRow(row);
                    } else if (row) {
                        row.remove();
                    }
                    this.deleting = false;
                    this.confirmOpen = false;
                    this.target = null;
                    this.showToast((json && json.message) ? json.message : 'Draft surat keluar berhasil dihapus.', 'success');
                } catch (e) {
                    this.deleting = false;
                    this.error = 'Gagal menghubungi server. Periksa koneksi internet Anda.';
                }
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
