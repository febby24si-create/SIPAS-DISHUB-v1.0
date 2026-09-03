<x-app-layout>
    <x-slot name="header">{{ $klasifikasi->exists ? 'Edit Klasifikasi Surat' : 'Tambah Klasifikasi Surat' }}</x-slot>

    <div style="max-width:560px;">
        <form action="{{ $klasifikasi->exists ? route('klasifikasi-surat.update', $klasifikasi) : route('klasifikasi-surat.store') }}"
              method="POST"
              style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:28px; display:flex; flex-direction:column; gap:18px;">
            @csrf
            @if ($klasifikasi->exists) @method('PUT') @endif

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Kode</label>
                <input type="text" name="kode" value="{{ old('kode', $klasifikasi->kode) }}"
                       style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:14px;">
                @error('kode') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
            </div>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Nama Klasifikasi</label>
                <input type="text" name="nama" value="{{ old('nama', $klasifikasi->nama) }}"
                       style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:14px;">
                @error('nama') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
            </div>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Status</label>
                <select name="status" style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:14px;">
                    <option value="aktif" @selected(old('status', $klasifikasi->status ?? 'aktif') === 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected(old('status', $klasifikasi->status ?? 'aktif') === 'nonaktif')>Nonaktif</option>
                </select>
                @error('status') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
            </div>

            <div style="display:flex; gap:10px; margin-top:8px;">
                <button type="submit" class="btn-primary" style="padding:10px 20px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; border:none; border-radius:12px; font-size:13px; font-weight:600; cursor:pointer;">
                    Simpan
                </button>
                <a href="{{ route('klasifikasi-surat.index') }}" style="padding:10px 20px; color:#64748b; text-decoration:none; font-size:13px; font-weight:600;">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
