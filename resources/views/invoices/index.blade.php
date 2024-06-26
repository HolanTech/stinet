@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header bg-primary vertical-center">
            <h3 class="card-title">Daftar Invoice</h3>
        </div>
        <div class="card-body">
            <table id="datatable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Pelanggan</th>
                        <th>Nomor Invoice</th>
                        <th>Nama Pelanggan</th>
                        <th>Tanggal Invoice</th>
                        <th>Jumlah</th>
                        <th>Donasi</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $invoice->no_pelanggan }}</td>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->customer->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td>
                            <td>{{ $invoice->amount }}</td>
                            <td>{{ $invoice->donation }}</td>
                            <td>{{ $invoice->status }}</td>
                            <td>
                                <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-info btn-sm">View</a>
                                <!-- Tambahkan tombol edit dan delete jika diperlukan -->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable();
        });
    </script>
@endpush
