@extends('layouts.admin')

@section('content')
    <div class="text-center">
        <div class="card mt-1">
            <strong>
                <h4>Add New Customer</h4>
            </strong>
        </div>
    </div>
    <div class="card">
        <form method="POST" action="{{ route('customer.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-6">
                    <div class="card-6">

                        <div class="row m-3">
                            <div class="col-12">
                                <input id="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                    value="{{ old('name', $data['name'] ?? '') }}" placeholder="Nama Pelanggan" required
                                    autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row m-3">
                            <div class="col-12">
                                <input id="no_hp" type="text"
                                    class="form-control @error('no_hp') is-invalid @enderror" name="no_hp"
                                    value="{{ old('no_hp', $data['phone'] ?? '') }}" placeholder="Nomor Handphone" required
                                    autocomplete="no_hp">
                                @error('no_hp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row m-3">
                            <div class="col-12">
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email', $data['email'] ?? '') }}" placeholder="Email" required
                                    autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row m-3">
                            <div class="col-12">
                                <input id="lokasi" type="text"
                                    class="form-control @error('lokasi') is-invalid @enderror" name="lokasi"
                                    value="{{ old('lokasi') }}" placeholder="Latitude & Longitude lokasi" required
                                    autocomplete="lokasi">
                                @error('lokasi')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row m-3">
                            <div class="col-12">
                                <select name="paket" id="paket"
                                    class="form-control @error('paket') is-invalid @enderror" required>
                                    <option value="">Pilih Paket</option>
                                    @foreach ($pakets as $paket)
                                        <option value="{{ $paket->id }}">{{ $paket->tipe }}: {{ $paket->speed }} Mbps
                                        </option>
                                    @endforeach
                                </select>
                                @error('paket')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row m-3">
                            <div class="col-12">
                                <input id="date" type="date"
                                    class="form-control @error('date') is-invalid @enderror" name="date"
                                    value="{{ old('date') }}" required autocomplete="date">
                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row m-3">
                            <div class="col-12 input-group">
                                <textarea name="address" id="address" cols="30" rows="4" placeholder="Masukkan Alamat Lengkap"
                                    class="form-control @error('address') is-invalid @enderror" required autocomplete="address">{{ old('address', $data['alamat'] ?? '') }}</textarea>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-6">
                    <div class="card-6">
                        <div class="row m-3">
                            <div class="col-12">
                                <input id="merk_ont" type="text"
                                    class="form-control @error('merk_ont') is-invalid @enderror" name="merk_ont"
                                    value="{{ old('merk_ont') }}" placeholder="Masukkan Merek Ont" required
                                    autocomplete="merk_ont">
                                @error('merk_ont')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row m-3">
                            <div class="col-12">
                                <input id="sn_ont" type="text"
                                    class="form-control @error('sn_ont') is-invalid @enderror" name="sn_ont"
                                    value="{{ old('sn_ont') }}" placeholder="Masukkan Serial Number ONT" required
                                    autocomplete="sn_ont">
                                @error('sn_ont')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row m-3">
                            <div class="col-12">
                                <input id="dw" type="text"
                                    class="form-control @error('dw') is-invalid @enderror" name="dw"
                                    value="{{ old('dw') }}" placeholder="Masukkan Panjang Kabel DW yang dipakai"
                                    required autocomplete="dw">
                                @error('dw')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row m-3">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="ktp">Foto KTP</label>
                                    <input id="ktp" type="file"
                                        class="form-control @error('ktp') is-invalid @enderror" name="ktp" required>
                                    @error('ktp')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <img id="ktpPreview" src="#" alt="Pratinjau KTP"
                                        style="display:none; margin-top: 10px; max-width: 100%; height: auto;">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="tempat">Foto Tempat</label>
                                    <input id="tempat" type="file"
                                        class="form-control @error('tempat') is-invalid @enderror" name="tempat"
                                        required>
                                    @error('tempat')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <img id="tempatPreview" src="#" alt="Pratinjau Tempat"
                                        style="display:none; margin-top: 10px; max-width: 100%; height: auto;">
                                </div>
                            </div>
                            <small>Ukuran Foto Maksimal 2 Mb</small>
                        </div>
                        <div class="row m-3">
                            <div class="col-12">
                                <input id="id" type="hidden"
                                    class="form-control @error('id') is-invalid @enderror" name="id"
                                    value="{{ old('id', $data['id'] ?? '') }}" placeholder="ID Pelanggan" required
                                    autocomplete="id" autofocus>
                                @error('id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row m-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100">
                                    {{ __('Save Changes') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('#paket').select2({
                placeholder: "Pilih Paket",
                allowClear: true
            });
        });
    </script>
    <script>
        document.getElementById('ktp').addEventListener('change', function() {
            readURL(this, 'ktpPreview');
        });

        document.getElementById('tempat').addEventListener('change', function() {
            readURL(this, 'tempatPreview');
        });

        function readURL(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var preview = document.getElementById(previewId);
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
