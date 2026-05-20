<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold gradient-text" style="font-family: 'Amiri', serif;">{{ __('messages.notifications.page_title') }}</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($notifications->count() > 0)
        <div class="flex justify-end mb-4">
            <form method="POST" action="{{ route('notifications.read-all') }}" id="markAllForm">
                @csrf
                <button type="submit" class="text-sm text-[#C9A84C]/70 hover:text-[#C9A84C] transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('messages.notifications.mark_all_read') }}
                </button>
            </form>
        </div>

        <div class="space-y-3">
            @foreach($notifications as $notification)
                @php
                    $data = $notification->data;
                    $locale = app()->getLocale();
                    $messageKey = $locale === 'ar' ? 'message_ar' : 'message_en';
                    $message = $data[$messageKey] ?? ($data['message_ar'] ?? '');
                    $type = $data['type'] ?? 'general';
                    $isRead = !is_null($notification->read_at);
                @endphp

                <a href="{{ route('notifications.go', $notification->id) }}" class="glass-card p-5 group flex items-start gap-4 {{ !$isRead ? 'border-l-2 border-l-[#C9A84C]' : '' }} hover:bg-white/[0.02] transition-all cursor-pointer block">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                        {{ $type === 'review_reminder' ? 'bg-[#C9A84C]/20 text-[#C9A84C]' : ($type === 'achievement' ? 'bg-green-500/20 text-green-400' : ($type === 'streak' ? 'bg-blue-500/20 text-blue-400' : 'bg-[#f8fafc]/10 text-[#f8fafc]/40')) }}">
                        @if($type === 'review_reminder')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @elseif($type === 'achievement')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        @elseif($type === 'streak')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-[#f8fafc]/80 {{ !$isRead ? 'font-medium' : '' }}">{{ $message }}</p>
                        <p class="text-xs text-[#f8fafc]/30 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        @if(!$isRead)
                            <span class="w-2 h-2 rounded-full bg-[#C9A84C]"></span>
                        @endif
                        <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}" onclick="event.stopPropagation();">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-[#f8fafc]/20 hover:text-red-400 transition p-1" onclick="event.stopPropagation();">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $notifications->withQueryString()->links() }}
        </div>
        @else
        <div class="glass-card p-12 text-center">
            <div class="w-20 h-20 rounded-full bg-[#C9A84C]/10 flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-[#C9A84C]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </div>
            <h3 class="text-xl font-bold text-[#C9A84C] mb-2">{{ __('messages.notifications.no_notifications') }}</h3>
            <p class="text-[#f8fafc]/40 text-sm mb-6">{{ __('messages.notifications.no_notifications_desc') }}</p>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 bg-[#1B5E20] hover:bg-[#2E7D32] text-white px-6 py-3 rounded-xl transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                {{ __('messages.notifications.back_dashboard') }}
            </a>
        </div>
        @endif
    </div>
</x-app-layout>
