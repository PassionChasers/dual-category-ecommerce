/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class',

  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './resources/**/*.vue',
  ],

  theme: {
    extend: {
      colors: {
        primary: '#E56F07',
        'primary-dark': '#cc6306',
        btn_blue: '#077DE5',
        btn_yellow: '#E5DE07',
      },
    },
  },

  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    require('@tailwindcss/aspect-ratio'),
  ],
}
