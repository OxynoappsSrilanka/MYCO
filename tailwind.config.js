/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './**/*.php',
    './assets/js/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        'navy': '#141943',
        'navy-dark': '#141943',
        'navy-mid': '#354968',
        'myco-red': '#C8402E',
        red: {
          DEFAULT: '#C8402E',
        },
      },
      fontFamily: {
        inter: ['Inter', 'sans-serif'],
      },
      maxWidth: {
        'inner': '1560px',
      },
    },
  },
  plugins: [],
};
