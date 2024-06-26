@extends('layouts.admin')

@section('content')
    <style>
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, .1);
        }

        .card-header {
            border-radius: 15px 15px 0 0;
            font-weight: bold;
        }

        .btn-custom {
            border-radius: 20px;
            padding: 10px 80px;
            font-weight: bold;
            font-size: 16px;
            /* Adjust the font size for better readability */
            margin: 5px;
            /* Adds some space between buttons */
        }

        .select2-container--bootstrap4 .select2-selection {
            border-radius: 20px;
            height: 38px;
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            line-height: 36px;
        }

        .gradient-custom-1 {
            background: linear-gradient(to right, #2f7af2, #61bfeb);
        }

        .gradient-custom-2 {
            background: linear-gradient(to right, #f41bb6, #5a91f0);
        }

        .gradient-custom-3 {
            background: linear-gradient(to right, #ffecd2, #fcb69f);
        }
    </style>

    <div class="container mt-3">
        <div class="row">
            <div class="col-lg-6">
                <!-- Card Input Site To Site -->
                <div class="card">
                    <div class="card-header gradient-custom-1 text-white">
                        <h5>Input Site To Site</h5>
                    </div>
                    <div class="card-body">
                        <form id="dynamicForm" action="" method="POST">
                            @csrf
                            <div class="mb-3">
                                {{-- <div class="my-2">Site 1</div> --}}
                                <input type="text" class="form-control" id="inputSite1" name="site1"
                                    placeholder="Input Site 1" aria-label="Site 1">
                            </div>
                            <div class="mb-3">
                                {{-- <div class="my-2">Site 1</div> --}}
                                <input type="text" class="form-control" id="inputSite2" name="site2"
                                    placeholder="Input Site 2" aria-label="Site 2">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <!-- Card Already Site -->
                <div class="card">
                    <div class="card-header gradient-custom-2 text-white">
                        <h5>Already Site</h5>
                    </div>
                    <div class="card-body">
                        <!-- Dropdown untuk Site1 -->
                        {{-- <div class="my-2">Site 1</div> --}}
                        <select class="form-control select2" id="alreadySite1" name="alreadySite1"
                            aria-label="Already Site 1">
                            <option value="">Select or Type</option>
                            @foreach ($allSites as $site)
                                <option value="{{ $site }}">{{ $site }}</option>
                            @endforeach
                        </select>
                        <br>
                        <!-- Dropdown untuk Site2 -->
                        {{-- <div class="my-2">Site 2</div> --}}
                        <select class="form-control select2" id="alreadySite2" name="alreadySite2"
                            aria-label="Already Site 2">
                            <option value="">Select or Type</option>
                            @foreach ($allSites as $site)
                                <option value="{{ $site }}">{{ $site }}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
            </div>

            <div class="card col-12 mt-0">
                <div class="card-body d-flex justify-content-between">
                    <button type="button" id="showDataOTB" class="btn  btn-custom btn-success "><i
                            class="fas fa-search"></i>
                        Show Data
                        OTB</button>
                    <button type="button" id="showAssets" class="btn  btn-custom btn-warning "><i
                            class="fas fa-briefcase"></i> Show
                        Assets</button>
                    <button type="button" id="showSpliceConfig" class="btn btn-custom  btn-danger "><i
                            class="fas fa-tools"></i> Show
                        Splice Config</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialization for Select2
            $('.select2').select2({
                tags: true,
                placeholder: "Select or type",
                allowClear: true,
                theme: 'bootstrap4'

            });

            // Function to change form action and submit with both inputs
            function changeFormActionAndSubmit(newAction, requireBoth = true) {
                var form = document.getElementById('dynamicForm');
                var inputSite1 = document.getElementById('inputSite1').value;
                var inputSite2 = document.getElementById('inputSite2').value;
                var alreadySite1 = $('#alreadySite1').val();
                var alreadySite2 = $('#alreadySite2').val();

                var site1Value = inputSite1 ? inputSite1 : alreadySite1;
                var site2Value = inputSite2 ? inputSite2 : alreadySite2;

                // For "Show Assets", proceed if at least one field is filled
                if (!requireBoth || (site1Value && site2Value)) {
                    document.getElementById('inputSite1').value = site1Value;
                    document.getElementById('inputSite2').value = site2Value;

                    form.action = newAction;
                    form.method = 'GET'; // Adjust if you need a different method
                    form.submit();
                } else {
                    alert(
                        "Please fill in both Site 1 and Site 2 fields for other actions, or at least one for Show Assets."
                    );
                }
            }

            // Button event listeners
            document.getElementById('showDataOTB').addEventListener('click', function() {
                changeFormActionAndSubmit('{{ route('data_otb.index') }}', true);
            });

            document.getElementById('showAssets').addEventListener('click', function() {
                changeFormActionAndSubmit('{{ route('asset.index') }}', false);
            });

            document.getElementById('showSpliceConfig').addEventListener('click', function() {
                changeFormActionAndSubmit('#', true);
            });
        });
    </script>
@endpush
