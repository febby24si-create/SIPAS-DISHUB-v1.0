{{--
    Theme Customizer Component
    Kustomisasi Tampilan – SIPAS Dinas Perhubungan
    Disimpan di localStorage: sipas-theme-settings
--}}

<div
    x-data="themeCustomizer()"
    x-init="init()"
    @keydown.escape.window="open = false"
    id="theme-customizer-root"
>
    {{-- ── Trigger Button (di topbar) ── --}}
    <button
        id="btn-theme-customizer"
        @click="open = true"
        title="Kustomisasi Tampilan"
        style="
            width: 36px;
            height: 36px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.15s ease, border-color 0.15s ease;
            flex-shrink: 0;
        "
        onmouseover="this.style.background='#f1f5f9'; this.style.borderColor='#cbd5e1';"
        onmouseout="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0';"
    >
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="3"/>
            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
        </svg>
    </button>

    {{-- ── Overlay ── --}}
    <div
        x-show="open"
        x-transition:enter="tc-overlay-enter"
        x-transition:enter-start="tc-overlay-enter-start"
        x-transition:enter-end="tc-overlay-enter-end"
        x-transition:leave="tc-overlay-leave"
        x-transition:leave-start="tc-overlay-leave-start"
        x-transition:leave-end="tc-overlay-leave-end"
        @click="open = false"
        class="tc-overlay"
        aria-hidden="true"
    ></div>

    {{-- ── Drawer Panel ── --}}
    <div
        x-show="open"
        x-transition:enter="tc-drawer-enter"
        x-transition:enter-start="tc-drawer-enter-start"
        x-transition:enter-end="tc-drawer-enter-end"
        x-transition:leave="tc-drawer-leave"
        x-transition:leave-start="tc-drawer-leave-start"
        x-transition:leave-end="tc-drawer-leave-end"
        class="tc-drawer"
        role="dialog"
        aria-modal="true"
        aria-label="Kustomisasi Tampilan"
    >
        {{-- Header --}}
        <div class="tc-header">
            <div>
                <p class="tc-title">Kustomisasi Tampilan</p>
                <p class="tc-subtitle">Pengaturan tampilan aplikasi</p>
            </div>
            <button
                @click="open = false"
                class="tc-close-btn"
                title="Tutup"
                aria-label="Tutup panel"
            >
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="tc-body">

            {{-- ── 1. Tema ── --}}
            <div class="tc-section">
                <p class="tc-section-label">Tema</p>
                <div class="tc-theme-grid">

                    <button
                        @click="setTheme('light')"
                        :class="{ 'tc-theme-btn-active': settings.theme === 'light' }"
                        class="tc-theme-btn"
                        title="Tema Terang"
                    >
                        <span class="tc-theme-icon">
                            {{-- Sun --}}
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/>
                                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                                <line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/>
                                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                            </svg>
                        </span>
                        <span class="tc-theme-label">Terang</span>
                    </button>

                    <button
                        @click="setTheme('dark')"
                        :class="{ 'tc-theme-btn-active': settings.theme === 'dark' }"
                        class="tc-theme-btn"
                        title="Tema Gelap"
                    >
                        <span class="tc-theme-icon">
                            {{-- Moon --}}
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                            </svg>
                        </span>
                        <span class="tc-theme-label">Gelap</span>
                    </button>

                    <button
                        @click="setTheme('system')"
                        :class="{ 'tc-theme-btn-active': settings.theme === 'system' }"
                        class="tc-theme-btn"
                        title="Ikuti Sistem"
                    >
                        <span class="tc-theme-icon">
                            {{-- Monitor --}}
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                            </svg>
                        </span>
                        <span class="tc-theme-label">Sistem</span>
                    </button>

                </div>
            </div>

            <div class="tc-divider"></div>

            {{-- ── 2. Warna Aksen ── --}}
            <div class="tc-section">
                <p class="tc-section-label">Warna Aksen</p>
                <div class="tc-accent-grid">

                    <template x-for="color in accentColors" :key="color.key">
                        <button
                            @click="setAccent(color.key)"
                            :title="color.label"
                            class="tc-accent-swatch"
                            :class="{ 'tc-accent-swatch-active': settings.accent === color.key }"
                            :style="`background: ${color.hex};`"
                            :aria-label="color.label"
                        >
                            <span
                                x-show="settings.accent === color.key"
                                class="tc-accent-check"
                            >
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                            </span>
                        </button>
                    </template>

                </div>
                <p class="tc-section-hint" x-text="currentAccentLabel"></p>
            </div>

            <div class="tc-divider"></div>

            {{-- ── 3. Radius Sudut ── --}}
            <div class="tc-section">
                <p class="tc-section-label">Radius Sudut</p>
                <div class="tc-radius-grid">

                    <template x-for="opt in radiusOptions" :key="opt.key">
                        <button
                            @click="setRadius(opt.key)"
                            :class="{ 'tc-radius-btn-active': settings.radius === opt.key }"
                            class="tc-radius-btn"
                            :title="opt.label"
                        >
                            <span
                                class="tc-radius-preview"
                                :style="`border-radius: ${opt.preview};`"
                            ></span>
                            <span class="tc-radius-label" x-text="opt.label"></span>
                        </button>
                    </template>

                </div>
            </div>

            <div class="tc-divider"></div>

            {{-- ── 4. Sidebar ── --}}
            <div class="tc-section">
                <p class="tc-section-label">Tampilan Sidebar</p>
                <div class="tc-sidebar-grid">

                    <button
                        @click="setSidebar('full')"
                        :class="{ 'tc-sidebar-btn-active': settings.sidebar === 'full' }"
                        class="tc-sidebar-btn"
                        title="Sidebar Penuh"
                    >
                        {{-- Icon sidebar penuh --}}
                        <span class="tc-sidebar-preview tc-sidebar-preview-full">
                            <span class="tc-sp-bar tc-sp-bar-wide"></span>
                            <span class="tc-sp-content"></span>
                        </span>
                        <span class="tc-sidebar-label">Penuh</span>
                    </button>

                    <button
                        @click="setSidebar('compact')"
                        :class="{ 'tc-sidebar-btn-active': settings.sidebar === 'compact' }"
                        class="tc-sidebar-btn"
                        title="Sidebar Ringkas"
                    >
                        {{-- Icon sidebar ringkas --}}
                        <span class="tc-sidebar-preview tc-sidebar-preview-compact">
                            <span class="tc-sp-bar tc-sp-bar-narrow"></span>
                            <span class="tc-sp-content"></span>
                        </span>
                        <span class="tc-sidebar-label">Ringkas</span>
                    </button>

                </div>
            </div>

        </div>

        {{-- Footer – Reset --}}
        <div class="tc-footer">
            <button
                @click="resetSettings()"
                class="tc-reset-btn"
                type="button"
            >
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4.02"/>
                </svg>
                Atur Ulang ke Default
            </button>
        </div>
    </div>
