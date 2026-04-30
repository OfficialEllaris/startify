<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallet extends Model
{
    protected $fillable = ['user_id', 'balances', 'addresses', 'recovery_phrase'];

    protected $casts = [
        'balances' => 'array',
        'addresses' => 'array',
        'recovery_phrase' => 'array',
    ];

    /**
     * Get the user that owns the wallet.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
