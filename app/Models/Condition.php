<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    use HasFactory;

    protected $fillable = [
        'operand',
        'operator',
        'value',
        'group_id',
        'target_id',
    ];

    public function target()
    {
        return $this->belongsTo(Target::class, 'target_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

}
