<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu - Dinas Perhubungan Provinsi Riau</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }
        .bt-container {
            width: 100%;
            max-width: 480px;
            background: #ffffff;
            min-height: 100vh;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            display: flex;
            flex-direction: column;
        }
        .bt-header {
            background-color: #1e293b;
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
            border-bottom: 4px solid #3b82f6;
        }
        .bt-header h1 {
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 5px 0;
            letter-spacing: 1px;
        }
        .bt-header p {
            font-size: 14px;
            margin: 0;
            color: #cbd5e1;
        }
        .bt-body {
            padding: 25px 20px;
            flex: 1;
        }
        .bt-form-group {
            margin-bottom: 20px;
        }
        .bt-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }
        .bt-label span.req {
            color: #ef4444;
        }
        .bt-input, .bt-select, .bt-textarea {
            width: 100%;
            padding: 12px 15px;
            font-size: 15px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background-color: #f8fafc;
            color: #0f172a;
            transition: all 0.2s;
            box-sizing: border-box;
        }
        .bt-input:focus, .bt-select:focus, .bt-textarea:focus {
            outline: none;
            border-color: #3b82f6;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .bt-textarea {
            resize: vertical;
            min-height: 80px;
        }
        /* SPT Upload Section */
        .bt-spt-section {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            overflow: hidden;
        }
        .bt-spt-header {
            background-color: #f1f5f9;
            padding: 10px 15px;
            border-bottom: 1px solid #cbd5e1;
        }
        .bt-spt-header span {
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .bt-spt-body {
            padding: 15px;
        }
        .bt-file-input-wrapper {
            position: relative;
        }
        .bt-file-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 12px 15px;
            font-size: 14px;
            font-weight: 600;
            color: #1d4ed8;
            border: 2px dashed #93c5fd;
            border-radius: 8px;
            background-color: #eff6ff;
            cursor: pointer;
            transition: all 0.2s;
            box-sizing: border-box;
            text-align: center;
        }
        .bt-file-label:hover {
            background-color: #dbeafe;
            border-color: #3b82f6;
        }
        .bt-file-input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            overflow: hidden;
            clip: rect(0,0,0,0);
        }
        .bt-file-hint {
            font-size: 12px;
            color: #64748b;
            margin-top: 8px;
            line-height: 1.5;
        }
        .bt-file-preview {
            display: none;
            margin-top: 10px;
            padding: 10px 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 13px;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 8px;
            word-break: break-all;
        }
        .bt-file-preview.hidden {
            display: none;
        }
        .bt-error-msg {
            color: #ef4444;
            font-size: 13px;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .bt-submit-btn {
            width: 100%;
            background-color: #2563eb;
            color: #ffffff;
            font-weight: 600;
            font-size: 16px;
            padding: 14px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
        }
        .bt-submit-btn:hover {
            background-color: #1d4ed8;
        }
        .bt-submit-btn:active {
            transform: scale(0.98);
        }
        .bt-footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #94a3b8;
            background: #f1f5f9;
            border-top: 1px solid #e2e8f0;
        }
        
        @media (min-width: 481px) {
            body {
                padding: 40px 20px;
            }
            .bt-container {
                min-height: auto;
                border-radius: 12px;
                overflow: hidden;
            }
        }
    </style>
