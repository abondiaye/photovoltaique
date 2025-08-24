// webpack.config.js
const Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/app.js') 
    .addEntry('bg', './assets/background.js')
    .enableSingleRuntimeChunk()
    .splitEntryChunks()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .enableSassLoader();
    

const config = Encore.getWebpackConfig();
config.mode = Encore.isProduction() ? 'production' : 'development';

module.exports = config;
