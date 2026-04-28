<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="المنصة الذكية لحفظ القرآن الكريم ومتابعته باستخدام الذكاء الاصطناعي ونظام التكرار المتباعد">
    <title>المنصة الذكية لحفظ القرآن الكريم</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Noto+Naskh+Arabic:wght@400;500;600;700&family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-[#0F172A] text-[#f8fafc]" style="font-family: 'Tajawal', sans-serif;">
    <div class="min-h-screen islamic-pattern">
        <!-- Navigation -->
        <nav class="bg-[#0F172A]/80 backdrop-blur-xl border-b border-[#1B5E20]/20 fixed w-full z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <span class="text-2xl font-bold gradient-text" style="font-family: 'Amiri', serif;">القرآن الذكي</span>
                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-[#f8fafc]/80 hover:text-[#C9A84C] transition-colors duration-200">لوحة التحكم</a>
                        @else
                            <a href="{{ route('login') }}" class="text-[#f8fafc]/80 hover:text-[#C9A84C] transition-colors duration-200">تسجيل الدخول</a>
                            <a href="{{ route('register') }}" class="bg-gradient-to-r from-[#1B5E20] to-[#2E7D32] hover:from-[#2E7D32] hover:to-[#388E3C] text-white px-5 py-2 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg shadow-[#1B5E20]/30">إنشاء حساب</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="pt-32 pb-20 px-4 relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute top-20 left-10 w-72 h-72 bg-[#1B5E20]/10 rounded-full blur-3xl animate-float"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-[#C9A84C]/5 rounded-full blur-3xl animate-float stagger-2"></div>

            <div class="max-w-4xl mx-auto text-center relative z-10">
                <!-- بسملة -->
                <div class="mb-8 animate-fade-in-up">
                    <span class="text-3xl text-[#C9A84C]/60" style="font-family: 'Noto Naskh Arabic', serif;">بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيمِ</span>
                </div>

                <h1 class="text-5xl md:text-7xl font-bold mb-6 animate-fade-in-up stagger-1" style="font-family: 'Amiri', serif;">
                    <span class="gradient-text">المنصة الذكية</span>
                    <br>
                    <span class="text-[#f8fafc]">لحفظ القرآن الكريم</span>
                </h1>

                <p class="text-xl text-[#f8fafc]/70 mb-10 max-w-2xl mx-auto leading-relaxed animate-fade-in-up stagger-2">
                    منصة متكاملة تجمع بين <span class="text-[#C9A84C] font-bold">الذكاء الاصطناعي</span> ونظام <span class="text-[#C9A84C] font-bold">التكرار المتباعد</span> لمساعدتك على حفظ ومراجعة القرآن الكريم بسهولة وفعالية
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up stagger-3">
                    <a href="{{ route('register') }}" class="inline-block bg-gradient-to-r from-[#1B5E20] to-[#2E7D32] text-white text-lg px-10 py-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-xl shadow-[#1B5E20]/30 animate-pulse-glow">
                        ابدأ رحلة الحفظ 🚀
                    </a>
                    <a href="#features" class="inline-block bg-white/5 backdrop-blur-md border border-white/10 text-[#f8fafc] text-lg px-10 py-4 rounded-xl transition-all duration-300 hover:bg-white/10 hover:border-[#C9A84C]/30">
                        تعرّف على المنصة
                    </a>
                </div>
            </div>
        </section>

        <!-- Stats -->
        <section class="py-12 px-4">
            <div class="max-w-4xl mx-auto">
                <div class="grid grid-cols-3 gap-4">
                    <div class="glass-card p-6 text-center">
                        <div class="text-4xl font-bold text-[#C9A84C] mb-1">114</div>
                        <div class="text-[#f8fafc]/50 text-sm">سورة</div>
                    </div>
                    <div class="glass-card p-6 text-center">
                        <div class="text-4xl font-bold text-[#C9A84C] mb-1">6,236</div>
                        <div class="text-[#f8fafc]/50 text-sm">آية</div>
                    </div>
                    <div class="glass-card p-6 text-center">
                        <div class="text-4xl font-bold text-[#C9A84C] mb-1">30</div>
                        <div class="text-[#f8fafc]/50 text-sm">جزء</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section id="features" class="py-20 px-4">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-4xl font-bold text-center mb-4 gradient-text" style="font-family: 'Amiri', serif;">ميزات المنصة</h2>
                <p class="text-center text-[#f8fafc]/50 mb-12 max-w-xl mx-auto">أدوات متقدمة مصممة خصيصاً لمساعدتك في رحلة حفظ كتاب الله</p>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="glass-card p-8 group">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#1B5E20] to-[#2E7D32] flex items-center justify-center text-3xl mb-6 shadow-lg shadow-[#1B5E20]/30 group-hover:scale-110 transition-transform duration-300">🎤</div>
                        <h3 class="text-2xl font-bold mb-4 text-[#C9A84C]">التسميع الذكي</h3>
                        <p class="text-[#f8fafc]/60 leading-relaxed">سجّل تلاوتك واحصل على تقييم فوري بالذكاء الاصطناعي مع نسبة التطابق وعدد الأخطاء</p>
                    </div>
                    <div class="glass-card p-8 group">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#C9A84C] to-[#D4AF37] flex items-center justify-center text-3xl mb-6 shadow-lg shadow-[#C9A84C]/30 group-hover:scale-110 transition-transform duration-300">🔄</div>
                        <h3 class="text-2xl font-bold mb-4 text-[#C9A84C]">التكرار المتباعد</h3>
                        <p class="text-[#f8fafc]/60 leading-relaxed">نظام SuperMemo-2 يُجدول مراجعاتك تلقائياً لحفظ طويل الأمد وثابت في الذاكرة</p>
                    </div>
                    <div class="glass-card p-8 group">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#1E3A8A] to-[#3B82F6] flex items-center justify-center text-3xl mb-6 shadow-lg shadow-[#1E3A8A]/30 group-hover:scale-110 transition-transform duration-300">📝</div>
                        <h3 class="text-2xl font-bold mb-4 text-[#C9A84C]">اختبارات تلقائية</h3>
                        <p class="text-[#f8fafc]/60 leading-relaxed">أسئلة اختيار من متعدد مولّدة تلقائياً بالذكاء الاصطناعي لتعزيز حفظك</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How it Works -->
        <section class="py-20 px-4 bg-white/[0.02]">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-4xl font-bold text-center mb-12 gradient-text" style="font-family: 'Amiri', serif;">كيف تعمل المنصة؟</h2>
                <div class="space-y-8">
                    <div class="flex gap-6 items-start">
                        <div class="w-12 h-12 rounded-full bg-[#1B5E20] flex items-center justify-center text-white font-bold text-lg flex-shrink-0 shadow-lg">1</div>
                        <div>
                            <h3 class="text-xl font-bold text-[#C9A84C] mb-2">تصفّح سور القرآن</h3>
                            <p class="text-[#f8fafc]/60">اختر السورة والآية التي تريد حفظها من مكتبة شاملة تضم 114 سورة</p>
                        </div>
                    </div>
                    <div class="flex gap-6 items-start">
                        <div class="w-12 h-12 rounded-full bg-[#C9A84C] flex items-center justify-center text-[#0F172A] font-bold text-lg flex-shrink-0 shadow-lg">2</div>
                        <div>
                            <h3 class="text-xl font-bold text-[#C9A84C] mb-2">سجّل تلاوتك</h3>
                            <p class="text-[#f8fafc]/60">استخدم الميكروفون لتسجيل تلاوتك مباشرة من المتصفح أو ارفع ملفاً صوتياً</p>
                        </div>
                    </div>
                    <div class="flex gap-6 items-start">
                        <div class="w-12 h-12 rounded-full bg-[#1B5E20] flex items-center justify-center text-white font-bold text-lg flex-shrink-0 shadow-lg">3</div>
                        <div>
                            <h3 class="text-xl font-bold text-[#C9A84C] mb-2">احصل على التقييم</h3>
                            <p class="text-[#f8fafc]/60">الذكاء الاصطناعي يقيّم تلاوتك ويحدد نسبة التطابق وينظّم جدول مراجعاتك تلقائياً</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="py-20 px-4">
            <div class="max-w-3xl mx-auto text-center glass-card p-12">
                <h2 class="text-3xl font-bold mb-4" style="font-family: 'Amiri', serif;">
                    <span class="gradient-text">ابدأ رحلتك اليوم</span>
                </h2>
                <p class="text-[#f8fafc]/60 mb-8 text-lg">انضم إلى منصة القرآن الذكي واحفظ كتاب الله بطريقة علمية ومنهجية</p>
                <a href="{{ route('register') }}" class="inline-block bg-gradient-to-r from-[#1B5E20] to-[#2E7D32] text-white text-lg px-10 py-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-xl shadow-[#1B5E20]/30">
                    سجّل مجاناً الآن
                </a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-8 border-t border-[#1B5E20]/20">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-[#f8fafc]/40 text-sm">&copy; {{ date('Y') }} المنصة الذكية لحفظ القرآن الكريم</div>
                    <div class="flex gap-6 text-[#f8fafc]/40 text-sm">
                        <span>Laravel 12</span>
                        <span>•</span>
                        <span>FilamentPHP</span>
                        <span>•</span>
                        <span>AI Powered</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
