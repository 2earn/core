<?php

namespace App\Models;

use Core\Models\Platform;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'pin',
        'attachment_date',
        'purchase_date',
        'consumption_date',
        'value',
        'consumed',
        'platform_id',
    ];

    public function platform()
    {
        return $this->hasOne(Platform::class, 'id', 'platform_id');
    }
}
