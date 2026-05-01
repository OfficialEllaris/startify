<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CopiedTrader extends Model
{
    protected $fillable = [
        'user_id',
        'trader_id',
        'profit',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trader()
    {
        return $this->belongsTo(Trader::class);
    }
}
