var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.less('style.less', 'resources/assets/css/style.css')
        .scripts([
            '../../../bower_components/jquery/dist/jquery.min.js',
            '../../../bower_components/angular/angular.js',
            '../../../bower_components/bootstrap/dist/js/bootstrap.min.js',
            '../../../bower_components/bootstrap-material-design/dist/js/material.min.js',
            '../../../bower_components/bootstrap-material-design/dist/js/ripples.min.js',
            '../../../bower_components/ng-file-upload/ng-file-upload-shim.min.js',
            '../../../bower_components/ng-file-upload/ng-file-upload.min.js',
            '../../../bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js',


            'app.js',
            'app.config.js',
            'controller.upload.js'
        ], 'public/js/scripts.js')
        .styles([
            '../../../bower_components/bootstrap/dist/css/bootstrap.min.css',
            '../../../bower_components/font-awesome/css/font-awesome.min.css',
            '../../../bower_components/bootstrap-material-design/dist/css/bootstrap-material-design.min.css',
            '../../../bower_components/bootstrap-material-design/dist/css/ripples.min.css',

            'style.css'
        ], 'public/css/styles.css')
        .copy( '../../../bower_components/bootstrap/dist/fonts', 'public/build/fonts' )
        .copy( '../../../bower_components/font-awesome/fonts', 'public/build/fonts' )
        .version([
              'css/styles.css',
              'js/scripts.js'
        ]);
});
