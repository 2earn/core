<?php

namespace App\Livewire;


use App\Models\CashBalances;
use Carbon\Carbon;
use Core\Services\BalancesManager;
use Core\Services\settingsManager;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SharesSoldMarketStatus extends Component
{
    public $cashBalance;
    public $balanceForSopping;
    public $discountBalance;
    public $SMSBalance;
    public $cash = 25.033;
    private settingsManager $settingsManager;
    private BalancesManager $balancesManager;

    protected $listeners = [
        'checkContactNumbre' => 'checkContactNumbre'
    ];

    function checkContactNumbre()
    {
        dd('ddd');
    }

    public function mount(settingsManager $settingsManager, BalancesManager $balancesManager)
    {
        $this->settingsManager = $settingsManager;
        $this->balancesManager = $balancesManager;
    }

    public function getIp()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
    }

    public function render(settingsManager $settingsManager)
    {
        $user = $settingsManager->getAuthUser();

        if (!$user)
            dd('not found page');
        $solde = $this->balancesManager->getBalances($user->idUser, -1);
        $this->cashBalance = $solde->soldeCB;
        $this->balanceForSopping = $solde->soldeBFS;
        $this->discountBalance = $solde->soldeDB;
        $this->SMSBalance = $solde->soldeSMS;


        $arraySoldeD = [];
        $solde = $this->balancesManager->getCurrentBalance($user->idUser, -1);
        $s = $this->balancesManager->getBalances($user->idUser, -1);
        $soldeCBd = $solde->soldeCB;
        $soldeBFSd = $solde->soldeBFS;
        $soldeDBd = $solde->soldeDB;
        array_push($arraySoldeD, $soldeCBd);
        array_push($arraySoldeD, $soldeBFSd);
        array_push($arraySoldeD, $soldeDBd);
        $usermetta_info = collect(DB::table('metta_users')->where('idUser', $user->idUser)->first());
        $dateAujourdhui = Carbon::now()->format('Y-m-d');
        $vente_jour = CashBalances::where('balance_operation_id', 42)
            ->where('beneficiary_id', $user->idUser)
            ->whereDate('created_at', '=', $dateAujourdhui)
            ->selectRaw('SUM(value) as total_sum')->first()->total_sum;
        $vente_total = CashBalances::where('balance_operation_id', 42)
            ->where('beneficiary_id', $user->idUser)
            ->selectRaw('SUM(value) as total_sum')->first()->total_sum;

        $params = [
            "solde" => $s,
            "vente_jour" => $vente_jour,
            "vente_total" => $vente_total,
            'arraySoldeD' => $arraySoldeD,
            'usermetta_info' => $usermetta_info
        ];
        return view('livewire.shares-sold-market-status', $params)->extends('layouts.master')->section('content');
    }
}






