<?php

namespace App\Auth;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Http;
use App\Helpers\ApiHydrator;
use App\Models\User;

class BackendUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        $token = session('backend_token');
        if (!$token) return null;

        if (session()->has('backend_user')) {
            $userData = session('backend_user');
            return ApiHydrator::hydrateSingle(User::class, $userData);
        }

        try {
            $response = Http::withToken($token)
                ->get(config('services.backend.url') . '/api/user');

            if ($response->successful()) {
                $userData = $response->json();
                session(['backend_user' => $userData]);
                return ApiHydrator::hydrateSingle(User::class, $userData);
            }
        } catch (\Exception $e) {
            // Fail silently
        }

        return null;
    }

    public function retrieveByToken($identifier, $token)
    {
        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // No-op
    }

    public function retrieveByCredentials(array $credentials)
    {
        try {
            $response = Http::post(config('services.backend.url') . '/api/login', [
                'email' => $credentials['email'] ?? '',
                'password' => $credentials['password'] ?? '',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['verified']) && !$data['verified']) {
                    // Email unverified - put pending user details in session
                    session(['otp_user_id' => $data['user_id']]);
                    return null;
                }

                $token = $data['token'] ?? null;
                $userData = $data['user'] ?? null;

                if ($token && $userData) {
                    session(['backend_token' => $token, 'backend_user' => $userData]);
                    return ApiHydrator::hydrateSingle(User::class, $userData);
                }
            } else {
                $err = $response->json();
                if ($response->status() === 403) {
                    session(['login_error' => $err['message'] ?? 'Akun Anda telah dinonaktifkan oleh admin.']);
                }
            }
        } catch (\Exception $e) {
            // Fail silently
        }

        return null;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return true;
    }

    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false)
    {
        // No-op
    }
}
