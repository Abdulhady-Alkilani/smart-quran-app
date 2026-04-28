<nav x-data="{ open: false }" class="bg-[#0F172A]/95 backdrop-blur-xl border-b border-[#1B5E20]/30 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo & Links -->
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <span class="text-2xl font-bold text-[#C9A84C]" style="font-family: 'Amiri', serif;">القرآن الذكي</span>
                </a>

                <!-- Desktop Nav -->
                <div class="hidden sm:flex items-center gap-1">
                    <a href="{{ route('dashboard') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('dashboard') ? 'bg-[#1B5E20] text-white' : 'text-[#f8fafc]/70 hover:text-[#C9A84C] hover:bg-white/5' }}">
                        لوحة التحكم
                    </a>
                    <a href="{{ route('quran.index') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('quran.*') ? 'bg-[#1B5E20] text-white' : 'text-[#f8fafc]/70 hover:text-[#C9A84C] hover:bg-white/5' }}">
                        سور القرآن
                    </a>
                    <a href="{{ route('reviews.index') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('reviews.*') ? 'bg-[#1B5E20] text-white' : 'text-[#f8fafc]/70 hover:text-[#C9A84C] hover:bg-white/5' }}">
                        المراجعة اليومية
                    </a>
                    <a href="{{ route('user.profile.edit') }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('user.profile.*') ? 'bg-[#1B5E20] text-white' : 'text-[#f8fafc]/70 hover:text-[#C9A84C] hover:bg-white/5' }}">
                        الملف الشخصي
                    </a>
                </div>
            </div>

            <!-- User Menu (Desktop) -->
            <div class="hidden sm:flex items-center gap-4">
                <span class="text-[#f8fafc]/70 text-sm">أهلاً، {{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-red-400 hover:text-red-300 transition px-3 py-2 rounded-lg hover:bg-white/5">
                        تسجيل الخروج
                    </button>
                </form>
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="flex items-center sm:hidden">
                <button @click="open = !open" class="text-[#f8fafc]/70 hover:text-[#C9A84C] transition p-2 rounded-lg hover:bg-white/5">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-2"
         class="sm:hidden bg-[#0F172A]/98 backdrop-blur-xl border-t border-[#1B5E20]/20">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="block px-4 py-3 rounded-xl text-sm {{ request()->routeIs('dashboard') ? 'bg-[#1B5E20] text-white' : 'text-[#f8fafc]/70 hover:bg-white/5' }}">
                📊 لوحة التحكم
            </a>
            <a href="{{ route('quran.index') }}" class="block px-4 py-3 rounded-xl text-sm {{ request()->routeIs('quran.*') ? 'bg-[#1B5E20] text-white' : 'text-[#f8fafc]/70 hover:bg-white/5' }}">
                📖 سور القرآن
            </a>
            <a href="{{ route('reviews.index') }}" class="block px-4 py-3 rounded-xl text-sm {{ request()->routeIs('reviews.*') ? 'bg-[#1B5E20] text-white' : 'text-[#f8fafc]/70 hover:bg-white/5' }}">
                🔄 المراجعة اليومية
            </a>
            <a href="{{ route('user.profile.edit') }}" class="block px-4 py-3 rounded-xl text-sm {{ request()->routeIs('user.profile.*') ? 'bg-[#1B5E20] text-white' : 'text-[#f8fafc]/70 hover:bg-white/5' }}">
                👤 الملف الشخصي
            </a>

            <div class="border-t border-[#1B5E20]/20 pt-3 mt-3">
                <div class="px-4 py-2">
                    <div class="text-sm font-medium text-[#C9A84C]">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-[#f8fafc]/40">{{ Auth::user()->email }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-right px-4 py-3 rounded-xl text-sm text-red-400 hover:bg-white/5">
                        🚪 تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
