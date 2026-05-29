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
    },
  },
  plugins: [],
}
