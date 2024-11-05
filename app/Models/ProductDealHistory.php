<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDealHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'product_id',
        'deal_id'
    ];

    public function product()
    {
        return $this->hasOne(Product::class);
    }

    public function deal()
    {
        return $this->hasOne(Deal::class);
    }
}
