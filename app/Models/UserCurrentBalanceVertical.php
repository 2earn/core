<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class UserCurrentBalanceVertical extends Model
{
    use HasFactory, HasAuditing;
    protected $fillable = [
        'user_id',
        'user_id_auto',
        'current_balance',
        'previous_balance',
        'balance_id',
        'last_operation_id',
        'last_operation_date',
        'last_operation_value',
        'created_by',
        'updated_by',
    ];
}
