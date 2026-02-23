<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Override: Setelah login berhasil, kirim OTP dan redirect ke verifikasi
     */
    protected function authenticated(Request $request, $user)
    {
        // Generate OTP 6 digit
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->update(['otp' => $otp]);

        // Logout dulu (belum boleh login sampai OTP diverifikasi)
        Auth::logout();

        // Simpan user_id di session untuk verifikasi OTP
        session(['otp_user_id' => $user->id]);

        // Kirim OTP ke email
        \Mail::raw("Kode OTP Anda adalah: $otp", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Kode OTP Login');
        });

        return redirect()->route('otp.verify')
            ->with('success', 'Kode OTP telah dikirim ke email ' . $user->email);
    }
}
