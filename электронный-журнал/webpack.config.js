const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
      // Указываем директорию, куда будут помещены скомпилированные файлы
      .setOutputPath('public/build/')
      // Указываем публичный путь
      .setPublicPath('/build')
      // Добавляем главный JS-файл
      .addEntry('app', './assets/app.js')
      // Включаем поддержку SASS/SCSS, если нужно
      .enableSassLoader()
      // Автоматическое подключение jQuery (если нужно)
      .autoProvidejQuery()
      // Очистка папки build перед каждой сборкой
      .cleanupOutputBeforeBuild()
      // Включаем уведомления
      .enableBuildNotifications()
      // Включаем source maps в режиме разработки
      .enableSourceMaps(!Encore.isProduction())
      Encore.enableSingleRuntimeChunk()
      // Включаем минификацию для production
      .enableVersioning(Encore.isProduction());  


module.exports = Encore.getWebpackConfig();
