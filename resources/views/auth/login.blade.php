<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-[#C9A84C]" style="font-family: 'Amiri', serif;">تسجيل الدخول</h2>
        <p class="text-[#f8fafc]/50 text-sm mt-2">أدخل بياناتك للوصول إلى حسابك</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('البريد الإلكتروني')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="email@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('كلمة المرور')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded bg-white/5 border-white/10 text-[#1B5E20] focus:ring-[#1B5E20] focus:ring-offset-[#0F172A]" name="remember">
                <span class="ms-2 text-sm text-[#f8fafc]/60">تذكرني</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-[#C9A84C] hover:text-[#FFD700] transition" href="{{ route('password.request') }}">
                    نسيت كلمة المرور؟
                </a>
            @endif
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                تسجيل الدخول
            </x-primary-button>
        </div>

        <div class="mt-6 text-center">
            <span class="text-[#f8fafc]/40 text-sm">ليس لديك حساب؟</span>
            <a href="{{ route('register') }}" class="text-sm text-[#C9A84C] hover:text-[#FFD700] transition mr-1">إنشاء حساب جديد</a>
        </div>
    </form>
</x-guest-layout>
