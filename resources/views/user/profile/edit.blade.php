<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold gradient-text" style="font-family: 'Amiri', serif;">{{ __('messages.profile.title') }}</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="glass-card p-8 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#1B5E20] via-[#C9A84C] to-[#1B5E20]"></div>
            <h3 class="text-xl font-bold text-[#C9A84C] mb-6">{{ __('messages.profile.edit_info') }}</h3>

            <form method="POST" action="{{ route('user.profile.update') }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label class="block text-[#C9A84C] mb-2 text-sm font-medium">{{ __('messages.profile.name') }}</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-[#f8fafc] focus:outline-none focus:border-[#1B5E20] focus:ring-1 focus:ring-[#1B5E20] transition">
                        @error('name')<span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-[#C9A84C] mb-2 text-sm font-medium">{{ __('messages.profile.email') }}</label>
                        <input type="email" value="{{ $user->email }}" disabled class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-[#f8fafc]/40 cursor-not-allowed">
                        <p class="text-[#f8fafc]/30 text-xs mt-1">{{ __('messages.profile.email_disabled') }}</p>
                    </div>

                    <div>
                        <label class="block text-[#C9A84C] mb-2 text-sm font-medium">{{ __('messages.profile.phone') }}</label>
                        <input type="text" name="phone" value="{{ old('phone', $profile->phone ?? '') }}" placeholder="+966xxxxxxxxx" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-[#f8fafc] focus:outline-none focus:border-[#1B5E20] focus:ring-1 focus:ring-[#1B5E20] transition">
                    </div>

                    <div>
                        <label class="block text-[#C9A84C] mb-2 text-sm font-medium">{{ __('messages.profile.country') }}</label>
                        <input type="text" name="country" value="{{ old('country', $profile->country ?? '') }}" placeholder="{{ app()->getLocale() === 'ar' ? 'مثل: السعودية' : 'e.g. Saudi Arabia' }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-[#f8fafc] focus:outline-none focus:border-[#1B5E20] focus:ring-1 focus:ring-[#1B5E20] transition">
                    </div>

                    <div>
                        <label class="block text-[#C9A84C] mb-2 text-sm font-medium">{{ __('messages.profile.bio') }}</label>
                        <textarea name="bio" rows="3" placeholder="{{ __('messages.profile.bio_placeholder') }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-[#f8fafc] focus:outline-none focus:border-[#1B5E20] focus:ring-1 focus:ring-[#1B5E20] transition">{{ old('bio', $profile->bio ?? '') }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="flex items-center gap-2 bg-gradient-to-r from-[#1B5E20] to-[#2E7D32] hover:from-[#2E7D32] hover:to-[#388E3C] text-white px-8 py-3 rounded-xl transition-all duration-300 transform hover:scale-[1.02] shadow-lg shadow-[#1B5E20]/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                            {{ __('messages.profile.save_changes') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="glass-card p-8 mt-6 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#C9A84C] to-[#FFD700]"></div>
            <h3 class="flex items-center gap-2 text-xl font-bold text-[#C9A84C] mb-6">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                {{ __('messages.profile.memorization_stats') }}
            </h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white/5 rounded-xl p-5 text-center border border-white/5 hover:border-green-500/20 transition">
                    <div class="text-3xl font-bold text-green-400 mb-1">{{ $user->memorizationProgress()->where('status', 'memorized')->count() }}</div>
                    <div class="flex items-center justify-center gap-1 text-[#f8fafc]/50 text-sm">
                        <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('messages.profile.memorized') }}
                    </div>
                </div>
                <div class="bg-white/5 rounded-xl p-5 text-center border border-white/5 hover:border-yellow-500/20 transition">
                    <div class="text-3xl font-bold text-yellow-400 mb-1">{{ $user->memorizationProgress()->where('status', 'learning')->count() }}</div>
                    <div class="flex items-center justify-center gap-1 text-[#f8fafc]/50 text-sm">
                        <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        {{ __('messages.profile.learning') }}
                    </div>
                </div>
                <div class="bg-white/5 rounded-xl p-5 text-center border border-white/5 hover:border-blue-500/20 transition">
                    <div class="text-3xl font-bold text-blue-400 mb-1">{{ $user->recitationAttempts()->count() }}</div>
                    <div class="flex items-center justify-center gap-1 text-[#f8fafc]/50 text-sm">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                        {{ __('messages.profile.recitation_attempts') }}
                    </div>
                </div>
                <div class="bg-white/5 rounded-xl p-5 text-center border border-white/5 hover:border-[#C9A84C]/20 transition">
                    @php
                        $total = $user->recitationAttempts()->count();
                        $passed = $user->recitationAttempts()->where('is_passed', true)->count();
                        $rate = $total > 0 ? round(($passed / $total) * 100) : 0;
                    @endphp
                    <div class="text-3xl font-bold text-[#C9A84C] mb-1">{{ $rate }}%</div>
                    <div class="flex items-center justify-center gap-1 text-[#f8fafc]/50 text-sm">
                        <svg class="w-4 h-4 text-[#C9A84C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        {{ __('messages.profile.success_rate') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
