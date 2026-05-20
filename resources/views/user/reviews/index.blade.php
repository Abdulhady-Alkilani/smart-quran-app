<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold gradient-text" style="font-family: 'Amiri', serif;">{{ __('messages.reviews.title') }}</h2>
            <a href="{{ route('reviews.schedule') }}" class="flex items-center gap-2 bg-white/5 hover:bg-white/10 border border-white/10 text-[#f8fafc]/70 hover:text-[#C9A84C] px-4 py-2 rounded-xl transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                {{ __('messages.reviews.view_schedule') }}
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="glass-card p-5 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-red-500 to-red-400"></div>
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-3xl font-bold text-red-400 mb-1">{{ $overdueCount }}</div>
                        <div class="text-[#f8fafc]/60 text-sm">{{ __('messages.reviews.overdue') }}</div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-red-500/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>
            <div class="glass-card p-5 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#C9A84C] to-[#FFD700]"></div>
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-3xl font-bold text-[#C9A84C] mb-1">{{ $todayCount }}</div>
                        <div class="text-[#f8fafc]/60 text-sm">{{ __('messages.reviews.due_today') }}</div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-[#C9A84C]/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#C9A84C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>
            <div class="glass-card p-5 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-blue-400"></div>
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-3xl font-bold text-blue-400 mb-1">{{ $upcomingCount }}</div>
                        <div class="text-[#f8fafc]/60 text-sm">{{ __('messages.reviews.upcoming') }}</div>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        @if($totalDue > 0 && $firstDueRoute)
        <a href="{{ $firstDueRoute }}" class="glass-card p-5 mb-6 flex items-center justify-between group hover:border-[#C9A84C]/40 transition-all duration-300 block">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-[#1B5E20] to-[#2E7D32] flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="font-bold text-[#C9A84C] text-lg">{{ __('messages.reviews.start_quick_review') }}</div>
                    <div class="text-[#f8fafc]/40 text-sm">{{ __('messages.reviews.start_quick_review_desc') }}</div>
                </div>
            </div>
            <svg class="w-6 h-6 text-[#C9A84C] group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
        @endif

        @if($surahs->count() > 1)
        <div class="mb-6">
            <form method="GET" action="{{ route('reviews.index') }}" class="flex items-center gap-3">
                <label class="text-[#f8fafc]/60 text-sm whitespace-nowrap">{{ __('messages.reviews.filter_by_surah') }}:</label>
                <select name="surah" onchange="this.form.submit()" class="bg-white/5 border border-white/10 text-[#f8fafc] rounded-lg px-4 py-2 text-sm focus:border-[#C9A84C] focus:ring-[#C9A84C] transition">
                    <option value="" class="bg-gray-900 text-white">{{ __('messages.reviews.all_surahs') }}</option>
                    @foreach($surahs as $surah)
                    <option value="{{ $surah->id }}" {{ $surahFilter == $surah->id ? 'selected' : '' }} class="bg-gray-900 text-white">{{ $surah->name_ar }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        @endif

        <div class="glass-card p-8 mb-8 text-center relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#1B5E20] via-[#C9A84C] to-[#1B5E20]"></div>
            <div class="text-6xl font-bold text-[#C9A84C] mb-2">{{ $totalDue }}</div>
            <div class="text-[#f8fafc]/60 text-lg">{{ __('messages.reviews.due_ayahs') }}</div>
            @if($totalDue > 0)
            <div class="mt-4 w-48 mx-auto bg-white/10 rounded-full h-2">
                <div class="bg-gradient-to-r from-[#C9A84C] to-[#FFD700] h-2 rounded-full" style="width: 100%"></div>
            </div>
            <p class="text-[#f8fafc]/40 text-sm mt-2">{{ __('messages.reviews.start_review') }}</p>
            @endif
        </div>

        @if($dueReviews->count() > 0)
        <div class="space-y-4">
            @foreach($dueReviews as $index => $item)
            <div class="glass-card p-6 group" style="animation: fadeInUp 0.4s ease-out {{ $index * 0.05 }}s both;">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-[#1B5E20]/20 flex items-center justify-center text-[#C9A84C] text-sm font-bold">{{ $index + 1 }}</span>
                        <div>
                            <span class="text-[#C9A84C] font-medium">{{ __('messages.dashboard.surah') }} {{ $item->ayah->surah->name_ar }}</span>
                            <span class="text-[#f8fafc]/40 text-sm"> — {{ __('messages.dashboard.ayah') }} {{ $item->ayah->number_in_surah }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        @if($item->interval_days)
                        <span class="flex items-center gap-1 px-2 py-1 rounded-full text-xs {{ $item->interval_days >= 30 ? 'bg-green-500/20 text-green-400' : ($item->interval_days >= 7 ? 'bg-blue-500/20 text-blue-400' : 'bg-yellow-500/20 text-yellow-400') }}">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $item->interval_days }} {{ __('messages.reviews.days_interval') }}
                        </span>
                        @endif
                        @if($item->last_review_date)
                        <span class="text-[#f8fafc]/30 text-xs">{{ __('messages.reviews.last_review') }} {{ $item->last_review_date->diffForHumans() }}</span>
                        @endif
                    </div>
                </div>
                <div class="text-2xl leading-loose mb-4 text-right" style="font-family: 'Noto Naskh Arabic', serif;">{{ $item->ayah->text_uthmani }}</div>
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <span class="flex items-center gap-1 px-3 py-1 rounded-full text-xs {{ $item->status === 'memorized' ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400' }}">
                            @if($item->status === 'memorized')
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @else
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            @endif
                            {{ $item->status === 'memorized' ? __('messages.reviews.memorized') : __('messages.reviews.learning') }}
                        </span>
                        <span class="text-[#f8fafc]/30 text-xs">{{ __('messages.reviews.repetition') }} {{ $item->repetition_count ?? 0 }}</span>
                    </div>
                    <a href="{{ route('recitation.create', $item->ayah) }}" class="flex items-center gap-2 bg-[#1B5E20] hover:bg-[#2E7D32] text-white px-6 py-2 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg shadow-[#1B5E20]/20 opacity-80 group-hover:opacity-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                        {{ __('messages.reviews.recite_now') }}
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="glass-card p-12 text-center">
            <div class="w-20 h-20 rounded-full bg-green-500/20 flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-3xl font-bold text-[#C9A84C] mb-3" style="font-family: 'Amiri', serif;">{{ __('messages.reviews.mashallah') }}</h3>
            <p class="text-[#f8fafc]/60 text-lg mb-6">{{ __('messages.reviews.all_done') }}</p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('quran.index') }}" class="flex items-center gap-2 bg-[#1B5E20] hover:bg-[#2E7D32] text-white px-6 py-3 rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    {{ __('messages.reviews.memorize_new') }}
                </a>
                <a href="{{ route('dashboard') }}" class="bg-white/5 hover:bg-white/10 border border-white/10 text-[#f8fafc] px-6 py-3 rounded-xl transition">{{ __('messages.reviews.back_dashboard') }}</a>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
