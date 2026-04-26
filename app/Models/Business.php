<?php

namespace App\Models;

use App\Enums\BusinessStatus;
use App\Enums\BusinessType;
use Database\Factories\BusinessFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'state', 'name', 'type', 'purpose', 'status', 'use_registrar_agent', 'agent_name', 'agent_address', 'submitted_at'])]
class Business extends Model
{
    /** @use HasFactory<BusinessFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => BusinessType::class,
            'status' => BusinessStatus::class,
            'use_registrar_agent' => 'boolean',
            'submitted_at' => 'datetime',
        ];
    }

    /**
     * Get the user who owns this business.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
