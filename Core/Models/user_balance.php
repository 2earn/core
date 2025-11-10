<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditing;

class user_balance extends Model
{
    use HasAuditing;

    protected $fillable = [
        'id_item',
        'item_title',
        'id_plateform',
        'Date',
        'idBalancesOperation',
        'Description',
        'idSource',
        'idUser',
        'idamount',
        'value',
        'Balance',
        'WinPurchaseAmount',
        'PU',
        'gifted_shares',
        'Block_trait',
        'ref',
        'PrixUnitaire',
        'created_by',
        'updated_by',
    ];

    public $timestamps = true;

    public function userearn()
    {
        return $this->belongsTo(user_earn::class, 'idUser', 'idUser');
    }
}
