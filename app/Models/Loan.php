<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Loan extends Model
{
    protected $fillable = [
        'user_id', 'tanggal_pinjam', 'tanggal_tenggat',
        'tanggal_kembali', 'status', 'denda',
        'biaya_sewa', 'deposit', 'deposit_dikembalikan',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_tenggat' => 'date',
        'tanggal_kembali' => 'date',
        'denda' => 'decimal:2',
        'biaya_sewa' => 'decimal:2',
        'deposit' => 'decimal:2',
        'deposit_dikembalikan' => 'decimal:2',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function items(): HasMany { return $this->hasMany(LoanItem::class); }
    public function fine(): HasOne { return $this->hasOne(Fine::class); }

    public function isOverdue(): bool
    {
        return $this->status !== 'kembali'
            && $this->tanggal_tenggat
            && $this->tanggal_tenggat->isPast();
    }

    public function lateDays(): int
    {
        if (! $this->tanggal_tenggat) return 0;
        $end = $this->tanggal_kembali ?? now();
        return $end->lte($this->tanggal_tenggat) ? 0 : $this->tanggal_tenggat->diffInDays($end);
    }
    public function dendaEstimasi(): float
    {
        if ($this->status === 'kembali') {
            return (float) $this->denda;
        }
        if (! $this->isOverdue()) return 0;
        $lateDays = $this->lateDays();
        $jumlah = $this->jumlahBuku();
        return (float) ($lateDays * $jumlah * (int) config('perpustakaan.denda_per_hari'));
    }
    public function jumlahBuku(): int
    {
        return (int) $this->items->sum('jumlah') ?: 1;
    }

    public function totalAwal(): float
    {
        return (float) $this->biaya_sewa + (float) $this->deposit;
    }
}
