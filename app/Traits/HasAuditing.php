<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait HasAuditing
{
    /**
     * Boot the trait and register the model events.
     */
    protected static function bootHasAuditing(): void
    {
        // Set created_by and updated_by on creating
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = $model->created_by ?? Auth::id();
                $model->updated_by = $model->updated_by ?? Auth::id();
            }
        });

        // Set updated_by on updating
        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
    }

    /**
     * Get the user who created the record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model', \App\Models\User::class), 'created_by');
    }

    /**
     * Get the user who last updated the record.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model', \App\Models\User::class), 'updated_by');
    }
}

