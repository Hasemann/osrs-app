const Encore = require('@symfony/webpack-encore');
const path = require('path');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/app.js')
    .enableVueLoader() // Enable Vue 3 support
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .enablePostCssLoader((options) => {
        options.postcssOptions = {
            config: './postcss.config.js'
        };
    })

module.exports = Encore.getWebpackConfig();

