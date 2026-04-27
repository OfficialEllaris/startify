<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = ['user_id', 'balances', 'addresses'];

    protected $casts = [
        'balances' => 'array',
        'addresses' => 'array',
    ];

    /**
     * Get the user that owns the wallet.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
