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
    mix
        .scripts([
            '../../../bower_components/jquery/dist/jquery.min.js'
        ], 'public/js/scripts.js')
        .styles([
            '../../../bower_components/bootstrap/dist/css/bootstrap.min.css',
            '../../../bower_components/bootstrap-material-design/dist/bootstrap-material-design.min.css',
            '../../../bower_components/font-awesome/css/font-awesome.min.css'
        ], 'public/css/styles.css')
        .copy( '../../../bower_components/font-awesome/fonts', 'public/build/fonts' )
        .version([
              'css/styles.css',
              'js/scripts.js'
        ]);
});
