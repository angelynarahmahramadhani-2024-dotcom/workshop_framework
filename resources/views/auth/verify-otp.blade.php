@extends('layouts.auth')

@section('title', 'Verifikasi OTP')

@section('content')
<div class="col-lg-4 mx-auto">
    <div class="auth-form-light text-left p-5">
        <div class="brand-logo text-center">
            <img src="{{ asset('images/logo.svg') }}" alt="logo">
        </div>
        <h4>Verifikasi OTP</h4>
        <h6 class="font-weight-light">Masukkan kode 6 digit yang dikirim ke email Anda.</h6>
        
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

        <form class="pt-3" method="POST" action="{{ route('otp.verify') }}">
            @csrf
            
            <div class="form-group">
                <label for="otp">Kode OTP</label>
                <input type="text" 
                       class="form-control form-control-lg text-center @error('otp') is-invalid @enderror" 
                       id="otp" 
                       name="otp" 
                       placeholder="______"
                       maxlength="6"
                       pattern="[0-9]{6}"
                       style="font-size: 24px; letter-spacing: 10px;"
                       required 
                       autofocus>
                @error('otp')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                    VERIFIKASI
                </button>
            </div>
            
            <div class="text-center mt-4">
                <p class="text-muted">Tidak menerima kode?</p>
                <a href="{{ route('otp.resend') }}" class="btn btn-link">Kirim Ulang OTP</a>
            </div>
            
            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="auth-link text-black">Kembali ke Login</a>
            </div>
        </form>
    </div>
</div>
@endsection
