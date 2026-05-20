<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-[#C9A84C]" style="font-family: 'Amiri', serif;">{{ __('messages.auth.login_title') }}</h2>
        <p class="text-[#f8fafc]/50 text-sm mt-2">{{ __('messages.auth.login_desc') }}</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('messages.auth.email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="email@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('messages.auth.password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded bg-white/5 border-white/10 text-[#1B5E20] focus:ring-[#1B5E20] focus:ring-offset-[#0F172A]" name="remember">
                <span class="ms-2 text-sm text-[#f8fafc]/60">{{ __('messages.auth.remember_me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-[#C9A84C] hover:text-[#FFD700] transition" href="{{ route('password.request') }}">
                    {{ __('messages.auth.forgot_password') }}
                </a>
            @endif
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                {{ __('messages.auth.login_title') }}
            </x-primary-button>
        </div>

        <div class="mt-6 text-center">
            <span class="text-[#f8fafc]/40 text-sm">{{ __('messages.auth.no_account') }}</span>
            <a href="{{ route('register') }}" class="text-sm text-[#C9A84C] hover:text-[#FFD700] transition {{ app()->getLocale() === 'ar' ? 'mr-1' : 'ml-1' }}">{{ __('messages.auth.register_title') }}</a>
        </div>
    </form>
</x-guest-layout>
