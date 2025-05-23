<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemDealHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'item_id',
        'deal_id'
    ];

    public function Item()
    {
        return $this->hasOne(Item::class);
    }

    public function deal()
    {
        return $this->hasOne(Deal::class);
    }
}
