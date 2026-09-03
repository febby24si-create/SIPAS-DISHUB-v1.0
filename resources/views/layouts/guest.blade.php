<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SIPAS') }} – Login | Dinas Perhubungan Provinsi Riau</title>
    <meta name="description" content="Sistem Informasi Persuratan Dinas Perhubungan Provinsi Riau">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ─── Reset & base ─────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; }
        html, body { margin: 0; padding: 0; height: 100%; font-family: 'Inter', sans-serif; }

        /* ─── Page wrapper ─────────────────────────────── */
        .lp-wrap {
            display: flex;
            min-height: 100vh;
            overflow: hidden;
        }

        /* ═══════════════════════════════════════════════════
           LEFT PANEL  (70%)
        ═══════════════════════════════════════════════════ */
        .lp-left {
            flex: 0 0 70%;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px 64px 40px;
            overflow: hidden;

            /* Base colour – dark navy */
            background: #0d1b35;
        }

        /* Gradient mesh layer */
        .lp-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 70% 55% at 15% 5%,  rgba(59,130,246,.50) 0%, transparent 55%),
                radial-gradient(ellipse 55% 65% at 85% 90%, rgba(139,92,246,.40) 0%, transparent 55%),
                radial-gradient(ellipse 45% 40% at 75% 20%, rgba(14,165,233,.30) 0%, transparent 50%);
            pointer-events: none;
        }

        /* Subtle dot-grid pattern */
        .lp-left::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255,255,255,.07) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
        }

        /* All content above the pseudo-elements */
        .lp-left-inner {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            height: 100%;
            justify-content: space-between;
        }

        /* ── Brand / Logo ── */
        .lp-brand {
            display: inline-flex;
            align-items: center;
            gap: 16px;
        }
        .lp-brand-logo-wrap {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: rgba(255,255,255,.10);
            border: 1px solid rgba(255,255,255,.18);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
        }
        .lp-brand-logo-wrap img {
            width: 44px;
            height: 44px;
            object-fit: contain;
        }
        .lp-brand-text .name {
            color: #ffffff;
            font-size: 19px;
            font-weight: 800;
            letter-spacing: -.4px;
            line-height: 1.15;
        }
        .lp-brand-text .inst {
            color: rgba(255,255,255,.50);
            font-size: 12px;
            font-weight: 400;
            margin-top: 2px;
            letter-spacing: .1px;
        }

        /* ── Headline block ── */
        .lp-hero {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 48px 0 36px;
            max-width: 620px;
        }
        .lp-hero h1 {
            margin: 0 0 20px;
            color: #ffffff;
            font-size: clamp(38px, 4vw, 60px);
            font-weight: 900;
            letter-spacing: -2px;
            line-height: 1.08;
        }
        .lp-hero h1 .hi {
            background: linear-gradient(110deg, #7dd3fc 0%, #67e8f9 50%, #a5b4fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .lp-hero p {
            margin: 0 0 44px;
            color: rgba(255,255,255,.55);
            font-size: 16px;
            line-height: 1.80;
            max-width: 500px;
        }

        /* ── Feature cards ── */
        .lp-features {
            display: flex;
            gap: 14px;
        }
        .lp-feat {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 18px 16px;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.10);
            border-radius: 16px;
            backdrop-filter: blur(12px);
            transition: background .2s, border-color .2s, transform .2s;
        }
        .lp-feat:hover {
            background: rgba(255,255,255,.10);
            border-color: rgba(255,255,255,.18);
            transform: translateY(-2px);
        }
        .lp-feat-icon {
            width: 40px;
            height: 40px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .fi-blue   { background: rgba(59,130,246,.22); }
        .fi-cyan   { background: rgba(14,165,233,.22); }
        .fi-purple { background: rgba(139,92,246,.22); }
        .lp-feat-title {
            color: rgba(255,255,255,.90);
            font-size: 13px;
            font-weight: 600;
            line-height: 1.4;
        }
        .lp-feat-desc {
            color: rgba(255,255,255,.45);
            font-size: 11.5px;
            line-height: 1.5;
        }

        /* ── Left footer ── */
        .lp-left-footer {
            color: rgba(255,255,255,.28);
            font-size: 12px;
        }

        /* ═══════════════════════════════════════════════════
           RIGHT PANEL  (30%)
        ═══════════════════════════════════════════════════ */
        .lp-right {
            flex: 0 0 30%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            background: #ffffff;
            position: relative;
            overflow-y: auto;
        }

        /* top accent stripe */
        .lp-right::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #0ea5e9, #8b5cf6);
        }

        .lp-form-wrap {
            width: 100%;
            max-width: 340px;
        }

        /* ── Form header ── */
        .lp-form-head { margin-bottom: 32px; }

        .lp-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 13px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1d4ed8;
            font-size: 11.5px;
            font-weight: 600;
            border-radius: 100px;
            margin-bottom: 20px;
            letter-spacing: .2px;
        }
        .lp-badge svg { flex-shrink: 0; }

        .lp-form-head h2 {
            margin: 0 0 10px;
            color: #0f172a;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -.6px;
            line-height: 1.2;
        }
        .lp-form-head p {
            margin: 0;
            color: #64748b;
            font-size: 13.5px;
            line-height: 1.65;
        }

        /* ── Fields ── */
        .lp-field { margin-bottom: 18px; }
        .lp-label {
            display: block;
            margin-bottom: 7px;
            color: #334155;
            font-size: 13px;
            font-weight: 600;
        }
        .lp-input-wrap { position: relative; }
        .lp-input-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
            display: flex;
        }
        .lp-input {
            width: 100%;
            height: 46px;
            padding: 0 44px;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 11px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: #0f172a;
            outline: none;
            transition: border-color .18s, box-shadow .18s, background .18s;
        }
        .lp-input::placeholder { color: #b4bfcc; }
        .lp-input:hover  { border-color: #cbd5e1; background: #fff; }
        .lp-input:focus  {
            border-color: #3b82f6;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(59,130,246,.13);
        }
        .lp-input.error  { border-color: #f87171; }
        .lp-input.error:focus { box-shadow: 0 0 0 3px rgba(248,113,113,.12); }

        /* show/hide password toggle */
        .lp-pw-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            color: #94a3b8;
            display: flex;
            transition: color .15s;
        }
        .lp-pw-btn:hover { color: #3b82f6; }

        /* error text */
        .lp-err {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 5px;
            color: #ef4444;
            font-size: 11.5px;
            font-weight: 500;
        }

        /* ── Options row ── */
        .lp-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 26px;
        }
        .lp-remember {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            user-select: none;
        }
        .lp-remember input[type=checkbox] {
            width: 16px;
            height: 16px;
            accent-color: #3b82f6;
            cursor: pointer;
            border-radius: 4px;
        }
        .lp-remember span {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
        }
        .lp-forgot {
            font-size: 13px;
            color: #3b82f6;
            font-weight: 600;
            text-decoration: none;
            transition: color .15s;
        }
        .lp-forgot:hover { color: #1d4ed8; text-decoration: underline; }

        /* ── Submit button ── */
        .lp-btn {
            width: 100%;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            border: none;
            border-radius: 11px;
            cursor: pointer;
            font-size: 14.5px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            color: #fff;
            background: linear-gradient(130deg, #2563eb 0%, #1d4ed8 50%, #4f46e5 100%);
            box-shadow: 0 4px 18px rgba(37,99,235,.35);
            transition: transform .15s, box-shadow .15s, filter .15s;
            letter-spacing: -.1px;
        }
        .lp-btn:hover {
            filter: brightness(1.08);
            box-shadow: 0 6px 24px rgba(37,99,235,.45);
            transform: translateY(-1px);
        }
        .lp-btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 10px rgba(37,99,235,.30);
        }
        .lp-btn:disabled {
            opacity: .75;
            cursor: not-allowed;
            transform: none;
        }

        /* ── Divider ── */
        .lp-divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 24px 0 0;
            color: #cbd5e1;
            font-size: 11.5px;
        }
        .lp-divider::before, .lp-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #f1f5f9;
        }

        /* ── Success alert ── */
        .lp-alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 11px;
            color: #15803d;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 22px;
        }

        /* ── Right footer ── */
        .lp-form-footer {
            margin-top: 28px;
            text-align: center;
            color: #94a3b8;
            font-size: 11.5px;
            line-height: 1.65;
        }

        /* ═══════════════════════════════════════════════════
           RESPONSIVE
        ═══════════════════════════════════════════════════ */
        @media (max-width: 1100px) {
            .lp-left  { flex: 0 0 60%; padding: 40px 44px; }
            .lp-right { flex: 0 0 40%; padding: 40px 32px; }
            .lp-features { flex-direction: column; gap: 10px; }
        }

        @media (max-width: 768px) {
            .lp-wrap  { flex-direction: column; }
            .lp-left  { flex: none; padding: 40px 28px 48px; min-height: auto; }
            .lp-right { flex: none; min-height: 100vh; padding: 40px 24px; }
            .lp-hero h1 { font-size: 36px; }
            .lp-features { flex-direction: row; }
        }

        @media (max-width: 500px) {
            .lp-features { flex-direction: column; }
            .lp-left { padding: 32px 20px 40px; }
        }

        /* Spinner animation */
        @keyframes spin { to { transform: rotate(360deg); } }
        .spin { animation: spin .65s linear infinite; }
    </style>
