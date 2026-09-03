<x-guest-layout>

    {{-- ── Session Status ─────────────────────────────────── --}}
    @if (session('status'))
        <div class="lp-alert" role="alert">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            {{ session('status') }}
        </div>
    @endif

    {{-- ── Form Header ─────────────────────────────────────── --}}
    <div class="lp-form-head">

        {{-- Verified badge --}}
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

    {{-- ── Login Form ───────────────────────────────────────── --}}
    <form method="POST" action="{{ route('login') }}" id="lp-form" novalidate>
        @csrf

        {{-- Email --}}
        <div class="lp-field">
            <label class="lp-label" for="email">Alamat Email</label>
            <div class="lp-input-wrap">
                <span class="lp-input-icon" aria-hidden="true">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                </span>
                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="lp-input {{ $errors->has('email') ? 'error' : '' }}"
                       placeholder="nama@instansi.go.id"
                       required
                       autofocus
                       autocomplete="username"
                       aria-describedby="{{ $errors->has('email') ? 'email-error' : '' }}">
            </div>
            @error('email')
                <div class="lp-err" id="email-error" role="alert">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Password --}}
        <div class="lp-field">
            <label class="lp-label" for="password">Kata Sandi</label>
            <div class="lp-input-wrap">
                <span class="lp-input-icon" aria-hidden="true">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </span>
                <input id="password"
                       type="password"
                       name="password"
                       class="lp-input {{ $errors->has('password') ? 'error' : '' }}"
                       placeholder="••••••••"
                       required
                       autocomplete="current-password"
                       aria-describedby="{{ $errors->has('password') ? 'pw-error' : '' }}">

                {{-- Show / hide toggle --}}
                <button type="button"
                        class="lp-pw-btn"
                        id="pw-toggle"
                        onclick="lpTogglePw()"
                        aria-label="Tampilkan kata sandi">
                    {{-- Eye (visible) --}}
                    <svg id="lp-eye" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    {{-- Eye-off (hidden) --}}
                    <svg id="lp-eye-off" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94
                                 M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19
                                 m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                        <line x1="1" y1="1" x2="23" y2="23"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <div class="lp-err" id="pw-error" role="alert">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Remember + Forgot --}}
        <div class="lp-options">
            <label class="lp-remember">
                <input id="remember_me" type="checkbox" name="remember">
                <span>Ingat saya</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="lp-forgot">
                    Lupa sandi?
                </a>
            @endif
        </div>

        {{-- Submit --}}
        <button type="submit" class="lp-btn" id="lp-submit">
            {{-- Icon --}}
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                <polyline points="10 17 15 12 10 7"/>
                <line x1="15" y1="12" x2="3" y2="12"/>
            </svg>
            Masuk ke SIPAS
        </button>

    </form>

    {{-- ── Right footer ────────────────────────────────────── --}}
    <div class="lp-divider">© {{ date('Y') }} Dinas Perhubungan</div>
    <div class="lp-form-footer">
        Hak cipta dilindungi undang-undang.
    </div>

</x-guest-layout>

{{-- ── Scripts ───────────────────────────────────────────── --}}
<script>
    /** Toggle show / hide password */
    function lpTogglePw() {
        const inp    = document.getElementById('password');
        const eyeOn  = document.getElementById('lp-eye');
        const eyeOff = document.getElementById('lp-eye-off');
        const toggle = document.getElementById('pw-toggle');
        const show   = inp.type === 'password';

        inp.type             = show ? 'text' : 'password';
        eyeOn.style.display  = show ? 'none'  : '';
        eyeOff.style.display = show ? ''      : 'none';
        toggle.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
    }

    /** Loading state on submit */
    document.getElementById('lp-form').addEventListener('submit', function () {
        const btn = document.getElementById('lp-submit');
        btn.disabled = true;
        btn.innerHTML = `
            <svg class="spin" width="17" height="17" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12a9 9 0 1 1-6.22-8.56"/>
            </svg>
            Memproses…
        `;
    });
</script>
