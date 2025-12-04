<?php

namespace App\Livewire;

use App\Http\Traits\earnTrait;
use App\Models\User;
use App\Services\UserContactService;
use Core\Enum\TypeEventNotificationEnum;
use Core\Enum\TypeNotificationEnum;
use Core\Models\UserContactNumber;
use Core\Services\settingsManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class ContactNumber extends Component
{
    use earnTrait;

    public $search;

    protected $listeners = [
        'setActiveNumber' => 'setActiveNumber',
        'saveContactNumber' => 'saveContactNumber',
        'deleteContact' => 'deleteContact',
        'preSaveContact' => 'preSaveContact'
    ];

    public function deleteContact($id, settingsManager $settingsManager, UserContactService $userContactService)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) {
            return;
        }

        try {
            $userContactService->deleteContact($id);
            return redirect()->route('contact_number', app()->getLocale())->with('success', trans('Contact number deleted with success'));
        } catch (\Exception $e) {
            return redirect()->route('contact_number', app()->getLocale())->with('danger', trans($e->getMessage()));
        }
    }

    public function saveContactNumber($code, $iso, $mobile, $fullNumber, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;
        $user = $settingsManager->getUserById($userAuth->id);
        if (!$user) return;
        $country = $settingsManager->getCountryByIso($iso);
        if (!$country) return;
        if ($code != $user->OptActivation) {
            return redirect()->route("contact_number", app()->getLocale())->with('danger', Lang::get('Invalid OPT code'));
        }
        $newC = $settingsManager->createUserContactNumberByProp($userAuth->idUser, $mobile, $country->id, $iso, $fullNumber);
        return redirect()->route('contact_number', app()->getLocale())->with('success', trans('Adding contact number completed successfully'));
    }

    public function setActiveNumber($checked, $id, UserContactService $userContactService)
    {
        if (!$checked) {
            return;
        }

        $userContactService->setActiveNumber(auth()->user()->idUser, $id);
        return redirect()->route('contact_number', app()->getLocale())->with('success', trans('Number updated successfully'));
    }


    public function preSaveContact($fullNumber, $isoP, $mobile, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;
        $country = $settingsManager->getCountryByIso($isoP);
        if (!$country) return;
        $check_exchange = $this->randomNewCodeOpt();
        User::where('id', $userAuth->id)->update(['OptActivation' => $check_exchange]);
        $numberID = $settingsManager->getNumberCOntactID($userAuth->idUser)->fullNumber;
        $userMail = $settingsManager->getUserById($userAuth->id)->email;
        $settingsManager->NotifyUser($userAuth->id, TypeEventNotificationEnum::OPTVerification, ['fullNumber' => $numberID, 'msg' => $check_exchange, 'type' => TypeNotificationEnum::SMS, 'isoP' => $isoP]);
        $userMailSend = "";
        if ($userMail != null) {
            $settingsManager->NotifyUser($userAuth->id, TypeEventNotificationEnum::NewContactNumber, ['canSendMail' => 1, 'msg' => $check_exchange, 'toMail' => $userMail, 'emailTitle' => "2Earn.cash"]);
            $userMailSend = $userMail;
        }
        $msgSend = Lang::get('We_will_sendWithMail');
        if ($userMail == null || $userMail == "") {
            $msgSend = Lang::get('We_will_send');
        }
        $params = [
            'type' => 'warning',
            'title' => "Opt",
            'text' => '',
            'FullNumber' => $numberID,
            'FullNumberNew' => $fullNumber,
            'userMail' => $userMail,
            'isoP' => $isoP,
            'mobile' => $mobile,
            'msgSend' => $msgSend
        ];
        $this->dispatch('PreAddNumber', $params);
    }

    public function render(settingsManager $settingsManager, UserContactService $userContactService)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;

        $userContactNumber = $userContactService->getByUserIdWithSearch($userAuth->idUser, $this->search);

        return view('livewire.contact-number', ['userContactNumber' => $userContactNumber])->extends('layouts.master')->section('content');
    }
}
