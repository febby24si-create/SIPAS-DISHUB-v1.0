<x-app-layout>
    <x-slot name="header">Pengaturan Kepegawaian</x-slot>

    @if (session('status'))
        <div style="background:#ecfdf5; border:1px solid #a7f3d0; padding:12px 16px; border-radius:8px; color:#065f46; font-size:14px; margin-bottom:20px;">
            {{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <div style="background:#fef2f2; border:1px solid #fecaca; padding:12px 16px; border-radius:8px; color:#991b1b; font-size:14px; margin-bottom:20px;">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="background:#fef2f2; border:1px solid #fecaca; padding:12px 16px; border-radius:8px; color:#991b1b; font-size:14px; margin-bottom:20px;">
            <ul style="margin:0; padding-left:20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 24px;">

        {{-- A. Pengaturan Early Warning --}}
        <div style="background:white; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,0.1); overflow:hidden;">
            <div style="padding:16px 20px; border-bottom:1px solid #f1f5f9; background:#f8fafc;">
                <h2 style="font-size:15px; font-weight:600; color:#1e293b; margin:0;">Pengaturan Peringatan Dini (Early Warning)</h2>
            </div>
            <div style="padding:20px;">
                <form action="{{ route('pengaturan.kepegawaian.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:13px; font-weight:500; color:#475569; margin-bottom:6px;">Interval Kenaikan Gaji Berkala (Bulan)</label>
                        <input type="number" name="kgb_interval_months" value="{{ old('kgb_interval_months', $settings['kgb_interval_months'] ?? '') }}" placeholder="Contoh: 24" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px; font-size:14px;" min="1">
                        <small style="color:#64748b; font-size:12px;">Interval KGB default adalah 24 bulan.</small>
                    </div>

                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:13px; font-weight:500; color:#475569; margin-bottom:6px;">Interval Kenaikan Pangkat (Tahun)</label>
                        <input type="number" name="kp_interval_years" value="{{ old('kp_interval_years', $settings['kp_interval_years'] ?? '') }}" placeholder="Contoh: 4" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px; font-size:14px;" min="1">
                    </div>

                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:13px; font-weight:500; color:#475569; margin-bottom:6px;">Pengingat KGB (H- Hari)</label>
                        <input type="number" name="reminder_kgb_days" value="{{ old('reminder_kgb_days', $settings['reminder_kgb_days'] ?? '') }}" placeholder="Contoh: 60" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px; font-size:14px;" min="1">
                    </div>

                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:13px; font-weight:500; color:#475569; margin-bottom:6px;">Pengingat Kenaikan Pangkat (H- Hari)</label>
                        <input type="number" name="reminder_kp_days" value="{{ old('reminder_kp_days', $settings['reminder_kp_days'] ?? '') }}" placeholder="Contoh: 90" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px; font-size:14px;" min="1">
                    </div>

                    <div style="margin-bottom:20px;">
                        <label style="display:block; font-size:13px; font-weight:500; color:#475569; margin-bottom:6px;">Pengingat Batas Usia Pensiun / BUP (H- Hari)</label>
                        <input type="number" name="reminder_bup_days" value="{{ old('reminder_bup_days', $settings['reminder_bup_days'] ?? '') }}" placeholder="Contoh: 365" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px; font-size:14px;" min="1">
                    </div>

                    <button type="submit" style="background:#2563eb; color:white; padding:8px 16px; border:none; border-radius:6px; font-size:14px; font-weight:500; cursor:pointer;">
                        Simpan Pengaturan
                    </button>
                </form>
            </div>
        </div>

        {{-- B. Master Kategori BUP --}}
        <div>
            <div style="background:white; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,0.1); overflow:hidden; margin-bottom:24px;">
                <div style="padding:16px 20px; border-bottom:1px solid #f1f5f9; background:#f8fafc;">
                    <h2 style="font-size:15px; font-weight:600; color:#1e293b; margin:0;">Tambah Kategori BUP</h2>
                </div>
                <div style="padding:20px;">
                    <form action="{{ route('pengaturan.kategori-bup.store') }}" method="POST">
                        @csrf
                        <div style="margin-bottom:16px;">
                            <label style="display:block; font-size:13px; font-weight:500; color:#475569; margin-bottom:6px;">Nama Kategori</label>
                            <input type="text" name="nama_kategori" required placeholder="Misal: Struktural, Fungsional Ahli Utama" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px; font-size:14px;">
                        </div>
                        <div style="margin-bottom:16px;">
                            <label style="display:block; font-size:13px; font-weight:500; color:#475569; margin-bottom:6px;">Usia Pensiun</label>
                            <input type="number" name="usia_pensiun" required placeholder="Misal: 58" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px; font-size:14px;" min="1">
                        </div>
                        <div style="margin-bottom:20px; display:flex; align-items:center; gap:8px;">
                            <input type="checkbox" name="status" value="1" id="status" checked>
                            <label for="status" style="font-size:14px; color:#334155;">Aktif</label>
                        </div>
                        <button type="submit" style="background:#10b981; color:white; padding:8px 16px; border:none; border-radius:6px; font-size:14px; font-weight:500; cursor:pointer;">
                            Tambah Kategori
                        </button>
                    </form>
                </div>
            </div>

            <div style="background:white; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,0.1); overflow:hidden;">
                <div style="padding:16px 20px; border-bottom:1px solid #f1f5f9; background:#f8fafc;">
                    <h2 style="font-size:15px; font-weight:600; color:#1e293b; margin:0;">Daftar Kategori BUP</h2>
                </div>
                <div style="padding:0;">
                    <table style="width:100%; border-collapse:collapse; text-align:left; font-size:14px;">
                        <thead>
                            <tr style="background:#f8fafc; border-bottom:1px solid #e2e8f0; color:#475569;">
                                <th style="padding:12px 16px; font-weight:500;">Kategori</th>
                                <th style="padding:12px 16px; font-weight:500;">Usia BUP</th>
                                <th style="padding:12px 16px; font-weight:500;">Status</th>
                                <th style="padding:12px 16px; font-weight:500; width:120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategoriBup as $bup)
                            <tr style="border-bottom:1px solid #f1f5f9;">
                                <td style="padding:12px 16px;">{{ $bup->nama_kategori }}</td>
                                <td style="padding:12px 16px;">{{ $bup->usia_pensiun }} Thn</td>
                                <td style="padding:12px 16px;">
                                    @if($bup->status)
                                        <span style="background:#dcfce7; color:#166534; padding:2px 8px; border-radius:12px; font-size:12px;">Aktif</span>
                                    @else
                                        <span style="background:#f1f5f9; color:#475569; padding:2px 8px; border-radius:12px; font-size:12px;">Nonaktif</span>
                                    @endif
                                </td>
                                <td style="padding:12px 16px; display:flex; gap:8px;">
                                    <a href="{{ route('pengaturan.kategori-bup.edit', $bup) }}" style="color:#2563eb; text-decoration:none; font-size:13px;">Edit</a>
                                    
                                    <form action="{{ route('pengaturan.kategori-bup.destroy', $bup) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori BUP ini?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background:none; border:none; color:#dc2626; cursor:pointer; font-size:13px; padding:0;">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" style="padding:20px; text-align:center; color:#64748b; font-style:italic;">Belum ada kategori BUP</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
