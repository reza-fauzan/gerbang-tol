<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div id="map"></div>

    <script>
        var map = L.map('map').setView([-8.3513778, 115.0528291, 9], 9);

        var tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        const regencies = @json($regencies)

        const regencyData = regencies.map(regency => ({
            type: "Feature",
            properties: {
                name: regency.name,
                id: regency.id,
                jalan_kota: regency.jalan_kota,
            },
            geometry: {
                type: regency.type_polygon,
                coordinates: JSON.parse(regency.polygon),
            }
        }));

        const geoJson = {
            type: "FeatureCollection",
            features: regencyData,
        };

        function getColor(d) {
            return d > 120000 ? '#800026' :
                d > 100000 ? '#BD0026' :
                d > 80000 ? '#E31A1C' :
                d > 70000 ? '#FC4E2A' :
                d > 60000 ? '#FD8D3C' :
                d > 50000 ? '#FEB24C' :
                d > 40000 ? '#FED976' :
                '#FFEDA0';
        }

        function style(feature) {
            return {
                fillColor: getColor(feature.properties.jalan_kota),
                weight: 2,
                opacity: 1,
                color: 'white',
                dashArray: '3',
                fillOpacity: 0.7
            };
        }

        function highlightFeature(e) {
            var layer = e.target;

            layer.setStyle({
                weight: 5,
                color: '#666',
                dashArray: '',
                fillOpacity: 0.7
            });

            layer.bringToFront();
            info.update(layer.feature.properties);
        }

        function resetHighlight(e) {
            geojson.resetStyle(e.target);
            info.update();
        }

        function zoomToFeature(e) {
            map.fitBounds(e.target.getBounds());
        }

        function onEachFeature(feature, layer) {
            layer.on({
                mouseover: highlightFeature,
                mouseout: resetHighlight,
                click: zoomToFeature
            });
        }

        var info = L.control();

        info.onAdd = function(map) {
            this._div = L.DomUtil.create('div', 'info'); // create a div with a class "info"
            this.update();
            return this._div;
        };

        // method that we will use to update the control based on feature properties passed
        info.update = function(props) {
            this._div.innerHTML = '<h4>Panjang Jalan di Provinsi Bali (Km)</h4>' + (props ?
                '<b>' + props.name + '</b><br />' + props.jalan_kota.toLocaleString('id-ID') + ' km' :
                'Hover over a regency');
        };

        info.addTo(map);

        var legend = L.control({
            position: 'bottomright'
        });

        legend.onAdd = function(map) {

            var div = L.DomUtil.create('div', 'info legend'),
                grades = [0, 40000, 50000, 60000, 70000, 90000, 100000, 120000],
                labels = [];

            // loop through our density intervals and generate a label with a colored square for each interval
            for (var i = 0; i < grades.length; i++) {
                div.innerHTML +=
                    '<i style="background:' + getColor(grades[i] + 1) + '"></i> ' +
                    grades[i] + (grades[i + 1] ? '&ndash;' + grades[i + 1] + '<br>' : '+');
            }

            return div;
        };

        legend.addTo(map);

        geojson = L.geoJSON(geoJson, {
            style: style,
            onEachFeature: onEachFeature
        }).addTo(map);

        console.log("GeoJSON Features:", geoJson.features);
        geoJson.features.forEach(feature => {
            if (!feature.geometry.coordinates || feature.geometry.coordinates.length === 0) {
                console.error("Invalid coordinates found:", feature);
            }
        });
    </script>
</x-layout>
