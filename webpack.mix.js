const mix = require('laravel-mix');
const postcssImport = require('postcss-import');

mix.postCss('resources/css/app.css', 'public/css', [
    postcssImport,
    require('autoprefixer'),
]);

mix.css('resources/css/style.css', 'public/css');

if (mix.inProduction()) {
    mix.version();
}
