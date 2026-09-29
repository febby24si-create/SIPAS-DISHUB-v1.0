<x-app-layout>
    <x-slot name="header">Detail Kunjungan Tamu</x-slot>

    <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- Tombol Kembali --}}
        <div>
            <a href="{{ route('buku-tamu.index') }}"
               style="display:inline-flex; align-items:center; gap:7px; padding:9px 18px; background:white; border:1px solid #e2e8f0; border-radius:8px; font-size:13px; font-weight:600; color:#374151; text-decoration:none;"
               onmouseover="this.style.borderColor='#1d4ed8';this.style.color='#1d4ed8'" onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#374151'">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali ke Daftar
            </a>
        </div>

        {{-- Kartu Detail --}}
        <div style="background:white; border-radius:14px; border:1px solid rgba(0,0,0,0.06); overflow:hidden;">

            {{-- Header kartu --}}
            <div style="padding:18px 24px; border-bottom:1px solid rgba(0,0,0,0.05); display:flex; align-items:center; gap:10px;">
                <div style="width:32px; height:32px; background:#eff6ff; border-radius:9px; display:flex; align-items:center; justify-content:center;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <span style="font-size:14px; font-weight:700; color:#1e293b;">Informasi Tamu</span>
            </div>

            {{-- Grid Detail --}}
            <div style="padding:24px; display:grid; grid-template-columns:1fr 1fr; gap:0;">

                {{-- Nama --}}
                <div style="padding:14px 20px; border-bottom:1px solid #f1f5f9;">
                    <p style="font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; margin:0 0 5px;">Nama Lengkap</p>
                    <p style="font-size:15px; font-weight:600; color:#1e293b; margin:0;">{{ $bukuTamu->nama }}</p>
                </div>

                {{-- No HP --}}
                <div style="padding:14px 20px; border-bottom:1px solid #f1f5f9; border-left:1px solid #f1f5f9;">
                    <p style="font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; margin:0 0 5px;">No. HP / Kontak</p>
                    <p style="font-size:15px; font-weight:600; color:#1e293b; font-family:monospace; margin:0;">{{ $bukuTamu->no_hp }}</p>
                </div>

                {{-- Instansi --}}
                <div style="padding:14px 20px; border-bottom:1px solid #f1f5f9;">
                    <p style="font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; margin:0 0 5px;">Instansi Asal</p>
                    <p style="font-size:14px; color:{{ $bukuTamu->instansi ? '#1e293b' : '#94a3b8' }}; margin:0; font-weight:{{ $bukuTamu->instansi ? '500' : '400' }}; font-style:{{ $bukuTamu->instansi ? 'normal' : 'italic' }};">
                        {{ $bukuTamu->instansi ?? 'Tidak diisi' }}
                    </p>
                </div>

                {{-- Tanggal & Jam --}}
                <div style="padding:14px 20px; border-bottom:1px solid #f1f5f9; border-left:1px solid #f1f5f9;">
                    <p style="font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; margin:0 0 5px;">Waktu Kunjungan</p>
                    <p style="font-size:14px; font-weight:600; color:#1e293b; margin:0;">
                        {{ \Carbon\Carbon::parse($bukuTamu->tanggal)->translatedFormat('d F Y') }}
                    </p>
                    <p style="font-size:13px; color:#64748b; margin:2px 0 0; font-family:monospace;">
                        {{ substr($bukuTamu->jam, 0, 5) }} WIB
                    </p>
                </div>

                {{-- Bertemu Dengan --}}
                <div style="padding:14px 20px; border-bottom:1px solid #f1f5f9;">
                    <p style="font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; margin:0 0 5px;">Bertemu Dengan</p>
                    <p style="font-size:14px; color:{{ $bukuTamu->pegawai ? '#1e293b' : '#94a3b8' }}; margin:0; font-weight:{{ $bukuTamu->pegawai ? '500' : '400' }}; font-style:{{ $bukuTamu->pegawai ? 'normal' : 'italic' }};">
                        {{ $bukuTamu->pegawai?->nama ?? 'Tidak dipilih' }}
                    </p>
                </div>

                {{-- Unit Kerja Tujuan --}}
                <div style="padding:14px 20px; border-bottom:1px solid #f1f5f9; border-left:1px solid #f1f5f9;">
                    <p style="font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; margin:0 0 5px;">Unit/Seksi Tujuan</p>
                    <p style="font-size:14px; color:{{ $bukuTamu->unitKerja ? '#1e293b' : '#94a3b8' }}; margin:0; font-weight:{{ $bukuTamu->unitKerja ? '500' : '400' }}; font-style:{{ $bukuTamu->unitKerja ? 'normal' : 'italic' }};">
                        {{ $bukuTamu->unitKerja?->nama ?? 'Tidak dipilih' }}
                    </p>
                </div>

                {{-- Keperluan (full-width) --}}
                <div style="padding:14px 20px; grid-column:1 / -1;">
                    <p style="font-size:11px; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.5px; margin:0 0 8px;">Keperluan Kunjungan</p>
                    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:14px 16px;">
                        <p style="font-size:14px; color:#1e293b; margin:0; line-height:1.7;">{{ $bukuTamu->keperluan }}</p>
                    </div>
                </div>

            </div>
        </div>

    </div>
</x-app-layout>
