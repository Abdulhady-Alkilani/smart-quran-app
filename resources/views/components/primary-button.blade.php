<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-[#1B5E20] to-[#2E7D32] border border-transparent rounded-xl font-medium text-sm text-white tracking-widest hover:from-[#2E7D32] hover:to-[#388E3C] focus:outline-none focus:ring-2 focus:ring-[#1B5E20] focus:ring-offset-2 focus:ring-offset-[#0F172A] transition-all duration-300 transform hover:scale-[1.02] shadow-lg shadow-[#1B5E20]/30']) }}>
    {{ $slot }}
</button>
