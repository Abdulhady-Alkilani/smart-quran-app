import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Tajawal', 'Segoe UI', 'system-ui', 'sans-serif'],
            },
            colors: {
                'quran-green': {
                    50: '#e8f5e9',
                    100: '#c8e6c9',
                    200: '#a5d6a7',
                    300: '#81c784',
                    400: '#66bb6a',
                    500: '#1B5E20',
                    600: '#2E7D32',
                    700: '#1B5E20',
                    800: '#0D3B13',
                    900: '#0a2e0f',
                },
                'quran-gold': {
                    50: '#fdf8e7',
                    100: '#f9edc3',
                    200: '#f0d88a',
                    300: '#e5c14f',
                    400: '#d4b96a',
                    500: '#C9A84C',
                    600: '#b8943a',
                    700: '#a07e2d',
                    800: '#876922',
                    900: '#6e5519',
                },
            },
            borderRadius: {
                'xl': '1rem',
                '2xl': '1.25rem',
                '3xl': '1.5rem',
            },
        },
    },
}
