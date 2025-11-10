<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class Target extends Model
{
    use HasFactory, HasAuditing;

    protected $fillable = [
        'name',
        'description',
        'created_by',
        'updated_by',
    ];

    public function surveys()
    {
        return $this->morphedByMany(Survey::class, 'targetable');
    }

    public function group()
    {
        return $this->hasMany(Group::class);
    }

    public function condition()
    {
        return $this->hasMany(Condition::class);
    }
}
