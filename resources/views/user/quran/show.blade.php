<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold gradient-text" style="font-family: 'Amiri', serif;">سورة {{ $surah->name_ar }}</h2>
            <a href="{{ route('quiz.show', $surah) }}" class="bg-[#C9A84C]/20 text-[#C9A84C] hover:bg-[#C9A84C]/30 px-4 py-2 rounded-lg transition text-sm border border-[#C9A84C]/30">📝 اختبار السورة</a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- معلومات السورة -->
        <div class="glass-card p-6 mb-8 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#1B5E20] via-[#C9A84C] to-[#1B5E20]"></div>
            <div class="flex justify-between items-center flex-wrap gap-4">
                <div>
                    <h3 class="text-3xl font-bold text-[#C9A84C]" style="font-family: 'Amiri', serif;">{{ $surah->name_ar }}</h3>
                    <p class="text-[#f8fafc]/60">{{ $surah->name_en }}</p>
                </div>
                <div class="flex gap-3">
                    <span class="px-4 py-2 rounded-full text-sm font-medium {{ $surah->revelation_type === 'Meccan' ? 'bg-amber-500/20 text-amber-400 border border-amber-500/20' : 'bg-blue-500/20 text-blue-400 border border-blue-500/20' }}">
                        {{ $surah->revelation_type === 'Meccan' ? 'مكية' : 'مدنية' }}
                    </span>
                    <span class="px-4 py-2 rounded-full bg-[#1B5E20]/20 text-[#C9A84C] text-sm border border-[#1B5E20]/20">{{ $surah->total_ayahs }} آية</span>
                </div>
            </div>
        </div>

        <!-- تنقل بين السور -->
        <div class="flex justify-between items-center mb-6">
            @if($surah->number > 1)
            <a href="{{ route('quran.show', $surah->number - 1) }}" class="bg-white/5 hover:bg-white/10 border border-white/10 text-[#f8fafc]/70 hover:text-[#C9A84C] px-4 py-2 rounded-xl transition flex items-center gap-2">
                → السورة السابقة
            </a>
            @else
            <div></div>
            @endif

            <a href="{{ route('quran.index') }}" class="text-[#C9A84C] hover:text-[#FFD700] transition text-sm">📖 كل السور</a>

            @if($surah->number < 114)
            <a href="{{ route('quran.show', $surah->number + 1) }}" class="bg-white/5 hover:bg-white/10 border border-white/10 text-[#f8fafc]/70 hover:text-[#C9A84C] px-4 py-2 rounded-xl transition flex items-center gap-2">
                السورة التالية ←
            </a>
            @else
            <div></div>
            @endif
        </div>

        <!-- بسملة -->
        @if($surah->number != 9 && $surah->number != 1)
        <div class="text-center mb-8">
            <span class="text-2xl text-[#C9A84C]/70" style="font-family: 'Noto Naskh Arabic', serif;">بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيمِ</span>
        </div>
        @endif

        <!-- الآيات -->
        <div class="space-y-4">
            @foreach($ayahs as $ayah)
            <div class="glass-card p-6 group" id="ayah-{{ $ayah->number_in_surah }}">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-full bg-[#1B5E20]/20 flex items-center justify-center text-[#C9A84C] text-sm font-bold border border-[#1B5E20]/30">{{ $ayah->number_in_surah }}</span>
                        @php $status = $ayah->memorizationProgress->first()?->status ?? 'new'; @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $status === 'memorized' ? 'bg-green-500/20 text-green-400 border border-green-500/20' : ($status === 'learning' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/20' : 'bg-white/5 text-[#f8fafc]/40 border border-white/10') }}">
                            {{ $status === 'memorized' ? '✅ محفوظة' : ($status === 'learning' ? '📚 قيد التعلم' : '🆕 جديدة') }}
                        </span>
                    </div>
                    @if($ayah->audio_url)
                    <button onclick="playAudio('{{ $ayah->audio_url }}')" class="text-[#C9A84C] hover:text-[#FFD700] transition text-2xl opacity-60 group-hover:opacity-100">🔊</button>
                    @endif
                </div>

                <div class="text-3xl leading-[2.5] mb-6 text-right" style="font-family: 'Noto Naskh Arabic', serif;">{{ $ayah->text_uthmani }}</div>

                <div class="flex justify-end items-center gap-3 opacity-60 group-hover:opacity-100 transition">
                    @if($status !== 'memorized')
                    <form method="POST" action="{{ route('quran.start', $ayah) }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-[#C9A84C]/20 hover:bg-[#C9A84C]/30 text-[#C9A84C] px-4 py-2 rounded-lg transition text-sm border border-[#C9A84C]/20">📌 ابدأ الحفظ</button>
                    </form>
                    @endif
                    <a href="{{ route('recitation.create', $ayah) }}" class="bg-[#1B5E20] hover:bg-[#2E7D32] text-white px-4 py-2 rounded-lg transition text-sm">🎤 سمّع</a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- تنقل بين السور (أسفل) -->
        <div class="flex justify-between items-center mt-8 pt-8 border-t border-white/10">
            @if($surah->number > 1)
            <a href="{{ route('quran.show', $surah->number - 1) }}" class="bg-white/5 hover:bg-white/10 border border-white/10 text-[#f8fafc]/70 hover:text-[#C9A84C] px-6 py-3 rounded-xl transition">→ السورة السابقة</a>
            @else
            <div></div>
            @endif

            @if($surah->number < 114)
            <a href="{{ route('quran.show', $surah->number + 1) }}" class="bg-white/5 hover:bg-white/10 border border-white/10 text-[#f8fafc]/70 hover:text-[#C9A84C] px-6 py-3 rounded-xl transition">السورة التالية ←</a>
            @else
            <div></div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
    let currentAudio = null;
    function playAudio(url) {
        if (currentAudio) { currentAudio.pause(); currentAudio = null; }
        currentAudio = new Audio(url);
        currentAudio.play();
    }
    </script>
    @endpush
</x-app-layout>
