<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'qty',
        'shipping',
        'unit_price',
        'total_amount',

        'partner_discount_percentage',
        'partner_discount',
        'amount_after_partner_discount',

        'earn_discount_percentage',
        'earn_discount',
        'amount_after_earn_discount',

        'deal_discount_percentage',
        'deal_discount',
        'amount_after_deal_discount',

        'total_discount_with_discount_partner',
        'ponderation_with_discount_partner',
        'total_discount_percentage_with_discount_partner',

        'refund_dispatching',
        'final_amount',
        'final_discount',

        'final_discount_without_discount_partner',
        'discount_value_without_discount_partner',
        'discount_percentage_without_discount_partner',

        'order_id',
        'item_id',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class,'item_id','id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id','id');
    }
}
