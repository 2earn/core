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
        'consumption_date',
        'value',
        'consumed',
        'status',
        'category',
        'type',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function consume($coupon)
    {
        return $coupon->update(['user_id' => auth()->user()->id, 'consumed' => 1, 'consumption_date' => now()]);
    }

}
