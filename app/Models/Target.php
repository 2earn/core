<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Target extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function surveys(): BelongsToMany
    {
        return $this->morphedByMany(Survey::class, 'targetable');
    }

    public function group(): HasMany
    {
        return $this->hasMany(Group::class);
    }

    public function condition(): HasMany
    {
        return $this->hasMany(Condition::class);
    }
}
