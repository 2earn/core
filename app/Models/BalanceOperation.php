<?php
namespace App\Models;
use App\Models\BFSsBalances;
use App\Models\CashBalances;
use App\Models\DiscountBalances;
use App\Models\OperationCategory;
use App\Models\SharesBalances;
use App\Models\SMSBalances;
use App\Models\TreeBalances;
use App\Traits\HasAuditing;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
class BalanceOperation extends Model
{
    use HasAuditing, HasFactory;
    protected $table = 'balance_operations';
    protected $fillable = [
        'operation',
        'io',
        'source',
        'mode',
        'amounts_id',
        'note',
        'modify_amount',
        'operation_category_id',
        'ref',
        'direction',
        'balance_id',
        'parent_operation_id',
        'relateble',
        'relateble_model',
        'relateble_types',
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
    public function parent()
    {
        return $this->belongsTo(BalanceOperation::class, 'parent_operation_id');
    }
    public function operationCategory(): HasOne
    {
        return $this->hasOne(OperationCategory::class, 'id', 'operation_category_id');
    }
    public static function getMultiplicator($balanceOperationID): int
    {
        return strtoupper(BalanceOperation::where('id', $balanceOperationID)->pluck('direction')->first()) == 'IN' ? 1 : -1;
    }
}