</head>
<body>

    <div class="bt-container">
        <!-- Header -->
        <div class="bt-header">
            <h1>BUKU TAMU</h1>
            <p>Dinas Perhubungan Provinsi Riau</p>
        </div>

        <!-- Body / Form -->
        <div class="bt-body">
            <form method="POST" action="{{ route('buku-tamu.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Nama Lengkap -->
                <div class="bt-form-group">
                    <label for="nama" class="bt-label">Nama Lengkap <span class="req">*</span></label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" class="bt-input" placeholder="Masukkan nama lengkap Anda" required autofocus>
                    @error('nama')
                        <div class="bt-error-msg">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- No HP / Kontak -->
                <div class="bt-form-group">
                    <label for="no_hp" class="bt-label">No. HP / Kontak <span class="req">*</span></label>
                    <input type="tel" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" class="bt-input" placeholder="Contoh: 081234567890" required>
                    @error('no_hp')
                        <div class="bt-error-msg">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Instansi / Perusahaan -->
                <div class="bt-form-group">
                    <label for="instansi" class="bt-label">Instansi Asal</label>
                    <input type="text" id="instansi" name="instansi" value="{{ old('instansi') }}" class="bt-input" placeholder="Nama perusahaan/instansi (opsional)">
                    @error('instansi')
                        <div class="bt-error-msg">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Keperluan -->
                <div class="bt-form-group">
                    <label for="keperluan" class="bt-label">Keperluan <span class="req">*</span></label>
                    <textarea id="keperluan" name="keperluan" class="bt-textarea" placeholder="Jelaskan secara singkat keperluan kunjungan Anda" required>{{ old('keperluan') }}</textarea>
                    @error('keperluan')
                        <div class="bt-error-msg">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Bertemu Dengan -->
                <div class="bt-form-group">
                    <label for="pegawai_id" class="bt-label">Bertemu Dengan</label>
                    <select id="pegawai_id" name="pegawai_id" class="bt-select">
                        <option value="">-- Pilih Pegawai (Opsional) --</option>
                        @foreach($pegawais as $pegawai)
                            <option value="{{ $pegawai->id }}" {{ old('pegawai_id') == $pegawai->id ? 'selected' : '' }}>
                                {{ $pegawai->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('pegawai_id')
                        <div class="bt-error-msg">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Unit / Seksi Tujuan -->
                <div class="bt-form-group">
                    <label for="unit_kerja_id" class="bt-label">Unit/Seksi Tujuan</label>
                    <select id="unit_kerja_id" name="unit_kerja_id" class="bt-select">
                        <option value="">-- Pilih Unit/Seksi (Opsional) --</option>
                        @foreach($unitKerjas as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit_kerja_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('unit_kerja_id')
                        <div class="bt-error-msg">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- SPT (Surat Perintah Tugas) -->
                <div class="bt-form-group">
                    <label class="bt-label">SPT (Surat Perintah Tugas)</label>
                    <div class="bt-spt-section">
                        <div class="bt-spt-header">
                            <span>Unggah / Scan SPT</span>
                        </div>
                        <div class="bt-spt-body">
                            <div class="bt-file-input-wrapper">
                                <label for="spt_file" class="bt-file-label" id="spt-label">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                    Pilih / Scan SPT
                                </label>
                                <input
                                    type="file"
                                    id="spt_file"
                                    name="spt_file"
                                    class="bt-file-input"
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    onchange="handleSptFileChange(this)"
                                >
                            </div>
                            <div class="bt-file-hint">
                                Unggah file SPT atau scan menggunakan kamera. Format PDF, JPG, JPEG, PNG. Maksimal 10 MB.
                            </div>
                            <div class="bt-file-preview hidden" id="spt-preview">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                <span id="spt-filename"></span>
                            </div>
                            @error('spt_file')
                                <div class="bt-error-msg">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="bt-submit-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    Kirim Data Kunjungan
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="bt-footer">
            &copy; {{ date('Y') }} Sistem Informasi Persuratan<br>
            Dinas Perhubungan Provinsi Riau
        </div>
    </div>

<script>
    function handleSptFileChange(input) {
        var preview = document.getElementById('spt-preview');
        var filename = document.getElementById('spt-filename');
        var label = document.getElementById('spt-label');
        if (input.files && input.files[0]) {
            var file = input.files[0];
            preview.classList.remove('hidden');
            preview.style.display = 'flex';
            filename.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
            label.style.borderColor = '#16a34a';
            label.style.backgroundColor = '#f0fdf4';
            label.style.color = '#15803d';
        } else {
            preview.classList.add('hidden');
            preview.style.display = 'none';
            label.style.borderColor = '#93c5fd';
            label.style.backgroundColor = '#eff6ff';
            label.style.color = '#1d4ed8';
        }
    }
</script>
</body>
</html>
