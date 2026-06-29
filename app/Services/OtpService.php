<?php

namespace App\Services;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    private const TTL_MINUTES = 10;       // kode berlaku 10 menit
    private const COOLDOWN_SECONDS = 60;  // jeda kirim ulang

    /** Buat kode OTP baru, simpan, lalu kirim ke email user. */
    public function send(User $user): void
    {
        $code = (string) random_int(100000, 999999);

        Cache::put($this->codeKey($user), $code, now()->addMinutes(self::TTL_MINUTES));
        Cache::put(
            $this->cooldownKey($user),
            now()->addSeconds(self::COOLDOWN_SECONDS)->timestamp,
            now()->addSeconds(self::COOLDOWN_SECONDS)
        );

        Mail::to($user->email)->send(new OtpMail($user, $code));
    }

    /** Cek kode. Kalau benar, hapus dari cache. */
    public function verify(User $user, string $code): bool
    {
        $valid = Cache::get($this->codeKey($user));

        if ($valid && hash_equals((string) $valid, trim($code))) {
            Cache::forget($this->codeKey($user));
            return true;
        }

        return false;
    }

    /** Sisa detik sebelum boleh kirim ulang (0 = boleh sekarang). */
    public function secondsUntilResend(User $user): int
    {
        $ts = Cache::get($this->cooldownKey($user));
        return $ts ? max(0, (int) $ts - now()->timestamp) : 0;
    }

    private function codeKey(User $user): string
    {
        return "otp_code:{$user->id}";
    }

    private function cooldownKey(User $user): string
    {
        return "otp_cooldown:{$user->id}";
    }
}