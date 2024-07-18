<?php

namespace App\Http\Livewire;

use App\Http\Traits\earnLog;
use App\Models\User;
use Core\Enum\TypeEventNotificationEnum;
use Core\Enum\TypeNotificationEnum;
use Core\Services\settingsManager;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;


class ForgotPassword extends Component
{
    use earnLog;

    public $locales;
    public $check;

    protected $listeners = [
        'checkopt' => 'checkopt',
        'Presend' => 'PresendSms',
        'sendSms' => 'sendSms'
    ];

    public function PresendSms($ccode, $fullNumber, settingsManager $settingsManager)
    {
        $user = $settingsManager->getUserByFullNumber($fullNumber);

        if (!$user) {
            $this->earnDebug('Forget password user not found : fullNumber- ' . $fullNumber . ' code pays- ' . $ccode);
            return redirect()->route("forget_password", app()->getLocale())->with('ErrorUserFound', Lang::get('User not found !'));
        }
        $act_code = rand(1000, 9999);
        User::where('id', $user->id)->update(['activationCodeValue' => $act_code]);
        $settingsManager->NotifyUser($user->id, TypeEventNotificationEnum::ForgetPassword, [
            'msg' => $act_code,
            'type' => TypeNotificationEnum::SMS
        ]);
        $this->earnDebug('Forget password sms sended : fullNumber- ' . $fullNumber . ' code pays- ' . $ccode . 'OPT-' . $act_code);

        $userContactActif = $settingsManager->getidCountryForSms($user->id);
        $fullNumberSend = $userContactActif->fullNumber;

        $this->dispatchBrowserEvent('OptForgetPass', [
            'type' => 'warning',
            'title' => "Opt",
            'text' => '',
            'FullNumber' => $fullNumberSend,
        ]);
    }

    public function sendSms($codeOPT, $phoneNumber, settingsManager $settingsManager)
    {
        $user = $settingsManager->getUserByFullNumber($phoneNumber);
        if (!$user) {
            $this->earnDebug('Forget password input opt user not found  : fullNumber- ' . $phoneNumber);
            return redirect()->route("forget_password", app()->getLocale())->with('ErrorUserFound', Lang::get('User not found'));
        }
        if ($codeOPT != $user->activationCodeValue) {
            $this->earnDebug('Forget password input opt code OPT invalide  : fullNumber- ' . $phoneNumber . ' codeOPT- ' . $codeOPT);
            return redirect()->route("forget_password", app()->getLocale())->with('ErrorOptCodeForget', Lang::get('Invalid_OPT_code'));
        }

        return redirect()->route("reset_password", ["locale" => app()->getLocale(), "idUser" => Crypt::encryptString($user->idUser)]);
    }

    public function render()
    {
        $this->locales = config('app.available_locales');
        return view('livewire.forgot-password')->extends('layouts.master-without-nav')->section('content');
    }
}
