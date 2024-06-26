@extends('layouts.admin')

@section('content')
    <div id="invoiceContainer" class="card" style="width: 80mm; margin: auto; padding: 10px; border: 1px solid #ddd;">
        <div class="card-header bg-primary text-center" style="padding: 10px;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo" style="height: 50px;">
                <div>
                    <h3 class="card-title mb-0 text-bold">STINET</h3><br>
                    <p style="font-size: 10px; font-style: italic;">
                        Jalan Raya Tapos, RT 3 RW 5<br>
                        Kecamatan Tapos, Kota Depok
                    </p>
                </div>
            </div>
        </div>
        <div class="card-body" style="padding: 10px;">
            <table class="table">
                <tbody>
                    <tr>
                        <th style="text-align: left;">Nomor Invoice</th>
                        <td style="text-align: left;">{{ $invoice->invoice_number }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left;">Nomor Pelanggan</th>
                        <td style="text-align: left;">{{ $invoice->no_pelanggan }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left;">Nama Pelanggan</th>
                        <td style="text-align: left;">{{ $invoice->customer->name }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left;">Tanggal Invoice</th>
                        <td style="text-align: left;">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}
                        </td>
                    </tr>
                    <tr>
                        <th style="text-align: left;">Tagihan</th>
                        <td style="text-align: left;">{{ $invoice->amount }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left;">Donasi</th>
                        <td style="text-align: left;">{{ $invoice->donation }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: left;">Status</th>
                        <td style="text-align: left;">{{ $invoice->status }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="text-center mt-3">
                <p>Terima kasih telah berlangganan!</p>
                <p>"Kesuksesan bukanlah kunci kebahagiaan. Kebahagiaanlah kunci kesuksesan. Jika Anda mencintai apa yang
                    Anda kerjakan, Anda akan sukses."</p>
            </div>
        </div>
        <div class="card-footer text-center" style="padding: 10px;">
            <p>Supported by MegaHub</p>
        </div>
    </div>
    <div class="text-center mt-3">
        <button id="downloadButton" class="btn btn-success">Download Struk</button>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script>
        document.getElementById('downloadButton').addEventListener('click', function() {
            const element = document.getElementById('invoiceContainer');
            const opt = {
                margin: [10, 0, 10, 0], // top, left, bottom, right
                filename: 'invoice.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'portrait'
                },
                pagebreak: {
                    mode: ['avoid-all', 'css', 'legacy']
                }
            };

            html2pdf().from(element).set(opt).save().catch(err => console.log(err));
        });
    </script>
@endsection
