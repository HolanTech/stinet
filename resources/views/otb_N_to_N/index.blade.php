@extends('layouts.admin')

@section('content')
    <div id="hot"></div>
    <button onclick="saveData()">Simpan Data</button>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Mendefinisikan `hot` secara global
        var hot;
        var storeUrl = "{{ route('data_otb.store') }}"; // Menggunakan helper `route` Laravel untuk menghasilkan URL

        document.addEventListener("DOMContentLoaded", function() {
            var data = [
                ['', '', '', '', '', '', '', '', '', ''],
            ];
            var container = document.getElementById('hot');
            hot = new Handsontable(container, {
                data: data,
                rowHeaders: true,
                colHeaders: true,
                filters: true,
                dropdownMenu: true,
                contextMenu: true,
                manualRowResize: true,
                manualColumnResize: true,
                licenseKey: 'non-commercial-and-evaluation'
            });
        });

        function saveData() {
            if (hot) {
                var data = hot.getData();
                axios.post(storeUrl, {
                        data: data
                    })
                    .then(function(response) {
                        console.log(response.data);
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            } else {
                console.error('Handsontable instance is not initialized.');
            }
        }
    </script>
@endpush
