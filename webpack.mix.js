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
    'client/resources/js/leaflet.js',
    'client/resources/js/leaflet-search.js',
    'client/resources/js/leaflet.draw.1.0.4.js',
    'client/resources/js/proj4.js',
    'client/resources/js/wicket.js',
    'client/resources/js/MapField.js',
    ], 'client/dist/js/mapfield.js')

    .combine([
        'client/resources/css/leaflet.css',
        'client/resources/css/leaflet-search.css',
        'client/resources/css/leaflet.draw.1.0.4.css',
        'client/resources/css/MapField.css'
    ], 'client/dist/css/mapfield.css')


    .combine([
        'client/resources/js/leaflet.js',
        'client/resources/js/leaflet.markercluster.js',
        'client/resources/js/leaflet-search.js',
        'client/resources/js/proj4.js',
        'client/resources/js/GridFieldMap.js',
    ], 'client/dist/js/gridfieldmap.js')

    .combine([
        'client/resources/css/leaflet.css',
        'client/resources/css/MarkerCluster.css',
        'client/resources/css/MarkerCluster.Default.css',
        'client/resources/css/leaflet-search.css',
    ], 'client/dist/css/gridfieldmap.css')

;
