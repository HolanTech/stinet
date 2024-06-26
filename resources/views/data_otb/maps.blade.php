@extends('layouts.admin')

@section('content')
    <div id="map" style="height: 500px;"></div>
    {{-- Sisanya dari konten halaman Anda --}}
@endsection

@push('script')
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var polylinePoints = [];



            var map = L.map('map').setView([{{ $siteA['lat'] }}, {{ $siteA['lng'] }}], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            // Fungsi untuk menangani klik pada peta dan menambahkan titik
            map.on('click', function(e) {
                var latlng = e.latlng;
                L.marker(latlng).addTo(map); // Menambahkan marker
                polylinePoints.push(latlng);

                if (polylinePoints.length > 1) {
                    L.polyline(polylinePoints, {
                        color: 'red'
                    }).addTo(map); // Menggambar garis antara titik
                }
            });
            var siteAIcon = L.icon({
                iconUrl: '{{ asset('assets/marker/otb.png') }}',
                iconSize: [15, 20],
                iconAnchor: [7.5, 10],
                popupAnchor: [-3, -10]
            });

            var siteBIcon = L.icon({
                iconUrl: '{{ asset('assets/marker/otb.png') }}',
                iconSize: [15, 20],
                iconAnchor: [7.5, 10],
                popupAnchor: [-3, -10]
            });

            L.marker([{{ $siteA['lat'] }}, {{ $siteA['lng'] }}], {
                    icon: siteAIcon
                })
                .addTo(map)
                .bindPopup("{{ $siteA['name'] }}");

            L.marker([{{ $siteB['lat'] }}, {{ $siteB['lng'] }}], {
                    icon: siteBIcon
                })
                .addTo(map)
                .bindPopup("{{ $siteB['name'] }}");

            // Gambar rute dari SITE A ke SITE B
            drawRoute(map, {{ $siteA['lat'] }}, {{ $siteA['lng'] }}, {{ $siteB['lat'] }}, {{ $siteB['lng'] }});
        });

        function drawRoute(map, startLat, startLng, endLat, endLng) {
            var directionsServiceUrl =
                `https://api.mapbox.com/directions/v5/mapbox/driving/${startLng},${startLat};${endLng},${endLat}?geometries=geojson&access_token=pk.eyJ1IjoiaG9sYW50ZWNoIiwiYSI6ImNsdDVvZHFmaDAyMmQya3B0eWFzMHEzdGYifQ.X0oayz2VsQU132RBaQgzaw`;

            fetch(directionsServiceUrl)
                .then(response => response.json())
                .then(data => {
                    var coords = data.routes[0].geometry.coordinates;
                    var latlngs = coords.map(coord => [coord[1], coord[0]]);
                    var polyline = L.polyline(latlngs, {
                        color: 'blue'
                    }).addTo(map);
                    map.fitBounds(polyline.getBounds());
                })
                .catch(error => console.log('Error fetching route', error));
        }
    </script>
@endpush
