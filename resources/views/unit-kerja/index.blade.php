<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; gap:12px;">
            <div style="width:40px; height:40px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#15803d; flex-shrink:0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><line x1="12" y1="12" x2="12" y2="12.01"/><line x1="12" y1="16" x2="12" y2="16.01"/></svg>
            </div>
            <div>
                <div style="font-size:17px; font-weight:600; color:#1e293b; line-height:1.2;">Unit Kerja</div>
                <div style="margin-top:2px; font-size:12.5px; color:#64748b;">Kelola data bidang, seksi, dan unit kerja</div>
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

        @if (session('error'))
            <div style="display:flex; align-items:center; gap:10px; padding:14px 18px; background:#fef2f2; border:1px solid #fecaca; border-radius:14px; color:#991b1b; font-size:14px; font-weight:500;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <div style="display:flex; align-items:center; justify-content:space-between;">
            <div>
                <p style="font-size:13px; color:#94a3b8; margin:0;">
                    Kelola data master Unit Kerja Dinas Perhubungan
                </p>
            </div>
            <a href="{{ route('unit-kerja.create') }}" class="btn-primary" style="text-decoration:none; display:inline-flex; align-items:center; gap:8px; padding:10px 18px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; border-radius:12px; font-size:13px; font-weight:600; box-shadow:0 2px 8px rgba(29,78,216,0.3);">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Unit Kerja
            </a>
        </div>

        <style>
            .explorer-item {
                border-bottom: 1px solid var(--border-light, #f1f5f9);
                transition: background-color 0.15s ease;
            }
            .explorer-item:hover {
                background: rgba(0,0,0,0.02);
            }
            [data-theme="dark"] .explorer-item:hover {
                background: rgba(255,255,255,0.03);
            }
            .explorer-child-container {
                position: relative;
                padding-left: 32px;
            }
            .explorer-child-line {
                position: absolute;
                left: 19px;
                top: 0;
                bottom: 0;
                width: 1px;
                background: var(--border, #e2e8f0);
            }
            .explorer-child-item {
                display: flex;
                align-items: center;
                padding: 10px 20px 10px 14px;
                position: relative;
                transition: background-color 0.15s ease;
            }
            .explorer-child-item::before {
                content: '';
                position: absolute;
                left: -13px;
                top: 50%;
                width: 12px;
                height: 1px;
                background: var(--border, #e2e8f0);
            }
            .explorer-child-item:hover {
                background: rgba(0,0,0,0.02);
            }
            [data-theme="dark"] .explorer-child-item:hover {
                background: rgba(255,255,255,0.03);
            }
        </style>

        <div class="card" style="overflow:hidden;">
            <div style="padding:16px 20px; border-bottom:1px solid var(--border, #e2e8f0); display:flex; align-items:center; gap:10px; background:var(--page-bg, #f8fafc);">
                <div style="width:32px; height:32px; background:var(--tc-accent-soft, #eff6ff); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--tc-accent, #1d4ed8)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                </div>
                <div style="flex:1;">
                    <span style="font-size:14px; font-weight:700; color:var(--text-primary, #1e293b);">Struktur Organisasi</span>
                </div>
            </div>

            <div>
                @forelse ($unitKerja as $bidang)
                    <div x-data="{ expanded: true }" class="explorer-item">
                        <div @click="expanded = !expanded" style="display:flex; align-items:center; padding:12px 20px; cursor:pointer;">
                            <div style="width:24px; color:var(--text-muted, #94a3b8); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <svg x-show="!expanded" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                                <svg x-show="expanded" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><polyline points="6 9 12 15 18 9"/></svg>
                            </div>
                            <div style="color:#d97706; margin-right:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <svg x-show="!expanded" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                                <svg x-show="expanded" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                            </div>
                            <div style="flex:1; min-width:0;">
                                <div style="display:flex; align-items:center; flex-wrap:wrap; gap:8px;">
                                    <span style="font-weight:600; color:var(--text-primary, #1e293b); font-size:14px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $bidang->nama }}</span>
                                    <span style="font-family:monospace; font-size:11px; background:var(--tc-accent-soft, #eff6ff); color:var(--tc-accent, #1d4ed8); padding:2px 6px; border-radius:4px;">{{ $bidang->kode_unit }}</span>
                                </div>
                                <div style="font-size:12px; color:var(--text-muted, #64748b); margin-top:2px; display:flex; align-items:center; gap:6px;">
                                    @if($bidang->kepala)
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        {{ $bidang->kepala->nama }}
                                    @else
                                        <span style="font-style:italic; color:var(--text-muted, #94a3b8);">Kepala belum diatur</span>
                                    @endif
                                </div>
                            </div>
                            <div style="font-size:12px; font-weight:500; color:var(--text-muted, #64748b); padding:4px 10px; background:var(--page-bg, #f8fafc); border-radius:12px; margin-right:16px; white-space:nowrap; flex-shrink:0;">
                                {{ $bidang->children->count() }} Sub Unit
                            </div>
                            <div @click.stop style="display:flex; gap:6px; flex-shrink:0;">
                                <x-action-group>
                                    <x-action-btn type="edit" url="{{ route('unit-kerja.edit', $bidang) }}" />
                                    <x-action-delete action="{{ route('unit-kerja.destroy', $bidang) }}" confirmMessage="Yakin ingin menghapus unit ini?" />
                                </x-action-group>
                            </div>
                        </div>

                        <div x-show="expanded" class="explorer-child-container" style="display:none;">
                            @if($bidang->children->count() > 0)
                                <div class="explorer-child-line"></div>
                            @endif
                            @foreach($bidang->children as $seksi)
                                <div class="explorer-child-item">
                                    <div style="color:var(--text-muted, #94a3b8); margin-right:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                    </div>
                                    <div style="flex:1; min-width:0;">
                                        <div style="display:flex; align-items:center; flex-wrap:wrap; gap:8px;">
                                            <span style="font-weight:500; color:var(--text-primary, #334155); font-size:13.5px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $seksi->nama }}</span>
                                            <span style="font-family:monospace; font-size:10px; background:var(--page-bg, #f1f5f9); color:var(--text-muted, #475569); padding:2px 6px; border-radius:4px;">{{ $seksi->kode_unit }}</span>
                                        </div>
                                        <div style="font-size:11.5px; color:var(--text-muted, #64748b); margin-top:2px; display:flex; align-items:center; gap:6px;">
                                            @if($seksi->kepala)
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                                {{ $seksi->kepala->nama }}
                                            @else
                                                <span style="font-style:italic;">Kepala belum diatur</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div @click.stop style="display:flex; gap:6px; flex-shrink:0;">
                                        <x-action-group>
                                            <x-action-btn type="edit" url="{{ route('unit-kerja.edit', $seksi) }}" />
                                            <x-action-delete action="{{ route('unit-kerja.destroy', $seksi) }}" confirmMessage="Yakin ingin menghapus sub unit ini?" />
                                        </x-action-group>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div style="padding:40px 24px; text-align:center;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--border, #e2e8f0)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 12px;"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                        <p style="margin:0; font-size:14px; color:var(--text-muted, #94a3b8); font-weight:500;">Struktur organisasi belum dikonfigurasi.</p>
                    </div>
                @endforelse
            </div>

            @if ($unitKerja->hasPages())
                <div style="padding:14px 20px; border-top:1px solid var(--border-light, #f1f5f9); background:var(--card-bg, #fff);">
                    {{ $unitKerja->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
