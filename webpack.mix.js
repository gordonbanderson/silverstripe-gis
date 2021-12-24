const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.combine([
    'node_modules/leaflet/dist/leaflet.js',
    'node_modules/leaflet-search/src/leaflet-search.js',
    'node_modules/proj4/dist/proj4.js',
    'node_modules/leaflet.fullscreen/Control.FullScreen.js'
], 'client/dist/js/mapfield-common.js')

.combine([
    'node_modules/leaflet/dist/leaflet.css',
    'node_modules/leaflet-search/src/leaflet-search.css',
    'node_modules/leaflet.fullscreen/Control.FullScreen.css'
], 'client/dist/css/mapfield-common.css')

.combine([
    'node_modules/leaflet-draw/dist/leaflet.draw-src.js',
    'node_modules/wicket/wicket.js',
    'client/resources/js/MapField.js',
    ], 'client/dist/js/mapfield.js')

    .combine([
        'node_modules/leaflet-draw/dist/leaflet.draw-src.css',
        'client/resources/css/MapField.css'
    ], 'client/dist/css/mapfield.css')


    .combine([
        'node_modules/leaflet.markercluster/dist/leaflet.markercluster-src.js',
        'client/resources/js/GridFieldMap.js',
    ], 'client/dist/js/gridfieldmap.js')

    .combine([
        'node_modules/leaflet.markercluster/dist/MarkerCluster.css',
        'node_modules/leaflet.markercluster/dist/MarkerCluster.Default.css',
    ], 'client/dist/css/gridfieldmap.css')

    .copyDirectory('node_modules/leaflet-draw/dist/images', 'client/dist/css/images')
    .copyDirectory('node_modules/leaflet-search/images/', 'client/dist/images')
    .copy('node_modules/leaflet.fullscreen/icon-fullscreen.svg', 'client/dist/css')
;
