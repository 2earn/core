<?php

namespace App\Models;

use Core\Models\Platform;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class Coupon extends Model
{
    use HasFactory, HasAuditing;

    protected $fillable = [
        'pin',
        'sn',
        'attachment_date',
        'purchase_date',
        'consumption_date',
        'value',
        'consumed',
        'status',
        'reserved_until',
        'platform_id',
        'user_id',
        'created_by',
        'updated_by',
    ];

    public function platform()
    {
        return $this->hasOne(Platform::class, 'id', 'platform_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
