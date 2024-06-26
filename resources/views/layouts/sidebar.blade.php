<style>
    a {
        text-decoration: none;
    }
</style>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.html" class="brand-link">
        <img src="{{ asset('assets/dist/img/logo.png') }}" alt="AdminLTE Logo" class="brand-image">
        <span class="brand-text font-weight-light">PMA</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- User Panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('assets/dist/img/avatar4.png') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Administrator</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="/dashboard" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Master Data -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-database"></i>
                        <p>
                            Master
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <!-- Disini saya mengomentari bagian untuk menu pelanggan, jika diperlukan, bisa di-uncomment -->
                        <li class="nav-item">
                            <a href="{{ route('paket.index') }}" class="nav-link">
                                <i class="far fa-user nav-icon"></i>
                                <p>Paket</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('customers.member') }}" class="nav-link">
                                <i class="far fa-user nav-icon"></i>
                                <p>Mamber</p>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a href="{{ route('customer.index') }}" class="nav-link">
                                <i class="far fa-user nav-icon"></i>
                                <p>Pelanggan</p>
                            </a>
                        </li> --}}
                        {{-- <li class="nav-item">
                            <a href="{{ route('data_otb.site') }}" class="nav-link">
                                <i class="fas fa-sitemap nav-icon"></i>
                                <p>OTB perSite N to N</p>
                            </a>
                        </li> --}}
                        {{-- <li class="nav-item">
                            <a href="" class="nav-link">
                                <i class="fas fa-map-marker-alt nav-icon"></i>
                                <p>Pole All Area</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link">
                                <i class="fas fa-home nav-icon"></i>
                                <p>HH All Area</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link">
                                <i class="fas fa-tools nav-icon"></i>
                                <p>JB All Area</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link">
                                <i class="fas fa-network-wired nav-icon"></i>
                                <p>Splice Config All Area</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link">
                                <i class="fas fa-user-friends nav-icon"></i>
                                <p>Splice Config Customer</p>
                            </a>
                        </li> --}}
                    </ul>
                </li>
                <!-- Langganan Dan Tagiahan -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-database"></i>
                        <p>
                            Info Pelanggan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <!-- Disini saya mengomentari bagian untuk menu pelanggan, jika diperlukan, bisa di-uncomment -->
                        <li class="nav-item">
                            <a href="{{ route('customer.index') }}" class="nav-link">
                                <i class="far fa-user nav-icon"></i>
                                <p>Langganan</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('invoices.index') }}" class="nav-link">
                                <i class="far fa-user nav-icon"></i>
                                <p>Tagihan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('customer.index') }}" class="nav-link">
                                <i class="far fa-user nav-icon"></i>
                                <p>Assets</p>
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a href="{{ route('data_otb.site') }}" class="nav-link">
                                <i class="fas fa-sitemap nav-icon"></i>
                                <p>OTB perSite N to N</p>
                            </a>
                        </li> --}}
                        {{-- <li class="nav-item">
                            <a href="" class="nav-link">
                                <i class="fas fa-map-marker-alt nav-icon"></i>
                                <p>Pole All Area</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link">
                                <i class="fas fa-home nav-icon"></i>
                                <p>HH All Area</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link">
                                <i class="fas fa-tools nav-icon"></i>
                                <p>JB All Area</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link">
                                <i class="fas fa-network-wired nav-icon"></i>
                                <p>Splice Config All Area</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link">
                                <i class="fas fa-user-friends nav-icon"></i>
                                <p>Splice Config Customer</p>
                            </a>
                        </li> --}}
                    </ul>
                </li>

                {{-- <!-- View on Maps -->
                <li class="nav-item">
                    <a href="/maps" class="nav-link">
                        <i class="nav-icon fas fa-map-marked-alt"></i>
                        <p>View On Maps</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('customers.map') }}" class="nav-link">
                        <i class="nav-icon fas fa-map-marked-alt"></i>
                        <p>Customer On Maps</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('data_otb.allsite') }}" class="nav-link">
                        <i class="nav-icon fas fa-map-marked-alt"></i>
                        <p>OTB On Maps</p>
                    </a>
                </li> --}}
                {{-- <li class="nav-item">
                    <a href="/maps" class="nav-link">
                        <!-- Menggunakan ikon download dengan FontAwesome 5 -->
                        <i class="nav-icon fas fa-download"></i>
                        <p>Download App</p>
                    </a>
                </li> --}}

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
