<?php

namespace App\Livewire;

use App\Http\Traits\earnTrait;
use App\Models\User;
use Core\Enum\TypeEventNotificationEnum;
use Core\Enum\TypeNotificationEnum;
use Core\Models\user_earn;
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

    public function deleteContact($id, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) {
            return;
        }
        $number = UserContactNumber::find($id);
        if ($number->active) {
            return redirect()->route('contact_number', app()->getLocale())->with('danger', trans('Failed to delete active number'));
        }
        if ($number->isID == 1) {
            return redirect()->route('contact_number', app()->getLocale())->with('danger', Lang::get('Contact number deleting failed'));
        }
        $deleted = $number->delete();
        return redirect()->route('contact_number', app()->getLocale())->with('success', trans('Contact number deleted with success'));
    }

    public function saveContactNumber($code, $iso, $mobile, $fullNumber, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;
        $user = $settingsManager->getUserById($userAuth->id);
        if (!$user) return;
        $countrie = $settingsManager->getCountryByIso($iso);
        if (!$countrie) return;
        if ($code != $user->OptActivation) {
            return redirect()->route("contact_number", app()->getLocale())->with('danger', Lang::get('Invalid OPT code'));
        }
        $newC = $settingsManager->createUserContactNumberByProp($userAuth->idUser, $mobile, $countrie->id, $iso, $fullNumber);
        return redirect()->route('contact_number', app()->getLocale())->with('success', trans('Adding contact number completed successfully'));
    }

    public function setActiveNumber($checked, $id, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth || !$checked) {
            return;
        }
        $userContactNumber = UserContactNumber::where('idUser', $userAuth->idUser)->get();
        DB::update('update usercontactnumber set active = 0 where idUser = ?', [$userAuth->idUser]);
        DB::update('update usercontactnumber set active = ? where id = ?', [$checked, $id]);
        return redirect()->route('contact_number', app()->getLocale())->with('success', trans('Updated successfully'));
    }



    public function preSaveContact($fullNumber, $isoP, $mobile, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;
        $countrie = $settingsManager->getCountryByIso($isoP);
        if (!$countrie) return;
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
    } public function render(settingsManager $settingsManager)
{
    $userAuth = $settingsManager->getAuthUser();
    if (!$userAuth) return;
    $userContactNumber = DB::table('usercontactnumber')
        ->selectRaw('usercontactnumber.id,usercontactnumber.idUser,usercontactnumber.mobile,usercontactnumber.codeP,usercontactnumber.active,
            usercontactnumber.isoP,usercontactnumber.fullNumber,usercontactnumber.isID')
        ->where('idUser', '=', $userAuth->idUser)
        ->where(
            function ($query) {
                return $query
                    ->where('mobile', 'like', '%' . $this->search . '%')
                    ->orWhere('id', 'like', '%' . $this->search . '%');
            })
        ->get();

    return view('livewire.contact-number', ['userContactNumber' => $userContactNumber])->extends('layouts.master')->section('content');
}
}
