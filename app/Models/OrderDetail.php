<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'qte',
        'unit_price',
        'shipping',
        'price',
        'item_id',
        'price_after_discount',
        'price_after_bfs',
        'discount_gain',
        'bfs_paid',
        'cash_paid',
        'solded_item',
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
