// webpack.mix.js
let mix = require('laravel-mix');

mix
    .options({ manifest: false });

// all local fonts & images referenced in the less files
// are automatically copied to the respective public folders
mix
    .less('resources/css/less/style.less', 'public/css/style.css')
    .minify([
        'node_modules/jquery/dist/jquery.min.js',
        'node_modules/swipejs/build/swipe.min.js',
        'node_modules/magnific-popup/dist/jquery.magnific-popup.min.js',
        'resources/js/app.js',
    ], 'public/js/script.js')