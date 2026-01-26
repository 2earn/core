<?php

namespace App\Livewire;

use App\Enums\TypeEventNotificationEnum;
use App\Enums\TypeNotificationEnum;
use App\Http\Traits\earnTrait;
use App\Models\User;
use App\Services\UserContactService;
use App\Services\UserService;
use App\Services\settingsManager;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class AddContactNumber extends Component
{
    use earnTrait;

    protected UserContactService $userContactService;
    protected UserService $userService;

    protected $listeners = [
        'preSaveContact' => 'preSaveContact',
        'saveContactNumber' => 'saveContactNumber'
    ];

    public function boot(UserContactService $userContactService, UserService $userService)
    {
        $this->userContactService = $userContactService;
        $this->userService = $userService;
    }

    public function preSaveContact($fullNumber, $isoP, $mobile, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;

        $country = $settingsManager->getCountryByIso($isoP);
        if (!$country) return;

        // Get required data for verification
        $numberID = $settingsManager->getNumberCOntactID($userAuth->idUser)->fullNumber;
        $userMail = $settingsManager->getUserById($userAuth->id)->email;

        // Prepare contact number verification using service
        $result = $this->userContactService->prepareContactNumberVerification(
            $userAuth->idUser,
            $userAuth->id,
            $fullNumber,
            $isoP,
            $mobile,
            $userMail,
            $numberID
        );

        if (!$result['success']) {
            $this->dispatch('showAlert', [
                'type' => $result['alertType'],
                'title' => trans('Error'),
                'text' => trans($result['message'])
            ]);
            return;
        }

        // Update user with OTP code
        User::where('id', $userAuth->id)->update(['OptActivation' => $result['otpCode']]);

        // Send SMS notification
        if ($result['shouldNotifyBySms']) {
            $settingsManager->NotifyUser(
                $userAuth->id,
                TypeEventNotificationEnum::OPTVerification,
                [
                    'fullNumber' => $numberID,
                    'msg' => $result['otpCode'],
                    'type' => TypeNotificationEnum::SMS,
                    'isoP' => $isoP
                ]
            );
        }

        // Send email notification if user has email
        if ($result['shouldNotifyByEmail']) {
            $settingsManager->NotifyUser(
                $userAuth->id,
                TypeEventNotificationEnum::NewContactNumber,
                [
                    'canSendMail' => 1,
                    'msg' => $result['otpCode'],
                    'toMail' => $userMail,
                    'emailTitle' => "2Earn.cash"
                ]
            );
        }

        // Dispatch Livewire event with verification params
        $this->dispatch('PreAddNumber', $result['verificationParams']);
    }

    public function saveContactNumber($code, $iso, $mobile, $fullNumber, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;

        $user = $settingsManager->getUserById($userAuth->id);
        if (!$user) return;

        $country = $settingsManager->getCountryByIso($iso);
        if (!$country) return;

        // Verify and save contact number using service
        $result = $this->userContactService->verifyAndSaveContactNumber(
            $userAuth->id,
            $userAuth->idUser,
            $code,
            $user->OptActivation,
            $mobile,
            $country->id,
            $iso,
            $fullNumber
        );

        $flashType = $result['success'] ? 'success' : 'danger';
        return redirect()->route('contact_number', app()->getLocale())
            ->with($flashType, Lang::get($result['message']));
    }

    public function render()
    {
        return view('livewire.add-contact-number')->extends('layouts.master')->section('content');
    }
}
