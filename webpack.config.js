var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('web/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/')
    //.cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())
    // deprecation??
    .enableSingleRuntimeChunk()

    .addStyleEntry(
        'css/common',
        [
            './vendor/owenversteeg/min/less/general.less',
            './vendor/owenversteeg/min/less/buttons.less',
            './vendor/owenversteeg/min/less/grid.less',
            './vendor/owenversteeg/min/less/headings.less',
            './vendor/owenversteeg/min/less/icons.less',
            './vendor/owenversteeg/min/less/forms.less',
            './vendor/owenversteeg/min/less/navbar.less',
            './vendor/owenversteeg/min/less/tables.less',
            './vendor/owenversteeg/min/less/messages.less',
            './src/PhpOfBy/WebsiteBundle/Resources/public/css/common.css' // Unfortunately webpack does not have connection to symfony bundles
        ]
    )

    .addStyleEntry(
        'css/front',
        [
            './src/PhpOfBy/WebsiteBundle/Resources/public/css/front.css'
        ]
    )

    // uncomment to define the assets of the project
    // .addEntry('js/app', './assets/js/app.js')
    // .addStyleEntry('css/app', './assets/css/app.scss')

    // uncomment if you use less files
    .enableLessLoader()

    // uncomment for legacy applications that require $/jQuery as a global variable
    // .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
