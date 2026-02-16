@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div class="col-lg-4 mx-auto">
    <div class="auth-form-light text-left p-5">
        <div class="brand-logo text-center">
            <h2 class="text-primary"><i class="mdi mdi-book-multiple"></i> Koleksi Buku</h2>
        </div>
        <h4>Forgot Password?</h4>
        <h6 class="font-weight-light">Enter your email to reset your password</h6>
        
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        
        <form class="pt-3" method="POST" action="{{ route('password.email') }}">
            @csrf
            
            <div class="form-group">
                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email') }}" 
                       placeholder="Email" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="mt-3 d-grid gap-2">
                <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                    SEND RESET LINK
                </button>
            </div>
            
            <div class="text-center mt-4 font-weight-light">
                Remember your password? <a href="{{ route('login') }}" class="text-primary">Login</a>
            </div>
        </form>
    </div>
</div>
@endsection