</head>
<body>
<div class="lp-wrap">

    {{-- ═════════════════════════════════════════
         LEFT PANEL — Branding
    ═════════════════════════════════════════ --}}
    <div class="lp-left">
        <div class="lp-left-inner">

            {{-- Logo / Brand --}}
            <div class="lp-brand">
                <div class="lp-brand-logo-wrap">
                    <img src="{{ asset('images/logo-dishub.png') }}"
                         alt="Logo Dinas Perhubungan"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    {{-- Fallback icon --}}
                    <div style="display:none; align-items:center; justify-content:center; width:100%; height:100%;">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                        </svg>
                    </div>
                </div>
                <div class="lp-brand-text">
                    <div class="name">SIPAS</div>
                    <div class="inst">Dinas Perhubungan Provinsi Riau</div>
                </div>
            </div>

            {{-- Hero Headline --}}
            <div class="lp-hero">
                <h1>
                    Kelola Surat<br>
                    <span class="hi">Lebih Cerdas</span>
                </h1>
                <p>
                    Sistem Informasi Persuratan Dinas Perhubungan. Buat, kelola, dan arsipkan surat dengan mudah dan efisien dalam satu platform terpadu.
                </p>

                {{-- Feature Cards --}}
                <div class="lp-features">
                    {{-- Card 1 --}}
                    <div class="lp-feat">
                        <div class="lp-feat-icon fi-blue">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                            </svg>
                        </div>
                        <div class="lp-feat-title">Pembuatan Surat Otomatis</div>
                        <div class="lp-feat-desc">Dari template yang sudah tersedia</div>
                    </div>
                    {{-- Card 2 --}}
                    <div class="lp-feat">
                        <div class="lp-feat-icon fi-cyan">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#22d3ee" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 8v13H3V8"/><path d="M1 3h22v5H1z"/>
                                <path d="M10 12h4"/>
                            </svg>
                        </div>
                        <div class="lp-feat-title">Pengarsipan Terorganisir</div>
                        <div class="lp-feat-desc">Mudah dicari kapan saja</div>
                    </div>
                    {{-- Card 3 --}}
                    <div class="lp-feat">
                        <div class="lp-feat-icon fi-purple">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                        </div>
                        <div class="lp-feat-title">Alur Finalisasi Digital</div>
                        <div class="lp-feat-desc">Persetujuan surat lebih cepat</div>
                    </div>
                </div>
            </div>

            {{-- Left Footer --}}
            <div class="lp-left-footer">
                © {{ date('Y') }} Dinas Perhubungan. Semua hak dilindungi.
            </div>

        </div>
    </div>

    {{-- ═════════════════════════════════════════
         RIGHT PANEL — Form
    ═════════════════════════════════════════ --}}
    <div class="lp-right">
        <div class="lp-form-wrap">
            {{ $slot }}
        </div>
    </div>

</div>
</body>
</html>
