<?php

namespace App\Http\Livewire;

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
        if (!$userAuth) return;

        $number = UserContactNumber::find($id);
        if ($number->active) {
            return redirect()->route('ContactNumber', app()->getLocale())->with('faileddeleteActiveNumber', '');
        }
        if ($number->isID == 1) {
            return redirect()->route('ContactNumber', app()->getLocale())->with('failedDeleteIDNumber', Lang::get('Failed_Delete_ID_Number'));
        }
        $deleted = $number->delete();
        return redirect()->route('ContactNumber', app()->getLocale())->with('deleteNumberContactSucces', '');
    }

    public function saveContactNumber($code, $iso, $mobile, $fullNumber, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;
        $user = $settingsManager->getUserById($userAuth->id);
        if (!$user) return;
        $countrie = $settingsManager->getCountryByIso($iso);
        if (!$countrie) return;
        if ($code != $user->OptActivation)
            return redirect()->route("ContactNumber", app()->getLocale())->with('ErrorOptAddNumber', 'Invalid OPT code');
        $newC = $settingsManager->createUserContactNumberByProp($userAuth->idUser, $mobile, $countrie->id, $iso, $fullNumber);
        return redirect()->route('ContactNumber', app()->getLocale())->with('AddNumberContactSucces', '');
    }

    public function setActiveNumber($checked, $id, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();

        if (!$userAuth) return;

        if (!$checked) return;

        $userContactNumber = UserContactNumber::where('idUser', $userAuth->idUser)->get();
        DB::update('update usercontactnumber set active = 0 where idUser = ?', [$userAuth->idUser]);
        DB::update('update usercontactnumber set active = ? where id = ?', [$checked, $id]);
        return redirect()->route('ContactNumber', app()->getLocale())->with('succesUpdate', '');
    }

    public function render(settingsManager $settingsManager)
    {

//        $egypt = country('eg');
//        dd($countries) ;
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;
//        $userContactNumber = collect(UserContactNumber::with('idUser',$userAuth->idUser));
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
            ->paginate(5);

//dd($userContactNumber);
        return view('livewire.contact-number', [
            'userContactNumber' => $userContactNumber
        ])->extends('layouts.master')->section('content');
    }

    public function preSaveContact($fullNumber, $isoP, $mobile, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;
        $countrie = $settingsManager->getCountryByIso($isoP);
        if (!$countrie) return;
//        $existeNumner = UserContactNumber::where('mobile', $mobile)->where('isoP', $isoP)->first();
//        if ($existeNumner) return redirect()->route('ContactNumber', app()->getLocale())->with('numberPhoneexiste', Lang::get('number_existe'));
        $check_exchange = $this->randomNewCodeOpt();
        User::where('id', $userAuth->id)->update(['OptActivation' => $check_exchange]);

        $numberID = $settingsManager->getNumberCOntactID($userAuth->idUser)->fullNumber;
        $userMail = $settingsManager->getUserById($userAuth->id)->email;


        $settingsManager->NotifyUser($userAuth->id, TypeEventNotificationEnum::OPTVerification, [
            'fullNumber' => $numberID,
            'msg' => $check_exchange,
            'type' => TypeNotificationEnum::SMS,
            'isoP' => $isoP
        ]);
        $userMailSend ="";
        if ($userMail != null) {
            $settingsManager->NotifyUser($userAuth->id, TypeEventNotificationEnum::NewContactNumber, [
                'canSendMail' => 1,
                'msg' => $check_exchange,
                'toMail' => $userMail,
                'emailTitle' => "2Earn.cash",

            ]);
            $userMailSend= $userMail;
        }
        $msgSend = Lang::get('We_will_sendWithMail');
        if ($userMail == null || $userMail == ""  ) {
            $msgSend = Lang::get('We_will_send');

        }

        $this->dispatchBrowserEvent('PreAddNumber', [
            'tyepe' => 'warning',
            'title' => "Opt",
            'text' => '',
            'FullNumber' => $numberID,
            'FullNumberNew' => $fullNumber,
            'userMail'=>$userMail,
            'isoP' => $isoP,
            'mobile' => $mobile,
            'msgSend'=>$msgSend
        ]);
    }
}
