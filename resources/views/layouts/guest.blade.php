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
</head>
<body class="font-tajawal antialiased bg-[#0F172A] text-[#f8fafc]" style="font-family: {{ $isRtl ? "'Tajawal', sans-serif" : "'Inter', 'Tajawal', sans-serif" }};">
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
</body>
</html>
