<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fine extends Model
{
    protected $fillable = ['loan_id', 'hari_terlambat', 'total_denda', 'status_bayar'];

    protected $casts = ['total_denda' => 'decimal:2'];

    public function loan(): BelongsTo { return $this->belongsTo(Loan::class); }
}
