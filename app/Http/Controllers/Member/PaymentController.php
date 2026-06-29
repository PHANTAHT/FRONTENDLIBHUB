<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    /**
     * Halaman status setelah bayar (redirect dari Snap on the frontend).
     */
    public function finish($bookingId)
    {
        $token = session('backend_token');
        
        try {
            $response = Http::withToken($token)
                ->get(config('services.backend.url') . "/api/member/booking/{$bookingId}/pay");

            if ($response->successful()) {
                $data = $response->json();
                $booking = $data['booking'] ?? [];
                $paymentStatus = $booking['payment_status'] ?? 'unpaid';
                $kodeBooking = $booking['kode_booking'] ?? '';

                return redirect()->route('member.bookings')
                    ->with('status', match($paymentStatus) {
                        'paid'    => "Pembayaran berhasil! Kode booking kamu: {$kodeBooking}.",
                        'failed'  => 'Pembayaran gagal. Silakan coba lagi.',
                        'expired' => 'Waktu pembayaran habis. Silakan booking ulang.',
                        default   => 'Pembayaran sedang diproses.',
                    });
            }
        } catch (\Exception $e) {
            // Fail silently
        }

        return redirect()->route('member.bookings')
            ->with('status', 'Pembayaran sedang diproses.');
    }
}