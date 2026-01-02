<?php

namespace App\Livewire;

use App\Services\UserContactNumberService;
use App\Services\UserService;
use Core\Enum\TypeEventNotificationEnum;
use Core\Enum\TypeNotificationEnum;
use Core\Services\settingsManager;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class EditPhoneNumber extends Component
{
    protected UserService $userService;
    protected UserContactNumberService $contactNumberService;

    protected $listeners = [
        'PreChangePhone' => 'PreChangePhone',
        'UpdatePhoneNumber' => 'UpdatePhoneNumber'
    ];

    public function boot(UserService $userService, UserContactNumberService $contactNumberService)
    {
        $this->userService = $userService;
        $this->contactNumberService = $contactNumberService;
    }

    public function render()
    {
        return view('livewire.edit-phone-number');
    }

    public function UpdatePhoneNumber($code, $phonenumber, $fullNumber, $codeP, $iso, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;

        $user = $settingsManager->getUserById($userAuth->id);
        if (!$user) return;

        if ($code != $user->activationCodeValue)
            return redirect()->route("account", app()->getLocale())
                ->with('danger', Lang::get('Invalid OPT code'));

        $country = $settingsManager->getCountryByIso($iso);

        // Update user's phone information using UserService
        $this->userService->updateByIdUser(auth()->user()->idUser, [
            'mobile' => $phonenumber,
            'fullphone_number' => $fullNumber,
            'idCountry' => $country->id,
            'activationCodeValue' => $code,
            'id_phone' => $codeP
        ]);

        // Check if contact number exists
        $existeNumner = $this->contactNumberService->findByMobileAndIsoForUser(
            $phonenumber,
            $iso,
            $userAuth->idUser
        );

        if ($existeNumner) {
            // Update existing contact and activate it
            $this->contactNumberService->updateAndActivate($existeNumner->id, $userAuth->idUser);
        } else {
            // Create new contact number
            $newC = $settingsManager->createUserContactNumberByProp(
                $userAuth->idUser,
                $phonenumber,
                $country->id,
                $iso,
                $fullNumber
            );
            // Activate the new contact
            $this->contactNumberService->createAndActivate($newC->id, $userAuth->idUser);
        }

        return redirect()->route("account", app()->getLocale())
            ->with('success', Lang::get('Phone number changed'));
    }

    public function PreChangePhone($phone, $fullNumber, $methodeVerification, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;

        $user = $settingsManager->getUserById($userAuth->id);
        if (!$user) return;

        if ($user->fullphone_number == $fullNumber)
            return redirect()->route("account", app()->getLocale())
                ->with('danger', Lang::get('Same phone number'));

        $userExiste = $settingsManager->getUserByFullNumber($fullNumber);
        if ($userExiste && $userExiste->id != $userAuth->id)
            return redirect()->route("account", app()->getLocale())
                ->with('danger', Lang::get('Phone number used'));

        if ($user->email == null || $user->email == "") {
            abort(404);
        }

        $check_exchange = rand(1000, 9999);

        // Update activation code using UserService
        $this->userService->updateActivationCodeValue($userAuth->id, $check_exchange);

        $sendin = "";
        if ($methodeVerification == 'mail') {
            $settingsManager->NotifyUser($userAuth->id, TypeEventNotificationEnum::OPTVerification, [
                'canSendMail' => 1,
                'msg' => $check_exchange,
                'toMail' => $user->email,
                'emailTitle' => "2Earn.cash"
            ]);
            $sendin = $user->email;
        } elseif ($methodeVerification == 'phone') {
            $settingsManager->NotifyUser($userAuth->id, TypeEventNotificationEnum::OPTVerification, [
                'fullNumber' => $fullNumber,
                'msg' => $check_exchange,
                'type' => TypeNotificationEnum::SMS
            ]);
            $sendin = $fullNumber;
        } else {
            return;
        }

        $this->dispatch('PreChPhone', [
            'type' => 'warning',
            'title' => "Opt",
            'text' => '',
            'FullNumber' => $sendin,
            'methodeVerification' => $methodeVerification
        ]);
    }
}
