<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ref',
        'price',
        'discount',
        'photo_link',
        'description',
        'stock',
        'deal_id',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class);
    }

    public function deal()
    {
        return $this->belongsTo(Deal::class, 'deal_id', 'id');
    }

    public function OrderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function shares()
    {
        return $this->hasMany(Share::class);
    }
}
