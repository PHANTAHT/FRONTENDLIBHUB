<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [], ['email' => 'email', 'password' => 'kata sandi']);

        // Remove any old pending registration states
        $request->session()->forget('otp_user_id');

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            // Check if user is unverified (custom user provider sets otp_user_id in session)
            if ($request->session()->has('otp_user_id')) {
                return redirect()->route('otp.show')
                    ->with('info', 'Verifikasi email kamu dulu. Kode sudah dikirim.');
            }

            if ($request->session()->has('login_error')) {
                $msg = $request->session()->pull('login_error');
                return back()
                    ->withErrors(['email' => $msg])
                    ->onlyInput('email');
            }

            return back()
                ->withErrors(['email' => 'Email atau kata sandi salah.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(
            Auth::user()->isAdmin() ? route('admin.dashboard') : route('member.home')
        );
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'no_telp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        try {
            $payload = array_merge($data, [
                'password_confirmation' => $request->input('password_confirmation')
            ]);
            $response = Http::post(config('services.backend.url') . '/api/register', $payload);

            if ($response->successful()) {
                $resData = $response->json();
                $request->session()->put('otp_user_id', $resData['user_id']);

                return redirect()->route('otp.show')
                    ->with('status', 'Kami mengirim kode verifikasi ke email kamu.');
            } else {
                $err = $response->json();
                return back()
                    ->withErrors($err['errors'] ?? ['email' => $err['message'] ?? 'Pendaftaran gagal. Coba lagi.'])
                    ->withInput();
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal terhubung ke server backend: ' . $e->getMessage())->withInput();
        }
    }

    public function logout(Request $request)
    {
        $token = session('backend_token');
        if ($token) {
            try {
                Http::withToken($token)->post(config('services.backend.url') . '/api/logout');
            } catch (\Exception $e) {
                // Fail silently
            }
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }
}
