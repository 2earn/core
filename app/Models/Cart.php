<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_cart',
        'total_cart_quantity',
        'user_id',
        'cart_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function cartItem(): HasMany
    {
        return $this->hasMany(CartItem::class, 'cart_id', 'id');
    }
}
