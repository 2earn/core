<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['operator', 'target_id'];

    public function target()
    {
        return $this->belongsTo(Target::class, 'target_id', 'id');
    }

    public function condition()
    {
        return $this->hasMany(Condition::class);
    }
}
