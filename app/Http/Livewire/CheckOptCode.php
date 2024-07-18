<?php

namespace App\Http\Livewire;

use App\Http\Traits\earnTrait;
use App\Models\MettaUser;
use App\Models\User;
use App\Notifications\contact_registred;
use Core\Enum\ActionEnum;
use Core\Enum\AmoutEnum;
use Core\Enum\BalanceOperationsEnum;
use Core\Enum\EventBalanceOperationEnum;
use Core\Enum\NotificationSettingEnum;
use Core\Enum\OperateurSmsEnum;
use Core\Enum\SettingsEnum;
use Core\Enum\TypeNotificationEnum;
use Core\Enum\TypeEventNotificationEnum;
use Core\Services\CommandeServiceManager;
use Core\Services\NotifyHelper;
use Core\Services\settingsManager;
use Core\Services\SmsHelper;
use Core\Services\UserBalancesHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Livewire\Component;
use Illuminate\Support\Facades\Crypt;

class CheckOptCode extends Component
{
    use earnTrait;

    public $code;
    public $idUser = 0;
    public $ccode;
    public $numPhone;
    protected $rules = [
        'code' => 'required|numeric'
    ];

    public function mount($iduser, $ccode, $numTel)
    {
        $this->idUser = $iduser;
        $this->ccode = $ccode;
        $this->numPhone = $numTel;
    }

    public function render()
    {
        return view('livewire.check-opt-code')->extends('layouts.master-without-nav')->section('content');
    }

    /**
     * @param settingsManager $settingsManager
     * @param CommandeServiceManager $commandeServiceManager
     * @param UserBalancesHelper $userBalancesHelper
     * @return RedirectResponse
     * 1 - CHECK validation code is required : return with error
     * 2 - check length code is 4 character : return with error
     * 3 - check expiration code : return ith error
     * 4 - check user not verified : return with error
     * 5 - generate a new password for  user
     * 6 - change status user to 0
     * 7 - assign role number 4 to the user
     * 8 - notify user with sms : send the new password
     * 9 - if the user is invited  : send notification to upline user
     * 10 - save operations of userBalance
     *  ToDo
     */
    public function verifCodeOpt(
        settingsManager        $settingsManager,
        CommandeServiceManager $commandeServiceManager,
        UserBalancesHelper     $userBalancesHelper,
    )
    {
        $this->validate();
        $user = $settingsManager->getUsers()->where('idUser', Crypt::decryptString($this->idUser))->first();

        if (substr($user->OptActivation, 0, 4) != ($this->code)) {
            return redirect()->route('check_opt_code', ["locale" => app()->getLocale(), "iduser" => $this->idUser, "ccode" => $this->ccode, "numTel" => $this->numPhone])->with('ErrorOptCode', Lang::get('Invalid OPT code'));
        }
        $date = date('Y-m-d H:i:s');
        if (abs(strtotime($date) - strtotime($user->OptActivation_at)) / 60 > 1500) {
            return redirect()->route('check_opt_code', ["locale" => app()->getLocale(), "iduser" => $this->idUser, "ccode" => $this->ccode, "numTel" => $this->numPhone])->with('ErrorExpirationCode', Lang::get('OPT code expired'));
        }
        $user = $settingsManager->getUserById($user->id);
        if ($user->status != -2) {
            return redirect()->route('check_opt_code', ["locale" => app()->getLocale(), "iduser" => $this->idUser, "ccode" => $this->ccode, "numTel" => $this->numPhone])->with('ErrorExpirationCode', Lang::get('User already verified'));
        }
        $userUpline = $settingsManager->checkUserInvited($user);
        $password = $this->randomNewPassword(8);
        $user->password = Hash::make($password);
        $user->pass = $password;
        $user->status = 0;
        if ($commandeServiceManager->saveUser($user)) {
            $settingsManager->NotifyUser($user->id, TypeEventNotificationEnum::Password, [
                'msg' => $password,
                'type' => TypeNotificationEnum::SMS
            ]);
            $user->assignRole(4);
            if ($userUpline) {
                $userUpline->notify(new contact_registred($user->fullphone_number));
                $settingsManager->NotifyUser($userUpline->id, TypeEventNotificationEnum::ToUpline, [
                    'msg' => $user->fullphone_number,
                    'toMail' => "khan_josef@hotmail.com",
                    'emailTitle' => "reg Title",
                ]);
            }
            $userBalancesHelper->AddBalanceByEvent(EventBalanceOperationEnum::Signup, $user->idUser);
        }
        return redirect()->route('login', app()->getLocale())->with('FromLogOut', Lang::get('fds'));
    }

}
