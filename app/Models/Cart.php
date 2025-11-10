<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasAuditing;

class Cart extends Model
{
    use HasFactory, HasAuditing;

    protected $fillable = [
        'total_cart',
        'total_cart_quantity',
        'shipping',
        'user_id',
        'cart_id',
        'created_by',
        'updated_by',
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
