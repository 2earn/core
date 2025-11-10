<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class Group extends Model
{
    use HasFactory, HasAuditing;

    protected $fillable = ['operator', 'target_id',
        'created_by',
        'updated_by',
    ];

    public function target()
    {
        return $this->belongsTo(Target::class, 'target_id', 'id');
    }

    public function condition()
    {
        return $this->hasMany(Condition::class);
    }
}
