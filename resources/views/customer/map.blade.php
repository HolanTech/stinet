@extends('layouts.admin')

@section('content')
    <style>
        #map {
            height: 400px;
        }
    </style>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        <strong>Customers On Maps</strong>
                    </div>
                    <!-- Removed the card-header comment to clean up the code. If you want a header, you can uncomment and use it. -->
                    <div class="card-body">
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('script')
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" rel="stylesheet" /> --}}
    <script>
        // Fungsi untuk mendapatkan lokasi saat ini dan memfokuskan peta

        var customers = @json($customers);
        var map = L.map('map').setView([-6.4043810871534355, 106.87004348081192], 15);

        // Layer tampilan satelit
        var satellite = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            // attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 19,
            id: 'mapbox/satellite-v9',
            tileSize: 512,
            zoomOffset: -1,
            accessToken: 'pk.eyJ1IjoiaG9sYW50ZWNoIiwiYSI6ImNsdDVvZHFmaDAyMmQya3B0eWFzMHEzdGYifQ.X0oayz2VsQU132RBaQgzaw'
        });

        // Layer tampilan roadmap
        var roadmap = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            // attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 19,
            id: 'mapbox/streets-v11', // Ganti id untuk tampilan roadmap
            tileSize: 512,
            zoomOffset: -1,
            accessToken: 'pk.eyJ1IjoiaG9sYW50ZWNoIiwiYSI6ImNsdDVvZHFmaDAyMmQya3B0eWFzMHEzdGYifQ.X0oayz2VsQU132RBaQgzaw'
        });

        // Secara default menampilkan tampilan satelit
        satellite.addTo(map);

        // Menambahkan kontrol untuk beralih antara satelit dan roadmap
        var baseMaps = {
            "Satelit": satellite,
            "Roadmap": roadmap
        };
        L.control.layers(baseMaps).addTo(map);
        customers.forEach(function(customer) {
            L.marker([customer.latitude, customer.longitude], {
                icon: L.icon({
                    iconUrl: '{{ asset('assets/marker/green.png') }}',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34]
                })
            }).addTo(map).bindPopup("<b>" + customer.name + "</b><br>" + customer.address);
        });
    </script>
@endpush
