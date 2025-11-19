<?php

namespace App\Models;

use Core\Models\Platform;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlatformValidationRequest extends Model
{
    protected $fillable = [
        'platform_id',
        'status',
        'rejection_reason',
    ];

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }
}

