// webpack.config.js
const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build')
  
  // entrées (noms uniques)
  .addEntry('app', './assets/app.js')
  // décommente SEULEMENT si le fichier existe et est utilisé :
  // .addEntry('bg', './assets/background.js')

  .splitEntryChunks()
  .enableSingleRuntimeChunk()

  .cleanupOutputBeforeBuild()
  .enableBuildNotifications()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())

  .enableSassLoader()
  .enablePostCssLoader()

  // ❌ PAS de .configureBabel() ni .configureBabelPresetEnv() ici
;

module.exports = Encore.getWebpackConfig();
