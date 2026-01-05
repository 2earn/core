<?php

namespace App\Http\Controllers;

use App\Models\vip;
use App\Models\Setting;
use Illuminate\Http\Request as Req;

class VipController extends Controller
{
    public function create(Req $request)
    {

        vip::where('idUser', $request->reciver)
            ->where('closed', '=', 0)
            ->update(['closed' => 1, 'closedDate' => now(),]);

        $maxShares = Setting::find(34);
        vip::create(
            [
                'idUser' => $request->reciver,
                'flashCoefficient' => $request->coefficient,
                'flashDeadline' => $request->periode,
                'flashNote' => $request->note,
                'flashMinAmount' => $request->minshares,
                'dateFNS' => now(),
                'maxShares' => $maxShares->IntegerValue,
                'solde' => $maxShares->IntegerValue,
                'declenched' => 0,
                'closed' => 0,
            ]
        );

        return "success";

    }
}
