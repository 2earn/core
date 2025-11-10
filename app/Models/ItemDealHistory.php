<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class ItemDealHistory extends Model
{
    use HasFactory, HasAuditing;

    protected $fillable = [
        'start_date',
        'end_date',
        'item_id',
        'deal_id',
        'created_by',
        'updated_by',
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
