<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\UserEarn;
use Core\Enum\TypeEventNotificationEnum;
use Core\Enum\TypeNotificationEnum;
use Core\Models\user_earn;
use Core\Models\UserContactNumber;
use Core\Services\settingsManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class EditPhoneNumber extends Component
{
    public $newPhone = 123456;
    protected $listeners = [
        'PreChangePhone' => 'PreChangePhone',
        'UpdatePhoneNumber' => 'UpdatePhoneNumber'
    ];

    public function render()
    {
        return view('livewire.edit-phone-number');
    }

    public function UpdatePhoneNumber($code, $phonenumber, $fullNumber, $codeP, $iso,settingsManager $settingsManager)
    {


        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;
        $user = $settingsManager->getUserById($userAuth->id);
        if (!$user) return;

        if ($code != $user->activationCodeValue)
            return redirect()->route("account", app()->getLocale())->with('ErrorSamePhone', 'Invalid OPT code');

        $country = $settingsManager->getCountryByIso($iso);
//        user_earn::where('idUser', $userAuth->idUser)->update(['mobile' => $phonenumber, 'fullphone_number' => $fullNumber, 'idCountry' => $codeP]);
        User::where('idUser', auth()->user()->idUser)->update([
            'mobile' => $phonenumber,
            'fullphone_number' => $fullNumber,
            'idCountry' => $country->id,
            'activationCodeValue' => $code,
            'id_phone'=>$codeP
        ]);

        $existeNumner = UserContactNumber::where('mobile', $phonenumber)
            ->where('isoP', $iso)
            ->where('idUser',$userAuth->idUser)
            ->first();
        if($existeNumner){
            DB::update('update usercontactnumber set active = 0 , isID=0 where idUser = ?', [$userAuth->idUser]);
            DB::update('update usercontactnumber set active = ? , isID=1 where id = ?', [1, $existeNumner->id]);
        }
        else{
            $newC = $settingsManager->createUserContactNumberByProp($userAuth->idUser, $phonenumber, $country->id, $iso, $fullNumber);
            DB::update('update usercontactnumber set active = 0 , isID= 0 where idUser = ?', [$userAuth->idUser]);
            DB::update('update usercontactnumber set active = ? ,isID = 1  where id = ?', [1, $newC->id]);
        }
        return redirect()->route("account", app()->getLocale())->with('SuccesUpdatePhone', 'Phone number changed');
    }

    public function PreChangePhone($phone, $fullNumber, $methodeVerification, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) return;
        $user = $settingsManager->getUserById($userAuth->id);
        if (!$user) return;
        if ($user->fullphone_number == $fullNumber)
            return redirect()->route("account", app()->getLocale())->with('ErrorSamePhone', Lang::get('Same_phone_number'));
        $userExiste = $settingsManager->getUserByFullNumber($fullNumber);
        if ($userExiste && $userExiste->id != $userAuth->id)
            return redirect()->route("account", app()->getLocale())->with('ErrorSamePhone', Lang::get('Phone_number_used'));
        if ($user->email == null || $user->email == "") {
            abort(404);
        }
        $check_exchange = rand(1000, 9999);
        User::where('id', $userAuth->id)->update(['activationCodeValue' => $check_exchange]);
//        user_earn::where('idUser', $userAuth->idUser)->update(['change_to' => $fullNumber, 'activationCodeValue' => $check_exchange]);
        $sendin = "";
        if ($methodeVerification == 'mail') {
            $settingsManager->NotifyUser($userAuth->id, TypeEventNotificationEnum::OPTVerification, [
                'canSendMail' => 1,
                'msg' => $check_exchange,
                'toMail' => $user->email,
                'emailTitle' => "2Earn.cash",
            ]);
            $sendin =  $user->email ;
        } elseif ($methodeVerification == 'phone') {
            $settingsManager->NotifyUser($userAuth->id, TypeEventNotificationEnum::OPTVerification, [
                'fullNumber' => $fullNumber,
                'msg' => $check_exchange,
                'type' => TypeNotificationEnum::SMS
            ]);
            $sendin =  $fullNumber ;
        } else
            return;
//        $settingsManager->NotifyUser($userAuth->id, TypeEventNotificationEnum::OPTVerification, [
//            'fullNumber' => $fullNumber,
//            'msg' => $check_exchange,
//            'type' => TypeNotificationEnum::SMS
//        ]);
        $this->dispatchBrowserEvent('PreChPhone', [
            'type' => 'warning',
            'title' => "Opt",
            'text' => '',
            'FullNumber' => $sendin,
            'methodeVerification' => $methodeVerification
        ]);

    }
}
