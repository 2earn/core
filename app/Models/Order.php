<?php

namespace App\Models;

use Core\Enum\OrderEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use InvalidArgumentException;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'out_of_deal_amount',
        'deal_amount_before_discount',
        'total_order',
        'total_order_quantity',
        'deal_amount_after_discounts',
        'amount_after_discount',
        'paid_cash',
        'commission_2_earn',
        'deal_amount_for_partner',
        'commission_for_camembert',
        'total_final_discount',
        'total_final_discount_percentage',
        'total_lost_discount',
        'total_lost_discount_percentage',
        'user_id',
        'note',
        'status',
    ];
    protected $casts = ['status' => OrderEnum::class];

    public function OrderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function updateStatus(OrderEnum $newStatus)
    {
        if (!OrderEnum::tryFrom($newStatus->value)) {
            throw new InvalidArgumentException("Invalid status provided.");
        }
        $this->status = $newStatus;
        $this->save();
        return $this->status;
    }

}
