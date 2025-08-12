const Encore = require('@symfony/webpack-encore');

Encore
    // le dossier où seront compilés les assets
    .setOutputPath('public/build/')
    // le chemin public utilisé par le navigateur pour accéder aux assets compilés
    .setPublicPath('/build')
    // ton point d’entrée JS principal
    .addEntry('app', './assets/app.js')

    // pour compiler les fichiers SCSS/Sass
    .enableSassLoader()

    // active les fichiers source maps pour le dev
    .enableSourceMaps(!Encore.isProduction())

    // nettoie le dossier public/build à chaque build
    .cleanupOutputBeforeBuild()

    // permet d’avoir un manifest.json pour Symfony
    .enableVersioning(Encore.isProduction())

    // active Stimulus si tu l’utilises
    // .enableStimulusBridge('./assets/controllers.json')

    .enableSingleRuntimeChunk()
;

module.exports = Encore.getWebpackConfig();
