<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'user_id',
        'brand',
        'card_holder_name',
        'number',
        'last_four',
        'expiry',
        'cvv',
        'balance',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
