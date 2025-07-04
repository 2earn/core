<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceInjectorCoupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'pin',
        'sn',
        'attachment_date',
        'purchase_date',
        'consumption_date',
        'value',
        'consumed',
        'status',
        'category',
        'type',
        'reserved_until',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
