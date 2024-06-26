@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header bg-primary">
            <h3 class="card-title">Tambah Paket</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('paket.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="tipe">Tipe</label>
                    <select class="form-control" id="tipe" name="tipe" required>
                        <option value="Reguler">Reguler</option>
                        <option value="Bisnis">Bisnis</option>
                        <option value="Apartemen">Apartemen</option>
                        <option value="Gamer">Gamer</option>
                        <option value="Dedicated">Dedicated</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" required>
                </div>
                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" class="form-control" id="harga" name="harga" required>
                </div>
                <div class="form-group">
                    <label for="diskon">Diskon</label>
                    <input type="number" class="form-control" id="diskon" name="diskon" required>
                </div>
                <div class="form-group">
                    <label for="speed">Speed</label>
                    <input type="text" class="form-control" id="speed" name="speed" required>
                </div>
                <div class="form-group">
                    <label for="qty">Quantity</label>
                    <input type="number" class="form-control" id="qty" name="qty" required>
                </div>
                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" class="form-control" id="image" name="image" required>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
@endsection
