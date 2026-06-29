<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PasswordResetController extends Controller
{
    public function request()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(
            ['email' => ['required', 'email']],
            [],
            ['email' => 'email']
        );

        try {
            $response = Http::post(config('services.backend.url') . '/api/forgot-password', [
                'email' => $request->input('email')
            ]);

            if ($response->successful()) {
                $data = $response->json();
                session([
                    'pw_reset_user_id' => $data['user_id'],
                    'pw_reset_email' => $request->input('email')
                ]);
                session()->forget('pw_reset_verified');
                session()->forget('pw_reset_code');

                return redirect()->route('password.otp')
                    ->with('status', 'Kode verifikasi telah dikirim ke email kamu.');
            } else {
                $err = $response->json();
                return back()->withErrors(['email' => $err['errors']['email'][0] ?? $err['message'] ?? 'Email tidak ditemukan.']);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses permintaan reset: ' . $e->getMessage());
        }
    }

    public function showOtp(Request $request)
    {
        $userId = session('pw_reset_user_id');
        if (!$userId) {
            return redirect()->route('password.request');
        }

        try {
            $response = Http::get(config('services.backend.url') . '/api/pending-user/' . $userId);
            if ($response->successful()) {
                $data = $response->json();
                return view('auth.reset-otp', [
                    'email' => $data['email'],
                    'cooldown' => $data['cooldown'],
                ]);
            }
        } catch (\Exception $e) {
            // Fail silently
        }

        return redirect()->route('password.request')->with('error', 'Gagal memuat halaman OTP.');
    }

    public function verifyOtp(Request $request)
    {
        $userId = session('pw_reset_user_id');
        if (!$userId) {
            return redirect()->route('password.request');
        }

        $request->validate(['code' => ['required', 'digits:6']], [], ['code' => 'kode']);

        try {
            $response = Http::post(config('services.backend.url') . '/api/reset-password/otp', [
                'user_id' => $userId,
                'code' => $request->input('code')
            ]);

            if ($response->successful()) {
                session([
                    'pw_reset_verified' => true,
                    'pw_reset_code' => $request->input('code')
                ]);
                return redirect()->route('password.reset');
            } else {
                $err = $response->json();
                return back()->withErrors(['code' => $err['message'] ?? 'Kode salah atau sudah kedaluwarsa.']);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses OTP: ' . $e->getMessage());
        }
    }

    public function resendOtp(Request $request)
    {
        $userId = session('pw_reset_user_id');
        if (!$userId) {
            return redirect()->route('password.request');
        }

        try {
            $response = Http::post(config('services.backend.url') . '/api/reset-password/otp/resend', [
                'user_id' => $userId
            ]);

            if ($response->successful()) {
                return back()->with('status', 'Kode baru telah dikirim.');
            } else {
                $err = $response->json();
                return back()->with('info', $err['message'] ?? 'Gagal mengirim ulang kode OTP.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim ulang: ' . $e->getMessage());
        }
    }

    public function showReset(Request $request)
    {
        if (!session('pw_reset_user_id') || !session('pw_reset_verified')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password');
    }

    public function reset(Request $request)
    {
        $userId = session('pw_reset_user_id');
        $code = session('pw_reset_code');
        if (!$userId || !session('pw_reset_verified') || !$code) {
            return redirect()->route('password.request');
        }

        $request->validate(
            ['password' => ['required', 'min:8', 'confirmed']],
            [],
            ['password' => 'kata sandi']
        );

        try {
            $response = Http::post(config('services.backend.url') . '/api/reset-password', [
                'user_id' => $userId,
                'code' => $code,
                'password' => $request->input('password'),
                'password_confirmation' => $request->input('password_confirmation')
            ]);

            if ($response->successful()) {
                session()->forget(['pw_reset_user_id', 'pw_reset_email', 'pw_reset_verified', 'pw_reset_code']);

                return redirect()->route('login')
                    ->with('status', 'Kata sandi berhasil diubah. Silakan masuk.');
            } else {
                $err = $response->json();
                return back()->withErrors(['password' => $err['message'] ?? 'Gagal reset kata sandi.']);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses reset: ' . $e->getMessage());
        }
    }
}