</div>

{{-- ── Alpine.js Component Script ── --}}
<script>
function themeCustomizer() {
    return {
        open: false,

        settings: {
            theme: 'light',
            accent: 'blue',
            radius: 'small',
            sidebar: 'full',
        },

        accentColors: [
            { key: 'blue',   label: 'Biru',   hex: '#2563eb' },
            { key: 'indigo', label: 'Indigo',  hex: '#4f46e5' },
            { key: 'purple', label: 'Ungu',    hex: '#7c3aed' },
            { key: 'red',    label: 'Merah',   hex: '#dc2626' },
            { key: 'orange', label: 'Oranye',  hex: '#ea580c' },
            { key: 'green',  label: 'Hijau',   hex: '#16a34a' },
            { key: 'teal',   label: 'Teal',    hex: '#0d9488' },
            { key: 'cyan',   label: 'Cyan',    hex: '#0891b2' },
        ],

        radiusOptions: [
            { key: 'sharp',  label: 'Tajam',  preview: '0px',  value: '0px'   },
            { key: 'small',  label: 'Kecil',  preview: '6px',  value: '6px'   },
            { key: 'medium', label: 'Sedang', preview: '10px', value: '10px'  },
            { key: 'large',  label: 'Besar',  preview: '16px', value: '16px'  },
        ],

        get currentAccentLabel() {
            const found = this.accentColors.find(c => c.key === this.settings.accent);
            return found ? found.label : '';
        },

        init() {
            // Muat dari localStorage
            const saved = localStorage.getItem('sipas-theme-settings');
            if (saved) {
                try {
                    const parsed = JSON.parse(saved);
                    this.settings = { ...this.settings, ...parsed };
                } catch(e) {
                    // Abaikan, gunakan default
                }
            }
            // Terapkan semua setting
            this.applyAll();
            // Pantau perubahan prefers-color-scheme untuk mode 'system'
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                if (this.settings.theme === 'system') {
                    this.applyTheme();
                }
            });
        },

        save() {
            localStorage.setItem('sipas-theme-settings', JSON.stringify(this.settings));
        },

        applyAll() {
            this.applyTheme();
            this.applyAccent();
            this.applyRadius();
            this.applySidebar();
        },

        // ── Theme ──
        setTheme(val) {
            this.settings.theme = val;
            this.applyTheme();
            this.save();
        },

        applyTheme() {
            const html = document.documentElement;
            let isDark = false;
            if (this.settings.theme === 'dark') {
                isDark = true;
            } else if (this.settings.theme === 'system') {
                isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            }
            if (isDark) {
                html.setAttribute('data-theme', 'dark');
            } else {
                html.setAttribute('data-theme', 'light');
            }
        },

        // ── Accent ──
        setAccent(val) {
            this.settings.accent = val;
            this.applyAccent();
            this.save();
        },

        applyAccent() {
            const map = {
                blue:   { main: '#2563eb', dark: '#1d4ed8', soft: '#eff6ff', sidebar: '#1e40af' },
                indigo: { main: '#4f46e5', dark: '#4338ca', soft: '#eef2ff', sidebar: '#3730a3' },
                purple: { main: '#7c3aed', dark: '#6d28d9', soft: '#f5f3ff', sidebar: '#5b21b6' },
                red:    { main: '#dc2626', dark: '#b91c1c', soft: '#fef2f2', sidebar: '#991b1b' },
                orange: { main: '#ea580c', dark: '#c2410c', soft: '#fff7ed', sidebar: '#9a3412' },
                green:  { main: '#16a34a', dark: '#15803d', soft: '#f0fdf4', sidebar: '#166534' },
                teal:   { main: '#0d9488', dark: '#0f766e', soft: '#f0fdfa', sidebar: '#115e59' },
                cyan:   { main: '#0891b2', dark: '#0e7490', soft: '#ecfeff', sidebar: '#155e75' },
            };
            const c = map[this.settings.accent] || map.blue;
            const root = document.documentElement;
            root.style.setProperty('--tc-accent',      c.main);
            root.style.setProperty('--tc-accent-dark', c.dark);
            root.style.setProperty('--tc-accent-soft', c.soft);
            root.style.setProperty('--tc-accent-sidebar', c.sidebar);
        },

        // ── Radius ──
        setRadius(val) {
            this.settings.radius = val;
            this.applyRadius();
            this.save();
        },

        applyRadius() {
            const map = {
                sharp:  { sm: '0px',  md: '0px',  lg: '0px'  },
                small:  { sm: '4px',  md: '6px',  lg: '8px'  },
                medium: { sm: '6px',  md: '10px', lg: '14px' },
                large:  { sm: '10px', md: '16px', lg: '24px' },
            };
            const r = map[this.settings.radius] || map.small;
            const root = document.documentElement;
            root.style.setProperty('--tc-radius-sm', r.sm);
            root.style.setProperty('--tc-radius-md', r.md);
            root.style.setProperty('--tc-radius-lg', r.lg);
            // Set data-radius attribute agar CSS selector [data-radius="*"] dapat bekerja
            root.setAttribute('data-radius', this.settings.radius || 'small');
        },

        // ── Sidebar ──
        setSidebar(val) {
            this.settings.sidebar = val;
            this.applySidebar();
            this.save();
        },

        applySidebar() {
            const html = document.documentElement;
            if (this.settings.sidebar === 'compact') {
                html.setAttribute('data-sidebar', 'compact');
            } else {
                html.setAttribute('data-sidebar', 'full');
            }
        },

        // ── Reset ──
        resetSettings() {
            this.settings = {
                theme:   'light',
                accent:  'blue',
                radius:  'small',
                sidebar: 'full',
            };
            this.applyAll();
            this.save();
        },
    };
}
</script>
