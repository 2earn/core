<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionBreakDown extends Model
{
    use HasFactory;

    protected $fillable = [
        'trigger',
        'amount',
        'percentage',
        'value',
        'additional',
        'camembert',
        'earn',
        'pool',
        'cashback_proactif',
        'tree',
        'order_id',
        'deal_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function deal()
    {
        return $this->hasOne(Deal::class, 'deal_id', 'id');
    }
}
