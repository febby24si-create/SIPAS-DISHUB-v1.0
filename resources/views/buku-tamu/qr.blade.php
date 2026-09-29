<x-app-layout>
    <x-slot name="header">QR Buku Tamu</x-slot>

    <style>
        @media print {
            .sidebar, aside, .topbar, header,
            .no-print { display: none !important; }
            .main-content { margin: 0 !important; padding: 0 !important; width: 100% !important; }
            .page-content { padding: 0 !important; }
            .print-area {
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                justify-content: center !important;
                min-height: 100vh !important;
                padding: 20px !important;
            }
        }
    </style>

    <div style="display:flex; flex-direction:column; gap:20px; align-items:center;">

        {{-- Tombol navigasi (tidak tampil saat cetak) --}}
        <div class="no-print" style="width:100%; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
            <a href="{{ route('buku-tamu.index') }}"
               style="display:inline-flex; align-items:center; gap:7px; padding:9px 18px; background:white; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; font-weight:600; color:#374151; text-decoration:none;"
               onmouseover="this.style.borderColor='#1d4ed8';this.style.color='#1d4ed8'" onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#374151'">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali ke Buku Tamu
            </a>

            <div style="display:flex; gap:10px;">
                <button onclick="window.print()"
                        style="display:inline-flex; align-items:center; gap:7px; padding:9px 18px; background:#1d4ed8; color:white; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;"
                        onmouseover="this.style.background='#1e40af'" onmouseout="this.style.background='#1d4ed8'">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                    Cetak QR
                </button>

                <button id="btn-download" onclick="downloadQr()"
                        style="display:inline-flex; align-items:center; gap:7px; padding:9px 18px; background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;"
                        onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fdf4'">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Download PNG
                </button>
            </div>
        </div>

        {{-- Kartu QR (tampil saat cetak juga) --}}
        <div class="print-area" style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:40px 48px; text-align:center; max-width:480px; width:100%; box-shadow:0 4px 24px rgba(0,0,0,0.06);">

            {{-- Identitas --}}
            <div style="margin-bottom:28px;">
                <div style="display:flex; align-items:center; justify-content:center; gap:8px; margin-bottom:10px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                    <span style="font-size:13px; font-weight:700; color:#1d4ed8; text-transform:uppercase; letter-spacing:1px;">Buku Tamu</span>
                </div>
                <h2 style="font-size:22px; font-weight:800; color:#1e293b; margin:0 0 6px;">Dinas Perhubungan<br>Provinsi Riau</h2>
                <p style="font-size:13px; color:#64748b; margin:0;">Scan QR Code berikut untuk membuka<br>formulir Buku Tamu.</p>
            </div>

            {{-- QR Code (SVG) --}}
            <div id="qr-container" style="display:inline-block; padding:16px; background:white; border-radius:12px; border:2px solid #e2e8f0; margin-bottom:24px;">
                {!! $qrSvg !!}
            </div>

            {{-- Instruksi --}}
            <div style="display:flex; align-items:center; justify-content:center; gap:8px; margin-bottom:16px; background:#f8fafc; border-radius:10px; padding:12px 16px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.18 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.61A16 16 0 0 0 16 16.61l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 23.92 18z"/></svg>
                <p style="font-size:13px; font-weight:600; color:#374151; margin:0;">Scan untuk mengisi Buku Tamu</p>
            </div>

            {{-- URL tujuan --}}
            <p style="font-size:11px; color:#94a3b8; word-break:break-all; margin:0; font-family:monospace; background:#f1f5f9; padding:8px 12px; border-radius:6px;">
                {{ $url }}
            </p>
        </div>

    </div>

    @push('scripts')
    <script>
    function downloadQr() {
        // Ambil SVG dari container
        var svgEl = document.querySelector('#qr-container svg');
        if (!svgEl) return;

        var svgData = new XMLSerializer().serializeToString(svgEl);
        var svgBlob = new Blob([svgData], { type: 'image/svg+xml;charset=utf-8' });

        // Convert SVG → Canvas → PNG
        var url = URL.createObjectURL(svgBlob);
        var img = new Image();
        img.onload = function () {
            var canvas = document.createElement('canvas');
            var scale = 3; // 3x untuk kualitas tinggi
            canvas.width  = img.width  * scale;
            canvas.height = img.height * scale;
            var ctx = canvas.getContext('2d');
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.scale(scale, scale);
            ctx.drawImage(img, 0, 0);
            URL.revokeObjectURL(url);

            var a = document.createElement('a');
            a.download = 'qr-buku-tamu.png';
            a.href = canvas.toDataURL('image/png');
            a.click();
        };
        img.src = url;
    }
    </script>
    @endpush

</x-app-layout>
