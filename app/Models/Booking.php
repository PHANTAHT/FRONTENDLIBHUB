<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'kode_booking', 'user_id', 'book_id',
        'tanggal_booking', 'tanggal_rencana_kembali',
        'tanggal_expired', 'status',
        'midtrans_order_id', 'snap_token', 'payment_status',
    ];

    protected $casts = [
        'tanggal_booking'          => 'date',
        'tanggal_rencana_kembali'  => 'date',
        'tanggal_expired'          => 'date',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function book(): BelongsTo { return $this->belongsTo(Book::class); }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }
}