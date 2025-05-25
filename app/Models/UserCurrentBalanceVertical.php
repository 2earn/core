<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCurrentBalanceVertical extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'user_id_auto',
        'current_balance',
        'balance_id',
        'last_operation_id',
        'last_operation_date',
        'last_operation_value',
    ];
}
