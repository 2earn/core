<?php

namespace Core\Models;

use App\Models\SharesBalances;
use App\Models\BFSsBalances;
use App\Models\CashBalances;
use App\Models\DiscountBalances;
use App\Models\SMSBalances;
use App\Models\TreeBalances;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BalanceOperation extends Model
{
    protected $table = 'balance_operations';

    protected $fillable = [
        'operation',
        'io',
        'source',
        'mode',
        'amounts_id',
        'note',
        'modify_amount',
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

    public static function getMultiplicator($treeBalances): int
    {
        return BalanceOperation::where('id', $treeBalances->balance_operation_id)->pluck('io') == 'I' ? 1 : -1;
    }

}
