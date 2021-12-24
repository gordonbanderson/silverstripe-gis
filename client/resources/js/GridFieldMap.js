jQuery(function($) {
    $.entwine('ss', function($) {
      $('.grid-field-map').entwine({
        onmatch: function() {

            var me = this,
                gridFieldUrl = this.closest('.ss-gridfield').data('url'),
                value = $(this).data('mapCenter'),
                coords = value.match(/^srid=(\d+);\w+\(([\d\.-\s]+)\)/i),
                epsg = coords[1],
                center = coords[2].split(' '),
                proj;

            if (epsg != '4326') {
                proj = proj4('EPSG:' + epsg);
                center = proj.inverse([center[1], center[0]]);
            }
            center = [center[1], center[0]];

            this.css({width:'100%', height:'400px'});
            
            var map = L.map(this[0], {
                fullscreenControl: true,
                fullscreenControlOptions: {
                    position: 'topleft'
                }
            }).setView(center, 13);

            map.on('enterFullscreen', function(e){
                $('.grid-field-map').css({ height:'100% !important'});
            });

            map.on('exitFullscreen', function(e){
                $('.grid-field-map').css({ height:'400px'});
            });

            var streets = L.tileLayer('//{s}.tile.osm.org/{z}/{x}/{y}.png').addTo(map);
            var satelite = L.tileLayer('//{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{
                maxZoom: 20,
                subdomains:['mt0','mt1','mt2','mt3']
            });

            var baseMaps = {
                "Streets": streets,
                "Satelite": satelite
            };

            var list = this.data('list'), clustered = L.geoJson();
            Object.keys(list).forEach(function(key,index) {
                clustered.addData({
                    type: 'Feature',
                    properties: {
                      ID: key,
                      Title: list[key][0]
                    },
                    'geometry': {
                        type: list[key][1],
                        coordinates: list[key][2]
                    }
                });
            })

            var data = L.markerClusterGroup()
                .addLayer(clustered)
                .bindPopup(function (layer) {
                    return '<a href="' + gridFieldUrl + '/item/' + layer.feature.properties.ID + '">' + layer.feature.properties.Title + '</a>';
                })
                .addTo(map);

            if (Object.keys(list).length) map.fitBounds(data.getBounds());

            L.control.layers(baseMaps, { "Data": data }).addTo(map);


            map.addControl(new L.Control.Search({
              url: '//nominatim.openstreetmap.org/search?format=json&q={s}',
              jsonpParam: 'json_callback',
              propertyName: 'display_name',
              propertyLoc: ['lat','lon'],
              marker: false,
              autoCollapse: true,
              autoType: false,
              minLength: 2
            }));

        }
      });
    });
});
