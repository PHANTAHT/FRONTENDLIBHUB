<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Helpers\ApiHydrator;
use App\Models\User;

class GoogleController extends Controller
{
    public function redirect()
    {
        // Redirect the user directly to the backend's Google authentication redirect endpoint
        return redirect()->away(config('services.backend.public_url') . '/api/auth/google');
    }

    public function callback(Request $request)
    {
        $token = $request->query('token');
        
        if (!$token) {
            $error = $request->query('error', 'Gagal login dengan Google.');
            return redirect()->route('login')->with('error', $error);
        }

        try {
            // Retrieve user details from backend using the provided Sanctum token
            $response = Http::withToken($token)
                ->get(config('services.backend.url') . '/api/user');

            if ($response->successful()) {
                $userData = $response->json();

                // Save token and user data in frontend session
                session([
                    'backend_token' => $token,
                    'backend_user'  => $userData
                ]);

                // Hydrate User model and login locally on the frontend
                $user = ApiHydrator::hydrateSingle(User::class, $userData);
                Auth::login($user);
                $request->session()->regenerate();

                return $user->isAdmin()
                    ? redirect()->route('admin.dashboard')
                    : redirect()->route('member.home');
            }
        } catch (\Exception $e) {
            // Fail silently and redirect to login
        }

        return redirect()->route('login')->with('error', 'Gagal memproses login Google.');
    }
}