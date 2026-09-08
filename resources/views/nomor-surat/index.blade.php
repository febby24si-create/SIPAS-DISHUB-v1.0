<x-app-layout>
    <x-slot name="header">Nomor Surat</x-slot>

    <div style="max-width:640px; display:flex; flex-direction:column; gap:20px;">

        <div style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:24px;">
            <p style="font-size:13px; font-weight:600; color:#334155; margin:0 0 8px 0;">Format Penomoran Saat Ini</p>
            <p
                style="font-family:monospace; font-size:15px; font-weight:700; color:#1d4ed8; background:#eff6ff; display:inline-block; padding:6px 12px; border-radius:8px; margin:0 0 16px 0;">
                {{ $format }}
            </p>

            <p style="font-size:13px; font-weight:600; color:#334155; margin:0 0 8px 0;">Contoh Hasil</p>
            <p
                style="font-family:monospace; font-size:15px; font-weight:700; color:#059669; background:#ecfdf5; display:inline-block; padding:6px 12px; border-radius:8px; margin:0;">
                {{ $contoh }}
            </p>
        </div>

        <div
            style="display:flex; align-items:flex-start; gap:12px; padding:16px 20px; background:#fffbeb; border:1px solid #fde68a; border-radius:14px; color:#92400e; font-size:13px; line-height:1.6;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:2px;">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="8" x2="12" y2="12" />
                <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>
            <span>Format nomor surat dihasilkan otomatis berdasarkan konfigurasi sistem
                (<code>config/persuratan.php</code>) dan penomoran urut per klasifikasi/tahun. Pengaturan format melalui
                halaman ini (tanpa mengubah kode) belum tersedia pada tahap ini dan masih perlu dikonfirmasi terhadap
                format nomor surat resmi yang berlaku di Dishub.</span>
        </div>
    </div>
</x-app-layout>
