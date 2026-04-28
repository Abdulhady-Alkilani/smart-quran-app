<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold gradient-text" style="font-family: 'Amiri', serif;">الملف الشخصي</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- نموذج تعديل البيانات -->
        <div class="glass-card p-8 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#1B5E20] via-[#C9A84C] to-[#1B5E20]"></div>
            <h3 class="text-xl font-bold text-[#C9A84C] mb-6">تعديل البيانات الشخصية</h3>

            <form method="POST" action="{{ route('user.profile.update') }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label class="block text-[#C9A84C] mb-2 text-sm font-medium">الاسم</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-[#f8fafc] focus:outline-none focus:border-[#1B5E20] focus:ring-1 focus:ring-[#1B5E20] transition">
                        @error('name')<span class="text-red-400 text-sm mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-[#C9A84C] mb-2 text-sm font-medium">البريد الإلكتروني</label>
                        <input type="email" value="{{ $user->email }}" disabled class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-[#f8fafc]/40 cursor-not-allowed">
                        <p class="text-[#f8fafc]/30 text-xs mt-1">لا يمكن تعديل البريد الإلكتروني</p>
                    </div>

                    <div>
                        <label class="block text-[#C9A84C] mb-2 text-sm font-medium">الهاتف</label>
                        <input type="text" name="phone" value="{{ old('phone', $profile->phone ?? '') }}" placeholder="+966xxxxxxxxx" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-[#f8fafc] focus:outline-none focus:border-[#1B5E20] focus:ring-1 focus:ring-[#1B5E20] transition">
                    </div>

                    <div>
                        <label class="block text-[#C9A84C] mb-2 text-sm font-medium">الدولة</label>
                        <input type="text" name="country" value="{{ old('country', $profile->country ?? '') }}" placeholder="مثل: السعودية" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-[#f8fafc] focus:outline-none focus:border-[#1B5E20] focus:ring-1 focus:ring-[#1B5E20] transition">
                    </div>

                    <div>
                        <label class="block text-[#C9A84C] mb-2 text-sm font-medium">نبذة عنك</label>
                        <textarea name="bio" rows="3" placeholder="أخبرنا عن نفسك ورحلتك مع القرآن..." class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-[#f8fafc] focus:outline-none focus:border-[#1B5E20] focus:ring-1 focus:ring-[#1B5E20] transition">{{ old('bio', $profile->bio ?? '') }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-gradient-to-r from-[#1B5E20] to-[#2E7D32] hover:from-[#2E7D32] hover:to-[#388E3C] text-white px-8 py-3 rounded-xl transition-all duration-300 transform hover:scale-[1.02] shadow-lg shadow-[#1B5E20]/30">
                            💾 حفظ التغييرات
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- إحصائيات الحفظ -->
        <div class="glass-card p-8 mt-6 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#C9A84C] to-[#FFD700]"></div>
            <h3 class="text-xl font-bold text-[#C9A84C] mb-6">📊 إحصائيات الحفظ</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white/5 rounded-xl p-5 text-center border border-white/5 hover:border-green-500/20 transition">
                    <div class="text-3xl font-bold text-green-400 mb-1">{{ $user->memorizationProgress()->where('status', 'memorized')->count() }}</div>
                    <div class="text-[#f8fafc]/50 text-sm">آية محفوظة ✅</div>
                </div>
                <div class="bg-white/5 rounded-xl p-5 text-center border border-white/5 hover:border-yellow-500/20 transition">
                    <div class="text-3xl font-bold text-yellow-400 mb-1">{{ $user->memorizationProgress()->where('status', 'learning')->count() }}</div>
                    <div class="text-[#f8fafc]/50 text-sm">قيد التعلم 📚</div>
                </div>
                <div class="bg-white/5 rounded-xl p-5 text-center border border-white/5 hover:border-blue-500/20 transition">
                    <div class="text-3xl font-bold text-blue-400 mb-1">{{ $user->recitationAttempts()->count() }}</div>
                    <div class="text-[#f8fafc]/50 text-sm">محاولات التسميع 🎤</div>
                </div>
                <div class="bg-white/5 rounded-xl p-5 text-center border border-white/5 hover:border-[#C9A84C]/20 transition">
                    @php
                        $total = $user->recitationAttempts()->count();
                        $passed = $user->recitationAttempts()->where('is_passed', true)->count();
                        $rate = $total > 0 ? round(($passed / $total) * 100) : 0;
                    @endphp
                    <div class="text-3xl font-bold text-[#C9A84C] mb-1">{{ $rate }}%</div>
                    <div class="text-[#f8fafc]/50 text-sm">نسبة النجاح 🏆</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
