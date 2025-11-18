<?php

namespace App\Models;

use Core\Models\Platform;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlatformTypeChangeRequest extends Model
{
    protected $fillable = [
        'platform_id',
        'old_type',
        'new_type',
        'status',
    ];

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }
}

