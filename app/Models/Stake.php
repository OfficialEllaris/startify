<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stake extends Model
{
    protected $fillable = [
        'user_id',
        'asset_id',
        'amount',
        'validator_id',
        'apy',
        'status',
        'earned_rewards',
        'last_reward_at',
    ];

    protected $casts = [
        'last_reward_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
