<x-app-layout>
    <x-slot name="header">Tambah Pengguna Baru</x-slot>

    <div style="max-width:640px;">
        <form action="{{ route('pengguna.store') }}" method="POST"
              style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:28px; display:flex; flex-direction:column; gap:18px;">
            @csrf

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Nama Lengkap <span style="color:#dc2626;">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       style="width:100%; padding:10px 14px; border:1px solid {{ $errors->has('name') ? '#fca5a5' : '#e2e8f0' }}; border-radius:10px; font-size:14px; box-sizing:border-box;" placeholder="Nama pengguna">
                @error('name') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
            </div>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Email <span style="color:#dc2626;">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       style="width:100%; padding:10px 14px; border:1px solid {{ $errors->has('email') ? '#fca5a5' : '#e2e8f0' }}; border-radius:10px; font-size:14px; box-sizing:border-box;" placeholder="Alamat email aktif">
                @error('email') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
            </div>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Role / Hak Akses <span style="color:#dc2626;">*</span></label>
                <select name="role_id" required style="width:100%; padding:10px 14px; border:1px solid {{ $errors->has('role_id') ? '#fca5a5' : '#e2e8f0' }}; border-radius:10px; font-size:14px; color:#334155;">
                    <option value="">-- Pilih Role --</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
                @error('role_id') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
            </div>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Link ke Pegawai (Opsional)</label>
                <select name="pegawai_id" style="width:100%; padding:10px 14px; border:1px solid {{ $errors->has('pegawai_id') ? '#fca5a5' : '#e2e8f0' }}; border-radius:10px; font-size:14px; color:#334155;">
                    <option value="">-- Tanpa Relasi Pegawai --</option>
                    @foreach ($pegawais as $pegawai)
                        <option value="{{ $pegawai->id }}" @selected(old('pegawai_id') == $pegawai->id)>
                            {{ $pegawai->nama }} {{ $pegawai->nip ? ' (NIP: '.$pegawai->nip.')' : '' }}
                        </option>
                    @endforeach
                </select>
                <p style="font-size:11px; color:#64748b; margin:4px 0 0;">Hanya menampilkan pegawai aktif yang belum memiliki akun.</p>
                @error('pegawai_id') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
            </div>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Password Sementara <span style="color:#dc2626;">*</span></label>
                <input type="password" name="password" required minlength="8"
                       style="width:100%; padding:10px 14px; border:1px solid {{ $errors->has('password') ? '#fca5a5' : '#e2e8f0' }}; border-radius:10px; font-size:14px; box-sizing:border-box;" placeholder="Minimal 8 karakter">
                @error('password') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
            </div>

            <div style="display:flex; gap:10px; margin-top:8px;">
                <button type="submit" style="padding:10px 20px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; border:none; border-radius:12px; font-size:13px; font-weight:600; cursor:pointer;">
                    Simpan Pengguna
                </button>
                <a href="{{ route('pengguna.index') }}" style="padding:10px 20px; color:#64748b; text-decoration:none; font-size:13px; font-weight:600;">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
