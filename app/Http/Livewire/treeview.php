<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class treeview extends Component
{
    public $test = '121';

    public function render()
    {
        $results =  DB::select("select 11111111 as id,
       null as SHARES,
       null as pid,
       null as cash,
       null as bfs,
       null as discount,
       '2earn.cash' as cname,
       '2earn.cash' as name,
       '2earn.cash' as title

from dual
union
SELECT users.idUser,
       round(cu.SHARES,0) as SHARES,
       users.idUpline,
       round(cu.CB,2) as cash,
       round(cu.BFS,2) as bfs,
       round(cu.DB,2) as discount,
       countries.name as cname,
       COALESCE(CONCAT(COALESCE(meta.arFirstName, meta.enFirstName), ' ', COALESCE(meta.arLastName, meta.enLastName)),users.mobile) AS name,
       users.mobile

FROM users
         JOIN metta_users as meta ON meta.idUser = users.idUser
         JOIN countries ON countries.id = users.idCountry
         join soldes_transposee cu on users.idUser = cu.idUser
where (idUpline <> 0 or users.idUser in(select idupline from users))


");

        foreach ($results as $result) {
            if($result->id==11111111)
            {
                $setting = \Core\Models\Setting::Where('idSETTINGS', '18')->orderBy('idSETTINGS')->pluck('IntegerValue')->first();
                $sellesActions=getSelledActions();
                $actualActionvalue= actualActionValue($sellesActions);


                $result->SHARES=$setting-$sellesActions;
                $result->ActionsValue=$actualActionvalue*($setting-$sellesActions);
            }
            else{
                $result->ActionsValue = number_format(getUserActualActionsValue($result->id), 2, '.', '') * 1;
                $result->gain = number_format(getUserActualActionsProfit($result->id), 2, '.', '') * 1;
            }



        }


        return view('livewire.treeview', ['nodes' => $results])->extends('layouts.master')->section('content');
    }
}
