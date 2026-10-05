<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIPAS') }} – Dinas Perhubungan</title>

        <link rel="icon" type="image/png" href="{{ asset('logo-dishub.png') }}">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Anti-flash: terapkan theme/sidebar dari localStorage sebelum render --}}
        <script>
            (function() {
                try {
                    var s = JSON.parse(localStorage.getItem('sipas-theme-settings') || '{}');
                    var theme = s.theme || 'light';
                    var sidebar = s.sidebar || 'full';
                    var isDark = theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
                    document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
                    document.documentElement.setAttribute('data-sidebar', sidebar);
                    // Accent variables
                    var accentMap = {
                        blue:   { main:'#2563eb', dark:'#1d4ed8', soft:'#eff6ff', sb:'#1e40af' },
                        indigo: { main:'#4f46e5', dark:'#4338ca', soft:'#eef2ff', sb:'#3730a3' },
                        purple: { main:'#7c3aed', dark:'#6d28d9', soft:'#f5f3ff', sb:'#5b21b6' },
                        red:    { main:'#dc2626', dark:'#b91c1c', soft:'#fef2f2', sb:'#991b1b' },
                        orange: { main:'#ea580c', dark:'#c2410c', soft:'#fff7ed', sb:'#9a3412' },
                        green:  { main:'#16a34a', dark:'#15803d', soft:'#f0fdf4', sb:'#166534' },
                        teal:   { main:'#0d9488', dark:'#0f766e', soft:'#f0fdfa', sb:'#115e59' },
                        cyan:   { main:'#0891b2', dark:'#0e7490', soft:'#ecfeff', sb:'#155e75' },
                    };
                    var accent = s.accent || 'blue';
                    var c = accentMap[accent] || accentMap.blue;
                    var root = document.documentElement;
                    root.style.setProperty('--tc-accent', c.main);
                    root.style.setProperty('--tc-accent-dark', c.dark);
                    root.style.setProperty('--tc-accent-soft', c.soft);
                    root.style.setProperty('--tc-accent-sidebar', c.sb);
                    // Radius variables + data-radius attribute
                    var radiusMap = {
                        sharp:  { sm:'0px',  md:'0px',  lg:'0px'  },
                        small:  { sm:'4px',  md:'6px',  lg:'8px'  },
                        medium: { sm:'6px',  md:'10px', lg:'14px' },
                        large:  { sm:'10px', md:'16px', lg:'24px' },
                    };
                    var radius = s.radius || 'small';
                    var r = radiusMap[radius] || radiusMap.small;
                    root.style.setProperty('--tc-radius-sm', r.sm);
                    root.style.setProperty('--tc-radius-md', r.md);
                    root.style.setProperty('--tc-radius-lg', r.lg);
                    root.setAttribute('data-radius', radius);
                } catch(e) {
                    document.documentElement.setAttribute('data-theme', 'light');
                    document.documentElement.setAttribute('data-sidebar', 'full');
                    document.documentElement.setAttribute('data-radius', 'small');
                }
            })();
        </script>

    </head>
    <body class="font-sans antialiased" style="background:#f0f4f8;">

        {{-- ════════ SIDEBAR ════════ --}}
        <aside class="sidebar" id="sidebar">
            {{-- Logo / Brand --}}
            <div class="sidebar-logo">
                <div class="sidebar-logo-icon" style="background:transparent; padding:0; width:auto; height:auto;">
                    <img src="{{ asset('logoDishub.png') }}"
                         alt="Logo Dishub"
                         style="width:42px; height:42px; object-fit:contain; border-radius:8px;">
                </div>
                <div class="sidebar-logo-text">
                    <span class="title">SIPAS</span>
                    <span class="subtitle">Dinas Perhubungan</span>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="sidebar-nav">
                <p class="sidebar-section-label">Dashboard</p>

                <a href="{{ route('dashboard') }}"
                   data-tooltip="Dashboard"
                   class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                            <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                        </svg>
                    </span>
                    Dashboard
                </a>

                <p class="sidebar-section-label">Persuratan</p>

                {{-- Surat Masuk (dropdown) --}}
                <div x-data="{ open: {{ request()->routeIs('surat-masuk.*') ? 'true' : 'false' }} }">
                    <a href="#" @click.prevent="open = !open"
                       data-tooltip="Surat Masuk"
                       class="sidebar-link {{ request()->routeIs('surat-masuk.*') ? 'active' : '' }}">
                        <span class="icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/>
                                <path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>
                            </svg>
                        </span>
                        Surat Masuk
                        <span class="arrow" :class="open ? 'rotated' : ''">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                        </span>
                    </a>
                    <div class="sidebar-submenu" x-show="open">
                        <a href="{{ route('surat-masuk.index') }}"
                           class="sidebar-submenu-link {{ request()->routeIs('surat-masuk.index') ? 'active' : '' }}">
                            Daftar Surat
                        </a>
                        <a href="{{ route('surat-masuk.create') }}"
                           class="sidebar-submenu-link {{ request()->routeIs('surat-masuk.create') ? 'active' : '' }}">
                            Input Surat
                        </a>
                    </div>
                </div>

                {{-- Surat Keluar (dropdown) --}}
                <div x-data="{ open: {{ request()->routeIs('surat-keluar.*') ? 'true' : 'false' }} }">
                    <a href="#" @click.prevent="open = !open"
                       data-tooltip="Surat Keluar"
                       class="sidebar-link {{ request()->routeIs('surat-keluar.*') ? 'active' : '' }}">
                        <span class="icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>
                            </svg>
                        </span>
                        Surat Keluar
                        <span class="arrow" :class="open ? 'rotated' : ''">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                        </span>
                    </a>
                    <div class="sidebar-submenu" x-show="open">
                        <a href="{{ route('surat-keluar.index') }}"
                           class="sidebar-submenu-link {{ request()->routeIs('surat-keluar.index') ? 'active' : '' }}">
                            Daftar Surat
                        </a>
                        <a href="{{ route('surat-keluar.draft') }}"
                           class="sidebar-submenu-link {{ request()->routeIs('surat-keluar.draft') ? 'active' : '' }}">
                            Draft Surat
                        </a>
                    </div>
                </div>

                <p class="sidebar-section-label">Arsip</p>

                <a href="{{ route('arsip.index') }}"
                   data-tooltip="Arsip Digital"
                   class="sidebar-link {{ request()->routeIs('arsip.*') ? 'active' : '' }}">
                    <span class="icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 8v13H3V8"/><path d="M1 3h22v5H1z"/><path d="M10 12h4"/>
                        </svg>
                    </span>
                    Arsip Digital
                </a>

                <a href="{{ route('pencarian.index') }}"
                   data-tooltip="Pencarian Surat"
                   class="sidebar-link {{ request()->routeIs('pencarian.*') ? 'active' : '' }}">
                    <span class="icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                    </span>
                    Pencarian Surat
                </a>

                <p class="sidebar-section-label">Kepegawaian</p>

                {{-- Pengajuan Cuti --}}
                <a href="{{ route('kepegawaian.cuti.index') }}"
                   data-tooltip="Pengajuan Cuti"
                   class="sidebar-link {{ request()->routeIs('kepegawaian.cuti.*') ? 'active' : '' }}">
                    <span class="icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                    </span>
                    Pengajuan Cuti
                </a>

                {{-- Kenaikan Pangkat (Dinonaktifkan - Pindah ke SRIKANDI) --}}
                {{--
                <a href="{{ route('kepegawaian.pangkat.index') }}"
                   class="sidebar-link {{ request()->routeIs('kepegawaian.pangkat.*') ? 'active' : '' }}">
                    <span class="icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>
                        </svg>
                    </span>
                    Kenaikan Pangkat
                </a>
                --}}

                {{-- Gaji Berkala --}}
                <a href="{{ route('kepegawaian.kgb.index') }}"
                   data-tooltip="Gaji Berkala"
                   class="sidebar-link {{ request()->routeIs('kepegawaian.kgb.*') ? 'active' : '' }}">
                    <span class="icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                        </svg>
                    </span>
                    Gaji Berkala
                </a>

                <p class="sidebar-section-label">Laporan</p>

                <a href="{{ route('laporan.index') }}"
                   data-tooltip="Laporan Persuratan"
                   class="sidebar-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                    <span class="icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                        </svg>
                    </span>
                    Laporan Persuratan
                </a>

                {{-- Buku Tamu (Admin) --}}
                <a href="{{ route('buku-tamu.index') }}"
                   data-tooltip="Buku Tamu"
                   class="sidebar-link {{ request()->routeIs('buku-tamu.index') || request()->routeIs('buku-tamu.show') ? 'active' : '' }}">
                    <span class="icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                        </svg>
                    </span>
                    Buku Tamu
                </a>

                {{-- Master Data - Persuratan --}}
                <p class="sidebar-section-label">Master Data</p>
                <div x-data="{ open: {{ request()->routeIs('jenis-surat.*') || request()->routeIs('klasifikasi-surat.*') || request()->routeIs('nomor-surat.*') ? 'true' : 'false' }} }">
                    <a href="#" @click.prevent="open = !open"
                       data-tooltip="Persuratan"
                       class="sidebar-link {{ request()->routeIs('jenis-surat.*') || request()->routeIs('klasifikasi-surat.*') || request()->routeIs('nomor-surat.*') ? 'active' : '' }}">
                        <span class="icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                            </svg>
                        </span>
                        Persuratan
                        <span class="arrow" :class="open ? 'rotated' : ''">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                        </span>
                    </a>
                    <div class="sidebar-submenu" x-show="open">
                        <a href="{{ route('jenis-surat.index') }}"
                           class="sidebar-submenu-link {{ request()->routeIs('jenis-surat.*') ? 'active' : '' }}">
                            Jenis Surat
                        </a>
                        <a href="{{ route('klasifikasi-surat.index') }}"
                           class="sidebar-submenu-link {{ request()->routeIs('klasifikasi-surat.*') ? 'active' : '' }}">
                            Klasifikasi Surat
                        </a>
                        <a href="{{ route('nomor-surat.index') }}"
                           class="sidebar-submenu-link {{ request()->routeIs('nomor-surat.*') ? 'active' : '' }}">
                            Nomor Surat
                        </a>
                    </div>
                </div>

                {{-- Master Data - Kepegawaian & Organisasi --}}
                <div x-data="{ open: {{ request()->routeIs('unit-kerja.*') || request()->routeIs('pegawai.*') ? 'true' : 'false' }} }">
                    <a href="#" @click.prevent="open = !open"
                       data-tooltip="Kepeg. & Organisasi"
                       class="sidebar-link {{ request()->routeIs('unit-kerja.*') || request()->routeIs('pegawai.*') ? 'active' : '' }}">
                        <span class="icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                        </span>
                        Kepeg. & Organisasi
                        <span class="arrow" :class="open ? 'rotated' : ''">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                        </span>
                    </a>
                    <div class="sidebar-submenu" x-show="open">
                        <a href="{{ route('unit-kerja.index') }}"
                           class="sidebar-submenu-link {{ request()->routeIs('unit-kerja.*') ? 'active' : '' }}">
                            Unit Kerja
                        </a>
                        <a href="{{ route('pegawai.index') }}"
                           class="sidebar-submenu-link {{ request()->routeIs('pegawai.*') ? 'active' : '' }}">
                            Pegawai
                        </a>
                    </div>
                </div>

                <p class="sidebar-section-label">Pengguna</p>

                <a href="{{ route('pengguna.index') }}"
                   data-tooltip="Daftar Pengguna"
                   class="sidebar-link {{ request()->routeIs('pengguna.*') ? 'active' : '' }}">
                    <span class="icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                    </span>
                    Daftar Pengguna
                </a>

                <p class="sidebar-section-label">Pengaturan</p>

                <div x-data="{ open: {{ request()->routeIs('pengaturan.*') ? 'true' : 'false' }} }">
                    <a href="#" @click.prevent="open = !open"
                       data-tooltip="Pengaturan"
                       class="sidebar-link {{ request()->routeIs('pengaturan.*') ? 'active' : '' }}">
                        <span class="icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                            </svg>
                        </span>
                        Pengaturan
                        <span class="arrow" :class="open ? 'rotated' : ''">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                        </span>
                    </a>
                    <div class="sidebar-submenu" x-show="open">
                        <a href="{{ route('pengaturan.index') }}"
                           class="sidebar-submenu-link {{ request()->routeIs('pengaturan.index') ? 'active' : '' }}">
                            Pengaturan Umum
                        </a>
                        <a href="{{ route('pengaturan.kepegawaian.index') }}"
                           class="sidebar-submenu-link {{ request()->routeIs('pengaturan.kepegawaian.*') || request()->routeIs('pengaturan.kategori-bup.*') ? 'active' : '' }}">
                            Pengaturan Kepegawaian
                        </a>
                    </div>
                </div>
            </nav>

            {{-- User Footer --}}
            <div class="sidebar-footer">
                <div x-data="{ open: false }" class="relative">
                    <div class="sidebar-user-card" @click="open = !open">
                        <div class="sidebar-avatar" style="padding:0; overflow:hidden;">
                            @if(Auth::user()->avatar && file_exists(public_path('avatars/' . Auth::user()->avatar)))
                                <img src="{{ asset('avatars/' . Auth::user()->avatar) }}"
                                     alt="Foto Profil"
                                     style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
                            @else
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            @endif
                        </div>
                        <div class="sidebar-user-info flex-1 min-w-0">
                            <p class="name truncate">{{ Auth::user()->name }}</p>
                            <p class="role">{{ Auth::user()->role?->name ?? 'Administrator' }}</p>
                        </div>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" :class="open ? 'rotate-180' : ''" style="transition:transform 0.2s; flex-shrink:0;">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </div>

                    {{-- User Dropdown --}}
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2"
                         @click.outside="open = false"
                         class="absolute bottom-full left-0 right-0 mb-2 bg-white rounded-2xl shadow-xl border py-2 z-50"
                         style="border-color:rgba(0,0,0,0.08); box-shadow:0 8px 30px rgba(0,0,0,0.15);">

                        <a href="{{ route('profile.edit') }}" class="dropdown-item" style="color:#374151; text-decoration:none; display:flex; align-items:center; gap:10px; padding:10px 16px; transition:background 0.15s;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Profil Saya
                        </a>

                        <div style="margin:4px 0; border-top:1px solid rgba(0,0,0,0.06);"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item w-full text-left" style="color:#dc2626; background:none; border:none; cursor:pointer; display:flex; align-items:center; gap:10px; padding:10px 16px; width:100%; transition:background 0.15s;">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        {{-- ════════ MAIN CONTENT ════════ --}}
        <div class="main-content">
            {{-- Topbar --}}
            @isset($header)
            <header class="topbar">
                <div>
                    {{-- Breadcrumb --}}
                    @isset($breadcrumb)
                    <div style="font-size:11px; color:#94a3b8; margin-bottom:3px; display:flex; align-items:center; gap:4px;">
                        <span>Aplikasi</span>
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                        <span>{{ $breadcrumb }}</span>
                    </div>
                    @endisset
                    <div class="topbar-title">{{ $header }}</div>
                </div>
                <div class="flex items-center gap-2">
                    <div style="display:flex; align-items:center; gap:6px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:7px 12px;">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        <span style="font-size:12px; color:#475569; font-weight:500;">{{ now()->translatedFormat('l, d F Y') }}</span>
                    </div>
                    <button style="width:36px; height:36px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; display:flex; align-items:center; justify-content:center; cursor:pointer;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    </button>
                    {{-- Theme Customizer Trigger --}}
                    <x-theme-customizer />
                </div>
            </header>
            @endisset

            {{-- Page Content --}}
            <main class="page-content">
                {{ $slot }}
            </main>
        </div>

        {{-- ════════ DARK MODE: Dropdown & DOM Patches ════════ --}}
        {{-- Patch onmouseover/onmouseout dan Alpine :style yang inject warna hardcoded --}}
        <script>
        (function() {
            function isDark() {
                return document.documentElement.getAttribute('data-theme') === 'dark';
            }

            /* ── Patch onmouseover pada dropdown item ── */
            function patchDropdownItems(root) {
                root = root || document;
                // Semua div yang punya onmouseover berisi backgroundColor
                root.querySelectorAll('div[onmouseover*="backgroundColor"]').forEach(function(el) {
                    if (el._dmPatched) return;
                    el._dmPatched = true;

                    const origOver = el.getAttribute('onmouseover') || '';
                    const origOut  = el.getAttribute('onmouseout')  || '';

                    el.addEventListener('mouseover', function(e) {
                        if (isDark()) {
                            this.style.backgroundColor = '#1e3a5c'; // dark hover: biru navy subtle
                        }
                        // light mode: biarkan inline onmouseover berjalan normal
                    }, true);

                    el.addEventListener('mouseout', function(e) {
                        if (isDark()) {
                            this.style.backgroundColor = 'transparent';
                        }
                    }, true);
                });

                // Patch tr.aktivitas-row jika ada
                root.querySelectorAll('.aktivitas-row').forEach(function(tr) {
                    if (tr._dmPatched) return;
                    tr._dmPatched = true;
                    tr.addEventListener('mouseover', function() {
                        this.style.background = isDark() ? '#162032' : '#f8fafc';
                    });
                    tr.addEventListener('mouseout', function() {
                        this.style.background = 'transparent';
                    });
                });
            }

            /* ── Patch Alpine :style span yang inject color:#1e293b ── */
            function patchAlpineSpans(root) {
                root = root || document;
                // Span dalam dropdown trigger yang punya inline color:#1e293b saat dipilih
                root.querySelectorAll('button[style*="background:#fff"] span[style*="color:#1e293b"], button[style*="background: #fff"] span[style*="color:#1e293b"]').forEach(function(el) {
                    if (isDark() && el.style.color === '#1e293b' || el.style.color === 'rgb(30, 41, 59)') {
                        el.style.color = '#e2e8f0';
                    }
                });
            }

            /* ── Jalankan patch saat DOM ready ── */
            function runAllPatches() {
                patchDropdownItems();
                patchAlpineSpans();
            }

            document.addEventListener('DOMContentLoaded', runAllPatches);

            /* ── MutationObserver: patch item baru yang dirender Alpine x-for ── */
            var observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(m) {
                    if (m.type === 'childList' && m.addedNodes.length) {
                        m.addedNodes.forEach(function(node) {
                            if (node.nodeType === 1) {
                                patchDropdownItems(node.parentElement || document);
                                patchAlpineSpans(node.parentElement || document);
                            }
                        });
                    }
                });
            });
            document.addEventListener('DOMContentLoaded', function() {
                observer.observe(document.body, { childList: true, subtree: true });
            });

        })();
        </script>

        @stack('scripts')

    </body>
</html>
