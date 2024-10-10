<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['operator', 'target_id'];

    public function target(): BelongsTo
    {
        return $this->belongsTo(Target::class, 'target_id', 'id');
    }

    public function condition(): HasMany
    {
        return $this->hasMany(Condition::class);
    }
}
