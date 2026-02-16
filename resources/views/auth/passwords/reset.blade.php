@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div class="col-lg-4 mx-auto">
    <div class="auth-form-light text-left p-5">
        <div class="brand-logo text-center">
            <h2 class="text-primary"><i class="mdi mdi-book-multiple"></i> Koleksi Buku</h2>
        </div>
        <h4>Reset Password</h4>
        <h6 class="font-weight-light">Enter your new password</h6>
        
        <form class="pt-3" method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="form-group">
                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ $email ?? old('email') }}" 
                       placeholder="Email" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="form-group">
                <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                       id="password" name="password" placeholder="New Password" required autocomplete="new-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="form-group">
                <input type="password" class="form-control form-control-lg" 
                       id="password-confirm" name="password_confirmation" 
                       placeholder="Confirm Password" required autocomplete="new-password">
            </div>
            
            <div class="mt-3 d-grid gap-2">
                <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                    RESET PASSWORD
                </button>
            </div>
            
            <div class="text-center mt-4 font-weight-light">
                <a href="{{ route('login') }}" class="text-primary">Back to Login</a>
            </div>
        </form>
    </div>
</div>
@endsection
