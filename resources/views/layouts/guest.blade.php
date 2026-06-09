@php
    $locale = app()->getLocale();
    $isRtl = $locale === 'ar';
    $dir = $isRtl ? 'rtl' : 'ltr';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $locale) }}" dir="{{ $dir }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Smart Quran Platform') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Noto+Naskh+Arabic:wght@400;500;600;700&family=Tajawal:wght@300;400;500;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        // Apply theme immediately to prevent flash
        (function() {
            var theme = localStorage.getItem('quran_theme') || 'dark';
            if (theme === 'light') {
                document.documentElement.classList.add('light-mode');
            }
        })();
    </script>
</head>
<body class="font-tajawal antialiased bg-[#0F172A] text-[#f8fafc] transition-colors duration-300" style="font-family: {{ $isRtl ? "'Tajawal', sans-serif" : "'Inter', 'Tajawal', sans-serif" }};">
    <!-- Language and Theme Toggle -->
    <div class="absolute top-4 {{ $isRtl ? 'left-4' : 'right-4' }} flex items-center gap-4 z-50">
        <a href="{{ route('locale.switch', $locale === 'ar' ? 'en' : 'ar') }}" class="text-[#f8fafc]/70 hover:text-[#f8fafc] transition font-medium">
            {{ $locale === 'ar' ? 'English' : 'العربية' }}
        </a>
        <button onclick="window.__toggleTheme()" type="button" class="theme-toggle-btn">
            <svg class="theme-icon-dark" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
            <svg class="theme-icon-light" style="display: none;" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
        </button>
    </div>

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div>
            <a href="/" class="flex items-center gap-3 text-2xl font-bold text-[#C9A84C]">
                <x-application-logo class="w-10 h-10" />
                <span style="font-family: 'Amiri', serif;">{{ __('messages.app_name') }}</span>
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white/5 backdrop-blur-md border border-white/10 shadow-xl overflow-hidden sm:rounded-2xl">
            {{ $slot }}
        </div>
    </div>
    
    <script>
        // Theme Toggle Functionality matching app.blade.php
        (function() {
            function updateThemeIcons() {
                var isLight = document.documentElement.classList.contains('light-mode');
                var darkIcons = document.querySelectorAll('.theme-icon-dark');
                var lightIcons = document.querySelectorAll('.theme-icon-light');
                for (var i = 0; i < darkIcons.length; i++) {
                    darkIcons[i].style.display = isLight ? 'none' : 'block';
                }
                for (var i = 0; i < lightIcons.length; i++) {
                    lightIcons[i].style.display = isLight ? 'block' : 'none';
                }
            }

            window.__toggleTheme = function() {
                var html = document.documentElement;
                var isLight = html.classList.contains('light-mode');
                if (isLight) {
                    html.classList.remove('light-mode');
                    localStorage.setItem('quran_theme', 'dark');
                } else {
                    html.classList.add('light-mode');
                    localStorage.setItem('quran_theme', 'light');
                }
                updateThemeIcons();
                document.dispatchEvent(new CustomEvent('themeChanged', { detail: { isLight: !isLight } }));
            };

            // Update icons on load
            document.addEventListener('DOMContentLoaded', updateThemeIcons);
        })();
    </script>
</body>
</html>
