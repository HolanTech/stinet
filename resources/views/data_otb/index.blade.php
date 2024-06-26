@extends('layouts.admin')

@section('content')
    <div class="d-flex align-items-center justify-content-between">
        <button onclick="saveData()" class="btn btn-primary me-2 w-25">Simpan Data</button>
        <button id="export-file" class="btn btn-success me-2 w-25">Download CSV</button>
        <button id="showOnMap" class="btn btn-secondary me-2 w-25">Show On Map</button>
        <div class="d-flex align-items-center justify-content-between col-6">
            <input type="text" class="form-control me-2" id="inputSite1" value="{{ $site1 ?? '' }}" name="site1"
                placeholder="Masukkan Site 1">
            <input type="text" class="form-control" id="inputSite2" value="{{ $site2 ?? '' }}" name="site2"
                placeholder="Masukkan Site 2">
        </div>
    </div>

    <div id="hot" style="width: 100%; height: 400px; overflow: hidden"></div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        let hot; // Memindahkan definisi 'hot' ke scope yang lebih luas
        const showMapRoute = "{{ route('data_otb.map') }}";

        document.addEventListener("DOMContentLoaded", function() {
            const container = document.getElementById('hot');
            const exportButton = document.getElementById('export-file');
            const showOnMapButton = document.getElementById('showOnMap');
            const site1Input = document.getElementById('inputSite1');
            const site2Input = document.getElementById('inputSite2');

            hot = new Handsontable(container, {
                data: JSON.parse(`{!! $jsonData !!}`),
                mergeCells: JSON.parse(`{!! $jsonMergeCells !!}`),
                colHeaders: true,
                rowHeaders: true,
                contextMenu: true,
                manualRowMove: true,
                manualColumnMove: true,
                dropdownMenu: true,
                filters: true,
                height: 'auto',
                autoWrapRow: true,
                multiColumnSorting: true,
                licenseKey: 'non-commercial-and-evaluation',
            });

            exportButton.addEventListener('click', () => {
                const exportPlugin = hot.getPlugin('exportFile');
                exportPlugin.downloadFile('csv', {
                    bom: false,
                    columnDelimiter: ';',
                    columnHeaders: true,
                    exportHiddenColumns: true,
                    exportHiddenRows: true,
                    fileExtension: 'csv',
                    filename: 'Handsontable-CSV-file_[YYYY]-[MM]-[DD]',
                    mimeType: 'text/csv',
                    rowDelimiter: '\\r\\n',
                    rowHeaders: false
                });
            });

            showOnMapButton.addEventListener('click', () => {
                showOnMap(site1Input.value, site2Input.value);
            });
        });

        function showOnMap(site1, site2) {
            const params = new URLSearchParams({
                site1,
                site2
            }).toString();
            const fullUrl = `${showMapRoute}?${params}`;
            window.open(fullUrl, '_blank'); // Assuming you want to open the map in a new tab
        }

        function saveData() {
            if (!hot) {
                console.error('Handsontable instance is not initialized.');
                return;
            }

            const data = hot.getData();
            const mergeCells = hot.getPlugin('mergeCells').mergedCellsCollection.mergedCells;
            const site1 = document.getElementById('inputSite1').value;
            const site2 = document.getElementById('inputSite2').value;

            axios.post('{{ route('data_otb.store') }}', {
                    data: data,
                    mergeCells: mergeCells,
                    site1: site1,
                    site2: site2,
                }, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(function(response) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Data berhasil disimpan.',
                        icon: 'success',
                        confirmButtonText: 'Oke'
                    });
                })
                .catch(function(error) {
                    console.error('Error saving data:', error);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menyimpan.',
                        icon: 'error',
                        confirmButtonText: 'Oke'
                    });
                });
        }
    </script>
@endpush
