<x-guest-layout>

    @php
        // Menentukan form mana yang aktif berdasarkan error atau old input
        $activeForm = 'login';
        if (old('_form_type') === 'forgot' || session('status')) {
            $activeForm = 'forgot';
        }
    @endphp

    {{-- ── Session Status (Global) ─────────────────────────────────── --}}
    @if (session('status'))
        <div class="lp-alert" role="alert">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            {{ session('status') }}
        </div>
    @endif

    {{-- ── FORM LOGIN ───────────────────────────────────────── --}}
    <div id="form-login" style="display: {{ $activeForm === 'login' ? 'block' : 'none' }};">
        <div class="lp-form-head">
            <div class="lp-badge">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                Sistem Terverifikasi
            </div>
            <h2>Selamat Datang</h2>
            <p>Masuk ke akun SIPAS Anda untuk mengelola persuratan Dinas Perhubungan.</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="lp-form-instance" novalidate>
            @csrf
            <input type="hidden" name="_form_type" value="login">

            <div class="lp-field">
                <label class="lp-label" for="login_email">Alamat Email</label>
                <div class="lp-input-wrap">
                    <span class="lp-input-icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </span>
                    <input id="login_email" type="email" name="email" value="{{ old('_form_type') === 'login' ? old('email') : '' }}" class="lp-input {{ $errors->has('email') && old('_form_type') !== 'register' && old('_form_type') !== 'forgot' ? 'error' : '' }}" placeholder="nama@instansi.go.id" required autofocus autocomplete="username">
                </div>
                @if($errors->has('email') && old('_form_type') !== 'register' && old('_form_type') !== 'forgot')
                    <div class="lp-err" role="alert">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        {{ $errors->first('email') }}
                    </div>
                @endif
            </div>

            <div class="lp-field">
                <label class="lp-label" for="login_password">Kata Sandi</label>
                <div class="lp-input-wrap">
                    <span class="lp-input-icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </span>
                    <input id="login_password" type="password" name="password" class="lp-input {{ $errors->has('password') && old('_form_type') !== 'register' ? 'error' : '' }}" placeholder="••••••••" required autocomplete="current-password">
                    <button type="button" class="lp-pw-btn" onclick="lpTogglePw('login_password', this)">
                        <svg class="eye-on" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="eye-off" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>
                @if($errors->has('password') && old('_form_type') !== 'register')
                    <div class="lp-err" role="alert">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        {{ $errors->first('password') }}
                    </div>
                @endif
            </div>

            <div class="lp-options">
                <label class="lp-remember">
                    <input id="remember_me" type="checkbox" name="remember">
                    <span>Ingat saya</span>
                </label>
                <a href="javascript:void(0)" onclick="switchForm('forgot')" class="lp-forgot">
                    Lupa sandi?
                </a>
            </div>

            <button type="submit" class="lp-btn submit-btn">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                Masuk ke SIPAS
            </button>
        </form>


    </div>





    {{-- ── FORM FORGOT PASSWORD ───────────────────────────────────────── --}}
    <div id="form-forgot" style="display: {{ $activeForm === 'forgot' ? 'block' : 'none' }};">
        <div class="lp-form-head">
            <h2>Lupa Kata Sandi?</h2>
            <p>Masukkan alamat email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi.</p>
        </div>

        <form method="POST" action="{{ route('password.email') }}" class="lp-form-instance" novalidate>
            @csrf
            <input type="hidden" name="_form_type" value="forgot">

            {{-- Email --}}
            <div class="lp-field">
                <label class="lp-label" for="forgot_email">Alamat Email</label>
                <div class="lp-input-wrap">
                    <span class="lp-input-icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </span>
                    <input id="forgot_email" type="email" name="email" value="{{ old('_form_type') === 'forgot' ? old('email') : '' }}" class="lp-input {{ $errors->has('email') && old('_form_type') === 'forgot' ? 'error' : '' }}" placeholder="nama@instansi.go.id" required>
                </div>
                @if($errors->has('email') && old('_form_type') === 'forgot')
                    <div class="lp-err" role="alert">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        {{ $errors->first('email') }}
                    </div>
                @endif
            </div>

            <button type="submit" class="lp-btn submit-btn" style="margin-top: 20px;">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                Kirim Link Reset Password
            </button>
        </form>

        <div class="lp-divider" style="margin-top:20px; font-size:13px; color:#64748b; justify-content:center;">
            <a href="javascript:void(0)" onclick="switchForm('login')" class="lp-forgot">Kembali ke Login</a>
        </div>
    </div>

    {{-- ── Right footer ────────────────────────────────────── --}}
    <div class="lp-form-footer">
        Hak cipta dilindungi undang-undang.
    </div>

</x-guest-layout>

{{-- ── Scripts ───────────────────────────────────────────── --}}
<script>
    function switchForm(formName) {
        document.getElementById('form-login').style.display = formName === 'login' ? 'block' : 'none';
        document.getElementById('form-forgot').style.display = formName === 'forgot' ? 'block' : 'none';
        
        // Remove alert messages on switch to avoid confusion
        const alerts = document.querySelectorAll('.lp-alert');
        alerts.forEach(a => a.style.display = 'none');
    }

    /** Toggle show / hide password */
    function lpTogglePw(inputId, btn) {
        const inp = document.getElementById(inputId);
        const eyeOn = btn.querySelector('.eye-on');
        const eyeOff = btn.querySelector('.eye-off');
        const show = inp.type === 'password';

        inp.type = show ? 'text' : 'password';
        eyeOn.style.display = show ? 'none' : '';
        eyeOff.style.display = show ? '' : 'none';
    }

    /** Loading state on submit */
    document.querySelectorAll('.lp-form-instance').forEach(function(form) {
        form.addEventListener('submit', function () {
            const btn = form.querySelector('.submit-btn');
            btn.disabled = true;
            btn.innerHTML = `
                <svg class="spin" width="17" height="17" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12a9 9 0 1 1-6.22-8.56"/>
                </svg>
                Memproses…
            `;
        });
    });
</script>
