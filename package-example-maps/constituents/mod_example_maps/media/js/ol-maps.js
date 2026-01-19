(function () {
    // Get the data passed down from all the map modules set on the site page
    // and draw a map for each module
    let mapsdata = Joomla.getOptions('mod_example_maps.mapdata');
    mapsdata.forEach(function(mapdata) {
        drawMap(mapdata);
    });

    function drawMap(mapdata) {

        // To draw a map, Openlayers needs:
        // 1. a target HTML element into which the map is put
        // 2. a map layer, which can be eg a Vector layer with details of polygons for
        //    country boundaries, lines for roads, etc, or a Tile layer, with individual
        //    .png files for each map tile (256 by 256 pixel square). We use a Tile layer.
        // 3. a view, specifying the 2D projection of the map (default Spherical Mercator),
        //    map centre coordinates and zoom level

        // we use raster Tile layers, and need to specify a source for the tiles
        let mapTile = new ol.layer.Tile({  // use the OSM server
            source: new ol.source.OSM()
        });

        // to use Thunderforest maps uncomment the following lines - you will need an API key from https://www.thunderforest.com/pricing/
        /* 
        const plugin_vars = Joomla.getOptions('plg_example_maps.apikey');
        const thunderforest_API_key = plugin_vars.apikey;
        mapTile = new ol.layer.Tile({
            source: new ol.source.XYZ(
            {
                url : "https://tile.thunderforest.com/cycle/{z}/{x}/{y}.png?apikey=" + thunderforest_API_key,

                attributions: 'Maps &#169; <a href="https://www.thunderforest.com" target="_blank">Thunderforest</a>, Data &#169; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a> contributors',
            })
        });
        */

        // Use the module config lat, long and zoom values, plus the tile layer we've defined, to create the map
        const x = parseFloat(mapdata.long);
        const y = parseFloat(mapdata.lat);
        const mapCentre = ol.proj.fromLonLat([x, y]);

        const map = new ol.Map({
            target: mapdata.id,
            layers: [
                mapTile
            ],
            view: new ol.View({
                center: mapCentre,
                zoom: mapdata.zoom
            })
        });
        
        // To add a marker pin to a map you need to specify a Feature with:
        //    - the position of the Feature
        //    - the style representing how the Feature is displayed
        // The Features are grouped into a VectorSource,
        // which is added to the map via a Vector Layer

        // Show the marker pins as blue crosses, so set up blue cross as a style to use later
        const blueStroke = new ol.style.Stroke({
            color: 'blue',
            width: 3
        });
        const crossShape = new ol.style.RegularShape({
            stroke: blueStroke,
            points: 4,
            radius: 10,
            radius2: 0,
            angle: 0,
        });
        const crossStyle = new ol.style.Style({image: crossShape});

        // now create Features for each of the marker pins defined in the Joomla module config
        let point;
        const numPins = Object.keys(mapdata.pins).length;
        const features = new Array(numPins);
        let i = 0;
        for (const [key, value] of Object.entries(mapdata.pins)) {
            point = ol.proj.fromLonLat([parseFloat(value.pinlong), parseFloat(value.pinlat)]);
            features[i] = new ol.Feature({geometry: new ol.geom.Point(point)});
            features[i].setStyle(crossStyle);
            i++;
        }

        // now we add the features to the map via a Vector source and Vector layer
        const vectorSource = new ol.source.Vector({
            features: features
        });
        const vector = new ol.layer.Vector({
            source: vectorSource
        });
        map.addLayer(vector);
    }

})();