<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model;

class user_balance extends Model
{
    protected $fillable = [

        'id_item', 'item_title', 'id_plateform', 'Date', 'idBalancesOperation', 'Description', 'idSource', 'idUser', 'idamount', 'value', 'Balance', 'WinPurchaseAmount', 'PU', 'gifted_shares', 'Block_trait', 'ref', 'PrixUnitaire'
    ];

    //
    public $timestamps = false;
    public function userearn()
    {

        return $this->belongsTo(user_earn::class ,'idUser','idUser');

    }

}
