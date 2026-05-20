<svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    <defs>
        <linearGradient id="primaryGrad" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#1B5E20" />
            <stop offset="100%" stop-color="#0a2e0f" />
        </linearGradient>
        <linearGradient id="goldGrad" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#FFD700" />
            <stop offset="100%" stop-color="#C9A84C" />
        </linearGradient>
        <filter id="glow" x="-20%" y="-20%" width="140%" height="140%">
            <feGaussianBlur stdDeviation="4" result="blur" />
            <feComposite in="SourceGraphic" in2="blur" operator="over" />
        </filter>
    </defs>
    
    <!-- Outer Shield/Mihrab -->
    <path d="M100 15 L175 45 L175 110 C175 155 100 185 100 185 C100 185 25 155 25 110 L25 45 Z" fill="url(#primaryGrad)" />
    
    <!-- Inner Gold Line -->
    <path d="M100 28 L160 52 L160 105 C160 142 100 168 100 168 C100 168 40 142 40 105 L40 52 Z" fill="none" stroke="url(#goldGrad)" stroke-width="4" filter="url(#glow)" />
    
    <!-- Quran Book Shape -->
    <path d="M100 140 C80 140 60 130 50 120 L50 70 C60 80 80 90 100 90 C120 90 140 80 150 70 L150 120 C140 130 120 140 100 140 Z" fill="url(#goldGrad)" />
    <path d="M100 90 L100 140" stroke="#0a2e0f" stroke-width="6" stroke-linecap="round" />
    
    <!-- Star/Sparkle above book -->
    <path d="M100 45 L105 60 L120 65 L105 70 L100 85 L95 70 L80 65 L95 60 Z" fill="#ffffff" filter="url(#glow)" />
</svg>
