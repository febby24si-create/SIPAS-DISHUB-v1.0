<x-app-layout>
    <x-slot name="header">Edit Kategori BUP</x-slot>
    <x-slot name="breadcrumb">Pengaturan / Edit Kategori BUP</x-slot>

    <div style="background:white; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,0.1); overflow:hidden; max-width:600px; margin:0 auto;">
        <div style="padding:16px 20px; border-bottom:1px solid #f1f5f9; background:#f8fafc; display:flex; justify-content:space-between; align-items:center;">
            <h2 style="font-size:15px; font-weight:600; color:#1e293b; margin:0;">Form Edit Kategori BUP</h2>
            <a href="{{ route('pengaturan.kepegawaian.index') }}" style="color:#64748b; text-decoration:none; font-size:13px;">Batal</a>
        </div>
        <div style="padding:20px;">
            <form action="{{ route('pengaturan.kategori-bup.update', $kategoriBup) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div style="margin-bottom:16px;">
                    <label style="display:block; font-size:13px; font-weight:500; color:#475569; margin-bottom:6px;">Nama Kategori</label>
                    <input type="text" name="nama_kategori" required value="{{ old('nama_kategori', $kategoriBup->nama_kategori) }}" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px; font-size:14px;">
                    @error('nama_kategori')<span style="color:#ef4444; font-size:12px;">{{ $message }}</span>@enderror
                </div>
                
                <div style="margin-bottom:16px;">
                    <label style="display:block; font-size:13px; font-weight:500; color:#475569; margin-bottom:6px;">Usia Pensiun</label>
                    <input type="number" name="usia_pensiun" required value="{{ old('usia_pensiun', $kategoriBup->usia_pensiun) }}" style="width:100%; padding:8px 12px; border:1px solid #cbd5e1; border-radius:6px; font-size:14px;" min="1">
                    @error('usia_pensiun')<span style="color:#ef4444; font-size:12px;">{{ $message }}</span>@enderror
                </div>
                
                <div style="margin-bottom:20px; display:flex; align-items:center; gap:8px;">
                    <input type="checkbox" name="status" value="1" id="status" {{ old('status', $kategoriBup->status) ? 'checked' : '' }}>
                    <label for="status" style="font-size:14px; color:#334155;">Aktif</label>
                </div>
                
                <button type="submit" style="background:#2563eb; color:white; padding:8px 16px; border:none; border-radius:6px; font-size:14px; font-weight:500; cursor:pointer;">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
