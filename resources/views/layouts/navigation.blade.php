@php
    $locale = app()->getLocale();
    $isRtl = $locale === 'ar';
@endphp
<nav x-data="{ open: false }" class="bg-[#0F172A]/95 backdrop-blur-xl border-b border-[#1B5E20]/30 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-2 xl:gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2 flex-shrink-0">
                    <x-application-logo class="w-8 h-8" />
                    <span class="text-lg xl:text-xl font-bold text-[#C9A84C] whitespace-nowrap hidden lg:block overflow-hidden text-ellipsis max-w-[150px] xl:max-w-[250px] 2xl:max-w-none" style="font-family: 'Amiri', serif;">{{ __('messages.app_name') }}</span>
                </a>

                <div class="hidden lg:flex items-center gap-1">
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-1.5 px-2 py-2 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                              {{ request()->routeIs('dashboard') ? 'text-[#C9A84C] bg-white/5 shadow-[inset_0_-2px_0_0_#C9A84C]' : 'text-[#f8fafc]/70 hover:text-[#C9A84C] hover:bg-white/5' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        {{ __('messages.nav.dashboard') }}
                    </a>
                    <a href="{{ route('quran.index') }}"
                       class="flex items-center gap-1.5 px-2 py-2 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                              {{ request()->routeIs('quran.*') ? 'text-[#C9A84C] bg-white/5 shadow-[inset_0_-2px_0_0_#C9A84C]' : 'text-[#f8fafc]/70 hover:text-[#C9A84C] hover:bg-white/5' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        {{ __('messages.nav.quran') }}
                    </a>
                    <a href="{{ route('reviews.index') }}"
                       class="flex items-center gap-1.5 px-2 py-2 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                              {{ request()->routeIs('reviews.*') ? 'text-[#C9A84C] bg-white/5 shadow-[inset_0_-2px_0_0_#C9A84C]' : 'text-[#f8fafc]/70 hover:text-[#C9A84C] hover:bg-white/5' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        {{ __('messages.nav.reviews') }}
                    </a>
                    <a href="{{ route('hifz.index') }}"
                       class="flex items-center gap-1.5 px-2 py-2 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                              {{ request()->routeIs('hifz.*') ? 'text-purple-400 bg-white/5 shadow-[inset_0_-2px_0_0_#a78bfa]' : 'text-[#f8fafc]/70 hover:text-purple-400 hover:bg-white/5' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                        {{ __('messages.nav.hifz') }}
                    </a>
                    <a href="{{ route('user.profile.edit') }}"
                       class="flex items-center gap-1.5 px-2 py-2 rounded-lg text-sm font-medium transition-all duration-200 whitespace-nowrap
                              {{ request()->routeIs('user.profile.*') ? 'text-[#C9A84C] bg-white/5 shadow-[inset_0_-2px_0_0_#C9A84C]' : 'text-[#f8fafc]/70 hover:text-[#C9A84C] hover:bg-white/5' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        {{ __('messages.nav.profile') }}
                    </a>

                    @if(Auth::user() && Auth::user()->roles()->where('name', 'admin')->exists())
                        <span class="w-px h-6 bg-[#1B5E20]/40 mx-1"></span>
                        <a href="{{ url('/admin') }}"
                           class="flex items-center gap-1.5 px-2 py-2 rounded-lg text-sm font-medium transition-all duration-200 text-[#C9A84C] hover:bg-[#C9A84C]/10 border border-[#C9A84C]/30 hover:border-[#C9A84C]/50 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            لوحة الإدارة
                        </a>
                    @endif
                </div>
            </div>

            <div class="hidden lg:flex items-center gap-2 flex-shrink-0">
                <div class="flex items-center gap-2">
                    <div x-data="notifications()" x-init="init()" class="relative flex-shrink-0">
                        <button @click="toggleDropdown()" class="relative p-2 rounded-lg text-[#f8fafc]/70 hover:text-[#C9A84C] hover:bg-white/5 transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <span x-show="unreadCount > 0" x-cloak
                                  x-text="unreadCount > 9 ? '9+' : unreadCount"
                                  class="absolute -top-0.5 -right-0.5 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center ring-2 ring-[#0F172A]"></span>
                        </button>

                        <div x-show="dropdownOpen" x-cloak
                             @click.away="dropdownOpen = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute {{ $isRtl ? 'left-0' : 'right-0' }} mt-2 w-80 bg-[#1E293B] border border-[#1B5E20]/30 rounded-xl shadow-2xl z-50 overflow-hidden">

                            <div class="px-4 py-3 border-b border-[#1B5E20]/20 flex items-center justify-between">
                                <span class="text-sm font-semibold text-[#C9A84C]">{{ __('messages.notifications.title') }}</span>
                                <button @click="markAllRead()" x-show="unreadCount > 0"
                                        class="text-xs text-[#C9A84C]/70 hover:text-[#C9A84C] transition">
                                    {{ __('messages.notifications.mark_all_read') }}
                                </button>
                            </div>

                            <div class="max-h-72 overflow-y-auto">
                                <template x-if="notifications.length === 0">
                                    <div class="px-4 py-8 text-center">
                                        <svg class="w-10 h-10 text-[#f8fafc]/20 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                        <p class="text-[#f8fafc]/40 text-sm">{{ __('messages.notifications.empty') }}</p>
                                    </div>
                                </template>

                                <template x-for="notification in notifications" :key="notification.id">
                                    <a :href="'/notifications/' + notification.id + '/go'"
                                       class="block px-4 py-3 hover:bg-white/5 transition border-b border-[#1B5E20]/10 last:border-0">
                                        <div class="flex items-start gap-3">
                                            <div class="mt-0.5 w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                                                 :class="{
                                                     'bg-[#C9A84C]/20 text-[#C9A84C]': notification.type === 'review_reminder',
                                                     'bg-purple-500/20 text-purple-400': notification.type === 'ayah_review',
                                                     'bg-green-500/20 text-green-400': notification.type === 'achievement',
                                                     'bg-blue-500/20 text-blue-400': notification.type === 'streak'
                                                 }">
                                                <svg x-show="notification.type === 'review_reminder'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <svg x-show="notification.type === 'ayah_review'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                                                <svg x-show="notification.type === 'achievement'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                                <svg x-show="notification.type === 'streak'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm text-[#f8fafc]/80" x-text="notification.message"></p>
                                                <p class="text-xs text-[#f8fafc]/30 mt-1" x-text="notification.created_at"></p>
                                            </div>
                                            <span x-show="!notification.read_at" class="w-2 h-2 rounded-full bg-[#C9A84C] mt-2 flex-shrink-0"></span>
                                        </div>
                                    </a>
                                </template>
                            </div>

                            <div class="px-4 py-2 border-t border-[#1B5E20]/20 bg-[#0F172A]/50">
                                <a href="{{ route('notifications.index') }}" class="block text-center text-xs text-[#C9A84C]/70 hover:text-[#C9A84C] transition">
                                    {{ __('messages.notifications.view_all') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <span class="text-[#f8fafc]/70 text-sm font-medium whitespace-nowrap hidden xl:block">{{ __('messages.nav.greeting') }} {{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                        @csrf
                        <button type="submit" class="flex items-center gap-1.5 text-sm text-red-400 hover:text-red-300 transition px-2 py-2 rounded-lg hover:bg-white/5 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            {{ __('messages.nav.logout') }}
                        </button>
                    </form>
                </div>

                <button onclick="window.__toggleTheme && window.__toggleTheme()" class="theme-toggle-btn flex-shrink-0 mx-1" title="Toggle theme">
                    <svg class="theme-icon-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg class="theme-icon-light" style="display:none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>

                <a href="{{ route('locale.switch', $locale === 'ar' ? 'en' : 'ar') }}"
                   class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium text-[#C9A84C] hover:bg-[#C9A84C]/10 border border-[#C9A84C]/30 hover:border-[#C9A84C]/50 transition-all duration-200 flex-shrink-0">
                    @if($locale === 'ar')
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12.87 15.07l-2.54-2.51.03-.03A17.52 17.52 0 0014.07 6H17V4h-7V2H8v2H1v1.99h11.17C11.5 7.92 10.44 9.75 9 11.35 8.07 10.32 7.3 9.19 6.69 8h-2c.73 1.63 1.73 3.17 2.98 4.56l-5.09 5.02L4 19l5-5 3.11 3.11.76-2.04zM18.5 10h-2L12 22h2l1.12-3h4.75L21 22h2l-4.5-12zm-2.62 7l1.62-4.33L19.12 17h-3.24z"/></svg>
                        English
                    @else
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12.87 15.07l-2.54-2.51.03-.03A17.52 17.52 0 0014.07 6H17V4h-7V2H8v2H1v1.99h11.17C11.5 7.92 10.44 9.75 9 11.35 8.07 10.32 7.3 9.19 6.69 8h-2c.73 1.63 1.73 3.17 2.98 4.56l-5.09 5.02L4 19l5-5 3.11 3.11.76-2.04zM18.5 10h-2L12 22h2l1.12-3h4.75L21 22h2l-4.5-12zm-2.62 7l1.62-4.33L19.12 17h-3.24z"/></svg>
                        العربية
                    @endif
                </a>
            </div>

            <div class="flex items-center lg:hidden gap-2">
                <a href="{{ route('notifications.index') }}" class="relative p-2 text-[#f8fafc]/70 hover:text-[#C9A84C] transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    @php $unreadMobile = Auth::user()->unreadNotifications()->count(); @endphp
                    @if($unreadMobile > 0)
                    <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center">{{ $unreadMobile > 9 ? '9+' : $unreadMobile }}</span>
                    @endif
                </a>
                <button onclick="window.__toggleTheme && window.__toggleTheme()" class="theme-toggle-btn" title="Toggle theme">
                    <svg class="theme-icon-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg class="theme-icon-light" style="display:none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>
                <a href="{{ route('locale.switch', $locale === 'ar' ? 'en' : 'ar') }}"
                    class="text-sm font-medium text-[#C9A84C] px-2 py-1 rounded border border-[#C9A84C]/30 flex-shrink-0">
                    {{ $locale === 'ar' ? 'EN' : 'عربي' }}
                </a>
                <button @click="open = !open" class="text-[#f8fafc]/70 hover:text-[#C9A84C] transition p-2 rounded-lg hover:bg-white/5">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-2"
         class="lg:hidden bg-[#0F172A]/98 backdrop-blur-xl border-t border-[#1B5E20]/20">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm {{ request()->routeIs('dashboard') ? 'bg-[#1B5E20] text-white' : 'text-[#f8fafc]/70 hover:bg-white/5' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                {{ __('messages.nav.dashboard') }}
            </a>
            <a href="{{ route('quran.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm {{ request()->routeIs('quran.*') ? 'bg-[#1B5E20] text-white' : 'text-[#f8fafc]/70 hover:bg-white/5' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                {{ __('messages.nav.quran') }}
            </a>
            <a href="{{ route('reviews.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm {{ request()->routeIs('reviews.*') ? 'bg-[#1B5E20] text-white' : 'text-[#f8fafc]/70 hover:bg-white/5' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                {{ __('messages.nav.reviews') }}
            </a>
            <a href="{{ route('hifz.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm {{ request()->routeIs('hifz.*') ? 'bg-purple-600 text-white' : 'text-[#f8fafc]/70 hover:bg-white/5' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                {{ __('messages.nav.hifz') }}
            </a>
            <a href="{{ route('user.profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm {{ request()->routeIs('user.profile.*') ? 'bg-[#1B5E20] text-white' : 'text-[#f8fafc]/70 hover:bg-white/5' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                {{ __('messages.nav.profile') }}
            </a>
            <a href="{{ route('notifications.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm {{ request()->routeIs('notifications.*') ? 'bg-[#1B5E20] text-white' : 'text-[#f8fafc]/70 hover:bg-white/5' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                {{ __('messages.notifications.title') }}
            </a>

            <div class="border-t border-[#1B5E20]/20 pt-3 mt-3">
                <div class="px-4 py-2">
                    <div class="text-sm font-medium text-[#C9A84C]">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-[#f8fafc]/40">{{ Auth::user()->email }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 text-right px-4 py-3 rounded-xl text-sm text-red-400 hover:bg-white/5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        {{ __('messages.nav.logout') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
