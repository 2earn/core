<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    use HasFactory;

    public  static $operands = ['=', '!=', '<', '>', '<=', '>='];


    public static $operators = [
        ['name' => '[user] id', 'value' => 'u.id'],
        ['name' => '[user] name', 'value' => 'u.name'],
        ['name' => '[user] email', 'value' => 'u.email'],
        ['name' => '[user] idUpline', 'value' => 'u.idUpline'],
        ['name' => '[user] idUser', 'value' => 'u.idUser'],
        ['name' => '[user] mobile', 'value' => 'u.mobile'],
        ['name' => '[user] fullphone_number', 'value' => 'u.fullphone_number'],
        ['name' => '[user] status', 'value' => 'u.status']
    ];

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
