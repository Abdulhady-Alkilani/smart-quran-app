<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold gradient-text" style="font-family: 'Amiri', serif;">{{ __('messages.tajweed.guide_title') }}</h2>
            <a href="{{ route('quran.index') }}" class="flex items-center gap-2 text-sm text-[#C9A84C]/70 hover:text-[#C9A84C] transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                {{ __('messages.quran.all_surahs') }}
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="glass-card p-8 mb-8 text-center relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-purple-600 via-[#C9A84C] to-purple-600"></div>
            <div class="w-20 h-20 rounded-full bg-purple-500/20 flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
            </div>
            <h1 class="text-3xl font-bold text-[#C9A84C] mb-3" style="font-family: 'Amiri', serif;">{{ __('messages.tajweed.guide_title') }}</h1>
            <p class="text-[#f8fafc]/60 max-w-2xl mx-auto leading-relaxed">{{ __('messages.tajweed.guide_intro') }}</p>
        </div>

        @php
            $locale = app()->getLocale();
            $descKey = $locale === 'ar' ? 'desc_ar' : 'desc_en';
            $explanationKey = $locale === 'ar' ? 'explanation_ar' : 'explanation_en';
            $exampleKey = $locale === 'ar' ? 'example_ar' : 'example_en';
            $howToKey = $locale === 'ar' ? 'how_to_ar' : 'how_to_en';
            $catKey = $locale === 'ar' ? 'category_ar' : 'category_en';
            $icons = [
                'أحكام المد' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>',
                'Prolongation (Madd)' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>',
                'أحكام النون الساكنة والتنوين' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
                'Still Nun & Tanween Rules' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
                'أحكام الميم الساكنة' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>',
                'Still Mim Rules' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>',
                'أحكام الوقف' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728"/></svg>',
                'Stopping Rules' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728"/></svg>',
            ];
        @endphp

        @foreach($rulesByCategory as $category => $rules)
        <div class="mb-10" id="category-{{ \Str::slug($category) }}">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center text-purple-400">
                    {!! $icons[$category] ?? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' !!}
                </div>
                <h2 class="text-xl font-bold text-[#C9A84C]" style="font-family: 'Amiri', serif;">{{ $category }}</h2>
                <div class="flex-1 h-px bg-gradient-to-r from-[#C9A84C]/30 to-transparent"></div>
            </div>

            <div class="space-y-4">
                @foreach($rules as $key => $rule)
                <div class="glass-card overflow-hidden" id="rule-{{ $key }}">
                    <div class="p-6">
                        <div class="flex items-start gap-4 mb-4">
                            <div class="w-14 h-14 rounded-xl flex items-center justify-center flex-shrink-0 text-2xl font-bold border-2" style="background-color: {{ $rule['color'] }}20; color: {{ $rule['color'] }}; border-color: {{ $rule['color'] }}40;">
                                {{ $key }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-1">
                                    <h3 class="text-lg font-bold" style="color: {{ $rule['color'] }};">{{ $rule[$descKey] }}</h3>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-white/5 text-[#f8fafc]/40 border border-white/10">{{ $rule['type'] }}</span>
                                    <span class="w-3 h-3 rounded-full flex-shrink-0" style="background-color: {{ $rule['color'] }};"></span>
                                    <span class="text-[10px] text-[#f8fafc]/30 font-mono">{{ $rule['color'] }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 p-4 rounded-xl bg-white/[0.03] border border-white/[0.06]">
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-[#C9A84C] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="text-sm text-[#f8fafc]/70 leading-relaxed">{{ $rule[$explanationKey] }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 rounded-xl bg-purple-500/[0.05] border border-purple-500/10">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    <span class="text-xs font-semibold text-purple-400 uppercase">{{ __('messages.tajweed.example') }}</span>
                                </div>
                                <p class="text-sm text-[#f8fafc]/80 leading-relaxed" dir="rtl" style="font-family: 'Noto Naskh Arabic', serif;">{{ $rule[$exampleKey] }}</p>
                            </div>

                            <div class="p-4 rounded-xl bg-green-500/[0.05] border border-green-500/10">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span class="text-xs font-semibold text-green-400 uppercase">{{ __('messages.tajweed.how_to') }}</span>
                                </div>
                                <p class="text-sm text-[#f8fafc]/80 leading-relaxed">{{ $rule[$howToKey] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-3 bg-white/[0.02] border-t border-white/[0.04] flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-[#f8fafc]/30">{{ __('messages.tajweed.color_sample') }}:</span>
                            <span class="text-lg font-bold" style="color: {{ $rule['color'] }}; font-family: 'Noto Naskh Arabic', serif;">بِسْمِ</span>
                        </div>
                        <a href="{{ route('quran.show', 1) }}" class="text-xs text-[#C9A84C]/50 hover:text-[#C9A84C] transition flex items-center gap-1">
                            {{ __('messages.tajweed.see_in_quran') }}
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <div class="glass-card p-8 mt-10">
            <h3 class="text-lg font-bold text-[#C9A84C] mb-4 text-center" style="font-family: 'Amiri', serif;">{{ __('messages.tajweed.quick_reference') }}</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                @foreach($allRules as $key => $rule)
                <a href="#rule-{{ $key }}" class="flex items-center gap-2 px-3 py-2.5 rounded-xl bg-white/[0.03] border border-white/[0.06] hover:border-white/20 transition group">
                    <span class="w-4 h-4 rounded-full flex-shrink-0" style="background-color: {{ $rule['color'] }};"></span>
                    <span class="text-xs text-[#f8fafc]/60 group-hover:text-[#f8fafc]/90 transition truncate" style="color: {{ $rule['color'] }};">{{ $rule[$descKey] }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        html { scroll-behavior: smooth; }
    </style>
    @endpush
</x-app-layout>
