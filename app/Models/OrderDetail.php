<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'qty',
        'unit_price',
        'total_amount',
        'partner_discount_percentage',
        'amount_after_partner_discount',
        '2_earn_discount_percentage',
        '2_earn_discount',
        'amount_after_2_earn_discount',
        'deal_discount_percentage',
        'deal_discount',
        'amount_after_deal_discount',
        'total_discount',
        'total_discount_percentage',
        'refund_dispatching',
        'final_amount',
        'final_discount',
        'final_discount_percentage',
        'missed_discount',
    ];

    public function Item()
    {
        return $this->belongsTo(Item::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
