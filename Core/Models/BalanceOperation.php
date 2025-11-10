<?php

namespace Core\Models;

use App\Models\BFSsBalances;
use App\Models\CashBalances;
use App\Models\DiscountBalances;
use App\Models\OperationCategory;
use App\Models\SharesBalances;
use App\Models\SMSBalances;
use App\Models\TreeBalances;
use App\Traits\HasAuditing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BalanceOperation extends Model
{
    use HasAuditing;

    protected $table = 'balance_operations';

    protected $fillable = [
        'operation',
        'io',
        'source',
        'mode',
        'amounts_id',
        'note',
        'modify_amount',
        'parent_id',
        'operation_category_id',
        'ref',
        'direction',
        'created_by',
        'updated_by',
    ];

    public function cashBalance(): HasMany
    {
        return $this->hasMany(CashBalances::class);
    }

    public function bfssBalances(): HasMany
    {
        return $this->hasMany(BFSsBalances::class);
    }

    public function discountBalances(): HasMany
    {
        return $this->hasMany(DiscountBalances::class);
    }

    public function smsBalances(): HasMany
    {
        return $this->hasMany(SMSBalances::class);
    }

    public function actionBalances(): HasMany
    {
        return $this->hasMany(SharesBalances::class);
    }

    public function treeBalances(): HasMany
    {
        return $this->hasMany(TreeBalances::class);
    }

    public function opeartionCategory(): HasOne
    {
        return $this->hasOne(OperationCategory::class);
    }

    public function parent()
    {
        return $this->belongsTo(BalanceOperation::class, 'parent_id');
    }


    public function getIoAttribute(): ?string
    {
        $direction = $this->attributes['direction'] ?? 'IN';
        return match (strtoupper($direction)) {
            'IN'  => 'I',
            'OUT' => 'O',
            default => 'I',
        };
    }

    public function setIoAttribute($value): void
    {
        $this->attributes['direction'] = strtoupper($value) === 'O' ? 'OUT' : 'IN';
    }


    public function getAmountsIdAttribute(): ?int
    {
        return $this->attributes['balance_id'] ?? null;
    }

    public function setAmountsIdAttribute($value): void
    {
        $this->attributes['balance_id'] = $value;
    }

    public function getSourceAttribute(): ?string
    {
        return $this->attributes['relateble_model'] ?? null;
    }

    public function setSourceAttribute($value): void
    {
        $this->attributes['relateble_model'] = $value;
    }

    public function getModeAttribute(): ?string
    {
        return $this->attributes['relateble_types'] ?? null;
    }

    public function setModeAttribute($value): void
    {
        $this->attributes['relateble_types'] = $value;
    }

    public static function getMultiplicator($balanceOperationID): int
    {
        return strtoupper(BalanceOperation::where('id', $balanceOperationID)->pluck('direction')->first()) == "IN" ? 1 : -1;
    }
}
