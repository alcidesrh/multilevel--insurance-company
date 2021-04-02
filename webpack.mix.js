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

const VuetifyLoaderPlugin = require('vuetify-loader/lib/plugin')

mix.webpackConfig({
    plugins: [
        new VuetifyLoaderPlugin()
    ],
}).js('resources/js/main.js', 'public/js')
.js('resources/js/application.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .options({
        hmrOptions: {
            host: 'multilevel-company.wip',
            port: 8080
        }
    })
