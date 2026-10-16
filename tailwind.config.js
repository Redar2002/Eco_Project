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

    // Classes générées dynamiquement (couleurs des badges de statut)
    safelist: [
        { pattern: /(bg|text|ring)-(amber|blue|indigo|green|red|slate|primary|accent)-(50|100|600|700|800)/ },
    ],

    theme: {
        extend: {
            colors: {
                // Couleur principale : bleu nuit #1E3A8A
                primary: { ...colors.blue, DEFAULT: '#1E3A8A', 950: '#0f1d47' },
                // Couleur d'accent : ambre #F59E0B
                accent: { ...colors.amber, DEFAULT: '#F59E0B' },
                ink: colors.slate,
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                soft: '0 1px 2px rgba(15,23,42,.04), 0 8px 24px -12px rgba(15,23,42,.12)',
                lift: '0 2px 4px rgba(15,23,42,.04), 0 24px 48px -16px rgba(15,23,42,.22)',
            },
            keyframes: {
                'slide-in': { '0%': { opacity: 0, transform: 'translateY(12px) scale(.98)' }, '100%': { opacity: 1, transform: 'none' } },
            },
            animation: {
                'slide-in': 'slide-in .25s ease-out',
            },
        },
    },

    plugins: [forms],
};
