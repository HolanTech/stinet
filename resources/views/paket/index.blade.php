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
            <h3 class="card-title">List Paket</h3>
        </div>
        <div class="card-body">
            <a href="{{ route('paket.create') }}" class="btn btn-primary">Tambah Data</a>
            <table id="datatable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tipe</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Diskon</th>
                        <th>Speed</th>
                        <th>Qty</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pakets as $paket)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $paket->tipe }}</td>
                            <td>{{ $paket->nama }}</td>
                            <td>{{ $paket->harga }}</td>
                            <td>{{ $paket->diskon }}</td>
                            <td>{{ $paket->speed }}</td>
                            <td>{{ $paket->qty }}</td>
                            <td><img src="{{ asset('storage/' . $paket->image) }}" alt="Image" width="50"></td>
                            <td>
                                <a href="{{ route('paket.edit', $paket->id) }}" class="icon-button">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <button type="button" class="icon-button" data-toggle="modal"
                                    data-target="#deleteModal{{ $paket->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @foreach ($pakets as $paket)
        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal{{ $paket->id }}" tabindex="-1" role="dialog"
            aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Delete Paket</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this paket?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <form action="{{ route('paket.destroy', $paket->id) }}" method="POST">
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

        function toggleStatus(paketId, currentStatus) {
            const url = `/paket/status/${paketId}`; // Adjust URL to your route for updating status
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
