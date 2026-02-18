<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class CartItem extends Model
{
    use HasFactory, HasAuditing;

    protected $fillable = [
        'cart_id',
        'qty',
        'shipping',
        'unit_price',
        'total_amount',
        'item_id',
        'created_by',
        'updated_by',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
