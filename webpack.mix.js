const mix = require('laravel-mix');

// Compile JS utama Laravel
mix.js('resources/js/app.js', 'public/js')

// Compile CSS utama dengan Tailwind
   .postCss('resources/css/app.css', 'public/css', [
       require('postcss-import'),
       require('tailwindcss'),
       require('autoprefixer'),
   ])

// Kalau kamu punya CSS tambahan manual, bisa tambahkan juga
   .postCss('resources/css/style.css', 'public/css')

// Aktifkan versioning kalau production
if (mix.inProduction()) {
    mix.version();
}
