{{--
    Layout App - Purple Admin Template
    
    Struktur Layout Modular sesuai Modul:
    1. Header (DOCTYPE, meta, title)
    2. Style Global (CSS untuk semua halaman)
    3. Style Page (CSS khusus halaman - via @stack)
    4. Navbar
    5. Sidebar
    6. Content (@yield)
    7. Footer
    8. Javascript Global (JS untuk semua halaman)
    9. Javascript Page (JS khusus halaman - via @stack)
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    {{-- 1. Header --}}
    @include('partials.header')
    
    {{-- 2. Style Global --}}
    @include('partials.style-global')
    
    {{-- 3. Style Page (khusus halaman ini) --}}
    @stack('styles')
</head>
<body>
    <div class="container-scroller">
        {{-- 4. Navbar --}}
        @include('partials.navbar')
        
        <div class="container-fluid page-body-wrapper">
            {{-- 5. Sidebar --}}
            @include('partials.sidebar')
            
            <div class="main-panel">
                <div class="content-wrapper">
                    {{-- Flash Messages --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    {{-- 6. Content --}}
                    @yield('content')
                </div>
                
                {{-- 7. Footer --}}
                @include('partials.footer')
            </div>
        </div>
    </div>
    
    {{-- 8. Javascript Global --}}
    @include('partials.js-global')
    
    {{-- 9. Javascript Page (khusus halaman ini) --}}
    @stack('scripts')
</body>
</html>