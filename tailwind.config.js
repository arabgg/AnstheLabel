/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/views/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        Montserrat: ['Montserrat', 'sans-serif'],
      },
    },
  },
  safelist: [
    'font-Montserrat',
    'bg-[#560024]',
    'hover:bg-[#A65A6A]',
    'focus:ring-rose-700',
  ],
  plugins: [],
}
