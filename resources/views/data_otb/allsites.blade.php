@extends('layouts.admin')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />

    <div id="map" style="height: 500px;"></div>
    {{-- Sisanya dari konten halaman Anda --}}
@endsection

@push('script')
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var sitePairs = @json($sitePairs);
            if (!sitePairs.length) return;

            var map = L.map('map').setView([sitePairs[0].siteA.lat, sitePairs[0].siteA.lng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            sitePairs.forEach(function(pair) {
                if (pair.siteA.lat != null && pair.siteA.lng != null && pair.siteB.lat != null && pair.siteB
                    .lng != null) {
                    // Implementasi Routing dengan jumlah waypoints yang dikurangi
                    L.Routing.control({
                        waypoints: [
                            L.latLng(pair.siteA.lat, pair.siteA.lng),
                            L.latLng(pair.siteB.lat, pair.siteB.lng)
                        ],
                        routeWhileDragging: true,
                        createMarker: function(i, waypoint) {
                            var markerOpts = {
                                icon: L.icon({
                                    iconUrl: '{{ asset('assets/marker/otb.png') }}', // Pastikan URL ini benar
                                    iconSize: [30, 40],
                                    iconAnchor: [15, 40],
                                    popupAnchor: [0, -40]
                                })
                            };
                            // Gunakan nama site dari objek pair
                            var siteName = i === 0 ? pair.siteA.name : pair.siteB.name;
                            return L.marker(waypoint.latLng, markerOpts).bindPopup(siteName);
                        }
                    }).addTo(map);
                }
            });
        });
    </script>
@endpush
