<?php

namespace App\Models;

use Core\Enum\OrderEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'additional_tax',
        'total_shipping',
        'total_price',
        'total_price_after_discount',
        'note',
        'total_price_after_bfss',
        'total_discount_gain',
        'total_bfs_paid',
        'total_supplement',
        'total_supplement_after_bfs',
        'total_supplement_paid',
        'status',
    ];
    protected $casts = ['status' => OrderEnum::class];
    public function OrderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function updateStatus(OrderEnum $newStatus)
    {
        if ($this->status === OrderEnum::Paid->value && $newStatus === OrderEnum::Dispatched) {
            $this->status = $newStatus->value;
        } elseif ($this->status !== OrderEnum::Failed->value && $this->status !== OrderEnum::Dispatched->value) {
            $this->status = $newStatus->value;
        }
        $this->save();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
