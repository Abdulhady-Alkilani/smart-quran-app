<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold gradient-text" style="font-family: 'Amiri', serif;">{{ __('messages.srs.title') }}</h2>
            <a href="{{ route('reviews.index') }}" class="flex items-center gap-2 bg-white/5 hover:bg-white/10 border border-white/10 text-[#f8fafc]/70 hover:text-[#C9A84C] px-4 py-2 rounded-xl transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                {{ __('messages.srs.back_reviews') }}
            </a>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="glass-card p-6 mb-6">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#1B5E20] via-[#C9A84C] to-purple-500"></div>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-[#C9A84C]/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#C9A84C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div>
                    <h3 class="text-[#C9A84C] font-bold">{{ __('messages.srs.algorithm') }}</h3>
                    <p class="text-[#f8fafc]/40 text-xs">SuperMemo-2 (SM-2) — {{ __('messages.srs.algorithm_desc') }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white/5 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-white">{{ $totalCount }}</div>
                    <div class="text-[#f8fafc]/50 text-xs">{{ __('messages.srs.total_tracked') }}</div>
                </div>
                <div class="bg-white/5 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-green-400">{{ $memorizedCount }}</div>
                    <div class="text-[#f8fafc]/50 text-xs">{{ __('messages.srs.memorized') }}</div>
                </div>
                <div class="bg-white/5 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-yellow-400">{{ $learningCount }}</div>
                    <div class="text-[#f8fafc]/50 text-xs">{{ __('messages.srs.learning') }}</div>
                </div>
                <div class="bg-white/5 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-[#C9A84C]">{{ number_format($avgEasiness, 2) }}</div>
                    <div class="text-[#f8fafc]/50 text-xs">{{ __('messages.srs.avg_easiness') }}</div>
                </div>
            </div>
        </div>

        <div class="glass-card p-6 mb-6">
            <h3 class="text-[#C9A84C] font-bold mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ __('messages.srs.next_14_days') }}
            </h3>
            <div class="grid grid-cols-7 gap-2">
                @foreach($dailyLoad as $day)
                <div class="bg-white/5 rounded-xl p-3 text-center {{ $day['count'] > 0 ? 'border border-white/10' : 'border border-transparent' }}">
                    <div class="text-xs {{ $day['is_today'] ? 'text-[#C9A84C] font-bold' : 'text-[#f8fafc]/40' }}">{{ $day['label'] }}</div>
                    <div class="text-xs text-[#f8fafc]/30 mb-1">{{ $day['short'] }}</div>
                    <div class="text-lg font-bold {{ $day['count'] > 0 ? ($day['is_today'] ? 'text-red-400' : ($day['count'] > 5 ? 'text-amber-400' : 'text-green-400')) : 'text-[#f8fafc]/20' }}">{{ $day['count'] }}</div>
                </div>
                @endforeach
            </div>
        </div>

        @if($surahs->count() > 1)
        <div class="mb-6">
            <form method="GET" action="{{ route('reviews.schedule') }}" class="flex items-center gap-3">
                <label class="text-[#f8fafc]/60 text-sm whitespace-nowrap">{{ __('messages.reviews.filter_by_surah') }}:</label>
                <select name="surah" onchange="this.form.submit()" class="bg-white/5 border border-white/10 text-[#f8fafc] rounded-lg px-4 py-2 text-sm focus:border-[#C9A84C] focus:ring-[#C9A84C] transition">
                    <option value="">{{ __('messages.reviews.all_surahs') }}</option>
                    @foreach($surahs as $surah)
                    <option value="{{ $surah->id }}" {{ $surahFilter == $surah->id ? 'selected' : '' }}>{{ $surah->name_ar }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        @endif

        @php
        $sections = [
            ['items' => $overdue, 'title' => __('messages.srs.overdue'), 'color' => 'red', 'icon' => 'exclamation'],
            ['items' => $today, 'title' => __('messages.srs.due_today'), 'color' => 'amber', 'icon' => 'clock'],
            ['items' => $thisWeek, 'title' => __('messages.srs.this_week'), 'color' => 'blue', 'icon' => 'calendar'],
            ['items' => $thisMonth, 'title' => __('messages.srs.this_month'), 'color' => 'purple', 'icon' => 'calendar'],
            ['items' => $later, 'title' => __('messages.srs.later'), 'color' => 'green', 'icon' => 'check'],
        ];
        @endphp

        @foreach($sections as $section)
        @if($section['items']->count() > 0)
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                @if($section['color'] === 'red')
                <div class="w-8 h-8 rounded-lg bg-red-500/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                </div>
                @elseif($section['color'] === 'amber')
                <div class="w-8 h-8 rounded-lg bg-amber-500/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                @elseif($section['color'] === 'blue')
                <div class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                @elseif($section['color'] === 'purple')
                <div class="w-8 h-8 rounded-lg bg-purple-500/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                @else
                <div class="w-8 h-8 rounded-lg bg-green-500/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                @endif
                <h3 class="text-lg font-bold text-[#f8fafc]/80">{{ $section['title'] }}</h3>
                <span class="text-xs text-[#f8fafc]/30 bg-white/5 px-2 py-1 rounded-full">{{ $section['items']->count() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-white/10">
                            <th class="text-right py-3 px-3 text-[#f8fafc]/40 font-medium text-xs">{{ __('messages.srs.ayah') }}</th>
                            <th class="text-right py-3 px-3 text-[#f8fafc]/40 font-medium text-xs">{{ __('messages.srs.status') }}</th>
                            <th class="text-center py-3 px-3 text-[#f8fafc]/40 font-medium text-xs">{{ __('messages.srs.reps') }}</th>
                            <th class="text-center py-3 px-3 text-[#f8fafc]/40 font-medium text-xs">{{ __('messages.srs.easiness') }}</th>
                            <th class="text-center py-3 px-3 text-[#f8fafc]/40 font-medium text-xs">{{ __('messages.srs.interval') }}</th>
                            <th class="text-center py-3 px-3 text-[#f8fafc]/40 font-medium text-xs">{{ __('messages.srs.last_review') }}</th>
                            <th class="text-center py-3 px-3 text-[#f8fafc]/40 font-medium text-xs">{{ __('messages.srs.next_review') }}</th>
                            <th class="text-center py-3 px-3 text-[#f8fafc]/40 font-medium text-xs"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($section['items'] as $item)
                        <tr class="border-b border-white/5 hover:bg-white/5 transition">
                            <td class="py-3 px-3">
                                <div class="flex items-center gap-2">
                                    <span class="text-[#C9A84C] font-medium text-xs">{{ $item->ayah->surah->name_ar }}</span>
                                    <span class="text-[#f8fafc]/40 text-xs">{{ $item->ayah->number_in_surah }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-3">
                                <span class="px-2 py-0.5 rounded-full text-xs {{ $item->status === 'memorized' ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400' }}">
                                    {{ $item->status === 'memorized' ? __('messages.srs.memorized') : __('messages.srs.learning') }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-center text-[#f8fafc]/70">{{ $item->repetition_count ?? 0 }}</td>
                            <td class="py-3 px-3 text-center">
                                @php $ef = $item->easiness_factor ?? 2.5; @endphp
                                <span class="px-2 py-0.5 rounded text-xs {{ $ef >= 2.5 ? 'bg-green-500/10 text-green-400' : ($ef >= 2.0 ? 'bg-yellow-500/10 text-yellow-400' : 'bg-red-500/10 text-red-400') }}">
                                    {{ number_format($ef, 2) }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-center text-[#f8fafc]/70">
                                @if($item->interval_days)
                                <span class="text-xs">{{ $item->interval_days }} {{ __('messages.srs.days') }}</span>
                                @else
                                <span class="text-[#f8fafc]/20">—</span>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-center text-[#f8fafc]/40 text-xs">
                                @if($item->last_review_date)
                                    {{ $item->last_review_date->translatedFormat('d M') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="py-3 px-3 text-center">
                                @php
                                    $isOverdue = $item->next_review_date->lt(\Carbon\Carbon::today());
                                @endphp
                                <span class="text-xs {{ $isOverdue ? 'text-red-400 font-bold' : 'text-[#f8fafc]/60' }}">
                                    {{ $item->next_review_date->translatedFormat('d M') }}
                                </span>
                                <div class="text-[10px] {{ $isOverdue ? 'text-red-400/60' : 'text-[#f8fafc]/30' }}">{{ $item->next_review_date->diffForHumans() }}</div>
                            </td>
                            <td class="py-3 px-3 text-center">
                                <a href="{{ route('recitation.create', $item->ayah) }}" class="text-[#C9A84C] hover:text-[#FFD700] transition text-xs">{{ __('messages.srs.review') }}</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
        @endforeach

        @if($totalCount === 0)
        <div class="glass-card p-12 text-center">
            <div class="w-20 h-20 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-[#f8fafc]/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-[#C9A84C] mb-3" style="font-family: 'Amiri', serif;">{{ __('messages.srs.no_data') }}</h3>
            <p class="text-[#f8fafc]/60 mb-6">{{ __('messages.srs.no_data_desc') }}</p>
            <a href="{{ route('quran.index') }}" class="inline-flex items-center gap-2 bg-[#1B5E20] hover:bg-[#2E7D32] text-white px-6 py-3 rounded-xl transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                {{ __('messages.reviews.memorize_new') }}
            </a>
        </div>
        @endif

        <div class="glass-card p-6 mt-6">
            <h3 class="text-[#C9A84C] font-bold mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ __('messages.srs.how_it_works') }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white/5 rounded-xl p-4">
                    <div class="text-green-400 font-bold text-sm mb-1">Q ≥ 95%</div>
                    <p class="text-[#f8fafc]/50 text-xs">{{ __('messages.srs.quality_5') }}</p>
                </div>
                <div class="bg-white/5 rounded-xl p-4">
                    <div class="text-yellow-400 font-bold text-sm mb-1">Q 80–94%</div>
                    <p class="text-[#f8fafc]/50 text-xs">{{ __('messages.srs.quality_3') }}</p>
                </div>
                <div class="bg-white/5 rounded-xl p-4">
                    <div class="text-red-400 font-bold text-sm mb-1">Q &lt; 80%</div>
                    <p class="text-[#f8fafc]/50 text-xs">{{ __('messages.srs.quality_0') }}</p>
                </div>
            </div>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white/5 rounded-xl p-4">
                    <div class="text-[#C9A84C] font-bold text-sm mb-1">{{ __('messages.srs.ef_label') }}</div>
                    <p class="text-[#f8fafc]/50 text-xs">{{ __('messages.srs.ef_desc') }}</p>
                </div>
                <div class="bg-white/5 rounded-xl p-4">
                    <div class="text-[#C9A84C] font-bold text-sm mb-1">{{ __('messages.srs.interval_label') }}</div>
                    <p class="text-[#f8fafc]/50 text-xs">{{ __('messages.srs.interval_desc') }}</p>
                </div>
                <div class="bg-white/5 rounded-xl p-4">
                    <div class="text-[#C9A84C] font-bold text-sm mb-1">{{ __('messages.srs.reps_label') }}</div>
                    <p class="text-[#f8fafc]/50 text-xs">{{ __('messages.srs.reps_desc') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
