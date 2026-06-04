/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
        fontFamily: {
          jakarta: ['"Plus Jakarta Sans"', 'sans-serif'],
          inter: ['Inter', 'sans-serif'],
        },
        colors: {
          brand: {
            50: '#eef2ff',
            100: '#e0e7ff',
            200: '#c7d2fe',
            300: '#a5b4fc',
            400: '#818cf8',
            500: '#6366f1',
            600: '#4f46e5',
            700: '#4338ca',
            800: '#3730a3',
            900: '#312e81',
          },
        },
        boxShadow: {
          'soft': '0 2px 15px -3px rgba(0,0,0,0.07), 0 10px 20px -2px rgba(0,0,0,0.04)',
          'card': '0 1px 3px rgba(0,0,0,0.05), 0 4px 12px rgba(0,0,0,0.06)',
          'card-hover': '0 8px 30px rgba(0,0,0,0.12)',
          'brand': '0 4px 14px 0 rgba(99,102,241,0.35)',
        },
        animation: {
          'float': 'float 3s ease-in-out infinite',
          'float-delayed': 'float 3s ease-in-out 1.5s infinite',
          'pulse-soft': 'pulse-soft 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
          'bounce-slight': 'bounce-slight 2s infinite',
        },
        keyframes: {
          float: {
            '0%, 100%': { transform: 'translateY(0)' },
            '50%': { transform: 'translateY(-10px)' },
          },
          'pulse-soft': {
            '0%, 100%': { opacity: 1 },
            '50%': { opacity: .7 },
          },
          'bounce-slight': {
            '0%, 100%': { transform: 'translateY(-5%)', animationTimingFunction: 'cubic-bezier(0.8,0,1,1)' },
            '50%': { transform: 'none', animationTimingFunction: 'cubic-bezier(0,0,0.2,1)' },
          },
          shine: {
            '100%': { transform: 'translateX(100%)' },
          }
        }
    },
  },
  plugins: [],
}
