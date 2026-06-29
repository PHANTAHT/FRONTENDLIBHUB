<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'judul', 'pengarang', 'penerbit', 'tahun_terbit', 'isbn',
        'stok', 'foto_sampul', 'sinopsis', 'jumlah_halaman', 'category_id',
    ];

    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function loanItems(): HasMany { return $this->hasMany(LoanItem::class); }
    public function bookings(): HasMany { return $this->hasMany(Booking::class); }

    // Stok yang sedang ditahan oleh booking aktif (diambil dari pre-calculated attribute dari API backend)
    public function reservedCount(): int
    {
        return (int) ($this->attributes['reserved_count'] ?? 0);
    }

    public function availableStock(): int
    {
        return (int) ($this->attributes['available_stock'] ?? $this->stok);
    }

    public function coverUrl(): string
    {
        if ($this->foto_sampul) {
            return str_starts_with($this->foto_sampul, 'http')
                ? $this->foto_sampul
                : config('services.backend.public_url') . '/storage/' . $this->foto_sampul;
        }
        return 'https://placehold.co/300x440/800B38/F2E2D3?text=' . urlencode($this->judul);
    }
}
