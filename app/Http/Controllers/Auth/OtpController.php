<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Helpers\ApiHydrator;
use App\Models\User;

class OtpController extends Controller
{
    public function show(Request $request)
    {
        $userId = $request->session()->get('otp_user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        try {
            $response = Http::get(config('services.backend.url') . '/api/pending-user/' . $userId);
            if ($response->successful()) {
                $data = $response->json();
                return view('auth.otp', [
                    'email' => $data['email'],
                    'cooldown' => $data['cooldown'],
                ]);
            }
        } catch (\Exception $e) {
            // Fail silently
        }

        return redirect()->route('login')->with('error', 'Gagal memuat halaman verifikasi.');
    }

    public function verify(Request $request)
    {
        $userId = $request->session()->get('otp_user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $request->validate(['code' => ['required', 'digits:6']], [], ['code' => 'kode']);

        try {
            $response = Http::post(config('services.backend.url') . '/api/verify-otp', [
                'user_id' => $userId,
                'code' => $request->input('code')
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Store backend token and user in session
                session([
                    'backend_token' => $data['token'],
                    'backend_user' => $data['user']
                ]);

                // Clear pending state
                $request->session()->forget('otp_user_id');

                // Perform local login using the hydrated model
                $user = ApiHydrator::hydrateSingle(User::class, $data['user']);
                Auth::login($user);
                $request->session()->regenerate();

                return redirect()->route('member.home')
                    ->with('status', 'Email berhasil diverifikasi. Selamat datang!');
            } else {
                $err = $response->json();
                return back()->withErrors(['code' => $err['message'] ?? 'Kode salah atau sudah kedaluwarsa.']);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses verifikasi: ' . $e->getMessage());
        }
    }

    public function resend(Request $request)
    {
        $userId = $request->session()->get('otp_user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        try {
            $response = Http::post(config('services.backend.url') . '/api/verify-otp/resend', [
                'user_id' => $userId
            ]);

            if ($response->successful()) {
                return back()->with('status', 'Kode baru telah dikirim ke email kamu.');
            } else {
                $err = $response->json();
                return back()->with('info', $err['message'] ?? 'Gagal mengirim ulang kode OTP.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim ulang: ' . $e->getMessage());
        }
    }
}