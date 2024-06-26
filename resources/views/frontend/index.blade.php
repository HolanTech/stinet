@extends('layouts.home')
@section('title', 'Selamat Datang di STINET')

@section('content')
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-primary">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="#">
                    <img src="{{ asset('assets/logo.png') }}" alt="STINET Logo" height="50" class="me-2">
                    <span>STINET</span>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" style="color: #fff;" href="/">Beranda</a></li>
                        <li class="nav-item"><a class="nav-link" style="color: #fff;" href="#services">Layanan</a></li>
                        <li class="nav-item"><a class="nav-link" style="color: #fff;" href="#packages">Paket</a></li>
                        <li class="nav-item"><a class="nav-link" style="color: #fff;" href="#payment">Pembayaran</a></li>
                        <li class="nav-item"><a class="nav-link" style="color: #fff;" href="#contact">Kontak</a></li>
                        @guest
                            <li class="nav-item">
                                <a class="nav-link btn btn-gold ms-2" style="color: #fff;" href="#" data-toggle="modal"
                                    data-target="#loginModal">Masuk/Daftar</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Hallo, {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <div class="banner">
            <div class="banner-text">
                <h1>Selamat Datang di STINET</h1>
                <p>Internet cepat dan handal untuk kebutuhan Anda!</p>
                <button class="btn btn-primary btn-banner" onclick="window.location.href='#services'">Lihat Layanan</button>
                <button class="btn btn-gold btn-banner" data-toggle="modal" data-target="#registerModal">Registrasi
                    Sekarang</button>
            </div>
        </div>
    </header>
    <main>
        <section id="services" class="py-5 bg-primary text-light">
            <div class="container">
                <h2 class="text-center text-gold mb-4">Layanan Kami</h2>
                <div class="service-list d-flex justify-content-between align-items-center">
                    <button class="btn btn-gold" id="prevService"><i class="fas fa-arrow-left"></i></button>
                    <div class="service-carousel d-flex justify-content-center">
                        <div class="service-item">
                            <img src="{{ asset('img/service1.png') }}" alt="Internet Berkecepatan Tinggi"
                                class="img-fluid rounded">
                            <h3 class="text-primary mt-3">Internet Berkecepatan Tinggi</h3>
                            <p class="text-dark">Rasakan kecepatan internet luar biasa dengan teknologi mutakhir yang kami
                                tawarkan, tanpa hambatan dan tanpa gangguan.</p>
                        </div>
                        <div class="service-item">
                            <img src="{{ asset('img/service2.png') }}" alt="Pilihan Paket Fleksibel"
                                class="img-fluid rounded">
                            <h3 class="text-primary mt-3">Pilihan Paket Fleksibel</h3>
                            <p class="text-dark">Temukan paket internet yang sesuai dengan gaya hidup dan kebutuhan Anda,
                                dari penggunaan ringan hingga berat.</p>
                        </div>
                        <div class="service-item">
                            <img src="{{ asset('img/service3.png') }}" alt="Dukungan Pelanggan 24/7"
                                class="img-fluid rounded">
                            <h3 class="text-primary mt-3">Dukungan Pelanggan 24/7</h3>
                            <p class="text-dark">Layanan pelanggan kami siap membantu Anda kapan saja, setiap hari, dengan
                                cepat dan ramah.</p>
                        </div>
                        <div class="service-item">
                            <img src="{{ asset('img/service4.png') }}" alt="Teknologi Keamanan" class="img-fluid rounded">
                            <h3 class="text-primary mt-3">Teknologi Keamanan</h3>
                            <p class="text-dark">Kami melindungi koneksi internet Anda dengan teknologi keamanan terbaru,
                                memastikan data Anda aman setiap saat.</p>
                        </div>
                    </div>
                    <button class="btn btn-gold" id="nextService"><i class="fas fa-arrow-right"></i></button>
                </div>
            </div>
        </section>
        <section id="packages" class="py-5">
            <div class="container">
                <h2 class="text-center mb-4">Pilihan Paket Kami</h2>
                <div class="row">
                    @foreach ($pakets as $paket)
                        <div class="col-md-3 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center">
                                    @if ($paket->image)
                                        <img src="{{ asset('storage/' . $paket->image) }}"
                                            alt="Image of {{ $paket->nama }}" class="img-fluid mb-3 paket-image">
                                    @else
                                        <img src="{{ asset('images/default-image.png') }}" alt="Default Image"
                                            class="img-fluid mb-3 paket-image">
                                    @endif
                                    <h5 class="card-title">{{ $paket->nama }}</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">{{ $paket->speed }} Mbps</h6>
                                    <p class="card-text">
                                        <del>Rp {{ number_format($paket->harga) }}</del>
                                        <span class="badge bg-danger">-{{ $paket->diskon }}%</span>
                                    </p>
                                    <p class="card-text fs-4 fw-bold">Rp
                                        {{ number_format($paket->harga - ($paket->harga * $paket->diskon) / 100) }}</p>
                                    <p class="card-text"><small>Cocok untuk {{ $paket->qty }} Perangkat</small></p>
                                    <p class="card-text"><small>Harga belum termasuk PPN 11%</small></p>

                                    <button class="btn btn-primary mb-2" data-toggle="modal"
                                        data-target="#registerModal">Langganan Sekarang</button>
                                    <a href="https://wa.me/628155061990" class="btn btn-outline-secondary">Chat Sales</a>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <section id="payment" class="py-5 bg-light">
            @include('frontend.cek')
        </section>
        <section id="testimonials" class="py-5 bg-light">
            <div class="container">
                <h2 class="text-center mb-5">Testimoni Pelanggan</h2>
                <div class="testimonial-carousel position-relative">
                    @foreach ([
            ['name' => 'Ahmad Rafi', 'message' => 'Layanan STINET sangat luar biasa! Internet cepat dan handal.'],
            ['name' => 'Budi Santoso', 'message' => 'Saya sangat puas dengan dukungan pelanggan. Sangat direkomendasikan.'],
            ['name' => 'Cindy Permata', 'message' => 'STINET memberikan layanan terbaik. Sangat memuaskan!'],
            ['name' => 'Dian Kusuma', 'message' => 'Harga terjangkau dan kualitas prima. Terima kasih STINET!'],
            ['name' => 'Eko Prasetyo', 'message' => 'Koneksi internet stabil dan cepat. Luar biasa!'],
            ['name' => 'Fitri Ayu', 'message' => 'Pelayanan yang sangat ramah dan profesional.'],
            ['name' => 'Gita Anjani', 'message' => 'Internet super cepat, sangat memuaskan!'],
            ['name' => 'Hari Nugroho', 'message' => 'Kualitas internet yang selalu stabil.'],
            ['name' => 'Intan Maharani', 'message' => 'Layanan STINET sangat membantu bisnis saya.'],
            ['name' => 'Joko Widodo', 'message' => 'Saya sangat merekomendasikan STINET!'],
            ['name' => 'Kartika Sari', 'message' => 'Internet cepat dan dukungan pelanggan yang luar biasa.'],
            ['name' => 'Lestari Andini', 'message' => 'Pengalaman yang sangat baik dengan STINET.'],
            ['name' => 'Mulyadi Saputra', 'message' => 'Tidak ada gangguan, sangat stabil.'],
            ['name' => 'Nanda Puspita', 'message' => 'STINET benar-benar bisa diandalkan.'],
            ['name' => 'Oki Setiawan', 'message' => 'Harga yang sangat bersaing dengan kualitas terbaik.'],
            ['name' => 'Putri Amelia', 'message' => 'Pelayanan yang sangat memuaskan dan cepat.'],
            ['name' => 'Rizki Ramadhan', 'message' => 'Koneksi internet sangat cepat dan stabil.'],
            ['name' => 'Sari Dewi', 'message' => 'Saya sangat senang menggunakan STINET.'],
            ['name' => 'Tri Handayani', 'message' => 'Pelayanan yang sangat profesional dan cepat tanggap.'],
            ['name' => 'Umi Kalsum', 'message' => 'Koneksi internet yang tidak pernah mengecewakan.'],
        ] as $index => $testimonial)
                        <div class="testimonial-item position-absolute w-100"
                            style="display: {{ $index === 0 ? 'block' : 'none' }};">
                            <p class="text-center">"{{ $testimonial['message'] }}"</p>
                            <span class="d-block text-center">- {{ $testimonial['name'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <br>
        <section id="contact" class="py-5 mt-2">
            <div class="container">
                <h2 class="text-center mb-4">Kontak Kami</h2>
                {{-- <form class="mx-auto" id="contactForm" style="max-width: 600px;">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Pesan:</label>
                        <textarea class="form-control" id="message" name="message" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </form> --}}
            </div>
        </section>
    </main>
    <footer class="bg-primary text-light py-4">
        <div class="container">
            <div class="footer">
                <div class="footer-content">
                    <div class="row">
                        <div class="col-md-4">
                            <h5>Lokasi Kantor</h5>
                            <iframe
                                src="https://maps.google.com/maps?q=-6.43236131897763,106.88602145755401&t=&z=13&ie=UTF8&iwloc=&output=embed"
                                width="100%" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>
                        </div>
                        <div class="col-md-4">
                            <h5>Kontak Kami</h5>
                            <table class="table table-borderless" style="color: #ffffff; text-shadow: #000000;">
                                <tbody>
                                    <tr>
                                        <td><i class="fa fa-map-marker-alt"></i></td>
                                        <td>Alamat</td>
                                        <td>: Jl. Raya Tapos, Tapos, Kec. Tapos, Kota Depok, Jawa Barat 16457</td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-phone"></i></td>
                                        <td>Telepon</td>
                                        <td>: +628155061990</td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-envelope"></i></td>
                                        <td>Email</td>
                                        <td>: support@stinet.co.id</td>
                                    </tr>
                                    <tr>
                                        <td><i class="fab fa-whatsapp"></i></td>
                                        <td>WhatsApp</td>
                                        <td>: +628155061990</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-4 text-center">
                            <img src="{{ asset('assets/logo.png') }}" alt="STINET Logo" height="100" class="mb-2">
                            <h5>Honest, Diligent, and Trustworthy</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-3">&copy; 2024 STINET. Hak cipta dilindungi undang-undang.</div>
    </footer>
    <a href="https://wa.me/628155061990" class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp whatsapp-icon"></i>
    </a>
    <!-- Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Masuk / Daftar</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="login-tab" data-toggle="tab" data-target="#login"
                                type="button" role="tab" aria-controls="login" aria-selected="true">Masuk</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="register-tab" data-toggle="tab" data-target="#register"
                                type="button" role="tab" aria-controls="register"
                                aria-selected="false">Daftar</button>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <!-- Login Tab -->
                        <div class="tab-pane fade show active" id="login" role="tabpanel"
                            aria-labelledby="login-tab">
                            <form id="loginForm" action="{{ route('login') }}" method="POST" class="mt-3">
                                @csrf
                                <div class="mb-3">
                                    <label for="loginEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="loginEmail" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="loginPassword" class="form-label">Kata Sandi</label>
                                    <input type="password" class="form-control" id="loginPassword" name="password"
                                        required>
                                </div>
                                <button type="submit" class="btn btn-primary">Masuk</button>
                            </form>
                        </div>
                        <!-- Register Tab -->
                        <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
                            <form id="registerForm" action="{{ route('register') }}" method="POST" class="mt-3">
                                @csrf
                                <div class="mb-3">
                                    <label for="registerName" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="registerName" name="name"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="registerEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="registerEmail" name="email"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="registerPhone" class="form-label">Nomor Telepon</label>
                                    <input type="text" class="form-control" id="registerPhone" name="phone"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="registerAlamat" class="form-label">Alamat Lengkap</label>
                                    <textarea class="form-control" id="registerAlamat" name="alamat" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="registerPassword" class="form-label">Kata Sandi</label>
                                    <input type="password" class="form-control" id="registerPassword" name="password"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="registerPasswordConfirm" class="form-label">Konfirmasi Kata Sandi</label>
                                    <input type="password" class="form-control" id="registerPasswordConfirm"
                                        name="password_confirmation" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Daftar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Registrasi -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Daftar</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="registerFormModal" action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="registerNameModal" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="registerNameModal" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="registerEmailModal" class="form-label">Email</label>
                            <input type="email" class="form-control" id="registerEmailModal" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="registerPhoneModal" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="registerPhoneModal" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="registerAlamatModal" class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control" id="registerAlamatModal" name="alamat" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="registerPasswordModal" class="form-label">Kata Sandi</label>
                            <input type="password" class="form-control" id="registerPasswordModal" name="password"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="registerPasswordConfirmModal" class="form-label">Konfirmasi Kata Sandi</label>
                            <input type="password" class="form-control" id="registerPasswordConfirmModal"
                                name="password_confirmation" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Daftar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
