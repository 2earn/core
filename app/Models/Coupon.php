<?php

namespace App\Models;

use App\Http\Livewire\Platform;
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
    ];

    public function platform()
    {
        return $this->hasOne(Platform::class);
    }
}
