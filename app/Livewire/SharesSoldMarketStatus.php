<?php

namespace App\Livewire;


use App\Models\CashBalances;
use App\Services\Balances\CashBalancesService;
use App\Services\Balances\ShareBalanceService;
use Carbon\Carbon;
use Core\Services\BalancesManager;
use Core\Services\settingsManager;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class SharesSoldMarketStatus extends Component
{
    use WithPagination;

    public $cashBalance;
    public $balanceForSopping;
    public $discountBalance;
    public $SMSBalance;
    public $cash = 25.033;

    public $search = '';
    public $perPage = 100;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public $showModal = false;
    public $selectedId;
    public $selectedPhone;
    public $selectedAmount;
    public $selectedAmountTotal;
    public $selectedAsset;

    protected $listeners = [
        'checkContactNumbre' => 'checkContactNumbre'
    ];

    function checkContactNumbre()
    {
        dd('ddd');
    }


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function openModal($id, $phone, $amount, $asset)
    {
        $this->selectedId = $id;
        $this->selectedPhone = $phone;
        $this->selectedAmount = str_replace(',', '', $amount);
        $this->selectedAmountTotal = $this->selectedAmount;
        $this->selectedAsset = $asset;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['selectedId', 'selectedPhone', 'selectedAmount', 'selectedAmountTotal', 'selectedAsset']);
    }

    public function updateBalance()
    {
        $shareBalanceService = app(ShareBalanceService::class);
        $success = $shareBalanceService->updateShareBalance(
            $this->selectedId,
            $this->selectedAmount
        );

        if ($success) {
            $this->closeModal();
            $this->dispatch('balance-updated');
            session()->flash('message', __('Balance updated successfully'));
        } else {
            session()->flash('error', __('Error updating balance'));
        }
    }

    private function getFormatedFlagResourceName($alpha2)
    {
        return asset('assets/images/flags/' . strtolower($alpha2) . '.svg');
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
        return null;
    }

    public function render(
        settingsManager $settingsManager,
        BalancesManager $balancesManager,
        ShareBalanceService $shareBalanceService,
        CashBalancesService $cashBalancesService
    )
    {
        $user = $settingsManager->getAuthUser();

        if (!$user)
            dd('not found page');

        $solde = $balancesManager->getBalances($user->idUser, -1);

        $this->cashBalance = $solde->soldeCB;
        $this->balanceForSopping = $solde->soldeBFS;
        $this->discountBalance = $solde->soldeDB;
        $this->SMSBalance = $solde->soldeSMS;


        $arraySoldeD = [];
        $solde = $balancesManager->getCurrentBalance($user->idUser, -1);
        $s = $balancesManager->getBalances($user->idUser, -1);
        $soldeCBd = $solde->soldeCB;
        $soldeBFSd = $solde->soldeBFS;
        $soldeDBd = $solde->soldeDB;
        array_push($arraySoldeD, $soldeCBd);
        array_push($arraySoldeD, $soldeBFSd);
        array_push($arraySoldeD, $soldeDBd);
        $usermetta_info = collect(DB::table('metta_users')->where('idUser', $user->idUser)->first());

        $salesData = $cashBalancesService->getSalesData($user->idUser, 42);
        $vente_jour = $salesData['today'];
        $vente_total = $salesData['total'];

        $sharesSoldes = $shareBalanceService->getSharesSoldesData(
            $this->search,
            $this->perPage,
            $this->sortField,
            $this->sortDirection
        );

        $params = [
            "solde" => $s,
            "vente_jour" => $vente_jour,
            "vente_total" => $vente_total,
            'arraySoldeD' => $arraySoldeD,
            'usermetta_info' => $usermetta_info,
            'sharesSoldes' => $sharesSoldes,
        ];
        return view('livewire.shares-sold-market-status', $params)->extends('layouts.master')->section('content');
    }
}
