<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold gradient-text" style="font-family: 'Amiri', serif;">{{ __('messages.dashboard.surah') }} {{ $surah->name_ar }}</h2>
            <div class="flex gap-2 flex-wrap">
                @php $hasTajweed = $ayahs->whereNotNull('text_tajweed')->count() > 0; @endphp
                @if($hasTajweed)
                <button onclick="window.__toggleTajweed()" id="tajweedBtn"
                        class="flex items-center gap-2 bg-purple-500/20 text-purple-400 hover:bg-purple-500/30 px-4 py-2 rounded-lg transition text-sm border border-purple-500/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                    {{ __('messages.tajweed.toggle') }}
                </button>
                <a href="{{ route('quran.tajweed-guide') }}" class="flex items-center gap-2 bg-[#C9A84C]/10 text-[#C9A84C]/70 hover:bg-[#C9A84C]/20 hover:text-[#C9A84C] px-4 py-2 rounded-lg transition text-sm border border-[#C9A84C]/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    {{ __('messages.tajweed.guide_title') }}
                </a>
                @endif
                <a href="{{ route('quiz.complete', $surah) }}" class="flex items-center gap-2 bg-[#C9A84C]/20 text-[#C9A84C] hover:bg-[#C9A84C]/30 px-4 py-2 rounded-lg transition text-sm border border-[#C9A84C]/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    {{ __('messages.complete_ayah.button') }}
                </a>
                <a href="{{ route('quiz.show', $surah) }}" class="flex items-center gap-2 bg-[#1B5E20]/20 text-[#1B5E20] hover:bg-[#1B5E20]/30 px-4 py-2 rounded-lg transition text-sm border border-[#1B5E20]/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    {{ __('messages.quran.surah_quiz') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="glass-card p-6 mb-8 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#1B5E20] via-[#C9A84C] to-[#1B5E20]"></div>
            <div class="flex justify-between items-center flex-wrap gap-4">
                <div>
                    <h3 class="text-3xl font-bold text-[#C9A84C]" style="font-family: 'Amiri', serif;">{{ $surah->name_ar }}</h3>
                    <p class="text-[#f8fafc]/60">{{ $surah->name_en }}</p>
                </div>
                <div class="flex gap-3">
                    <span class="px-4 py-2 rounded-full text-sm font-medium {{ $surah->revelation_type === 'Meccan' ? 'bg-amber-500/20 text-amber-400 border border-amber-500/20' : 'bg-blue-500/20 text-blue-400 border border-blue-500/20' }}">
                        {{ $surah->revelation_type === 'Meccan' ? __('messages.quran.meccan') : __('messages.quran.medinan') }}
                    </span>
                    <span class="px-4 py-2 rounded-full bg-[#1B5E20]/20 text-[#C9A84C] text-sm border border-[#1B5E20]/20">{{ $surah->total_ayahs }} {{ __('messages.quran.ayahs_count') }}</span>
                </div>
            </div>
        </div>

        @php
            $isRtl = app()->getLocale() === 'ar';
            $prevIcon = $isRtl ? '&rarr;' : '&larr;';
            $nextIcon = $isRtl ? '&larr;' : '&rarr;';
        @endphp

        <div class="flex justify-between items-center mb-6">
            @if($surah->number > 1)
            <a href="{{ route('quran.show', $surah->number - 1) }}" class="bg-white/5 hover:bg-white/10 border border-white/10 text-[#f8fafc]/70 hover:text-[#C9A84C] px-4 py-2 rounded-xl transition flex items-center gap-2">
                {!! $prevIcon !!} {{ __('messages.quran.previous_surah') }}
            </a>
            @else
            <div></div>
            @endif

            <a href="{{ route('quran.index') }}" class="flex items-center gap-1.5 text-[#C9A84C] hover:text-[#FFD700] transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                {{ __('messages.quran.all_surahs') }}
            </a>

            @if($surah->number < 114)
            <a href="{{ route('quran.show', $surah->number + 1) }}" class="bg-white/5 hover:bg-white/10 border border-white/10 text-[#f8fafc]/70 hover:text-[#C9A84C] px-4 py-2 rounded-xl transition flex items-center gap-2">
                {{ __('messages.quran.next_surah') }} {!! $nextIcon !!}
            </a>
            @else
            <div></div>
            @endif
        </div>

        @if($surah->number != 9 && $surah->number != 1)
        <div class="text-center mb-8">
            <span class="text-2xl text-[#C9A84C]/70" style="font-family: 'Noto Naskh Arabic', serif;">{{ __('messages.bismillah') }}</span>
        </div>
        @endif

        <div class="mb-6 flex items-center gap-3">
            <div class="relative flex-1 max-w-xs">
                <svg class="absolute {{ $isRtl ? 'right-3' : 'left-3' }} top-1/2 -translate-y-1/2 w-4 h-4 text-[#f8fafc]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="number" id="ayahSearch" min="1" max="{{ $surah->total_ayahs }}" placeholder="{{ __('messages.quran.search_ayah_placeholder') }}"
                       class="w-full bg-white/5 border border-white/10 rounded-xl {{ $isRtl ? 'pr-10 pl-4' : 'pl-10 pr-4' }} py-2.5 text-[#f8fafc] text-sm placeholder-[#f8fafc]/30 focus:outline-none focus:border-[#C9A84C] focus:ring-1 focus:ring-[#C9A84C] transition">
            </div>
            <span class="text-[#f8fafc]/30 text-sm">/ {{ $surah->total_ayahs }}</span>
        </div>

        @php
            $tajweedService = app(\App\Services\TajweedService::class);
        @endphp

        <div class="space-y-4">
            @foreach($ayahs as $ayah)
            @php
                $tajweedHtml = $ayah->text_tajweed ? $tajweedService->parse($ayah->text_tajweed) : null;
            @endphp
            <div class="glass-card p-6 group" id="ayah-{{ $ayah->number_in_surah }}">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-full bg-[#1B5E20]/20 flex items-center justify-center text-[#C9A84C] text-sm font-bold border border-[#1B5E20]/30">{{ $ayah->number_in_surah }}</span>
                        @php $status = $ayah->memorizationProgress->first()?->status ?? 'new'; @endphp
                        <span class="flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium {{ $status === 'memorized' ? 'bg-green-500/20 text-green-400 border border-green-500/20' : ($status === 'learning' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/20' : 'bg-white/5 text-[#f8fafc]/40 border border-white/10') }}">
                            @if($status === 'memorized')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @elseif($status === 'learning')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            @else
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            @endif
                            {{ $status === 'memorized' ? __('messages.quran.memorized') : ($status === 'learning' ? __('messages.quran.learning') : __('messages.quran.new')) }}
                        </span>
                    </div>
                    @if($ayah->audio_url)
                    <button onclick="playAudio('{{ $ayah->audio_url }}')" class="text-[#C9A84C] hover:text-[#FFD700] transition opacity-60 group-hover:opacity-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                    </button>
                    @endif
                </div>

                <div class="text-3xl leading-[2.5] mb-6 text-right ayah-text" style="font-family: 'Noto Naskh Arabic', serif;">
                    <span class="ayah-uthmani">{{ $ayah->text_uthmani }}</span>
                    @if($tajweedHtml)
                    <span class="ayah-tajweed" style="display:none!important">{!! $tajweedHtml !!}</span>
                    @endif
                </div>

                <div class="flex justify-end items-center gap-3 opacity-60 group-hover:opacity-100 transition">
                    @if($status !== 'memorized')
                    <form method="POST" action="{{ route('quran.start', $ayah) }}" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center gap-1.5 bg-[#C9A84C]/20 hover:bg-[#C9A84C]/30 text-[#C9A84C] px-4 py-2 rounded-lg transition text-sm border border-[#C9A84C]/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                            {{ __('messages.quran.start_memorizing') }}
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('recitation.create', $ayah) }}" class="flex items-center gap-1.5 bg-[#1B5E20] hover:bg-[#2E7D32] text-white px-4 py-2 rounded-lg transition text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                        {{ __('messages.quran.recite') }}
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        @if($hasTajweed)
        <div class="mt-6 mb-6">
            <button onclick="toggleLegend()" class="flex items-center gap-2 text-sm text-purple-400/70 hover:text-purple-400 transition mx-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ __('messages.tajweed.legend_title') }}
                <svg class="w-3 h-3 transition-transform" id="legendArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>

            <div id="tajweedLegend" class="hidden mt-4 glass-card p-6">
                <div class="text-center mb-4">
                    <h3 class="text-lg font-bold text-[#C9A84C]" style="font-family: 'Amiri', serif;">{{ __('messages.tajweed.legend_title') }}</h3>
                    <p class="text-[#f8fafc]/40 text-xs mt-1">{{ __('messages.tajweed.legend_desc') }}</p>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    @foreach($tajweedRules as $key => $rule)
                    @if(in_array($key, ['h', 's', 'l']))
                    @continue
                    @endif
                    <a href="{{ route('quran.tajweed-guide') }}#rule-{{ $key }}" class="tajweed-legend-item flex items-center gap-2 px-3 py-2 rounded-lg bg-white/[0.03] border border-white/[0.06] hover:border-white/20 transition">
                        <span class="w-3 h-3 rounded-full flex-shrink-0" style="background-color: {{ $rule['color'] }};"></span>
                        <div class="min-w-0">
                            <div class="text-xs font-medium" style="color: {{ $rule['color'] }};">
                                {{ app()->getLocale() === 'ar' ? $rule['desc_ar'] : $rule['desc_en'] }}
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                <div class="text-center mt-4">
                    <a href="{{ route('quran.tajweed-guide') }}" class="inline-flex items-center gap-1.5 text-xs text-purple-400/70 hover:text-purple-400 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        {{ __('messages.tajweed.full_guide') }} →
                    </a>
                </div>
            </div>
        </div>
        @endif

        <div class="flex justify-between items-center mt-8 pt-8 border-t border-white/10">
            @if($surah->number > 1)
            <a href="{{ route('quran.show', $surah->number - 1) }}" class="bg-white/5 hover:bg-white/10 border border-white/10 text-[#f8fafc]/70 hover:text-[#C9A84C] px-6 py-3 rounded-xl transition">{!! $prevIcon !!} {{ __('messages.quran.previous_surah') }}</a>
            @else
            <div></div>
            @endif

            @if($surah->number < 114)
            <a href="{{ route('quran.show', $surah->number + 1) }}" class="bg-white/5 hover:bg-white/10 border border-white/10 text-[#f8fafc]/70 hover:text-[#C9A84C] px-6 py-3 rounded-xl transition">{{ __('messages.quran.next_surah') }} {!! $nextIcon !!}</a>
            @else
            <div></div>
            @endif
        </div>
    </div>

    <style>
        tajweed { display: inline; cursor: help; transition: all 0.2s; }
        tajweed:hover { filter: brightness(1.4); text-shadow: 0 0 8px currentColor; }
        tajweed.ham_wasl, tajweed.slnt, tajweed.lsm_shms { color: #AAAAAA; }
        tajweed.madda_normal { color: #537FFF; }
        tajweed.madda_permissible { color: #4050FF; }
        tajweed.madda_necessary { color: #000EBC; }
        tajweed.qlq { color: #DD0008; }
        tajweed.madda_obligatory { color: #2144C1; }
        tajweed.ikhf_shfw { color: #D500B7; }
        tajweed.ikhf { color: #9400A8; }
        tajweed.idghm_shfw { color: #58B800; }
        tajweed.iqlb { color: #26BFFD; }
        tajweed.idgh_ghn { color: #169777; }
        tajweed.idgh_w_ghn { color: #169200; }
        tajweed.idgh_mus { color: #A1A1A1; }
        tajweed.ghn { color: #FF7E1E; }
        .tajweed-legend-item:hover { transform: translateY(-1px); }

        .tajweed-tooltip {
            position: fixed; z-index: 9999; pointer-events: none;
            background: #1E293B; border: 1px solid rgba(201,168,76,0.3);
            border-radius: 12px; padding: 10px 14px; max-width: 280px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
            opacity: 0; transform: translateY(4px);
            transition: opacity 0.15s, transform 0.15s;
        }
        .tajweed-tooltip.visible { opacity: 1; transform: translateY(0); }
    </style>

    @php
        $locale = app()->getLocale();
        $descKey = $locale === 'ar' ? 'desc_ar' : 'desc_en';
        $exampleKey = $locale === 'ar' ? 'example_ar' : 'example_en';
        $tajweedMap = [];
        foreach($tajweedRules as $key => $rule) {
            $tajweedMap[$rule['type']] = [
                'label' => $rule[$descKey],
                'color' => $rule['color'],
                'example' => $rule[$exampleKey],
                'key' => $key,
            ];
        }
        $tajweedMapJson = json_encode($tajweedMap, JSON_UNESCAPED_UNICODE);
        $guideUrl = route('quran.tajweed-guide');
        $guideLabel = __('messages.tajweed.guide_link');
    @endphp

    <script>
    (function(){
        var tajweedMap = {!! $tajweedMapJson !!};
        var guideUrl = '{!! $guideUrl !!}';
        var guideLabel = '{!! $guideLabel !!}';

        var tajweedEnabled = localStorage.getItem('tajweed_enabled') === 'true';
        var tooltip = document.createElement('div');
        tooltip.className = 'tajweed-tooltip';
        document.body.appendChild(tooltip);

        window.__toggleTajweed = function() {
            tajweedEnabled = !tajweedEnabled;
            localStorage.setItem('tajweed_enabled', tajweedEnabled);
            applyTajweed();
        };

        function applyTajweed() {
            var texts = document.querySelectorAll('.ayah-text');
            for (var i = 0; i < texts.length; i++) {
                var el = texts[i];
                var uthmani = el.querySelector('.ayah-uthmani');
                var tajweed = el.querySelector('.ayah-tajweed');
                if (tajweedEnabled && tajweed) {
                    uthmani.style.display = 'none';
                    tajweed.style.display = 'inline';
                } else {
                    uthmani.style.display = '';
                    if (tajweed) tajweed.style.display = 'none';
                }
            }
            var btn = document.getElementById('tajweedBtn');
            if (btn) {
                if (tajweedEnabled) {
                    btn.classList.remove('bg-purple-500/20', 'text-purple-400', 'border-purple-500/30');
                    btn.classList.add('bg-purple-500/40', 'text-purple-300', 'border-purple-400/50');
                } else {
                    btn.classList.remove('bg-purple-500/40', 'text-purple-300', 'border-purple-400/50');
                    btn.classList.add('bg-purple-500/20', 'text-purple-400', 'border-purple-500/30');
                }
            }
        }

        document.addEventListener('mouseover', function(e) {
            var t = e.target.closest('tajweed');
            if (!t || !tajweedMap) return;
            var type = t.getAttribute('data-type');
            var info = tajweedMap[type];
            if (!info) return;
            tooltip.innerHTML = '<div style="font-size:13px;font-weight:700;color:' + info.color + ';">' + info.label + '</div>'
                + '<div style="font-size:12px;color:rgba(248,250,252,0.6);margin-top:2px;">' + info.example + '</div>';
            var rect = t.getBoundingClientRect();
            var top = rect.bottom + 8;
            var left = rect.left + (rect.width / 2) - 140;
            if (top + 120 > window.innerHeight) top = rect.top - 120;
            if (left < 8) left = 8;
            if (left + 280 > window.innerWidth) left = window.innerWidth - 288;
            tooltip.style.top = top + 'px';
            tooltip.style.left = left + 'px';
            tooltip.classList.add('visible');
        });

        document.addEventListener('mouseout', function(e) {
            var t = e.target.closest('tajweed');
            if (t) tooltip.classList.remove('visible');
        });

        var currentAudio = null;
        window.playAudio = function(url) {
            if (currentAudio) { currentAudio.pause(); currentAudio = null; }
            currentAudio = new Audio(url);
            currentAudio.play();
        };

        window.toggleLegend = function() {
            document.getElementById('tajweedLegend').classList.toggle('hidden');
        };

        var searchInput = document.getElementById('ayahSearch');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                var val = this.value.trim();
                var cards = document.querySelectorAll('[id^="ayah-"]');
                for (var i = 0; i < cards.length; i++) {
                    var num = cards[i].id.replace('ayah-', '');
                    if (!val || num === val) {
                        cards[i].style.display = '';
                        cards[i].style.opacity = '1';
                    } else {
                        cards[i].style.display = 'none';
                    }
                }
                if (val) {
                    var target = document.getElementById('ayah-' + val);
                    if (target) target.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        }

        applyTajweed();
    })();
    </script>
</x-app-layout>
