const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');
const purgecss = require('@fullhuman/postcss-purgecss')({
    content: [
        './resources/**/*.vue',
        './resources/**/*.blade.php',
    ],
    defaultExtractor: content => content.match(/[\w-/:]+(?<!:)/g) || []
});

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

mix.js('resources/js/echo.js', 'public/js')
    .js('resources/js/chatroom.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .options({
        processCssUrls: false,
        postCss: [
            tailwindcss('./tailwind.config.js'),
            ...process.env.NODE_ENV === 'production'
                ? [purgecss]
                : []
        ],
    });
