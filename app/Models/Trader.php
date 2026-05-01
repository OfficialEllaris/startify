<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trader extends Model
{
    protected $fillable = [
        'name',
        'avatar',
        'strategy',
        'win_rate',
        'profit_percentage',
        'min_investment',
        'risk_level',
        'total_copiers',
        'is_active',
    ];

    public function copiers()
    {
        return $this->hasMany(CopiedTrader::class);
    }
}
