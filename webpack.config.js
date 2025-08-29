// webpack.config.js
const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build')

  // Entrées
  .addEntry('app', './assets/app.js')
  .addEntry('bg', './assets/background.js') // si tu utilises le canvas animé

  // Optimisations
  .splitEntryChunks()
  .enableSingleRuntimeChunk()

  .cleanupOutputBeforeBuild()
  .enableBuildNotifications()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())

  // CSS/SCSS + PostCSS
  .enableSassLoader()
  .enablePostCssLoader()

  // Babel (ES compat + polyfills core-js)
  .configureBabel((config) => {
    config.presets.push([
      '@babel/preset-env',
      { useBuiltIns: 'usage', corejs: 3 }
    ]);
  })
;

module.exports = Encore.getWebpackConfig();
