@extends('layouts.auth')

@section('title', 'Konfirmasi Password')

@section('content')
<div class="col-lg-4 mx-auto">
    <div class="auth-form-light text-left p-5">
        <div class="brand-logo text-center">
            <h2 class="text-primary"><i class="mdi mdi-book-multiple"></i> Koleksi Buku</h2>
        </div>
        <h4>Confirm Password</h4>
        <h6 class="font-weight-light">Please confirm your password before continuing</h6>
        
        <form class="pt-3" method="POST" action="{{ route('password.confirm') }}">
            @csrf
            
            <div class="form-group">
                <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                       id="password" name="password" placeholder="Password" required autocomplete="current-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="mt-3 d-grid gap-2">
                <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                    CONFIRM PASSWORD
                </button>
            </div>
            
            @if (Route::has('password.request'))
                <div class="text-center mt-4 font-weight-light">
                    <a href="{{ route('password.request') }}" class="text-primary">Forgot your password?</a>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection
