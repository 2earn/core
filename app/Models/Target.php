<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function group()
    {
        return $this->hasMany(Group::class);
    }
    public function condition()
    {
        return $this->hasMany(Condition::class);
    }
}
