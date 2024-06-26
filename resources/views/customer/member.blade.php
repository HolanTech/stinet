@extends('layouts.admin')

@section('content')
    <style>
        .vertical-center {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-button {
            border: none;
            background-color: transparent;
            cursor: pointer;
        }
    </style>
    <div class="card">
        <div class="card-header bg-primary vertical-center">
            <h3 class="card-title">Data Calon Pelanggan</h3>
        </div>
        <div class="card-body">
            <a href="{{ route('customer.create') }}" class="btn btn-primary">Tambah Data</a>
            <table id="datatable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>No Handphone</th>
                        <th>Email</th>
                        <th>Alamat</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($members as $customer)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->alamat }}</td>
                            <td>
                                <form action="{{ route('customer.createWithMemberData', $customer->id) }}" method="GET">
                                    @csrf
                                    <button type="submit" class="icon-button">
                                        <i class="fas fa-pencil-alt"></i> Proses
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js"></script>
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable();
        });
    </script>
@endpush
