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
	.js('resources/js/pages/Maintenance.js', 'public/js')
	.js('resources/js/pages/Dashboard.js', 'public/js')
	.js('resources/js/pages/Shop.js', 'public/js')
    .react()
    .sass('resources/sass/Graphictoria.scss', 'public/css')
	.banner({
        banner: (function () {
            return [
                '/*!',
				' * Graphictoria 5 (https://gtoria.net)',
				` * Copyright © XlXi ${new Date().getFullYear()}`,
				' *',
				` * BUILD TIMESTAMP: ${new Date().toUTCString()}`,
				' */'
            ].join('\n');
        })(),
        raw: true,
    })
	.version();