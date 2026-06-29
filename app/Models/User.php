<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'nama_lengkap', 'email', 'no_telp', 'alamat', 'role',
        'status_keanggotaan', 'google_id', 'avatar', 'password',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function loans(): HasMany { return $this->hasMany(Loan::class); }
    public function bookings(): HasMany { return $this->hasMany(Booking::class); }

    public function isAdmin(): bool { return $this->role === 'admin'; }

    // Untuk kompatibilitas dengan helper bawaan yang memakai "name"
    public function getNameAttribute(): string { return $this->nama_lengkap; }

    public function initials(): string
    {
        $parts = preg_split('/\s+/', trim($this->nama_lengkap));
        $first = mb_substr($parts[0] ?? '', 0, 1);
        $last = count($parts) > 1 ? mb_substr(end($parts), 0, 1) : '';
        return mb_strtoupper($first . $last);
    }
}
