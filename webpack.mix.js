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

mix.js('resources/js/app.js', 'public/js')
    .vue({ version: 3 })
    .sass('resources/sass/app.scss', 'public/css')
    .options({
        processCssUrls: false
    });

if (mix.inProduction()) {
    const TerserPlugin = require('terser-webpack-plugin');
    mix.webpackConfig({
            optimization: {
                minimize: true,
                minimizer: [new TerserPlugin(
                    {
                        extractComments: true,
                        cache: true,
                        parallel: true,
                        sourceMap: true,
                        terserOptions: {
                            extractComments: 'all',
                            compress: {
                                drop_console: false,
                            },
                        }
                    }
                )],

        }
    });
}

mix.version();
