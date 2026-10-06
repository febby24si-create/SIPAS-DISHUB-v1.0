<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; gap:12px;">
            <div style="width:40px; height:40px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#1d4ed8; flex-shrink:0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="9" x2="20" y2="9"/><line x1="4" y1="15" x2="20" y2="15"/><line x1="10" y1="3" x2="8" y2="21"/><line x1="16" y1="3" x2="14" y2="21"/></svg>
            </div>
            <div>
                <div style="font-size:17px; font-weight:600; color:#1e293b; line-height:1.2;">Nomor Surat</div>
                <div style="margin-top:2px; font-size:12.5px; color:#64748b;">Kelola format dan urutan nomor surat</div>
            </div>
        </div>
    </x-slot>

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
