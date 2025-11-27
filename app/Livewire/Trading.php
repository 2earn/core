<?php

namespace App\Livewire;

use App\Models\SharesBalances;
use App\Models\vip;
use App\Services\Settings\SettingService;
use Core\Services\BalancesManager;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Trading extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    const MAX_AMOUNT = 99999999;
    const MAX_ACTIONS = 9999999;
    public $flash = false;
    public $cashBalance;
    public $action;
    public $gift;
    public $profit;
    public $ammount;
    public $currency = '$';
    public $maxActions;
    public $flashGift = 0;
    public $flashTimes = 1;
    public $flashPeriod;
    public $flashDate;
    public $flashMinShares = -1;
    public $flashGain = 0;
    public $selledActions = 0;
    public $actions = 0;
    public $totalActions = 0;
    public $precentageOfActions = 0;
    public $precentageOfSharesSale = 0;
    public $numberSharesSale = 0;
    public $giftedShares = 0;
    public $estimatedGain = 0;
    public $selledActionCursor = 0;
    public $totalPaied = 0;
    public $userSelledActionNumber = 0;
    public $actionValue = 0;
    public $targetDate = null;

    // Livewire table properties
    public $perPage = 10;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';


    public function mount()
    {
        $this->giftedShares = getSettingIntegerParam('GIFTED_SHARES', 0);
        $this->targetDate = getSettingStringParam('TARGET_DATE', 0);
        $this->totalActions = getSettingIntegerParam('Actions Number', 0) - $this->giftedShares;

        $this->selledActions = intval(getSelledActions(true));
        $this->precentageOfActions = round($this->selledActions / $this->totalActions, 3) * 100;

        $this->numberSharesSale = $this->totalActions - $this->giftedShares;
        $this->precentageOfSharesSale = round($this->selledActions / $this->numberSharesSale, 3) * 100;
        $this->userSelledActionNumber = round(SharesBalances::where('balance_operation_id', 44)->where('beneficiary_id', Auth()->user()->idUser)->selectRaw('SUM(value) as total_sum')->first()->total_sum);

        //------------------------------
        $this->selledActionCursor = $this->selledActions;
        $this->totalPaied = round(SharesBalances::where('balance_operation_id', 44)->where('beneficiary_id', Auth()->user()->idUser)->selectRaw('SUM(total_amount) as total_sum')->first()->total_sum, 3);
        //------------------------------

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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function getSharesData()
    {
        $query = SharesBalances::query()
            ->where('beneficiary_id', auth()->user()->idUser)
            ->select('*');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('id', 'like', '%' . $this->search . '%')
                  ->orWhere('created_at', 'like', '%' . $this->search . '%')
                  ->orWhere('value', 'like', '%' . $this->search . '%');
            });
        }

        $query->orderBy($this->sortField, $this->sortDirection);

        return $query->paginate($this->perPage);
    }


    public function render(BalancesManager $balancesManager, SettingService $settingService)
    {

        $actualActionValue = actualActionValue(getSelledActions(true), false);
        $this->vip = vip::Where('idUser', '=', auth()->user()->idUser)->where('closed', '=', false)->first();
        if ($this->vip) {
            $settingValues = $settingService->getIntegerValues(['20', '18']);
            $max_bonus = $settingValues['20'];
            $total_actions = $settingValues['18'];
            $k = $settingService->getDecimalValue('21');
            $this->actions = find_actions($this->vip->solde, $total_actions, $max_bonus, $k, $this->vip->flashCoefficient);
            $this->benefices = ($this->vip->solde - find_actions($this->vip->solde, $total_actions, $max_bonus, $k, $this->vip->flashCoefficient)) * $actualActionValue;
            $this->cout = formatSolde($this->actions * $actualActionValue / (($this->actions * $this->vip->flashCoefficient) + getGiftedActions($this->actions)), 2);
            $this->flashTimes = $this->vip->flashCoefficient;
            $this->flashPeriod = $this->vip->flashDeadline;
            $this->flashDate = $this->vip->dateFNS;
            $this->flashMinShares = $this->vip->flashMinAmount;
            $currentDateTime = new \DateTime();
            $dateFlash = new \DateTime($this->flashDate);
            $interval = new \DateInterval('PT' . $this->flashPeriod . 'H');
            $dateFlash = $dateFlash->add($interval);
            $this->flashDate = $dateFlash->format('F j, Y G:i:s');
            $this->flash = $currentDateTime < $dateFlash;
        }
        $params = [
            "soldeBuyShares" => $balancesManager->getBalances(auth()->user()->idUser, 2),
            "shares" => $this->getSharesData(),
            "currentActionValue" => actualActionValue(getSelledActions(true))
        ];

        return view('livewire.trading', $params)->extends('layouts.master')->section('content');
    }
}
