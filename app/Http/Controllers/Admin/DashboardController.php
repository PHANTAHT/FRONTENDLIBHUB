<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Helpers\ApiHydrator;
use App\Models\Booking;
use App\Models\Loan;

class DashboardController extends Controller
{
    public function index()
    {
        $token = session('backend_token');

        try {
            $response = Http::withToken($token)
                ->get(config('services.backend.url') . '/api/admin/dashboard');

            if ($response->successful()) {
                $data = $response->json();
                $stats = $data['stats'] ?? [];
                $recentLoans = ApiHydrator::hydrateCollection(Loan::class, $data['recentLoans'] ?? []);
                $recentBookings = ApiHydrator::hydrateCollection(Booking::class, $data['recentBookings'] ?? []);
            } else {
                $stats = [];
                $recentLoans = collect();
                $recentBookings = collect();
            }
        } catch (\Exception $e) {
            $stats = [];
            $recentLoans = collect();
            $recentBookings = collect();
        }

        return view('admin.dashboard', compact('stats', 'recentLoans', 'recentBookings'));
    }
}
