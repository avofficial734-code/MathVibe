import defaultTheme from 'tailwindcss/defaultTheme';
import colors from 'tailwindcss/colors';
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
            },
            colors: {
                brand: {
                    primary: colors.indigo,
                    secondary: colors.violet,
                    accent: colors.emerald,
                    info: colors.sky,
                    warn: colors.amber,
                    danger: colors.rose,
                    neutral: colors.slate,
                },
            },
        },
    },

    plugins: [forms],
};
