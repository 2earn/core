<?php

namespace App\Observers;

use Core\Models\user_balance;
use Core\Services\BalancesManager;
use Core\Services\settingsManager;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserBalanceObserver
{



    public function __construct(settingsManager $settingsManager,BalancesManager $balancesManager)
    {
        $this->settingsManager = $settingsManager;
        $this->balancesManager=$balancesManager ;
        $this->name = "fsdf";
    }
    private BalancesManager $balancesManager;

    public function saving(user_balance $user_balance)
    {
        //dd('saving') ;
    }

    public function creating(user_balance $user_balance)
    {
      //  dd('creating') ;
    }

    public function created(user_balance $user_balance)
    {       $table=[13,14,23,24,29,46,51] ;

           if(in_array($user_balance->idBalancesOperation,$table) )
           {
               $setting=\Core\Models\Setting::WhereIn('idSETTINGS',['22','23'])->orderBy('idSETTINGS')->pluck('IntegerValue') ;
               $md=$setting[0] ;
               $rc =$setting[1] ;
               $Count = DB::table('user_balances')->count();
               //$ref = '47' . date('ymd') . substr((10000 + $Count + 1), 1, 4);
               $ub=new user_balance([
                   'ref' => $user_balance->ref,
                   'idBalancesOperation' => 47,
                   'Date' => date('Y-m-d H:i:s'),
                   'idSource' => '11111111',
                   'idUser' => $user_balance->idUser,
                   'idamount' => 3,
                   'value' => min($md,$user_balance->value*(pow(abs($user_balance->value-10),1.5)/$rc)),
                   'WinPurchaseAmount' => "0.000",
                   'PrixUnitaire' => 1,
                   'Description' => number_format(100*min($md,$user_balance->value*(pow(abs($user_balance->value-10),1.5)/$rc))/$md,2,'.','').'%',
                   'Balance' => $this->balancesManager->getBalances($user_balance->idUser)->soldeDB+min($md,$user_balance->value*(pow(abs($user_balance->value-10),1.5)/$rc))
               ]); $ub->save() ;
            //   dd(min($md,(pow(abs($user_balance->value-10),1.5)/$rc)) ) ;
           }
          //  dd($user_balance->idBalancesOperation) ;
    }

    public function updating(user_balance $user_balance)
    {

        //dd('updating') ;
    }

}
