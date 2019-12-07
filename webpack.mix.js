const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
  .js('resources/js/admin.js', 'public/js')
  .js('resources/js/docs.js', 'public/js')
  .js('resources/js/end-user.js', 'public/js')
  .sass('resources/sass/admin.scss', 'public/css')
  .sass('resources/sass/docs.scss', 'public/css')
  .sass('resources/sass/end-user.scss', 'public/css')
  .copy('resources/assets', 'public/assets')
  .version();
