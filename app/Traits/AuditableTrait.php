<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

/**
 * Trait AuditableTrait
 *
 * Automatically populates created_by and updated_by fields
 * with the currently authenticated user's ID.
 *
 * Usage:
 * Add this trait to any model that has created_by and updated_by fields:
 *
 * class YourModel extends Model
 * {
 *     use AuditableTrait;
 * }
 */
trait AuditableTrait
{
    /**
     * Boot the auditable trait for a model.
     */
    protected static function bootAuditableTrait(): void
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = $model->created_by ?? Auth::id();
                $model->updated_by = $model->updated_by ?? Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
    }

    /**
     * Get the user who created this record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by')->withDefault([
            'name' => 'System'
        ]);
    }

    /**
     * Get the user who last updated this record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updater()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by')->withDefault([
            'name' => 'System'
        ]);
    }
}

