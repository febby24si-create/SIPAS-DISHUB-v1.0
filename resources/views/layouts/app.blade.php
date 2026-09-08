<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIPAS') }} – Dinas Perhubungan</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
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

                <a href="{{ route('buat-surat.create') }}"
                   class="sidebar-link {{ request()->routeIs('buat-surat.*') ? 'active' : '' }}">
                    <span class="icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </span>
                    Buat Surat
                </a>

                {{-- Surat Masuk (dropdown) --}}
                <div x-data="{ open: {{ request()->routeIs('surat-masuk.*') ? 'true' : 'false' }} }">
                    <a href="#" @click.prevent="open = !open"
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
                            Daftar Surat Masuk
                        </a>
                        <a href="{{ route('surat-masuk.create') }}"
                           class="sidebar-submenu-link {{ request()->routeIs('surat-masuk.create') ? 'active' : '' }}">
                            Input Surat Masuk
                        </a>
                    </div>
                </div>

                {{-- Surat Keluar (dropdown) --}}
                <div x-data="{ open: {{ request()->routeIs('surat-keluar.*') ? 'true' : 'false' }} }">
                    <a href="#" @click.prevent="open = !open"
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
                            Daftar Surat Keluar
                        </a>
                        <a href="{{ route('surat-keluar.draft') }}"
                           class="sidebar-submenu-link {{ request()->routeIs('surat-keluar.draft') ? 'active' : '' }}">
                            Draft Surat
                        </a>
                    </div>
                </div>

                <a href="{{ route('disposisi.index') }}"
                   class="sidebar-link {{ request()->routeIs('disposisi.*') ? 'active' : '' }}">
                    <span class="icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                        </svg>
                    </span>
                    Disposisi Surat
                </a>

                <p class="sidebar-section-label">Arsip</p>

                <a href="{{ route('arsip.index') }}"
                   class="sidebar-link {{ request()->routeIs('arsip.*') ? 'active' : '' }}">
                    <span class="icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 8v13H3V8"/><path d="M1 3h22v5H1z"/><path d="M10 12h4"/>
                        </svg>
                    </span>
                    Arsip Digital
                </a>

                <a href="{{ route('pencarian.index') }}"
                   class="sidebar-link {{ request()->routeIs('pencarian.*') ? 'active' : '' }}">
                    <span class="icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                    </span>
                    Pencarian Surat
                </a>

                <p class="sidebar-section-label">Laporan</p>

                <a href="{{ route('laporan.index') }}"
                   class="sidebar-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                    <span class="icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                        </svg>
                    </span>
                    Laporan Persuratan
                </a>

                <p class="sidebar-section-label">Master Data</p>

                <a href="{{ route('jenis-surat.index') }}"
                   class="sidebar-link {{ request()->routeIs('jenis-surat.*') ? 'active' : '' }}">
                    <span class="icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                        </svg>
                    </span>
                    Jenis Surat
                </a>

                <a href="{{ route('unit-kerja.index') }}"
                   class="sidebar-link {{ request()->routeIs('unit-kerja.*') ? 'active' : '' }}">
                    <span class="icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                    </span>
                    Unit Kerja
                </a>

                <a href="{{ route('pegawai.index') }}"
                   class="sidebar-link {{ request()->routeIs('pegawai.*') ? 'active' : '' }}">
                    <span class="icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </span>
                    Master Pegawai
                </a>

                <a href="{{ route('klasifikasi-surat.index') }}"
                   class="sidebar-link {{ request()->routeIs('klasifikasi-surat.*') ? 'active' : '' }}">
                    <span class="icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 6h16M4 12h10M4 18h6"/>
                        </svg>
                    </span>
                    Klasifikasi Surat
                </a>

                <a href="{{ route('nomor-surat.index') }}"
                   class="sidebar-link {{ request()->routeIs('nomor-surat.*') ? 'active' : '' }}">
                    <span class="icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="4" y="4" width="16" height="16" rx="2"/><path d="M9 9h6v6H9z"/>
                        </svg>
                    </span>
                    Nomor Surat
                </a>

                <a href="{{ route('template-surat.index') }}"
                   class="sidebar-link {{ request()->routeIs('template-surat.*') ? 'active' : '' }}">
                    <span class="icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/>
                            <polyline points="13 2 13 9 20 9"/>
                        </svg>
                    </span>
                    Template Surat
                </a>

                <p class="sidebar-section-label">Pengguna</p>

                <a href="{{ route('pengguna.index') }}"
                   class="sidebar-link {{ request()->routeIs('pengguna.*') ? 'active' : '' }}">
                    <span class="icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                        </svg>
                    </span>
                    Daftar Pengguna
                </a>

                <p class="sidebar-section-label">Pengaturan</p>

                <a href="{{ route('pengaturan.index') }}"
                   class="sidebar-link {{ request()->routeIs('pengaturan.*') ? 'active' : '' }}">
                    <span class="icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                        </svg>
                    </span>
                    Pengaturan Sistem
                </a>
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
                    <div class="topbar-title">{{ $header }}</div>
                    <div class="topbar-subtitle">{{ now()->translatedFormat('l, d F Y') }}</div>
                </div>
                <div class="flex items-center gap-3">
                    {{-- Notification Bell --}}
                    <button class="relative p-2 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-white border border-transparent hover:border-slate-200 transition-all duration-200" style="background:white; border:1px solid rgba(0,0,0,0.07);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    </button>
                </div>
            </header>
            @endisset

            {{-- Page Content --}}
            <main class="page-content">
                {{ $slot }}
            </main>
        </div>

    </body>
</html>
