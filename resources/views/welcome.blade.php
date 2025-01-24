<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div style="position: relative;">
        <!-- Tulisan di atas peta (kanan atas) -->
        <p
            style="position: absolute; top: 10px; right: 10px; z-index: 1000; 
                  background: rgba(255, 255, 255, 0.8); padding: 10px; 
                  border-radius: 5px; font-size: 18px; font-weight: bold;">
            Sumber Data: PUPR
        </p>

        <!-- Peta -->
        <div id="map" style="height: 600px;"></div>
    </div>

    <script>
        // Inisialisasi peta
        var map = L.map('map').setView([-0.3155398750904368, 117.1371634207888], 5);

        // Tambahkan layer peta
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 13,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Buat ikon kustom
        var customIcon = L.icon({
            iconUrl: '{{ asset('img/gate.png') }}', // Path gambar ikon menggunakan helper Laravel
            iconSize: [20, 20], // Ukuran ikon
            // iconAnchor: [25, 50],                    // Anchor ikon
            // popupAnchor: [0, -50]                    // Popup posisi relatif ke ikon
        });

        // Muat data GeoJSON
        let datas = {!! file_get_contents('https://data.pu.go.id/sites/default/files/geojson/ast_bpjt_gerbangtol.geojson') !!};
        console.log(datas);

        // Tambahkan data GeoJSON ke peta dengan marker kustom
        L.geoJSON(datas, {
            pointToLayer: function(feature, latlng) {
                // Setiap titik GeoJSON menggunakan ikon kustom
                return L.marker(latlng, {
                    icon: customIcon
                });
            },
            onEachFeature: function(feature, layer) {
                // Tambahkan popup ke setiap marker
                if (feature.properties && feature.properties.nama) {
                    layer.bindPopup(`
                        <b>Gerbang Tol:</b> ${feature.properties.nama} <br>
                        <b>Pengelola:</b> ${feature.properties.bujt} <br>
                        <b>Region:</b> ${feature.properties.region} <br>
                        <b>Ruas:</b> ${feature.properties.ruas} <br>
                        <b>Jumlah Gardu:</b> ${feature.properties.jml_gardu} <br>
                        <b>Status:</b> ${feature.properties.status}
                    `);
                }
            }
        }).addTo(map);
    </script>
</x-layout>
