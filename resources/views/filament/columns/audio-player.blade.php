@if($getState())
<audio controls preload="none" class="h-8" style="max-width: 200px;">
    <source src="{{ asset('storage/' . $getState()) }}" type="audio/webm">
    <source src="{{ asset('storage/' . $getState()) }}" type="audio/mpeg">
    متصفحك لا يدعم مشغل الصوت.
</audio>
@else
<span class="text-gray-400 text-xs">لا يوجد صوت</span>
@endif
