<?php

namespace App\Http\Livewire;

use App\Http\Traits\earnLog;
use App\Http\Traits\earnTrait;
use App\Models\User;
use Core\Enum\TypeEventNotificationEnum;
use Core\Enum\TypeNotificationEnum;
use Core\Services\settingsManager;
use Core\Services\TransactionManager;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Livewire\Component;

class Registre extends Component
{
    use earnTrait;
    use earnLog;

    public $fullNumber;
    public $country_code;
    public $ccode;
    public $phoneNumber;
    public $iso2Country;
    protected $listeners = ['changefullNumber' => 'changefullNumbe'];

    protected array $rules = [
        'phoneNumber' => 'required|numeric'
    ];

    public function changefullNumbe($num, $ccode, $iso, settingsManager $settingsManager, TransactionManager $transactionManager)
    {
        $this->ccode = $ccode;
        $this->fullNumber = $num;
        $this->iso2Country = $iso;
        $this->signup($settingsManager, $transactionManager);
    }

    public function signup(settingsManager $settingsManager, TransactionManager $transactionManager)
    {
        if ($this->phoneNumber == "") {
            return redirect()->route('registre', app()->getLocale())->with('errorPhoneValidation', Lang::get('your message,here'));
        }
        $newUser = null;
        $user = $settingsManager->getUserByFullNumber($this->fullNumber);
        if (!$user) {
            $newUser = $this->initNewUser();
        }
        if ($user && $user->status != -2) {
            return redirect()->route('registre', app()->getLocale())->with('errorPhoneExiste', Lang::get('UserExiste'));
        }
        if ($user) {
            $newUser = $settingsManager->getUserById($user->id);
        }

        $newcode = $this->randomNewCodeOpt();
        $newUser->OptActivation = $newcode;
        $newUser->OptActivation_at = date('Y-m-d H:i:s');

        $this->earnDebug('inscription newUserMobile before substr : ' . $newUser->mobile);
        $this->earnDebug('inscription newCcode  : ' . $newUser->ccode);
        $newUser->mobile = substr($this->fullNumber, Str::length($this->ccode) + 2);
        $this->earnDebug('inscription newUserMobile after substr : ' . $newUser->mobile);
        $newUser->mobile = $this->formatMobileNumber($newUser->mobile);
        $this->earnDebug('inscription newUserMobile after formatMobileNumber : ' . $newUser->mobile);
        $country = $settingsManager->getCountryByIso($this->iso2Country);
        $newUser->idCountry = $country->id;
        $newUser->fullphone_number = $this->fullNumber;
        $newUser->registred_from = 3;
        $newUser->id_phone = $this->ccode;
        $newUser->email_verified = 0;

        $transactionManager->beginTransaction();
        $settingsManager->createUserContactNumber($newUser, $this->iso2Country);

        $settingsManager->createMettaUser($newUser);
        $usere = $newUser->save();
        $transactionManager->commit();
        if ($usere) {
            $settingsManager->NotifyUser($newUser->id, TypeEventNotificationEnum::Inscri, ['msg' => $newcode, 'type' => TypeNotificationEnum::SMS]);
        }
        $settingsManager->generateNotificationSetting($newUser->idUser);
        return redirect()->route('check_opt_code', ["locale" => app()->getLocale(), "iduser" => Crypt::encryptString($newUser->idUser), "ccode" => $this->ccode, "numTel" => $this->fullNumber]);
    }

    public function initNewUser()
    {
        $newUser = new User();
        $lastuser = DB::table('users')->max('iduser');
        $newIdUser = $lastuser + 1;
        $newUser->idUser = $newIdUser;
        $newUser->status = -2;
        return $newUser;
    }

    public function mount()
    {
        $ip = ip2long(request()->ip());
        $ip = long2ip($ip);
        if ($ip = '0.0.0.0') {
            $ip = "41.226.181.241";
        }
        $IP = $ip;
        $json = file_get_contents("http://ipinfo.io/{$ip}/geo");
        $details = json_decode($json, true);
        $this->country_code = $details['country'];
    }

    public function render()
    {
        return view('livewire.registre')->extends('layouts.master-without-nav')->section('content');
    }
}
