@php
    $locale = app()->getLocale();
    $isRtl = $locale === 'ar';
    $dir = $isRtl ? 'rtl' : 'ltr';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $locale) }}" dir="{{ $dir }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ __('messages.app_description') }}">
    <title>{{ __('messages.app_tagline') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Noto+Naskh+Arabic:wght@400;500;600;700&family=Tajawal:wght@300;400;500;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-[#0F172A] text-[#f8fafc]" style="font-family: {{ $isRtl ? "'Tajawal', sans-serif" : "'Inter', 'Tajawal', sans-serif" }};">
    <div class="min-h-screen islamic-pattern">
        <nav class="bg-[#0F172A]/80 backdrop-blur-xl border-b border-[#1B5E20]/20 fixed w-full z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <x-application-logo class="w-8 h-8" />
                        <span class="text-2xl font-bold gradient-text" style="font-family: 'Amiri', serif;">{{ __('messages.app_name') }}</span>
                    </a>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('locale.switch', $locale === 'ar' ? 'en' : 'ar') }}"
                           class="flex items-center gap-1.5 text-sm font-medium text-[#C9A84C] px-3 py-1.5 rounded-lg border border-[#C9A84C]/30 hover:bg-[#C9A84C]/10 transition-all duration-200">
                            {{ $locale === 'ar' ? 'English' : 'العربية' }}
                        </a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-1.5 text-[#f8fafc]/80 hover:text-[#C9A84C] transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/></svg>
                                {{ __('messages.nav.dashboard') }}
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-[#f8fafc]/80 hover:text-[#C9A84C] transition-colors duration-200">{{ __('messages.login') }}</a>
                            <a href="{{ route('register') }}" class="bg-gradient-to-r from-[#1B5E20] to-[#2E7D32] hover:from-[#2E7D32] hover:to-[#388E3C] text-white px-5 py-2 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg shadow-[#1B5E20]/30">{{ __('messages.create_account') }}</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <section class="pt-32 pb-20 px-4 relative overflow-hidden">
            <div class="absolute top-20 {{ $isRtl ? 'left-10' : 'left-10' }} w-72 h-72 bg-[#1B5E20]/10 rounded-full blur-3xl animate-float"></div>
            <div class="absolute bottom-10 {{ $isRtl ? 'right-10' : 'right-10' }} w-96 h-96 bg-[#C9A84C]/5 rounded-full blur-3xl animate-float stagger-2"></div>

            <div class="max-w-4xl mx-auto text-center relative z-10">
                <div class="mb-8 animate-fade-in-up">
                    <span class="text-3xl text-[#C9A84C]/60" style="font-family: 'Noto Naskh Arabic', serif;">{{ __('messages.bismillah') }}</span>
                </div>

                <h1 class="text-5xl md:text-7xl font-bold mb-6 animate-fade-in-up stagger-1 flex flex-col items-center gap-4 md:gap-6" style="font-family: 'Amiri', serif;">
                    <span class="gradient-text">{{ __('messages.welcome.hero_title_1') }}</span>
                    <span class="text-[#f8fafc]">{{ __('messages.welcome.hero_title_2') }}</span>
                </h1>

                <p class="text-xl text-[#f8fafc]/70 mb-10 max-w-2xl mx-auto leading-relaxed animate-fade-in-up stagger-2">
                    @php
                        $heroDesc = __('messages.welcome.hero_description');
                        $heroDesc = str_replace(':ai', '<span class="text-[#C9A84C] font-bold">' . __('messages.welcome.ai') . '</span>', $heroDesc);
                        $heroDesc = str_replace(':spaced', '<span class="text-[#C9A84C] font-bold">' . __('messages.welcome.spaced') . '</span>', $heroDesc);
                    @endphp
                    {!! $heroDesc !!}
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up stagger-3">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#1B5E20] to-[#2E7D32] text-white text-lg px-10 py-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-xl shadow-[#1B5E20]/30 animate-pulse-glow">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        {{ __('messages.welcome.start_journey') }}
                    </a>
                    <a href="#features" class="inline-block bg-white/5 backdrop-blur-md border border-white/10 text-[#f8fafc] text-lg px-10 py-4 rounded-xl transition-all duration-300 hover:bg-white/10 hover:border-[#C9A84C]/30">
                        {{ __('messages.welcome.learn_more') }}
                    </a>
                </div>
            </div>
        </section>

        <section class="py-12 px-4">
            <div class="max-w-4xl mx-auto">
                <div class="grid grid-cols-3 gap-4">
                    <div class="glass-card p-6 text-center">
                        <div class="text-4xl font-bold text-[#C9A84C] mb-1">114</div>
                        <div class="text-[#f8fafc]/50 text-sm">{{ __('messages.welcome.surahs') }}</div>
                    </div>
                    <div class="glass-card p-6 text-center">
                        <div class="text-4xl font-bold text-[#C9A84C] mb-1">6,236</div>
                        <div class="text-[#f8fafc]/50 text-sm">{{ __('messages.welcome.ayahs') }}</div>
                    </div>
                    <div class="glass-card p-6 text-center">
                        <div class="text-4xl font-bold text-[#C9A84C] mb-1">30</div>
                        <div class="text-[#f8fafc]/50 text-sm">{{ __('messages.welcome.juz') }}</div>
                    </div>
                </div>
            </div>
        </section>

        <section id="features" class="py-20 px-4">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-4xl font-bold text-center mb-4 gradient-text" style="font-family: 'Amiri', serif;">{{ __('messages.welcome.features_title') }}</h2>
                <p class="text-center text-[#f8fafc]/50 mb-12 max-w-xl mx-auto">{{ __('messages.welcome.features_subtitle') }}</p>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="glass-card p-8 group">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#1B5E20] to-[#2E7D32] flex items-center justify-center mb-6 shadow-lg shadow-[#1B5E20]/30 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-[#C9A84C]">{{ __('messages.welcome.smart_recitation') }}</h3>
                        <p class="text-[#f8fafc]/60 leading-relaxed">{{ __('messages.welcome.smart_recitation_desc') }}</p>
                    </div>
                    <div class="glass-card p-8 group">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#C9A84C] to-[#D4AF37] flex items-center justify-center mb-6 shadow-lg shadow-[#C9A84C]/30 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-[#0F172A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-[#C9A84C]">{{ __('messages.welcome.spaced_repetition') }}</h3>
                        <p class="text-[#f8fafc]/60 leading-relaxed">{{ __('messages.welcome.spaced_repetition_desc') }}</p>
                    </div>
                    <div class="glass-card p-8 group">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#1E3A8A] to-[#3B82F6] flex items-center justify-center mb-6 shadow-lg shadow-[#1E3A8A]/30 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-[#C9A84C]">{{ __('messages.welcome.auto_quizzes') }}</h3>
                        <p class="text-[#f8fafc]/60 leading-relaxed">{{ __('messages.welcome.auto_quizzes_desc') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-20 px-4 bg-white/[0.02]">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-4xl font-bold text-center mb-12 gradient-text" style="font-family: 'Amiri', serif;">{{ __('messages.welcome.how_it_works') }}</h2>
                <div class="space-y-8">
                    <div class="flex gap-6 items-start">
                        <div class="w-12 h-12 rounded-full bg-[#1B5E20] flex items-center justify-center text-white font-bold text-lg flex-shrink-0 shadow-lg">1</div>
                        <div>
                            <h3 class="text-xl font-bold text-[#C9A84C] mb-2">{{ __('messages.welcome.step_1_title') }}</h3>
                            <p class="text-[#f8fafc]/60">{{ __('messages.welcome.step_1_desc') }}</p>
                        </div>
                    </div>
                    <div class="flex gap-6 items-start">
                        <div class="w-12 h-12 rounded-full bg-[#C9A84C] flex items-center justify-center text-[#0F172A] font-bold text-lg flex-shrink-0 shadow-lg">2</div>
                        <div>
                            <h3 class="text-xl font-bold text-[#C9A84C] mb-2">{{ __('messages.welcome.step_2_title') }}</h3>
                            <p class="text-[#f8fafc]/60">{{ __('messages.welcome.step_2_desc') }}</p>
                        </div>
                    </div>
                    <div class="flex gap-6 items-start">
                        <div class="w-12 h-12 rounded-full bg-[#1B5E20] flex items-center justify-center text-white font-bold text-lg flex-shrink-0 shadow-lg">3</div>
                        <div>
                            <h3 class="text-xl font-bold text-[#C9A84C] mb-2">{{ __('messages.welcome.step_3_title') }}</h3>
                            <p class="text-[#f8fafc]/60">{{ __('messages.welcome.step_3_desc') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-20 px-4">
            <div class="max-w-3xl mx-auto text-center glass-card p-12">
                <h2 class="text-3xl font-bold mb-4" style="font-family: 'Amiri', serif;">
                    <span class="gradient-text">{{ __('messages.welcome.start_today') }}</span>
                </h2>
                <p class="text-[#f8fafc]/60 mb-8 text-lg">{{ __('messages.welcome.start_today_desc') }}</p>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#1B5E20] to-[#2E7D32] text-white text-lg px-10 py-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-xl shadow-[#1B5E20]/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    {{ __('messages.welcome.register_free') }}
                </a>
            </div>
        </section>

        <footer class="py-8 border-t border-[#1B5E20]/20">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-[#f8fafc]/40 text-sm">&copy; {{ date('Y') }} {{ __('messages.app_tagline') }}</div>
                    <div class="flex gap-6 text-[#f8fafc]/40 text-sm">
                        <span>Laravel 12</span>
                        <span>•</span>
                        <span>FilamentPHP</span>
                        <span>•</span>
                        <span>AI Powered</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
