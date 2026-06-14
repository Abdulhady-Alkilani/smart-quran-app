<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold gradient-text" style="font-family: 'Amiri', serif;">تسميع سورة كاملة غيبياً</h2>
            <a href="{{ route('hifz.index') }}" class="flex items-center gap-2 bg-white/5 hover:bg-white/10 border border-white/10 text-[#f8fafc]/70 hover:text-[#C9A84C] px-4 py-2 rounded-xl transition text-sm">
                <svg class="w-4 h-4 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                العودة للتسميع الغيبي
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ 
        search: '',
        normalize(text) {
            if (!text) return '';
            return text.replace(/[\u064B-\u065F\u0670]/g, '')
                       .replace(/[أإآٱ]/g, 'ا')
                       .replace(/ة/g, 'ه')
                       .replace('سوره ', '')
                       .replace('سورة ', '')
                       .toLowerCase()
                       .trim();
        }
    }">
        <div class="glass-card p-6 mb-8 text-center relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#C9A84C] via-[#FFD700] to-[#C9A84C]"></div>
            <h3 class="text-2xl font-bold text-[#C9A84C] mb-2" style="font-family: 'Amiri', serif;">اختر سورة للتسميع</h3>
            <p class="text-[#f8fafc]/60 text-sm mb-6">اختر السورة التي تود تسميعها غيبياً وبشكل كامل. لن يتم عرض آيات السورة، وسيتم تقييم تلاوتك مباشرة.</p>
            
            <div class="max-w-md mx-auto relative flex items-center">
                <input type="text" x-model="search" placeholder="ابحث عن اسم السورة (عربي / انجليزي)..." class="w-full bg-white/5 border border-white/10 rounded-xl pr-12 pl-4 py-3 text-[#f8fafc] focus:outline-none focus:border-[#C9A84C] focus:ring-1 focus:ring-[#C9A84C] transition-colors placeholder-[#f8fafc]/30" dir="auto">
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-[#f8fafc]/40">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($surahs as $surah)
            <a href="{{ route('hifz.surah.recite', $surah) }}" x-show="search.trim() === '' || normalize('{{ $surah->name_ar }}').includes(normalize(search)) || normalize('{{ addslashes($surah->name_en) }}').includes(normalize(search)) || '{{ $surah->number }}' === search.trim()" class="glass-card p-5 group hover:border-[#C9A84C]/40 transition-all duration-300 flex items-center justify-between" x-transition>
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-[#C9A84C]/10 flex items-center justify-center text-[#C9A84C] font-bold border border-[#C9A84C]/20 group-hover:bg-[#C9A84C] group-hover:text-[#0F172A] transition-colors">
                        {{ $surah->number }}
                    </div>
                    <div>
                        <div class="font-bold text-[#f8fafc] text-lg" style="font-family: 'Amiri', serif;">{{ $surah->name_ar }}</div>
                        <div class="flex items-center gap-2 text-[#f8fafc]/50 text-xs mt-1">
                            <span dir="ltr">{{ $surah->name_en }}</span>
                            <span class="text-[#f8fafc]/20">•</span>
                            <span>{{ $surah->total_ayahs }} آيات</span>
                        </div>
                    </div>
                </div>
                <svg class="w-5 h-5 text-[#f8fafc]/30 group-hover:text-[#C9A84C] group-hover:-translate-x-1 transition-transform transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            @endforeach
        </div>
    </div>
</x-app-layout>
