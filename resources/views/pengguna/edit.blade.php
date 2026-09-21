<x-app-layout>
    <x-slot name="header">Edit Pengguna: {{ $pengguna->name }}</x-slot>

    <div style="max-width:640px; display:flex; flex-direction:column; gap:24px;">
        
        @if (session('status'))
            <div style="display:flex; align-items:center; gap:10px; padding:14px 18px; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:14px; color:#065f46; font-size:14px; font-weight:500;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div style="display:flex; align-items:center; gap:10px; padding:14px 18px; background:#fef2f2; border:1px solid #fecaca; border-radius:14px; color:#991b1b; font-size:14px; font-weight:500;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- FORM EDIT DATA --}}
        <form action="{{ route('pengguna.update', $pengguna) }}" method="POST"
              style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:28px; display:flex; flex-direction:column; gap:18px;">
            @csrf
            @method('PUT')

            <h2 style="font-size:16px; font-weight:700; color:#1e293b; margin:0 0 8px;">Informasi Dasar</h2>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Nama Lengkap <span style="color:#dc2626;">*</span></label>
                <input type="text" name="name" value="{{ old('name', $pengguna->name) }}" required
                       style="width:100%; padding:10px 14px; border:1px solid {{ $errors->has('name') ? '#fca5a5' : '#e2e8f0' }}; border-radius:10px; font-size:14px; box-sizing:border-box;">
                @error('name') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
            </div>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Email <span style="color:#dc2626;">*</span></label>
                <input type="email" name="email" value="{{ old('email', $pengguna->email) }}" required
                       style="width:100%; padding:10px 14px; border:1px solid {{ $errors->has('email') ? '#fca5a5' : '#e2e8f0' }}; border-radius:10px; font-size:14px; box-sizing:border-box;">
                @error('email') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
            </div>

            {{-- Role tidak dapat diubah dari UI --}}

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Link ke Pegawai (Opsional)</label>
                <select name="pegawai_id" style="width:100%; padding:10px 14px; border:1px solid {{ $errors->has('pegawai_id') ? '#fca5a5' : '#e2e8f0' }}; border-radius:10px; font-size:14px; color:#334155;">
                    <option value="">-- Tanpa Relasi Pegawai --</option>
                    @foreach ($pegawais as $pegawai)
                        <option value="{{ $pegawai->id }}" @selected(old('pegawai_id', $pengguna->pegawai_id) == $pegawai->id)>
                            {{ $pegawai->nama }} {{ $pegawai->nip ? ' (NIP: '.$pegawai->nip.')' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('pegawai_id') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
            </div>

            <div style="display:flex; gap:10px; margin-top:8px;">
                <button type="submit" style="padding:10px 20px; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:white; border:none; border-radius:12px; font-size:13px; font-weight:600; cursor:pointer;">
                    Simpan Perubahan
                </button>
                <a href="{{ route('pengguna.index') }}" style="padding:10px 20px; color:#64748b; text-decoration:none; font-size:13px; font-weight:600;">Batal</a>
            </div>
        </form>

        {{-- FORM RESET PASSWORD --}}
        <form action="{{ route('pengguna.reset-password', $pengguna) }}" method="POST"
              style="background:white; border-radius:20px; border:1px solid rgba(0,0,0,0.06); padding:28px; display:flex; flex-direction:column; gap:18px;">
            @csrf
            @method('PATCH')

            <h2 style="font-size:16px; font-weight:700; color:#1e293b; margin:0 0 8px;">Reset Password</h2>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Password Baru</label>
                <input type="password" name="password" required minlength="8"
                       style="width:100%; padding:10px 14px; border:1px solid {{ $errors->has('password') ? '#fca5a5' : '#e2e8f0' }}; border-radius:10px; font-size:14px; box-sizing:border-box;" placeholder="Minimal 8 karakter">
                @error('password') <p style="color:#be123c; font-size:12px; margin:4px 0 0;">{{ $message }}</p> @enderror
            </div>

            <div>
                <label style="display:block; font-size:13px; font-weight:600; color:#334155; margin-bottom:6px;">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" required minlength="8"
                       style="width:100%; padding:10px 14px; border:1px solid #e2e8f0; border-radius:10px; font-size:14px; box-sizing:border-box;" placeholder="Ulangi password baru">
            </div>

            <div style="margin-top:8px;">
                <button type="submit" onclick="return confirm('Reset password untuk akun ini?')"
                        style="padding:10px 20px; background:white; color:#0f172a; border:1px solid #cbd5e1; border-radius:12px; font-size:13px; font-weight:600; cursor:pointer;">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
