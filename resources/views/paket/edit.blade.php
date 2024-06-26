@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Edit Paket</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('paket.update', $paket->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="tipe">Tipe</label>
                    <select class="form-control" id="tipe" name="tipe" required>
                        <option value="Reguler" {{ $paket->tipe == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                        <option value="Bisnis" {{ $paket->tipe == 'Bisnis' ? 'selected' : '' }}>Bisnis</option>
                        <option value="Apartemen" {{ $paket->tipe == 'Apartemen' ? 'selected' : '' }}>Apartemen</option>
                        <option value="Gamer" {{ $paket->tipe == 'Gemer' ? 'selected' : '' }}>Other</option>
                        <option value="Dedicated" {{ $paket->tipe == 'Dedicated' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="{{ $paket->nama }}"
                        required>
                </div>
                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" class="form-control" id="harga" name="harga" value="{{ $paket->harga }}"
                        required>
                </div>
                <div class="form-group">
                    <label for="diskon">Diskon</label>
                    <input type="number" class="form-control" id="diskon" name="diskon" value="{{ $paket->diskon }}"
                        required>
                </div>
                <div class="form-group">
                    <label for="speed">Speed</label>
                    <input type="text" class="form-control" id="speed" name="speed" value="{{ $paket->speed }}"
                        required>
                </div>
                <div class="form-group">
                    <label for="qty">Quantity</label>
                    <input type="number" class="form-control" id="qty" name="qty" value="{{ $paket->qty }}"
                        required>
                </div>
                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" class="form-control" id="image" name="image">
                    @if ($paket->image)
                        <img src="{{ asset('storage/' . $paket->image) }}" alt="Image" width="100">
                    @endif
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
@endsection
