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
            <h3 class="card-title">Data Pelanggan</h3>
        </div>
        <div class="card-body">
            <a href="{{ route('customer.create') }}" class="btn btn-primary mb-3">Tambah Data</a>
            <div class="table-responsive">
                <table id="datatable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>No Handphone</th>
                            <th>Email</th>
                            <th>Alamat</th>
                            <th>Paket</th>
                            <th>Lokasi</th>
                            <th>Tanggal Daftar</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->no_hp }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->address }}</td>
                                <td>{{ $customer->paket }} Mbps</td>
                                <td>{{ $customer->lokasi }}</td>
                                <td>{{ \Carbon\Carbon::parse($customer->date)->format('d/m/Y') }}</td>
                                <td>
                                    <button type="button" class="icon-button"
                                        onclick="toggleStatus({{ $customer->id }}, {{ $customer->status }})">
                                        {!! $customer->status
                                            ? '<i class="fas fa-times-circle text-danger"></i>'
                                            : '<i class="fas fa-check-circle text-success"></i>' !!}
                                    </button>
                                </td>
                                <td>
                                    <a href="{{ route('customer.edit', $customer->id) }}" class="icon-button">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <button type="button" class="icon-button" data-toggle="modal"
                                        data-target="#deleteModal{{ $customer->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @foreach ($customers as $customer)
        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal{{ $customer->id }}" tabindex="-1" role="dialog"
            aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Delete Customer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this customer?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <form action="{{ route('customer.destroy', $customer->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('script')
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js"></script> <!-- Replace with your Font Awesome Kit -->
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable();
        });

        function toggleStatus(customerId, currentStatus) {
            const url = `/customer/status/${customerId}`; // Adjust URL to your route for updating status
            const token = '{{ csrf_token() }}'; // CSRF token

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        status: currentStatus ? 0 : 1 // Toggle status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    location.reload(); // Reload the page to update the status visually
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }
    </script>
@endpush
