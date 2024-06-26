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
                    <!-- Removed the card-header comment to clean up the code. If you want a header, you can uncomment and use it. -->
                    <div class="card-body">
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <style>
                            .table-rapat td,
                            .table-rapat th {
                                padding: .15rem;
                                /* Atur padding sesuai kebutuhan */
                            }
                        </style>
                        <table class="table table-borderless table-rapat">
                            <tbody>
                                <tr>
                                    <td style="width:3%"><img src="{{ asset('assets/marker/black.png') }}" width="20px">
                                    </td>
                                    <td style="width:57%"><small style="color:black">Customer Perusahaan</small></td>
                                    <td style="width:15%"><small style="color:rgb(0, 0, 0)">Garis Warna Hitam </small></td>
                                    <td style="width:35%"><small style="color:rgb(0, 0, 0)">: Kabel Relokasi </small></td>
                                </tr>
                                <tr>
                                    <td style="width:3%"><img src="{{ asset('assets/marker/red.png') }}" width="14px">
                                    </td>
                                    <td style="width:57%"><small style="color:red">Customer Perumahan</small></td>
                                    <td style="width:15%"><small style="color:red">Garis Warna Merah</small></td>
                                    <td style="width:35%"><small style="color:red">: Area Relokasi</small></td>
                                </tr>
                                <tr>
                                    <td style="width:3%"><img src="{{ asset('assets/marker/green.png') }}" width="10px">
                                    </td>
                                    <td style="width:57%"><small style="color:rgb(19, 255, 19)">Customer Site</small></td>
                                    <td style="width:15%"><small style="color:rgb(19, 255, 19)">Garis Warna Hijau</small>
                                    </td>
                                    <td style="width:35%"><small style="color:rgb(19, 255, 19)">: Kabel Customer</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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

        // Lanjutkan dengan kode penambahan marker dan polyline Anda di sini...

        var blackIcon = L.icon({
            iconUrl: 'assets/marker/black.png',
            iconSize: [9, 15], // size of the icon
            iconAnchor: [4, 15], // point of the icon which will correspond to marker's location
            popupAnchor: [0, -15] // point from which the popup should open relative to the iconAnchor
        });

        var greenIcon = L.icon({
            iconUrl: 'assets/marker/green3.png',
            iconSize: [8, 15], // size of the icon
            iconAnchor: [4, 15], // point of the icon which will correspond to marker's location
            popupAnchor: [0, -20] // point from which the popup should open relative to the iconAnchor
        });
        var redIcon = L.icon({
            iconUrl: 'assets/marker/red.png',
            iconSize: [8, 15], // size of the icon
            iconAnchor: [4, 15], // point of the icon which will correspond to marker's location
            popupAnchor: [0, -20] // point from which the popup should open relative to the iconAnchor
        });

        //black markers
        var blackMarkers = [
            [-6.401282100644635, 106.86994990386715],
            [-6.4016513349379265, 106.86986715840095],
            [-6.40189332922461, 106.86963648201377],
            [-6.402386731083103, 106.8694078412942],
            [-6.402969975548593, 106.86924197678343],


        ];
        blackMarkers.forEach(function(point) {
            L.marker(point, {
                icon: blackIcon
            }).addTo(map);
        });

        //green markers
        var greenMarkers = [
            [-6.407474037336285, 106.86832805256157],
            [-6.407594488851647, 106.86892771656207],
            [-6.406681592447343, 106.86921478975381],
            [-6.406034956506123, 106.86936789545604],
            [-6.406230914886554, 106.87008962947885],
            [-6.406470374820399, 106.87039154875188],

        ];
        greenMarkers.forEach(function(point) {
            L.marker(point, {
                icon: greenIcon
            }).addTo(map);
        });

        //red markers
        var redMarkers = [
            [-6.401587756567166, 106.87396439556156],
            [-6.401944931442587, 106.87405559067153],
            [-6.402307437021314, 106.87470468527478],
            [-6.403133735517512, 106.87445792203876],
            [-6.4039580944211885, 106.87448328056252],
            [-6.405027755715378, 106.8745401126674],
            [-6.405484205600966, 106.87443804219924],
            [-6.405408130648375, 106.87391493104987],
            [-6.405801184447994, 106.87380010177318],
            [-6.405725109542654, 106.87314940253859],
            [-6.405344734845896, 106.87251146211253],
            [-6.405040434884381, 106.8724349092614],
        ];
        redMarkers.forEach(function(point) {
            L.marker(point, {
                icon: redIcon
            }).addTo(map);
        });
        // Black markers dengan pop-up
        blackMarkers.forEach(function(point) {
            var marker = L.marker(point, {
                icon: blackIcon
            }).bindPopup('Lokasi: ' + point[0] + ', ' + point[1]).addTo(map);
        });


        // Green markers dengan pop-up
        greenMarkers.forEach(function(point) {
            var marker = L.marker(point, {
                icon: greenIcon
            }).bindPopup('Lokasi: ' + point[0] + ', ' + point[1]).addTo(map);
        });

        // Red markers dengan pop-up
        redMarkers.forEach(function(point) {
            var marker = L.marker(point, {
                icon: redIcon
            }).bindPopup('Lokasi: ' + point[0] + ', ' + point[1]).addTo(map);
        });


        // Drawing polylines
        var blackLine = L.polyline(blackMarkers, {
            color: 'rgb(19, 255, 19)'
        }).addTo(map);
        var greenLine = L.polyline(greenMarkers, {
            color: 'rgb(19, 255, 19)'
        }).addTo(map);
        var redLine = L.polyline(redMarkers, {
            color: 'rgb(19, 255, 19)'
        }).addTo(map);


        // Optionally, fit map bounds to show all markers and polylines
        var group = new L.featureGroup([blackLine, greenLine, redLine]);
        map.fitBounds(group.getBounds());

        // Pastikan Leaflet.js sudah diinclude dalam project Anda

        // Definisikan titik-titik untuk polyline
        var pathPoints = [
            [-6.406230914886554, 106.87008962947885],
            [-6.406470374820399, 106.87039154875188],
            [-6.407044166587772, 106.87060807290715],
            [-6.407097959531331, 106.87079753154768],
            [-6.406999339130471, 106.87086970626788],
            [-6.406577960839345, 106.8708787281079],
            [-6.406398650822683, 106.87109525226852],
            [-6.40617451320501, 106.87127568906466],
            [-6.405582789443119, 106.87145612586517],
            [-6.405340720433692, 106.87163656266567],
            [-6.405179341030285, 106.87192526154645],
            [-6.4051788692842955, 106.87192655910764],
            [-6.405040434884381, 106.8724349092614],
            // Tambahkan lebih banyak titik sesuai kebutuhan
        ];

        var path2Points = [
            [-6.407454894537179, 106.86831015450299],
            [-6.404213691820096, 106.86925429210166],
            [-6.40353221951535, 106.86940760551637],
            [-6.403499800494956, 106.86933046046761],
            [-6.403041338597384, 106.8694538420856],
            [-6.402969975548593, 106.86924197678343],
        ];

        var pathredPoints = [
            [-6.401764793845812, 106.87751429111849],
            [-6.401840185112971, 106.87491973105428],
            [-6.401327524276796, 106.87312933288135],
            [-6.401010880562066, 106.87291691275914],
            [-6.401101350214882, 106.87190033074567],
            [-6.401297367740997, 106.86983682098703],
            [-6.400633923481131, 106.86900231337263],
        ];

        var pathgreenPoints = [
            [-6.401657104481575, 106.86986426758016],
            [-6.401745859243947, 106.87009392613353],
            [-6.402247297741571, 106.87004924111783],
            [-6.402292532460464, 106.87047408136225],
            [-6.402850426997259, 106.87042856276463],
            [-6.403455536299501, 106.87016384134498],
            [-6.403734478803924, 106.87078902296251],
            [-6.4038866291966325, 106.8712100636437],
            [-6.40420360903579, 106.87112075198405],
            [-6.404406476029562, 106.87267732662366],
            [-6.404533267859732, 106.8727666382833],
            [-6.404710776369041, 106.87317492015598],
            [-6.403493573922712, 106.8733152670497],
            [-6.402961046939847, 106.87311112611339],
            [-6.402821575495397, 106.87387665462465],
            [-6.401959387538585, 106.87386389581613],
            [-6.4019340290472275, 106.8740297603269],
        ];

        var pathgreen2Points = [
            [-6.401587756567166, 106.87396439556156],
            [-6.401327524276791, 106.87312933288133],
            [-6.4013162380060935, 106.87307719771691],
            [-6.4010339858205665, 106.87289148730643],
            [-6.40130053453338, 106.86994105731063],
        ];



        // Menggambar polyline dan menambahkannya ke peta
        var polyline = L.polyline(pathPoints, {
            color: 'rgb(19, 255, 19)'
        }).addTo(map);
        var polyline2 = L.polyline(path2Points, {
            color: 'rgb(19, 255, 19)'
        }).addTo(map);
        var polyline3 = L.polyline(pathredPoints, {
            color: 'red'
        }).addTo(map);
        var polyline4 = L.polyline(pathgreenPoints, {
            color: 'black'
        }).addTo(map);
        var polyline5 = L.polyline(pathgreen2Points, {
            color: 'rgb(19, 255, 19)'
        }).addTo(map);

        // Fit map bounds untuk menampilkan semua polyline bersamaan
        var group = new L.featureGroup([polyline, polyline2, polyline3, polyline4, polyline5]);
        map.fitBounds(group.getBounds());

        function locateUser() {
            map.locate({
                setView: true,
                maxZoom: 16
            });
        }

        map.on('locationfound', function(e) {
            L.marker(e.latlng).addTo(map)
                .bindPopup("Lokasi Anda saat ini").openPopup();
        });

        map.on('locationerror', function(e) {
            alert("Lokasi tidak ditemukan. Pastikan Anda telah mengizinkan akses lokasi.");
        });

        // Tambahkan tombol lokasi
        var locateButton = L.control({
            position: 'topleft'
        });

        locateButton.onAdd = function(map) {
            var div = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
            div.innerHTML =
                '<button onclick="locateUser()" style="background-color: white; border: none; cursor: pointer;"><i class="fa fa-crosshairs"></i></button>';
            return div;
        };

        locateButton.addTo(map);
    </script>
@endpush
