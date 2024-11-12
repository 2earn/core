<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model;

class BalanceOperation extends Model
{
    protected $table = 'balance_operations';

    protected $fillable = [
        'designation',
        'io',
        'source',
        'mode',
        'amounts_id',
        'note',
        'modify_amount',
    ];

}
