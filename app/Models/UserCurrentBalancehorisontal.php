<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCurrentBalanceHorisontal extends Model
{
    protected $fillable = [
        'user_id',
        'user_id_auto',
        'cash_balance',
        'bfss_balance',
        'cash_balance',
        'discount_balance',
        'tree_balance',
        'sms_balance',
        'share_balance',
        'chances_balance',
    ];

    use HasFactory;
}
