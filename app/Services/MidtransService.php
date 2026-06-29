<?php

namespace App\Services;

use App\Models\Booking;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;
class MidtransService
{
    public function __construct()
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    /**
     * Buat Snap token untuk booking.
     * Idempotent: kalau snap_token sudah ada dan booking belum dibayar, kembalikan token lama.
     */
    public function getSnapToken(Booking $booking): string
    {
        // Idempotent — kembalikan token lama kalau masih ada
        if ($booking->snap_token && $booking->payment_status === 'unpaid') {
            return $booking->snap_token;
        }

        $pinjam  = $booking->tanggal_booking;
        $kembali = $booking->tanggal_rencana_kembali;
        $durasi  = max(1, $pinjam->diffInDays($kembali));

        $biayaSewa = $durasi * (int) config('perpustakaan.harga_sewa_per_hari');
        $deposit   = (int) config('perpustakaan.deposit_per_buku');
        $total     = $biayaSewa + $deposit;

        $params = [
            'transaction_details' => [
                'order_id' => $booking->midtrans_order_id,
                'gross_amount' => $total,
            ],
            'item_details' => [
                [
                    'id'       => 'sewa-' . $booking->book_id,
                    'price'    => $biayaSewa,
                    'quantity' => 1,
                    'name'     => 'Sewa: ' . $booking->book->judul,
                ],
                [
                    'id'       => 'deposit-' . $booking->book_id,
                    'price'    => $deposit,
                    'quantity' => 1,
                    'name'     => 'Deposit Buku',
                ],
            ],
            'customer_details' => [
                'first_name' => $booking->user->nama_lengkap,
                'email'      => $booking->user->email,
            ],
            'expiry' => [
                'unit'     => 'minutes',
                'duration' => 30,
            ],
            'notification_url' => 'https://nuttiness-unit-conductor.ngrok-free.dev/payment/webhook',
            'callbacks' => [
                'finish' => route('payment.finish', $booking),
            ],
        ];

        $token = Snap::getSnapToken($params);
        
        $booking->update(['snap_token' => $token]);

        return $token;
    }

    /**
     * Handle webhook dari Midtrans.
     * Return array ['status' => 'paid'|'failed'|'expired', 'order_id' => ...]
     */
    public function handleWebhook(): array
    {
        $json = json_decode(file_get_contents('php://input'), true);

        $orderId           = $json['order_id'] ?? null;
        $transactionStatus = $json['transaction_status'] ?? null;
        $fraudStatus       = $json['fraud_status'] ?? null;

        if ($transactionStatus === 'capture') {
            $status = $fraudStatus === 'accept' ? 'paid' : 'failed';
        } elseif ($transactionStatus === 'settlement') {
            $status = 'paid';
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'failure'])) {
            $status = 'failed';
        } elseif ($transactionStatus === 'expire') {
            $status = 'expired';
        } else {
            $status = 'unpaid';
        }

        return ['status' => $status, 'order_id' => $orderId];
    }
}