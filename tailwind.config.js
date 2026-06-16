const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.tsx',
        './resources/**/*.ts',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#edfcff',
                    100: '#d6f7ff',
                    200: '#b5f0ff',
                    300: '#83e8ff',
                    400: '#48d5ff',
                    500: '#00d4ff',
                    600: '#00b8e6',
                    700: '#0090b8',
                    800: '#007796',
                    900: '#00657d',
                },
                accent: {
                    50: '#edfff6',
                    100: '#d6ffea',
                    200: '#b0ffd8',
                    300: '#6bffc0',
                    400: '#1eff9e',
                    500: '#00ff88',
                    600: '#00cc6d',
                    700: '#00a357',
                    800: '#008045',
                    900: '#00693a',
                },
                dark: {
                    50: '#f6f6f7',
                    100: '#e1e3e8',
                    200: '#c3c7d1',
                    300: '#9ea3b2',
                    400: '#788094',
                    500: '#5d6579',
                    600: '#4a5163',
                    700: '#3d4252',
                    800: '#243044',
                    900: '#1a2332',
                    950: '#111827',
                    975: '#0a0e17',
                },
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
            animation: {
                'glow': 'glow 2s ease-in-out infinite',
                'float': 'float 3s ease-in-out infinite',
                'pulse-dot': 'pulseDot 2s ease-in-out infinite',
                'shimmer': 'shimmer 2s linear infinite',
                'slide-up': 'slideUp 0.4s ease-out',
                'slide-in': 'slideIn 0.3s ease-out',
                'fade-in': 'fadeIn 0.3s ease-out',
                'scale-in': 'scaleIn 0.2s ease-out',
            },
            keyframes: {
                glow: {
                    '0%, 100%': { boxShadow: '0 0 10px rgba(0, 212, 255, 0.2)' },
                    '50%': { boxShadow: '0 0 25px rgba(0, 212, 255, 0.4)' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-4px)' },
                },
                pulseDot: {
                    '0%, 100%': { transform: 'scale(1)', opacity: '1' },
                    '50%': { transform: 'scale(1.3)', opacity: '0.7' },
                },
                shimmer: {
                    '0%': { transform: 'translateX(-100%)' },
                    '100%': { transform: 'translateX(100%)' },
                },
                slideUp: {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideIn: {
                    '0%': { opacity: '0', transform: 'translateX(-12px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                fadeIn: {
                    '0%': { opacity: '0', transform: 'translateY(8px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                scaleIn: {
                    '0%': { opacity: '0', transform: 'scale(0.95)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
            },
            boxShadow: {
                'glow': '0 0 20px rgba(0, 212, 255, 0.15)',
                'glow-lg': '0 0 40px rgba(0, 212, 255, 0.3)',
                'glow-accent': '0 0 20px rgba(0, 255, 136, 0.15)',
                'glass': '0 8px 32px rgba(0, 0, 0, 0.3)',
            },
        },
    },
    plugins: [],
};
