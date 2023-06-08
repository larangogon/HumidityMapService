const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/sass')
    .sass('node_modules/bootstrap/scss/bootstrap.scss', 'public/sass');
