<?php

namespace App\Http\Livewire;

use App\Http\Traits\earnLog;
use Core\Services\settingsManager;

use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;
use Illuminate\Http\Request;

class Login extends Component
{
    use RedirectsUsers;
    use earnLog;

    protected $listeners = [
        'login' => 'login',
        'changeLanguage' => 'changeLanguage'
    ];

    public function render(settingsManager $settingsManager)
    {
        return view('livewire.login')->extends('layouts.master-without-nav')->section('content');
    }

    public function login($number, $code, $pass, $iso, settingsManager $settingsManager, Request $request)
    {
        if ($number == "" || $code == "" || $pass == "" || $iso == "") {
            return redirect()->route('login', ['locale' => app()->getLocale()])->with('messageFailedInformation', Lang::get('your phone or your password is incorrect !'));
        }
        if (strlen($iso) > 2) {
            return redirect()->route('login', ['locale' => app()->getLocale()])->with('messageFailedIso', Lang::get('Code_pays_incorrect'));
        }
        $user = $settingsManager->loginUser(str_replace(' ', '', $number), $code, false, $pass, $iso);
        if (!$user) {
            $this->earnDebug('Failed login : number phone -  ' . $number . ' Code pays- : ' . $code . ' Password- : ' . $pass . ' Iso- :' . $iso);
            return redirect()->route('login', ['locale' => app()->getLocale()])->with('message', Lang::get('your phone or your password is incorrect !'));
        }
        return redirect()->intended(route('home', app()->getLocale()))->with('fromLogin', Lang::get('fromLogin'));
    }
}
