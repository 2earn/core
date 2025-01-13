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
        'out_of_deal_amount',
        'deal_amount_before_discount',
        'amount_before_discount',
        'deal_amount_after_partner_discount',
        'deal_amount_after_deal_discount',
        'lost_discount_amount',
        'final_discount_value',
        'final_discount_percentage',
        'deal_amount_after_discounts',
        'paid_cash',
        'commission_2_earn',
        'deal_amount_for_partner',
        'commission_for_camembert',
        'missed_discount',
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
    }
}
