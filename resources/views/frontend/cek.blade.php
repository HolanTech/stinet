<div class="container">
    <h2 class="text-center mb-4">Cek Tagihan Layanan Internet Bulanan Anda</h2>
    <div class="d-flex justify-content-between flex-wrap">
        <div class="payment-box p-4 border rounded me-3 flex-fill">
            <h3 class="text-center mb-4">Cek Tagihan Layanan Internet Bulanan Anda</h3>
            <form id="paymentForm">
                <div class="mb-3">
                    <input type="text" class="form-control" placeholder="Masukkan Nomor Pelanggan" name="no_pelanggan"
                        id="no_pelanggan" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-danger">Cek Data Saya</button>
                </div>
            </form>
        </div>
        <div class="result-box p-4 border rounded flex-fill" id="resultBox">
            <img src="{{ asset('img/payment.png') }}" alt="Placeholder Image" class="img-fluid w-100"
                id="placeholderImage">
            <div class="table-responsive" style="display:none;" id="resultContent">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Nama Pelanggan</th>
                            <td><span id="customerName"></span></td>
                        </tr>
                        <tr>
                            <th>Nomor Pelanggan</th>
                            <td><span id="customerNumber"></span></td>
                        </tr>
                        <tr>
                            <th>Nomor Invoice</th>
                            <td><span id="invoiceNumber"></span></td>
                        </tr>
                        <tr>
                            <th>Tanggal Invoice</th>
                            <td><span id="invoiceDate"></span></td>
                        </tr>
                        <tr>
                            <th>Status Invoice</th>
                            <td><span id="invoiceStatus"></span></td>
                        </tr>
                        <tr>
                            <th>Jumlah Tagihan</th>
                            <td><span id="billingAmount"></span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mb-3" style="display:none;" id="donationInput">
                <label for="donationAmount">Anda juga dapat menambahkan donasi untuk anak yatim (opsional)</label>
                <input type="number" class="form-control" placeholder="Masukkan Jumlah Donasi (opsional)"
                    id="donationAmount">
            </div>
            <button id="btnBayar" class="btn btn-primary" style="display:none;">Bayar Sekarang</button>
        </div>
    </div>
    <div class="text-center mt-3" style="display:none;" id="downloadSection">
        <button id="downloadButton" class="btn btn-success">Download Struk</button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.querySelector("#paymentForm");
        const resultBox = document.querySelector("#resultBox");
        const placeholderImage = document.querySelector("#placeholderImage");
        const resultContent = document.querySelector("#resultContent");
        const donationInput = document.querySelector("#donationInput");
        const customerName = document.querySelector("#customerName");
        const customerNumber = document.querySelector("#customerNumber");
        const invoiceNumber = document.querySelector("#invoiceNumber");
        const invoiceDate = document.querySelector("#invoiceDate");
        const invoiceStatus = document.querySelector("#invoiceStatus");
        const billingAmount = document.querySelector("#billingAmount");
        const btnBayar = document.querySelector("#btnBayar");
        const noPelangganInput = document.querySelector("#no_pelanggan");
        const downloadSection = document.querySelector("#downloadSection");
        const downloadButton = document.querySelector("#downloadButton");

        form.addEventListener("submit", function(event) {
            event.preventDefault();
            const noPelanggan = noPelangganInput.value.trim();

            if (!noPelanggan) {
                Swal.fire('Oops...', 'Masukkan Nomor Pelanggan terlebih dahulu.', 'error');
                return;
            }

            fetch("/api/cek-tagihan", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                    body: JSON.stringify({
                        no_pelanggan: noPelanggan
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        customerName.textContent = data.data.customerName;
                        customerNumber.textContent = data.data.customerNumber;
                        invoiceNumber.textContent = data.data.invoice_number;
                        invoiceDate.textContent = data.data.invoice_date;
                        invoiceStatus.textContent = data.data.status;
                        billingAmount.textContent = data.data.billingAmount;

                        placeholderImage.style.display = "none";
                        resultContent.style.display = "block";

                        if (data.data.status === 'Paid') {
                            btnBayar.style.display = "none";
                            downloadSection.style.display = "block";
                        } else {
                            donationInput.style.display = "block";
                            btnBayar.style.display = "block";
                            downloadSection.style.display = "none";
                        }

                        btnBayar.onclick = function() {
                            const donationAmount = parseFloat(document.getElementById(
                                'donationAmount').value) || 0;
                            const totalAmount = parseFloat(billingAmount.textContent) +
                                donationAmount;

                            fetch("/api/process-payment", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "X-CSRF-TOKEN": document.querySelector(
                                            'meta[name="csrf-token"]').getAttribute(
                                            "content"),
                                    },
                                    body: JSON.stringify({
                                        no_pelanggan: noPelanggan,
                                        donation: donationAmount
                                    }),
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        window.snap.pay(data.data.snapToken, {
                                            onSuccess: function(result) {
                                                Swal.fire('Success!',
                                                    'Pembayaran berhasil.',
                                                    'success');
                                                setTimeout(function() {
                                                    // Tampilkan tombol download setelah pembayaran berhasil
                                                    btnBayar.style
                                                        .display =
                                                        "none";
                                                    downloadSection
                                                        .style.display =
                                                        "block";
                                                    downloadButton
                                                        .onclick =
                                                        function() {
                                                            fetch(
                                                                    `/invoice/html/${invoiceNumber.textContent}`
                                                                )
                                                                .then(
                                                                    response =>
                                                                    response
                                                                    .text()
                                                                )
                                                                .then(
                                                                    html => {
                                                                        const
                                                                            opt = {
                                                                                margin: [
                                                                                    10,
                                                                                    0,
                                                                                    10,
                                                                                    0
                                                                                ], // top, left, bottom, right
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
                                                                                }
                                                                            };
                                                                        html2pdf
                                                                            ()
                                                                            .from(
                                                                                html
                                                                            )
                                                                            .set(
                                                                                opt
                                                                            )
                                                                            .save()
                                                                            .catch(
                                                                                err =>
                                                                                console
                                                                                .log(
                                                                                    err
                                                                                )
                                                                            );
                                                                    });
                                                        };
                                                }, 3000);
                                            },
                                            onPending: function(result) {
                                                Swal.fire('Pending',
                                                    'Pembayaran Anda sedang diproses.',
                                                    'info');
                                            },
                                            onError: function(result) {
                                                Swal.fire('Error',
                                                    'Pembayaran gagal, silakan coba lagi.',
                                                    'error');
                                            }
                                        });
                                    } else {
                                        Swal.fire('Oops...', data.message, 'error');
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        };
                    } else {
                        Swal.fire('Oops...', data.message, 'error');
                    }
                })
                .catch(error => console.error('Error:', error));
        });

        if (downloadButton) {
            downloadButton.addEventListener('click', function() {
                fetch(`/invoice/html/${invoiceNumber.textContent}`)
                    .then(response => response.text())
                    .then(html => {
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
                            }
                        };
                        html2pdf().from(html).set(opt).save().catch(err => console.log(err));
                    });
            });
        }
    });
</script>
