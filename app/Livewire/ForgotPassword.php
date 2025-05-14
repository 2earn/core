<?php

namespace App\Livewire;

use App\Http\Traits\earnLog;
use App\Models\User;
use Core\Enum\StatusRequest;
use Core\Enum\TypeEventNotificationEnum;
use Core\Enum\TypeNotificationEnum;
use Core\Services\settingsManager;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;


class ForgotPassword extends Component
{
    use earnLog;

    const MAX_ATTEMPTS = 3;
    const COOLDOWN_PERIOD = 300;
    const RATE_KEY = 'forgot-password-attempts-';

    public $locales;
    public $check;

    protected $listeners = [
        'checkopt' => 'checkopt',
        'Presend' => 'PresendSms',
        'sendSms' => 'sendSms'
    ];

    public function PresendSms($ccode, $fullNumber, settingsManager $settingsManager)
    {
        $key = self::RATE_KEY . $fullNumber;

        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS)) {
            Cache::put('blocked-user-' . $fullNumber, true, now()->addMinutes(10));
            return redirect()->route("forget_password", app()->getLocale())
                ->with('danger', Lang::get('Too many attempts! Please try again later.'));
        }

        RateLimiter::hit($key, self::COOLDOWN_PERIOD); // Set the cooldown period (e.g., 60 seconds)

        $user = $settingsManager->getUserByFullNumber($fullNumber);
        if (!$user) {
            $this->earnDebug('Forget password user not found : fullNumber- ' . $fullNumber . ' code pays- ' . $ccode);
            return redirect()->route("forget_password", app()->getLocale())->with('danger', Lang::get('Bad credentials'));
        }
        if ($user?->status == StatusRequest::Registred->value) {
            $this->earnDebug('Forget password user with not valid status : fullNumber- ' . $fullNumber . ' code pays- ' . $ccode);
            return redirect()->route("registre", app()->getLocale())->with('danger', Lang::get('Registration operation not completed for this user'));
        }

        $act_code = rand(1000, 9999);
        User::where('id', $user->id)->update(['activationCodeValue' => $act_code]);
        $settingsManager->NotifyUser($user->id, TypeEventNotificationEnum::ForgetPassword, ['msg' => $act_code, 'type' => TypeNotificationEnum::SMS]);
        $this->earnDebug('Forget password sms sent : fullNumber- ' . $fullNumber . ' code pays- ' . $ccode . 'OPT-' . $act_code);
        $userContactActif = $settingsManager->getidCountryForSms($user->id);
        $fullNumberSend = $userContactActif->fullNumber;
        $params = [
            'type' => 'warning',
            'title' => "Opt",
            'text' => '',
            'FullNumber' => $fullNumberSend,
        ];
        $this->dispatch('OptForgetPass', $params);
    }

    public function sendSms($codeOPT, $phoneNumber, settingsManager $settingsManager)
    {
        $user = $settingsManager->getUserByFullNumber($phoneNumber);
        if (!$user) {
            $this->earnDebug('Forget password input opt user not found  : fullNumber- ' . $phoneNumber);
            return redirect()->route("forget_password", app()->getLocale())->with('danger', Lang::get('Bad credentials'));
        }
        if ($codeOPT != $user->activationCodeValue) {
            $this->earnDebug('Forget password input opt code OPT invalide  : fullNumber- ' . $phoneNumber . ' codeOPT- ' . $codeOPT);
            return redirect()->route("forget_password", app()->getLocale())->with('danger', Lang::get('Invalid OPT code'));
        }

        RateLimiter::clear(self::RATE_KEY . $phoneNumber);

        return redirect()->route("reset_password", ["locale" => app()->getLocale(), "idUser" => Crypt::encryptString($user->idUser)]);
    }

    public function render()
    {
        $this->locales = config('app.available_locales');
        return view('livewire.forgot-password')->extends('layouts.master-without-nav')->section('content');
    }
}
