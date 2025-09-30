<?php

namespace App\Http\Controllers;

use Core\Services\settingsManager;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function __construct(private readonly settingsManager $settingsManager)
    {
    }

    public function index()
    {
        $condition = "";
        if ($this->settingsManager->getAuthUser() == null) {
            $idUser = "";
        } else {
            $idUser = $this->settingsManager->getAuthUser()->idUser;
        }
        $type = request()->type;
        switch ($type) {
            case('out'):
                $condition = " where recharge_requests.idPayee = ";
                break;
            case('in'):
                $condition = " where recharge_requests.idUser = ";
                break;
        }
        if ($condition == "") {
            $condition = " where recharge_requests.idUser = ";
        }

        $request = DB::select(getSqlFromPath('get_request') . $condition . "  ? ", [$idUser]);
        return datatables($request)
            ->make(true);
    }

}
