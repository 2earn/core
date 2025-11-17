<?php

namespace App\Livewire;

use App\Http\Traits\earnTrait;
use App\Models\User;
use Carbon\Carbon;
use Core\Enum\TypeEventNotificationEnum;
use Core\Enum\TypeNotificationEnum;
use Core\Services\settingsManager;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class ChangeEmail extends Component
{
    use earnTrait;

    public $newEmail = '';
    public $user;
    public $numberActif;

    protected $listeners = [
        'sendVerificationMail' => 'sendVerificationMail',
        'saveVerifiedMail' => 'saveVerifiedMail',
        'checkUserEmail' => 'checkUserEmail',
        'cancelProcess' => 'cancelProcess',
    ];

    protected $rules = [
        'newEmail' => 'required|email|max:255',
    ];

    public function mount(settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) {
            abort(404);
        }

        $this->user = User::find($userAuth->id);
        $this->numberActif = $settingsManager->getNumberCOntactActif($userAuth->idUser)->fullNumber;
    }

    public function sendVerificationMail(settingsManager $settingsManager)
    {
        $this->validate();

        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) {
            abort(404);
        }

        $mail = trim($this->newEmail);

        if (!isValidEmailAdressFormat($mail)) {
            session()->flash('danger', Lang::get('Not valid Email Format'));
            return;
        }

        if ($userAuth->email == $mail) {
            session()->flash('danger', Lang::get('Same email Address'));
            return;
        }

        $userExisteMail = $settingsManager->getConditionalUser('email', $mail);
        if ($userExisteMail && $userExisteMail->idUser != $userAuth->idUser) {
            session()->flash('danger', Lang::get('mail_used'));
            return;
        }

        $opt = $this->randomNewCodeOpt();
        $us = User::find($this->user->id);
        $us->OptActivation = $opt;
        $us->OptActivation_at = Carbon::now();
        $us->save();

        $settingsManager->NotifyUser($userAuth->id, TypeEventNotificationEnum::VerifMail, [
            'msg' => $opt,
            'type' => TypeNotificationEnum::SMS
        ]);

        $this->dispatch('confirmOPTVerifMail', [
            'type' => 'warning',
            'title' => "Opt",
            'text' => '',
            'numberActif' => $this->numberActif,
            'newMail' => $mail
        ]);
    }

    public function cancelProcess($message)
    {
        session()->flash('warning', Lang::get($message));
    }

    public function checkUserEmail($codeOpt = null, settingsManager $settingsManager)
    {
        $us = User::find($this->user->id);
        if (is_null($codeOpt) || $codeOpt != $us->OptActivation) {
            session()->flash('danger', Lang::get('Invalid OPT code'));
            return;
        }

        $check_exchange = $this->randomNewCodeOpt();
        User::where('id', auth()->user()->id)->update(['OptActivation' => $check_exchange]);

        $settingsManager->NotifyUser(auth()->user()->id, TypeEventNotificationEnum::NewContactNumber, [
            'canSendMail' => 1,
            'msg' => $check_exchange,
            'toMail' => $this->newEmail,
            'emailTitle' => "2Earn.cash"
        ]);

        $this->dispatch('EmailCheckUser', [
            'emailValidation' => true,
            'title' => trans('Opt code from your email'),
            'html' => trans('We sent an opt code to your email') . ' : ' . $this->newEmail . ' <br> ' . trans('Please fill it')
        ]);
    }

    public function saveVerifiedMail($codeOpt = null)
    {
        $us = User::find($this->user->id);
        if (is_null($codeOpt) || $codeOpt != $us->OptActivation) {
            session()->flash('danger', Lang::get('Change user email failed - Code OPT'));
            return;
        }

        $us->email_verified = 1;
        $us->email = $this->newEmail;
        $us->email_verified_at = Carbon::now();
        $us->save();

        return redirect()->route('user_form', app()->getLocale())
            ->with('success', Lang::get('User email change completed successfully'));
    }

    public function render()
    {
        return view('livewire.change-email')->extends('layouts.master')->section('content');
    }
}

