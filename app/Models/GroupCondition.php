<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupCondition extends Model
{
    use HasFactory;

    protected $fillable = [
        'operand',
        'operator',
        'value',
        'target_group_id',
    ];

    public function group()
    {
        return $this->belongsTo(TargetGroup::class, 'target_group_id', 'id');
    }

}
