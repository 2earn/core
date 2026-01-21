<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Traits\HasAuditing;

class EntityRole extends Model
{
    use HasFactory, HasAuditing;

    protected $fillable = [
        'name',
        'roleable_id',
        'roleable_type',
        'user_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the parent roleable model (Platform or Partner).
     */
    public function roleable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user associated with this entity role
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the user who created this role
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Get the user who last updated this role
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    /**
     * Scope to get roles for platforms only
     */
    public function scopePlatformRoles($query)
    {
        return $query->where('roleable_type', Platform::class);
    }

    /**
     * Scope to get roles for partners only
     */
    public function scopePartnerRoles($query)
    {
        return $query->where('roleable_type', Partner::class);
    }

    /**
     * Scope to search by name
     */
    public function scopeSearchByName($query, $name)
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }
}
