const mix = require('laravel-mix');

require('laravel-mix-banner');

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

mix.js('resources/js/app.js', 'public/js')
	.js('resources/js/pages/maintenance.js', 'public/js/pages')
    .react()
    .sass('resources/sass/graphictoria.scss', 'public/css')
	.banner({
        banner: (function () {
            return [
                '/*!',
				' * Graphictoria 5 (https://gtoria.net)',
				' * Copyright © XlXi 2021',
				'*/'
            ].join('\n');
        })(),
        raw: true,
    });