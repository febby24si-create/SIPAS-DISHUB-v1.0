<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px;">
            <div style="display:flex; align-items:center; gap:12px; min-width:0;">
                <div style="width:42px; height:42px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; display:flex; align-items:center; justify-content:center; color:#1d4ed8; flex-shrink:0;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                    </svg>
                </div>
                <div style="min-width:0;">
                    <div style="font-size:18px; font-weight:700; color:#1e293b; line-height:1.25;">Pengaturan Umum</div>
                    <div style="margin-top:3px; font-size:12.5px; color:#64748b; line-height:1.35;">Konfigurasi terpadu identitas instansi, profil akun, preferensi sistem, tampilan, dan keamanan</div>
                </div>
            </div>

            <div style="display:flex; align-items:center; gap:8px;">
                <span style="display:inline-flex; align-items:center; gap:6px; padding:5px 12px; background:#eff6ff; border:1px solid #dbeafe; border-radius:20px; font-size:12px; font-weight:600; color:#1e40af;">
                    <span style="width:7px; height:7px; border-radius:50%; background:#2563eb;"></span>
                    Akses Administrator
                </span>
            </div>
        </div>
    </x-slot>

    {{-- Gaya Responsif Khusus Tata Letak 2 Kolom Pengaturan --}}
    <style>
        /* ── Layout & Sidebar Pengaturan ── */
        .settings-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 24px;
            align-items: start;
            max-width: 1200px;
            margin: 0 auto;
        }

        .settings-sidebar {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            padding: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            display: flex;
            flex-direction: column;
            gap: 4px;
            position: sticky;
            top: 84px;
            transition: background 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .settings-nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 10px;
            border: 1px solid transparent;
            background: transparent;
            cursor: pointer;
            text-align: left;
            width: 100%;
            transition: all 0.15s ease;
            position: relative;
        }

        .settings-nav-item:hover {
            background: #f8fafc;
        }

        .settings-nav-item .nav-title {
            font-size: 13.5px;
            font-weight: 600;
            color: #1e293b;
            line-height: 1.25;
            transition: color 0.15s ease;
        }

        .settings-nav-item .nav-sub {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
            line-height: 1.3;
            transition: color 0.15s ease;
        }

        .settings-nav-item .nav-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: #f1f5f9;
            color: #475569;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.15s ease;
        }

        .settings-nav-item:hover .nav-icon {
            background: #e2e8f0;
            color: #1e293b;
        }

        .settings-nav-active {
            background: var(--tc-accent-soft, #eff6ff) !important;
            border-color: transparent !important;
            box-shadow: inset 3px 0 0 var(--tc-accent, #2563eb) !important;
        }

        .settings-nav-active .nav-title {
            color: var(--tc-accent-dark, #1d4ed8) !important;
            font-weight: 700 !important;
        }

        .settings-nav-active .nav-sub {
            color: var(--tc-accent, #2563eb) !important;
        }

        .settings-nav-active .nav-icon {
            color: #ffffff !important;
            background: var(--tc-accent, #2563eb) !important;
        }

        /* ── Dark Mode untuk Sidebar Pengaturan ── */
        [data-theme="dark"] .settings-sidebar,
        .dark .settings-sidebar {
            background: #1e293b !important;
            border-color: #334155 !important;
            box-shadow: 0 4px 16px rgba(0,0,0,0.25) !important;
        }

        [data-theme="dark"] .settings-nav-item:hover,
        .dark .settings-nav-item:hover {
            background: #162032 !important;
        }

        [data-theme="dark"] .settings-nav-item .nav-title,
        .dark .settings-nav-item .nav-title {
            color: #e2e8f0 !important;
        }

        [data-theme="dark"] .settings-nav-item .nav-sub,
        .dark .settings-nav-item .nav-sub {
            color: #94a3b8 !important;
        }

        [data-theme="dark"] .settings-nav-item .nav-icon,
        .dark .settings-nav-item .nav-icon {
            background: #162032 !important;
            border: 1px solid #334155 !important;
            color: #94a3b8 !important;
        }

        [data-theme="dark"] .settings-nav-item:hover .nav-icon,
        .dark .settings-nav-item:hover .nav-icon {
            background: #24354d !important;
            color: #ffffff !important;
            border-color: #475569 !important;
        }

        [data-theme="dark"] .settings-nav-active,
        .dark .settings-nav-active {
            background: rgba(var(--tc-accent-rgb, 37, 99, 235), 0.2) !important;
            box-shadow: inset 3px 0 0 var(--tc-accent, #2563eb) !important;
        }

        [data-theme="dark"] .settings-nav-active .nav-title,
        .dark .settings-nav-active .nav-title {
            color: #93c5fd !important;
            font-weight: 700 !important;
        }

        [data-theme="dark"] .settings-nav-active .nav-sub,
        .dark .settings-nav-active .nav-sub {
            color: #cbd5e1 !important;
        }

        [data-theme="dark"] .settings-nav-active .nav-icon,
        .dark .settings-nav-active .nav-icon {
            background: var(--tc-accent, #2563eb) !important;
            border-color: var(--tc-accent, #2563eb) !important;
            color: #ffffff !important;
        }

        /* ── Kartu Pengaturan (Cards & Forms) ── */
        .settings-card {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: background 0.15s ease, border-color 0.15s ease;
        }

        .settings-card-header {
            padding: 18px 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            background: #ffffff;
        }

        .settings-card-body {
            padding: 24px;
        }

        .settings-card-footer {
            padding: 16px 24px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
        }

        .settings-field-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .settings-field-hint {
            margin-top: 5px;
            font-size: 11.5px;
            color: #64748b;
            line-height: 1.4;
        }

        .settings-input,
        .settings-select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 13.5px;
            color: #1e293b;
            background: #ffffff;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .settings-input:focus,
        .settings-select:focus {
            border-color: var(--tc-accent, #2563eb) !important;
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--tc-accent, #2563eb) 22%, transparent) !important;
        }

        .settings-btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 22px;
            background: var(--tc-accent, #2563eb);
            color: #ffffff !important;
            border: none;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 4px color-mix(in srgb, var(--tc-accent, #2563eb) 35%, transparent);
            transition: background 0.15s ease, transform 0.1s ease, box-shadow 0.15s ease;
            text-decoration: none;
        }

        .settings-btn-primary:hover {
            background: var(--tc-accent-dark, #1d4ed8);
            transform: translateY(-1px);
        }

        .settings-btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px 18px;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.15s ease, border-color 0.15s ease;
        }

        .settings-btn-secondary:hover {
            background: #f1f5f9;
            border-color: #94a3b8;
        }

        .settings-info-card {
            padding: 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            transition: background 0.15s, border-color 0.15s;
        }

        .settings-radio-option {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            cursor: pointer;
            background: #ffffff;
            transition: all 0.15s ease;
        }

        .settings-radio-option:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
        }

        .settings-radio-option.is-selected {
            border-color: var(--tc-accent, #2563eb) !important;
            background: var(--tc-accent-soft, #eff6ff) !important;
        }

        .settings-accent-option {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            cursor: pointer;
            background: #ffffff;
            transition: all 0.15s ease;
        }

        .settings-accent-option:hover {
            border-color: #94a3b8;
        }

        .settings-accent-option.is-selected {
            border-color: var(--tc-accent, #2563eb) !important;
            background: var(--tc-accent-soft, #eff6ff) !important;
        }

        /* ── Dark Mode untuk Kartu Pengaturan ── */
        [data-theme="dark"] .settings-card,
        .dark .settings-card {
            background: #1e293b !important;
            border-color: #334155 !important;
            box-shadow: 0 4px 16px rgba(0,0,0,0.2) !important;
        }

        [data-theme="dark"] .settings-card-header,
        .dark .settings-card-header {
            background: #1e293b !important;
            border-bottom-color: #293548 !important;
        }

        [data-theme="dark"] .settings-card-header h3,
        [data-theme="dark"] .settings-card-header h4,
        .dark .settings-card-header h3,
        .dark .settings-card-header h4 {
            color: #e2e8f0 !important;
        }

        [data-theme="dark"] .settings-card-header p,
        .dark .settings-card-header p {
            color: #94a3b8 !important;
        }

        [data-theme="dark"] .settings-card-footer,
        .dark .settings-card-footer {
            background: #162032 !important;
            border-top-color: #293548 !important;
        }

        [data-theme="dark"] .settings-field-label,
        .dark .settings-field-label {
            color: #e2e8f0 !important;
        }

        [data-theme="dark"] .settings-field-hint,
        .dark .settings-field-hint {
            color: #94a3b8 !important;
        }

        [data-theme="dark"] .settings-input,
        [data-theme="dark"] .settings-select,
        .dark .settings-input,
        .dark .settings-select {
            background: #162032 !important;
            border-color: #334155 !important;
            color: #e2e8f0 !important;
        }

        [data-theme="dark"] .settings-input:focus,
        [data-theme="dark"] .settings-select:focus,
        .dark .settings-input:focus,
        .dark .settings-select:focus {
            border-color: var(--tc-accent, #3b82f6) !important;
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--tc-accent, #3b82f6) 30%, transparent) !important;
        }

        [data-theme="dark"] .settings-btn-secondary,
        .dark .settings-btn-secondary {
            background: #162032 !important;
            border-color: #334155 !important;
            color: #cbd5e1 !important;
        }

        [data-theme="dark"] .settings-btn-secondary:hover,
        .dark .settings-btn-secondary:hover {
            background: #24354d !important;
            border-color: #475569 !important;
            color: #ffffff !important;
        }

        [data-theme="dark"] .settings-info-card,
        .dark .settings-info-card {
            background: #162032 !important;
            border-color: #293548 !important;
            color: #cbd5e1 !important;
        }

        [data-theme="dark"] .settings-info-card strong,
        .dark .settings-info-card strong {
            color: #f1f5f9 !important;
        }

        .settings-logo-preview {
            width: 120px;
            height: 120px;
            border-radius: 14px;
            background: #ffffff;
            border: 2px dashed #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 2px 5px rgba(0,0,0,0.04);
        }

        [data-theme="dark"] .settings-logo-preview,
        .dark .settings-logo-preview {
            background: #0f172a !important;
            border-color: #334155 !important;
        }

        .settings-callout-info {
            padding: 14px 18px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #1e3a8a;
        }

        [data-theme="dark"] .settings-callout-info,
        .dark .settings-callout-info {
            background: rgba(37, 99, 235, 0.12) !important;
            border-color: rgba(37, 99, 235, 0.28) !important;
            color: #93c5fd !important;
        }

        .settings-callout-success {
            padding: 14px 18px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #14532d;
        }

        [data-theme="dark"] .settings-callout-success,
        .dark .settings-callout-success {
            background: rgba(16, 185, 129, 0.12) !important;
            border-color: rgba(16, 185, 129, 0.28) !important;
            color: #86efac !important;
        }

        .settings-callout-danger {
            padding: 14px 18px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            color: #991b1b;
            font-size: 13px;
        }

        [data-theme="dark"] .settings-callout-danger,
        .dark .settings-callout-danger {
            background: rgba(239, 68, 68, 0.12) !important;
            border-color: rgba(239, 68, 68, 0.28) !important;
            color: #fca5a5 !important;
        }

        [data-theme="dark"] .settings-radio-option,
        .dark .settings-radio-option {
            background: #162032 !important;
            border-color: #334155 !important;
        }

        [data-theme="dark"] .settings-radio-option:hover,
        .dark .settings-radio-option:hover {
            border-color: #475569 !important;
            background: #1a2742 !important;
        }

        [data-theme="dark"] .settings-radio-option.is-selected,
        .dark .settings-radio-option.is-selected {
            border-color: var(--tc-accent, #3b82f6) !important;
            background: rgba(var(--tc-accent-rgb, 37, 99, 235), 0.2) !important;
        }

        [data-theme="dark"] .settings-accent-option,
        .dark .settings-accent-option {
            background: #162032 !important;
            border-color: #334155 !important;
        }

        [data-theme="dark"] .settings-accent-option:hover,
        .dark .settings-accent-option:hover {
            border-color: #475569 !important;
        }

        [data-theme="dark"] .settings-accent-option.is-selected,
        .dark .settings-accent-option.is-selected {
            border-color: var(--tc-accent, #3b82f6) !important;
            background: rgba(var(--tc-accent-rgb, 37, 99, 235), 0.2) !important;
        }

        /* ── Mobile & Tablet Responsiveness ── */
        @media (max-width: 900px) {
            .settings-layout {
                grid-template-columns: 1fr;
            }
            .settings-sidebar {
                position: relative;
                top: 0;
                flex-direction: row;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                padding: 6px;
                gap: 6px;
            }
            .settings-nav-item {
                white-space: nowrap;
                flex-shrink: 0;
                width: auto;
                padding: 8px 14px;
            }
            .settings-nav-active {
                box-shadow: none !important;
                background: var(--tc-accent, #2563eb) !important;
            }
            .settings-nav-active .nav-title {
                color: #ffffff !important;
            }
            .settings-nav-active .nav-sub {
                display: none;
            }
            .settings-nav-active .nav-icon {
                color: #ffffff !important;
                background: rgba(255,255,255,0.2) !important;
            }
        }
    </style>

    <div class="settings-layout"
         x-data="{
             activeTab: window.location.hash ? window.location.hash.substring(1) : 'identitas',
             setTab(tab) {
                 this.activeTab = tab;
                 window.location.hash = tab;
             }
         }">

        {{-- ══════════════════════════════════════════════════════════════════
             KOLOM KIRI: SIDEBAR KATEGORI PENGATURAN
        ══════════════════════════════════════════════════════════════════ --}}
        <aside class="settings-sidebar">
            {{-- 1. Identitas Instansi --}}
            <button type="button"
                    @click="setTab('identitas')"
                    class="settings-nav-item"
                    :class="{ 'settings-nav-active': activeTab === 'identitas' }">
                <div class="nav-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 21h18M3 7v14M21 7v14M6 21V11a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v10M9 9V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v4"/>
                    </svg>
                </div>
                <div style="min-width:0; flex:1;">
                    <div class="nav-title">Identitas Instansi</div>
                    <div class="nav-sub">Logo, nama dinas, kontak &amp; kop</div>
                </div>
            </button>

            {{-- 2. Profil Pengguna --}}
            <button type="button"
                    @click="setTab('profil')"
                    class="settings-nav-item"
                    :class="{ 'settings-nav-active': activeTab === 'profil' }">
                <div class="nav-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <div style="min-width:0; flex:1;">
                    <div class="nav-title">Profil Pengguna</div>
                    <div class="nav-sub">Nama, email &amp; foto profil</div>
                </div>
            </button>

            {{-- 3. Preferensi Sistem --}}
            <button type="button"
                    @click="setTab('preferensi')"
                    class="settings-nav-item"
                    :class="{ 'settings-nav-active': activeTab === 'preferensi' }">
                <div class="nav-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="4" y1="21" x2="4" y2="14"/><line x1="4" y1="10" x2="4" y2="3"/><line x1="12" y1="21" x2="12" y2="12"/><line x1="12" y1="8" x2="12" y2="3"/><line x1="20" y1="21" x2="20" y2="16"/><line x1="20" y1="12" x2="20" y2="3"/><line x1="1" y1="14" x2="7" y2="14"/><line x1="9" y1="8" x2="15" y2="8"/><line x1="17" y1="16" x2="23" y2="16"/>
                    </svg>
                </div>
                <div style="min-width:0; flex:1;">
                    <div class="nav-title">Preferensi Sistem</div>
                    <div class="nav-sub">Zona waktu, tanggal &amp; bahasa</div>
                </div>
            </button>

            {{-- 4. Tampilan Dashboard --}}
            <button type="button"
                    @click="setTab('tampilan')"
                    class="settings-nav-item"
                    :class="{ 'settings-nav-active': activeTab === 'tampilan' }">
                <div class="nav-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <polyline points="21 15 16 10 5 21"/>
                    </svg>
                </div>
                <div style="min-width:0; flex:1;">
                    <div class="nav-title">Tampilan Dashboard</div>
                    <div class="nav-sub">Banner selamat datang &amp; tema</div>
                </div>
            </button>

            {{-- 5. Notifikasi --}}
            <button type="button"
                    @click="setTab('notifikasi')"
                    class="settings-nav-item"
                    :class="{ 'settings-nav-active': activeTab === 'notifikasi' }">
                <div class="nav-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                </div>
                <div style="min-width:0; flex:1;">
                    <div class="nav-title">Notifikasi</div>
                    <div class="nav-sub">Pengingat KGB, KP, &amp; pensiun</div>
                </div>
            </button>

            {{-- 6. Keamanan dan Akses --}}
            <button type="button"
                    @click="setTab('keamanan')"
                    class="settings-nav-item"
                    :class="{ 'settings-nav-active': activeTab === 'keamanan' }">
                <div class="nav-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>
                <div style="min-width:0; flex:1;">
                    <div class="nav-title">Keamanan &amp; Akses</div>
                    <div class="nav-sub">Kata sandi akun &amp; durasi sesi</div>
                </div>
            </button>
        </aside>

        {{-- ══════════════════════════════════════════════════════════════════
             KOLOM KANAN: PANEL PENGATURAN KATEGORI AKTIF
        ══════════════════════════════════════════════════════════════════ --}}
        <main style="display:flex; flex-direction:column; gap:20px; min-width:0;">

            {{-- Alert Notifikasi Status --}}
            @if (session('status'))
                <div class="settings-callout-success" style="box-shadow:0 1px 3px rgba(0,0,0,0.04); font-size:13.5px; font-weight:500;">
                    <div style="width:28px; height:28px; border-radius:50%; background:rgba(16, 185, 129, 0.2); display:flex; align-items:center; justify-content:center; color:#10b981; flex-shrink:0;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <div style="flex:1;">
                        @if(session('status') === 'profile-updated')
                            Profil pengguna berhasil diperbarui.
                        @elseif(session('status') === 'password-updated')
                            Kata sandi akun berhasil diperbarui.
                        @else
                            {{ session('status') }}
                        @endif
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="settings-callout-danger" style="box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                    <div style="font-weight:600; margin-bottom:6px; display:flex; align-items:center; gap:8px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Terdapat kesalahan validasi pada formulir:
                    </div>
                    <ul style="margin:0; padding-left:26px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ──────────────────────────────────────────────────────────────
                 PANEL 1: IDENTITAS INSTANSI & APLIKASI
            ────────────────────────────────────────────────────────────── --}}
            <div x-show="activeTab === 'identitas'" x-transition:enter="transition ease-out duration-150">
                <form action="{{ route('pengaturan.identitas.update') }}" method="POST" enctype="multipart/form-data"
                      class="settings-card"
                      x-data="{
                          logoPreview: null,
                          logoName: '',
                          handleLogoSelect(e) {
                              const file = e.target.files[0];
                              if (file) {
                                  this.logoName = file.name;
                                  const reader = new FileReader();
                                  reader.onload = (event) => {
                                      this.logoPreview = event.target.result;
                                  };
                                  reader.readAsDataURL(file);
                              }
                          },
                          cancelLogoSelect() {
                              this.logoPreview = null;
                              this.logoName = '';
                              document.getElementById('input-logo-file').value = '';
                          }
                      }">
                    @csrf
                    @method('PUT')

                    <div class="settings-card-header">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width:38px; height:38px; border-radius:9px; background:var(--tc-accent-soft, #eff6ff); color:var(--tc-accent, #1d4ed8); display:flex; align-items:center; justify-content:center;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 21h18M3 7v14M21 7v14M6 21V11a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v10M9 9V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 style="margin:0; font-size:16px; font-weight:700;">Identitas Instansi &amp; Aplikasi</h3>
                                <p style="margin:2px 0 0; font-size:12.5px;" class="settings-field-hint">Pengaturan nama resmi kedinasan, logo instansi, alamat kantor, dan identitas kop laporan cetak</p>
                            </div>
                        </div>
                    </div>

                    <div class="settings-card-body" style="display:grid; grid-template-columns:240px 1fr; gap:26px;">
                        {{-- Subpanel Kiri: Logo --}}
                        <div class="settings-info-card" style="display:flex; flex-direction:column; align-items:center; text-align:center; gap:14px;">
                            <div class="settings-field-label">Logo Resmi Instansi</div>

                            <div class="settings-logo-preview">
                                <template x-if="logoPreview">
                                    <img :src="logoPreview" alt="Pratinjau Logo" style="max-width:100%; max-height:100%; object-fit:contain;">
                                </template>
                                <template x-if="!logoPreview">
                                    <img src="{{ $logoUrl }}" alt="Logo Saat Ini" style="max-width:100%; max-height:100%; object-fit:contain;">
                                </template>
                            </div>

                            <div>
                                @if($isCustomLogo)
                                    <span style="display:inline-flex; align-items:center; gap:5px; padding:3px 9px; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:14px; font-size:11.5px; font-weight:600; color:#065f46;">
                                        <span style="width:6px; height:6px; border-radius:50%; background:#10b981;"></span>
                                        Logo Kustom Aktif
                                    </span>
                                @else
                                    <span style="display:inline-flex; align-items:center; gap:5px; padding:3px 9px; background:#f1f5f9; border:1px solid #e2e8f0; border-radius:14px; font-size:11.5px; font-weight:600; color:#475569;">
                                        <span style="width:6px; height:6px; border-radius:50%; background:#94a3b8;"></span>
                                        Logo Bawaan Dishub
                                    </span>
                                @endif
                            </div>

                            <div style="width:100%;">
                                <label for="input-logo-file" class="settings-btn-secondary" style="width:100%; cursor:pointer;">
                                    📁 Unggah Logo Baru
                                </label>
                                <input id="input-logo-file" type="file" name="instansi_logo" accept="image/jpeg,image/png,image/webp" style="display:none;" @change="handleLogoSelect($event)">
                                <div class="settings-field-hint" style="margin-top:6px; font-size:11px;">
                                    Format: PNG, JPG, WebP. Maks 2 MB. Disarankan latar transparan.
                                </div>
                            </div>

                            <template x-if="logoName">
                                <div style="font-size:11.5px; color:var(--tc-accent, #2563eb); font-weight:500; display:flex; align-items:center; gap:6px;">
                                    <span x-text="logoName"></span>
                                    <button type="button" @click="cancelLogoSelect()" style="color:#ef4444; border:none; background:none; cursor:pointer; font-weight:bold;">&times;</button>
                                </div>
                            </template>

                            @if($isCustomLogo)
                                <div style="width:100%; margin-top:2px;">
                                    <button type="button"
                                            onclick="if(confirm('Apakah Anda yakin ingin menghapus logo kustom dan mengembalikan ke logo bawaan Dishub Riau?')) { document.getElementById('form-reset-logo').submit(); }"
                                            style="width:100%; padding:6px 10px; background:#fee2e2; border:1px solid #fca5a5; border-radius:6px; font-size:11.5px; font-weight:600; color:#991b1b; cursor:pointer;">
                                        Kembalikan Logo Bawaan
                                    </button>
                                </div>
                            @endif
                        </div>

                        {{-- Subpanel Kanan: Form Data Identitas --}}
                        <div style="display:flex; flex-direction:column; gap:16px;">
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                                <div>
                                    <label class="settings-field-label">
                                        Nama Instansi Resmi <span style="color:#ef4444;">*</span>
                                    </label>
                                    <input type="text" name="instansi_nama" value="{{ old('instansi_nama', $instansiNama) }}" required
                                           class="settings-input"
                                           placeholder="Contoh: Dinas Perhubungan Provinsi Riau">
                                </div>

                                <div>
                                    <label class="settings-field-label">
                                        Pemerintah Daerah / Induk
                                    </label>
                                    <input type="text" name="instansi_sub" value="{{ old('instansi_sub', $instansiSub) }}"
                                           class="settings-input"
                                           placeholder="Contoh: Pemerintah Provinsi Riau">
                                </div>
                            </div>

                            <div style="display:grid; grid-template-columns:1fr 2fr; gap:16px;">
                                <div>
                                    <label class="settings-field-label">
                                        Nama Singkatan Aplikasi <span style="color:#ef4444;">*</span>
                                    </label>
                                    <input type="text" name="app_name" value="{{ old('app_name', $appName) }}" required
                                           class="settings-input"
                                           placeholder="SIPAS-DISHUB">
                                </div>

                                <div>
                                    <label class="settings-field-label">
                                        Deskripsi Singkat Aplikasi
                                    </label>
                                    <input type="text" name="app_description" value="{{ old('app_description', $appDescription) }}"
                                           class="settings-input"
                                           placeholder="Sistem Informasi Persuratan dan Administrasi">
                                </div>
                            </div>

                            <div>
                                <label class="settings-field-label">
                                    Alamat Kantor Kedinasan
                                </label>
                                <textarea name="instansi_alamat" rows="2"
                                          class="settings-input" style="resize:vertical;"
                                          placeholder="Alamat kantor lengkap berserta kode pos">{{ old('instansi_alamat', $instansiAlamat) }}</textarea>
                                <span class="settings-field-hint">Alamat ini dicantumkan pada bagian kop surat resmi saat mencetak laporan persuratan.</span>
                            </div>

                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                                <div>
                                    <label class="settings-field-label">
                                        Nomor Telepon Kantor
                                    </label>
                                    <input type="text" name="instansi_telepon" value="{{ old('instansi_telepon', $instansiTelepon) }}"
                                           class="settings-input"
                                           placeholder="Contoh: (0761) 32155">
                                </div>

                                <div>
                                    <label class="settings-field-label">
                                        Alamat Email Resmi (Pos-el)
                                    </label>
                                    <input type="email" name="instansi_email" value="{{ old('instansi_email', $instansiEmail) }}"
                                           class="settings-input"
                                           placeholder="Contoh: dishub@riau.go.id">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="settings-card-footer">
                        <button type="submit" class="settings-btn-primary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                            </svg>
                            <span>Simpan Identitas Instansi</span>
                        </button>
                    </div>
                </form>

                <form id="form-reset-logo" action="{{ route('pengaturan.logo.reset') }}" method="POST" style="display:none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>

            {{-- ──────────────────────────────────────────────────────────────
                 PANEL 2: PROFIL PENGGUNA & AKUN
            ────────────────────────────────────────────────────────────── --}}
            <div x-show="activeTab === 'profil'" x-transition:enter="transition ease-out duration-150">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                      class="settings-card"
                      x-data="{
                          avatarPreview: null,
                          avatarName: '',
                          handleAvatarSelect(e) {
                              const file = e.target.files[0];
                              if (file) {
                                  this.avatarName = file.name;
                                  const reader = new FileReader();
                                  reader.onload = (event) => {
                                      this.avatarPreview = event.target.result;
                                  };
                                  reader.readAsDataURL(file);
                              }
                          }
                      }">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="redirect_to" value="{{ route('pengaturan.index') }}#profil">

                    <div class="settings-card-header">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width:38px; height:38px; border-radius:9px; background:var(--tc-accent-soft, #eff6ff); color:var(--tc-accent, #1d4ed8); display:flex; align-items:center; justify-content:center;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 style="margin:0; font-size:16px; font-weight:700;">Profil Pengguna &amp; Akun Administrator</h3>
                                <p style="margin:2px 0 0; font-size:12.5px;" class="settings-field-hint">Perbarui nama akun, alamat email resmi, dan unggah foto avatar pribadi</p>
                            </div>
                        </div>
                    </div>

                    <div class="settings-card-body" style="display:grid; grid-template-columns:240px 1fr; gap:26px;">
                        {{-- Subpanel Kiri: Avatar Pengguna --}}
                        <div class="settings-info-card" style="display:flex; flex-direction:column; align-items:center; text-align:center; gap:14px;">
                            <div class="settings-field-label">Foto Profil / Avatar</div>

                            <div style="width:110px; height:110px; border-radius:50%; background:var(--tc-accent, #2563eb); border:3px solid white; display:flex; align-items:center; justify-content:center; overflow:hidden; box-shadow:0 3px 8px rgba(0,0,0,0.12); position:relative;">
                                <template x-if="avatarPreview">
                                    <img :src="avatarPreview" alt="Pratinjau Avatar" style="width:100%; height:100%; object-fit:cover;">
                                </template>
                                <template x-if="!avatarPreview">
                                    @if($currentUser && $currentUser->avatar && file_exists(public_path('avatars/' . $currentUser->avatar)))
                                        <img src="{{ asset('avatars/' . $currentUser->avatar) }}" alt="Avatar" style="width:100%; height:100%; object-fit:cover;">
                                    @else
                                        <span style="font-size:36px; font-weight:800; color:white;">{{ strtoupper(substr($currentUser->name ?? 'A', 0, 1)) }}</span>
                                    @endif
                                </template>
                            </div>

                            <div style="display:flex; flex-direction:column; gap:4px; align-items:center;">
                                <span style="display:inline-flex; align-items:center; gap:5px; padding:3px 10px; background:var(--tc-accent-soft, #dbeafe); border-radius:14px; font-size:11.5px; font-weight:700; color:var(--tc-accent, #1d4ed8);">
                                    Role: {{ strtoupper($currentUser->role?->name ?? 'ADMIN') }}
                                </span>
                                <span class="settings-field-hint" style="margin:0; font-size:11px;">Akun Terverifikasi</span>
                            </div>

                            <div style="width:100%;">
                                <label for="input-avatar-file" class="settings-btn-secondary" style="width:100%; cursor:pointer;">
                                    📷 Ganti Foto Profil
                                </label>
                                <input id="input-avatar-file" type="file" name="avatar" accept="image/jpeg,image/png,image/webp" style="display:none;" @change="handleAvatarSelect($event)">
                                <div class="settings-field-hint" style="margin-top:6px; font-size:11px;">
                                    Format: JPG, PNG, WebP. Maks 2 MB.
                                </div>
                            </div>

                            <template x-if="avatarName">
                                <div style="font-size:11.5px; color:var(--tc-accent, #2563eb); font-weight:500;">
                                    Foto dipilih: <span x-text="avatarName"></span>
                                </div>
                            </template>
                        </div>

                        {{-- Subpanel Kanan: Form Data Profil --}}
                        <div style="display:flex; flex-direction:column; gap:18px;">
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                                <div>
                                    <label class="settings-field-label">
                                        Nama Lengkap <span style="color:#ef4444;">*</span>
                                    </label>
                                    <input type="text" name="name" value="{{ old('name', $currentUser->name ?? '') }}" required
                                           class="settings-input">
                                </div>

                                <div>
                                    <label class="settings-field-label">
                                        Alamat Email Akun <span style="color:#ef4444;">*</span>
                                    </label>
                                    <input type="email" name="email" value="{{ old('email', $currentUser->email ?? '') }}" required
                                           class="settings-input">
                                </div>
                            </div>

                            {{-- Informasi Pegawai Terhubung (Read-only) --}}
                            @if($currentUser && $currentUser->pegawai)
                                <div class="settings-info-card" style="display:flex; flex-direction:column; gap:8px;">
                                    <div style="font-size:12px; font-weight:700; color:var(--tc-accent, #475569); text-transform:uppercase; letter-spacing:0.5px;">
                                        Data Pegawai Kedinasan Terkait
                                    </div>
                                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; font-size:13px;">
                                        <div>
                                            <span class="settings-field-hint">NIP:</span>
                                            <strong style="margin-left:4px;">{{ $currentUser->pegawai->nip ?? '-' }}</strong>
                                        </div>
                                        <div>
                                            <span class="settings-field-hint">Jabatan:</span>
                                            <strong style="margin-left:4px;">{{ $currentUser->pegawai->jabatan ?? '-' }}</strong>
                                        </div>
                                        <div>
                                            <span class="settings-field-hint">Unit Kerja:</span>
                                            <strong style="margin-left:4px;">{{ $currentUser->pegawai->unitKerja?->nama ?? '-' }}</strong>
                                        </div>
                                        <div>
                                            <span class="settings-field-hint">Pangkat/Golongan:</span>
                                            <strong style="margin-left:4px;">{{ $currentUser->pegawai->pangkat_golongan ?? '-' }}</strong>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="settings-card-footer">
                        <button type="submit" class="settings-btn-primary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                            </svg>
                            <span>Simpan Profil Pengguna</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- ──────────────────────────────────────────────────────────────
                 PANEL 3: PREFERENSI SISTEM & REGIONAL
            ────────────────────────────────────────────────────────────── --}}
            <div x-show="activeTab === 'preferensi'" x-transition:enter="transition ease-out duration-150">
                <form action="{{ route('pengaturan.preferensi.update') }}" method="POST"
                      class="settings-card">
                    @csrf
                    @method('PUT')

                    <div class="settings-card-header">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width:38px; height:38px; border-radius:9px; background:var(--tc-accent-soft, #eff6ff); color:var(--tc-accent, #1d4ed8); display:flex; align-items:center; justify-content:center;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                                </svg>
                            </div>
                            <div>
                                <h3 style="margin:0; font-size:16px; font-weight:700;">Preferensi Sistem &amp; Format Regional</h3>
                                <p style="margin:2px 0 0; font-size:12.5px;" class="settings-field-hint">Standarisasi zona waktu kedinasan, format tanggal tampilan antarmuka, dan preferensi bahasa aplikasi</p>
                            </div>
                        </div>
                    </div>

                    <div class="settings-callout-success" style="margin:20px 24px 0;">
                        <div style="color:var(--text-success, #16a34a); margin-top:2px; flex-shrink:0;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </div>
                        <div style="font-size:12.5px; line-height:1.45;">
                            <strong>Jaminan Integritas Data Persuratan:</strong> Pengaturan format tanggal di bawah hanya mengatur representasi visual saat data disajikan kepada pengguna. Format penyimpanan tanggal di database MySQL tetap tersimpan secara aman dalam standar ISO 8601 (<code>YYYY-MM-DD</code>) sehingga tidak mengubah hasil filter rentang tanggal, urutan kronologis, maupun algoritma otomatis nomor surat.
                        </div>
                    </div>

                    <div class="settings-card-body" style="display:grid; grid-template-columns:1fr 1fr; gap:24px;">
                        <div>
                            <label class="settings-field-label">
                                Zona Waktu Operasional (Timezone)
                            </label>
                            <select name="app_timezone" class="settings-select">
                                <option value="Asia/Jakarta" {{ old('app_timezone', $appTimezone) === 'Asia/Jakarta' ? 'selected' : '' }}>
                                    Asia/Jakarta — WIB (Waktu Indonesia Barat, UTC+07:00) [Default Riau]
                                </option>
                                <option value="Asia/Makassar" {{ old('app_timezone', $appTimezone) === 'Asia/Makassar' ? 'selected' : '' }}>
                                    Asia/Makassar — WITA (Waktu Indonesia Tengah, UTC+08:00)
                                </option>
                                <option value="Asia/Jayapura" {{ old('app_timezone', $appTimezone) === 'Asia/Jayapura' ? 'selected' : '' }}>
                                    Asia/Jayapura — WIT (Waktu Indonesia Timur, UTC+09:00)
                                </option>
                            </select>
                            <div class="settings-field-hint">
                                Waktu server saat ini: <strong>{{ \Carbon\Carbon::now()->format('H:i:s') }} WIB</strong>
                            </div>
                        </div>

                        <div>
                            <label class="settings-field-label">
                                Format Tampilan Tanggal
                            </label>
                            <select name="date_format" class="settings-select">
                                <option value="d/m/Y" {{ old('date_format', $dateFormat) === 'd/m/Y' ? 'selected' : '' }}>
                                    d/m/Y — Contoh: {{ date('d/m/Y') }} (Standar Administrasi Indonesia)
                                </option>
                                <option value="Y-m-d" {{ old('date_format', $dateFormat) === 'Y-m-d' ? 'selected' : '' }}>
                                    Y-m-d — Contoh: {{ date('Y-m-d') }} (Standar ISO 8601)
                                </option>
                                <option value="d F Y" {{ old('date_format', $dateFormat) === 'd F Y' ? 'selected' : '' }}>
                                    d F Y — Contoh: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} (Format Resmi Panjang)
                                </option>
                            </select>
                            <div class="settings-field-hint">
                                Format yang digunakan pada tabel daftar surat dan kartu informasi.
                            </div>
                        </div>

                        <div>
                            <label class="settings-field-label">
                                Format Tampilan Waktu
                            </label>
                            <select name="time_format" class="settings-select">
                                <option value="24" {{ old('time_format', $timeFormat) === '24' ? 'selected' : '' }}>
                                    Format 24 Jam — Contoh: {{ date('H:i') }} WIB (Bawaan Kedinasan)
                                </option>
                                <option value="12" {{ old('time_format', $timeFormat) === '12' ? 'selected' : '' }}>
                                    Format 12 Jam (AM/PM) — Contoh: {{ date('h:i A') }}
                                </option>
                            </select>
                            <div class="settings-field-hint">
                                Standar waktu resmi administrasi pemerintahan menggunakan format 24 jam.
                            </div>
                        </div>

                        <div>
                            <label class="settings-field-label">
                                Bahasa Aplikasi (Locale)
                            </label>
                            <select name="app_locale" class="settings-select">
                                <option value="id" {{ old('app_locale', $appLocale) === 'id' ? 'selected' : '' }}>
                                    Bahasa Indonesia (id) — Standar Naskah Dinas
                                </option>
                            </select>
                            <div class="settings-field-hint">
                                Seluruh terminologi, label modul, dan template cetak dioptimalkan untuk Bahasa Indonesia.
                            </div>
                        </div>
                    </div>

                    <div class="settings-card-footer">
                        <button type="submit" class="settings-btn-primary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                            </svg>
                            <span>Simpan Preferensi Sistem</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- ──────────────────────────────────────────────────────────────
                 PANEL 4: TAMPILAN DASHBOARD & BANNER LATAR
            ────────────────────────────────────────────────────────────── --}}
            <div x-show="activeTab === 'tampilan'" x-transition:enter="transition ease-out duration-150" style="display:flex; flex-direction:column; gap:20px;">
                {{-- Kartu Banner Dashboard --}}
                <div class="settings-card"
                     x-data="{
                        bannerPreviewUrl: null,
                        bannerFileName: '',
                        handleBannerSelect(e) {
                            const file = e.target.files[0];
                            if (file) {
                                this.bannerFileName = file.name;
                                const reader = new FileReader();
                                reader.onload = (event) => {
                                    this.bannerPreviewUrl = event.target.result;
                                };
                                reader.readAsDataURL(file);
                            }
                        },
                        cancelBannerSelect() {
                            this.bannerPreviewUrl = null;
                            this.bannerFileName = '';
                            document.getElementById('banner-file-input').value = '';
                        }
                     }">
                    <div class="settings-card-header">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width:38px; height:38px; border-radius:9px; background:var(--tc-accent-soft, #eff6ff); color:var(--tc-accent, #1d4ed8); display:flex; align-items:center; justify-content:center;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <polyline points="21 15 16 10 5 21"/>
                                </svg>
                            </div>
                            <div>
                                <h3 style="margin:0; font-size:16px; font-weight:700;">Gambar Latar Banner Dashboard</h3>
                                <p style="margin:2px 0 0; font-size:12.5px;" class="settings-field-hint">Kustomisasi gambar latar sambutan pada beranda utama dashboard aplikasi</p>
                            </div>
                        </div>
                        <div>
                            @if($isCustomBanner)
                                <span style="display:inline-flex; align-items:center; gap:6px; padding:4px 10px; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:20px; font-size:12px; font-weight:600; color:#047857;">
                                    <span style="width:6px; height:6px; border-radius:50%; background:#10b981;"></span>
                                    Banner Kustom Aktif
                                </span>
                            @else
                                <span style="display:inline-flex; align-items:center; gap:6px; padding:4px 10px; background:#f1f5f9; border:1px solid #e2e8f0; border-radius:20px; font-size:12px; font-weight:600; color:#475569;">
                                    <span style="width:6px; height:6px; border-radius:50%; background:#94a3b8;"></span>
                                    Banner Bawaan Sistem
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="settings-card-body">
                        <div style="margin-bottom:20px;">
                            <label class="settings-field-label" style="margin-bottom:8px;">
                                Pratinjau Banner Dashboard Saat Ini:
                            </label>
                            <div style="position:relative; width:100%; height:180px; border-radius:12px; overflow:hidden; border:1px solid #cbd5e1; box-shadow:inset 0 2px 4px rgba(0,0,0,0.06);">
                                <div style="position:absolute; inset:0; background-size:cover; background-position:center; transition:all 0.3s ease;"
                                     :style="bannerPreviewUrl ? 'background-image: url(' + bannerPreviewUrl + ')' : 'background-image: url({{ $bannerUrl }})'">
                                </div>
                                <div style="position:absolute; inset:0; background:linear-gradient(135deg, rgba(10, 25, 47, 0.84) 0%, rgba(15, 30, 55, 0.72) 100%);"></div>

                                <div style="position:relative; z-index:2; height:100%; padding:22px; display:flex; flex-direction:column; justify-content:center; color:white;">
                                    <span style="display:inline-block; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:#93c5fd; margin-bottom:4px;">
                                        Pratinjau Tampilan Beranda
                                    </span>
                                    <h4 style="margin:0; font-size:20px; font-weight:800; color:white;">
                                        Selamat datang, {{ $currentUser->name ?? 'Administrator' }}
                                    </h4>
                                    <p style="margin:4px 0 0; font-size:12.5px; color:#cbd5e1; max-width:650px;">
                                        {{ $appDescription }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('pengaturan.banner.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div style="display:flex; flex-direction:column; gap:16px;">
                                <div class="settings-info-card" style="border:2px dashed #cbd5e1; padding:22px; text-align:center;">
                                    <div class="settings-field-label" style="font-size:14px; margin-bottom:4px;">
                                        Pilih Berkas Gambar Banner Baru
                                    </div>
                                    <div class="settings-field-hint" style="font-size:12px; margin-bottom:12px;">
                                        Format: JPG, JPEG, PNG, atau WebP. Maksimal 3 MB. Rekomendasi resolusi: 1920 &times; 450 px.
                                    </div>
                                    <input type="file" id="banner-file-input" name="banner_image" accept="image/jpeg,image/png,image/webp"
                                           style="display:none;" @change="handleBannerSelect($event)">
                                    <button type="button" onclick="document.getElementById('banner-file-input').click()"
                                            class="settings-btn-secondary">
                                        Pilih Gambar dari Komputer
                                    </button>
                                    <div x-show="bannerFileName" style="margin-top:10px; font-size:12.5px; color:var(--tc-accent, #2563eb); font-weight:600;">
                                        Berkas terpilih: <span x-text="bannerFileName"></span>
                                    </div>
                                </div>

                                <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <button type="submit" class="settings-btn-primary">
                                            Simpan Gambar Banner
                                        </button>
                                        <button type="button" x-show="bannerPreviewUrl" @click="cancelBannerSelect()"
                                                class="settings-btn-secondary">
                                            Batal Pilih
                                        </button>
                                    </div>

                                    @if($isCustomBanner)
                                        <button type="button" onclick="if(confirm('Apakah Anda yakin ingin menghapus gambar banner kustom dan mengembalikan banner ke gambar bawaan sistem?')) { document.getElementById('form-reset-banner').submit(); }"
                                                style="padding:10px 16px; background:#fee2e2; color:#991b1b; border:1px solid #fca5a5; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;">
                                            Kembalikan Banner Bawaan
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </form>

                        <form id="form-reset-banner" action="{{ route('pengaturan.banner.reset') }}" method="POST" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>

                {{-- Form Tema & Aksen Default --}}
                <form action="{{ route('pengaturan.tampilan.update') }}" method="POST" class="settings-card"
                      x-data="{
                          currentTheme: '{{ old('default_theme', $defaultTheme) }}',
                          currentAccent: '{{ old('default_accent', $defaultAccent) }}',
                          syncPreferences() {
                              try {
                                  var s = JSON.parse(localStorage.getItem('sipas-theme-settings') || '{}');
                                  s.theme = this.currentTheme;
                                  s.accent = this.currentAccent;
                                  localStorage.setItem('sipas-theme-settings', JSON.stringify(s));
                              } catch(e) {}
                          }
                      }"
                      @submit="syncPreferences()">
                    @csrf
                    @method('PUT')

                    <div class="settings-card-header">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width:38px; height:38px; border-radius:9px; background:var(--tc-accent-soft, #eff6ff); color:var(--tc-accent, #1d4ed8); display:flex; align-items:center; justify-content:center;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 10 10 0 0 0 0-20"/>
                                </svg>
                            </div>
                            <div>
                                <h3 style="margin:0; font-size:16px; font-weight:700;">Tema &amp; Warna Aksen Default</h3>
                                <p style="margin:2px 0 0; font-size:12.5px;">Preferensi mode visual awal bagi pengguna sebelum mengatur preferensi pribadi</p>
                            </div>
                        </div>
                    </div>

                    <div class="settings-card-body" style="display:grid; grid-template-columns:1fr; gap:24px;">
                        <div>
                            <label class="settings-field-label">
                                Mode Tampilan Default Sistem
                            </label>
                            <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:12px;">
                                <label class="settings-radio-option" :class="{ 'is-selected': currentTheme === 'light' }" @click="currentTheme = 'light'">
                                    <input type="radio" name="default_theme" value="light" x-model="currentTheme" style="accent-color:var(--tc-accent, #2563eb);">
                                    <div>
                                        <div style="font-size:13px; font-weight:600;" class="settings-field-label" style="margin:0;">☀️ Mode Terang</div>
                                        <div style="font-size:11px;" class="settings-field-hint" style="margin:0;">Palet terang bersih</div>
                                    </div>
                                </label>

                                <label class="settings-radio-option" :class="{ 'is-selected': currentTheme === 'dark' }" @click="currentTheme = 'dark'">
                                    <input type="radio" name="default_theme" value="dark" x-model="currentTheme" style="accent-color:var(--tc-accent, #2563eb);">
                                    <div>
                                        <div style="font-size:13px; font-weight:600;" class="settings-field-label" style="margin:0;">🌙 Mode Gelap</div>
                                        <div style="font-size:11px;" class="settings-field-hint" style="margin:0;">Kontras redup nyaman</div>
                                    </div>
                                </label>

                                <label class="settings-radio-option" :class="{ 'is-selected': currentTheme === 'system' }" @click="currentTheme = 'system'">
                                    <input type="radio" name="default_theme" value="system" x-model="currentTheme" style="accent-color:var(--tc-accent, #2563eb);">
                                    <div>
                                        <div style="font-size:13px; font-weight:600;" class="settings-field-label" style="margin:0;">🖥️ Ikuti Sistem</div>
                                        <div style="font-size:11px;" class="settings-field-hint" style="margin:0;">Otomatis OS</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="settings-field-label">
                                Warna Aksen Default Sistem
                            </label>
                            <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:10px;">
                                <label class="settings-accent-option" :class="{ 'is-selected': currentAccent === 'blue' }" @click="currentAccent = 'blue'">
                                    <input type="radio" name="default_accent" value="blue" x-model="currentAccent" style="accent-color:#2563eb;">
                                    <span style="width:14px; height:14px; border-radius:50%; background:#2563eb; flex-shrink:0;"></span>
                                    <span style="font-size:12.5px; font-weight:600;" class="settings-field-label" style="margin:0;">Biru</span>
                                </label>

                                <label class="settings-accent-option" :class="{ 'is-selected': currentAccent === 'indigo' }" @click="currentAccent = 'indigo'">
                                    <input type="radio" name="default_accent" value="indigo" x-model="currentAccent" style="accent-color:#4f46e5;">
                                    <span style="width:14px; height:14px; border-radius:50%; background:#4f46e5; flex-shrink:0;"></span>
                                    <span style="font-size:12.5px; font-weight:600;" class="settings-field-label" style="margin:0;">Indigo</span>
                                </label>

                                <label class="settings-accent-option" :class="{ 'is-selected': currentAccent === 'purple' }" @click="currentAccent = 'purple'">
                                    <input type="radio" name="default_accent" value="purple" x-model="currentAccent" style="accent-color:#7c3aed;">
                                    <span style="width:14px; height:14px; border-radius:50%; background:#7c3aed; flex-shrink:0;"></span>
                                    <span style="font-size:12.5px; font-weight:600;" class="settings-field-label" style="margin:0;">Ungu</span>
                                </label>

                                <label class="settings-accent-option" :class="{ 'is-selected': currentAccent === 'red' }" @click="currentAccent = 'red'">
                                    <input type="radio" name="default_accent" value="red" x-model="currentAccent" style="accent-color:#dc2626;">
                                    <span style="width:14px; height:14px; border-radius:50%; background:#dc2626; flex-shrink:0;"></span>
                                    <span style="font-size:12.5px; font-weight:600;" class="settings-field-label" style="margin:0;">Merah</span>
                                </label>

                                <label class="settings-accent-option" :class="{ 'is-selected': currentAccent === 'orange' }" @click="currentAccent = 'orange'">
                                    <input type="radio" name="default_accent" value="orange" x-model="currentAccent" style="accent-color:#ea580c;">
                                    <span style="width:14px; height:14px; border-radius:50%; background:#ea580c; flex-shrink:0;"></span>
                                    <span style="font-size:12.5px; font-weight:600;" class="settings-field-label" style="margin:0;">Oranye</span>
                                </label>

                                <label class="settings-accent-option" :class="{ 'is-selected': currentAccent === 'green' }" @click="currentAccent = 'green'">
                                    <input type="radio" name="default_accent" value="green" x-model="currentAccent" style="accent-color:#16a34a;">
                                    <span style="width:14px; height:14px; border-radius:50%; background:#16a34a; flex-shrink:0;"></span>
                                    <span style="font-size:12.5px; font-weight:600;" class="settings-field-label" style="margin:0;">Hijau</span>
                                </label>

                                <label class="settings-accent-option" :class="{ 'is-selected': currentAccent === 'teal' }" @click="currentAccent = 'teal'">
                                    <input type="radio" name="default_accent" value="teal" x-model="currentAccent" style="accent-color:#0d9488;">
                                    <span style="width:14px; height:14px; border-radius:50%; background:#0d9488; flex-shrink:0;"></span>
                                    <span style="font-size:12.5px; font-weight:600;" class="settings-field-label" style="margin:0;">Teal</span>
                                </label>

                                <label class="settings-accent-option" :class="{ 'is-selected': currentAccent === 'cyan' }" @click="currentAccent = 'cyan'">
                                    <input type="radio" name="default_accent" value="cyan" x-model="currentAccent" style="accent-color:#0891b2;">
                                    <span style="width:14px; height:14px; border-radius:50%; background:#0891b2; flex-shrink:0;"></span>
                                    <span style="font-size:12.5px; font-weight:600;" class="settings-field-label" style="margin:0;">Cyan</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="settings-card-footer">
                        <button type="submit" class="settings-btn-primary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                            </svg>
                            <span>Simpan Preferensi Tampilan</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- ──────────────────────────────────────────────────────────────
                 PANEL 5: NOTIFIKASI & PENGINGAT SISTEM
            ────────────────────────────────────────────────────────────── --}}
            <div x-show="activeTab === 'notifikasi'" x-transition:enter="transition ease-out duration-150">
                <form action="{{ route('pengaturan.notifikasi.update') }}" method="POST"
                      class="settings-card">
                    @csrf
                    @method('PUT')

                    <div class="settings-card-header">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width:38px; height:38px; border-radius:9px; background:var(--tc-accent-soft, #eff6ff); color:var(--tc-accent, #1d4ed8); display:flex; align-items:center; justify-content:center;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                                </svg>
                            </div>
                            <div>
                                <h3 style="margin:0; font-size:16px; font-weight:700;">Notifikasi &amp; Pengingat Sistem</h3>
                                <p style="margin:2px 0 0; font-size:12.5px;" class="settings-field-hint">Konfigurasi parameter pengingat berkala persuratan dan batas tempo layanan kepegawaian</p>
                            </div>
                        </div>
                    </div>

                    <div class="settings-card-body" style="display:flex; flex-direction:column; gap:20px;">
                        <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px;">
                            <div class="settings-info-card">
                                <label class="settings-field-label">
                                    Pengingat Gaji Berkala (KGB)
                                </label>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <input type="number" name="reminder_kgb_days" value="{{ old('reminder_kgb_days', $reminderKgbDays) }}" min="1" max="365" required
                                           class="settings-input" style="width:90px; text-align:center; font-weight:700;">
                                    <span class="settings-field-hint" style="margin:0; font-size:13px;">Hari sebelum TMT</span>
                                </div>
                                <div class="settings-field-hint" style="margin-top:6px;">Default: 60 hari kerja sebelum TMT aktif.</div>
                            </div>

                            <div class="settings-info-card">
                                <label class="settings-field-label">
                                    Pengingat Kenaikan Pangkat (KP)
                                </label>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <input type="number" name="reminder_kp_days" value="{{ old('reminder_kp_days', $reminderKpDays) }}" min="1" max="365" required
                                           class="settings-input" style="width:90px; text-align:center; font-weight:700;">
                                    <span class="settings-field-hint" style="margin:0; font-size:13px;">Hari sebelum periode</span>
                                </div>
                                <div class="settings-field-hint" style="margin-top:6px;">Default: 90 hari sebelum periode kenaikan pangkat.</div>
                            </div>

                            <div class="settings-info-card">
                                <label class="settings-field-label">
                                    Pengingat Usia Pensiun (BUP)
                                </label>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <input type="number" name="reminder_bup_days" value="{{ old('reminder_bup_days', $reminderBupDays) }}" min="1" max="730" required
                                           class="settings-input" style="width:90px; text-align:center; font-weight:700;">
                                    <span class="settings-field-hint" style="margin:0; font-size:13px;">Hari sebelum BUP</span>
                                </div>
                                <div class="settings-field-hint" style="margin-top:6px;">Default: 180 hari sebelum pegawai mencapai batas usia.</div>
                            </div>
                        </div>

                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                            <div class="settings-callout-info">
                                <div style="width:32px; height:32px; border-radius:8px; background:rgba(37, 99, 235, 0.2); color:var(--tc-accent, #1d4ed8); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                                </div>
                                <div style="font-size:12.5px;">
                                    <strong>Lonceng Notifikasi Persuratan:</strong> Aktif di bilah navigasi atas untuk memantau surat masuk dan lembar disposisi baru.
                                </div>
                            </div>

                            <div class="settings-callout-success">
                                <div style="width:32px; height:32px; border-radius:8px; background:rgba(16, 185, 129, 0.2); color:#10b981; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                </div>
                                <div style="font-size:12.5px;">
                                    <strong>Status Notifikasi Otomatis:</strong> Notifikasi sistem diperbarui secara real-time berdasarkan query database aktual.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="settings-card-footer">
                        <button type="submit" class="settings-btn-primary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                            </svg>
                            <span>Simpan Parameter Notifikasi</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- ──────────────────────────────────────────────────────────────
                 PANEL 6: KEAMANAN & AKSES AKUN
            ────────────────────────────────────────────────────────────── --}}
            <div x-show="activeTab === 'keamanan'" x-transition:enter="transition ease-out duration-150" style="display:flex; flex-direction:column; gap:20px;">
                {{-- Form Ubah Kata Sandi --}}
                <form action="{{ route('password.update') }}" method="POST" class="settings-card">
                    @csrf
                    @method('PUT')

                    <div class="settings-card-header">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width:38px; height:38px; border-radius:9px; background:var(--tc-accent-soft, #eff6ff); color:var(--tc-accent, #1d4ed8); display:flex; align-items:center; justify-content:center;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 style="margin:0; font-size:16px; font-weight:700;">Ubah Kata Sandi Akun</h3>
                                <p style="margin:2px 0 0; font-size:12.5px;">Pastikan akun Anda menggunakan kata sandi yang panjang dan acak demi keamanan data persuratan</p>
                            </div>
                        </div>
                    </div>

                    <div class="settings-card-body" style="display:flex; flex-direction:column; gap:16px; max-width:560px;">
                        <div>
                            <label class="settings-field-label">
                                Kata Sandi Saat Ini <span style="color:#ef4444;">*</span>
                            </label>
                            <input type="password" name="current_password" required autocomplete="current-password" class="settings-input">
                            @error('current_password', 'updatePassword')
                                <div style="font-size:12px; color:#ef4444; margin-top:4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="settings-field-label">
                                Kata Sandi Baru <span style="color:#ef4444;">*</span>
                            </label>
                            <input type="password" name="password" required autocomplete="new-password" class="settings-input">
                            @error('password', 'updatePassword')
                                <div style="font-size:12px; color:#ef4444; margin-top:4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="settings-field-label">
                                Konfirmasi Kata Sandi Baru <span style="color:#ef4444;">*</span>
                            </label>
                            <input type="password" name="password_confirmation" required autocomplete="new-password" class="settings-input">
                        </div>
                    </div>

                    <div class="settings-card-footer">
                        <button type="submit" class="settings-btn-primary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                            </svg>
                            <span>Perbarui Kata Sandi</span>
                        </button>
                    </div>
                </form>

                {{-- Informasi Durasi Sesi & Proteksi --}}
                <div class="settings-card">
                    <div class="settings-card-header">
                        <h4 style="margin:0; font-size:15px; font-weight:700;">Parameter Keamanan Sesi &amp; Akses Sistem</h4>
                    </div>

                    <div class="settings-card-body" style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                        <div class="settings-info-card">
                            <div style="font-size:12px; color:var(--text-muted, #64748b); font-weight:600;">Masa Aktif Sesi Kerja (Session Lifetime)</div>
                            <div style="font-size:20px; font-weight:800; color:var(--text-primary, #1e293b); margin-top:4px;">
                                {{ $sessionLifetime }} Menit <span style="font-size:13px; font-weight:500; color:var(--text-muted, #64748b);">({{ round($sessionLifetime / 60, 1) }} Jam)</span>
                            </div>
                            <div style="font-size:11.5px; color:var(--text-muted, #64748b); margin-top:2px;">Driver: {{ ucfirst($sessionDriver) }} Session Store</div>
                        </div>

                        <div class="settings-info-card">
                            <div style="font-size:12px; color:var(--text-muted, #64748b); font-weight:600;">Standar Proteksi Data Aktif</div>
                            <ul style="margin:6px 0 0; padding:0; list-style:none; font-size:12px; display:flex; flex-direction:column; gap:4px;">
                                <li>&#10003; <strong>CSRF Protection:</strong> Aktif di seluruh formulir</li>
                                <li>&#10003; <strong>Enkripsi Sandi:</strong> Bcrypt (Cost 12)</li>
                                <li>&#10003; <strong>Access Control:</strong> Middleware CheckRole Admin</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</x-app-layout>
