/** @type {import('tailwindcss').Config} */

export default Config ({

  darkMode: 'class', // use <html class="dark">

  content: [
    // "./resources/**/*.blade.php",
    // "./resources/**/*.js",
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    "./resources/**/*.vue",
  ],

  theme: {
    extend: {},
  },

  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    require('@tailwindcss/aspect-ratio'),
  ],
});

