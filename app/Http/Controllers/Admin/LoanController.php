<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Helpers\ApiHydrator;
use App\Models\Booking;
use App\Models\Loan;

class LoanController extends Controller
{
    public function index()
    {
        $token = session('backend_token');

        try {
            $response = Http::withToken($token)
                ->get(config('services.backend.url') . '/api/admin/loans');

            if ($response->successful()) {
                $data = $response->json();
                $openLoans = ApiHydrator::hydrateCollection(Loan::class, $data['openLoans'] ?? []);
                $reservedBookings = ApiHydrator::hydrateCollection(Booking::class, $data['reservedBookings'] ?? []);
            } else {
                $openLoans = collect();
                $reservedBookings = collect();
            }
        } catch (\Exception $e) {
            $openLoans = collect();
            $reservedBookings = collect();
        }

        return view('admin.loans.index', compact('openLoans', 'reservedBookings'));
    }

    public function history()
    {
        $token = session('backend_token');

        try {
            $response = Http::withToken($token)
                ->get(config('services.backend.url') . '/api/admin/loans/history');

            if ($response->successful()) {
                $loans = ApiHydrator::hydratePaginated(Loan::class, $response->json()['loans'] ?? []);
            } else {
                $loans = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
            }
        } catch (\Exception $e) {
            $loans = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
        }

        return view('admin.loans.history', compact('loans'));
    }

    public function confirmFromBooking(Request $request)
    {
        $request->validate(['kode_booking' => ['required', 'string']]);

        $token = session('backend_token');

        try {
            $response = Http::withToken($token)
                ->post(config('services.backend.url') . '/api/admin/loans/confirm', [
                    'kode_booking' => $request->input('kode_booking')
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return back()->with('status', $data['message']);
            } else {
                $err = $response->json();
                return back()->with('error', $err['message'] ?? 'Gagal mengonfirmasi peminjaman.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghubungi server backend: ' . $e->getMessage());
        }
    }

    public function confirmReturn($id)
    {
        $token = session('backend_token');

        try {
            $response = Http::withToken($token)
                ->patch(config('services.backend.url') . "/api/admin/loans/{$id}/return");

            if ($response->successful()) {
                $data = $response->json();
                return back()->with('status', $data['message']);
            } else {
                $err = $response->json();
                return back()->with('error', $err['message'] ?? 'Gagal mengembalikan buku.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }
    }

    public function payFine($id)
    {
        $token = session('backend_token');

        try {
            $response = Http::withToken($token)
                ->patch(config('services.backend.url') . "/api/admin/fines/{$id}/pay");

            if ($response->successful()) {
                $data = $response->json();
                return back()->with('status', $data['message']);
            } else {
                $err = $response->json();
                return back()->with('error', $err['message'] ?? 'Gagal membayar denda.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses denda: ' . $e->getMessage());
        }
    }

    public function rejectBooking($id)
    {
        $token = session('backend_token');

        try {
            $response = Http::withToken($token)
                ->patch(config('services.backend.url') . "/api/admin/bookings/{$id}/reject");

            if ($response->successful()) {
                $data = $response->json();
                return back()->with('status', $data['message']);
            } else {
                $err = $response->json();
                return back()->with('error', $err['message'] ?? 'Gagal membatalkan booking.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembatalan: ' . $e->getMessage());
        }
    }
}
