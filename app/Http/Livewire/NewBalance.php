<?php

namespace App\Http\Livewire;

use App\Services\Balances\BalancesFacade;
use Core\Models\Setting;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NewBalance extends Component
{
    function checkUserBalancesInReservation($idUser)
    {
        $reservation = Setting::Where('idSETTINGS', '32')->orderBy('idSETTINGS')->pluck('IntegerValue')->first();


        $result = DB::table('user_balances as u')
            ->where('idUser', $idUser)
            ->select(DB::raw('TIMESTAMPDIFF(HOUR, ' . DB::raw('DATE') . ', NOW()))'))
            ->where('idBalancesOperation', 44)
            ->whereRaw('TIMESTAMPDIFF(HOUR, ' . DB::raw('DATE') . ', NOW()) < ?', [$reservation])
            ->count();
        return $result ?? null;
    }

    public function render()
    {
        $idUser = "197604171";
        dd($this->checkUserBalancesInReservation($idUser),$this->checkUserBalancesInReservation($idUser));
        return view('livewire.new-balance')->extends('layouts.master')->section('content');
    }
}
