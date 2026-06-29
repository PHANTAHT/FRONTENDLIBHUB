<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Helpers\ApiHydrator;
use App\Models\Booking;

class BookingController extends Controller
{
    public function store(Request $request, $bookId)
    {
        $request->validate([
            'tanggal_booking' => ['required', 'date', 'after_or_equal:today'],
            'tanggal_kembali' => ['required', 'date', 'after:tanggal_booking'],
        ], [
            'tanggal_kembali.after' => 'Tanggal pengembalian harus setelah tanggal pengambilan.',
        ]);

        $token = session('backend_token');

        try {
            $response = Http::withToken($token)
                ->post(config('services.backend.url') . "/api/member/books/{$bookId}/booking", [
                    'tanggal_booking' => $request->input('tanggal_booking'),
                    'tanggal_kembali' => $request->input('tanggal_kembali')
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $booking = ApiHydrator::hydrateSingle(Booking::class, $data['booking']);
                
                return redirect()->route('member.booking.pay', $booking->id);
            } else {
                $err = $response->json();
                if ($response->status() === 422) {
                    return back()->withErrors($err['errors'] ?? [])->withInput();
                }
                return back()->with('error', $err['message'] ?? 'Gagal membuat booking.')->withInput();
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghubungi server backend: ' . $e->getMessage())->withInput();
        }
    }

    public function pay($bookingId)
    {
        $token = session('backend_token');

        try {
            $response = Http::withToken($token)
                ->get(config('services.backend.url') . "/api/member/booking/{$bookingId}/pay");

            if ($response->successful()) {
                $data = $response->json();
                $booking = ApiHydrator::hydrateSingle(Booking::class, $data['booking']);
                $snapToken = $data['snapToken'];
                $biayaSewa = $data['biayaSewa'];
                $deposit = $data['deposit'];
                $total = $data['total'];

                return view('member.payment', compact('booking', 'snapToken', 'biayaSewa', 'deposit', 'total'));
            } else {
                $err = $response->json();
                return redirect()->route('member.bookings')->with('error', $err['message'] ?? 'Gagal memuat pembayaran.');
            }
        } catch (\Exception $e) {
            return redirect()->route('member.bookings')->with('error', 'Gagal memuat pembayaran: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $token = session('backend_token');

        try {
            $response = Http::withToken($token)
                ->get(config('services.backend.url') . '/api/member/bookings');

            if ($response->successful()) {
                $data = $response->json();
                $bookings = ApiHydrator::hydrateCollection(Booking::class, $data['bookings'] ?? []);
            } else {
                $bookings = collect();
            }
        } catch (\Exception $e) {
            $bookings = collect();
        }

        return view('member.bookings', compact('bookings'));
    }

    public function cancel($bookingId)
    {
        $token = session('backend_token');

        try {
            $response = Http::withToken($token)
                ->patch(config('services.backend.url') . "/api/member/bookings/{$bookingId}/cancel");

            if ($response->successful()) {
                $data = $response->json();
                return back()->with('status', $data['message'] ?? 'Booking dibatalkan.');
            } else {
                $err = $response->json();
                return back()->with('error', $err['message'] ?? 'Booking tidak bisa dibatalkan.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan booking: ' . $e->getMessage());
        }
    }

    public function edit($bookingId)
    {
        $token = session('backend_token');
        
        try {
            $response = Http::withToken($token)
                ->get(config('services.backend.url') . '/api/member/bookings');

            if ($response->successful()) {
                $data = $response->json();
                $bookings = ApiHydrator::hydrateCollection(Booking::class, $data['bookings'] ?? []);
                $booking = collect($bookings)->firstWhere('id', (int) $bookingId);
                if ($booking) {
                    if ($booking->status !== 'pending_payment') {
                        return redirect()->route('member.bookings')->with('error', 'Booking yang sudah aktif (dibayar) tidak dapat diubah.');
                    }
                    return view('member.booking-edit', compact('booking'));
                }
            }
            
            return redirect()->route('member.bookings')->with('error', 'Booking tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->route('member.bookings')->with('error', 'Gagal memuat data booking: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $bookingId)
    {
        $request->validate([
            'tanggal_booking' => ['required', 'date', 'after_or_equal:today'],
            'tanggal_kembali' => ['required', 'date', 'after:tanggal_booking'],
        ], [
            'tanggal_kembali.after' => 'Tanggal pengembalian harus setelah tanggal pengambilan.',
        ]);

        $token = session('backend_token');

        try {
            $response = Http::withToken($token)
                ->put(config('services.backend.url') . "/api/member/bookings/{$bookingId}", [
                    'tanggal_booking' => $request->input('tanggal_booking'),
                    'tanggal_kembali' => $request->input('tanggal_kembali')
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return redirect()->route('member.bookings')->with('status', $data['message'] ?? 'Booking berhasil diperbarui.');
            } else {
                $err = $response->json();
                if ($response->status() === 422) {
                    return back()->withErrors($err['errors'] ?? [])->withInput();
                }
                return back()->with('error', $err['message'] ?? 'Gagal memperbarui booking.')->withInput();
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghubungi server backend: ' . $e->getMessage())->withInput();
        }
    }
}