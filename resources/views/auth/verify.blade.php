@extends('layouts.auth')

@section('title', 'Verifikasi Email')

@section('content')
<div class="col-lg-4 mx-auto">
    <div class="auth-form-light text-left p-5">
        <div class="brand-logo text-center">
            <h2 class="text-primary"><i class="mdi mdi-book-multiple"></i> Koleksi Buku</h2>
        </div>
        <h4>Verify Your Email</h4>
        <h6 class="font-weight-light">Check your email for a verification link</h6>
        
        @if (session('resent'))
            <div class="alert alert-success" role="alert">
                A fresh verification link has been sent to your email address.
            </div>
        @endif
        
        <p class="mt-3">Before proceeding, please check your email for a verification link.</p>
        <p>If you did not receive the email:</p>
        
        <form class="d-grid gap-2" method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                RESEND VERIFICATION EMAIL
            </button>
        </form>
        
        <div class="text-center mt-4 font-weight-light">
            <a href="{{ route('logout') }}" class="text-primary"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</div>
@endsection
