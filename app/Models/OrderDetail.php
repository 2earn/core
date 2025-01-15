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
        'partner_discount',
        'partner_discount_percentage',
        'amount_after_partner_discount',
        'earn_discount_percentage',
        'earn_discount',
        'amount_after_earn_discount',
        'deal_discount_percentage',
        'deal_discount',
        'amount_after_deal_discount',
        'total_discount',
        'ponderation',
        'total_discount_percentage',
        'refund_dispatching',
        'final_amount',
        'final_discount',
        'final_discount_percentage',
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
