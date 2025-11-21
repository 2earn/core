<?php

namespace App\Livewire;


use App\Models\CashBalances;
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

    // Livewire properties
    public $search = '';
    public $perPage = 100;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Modal properties
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
        try {
            // Call the update balance route logic
            $data = [
                'total' => $this->selectedAmountTotal,
                'amount' => $this->selectedAmount,
                'id' => $this->selectedId,
            ];

            // You would typically call a service here
            DB::table('shares_balances')
                ->where('id', $this->selectedId)
                ->update([
                    'current_balance' => $this->selectedAmount,
                    'payed' => 1,
                ]);

            $this->closeModal();
            $this->dispatch('balance-updated');

            session()->flash('message', __('Balance updated successfully'));
        } catch (\Exception $e) {
            session()->flash('error', __('Error updating balance'));
        }
    }

    private function getFormatedFlagResourceName($alpha2)
    {
        return asset('assets/images/flags/' . strtolower($alpha2) . '.svg');
    }

    public function getSharesSoldesData()
    {
        $query = DB::table('shares_balances')
            ->select(
                'current_balance',
                'payed',
                'countries.apha2',
                'shares_balances.id',
                DB::raw('CONCAT(nvl( meta.arFirstName,meta.enFirstName), \' \' ,nvl( meta.arLastName,meta.enLastName)) AS Name'),
                'user.mobile',
                DB::raw('CAST(value AS DECIMAL(10,0)) AS value'),
                'shares_balances.value as raw_value',
                DB::raw('CAST(shares_balances.unit_price AS DECIMAL(10,2)) AS unit_price'),
                'shares_balances.created_at',
                'shares_balances.payed as payed',
                'shares_balances.beneficiary_id'
            )
            ->join('users as user', 'user.idUser', '=', 'shares_balances.beneficiary_id')
            ->join('metta_users as meta', 'meta.idUser', '=', 'user.idUser')
            ->join('countries', 'countries.id', '=', 'user.idCountry')
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('user.mobile', 'like', '%' . $this->search . '%')
                      ->orWhere(DB::raw('CONCAT(nvl( meta.arFirstName,meta.enFirstName), \' \' ,nvl( meta.arLastName,meta.enLastName))'), 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection);

        return $query->paginate($this->perPage);
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

    public function render(settingsManager $settingsManager, BalancesManager $balancesManager)
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
        $dateAujourdhui = Carbon::now()->format('Y-m-d');
        $vente_jour = CashBalances::where('balance_operation_id', 42)
            ->where('beneficiary_id', $user->idUser)
            ->whereDate('created_at', '=', $dateAujourdhui)
            ->selectRaw('SUM(value) as total_sum')->first()->total_sum;
        $vente_total = CashBalances::where('balance_operation_id', 42)
            ->where('beneficiary_id', $user->idUser)
            ->selectRaw('SUM(value) as total_sum')->first()->total_sum;

        $sharesSoldes = $this->getSharesSoldesData();

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






