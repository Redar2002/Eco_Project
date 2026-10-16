import defaultTheme from 'tailwindcss/defaultTheme';
import colors from 'tailwindcss/colors';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './app/Enums/**/*.php',
    ],

    theme: {
        extend: {
            colors: {
                // Palette « Noir & Or » : noir profond, or champagne, rouge carmin
                primary: {
                    DEFAULT: '#0A0A0A',
                    50: '#F7F7F7', 100: '#EDEDED', 200: '#D6D6D6', 300: '#B3B3B3', 400: '#8A8A8A',
                    500: '#6B6B6B', 600: '#4D4D4D', 700: '#333333', 800: '#1F1F1F', 900: '#141414', 950: '#050505',
                },
                accent: {
                    DEFAULT: '#C9A24B',
                    50: '#FBF8F0', 100: '#F5ECD4', 200: '#EBD8A7', 300: '#DFC27A', 400: '#D4B05C',
                    500: '#C9A24B', 600: '#AD873A', 700: '#8A6A2E', 800: '#6A5126', 900: '#4A391C',
                },
                rouge: { ...colors.red, DEFAULT: '#B91C1C' },
                ink: colors.neutral,
                ivory: '#F6F3EC',
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['"Playfair Display"', ...defaultTheme.fontFamily.serif],
            },
            letterSpacing: {
                luxe: '0.22em',
            },
            boxShadow: {
                soft: '0 1px 2px rgba(10,10,10,.04), 0 10px 30px -14px rgba(10,10,10,.16)',
                lift: '0 2px 4px rgba(10,10,10,.05), 0 28px 50px -20px rgba(10,10,10,.30)',
                gold: '0 0 0 1px rgba(201,162,75,.55), 0 18px 40px -18px rgba(201,162,75,.45)',
            },
            backgroundImage: {
                'gold-gradient': 'linear-gradient(135deg, #F5E3B3 0%, #C9A24B 45%, #8A6A2E 100%)',
            },
            keyframes: {
                'slide-in': { '0%': { opacity: 0, transform: 'translateY(12px) scale(.98)' }, '100%': { opacity: 1, transform: 'none' } },
                marquee: { '0%': { transform: 'translateX(0)' }, '100%': { transform: 'translateX(-50%)' } },
                shimmer: { '0%': { backgroundPosition: '-200% 0' }, '100%': { backgroundPosition: '200% 0' } },
            },
            animation: {
                'slide-in': 'slide-in .25s ease-out',
                marquee: 'marquee 40s linear infinite',
                shimmer: 'shimmer 6s linear infinite',
            },
        },
    },

    plugins: [forms],
};
