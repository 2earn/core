<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class OrderDetail extends Model
{
    use HasFactory, HasAuditing;

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

        'total_discount',

        'order_id',
        'item_id',

        'note',
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
