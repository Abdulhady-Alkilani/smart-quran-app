<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold" style="font-family: 'Amiri', serif;">سور القرآن الكريم</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="surahBrowser()">
        <!-- البحث والفلترة -->
        <div class="mb-8 flex flex-col sm:flex-row gap-4">
            <div class="relative flex-1">
                <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-[#f8fafc]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" x-model="search" placeholder="ابحث عن سورة بالعربية أو الإنجليزية..."
                       class="w-full bg-white/5 border border-white/10 rounded-xl pr-12 pl-4 py-3 text-[#f8fafc] placeholder-[#f8fafc]/40 focus:outline-none focus:border-[#1B5E20] focus:ring-1 focus:ring-[#1B5E20] transition">
            </div>
            <select x-model="filter"
                    class="bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-[#f8fafc] focus:outline-none focus:border-[#1B5E20] focus:ring-1 focus:ring-[#1B5E20] transition">
                <option value="">الكل</option>
                <option value="Meccan">مكية</option>
                <option value="Medinan">مدنية</option>
            </select>
        </div>

        <!-- عداد النتائج -->
        <div class="mb-4 text-[#f8fafc]/50 text-sm">
            عرض <span class="text-[#C9A84C] font-bold" x-text="filteredCount"></span> من أصل {{ count($surahs) }} سورة
        </div>

        <!-- شبكة السور -->
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
                        {{ $surah->revelation_type === 'Meccan' ? 'مكية' : 'مدنية' }}
                    </span>
                </div>
                <h3 class="text-2xl font-bold text-[#C9A84C] mb-2 group-hover:text-[#FFD700] transition-colors duration-300" style="font-family: 'Amiri', serif;">{{ $surah->name_ar }}</h3>
                <p class="text-[#f8fafc]/60 mb-2">{{ $surah->name_en }}</p>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-[#f8fafc]/40">{{ $surah->total_ayahs }} آية</span>
                    <span class="text-[#1B5E20] group-hover:text-[#C9A84C] transition text-sm">عرض الآيات ←</span>
                </div>
            </a>
            @endforeach
        </div>

        <!-- لا نتائج -->
        <div x-show="filteredCount === 0" x-cloak class="text-center py-16">
            <div class="text-6xl mb-4">🔍</div>
            <h3 class="text-2xl font-bold text-[#C9A84C] mb-2">لا توجد نتائج</h3>
            <p class="text-[#f8fafc]/60">جرّب البحث بكلمة مختلفة أو أزل الفلتر</p>
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
