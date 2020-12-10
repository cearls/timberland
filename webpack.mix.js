const mix = require('laravel-mix');
require('laravel-mix-postcss-config');

mix
  .js('assets/scripts/app.js', 'assets/build/')
  .postCss('assets/styles/app.css', 'assets/build/', [
    require('tailwindcss'),
  ])
  .postCssConfig();

// Build theme for production
if (mix.inProduction()) {
  mix.copy('assets/build', 'build/assets/build');
  mix.copy('assets/fonts', 'build/assets/fonts');
  mix.copy('assets/images', 'build/assets/images');
  mix.copy('components', 'build/components');
  mix.copy('theme', 'build/theme');
  mix.copy('vendor', 'build/vendor');
  mix.copy('views', 'build/views');
}
