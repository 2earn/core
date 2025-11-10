<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class Activity extends Model
{
    use HasFactory, HasAuditing;

    protected $fillable = [
        'name',
        'type',
        'chance',
        'created_by',
        'updated_by',
    ];

    public function chance(): MorphOne
    {
        return $this->morphOne(ChanceBalances::class, 'chanceable');
    }
}
