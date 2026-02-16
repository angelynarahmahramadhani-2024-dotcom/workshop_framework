<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Login') - Koleksi Buku</title>
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" />
    
    {{-- Purple Admin CSS (sama seperti layout utama) --}}
    <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <style>
        .container-scroller {
            overflow: hidden;
        }
        
        .container-fluid.page-body-wrapper.full-page-wrapper {
            width: 100%;
            min-height: 100vh;
            padding: 0;
        }
        
        .content-wrapper.d-flex.align-items-center.auth {
            background: linear-gradient(to right, #da8cff, #9a55ff);
            min-height: 100vh;
        }
        
        .auth .brand-logo {
            margin-bottom: 2rem;
        }
        
        .auth .brand-logo img {
            width: 150px;
        }
        
        .auth form .form-group {
            margin-bottom: 1.5rem;
        }
        
        .auth form .form-group label {
            font-size: 0.8125rem;
            color: #6c7293;
        }
        
        .auth form .form-group .form-control {
            background: transparent;
            border-radius: 0;
            font-size: 0.9375rem;
            border: 1px solid #ebedf2;
            padding: 0.625rem 0.6875rem;
        }
        
        .auth form .auth-form-btn {
            padding: 1rem 3rem;
            font-size: 1rem;
            line-height: 1;
        }
        
        .auth form .auth-link {
            font-size: 0.875rem;
        }
        
        .auth form .auth-link:hover {
            color: initial;
        }
        
        .auth.auth-bg-1 {
            background: url('{{ asset("images/auth/login-bg.jpg") }}');
            background-size: cover;
        }
        
        .auth .lock-profile-img {
            width: 90px;
            height: 90px;
            border-radius: 100%;
        }
        
        .auth .auth-form-light {
            background: #ffffff;
            padding: 30px 40px;
            border-radius: 5px;
        }
        
        .auth .auth-form-light select {
            color: #c9c8c8;
        }
        
        .auth .auth-form-light .input-group .form-control:focus, 
        .auth .auth-form-light .input-group .form-control:active {
            border-color: #ebedf2;
        }
        
        .auth .auth-form-transparent {
            background: transparent;
        }
        
        .auth .auth-form-transparent .form-control,
        .auth .auth-form-transparent .input-group-text {
            border-color: #b9b8b8;
        }
        
        .auth .auth-form-transparent .form-control:focus, 
        .auth .auth-form-transparent .form-control:active {
            border-color: #b9b8b8;
        }
        
        .auth.theme-one .auto-form-wrapper {
            background: #ffffff;
            padding: 40px 40px 10px;
            border-radius: 4px;
            box-shadow: 0 -25px 37.7px 11.3px rgba(8, 143, 220, 0.07);
        }
        
        .auth.theme-one .auto-form-wrapper .form-group .input-group-append {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
        }
        
        .auth.theme-one .auto-form-wrapper .form-group .input-group-append .input-group-text {
            border: none;
            background: transparent;
            color: #b6b6b6;
        }
        
        .auth.theme-one .auto-form-wrapper .form-group .form-control {
            border: 1px solid #d9d9d9;
            height: auto;
            padding: 15px 20px;
            font-weight: 400;
            background: transparent;
            border-radius: 4px;
        }
        
        .auth.theme-one .auto-form-wrapper .submit-btn {
            font-family: "Roboto", sans-serif;
            font-size: 13px;
            padding: 14px 0;
            border-radius: 4px;
            background: linear-gradient(to right, #da8cff, #9a55ff);
            border: none;
        }
        
        .auth.theme-one .auto-form-wrapper .footer-text {
            font-size: 13px;
            margin-top: 30px;
            margin-bottom: 50px;
        }
        
        .auth.theme-one .auto-form-wrapper .footer-link {
            color: #7571f9;
            font-size: 13px;
        }
        
        .auth.theme-two .auto-form-wrapper {
            position: relative;
            height: 100%;
            max-width: 560px;
            width: 100%;
            padding: 100px 70px 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #ffffff;
        }
        
        .purple-gradient-bg {
            background: linear-gradient(to right, #da8cff, #9a55ff);
        }
    </style>
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth">
                <div class="row flex-grow w-100 mx-0">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    
    {{-- Purple Admin JS --}}
    <script src="{{ asset('vendors/js/vendor.bundle.base.js') }}"></script>
</body>
</html>
