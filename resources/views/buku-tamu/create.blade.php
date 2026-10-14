<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Buku Tamu &ndash; {{ $sysInstansiNama }}</title>
    <link rel="icon" type="image/png" href="{{ $sysLogoUrl }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --navy-900: #0f172a;
            --navy-800: #1e293b;
            --blue-600: #2563eb;
            --blue-700: #1d4ed8;
            --blue-800: #1e40af;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(145deg, #f1f5f9 0%, #e2e8f0 100%);
            margin: 0;
            padding: 24px 16px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1e293b;
        }

        .portal-wrapper {
            width: 100%;
            max-width: 680px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.12), 0 0 0 1px rgba(15, 23, 42, 0.05);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            margin: auto;
        }

        /* ── Header Dinas & Portal ── */
        .portal-header {
            background: linear-gradient(135deg, #0a192f 0%, #0f2b48 50%, #1e3a8a 100%);
            color: #ffffff;
            padding: 36px 32px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .portal-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at center, rgba(59, 130, 246, 0.15) 0%, transparent 60%);
            pointer-events: none;
        }

        .header-content {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .logo-wrap {
            width: 64px;
            height: 64px;
            background: #ffffff;
            border-radius: 16px;
            padding: 8px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .header-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            padding: 4px 14px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: #93c5fd;
            margin-bottom: 12px;
        }

        .header-title {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin: 0 0 6px;
            line-height: 1.25;
        }

        .header-instansi {
            font-size: 14px;
            color: #cbd5e1;
            font-weight: 500;
            margin: 0;
            line-height: 1.5;
            max-width: 440px;
        }

        /* ── Body & Form ── */
        .portal-body {
            padding: 32px 32px 28px;
            background: #ffffff;
        }

        .form-section-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 20px 22px;
            margin-bottom: 24px;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        .section-icon-badge {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: #eff6ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            flex-shrink: 0;
            border: 1px solid #bfdbfe;
        }

        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            letter-spacing: -0.2px;
        }

        .section-desc {
            font-size: 12px;
            color: #64748b;
            margin-left: auto;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        @media (max-width: 600px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .portal-body {
                padding: 22px 18px;
            }
            .portal-header {
                padding: 28px 18px 24px;
            }
            .header-title {
                font-size: 20px;
            }
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 16px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .form-label .req {
            color: #ef4444;
            font-weight: 700;
        }

        .form-label .opt {
            font-size: 11.5px;
            font-weight: 400;
            color: #94a3b8;
            margin-left: 2px;
        }

        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            width: 17px;
            height: 17px;
            color: #94a3b8;
            pointer-events: none;
            transition: color 0.15s ease;
        }

        .form-control {
            width: 100%;
            padding: 11px 14px 11px 40px;
            font-size: 13.5px;
            color: #0f172a;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            outline: none;
            transition: all 0.15s ease;
            box-sizing: border-box;
            font-family: inherit;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .input-wrap:focus-within .input-icon {
            color: #2563eb;
        }

        .form-control.is-invalid {
            border-color: #ef4444;
            background-color: #fffafb;
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15);
        }

        textarea.form-control {
            padding: 12px 14px 12px 40px;
            resize: vertical;
            min-height: 85px;
            line-height: 1.5;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 15px;
            padding-right: 38px;
            cursor: pointer;
        }

        .field-error {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            color: #dc2626;
            font-weight: 500;
            margin-top: 2px;
        }

        /* ── Dropzone SPT ── */
        .dropzone-container {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            background: #ffffff;
            padding: 20px 16px;
            text-align: center;
            transition: all 0.2s ease;
            cursor: pointer;
            position: relative;
        }

        .dropzone-container:hover,
        .dropzone-container.dragover {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .dropzone-inner {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .dropzone-icon-box {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #f1f5f9;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .dropzone-container:hover .dropzone-icon-box {
            background: #dbeafe;
            transform: scale(1.05);
        }

        .dropzone-prompt {
            font-size: 13.5px;
            font-weight: 600;
            color: #1e293b;
        }

        .dropzone-prompt span {
            color: #2563eb;
            text-decoration: underline;
        }

        .dropzone-hint {
            font-size: 11.5px;
            color: #64748b;
        }

        .hidden-file-input {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        /* File preview card */
        .file-preview-card {
            display: none;
            align-items: center;
            justify-content: space-between;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            padding: 10px 14px;
            margin-top: 10px;
            animation: fadeIn 0.2s ease-in-out;
        }

        .file-info {
            display: flex;
            align-items: center;
            gap: 10px;
            overflow: hidden;
        }

        .file-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #dcfce7;
            color: #16a34a;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .file-meta {
            display: flex;
            flex-direction: column;
            overflow: hidden;
            text-align: left;
        }

        .file-name {
            font-size: 13px;
            font-weight: 600;
            color: #166534;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 320px;
        }

        .file-size {
            font-size: 11px;
            color: #4ade80;
            font-weight: 500;
        }

        .btn-remove-file {
            background: transparent;
            border: none;
            color: #991b1b;
            cursor: pointer;
            padding: 4px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.15s;
        }

        .btn-remove-file:hover {
            background: #fee2e2;
        }

        /* ── Submit Button & Loading ── */
        .submit-btn {
            width: 100%;
            padding: 14px 20px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
            transition: all 0.2s ease;
        }

        .submit-btn:hover:not(:disabled) {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            box-shadow: 0 6px 20px rgba(29, 78, 216, 0.45);
            transform: translateY(-1px);
        }

        .submit-btn:active:not(:disabled) {
            transform: translateY(0);
        }

        .submit-btn:disabled {
            opacity: 0.75;
            cursor: not-allowed;
            filter: grayscale(20%);
        }

        .spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #ffffff;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Footer ── */
        .portal-footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 18px 24px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            line-height: 1.6;
        }

        .portal-footer strong {
            color: #334155;
        }
    </style>
</head>
<body>

    <div class="portal-wrapper">

        <!-- ── Header Portal ── -->
        <header class="portal-header">
            <div class="header-content">
                <div class="logo-wrap">
                    <img src="{{ $sysLogoUrl }}" alt="Logo Dinas" class="logo-img">
                </div>
                <div class="header-badge">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    Portal Kunjungan Resmi
                </div>
                <h1 class="header-title">Buku Tamu Digital</h1>
                <p class="header-instansi">{{ $sysInstansiNama }} &ndash; {{ $sysAppName }}</p>
            </div>
        </header>

        <!-- ── Body / Formulir ── -->
        <main class="portal-body">
            <form id="bukuTamuForm" method="POST" action="{{ route('buku-tamu.store') }}" enctype="multipart/form-data" novalidate>
                @csrf

                <!-- ── Section 1: Identitas Pengunjung ── -->
                <div class="form-section-card">
                    <div class="section-header">
                        <div class="section-icon-badge">1</div>
                        <h2 class="section-title">Identitas Pengunjung</h2>
                        <span class="section-desc">Data diri tamu</span>
                    </div>

                    <div class="form-grid">
                        <!-- Nama Lengkap -->
                        <div class="form-group">
                            <label for="nama" class="form-label">
                                Nama Lengkap <span class="req">*</span>
                            </label>
                            <div class="input-wrap">
                                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                <input type="text"
                                       id="nama"
                                       name="nama"
                                       value="{{ old('nama') }}"
                                       class="form-control @error('nama') is-invalid @enderror"
                                       placeholder="Nama lengkap sesuai KTP/ID"
                                       required
                                       autofocus>
                            </div>
                            @error('nama')
                                <div class="field-error">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- No. HP / Kontak -->
                        <div class="form-group">
                            <label for="no_hp" class="form-label">
                                No. HP / WhatsApp <span class="req">*</span>
                            </label>
                            <div class="input-wrap">
                                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.18 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.61A16 16 0 0 0 16 16.61l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 23.92 18z"/></svg>
                                <input type="tel"
                                       id="no_hp"
                                       name="no_hp"
                                       value="{{ old('no_hp') }}"
                                       class="form-control @error('no_hp') is-invalid @enderror"
                                       placeholder="Contoh: 081234567890"
                                       required>
                            </div>
                            @error('no_hp')
                                <div class="field-error">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Instansi Asal -->
                    <div class="form-group" style="margin-top: 14px;">
                        <label for="instansi" class="form-label">
                            Instansi / Lembaga / Perusahaan <span class="opt">(opsional)</span>
                        </label>
                        <div class="input-wrap">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/><path d="M9 9h1"/><path d="M9 13h1"/><path d="M9 17h1"/><path d="M14 9h1"/><path d="M14 13h1"/><path d="M14 17h1"/></svg>
                            <input type="text"
                                   id="instansi"
                                   name="instansi"
                                   value="{{ old('instansi') }}"
                                   class="form-control @error('instansi') is-invalid @enderror"
                                   placeholder="Asal dinas, kementerian, perusahaan, atau masyarakat umum">
                        </div>
                        @error('instansi')
                            <div class="field-error">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- ── Section 2: Informasi Kunjungan ── -->
                <div class="form-section-card">
                    <div class="section-header">
                        <div class="section-icon-badge">2</div>
                        <h2 class="section-title">Informasi Kunjungan</h2>
                        <span class="section-desc">Tujuan kedatangan</span>
                    </div>

                    <!-- Keperluan Kunjungan -->
                    <div class="form-group">
                        <label for="keperluan" class="form-label">
                            Keperluan Kunjungan <span class="req">*</span>
                        </label>
                        <div class="input-wrap">
                            <svg class="input-icon" style="top: 14px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                            <textarea id="keperluan"
                                      name="keperluan"
                                      class="form-control @error('keperluan') is-invalid @enderror"
                                      placeholder="Uraikan secara ringkas agenda, keperluan surat, atau maksud kunjungan..."
                                      rows="3"
                                      required>{{ old('keperluan') }}</textarea>
                        </div>
                        @error('keperluan')
                            <div class="field-error">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-grid" style="margin-top: 14px;">
                        <!-- Pegawai Dituju -->
                        <div class="form-group">
                            <label for="pegawai_id" class="form-label">
                                Bertemu Dengan <span class="opt">(opsional)</span>
                            </label>
                            <div class="input-wrap">
                                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M6 21v-2a6 6 0 0 1 12 0v2"/></svg>
                                <select id="pegawai_id"
                                        name="pegawai_id"
                                        class="form-control @error('pegawai_id') is-invalid @enderror">
                                    <option value="">&ndash; Pilih Pegawai Dituju &ndash;</option>
                                    @foreach($pegawais as $pegawai)
                                        <option value="{{ $pegawai->id }}" {{ old('pegawai_id') == $pegawai->id ? 'selected' : '' }}>
                                            {{ $pegawai->nama }} {{ $pegawai->jabatan ? '('.$pegawai->jabatan.')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('pegawai_id')
                                <div class="field-error">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Unit / Seksi Tujuan -->
                        <div class="form-group">
                            <label for="unit_kerja_id" class="form-label">
                                Unit / Bidang / Seksi <span class="opt">(opsional)</span>
                            </label>
                            <div class="input-wrap">
                                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                                <select id="unit_kerja_id"
                                        name="unit_kerja_id"
                                        class="form-control @error('unit_kerja_id') is-invalid @enderror">
                                    <option value="">&ndash; Pilih Unit / Bidang &ndash;</option>
                                    @foreach($unitKerjas as $unit)
                                        <option value="{{ $unit->id }}" {{ old('unit_kerja_id') == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('unit_kerja_id')
                                <div class="field-error">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- ── Section 3: Dokumen Pendukung ── -->
                <div class="form-section-card">
                    <div class="section-header">
                        <div class="section-icon-badge">3</div>
                        <h2 class="section-title">Dokumen Pendukung</h2>
                        <span class="section-desc">SPT / Surat Tugas</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Unggah Berkas SPT <span class="opt">(opsional &ndash; PDF, JPG, PNG, maks. 10 MB)</span>
                        </label>

                        <!-- Dropzone area -->
                        <div class="dropzone-container" id="dropzoneContainer">
                            <input type="file"
                                   id="spt_file"
                                   name="spt_file"
                                   class="hidden-file-input"
                                   accept=".pdf,.jpg,.jpeg,.png">
                            <div class="dropzone-inner">
                                <div class="dropzone-icon-box">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                </div>
                                <div class="dropzone-prompt">
                                    <span>Pilih file SPT</span> atau tarik ke area ini
                                </div>
                                <div class="dropzone-hint">
                                    Format didukung: PDF, JPG, JPEG, PNG. Ukuran maksimal 10 MB
                                </div>
                            </div>
                        </div>

                        <!-- Selected File Preview -->
                        <div class="file-preview-card" id="filePreviewCard">
                            <div class="file-info">
                                <div class="file-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                                </div>
                                <div class="file-meta">
                                    <span class="file-name" id="fileNameDisplay">file.pdf</span>
                                    <span class="file-size" id="fileSizeDisplay">1.2 MB</span>
                                </div>
                            </div>
                            <button type="button" class="btn-remove-file" id="btnRemoveFile" title="Hapus file terpilih">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                        </div>

                        @error('spt_file')
                            <div class="field-error">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- ── Tombol Kirim ── -->
                <button type="submit" id="submitBtn" class="submit-btn">
                    <span class="spinner" id="btnSpinner"></span>
                    <svg id="btnIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    <span id="btnText">Kirim Data Kunjungan</span>
                </button>
            </form>
        </main>

        <!-- ── Footer ── -->
        <footer class="portal-footer">
            <div>&copy; {{ date('Y') }} <strong>{{ $sysInstansiNama }}</strong>. Hak cipta dilindungi undang-undang.</div>
            <div style="font-size:11.5px; color:#94a3b8; margin-top:2px;">Sistem Informasi Pengelolaan Arsip & Persuratan ({{ $sysAppName }})</div>
        </footer>

    </div>

    <!-- ── Script Interaktivitas Form ── -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('bukuTamuForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnSpinner = document.getElementById('btnSpinner');
            const btnIcon = document.getElementById('btnIcon');
            const btnText = document.getElementById('btnText');

            const fileInput = document.getElementById('spt_file');
            const dropzone = document.getElementById('dropzoneContainer');
            const filePreview = document.getElementById('filePreviewCard');
            const fileNameDisplay = document.getElementById('fileNameDisplay');
            const fileSizeDisplay = document.getElementById('fileSizeDisplay');
            const btnRemoveFile = document.getElementById('btnRemoveFile');

            // Format bytes helper
            function formatBytes(bytes, decimals = 2) {
                if (!+bytes) return '0 Bytes';
                const k = 1024;
                const dm = decimals < 0 ? 0 : decimals;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`;
            }

            // Update preview file
            function updateFilePreview() {
                if (fileInput.files && fileInput.files[0]) {
                    const file = fileInput.files[0];
                    fileNameDisplay.textContent = file.name;
                    fileSizeDisplay.textContent = formatBytes(file.size);
                    filePreview.style.display = 'flex';
                    dropzone.style.display = 'none';
                } else {
                    filePreview.style.display = 'none';
                    dropzone.style.display = 'block';
                }
            }

            fileInput.addEventListener('change', updateFilePreview);

            // Drag and drop events
            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.add('dragover');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.remove('dragover');
                }, false);
            });

            dropzone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                if (dt.files && dt.files.length) {
                    fileInput.files = dt.files;
                    updateFilePreview();
                }
            });

            // Remove file button
            btnRemoveFile.addEventListener('click', function () {
                fileInput.value = '';
                updateFilePreview();
            });

            // Form submit loading state to prevent double submit
            form.addEventListener('submit', function (e) {
                // Check HTML5 validity
                if (!form.checkValidity()) {
                    return;
                }

                submitBtn.disabled = true;
                btnSpinner.style.display = 'inline-block';
                btnIcon.style.display = 'none';
                btnText.textContent = 'Mengirim data kunjungan...';
            });
        });
    </script>

</body>
</html>
