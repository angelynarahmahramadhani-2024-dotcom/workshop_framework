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
                    <span class="font-weight-bold mb-2">{{ Auth::user()->name ?? 'David Grey. H' }}</span>
                    <span class="text-secondary text-small">Administrator</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
        </li>
        
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

        {{-- Menu Barang --}}
        <li class="nav-item {{ Request::is('barang*') && !Request::is('barang-js*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('barang.index') }}">
                <span class="menu-title">Barang</span>
                <i class="mdi mdi-package-variant menu-icon"></i>
            </a>
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
    </ul>
</nav>
