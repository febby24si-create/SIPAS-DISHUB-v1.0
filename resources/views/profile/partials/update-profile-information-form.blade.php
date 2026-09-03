<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Perbarui nama, email, dan foto profil akun Anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- Foto Profil --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">Foto Profil</label>
            <div class="flex items-center gap-5">
                {{-- Preview Avatar --}}
                <div id="avatar-preview-wrap"
                     style="width:80px; height:80px; border-radius:50%; overflow:hidden; background:linear-gradient(135deg,#6366f1,#8b5cf6); display:flex; align-items:center; justify-content:center; font-size:28px; font-weight:700; color:white; flex-shrink:0; border:3px solid #e5e7eb;">
                    @if($user->avatar && file_exists(public_path('avatars/' . $user->avatar)))
                        <img id="avatar-preview" src="{{ asset('avatars/' . $user->avatar) }}"
                             style="width:100%; height:100%; object-fit:cover;">
                    @else
                        <span id="avatar-initials">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        <img id="avatar-preview" src="" style="display:none; width:100%; height:100%; object-fit:cover;">
                    @endif
                </div>
                {{-- Tombol Upload --}}
                <div>
                    <label for="avatar"
                           style="display:inline-flex; align-items:center; gap:8px; padding:8px 16px; background:white; border:1.5px solid #d1d5db; border-radius:10px; cursor:pointer; font-size:13px; font-weight:500; color:#374151; transition:all 0.2s;"
                           onmouseover="this.style.borderColor='#6366f1'; this.style.color='#6366f1';"
                           onmouseout="this.style.borderColor='#d1d5db'; this.style.color='#374151';">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                        Pilih Foto
                    </label>
                    <input id="avatar" name="avatar" type="file" accept="image/*" class="sr-only"
                           onchange="previewAvatar(this)">
                    <p class="mt-2 text-xs text-gray-500">JPG, PNG, atau GIF. Maks 2MB.</p>
                    @error('avatar')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <x-input-label for="name" :value="__('Nama')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Simpan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >✅ Profil berhasil disimpan.</p>
            @endif
        </div>
    </form>
</section>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatar-preview');
            const initials = document.getElementById('avatar-initials');
            preview.src = e.target.result;
            preview.style.display = 'block';
            if (initials) initials.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

