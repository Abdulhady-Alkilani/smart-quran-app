<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-[#C9A84C]" style="font-family: 'Amiri', serif;">إنشاء حساب جديد</h2>
        <p class="text-[#f8fafc]/50 text-sm mt-2">انضم إلى منصة القرآن الذكي وابدأ رحلة الحفظ</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('الاسم الكامل')" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="أدخل اسمك الكامل" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('البريد الإلكتروني')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="email@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('كلمة المرور')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" placeholder="على الأقل 8 أحرف" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('تأكيد كلمة المرور')" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="أعد إدخال كلمة المرور" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                إنشاء الحساب
            </x-primary-button>
        </div>

        <div class="mt-6 text-center">
            <span class="text-[#f8fafc]/40 text-sm">لديك حساب بالفعل؟</span>
            <a href="{{ route('login') }}" class="text-sm text-[#C9A84C] hover:text-[#FFD700] transition mr-1">تسجيل الدخول</a>
        </div>
    </form>
</x-guest-layout>
