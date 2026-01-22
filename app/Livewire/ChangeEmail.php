<?php

namespace App\Livewire;

use App\Enums\TypeEventNotificationEnum;
use App\Enums\TypeNotificationEnum;
use App\Http\Traits\earnTrait;
use App\Services\UserService;
use Carbon\Carbon;
use App\Services\settingsManager;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class ChangeEmail extends Component
{
    use earnTrait;

    public $newEmail = '';
    public $user;
    public $numberActif;

    protected UserService $userService;

    protected $listeners = [
        'sendVerificationMail' => 'sendVerificationMail',
        'saveVerifiedMail' => 'saveVerifiedMail',
        'checkUserEmail' => 'checkUserEmail',
        'cancelProcess' => 'cancelProcess',
    ];

    protected $rules = [
        'newEmail' => 'required|email|max:255',
    ];

    public function boot(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function mount(settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) {
            abort(404);
        }

        $this->user = $this->userService->findById($userAuth->id);
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
        $us = $this->userService->findById($this->user->id);
        $this->userService->updateUser($us, [
            'OptActivation' => $opt,
            'OptActivation_at' => Carbon::now()
        ]);

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
        $us = $this->userService->findById($this->user->id);
        if (is_null($codeOpt) || $codeOpt != $us->OptActivation) {
            session()->flash('danger', Lang::get('Invalid OPT code'));
            return;
        }

        $check_exchange = $this->randomNewCodeOpt();
        $this->userService->updateOptActivation(auth()->user()->id, $check_exchange);

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
        $us = $this->userService->findById($this->user->id);
        if (is_null($codeOpt) || $codeOpt != $us->OptActivation) {
            session()->flash('danger', Lang::get('Change user email failed - Code OPT'));
            return;
        }

        $this->userService->updateUser($us, [
            'email_verified' => 1,
            'email' => $this->newEmail,
            'email_verified_at' => Carbon::now()
        ]);

        return redirect()->route('user_form', app()->getLocale())
            ->with('success', Lang::get('User email change completed successfully'));
    }

    public function render()
    {
        return view('livewire.change-email')->extends('layouts.master')->section('content');
    }
}

