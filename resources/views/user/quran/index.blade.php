<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold" style="font-family: 'Amiri', serif;">{{ __('messages.quran.title') }}</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="surahBrowser()">
        <div class="mb-8 flex flex-col sm:flex-row gap-4">
            <div class="relative flex-1">
                <svg class="absolute {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }} top-1/2 -translate-y-1/2 w-5 h-5 text-[#f8fafc]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" x-model="search" placeholder="{{ __('messages.quran.search_placeholder') }}"
                       class="w-full bg-white/5 border border-white/10 rounded-xl {{ app()->getLocale() === 'ar' ? 'pr-12 pl-4' : 'pl-12 pr-4' }} py-3 text-[#f8fafc] placeholder-[#f8fafc]/40 focus:outline-none focus:border-[#1B5E20] focus:ring-1 focus:ring-[#1B5E20] transition">
            </div>
            <select x-model="filter"
                    class="bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-[#f8fafc] focus:outline-none focus:border-[#1B5E20] focus:ring-1 focus:ring-[#1B5E20] transition">
                <option value="" class="bg-[#0F172A] text-white">{{ __('messages.quran.all') }}</option>
                <option value="Meccan" class="bg-[#0F172A] text-white">{{ __('messages.quran.meccan') }}</option>
                <option value="Medinan" class="bg-[#0F172A] text-white">{{ __('messages.quran.medinan') }}</option>
            </select>
            <a href="{{ route('quran.tajweed-guide') }}" class="flex items-center justify-center gap-2 bg-purple-500/20 text-purple-400 hover:bg-purple-500/30 px-5 py-3 rounded-xl transition text-sm border border-purple-500/30 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                {{ __('messages.tajweed.guide_title') }}
            </a>
        </div>

        <div class="mb-4 text-[#f8fafc]/50 text-sm">
            {{ __('messages.quran.showing') }} <span class="text-[#C9A84C] font-bold" x-text="filteredCount"></span> {{ __('messages.quran.of') }} {{ count($surahs) }} {{ __('messages.quran.surah') }}
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($surahs as $surah)
            <a href="{{ route('quran.show', $surah) }}"
               x-show="matchesSurah('{{ $surah->name_ar }}', '{{ $surah->name_en }}', '{{ $surah->revelation_type }}')"
               x-transition:enter="transition ease-out duration-300"
               x-transition:enter-start="opacity-0 scale-95"
               x-transition:enter-end="opacity-100 scale-100"
               class="block bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 hover:bg-white/10 transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg hover:shadow-[#1B5E20]/10 group">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#1B5E20] to-[#2E7D32] flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-[#1B5E20]/30">{{ $surah->number }}</div>
                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $surah->revelation_type === 'Meccan' ? 'bg-amber-500/20 text-amber-400 border border-amber-500/20' : 'bg-blue-500/20 text-blue-400 border border-blue-500/20' }}">
                        {{ $surah->revelation_type === 'Meccan' ? __('messages.quran.meccan') : __('messages.quran.medinan') }}
                    </span>
                </div>
                <h3 class="text-2xl font-bold text-[#C9A84C] mb-2 group-hover:text-[#FFD700] transition-colors duration-300" style="font-family: 'Amiri', serif;">{{ $surah->name_ar }}</h3>
                <p class="text-[#f8fafc]/60 mb-2">{{ $surah->name_en }}</p>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-[#f8fafc]/40">{{ $surah->total_ayahs }} {{ __('messages.quran.ayahs_count') }}</span>
                    <span class="text-[#1B5E20] group-hover:text-[#C9A84C] transition text-sm">{{ __('messages.quran.view_ayahs') }} &rarr;</span>
                </div>
            </a>
            @endforeach
        </div>

        <div x-show="filteredCount === 0" x-cloak class="text-center py-16">
            <div class="w-20 h-20 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-[#f8fafc]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <h3 class="text-2xl font-bold text-[#C9A84C] mb-2">{{ __('messages.quran.no_results') }}</h3>
            <p class="text-[#f8fafc]/60">{{ __('messages.quran.try_different') }}</p>
        </div>
    </div>

    @push('scripts')
    <script>
    function surahBrowser() {
        return {
            search: '',
            filter: '',
            filteredCount: {{ count($surahs) }},

            matchesSurah(nameAr, nameEn, revelationType) {
                const searchMatch = !this.search ||
                    nameAr.includes(this.search) ||
                    nameEn.toLowerCase().includes(this.search.toLowerCase());
                const filterMatch = !this.filter || revelationType === this.filter;
                return searchMatch && filterMatch;
            },

            init() {
                this.$watch('search', () => this.updateCount());
                this.$watch('filter', () => this.updateCount());
            },

            updateCount() {
                this.$nextTick(() => {
                    const visible = this.$el.querySelectorAll('.grid > a:not([style*="display: none"])');
                    this.filteredCount = visible.length;
                });
            }
        }
    }
    </script>
    @endpush
</x-app-layout>
