<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        #invoiceContainer {
            width: 80mm;
            margin: auto;
            padding: 10px;
            border: 1px solid #ddd;
        }

        .card-header img {
            height: 50px;
        }

        .card-title {
            font-weight: bold;
        }

        .card-header p {
            font-size: 10px;
            font-style: italic;
        }

        .table th,
        .table td {
            text-align: left;
        }
    </style>
</head>

<body>
    <div id="invoiceContainer" class="card">
        <div class="card-header bg-primary text-center text-white">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo">
                <div>
                    <h3 class="card-title mb-0">STINET</h3>
                    <p>Jalan Raya Tapos, RT 3 RW 5<br>
                        Kecamatan Tapos, Kota Depok</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table">
                <tbody>
                    <tr>
                        <th>Nomor Invoice</th>
                        <td>{{ $invoice->invoice_number }}</td>
                    </tr>
                    <tr>
                        <th>Nomor Pelanggan</th>
                        <td>{{ $invoice->no_pelanggan }}</td>
                    </tr>
                    <tr>
                        <th>Nama Pelanggan</th>
                        <td>{{ $invoice->customer->name }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Invoice</th>
                        <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Tagihan</th>
                        <td>{{ $invoice->amount }}</td>
                    </tr>
                    <tr>
                        <th>Donasi</th>
                        <td>{{ $invoice->donation }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $invoice->status }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="text-center mt-3">
                <p>Terima kasih telah berlangganan!</p>
                <p>"Kesuksesan bukanlah kunci kebahagiaan. Kebahagiaanlah kunci kesuksesan. Jika Anda mencintai apa yang
                    Anda kerjakan, Anda akan sukses."</p>
            </div>
        </div>
        <div class="card-footer text-center">
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
</body>

</html>
