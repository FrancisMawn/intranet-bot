let mix = require('laravel-mix');
let ImageminPlugin = require('imagemin-webpack-plugin').default;
let ImageminMozjpeg = require('imagemin-mozjpeg');
let CopyWebpackPlugin = require('copy-webpack-plugin');

mix.setPublicPath('web/');

// Copy WET/GCweb vendor to web root
mix.copyDirectory('src/gcweb', 'web/dist/gcweb')
    .copyDirectory('src/wet-boew', 'web/dist/wet-boew');

// Build app sass
mix.sass('src/nglxp/css/app.scss', 'dist/nglxp/css').options({ processCssUrls: false }).sourceMaps();

// Build app js
mix.js('src/nglxp/js/app.js', 'dist/nglxp/js');

// Optimize assets and copy to web root
mix.webpackConfig({
    plugins: [
        new CopyWebpackPlugin([{
            from: 'src/nglxp/assets',
            to: 'dist/nglxp/assets'
        }]),
        new ImageminPlugin({
            test: /\.(jpe?g|png|gif|svg)$/i,
            plugins: [
                ImageminMozjpeg({
                    quality: 80
                })
            ]
        })
    ]
});

// Versioning
if (mix.inProduction()) {
    mix.version();
}