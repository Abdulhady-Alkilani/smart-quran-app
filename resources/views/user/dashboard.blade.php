<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold gradient-text" style="font-family: 'Amiri', serif;">لوحة التحكم</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- بطاقات الإحصائيات -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="glass-card p-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#1B5E20] to-[#2E7D32]"></div>
                <div class="text-4xl font-bold text-white mb-2">{{ $memorizedCount }}</div>
                <div class="text-[#f8fafc]/60 text-sm">آية محفوظة</div>
                <div class="absolute bottom-4 left-4 text-4xl opacity-10">📖</div>
            </div>
            <div class="glass-card p-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#C9A84C] to-[#D4AF37]"></div>
                @php $progressPct = min(100, round($memorizedCount / 62.36)); @endphp
                <div class="text-4xl font-bold text-white mb-2">{{ $progressPct }}%</div>
                <div class="text-[#f8fafc]/60 text-sm mb-3">نسبة التقدم</div>
                <div class="w-full bg-white/10 rounded-full h-2">
                    <div class="bg-gradient-to-r from-[#C9A84C] to-[#FFD700] rounded-full h-2 transition-all duration-1000" style="width: {{ $progressPct }}%"></div>
                </div>
                <div class="absolute bottom-4 left-4 text-4xl opacity-10">📊</div>
            </div>
            <div class="glass-card p-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#1E3A8A] to-[#3B82F6]"></div>
                <div class="text-4xl font-bold text-white mb-2">{{ $dueReviews->count() }}</div>
                <div class="text-[#f8fafc]/60 text-sm">مراجعة مستحقة اليوم</div>
                <div class="absolute bottom-4 left-4 text-4xl opacity-10">🔄</div>
            </div>
            <div class="glass-card p-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-purple-600 to-purple-400"></div>
                <div class="text-4xl font-bold text-white mb-2">{{ $recentAttempts->where('is_passed', true)->count() }}/{{ $recentAttempts->count() }}</div>
                <div class="text-[#f8fafc]/60 text-sm">تسميع ناجح (آخر 5)</div>
                <div class="absolute bottom-4 left-4 text-4xl opacity-10">🎤</div>
            </div>
        </div>

        <!-- إجراءات سريعة -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <a href="{{ route('quran.index') }}" class="glass-card p-5 flex items-center gap-4 group">
                <div class="w-12 h-12 rounded-xl bg-[#1B5E20]/20 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">📖</div>
                <div>
                    <div class="font-bold text-[#C9A84C]">تصفح السور</div>
                    <div class="text-[#f8fafc]/40 text-sm">ابدأ حفظ آيات جديدة</div>
                </div>
            </a>
            <a href="{{ route('reviews.index') }}" class="glass-card p-5 flex items-center gap-4 group">
                <div class="w-12 h-12 rounded-xl bg-[#C9A84C]/20 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">🔄</div>
                <div>
                    <div class="font-bold text-[#C9A84C]">المراجعة اليومية</div>
                    <div class="text-[#f8fafc]/40 text-sm">{{ $dueReviews->count() }} آية مستحقة</div>
                </div>
            </a>
            <a href="{{ route('user.profile.edit') }}" class="glass-card p-5 flex items-center gap-4 group">
                <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">👤</div>
                <div>
                    <div class="font-bold text-[#C9A84C]">الملف الشخصي</div>
                    <div class="text-[#f8fafc]/40 text-sm">عرض الإحصائيات</div>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- الآيات المستحقة للمراجعة -->
            <div>
                @if($dueReviews->count() > 0)
                <div class="glass-card p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-[#C9A84C]">📝 الآيات المستحقة للمراجعة</h3>
                        <span class="bg-red-500/20 text-red-400 px-3 py-1 rounded-full text-sm font-bold">{{ $dueReviews->count() }}</span>
                    </div>
                    <div class="space-y-3">
                        @foreach($dueReviews->take(5) as $item)
                        <div class="bg-white/5 rounded-xl p-4 flex justify-between items-center hover:bg-white/10 transition group">
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-lg mb-1 truncate" style="font-family: 'Noto Naskh Arabic', serif;">{{ Str::limit($item->ayah->text_uthmani, 60) }}</div>
                                <div class="text-sm text-[#f8fafc]/50">سورة {{ $item->ayah->surah->name_ar }} - آية {{ $item->ayah->number_in_surah }}</div>
                            </div>
                            <a href="{{ route('recitation.create', $item->ayah) }}" class="bg-[#1B5E20] hover:bg-[#2E7D32] text-white px-4 py-2 rounded-lg transition flex-shrink-0 mr-4 opacity-80 group-hover:opacity-100">سمّع</a>
                        </div>
                        @endforeach
                    </div>
                    @if($dueReviews->count() > 5)
                    <a href="{{ route('reviews.index') }}" class="block text-center text-[#C9A84C] hover:text-[#FFD700] mt-4 transition">عرض الكل ({{ $dueReviews->count() }}) ←</a>
                    @endif
                </div>
                @else
                <div class="glass-card p-8 text-center">
                    <div class="text-5xl mb-4">✅</div>
                    <h3 class="text-xl font-bold text-[#C9A84C] mb-2">لا مراجعات مستحقة اليوم</h3>
                    <p class="text-[#f8fafc]/50">أحسنت! اذهب لحفظ آيات جديدة</p>
                    <a href="{{ route('quran.index') }}" class="inline-block mt-4 bg-[#1B5E20] hover:bg-[#2E7D32] text-white px-6 py-2 rounded-lg transition">تصفح السور</a>
                </div>
                @endif
            </div>

            <!-- أحدث المحاولات -->
            <div>
                @if($recentAttempts->count() > 0)
                <div class="glass-card p-6">
                    <h3 class="text-xl font-bold text-[#C9A84C] mb-4">📊 أحدث المحاولات</h3>
                    <div class="space-y-3">
                        @foreach($recentAttempts as $attempt)
                        <div class="bg-white/5 rounded-xl p-4 flex justify-between items-center">
                            <div>
                                <div class="font-bold">{{ $attempt->ayah->surah->name_ar }} - آية {{ $attempt->ayah->number_in_surah }}</div>
                                <div class="text-sm text-[#f8fafc]/40">{{ $attempt->created_at->diffForHumans() }}</div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="text-left">
                                    <span class="text-lg font-bold {{ $attempt->is_passed ? 'text-green-400' : 'text-red-400' }}">{{ number_format($attempt->similarity_score, 1) }}%</span>
                                </div>
                                <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm {{ $attempt->is_passed ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                                    {{ $attempt->is_passed ? '✓' : '✗' }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="glass-card p-8 text-center">
                    <div class="text-5xl mb-4">🎤</div>
                    <h3 class="text-xl font-bold text-[#C9A84C] mb-2">لم تجرِ أي محاولات بعد</h3>
                    <p class="text-[#f8fafc]/50">ابدأ بحفظ آية ثم سمّعها</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
