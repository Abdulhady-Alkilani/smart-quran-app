@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-[#f8fafc] placeholder-[#f8fafc]/40 focus:outline-none focus:border-[#1B5E20] focus:ring-1 focus:ring-[#1B5E20] transition disabled:opacity-50']) }}>
