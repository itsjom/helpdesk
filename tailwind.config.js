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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                black: '#2d2d2d',
                'dark-gray': '#555555',
                'mid-gray': '#999999',
                'light-gray': '#f0f0f0',
                'surface-gray': '#f7f7f7',
                'border-gray': '#e5e5e5',
            },
            borderRadius: {
                'card': '0px',
                'input': '0px',
                'badge': '0px',
            }
        },
    },

    plugins: [forms],
};
