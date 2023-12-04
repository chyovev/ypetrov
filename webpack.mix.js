// webpack.mix.js
let mix = require('laravel-mix');

mix
    .options({ manifest: false });

// all local fonts & images referenced in the less files
// are automatically copied to the respective public folders
mix
    .less('resources/css/less/style.less', 'public/css/style.css');