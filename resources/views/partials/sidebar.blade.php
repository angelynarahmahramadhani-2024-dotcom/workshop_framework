<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        {{-- User Profile Section --}}
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    <img src="{{ asset('images/faces/face1.jpg') }}" alt="profile">
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">{{ Auth::user()->name ?? 'Guest Customer' }}</span>
                    <span class="text-secondary text-small">{{ Auth::check() ? 'Administrator' : 'Guest' }}</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
        </li>

        {{-- Menu Order Kantin (public, bisa diakses tanpa login) --}}
        <li class="nav-item {{ Request::is('order*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('order.index') }}">
                <span class="menu-title">Order Kantin</span>
                <i class="mdi mdi-food menu-icon"></i>
            </a>
        </li>

        @auth
        {{-- Dashboard --}}
        <li class="nav-item {{ Request::is('home') || Request::is('/') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('home') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>
        
        {{-- Menu Kategori --}}
        <li class="nav-item {{ Request::is('kategori*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('kategori.index') }}">
                <span class="menu-title">Kategori</span>
                <i class="mdi mdi-format-list-bulleted menu-icon"></i>
            </a>
        </li>
        
        {{-- Menu Buku --}}
        <li class="nav-item {{ Request::is('buku*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('buku.index') }}">
                <span class="menu-title">Buku</span>
                <i class="mdi mdi-book-open-page-variant menu-icon"></i>
            </a>
        </li>

        {{-- Menu Barang & POS --}}
        <li class="nav-item {{ (Request::is('barang*') && !Request::is('barang-js*')) || Request::is('pos*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#barang-menu" aria-expanded="{{ ((Request::is('barang*') && !Request::is('barang-js*')) || Request::is('pos*')) ? 'true' : 'false' }}" aria-controls="barang-menu">
                <span class="menu-title">Barang</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-package-variant menu-icon"></i>
            </a>
            <div class="collapse {{ ((Request::is('barang*') && !Request::is('barang-js*')) || Request::is('pos*')) ? 'show' : '' }}" id="barang-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('barang*') && !Request::is('barang-js*') ? 'active' : '' }}" href="{{ route('barang.index') }}">Data Barang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('pos') ? 'active' : '' }}" href="{{ route('pos.index') }}">POS jQuery AJAX</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('pos-axios') ? 'active' : '' }}" href="{{ route('pos.axios') }}">POS Axios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('barang/barcode-scanner') ? 'active' : '' }}" href="{{ route('barang.barcode-scanner') }}">Barcode Scanner</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- Menu Barang JavaScript --}}
        <li class="nav-item {{ Request::is('barang-js*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#barang-js-menu" aria-expanded="false" aria-controls="barang-js-menu">
                <span class="menu-title">Barang JavaScript</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-code-braces menu-icon"></i>
            </a>
            <div class="collapse" id="barang-js-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('barang-js/form-validasi') ? 'active' : '' }}" href="{{ route('barang-js.form-validasi') }}">Form Validasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('barang-js/datatables') ? 'active' : '' }}" href="{{ route('barang-js.datatables') }}">DataTables Demo</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- Menu Select Kota --}}
        <li class="nav-item {{ Request::is('select-kota*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('select-kota.index') }}">
                <span class="menu-title">Select Kota</span>
                <i class="mdi mdi-map-marker menu-icon"></i>
            </a>
        </li>

        {{-- Menu Wilayah (AJAX) --}}
        <li class="nav-item {{ Request::is('wilayah*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#wilayah-menu" aria-expanded="{{ Request::is('wilayah*') ? 'true' : 'false' }}" aria-controls="wilayah-menu">
                <span class="menu-title">Wilayah</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-map-search menu-icon"></i>
            </a>
            <div class="collapse {{ Request::is('wilayah*') ? 'show' : '' }}" id="wilayah-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('wilayah') || Request::is('wilayah-axios') ? 'active' : '' }}" href="{{ route('wilayah.index') }}">Wilayah</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- Menu Generate PDF --}}
        <li class="nav-item {{ Request::is('pdf*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#pdf-menu" aria-expanded="false" aria-controls="pdf-menu">
                <span class="menu-title">Generate PDF</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-file-pdf menu-icon"></i>
            </a>
            <div class="collapse" id="pdf-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pdf.sertifikat') }}" target="_blank">Sertifikat</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pdf.undangan') }}" target="_blank">Undangan</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- Menu Customer --}}
        <li class="nav-item {{ Request::is('customer*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#customer-menu" aria-expanded="{{ Request::is('customer*') ? 'true' : 'false' }}" aria-controls="customer-menu">
                <span class="menu-title">Customer</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-account-multiple menu-icon"></i>
            </a>
            <div class="collapse {{ Request::is('customer*') ? 'show' : '' }}" id="customer-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('customer') ? 'active' : '' }}" href="{{ route('customer.index') }}">Data Customer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('customer/create1') ? 'active' : '' }}" href="{{ route('customer.create1') }}">Tambah Customer 1</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('customer/create2') ? 'active' : '' }}" href="{{ route('customer.create2') }}">Tambah Customer 2</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- Vendor Area (hanya untuk role vendor) --}}
        @if((Auth::user()->role ?? null) === 'vendor')
        <li class="nav-item {{ Request::is('vendor/menu*') || Request::is('vendor/pesanan/lunas*') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#vendor-menu" aria-expanded="{{ Request::is('vendor/menu*') || Request::is('vendor/pesanan/lunas*') ? 'true' : 'false' }}" aria-controls="vendor-menu">
                <span class="menu-title">Vendor Area</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-store menu-icon"></i>
            </a>
            <div class="collapse {{ Request::is('vendor/menu*') || Request::is('vendor/pesanan/lunas*') ? 'show' : '' }}" id="vendor-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('vendor/menu*') ? 'active' : '' }}" href="{{ route('vendor.menu.index') }}">Master Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('vendor/pesanan/lunas*') ? 'active' : '' }}" href="{{ route('vendor.orders.paid') }}">Pesanan Lunas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('vendor/scan-qr*') ? 'active' : '' }}" href="{{ route('vendor.scan-qr') }}">Scan QR Code</a>
                    </li>
                </ul>
            </div>
        </li>
        @endif

        {{-- Menu Kunjungan Toko (Geolocation) --}}
        <li class="nav-item {{ Request::is('kunjungan-toko*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('kunjungan-toko.index') }}">
                <span class="menu-title">Kunjungan Toko</span>
                <i class="mdi mdi-map-marker-radius menu-icon"></i>
            </a>
        </li>
        @endauth
    </ul>
</nav>
