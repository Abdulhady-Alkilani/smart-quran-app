<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold gradient-text" style="font-family: 'Amiri', serif;">المراجعة اليومية</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- إحصائية -->
        <div class="glass-card p-8 mb-8 text-center relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#1B5E20] via-[#C9A84C] to-[#1B5E20]"></div>
            <div class="text-6xl font-bold text-[#C9A84C] mb-2">{{ $totalDue }}</div>
            <div class="text-[#f8fafc]/60 text-lg">آية مستحقة للمراجعة اليوم</div>
            @if($totalDue > 0)
            <div class="mt-4 w-48 mx-auto bg-white/10 rounded-full h-2">
                <div class="bg-gradient-to-r from-[#C9A84C] to-[#FFD700] h-2 rounded-full" style="width: 100%"></div>
            </div>
            <p class="text-[#f8fafc]/40 text-sm mt-2">ابدأ المراجعة للحفاظ على حفظك</p>
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
                            <span class="text-[#C9A84C] font-medium">سورة {{ $item->ayah->surah->name_ar }}</span>
                            <span class="text-[#f8fafc]/40 text-sm"> — آية {{ $item->ayah->number_in_surah }}</span>
                        </div>
                    </div>
                    @if($item->last_review_date)
                    <span class="text-[#f8fafc]/30 text-xs">آخر مراجعة: {{ $item->last_review_date->diffForHumans() }}</span>
                    @endif
                </div>
                <div class="text-2xl leading-loose mb-4 text-right" style="font-family: 'Noto Naskh Arabic', serif;">{{ $item->ayah->text_uthmani }}</div>
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 rounded-full text-xs {{ $item->status === 'memorized' ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400' }}">
                            {{ $item->status === 'memorized' ? 'محفوظة' : 'قيد التعلم' }}
                        </span>
                        <span class="text-[#f8fafc]/30 text-xs">تكرار: {{ $item->repetition_count ?? 0 }}</span>
                    </div>
                    <a href="{{ route('recitation.create', $item->ayah) }}" class="bg-[#1B5E20] hover:bg-[#2E7D32] text-white px-6 py-2 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg shadow-[#1B5E20]/20 opacity-80 group-hover:opacity-100">
                        🎤 سمّع الآن
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="glass-card p-12 text-center">
            <div class="text-7xl mb-6">✅</div>
            <h3 class="text-3xl font-bold text-[#C9A84C] mb-3" style="font-family: 'Amiri', serif;">ما شاء الله!</h3>
            <p class="text-[#f8fafc]/60 text-lg mb-6">لا توجد مراجعات مستحقة اليوم. أنهيت جميع مراجعاتك!</p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('quran.index') }}" class="bg-[#1B5E20] hover:bg-[#2E7D32] text-white px-6 py-3 rounded-xl transition">📖 احفظ آيات جديدة</a>
                <a href="{{ route('dashboard') }}" class="bg-white/5 hover:bg-white/10 border border-white/10 text-[#f8fafc] px-6 py-3 rounded-xl transition">العودة للوحة التحكم</a>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
