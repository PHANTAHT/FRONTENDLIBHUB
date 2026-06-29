<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! $request->user() || $request->user()->role !== $role) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Dynamic backend verification to intercept deactivated users
        $token = session('backend_token');
        if ($token) {
            try {
                $response = Http::withToken($token)
                    ->get(config('services.backend.url') . '/api/user');

                if ($response->status() === 403 || !$response->successful()) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('login')
                        ->with('error', 'Akun Anda telah dinonaktifkan oleh admin. Silakan hubungi admin.');
                }
            } catch (\Exception $e) {
                // Fail silently to keep app usable if there's a temporary connection issue
            }
        }

        return $next($request);
    }
}
