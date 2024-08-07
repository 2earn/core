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
        'target_id',
    ];

    public function target()
    {
        return $this->belongsTo(TargetGroup::class, 'targetGroup_id', 'id');
    }
}
