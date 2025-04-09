<?php

namespace App\Livewire;

use App\Http\Traits\earnLog;
use Core\Services\settingsManager;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Login extends Component
{
    use RedirectsUsers;
    use earnLog;

    protected $listeners = [
        'login' => 'login',
        'changeLanguage' => 'changeLanguage'
    ];

    public ?string $from = null;

    public function mount(Request $request)
    {
        $this->from = $request->query->get('form');
    }

    public function login($number, $code, $pass, $iso, settingsManager $settingsManager, Request $request)
    {
        if ($number == "" || $code == "" || $pass == "" || $iso == "") {
            return redirect()->route('login', ['locale' => app()->getLocale()])->with('danger', Lang::get('your phone or your password is incorrect !'));
        }
        if (strlen($iso) > 2) {
            return redirect()->route('login', ['locale' => app()->getLocale()])->with('danger', Lang::get('Code_pays_incorrect'));
        }
        $user = $settingsManager->loginUser(str_replace(' ', '', $number), $code, false, $pass, $iso);
        if (!$user) {
            $this->earnDebug('Failed login : number phone -  ' . $number . ' Code pays- : ' . $code . ' Password- : ' . $pass . ' Iso- :' . $iso);
            return redirect()->route('login', ['locale' => app()->getLocale()])->with('danger', Lang::get('your phone or your password is incorrect !'));
        }
        if (!is_null($this->from)) {
            Log::info('Inscription from Site 2earn :: code:' . $code . ' number: ' . $number);
            return redirect()->intended(route('home', app()->getLocale()))->with('from', $this->from);
        }
        return redirect()->intended(route('home', app()->getLocale()));
    }

    public function render(settingsManager $settingsManager)
    {
        return view('livewire.login')->extends('layouts.master-without-nav')->section('content');
    }

}
