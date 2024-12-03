<?php

namespace App\Models;

use App\Http\Livewire\Platform;
use Core\Models\BalanceOperation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChanceBalances extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'description',
        'current_balance',
        'reference',
    ];

    public function item()
    {
        return $this->hasOne(Item::class);
    }

    public function deal()
    {
        return $this->hasOne(Deal::class);
    }

    public function balanceOperation()
    {
        return $this->hasOne(BalanceOperation::class);
    }

    public function pool()
    {
        return $this->hasOne(Pool::class);
    }


    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function beneficiary()
    {
        return $this->belongsTo(User::class, 'beneficiary_id_auto');
    }

    public function platform()
    {
        return $this->hasOne(Platform::class);
    }

    public function activity()
    {
        return $this->hasOne(Activity::class);
    }


    public function chanceable(): MorphTo
    {
        return $this->morphTo();
    }
}
