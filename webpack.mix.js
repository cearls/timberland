const mix = require('laravel-mix');

mix
  .js('theme/assets/scripts/app.js', 'theme/assets/build/')
  .postCss('theme/assets/styles/app.css', 'theme/assets/build/', [
    require('tailwindcss'),
  ]);
