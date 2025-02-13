<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDeal extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_amount',
        'partner_discount',
        'amount_after_partner_discount',
        'earn_discount',
        'amount_after_earn_discount',
        'deal_discount_percentage',
        'deal_discount',
        'amount_after_deal_discount',
        'total_discount',
        'final_discount_percentage',
        'lost_discount',
        'final_amount',
        'final_discount',
        'deal_id',
        'order_id',
    ];

    public function deals()
    {
        return $this->belongsTo(Deal::class, 'deal_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

}
