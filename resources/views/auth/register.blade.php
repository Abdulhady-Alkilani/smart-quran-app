<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-[#C9A84C]" style="font-family: 'Amiri', serif;">{{ __('messages.auth.register_title') }}</h2>
        <p class="text-[#f8fafc]/50 text-sm mt-2">{{ __('messages.auth.register_desc') }}</p>
    </div>

    @if($errors->any())
    <div class="mb-4 bg-red-500/10 border border-red-500/30 rounded-xl px-4 py-3">
        <div class="flex items-center gap-2 mb-2">
            <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <span class="text-red-400 text-sm font-semibold">{{ __('messages.auth.register_title') }}</span>
        </div>
        <ul class="space-y-1">
            @foreach($errors->all() as $error)
            <li class="text-red-300 text-sm flex items-start gap-2">
                <svg class="w-3 h-3 text-red-400 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                {{ $error }}
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name" :value="__('messages.auth.full_name')" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="{{ __('messages.auth.name_placeholder') }}" class="block w-full rounded-lg {{ $errors->has('name') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}" />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('messages.auth.email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="email@example.com" class="block w-full rounded-lg {{ $errors->has('email') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('messages.auth.password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" placeholder="{{ __('messages.auth.password_min') }}" class="block w-full rounded-lg {{ $errors->has('password') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('messages.auth.confirm_password')" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('messages.auth.confirm_password_placeholder') }}" class="block w-full rounded-lg {{ $errors->has('password_confirmation') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : '' }}" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                {{ __('messages.auth.create_account') }}
            </x-primary-button>
        </div>

        <div class="mt-6 text-center">
            <span class="text-[#f8fafc]/40 text-sm">{{ __('messages.auth.have_account') }}</span>
            <a href="{{ route('login') }}" class="text-sm text-[#C9A84C] hover:text-[#FFD700] transition {{ app()->getLocale() === 'ar' ? 'mr-1' : 'ml-1' }}">{{ __('messages.auth.login_title') }}</a>
        </div>
    </form>
</x-guest-layout>
