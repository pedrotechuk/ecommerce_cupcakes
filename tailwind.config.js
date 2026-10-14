import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                display: ['Fraunces', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                brand: {
                    50: '#FFF1F5',
                    100: '#FFE1EA',
                    200: '#FFC2D6',
                    300: '#FF9DBB',
                    400: '#FB6F9C',
                    500: '#EF4E82',
                    600: '#DB2E68',
                    700: '#B81F54',
                    800: '#8F1943',
                    900: '#6B1435',
                },
                mocha: {
                    50: '#FBF6F3',
                    100: '#F1E4DB',
                    200: '#E0C4B2',
                    300: '#C99A7C',
                    400: '#AD7454',
                    500: '#8B5A3C',
                    600: '#6E4530',
                    700: '#553524',
                    800: '#3D2519',
                    900: '#2A1810',
                },
                cream: {
                    50: '#FFFDFB',
                    100: '#FDF8F3',
                },
            },
            animation: {
                'fade-in-up': 'fadeInUp 0.5s ease-out both',
            },
            keyframes: {
                fadeInUp: {
                    '0%': { opacity: '0', transform: 'translateY(0.75rem)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
            },
        },
    },

    plugins: [forms],
};
