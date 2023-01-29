/*
	Copyright © XlXi 2022
*/

const mix = require('laravel-mix');
require('laravel-mix-banner');

mix.js('resources/js/app.js', 'public/js')
	.js('resources/js/pages/Blog.js', 'public/js')
	.js('resources/js/pages/Maintenance.js', 'public/js')
	.js('resources/js/pages/Dashboard.js', 'public/js')
	.js('resources/js/pages/Shop.js', 'public/js')
	.js('resources/js/pages/Games.js', 'public/js')
	.js('resources/js/pages/Transactions.js', 'public/js')
	.js('resources/js/pages/AvatarEditor.js', 'public/js')
	.js('resources/js/pages/Item.js', 'public/js')
	.js('resources/js/pages/Place.js', 'public/js')
	.js('resources/js/pages/ManualAssetUpload.js', 'public/js/adm')
	.js('resources/js/pages/ManualUserModeration.js', 'public/js/adm')
	.js('resources/js/pages/AppDeployer.js', 'public/js/adm')
	.js('resources/js/pages/SiteConfiguration.js', 'public/js/adm')
    .react()
    .sass('resources/sass/VirtuBrick.scss', 'public/css')
	.banner({
        banner: (function () {
            return [
                '/*!',
				` * VirtuBrick`,
				` * Copyright © XlXi ${new Date().getFullYear()}`,
				' *',
				` * BUILD TIMESTAMP: ${new Date().toUTCString()}`,
				' */'
            ].join('\n');
        })(),
        raw: true,
    })
	.version();