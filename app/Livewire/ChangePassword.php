<?php

namespace App\Livewire;

use App\Http\Traits\earnLog;
use App\Http\Traits\earnTrait;
use App\Models\User;
use App\Services\UserService;
use App\Services\UserNotificationSettingsService;
use Core\Enum\BalanceEnum;
use Core\Enum\NotificationSettingEnum;
use Core\Enum\TypeEventNotificationEnum;
use Core\Enum\TypeNotificationEnum;
use Core\Services\settingsManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class ChangePassword extends Component
{
    use earnTrait;
    use earnLog;

    protected UserService $userService;
    protected UserNotificationSettingsService $notificationSettingsService;

    const SEND_PASSWORD_CHANGE_OPT = false;

    public $oldPassword;
    public $newPassword;
    public $confirmedPassword;
    public $sendPassSMS = true;
    public $sendPasswordChangeOPT;
    public $soldeSms = 0;

    protected $listeners = [
        'PreChangePass' => 'PreChangePass',
        'changePassword' => 'changePassword',
        'ParamSendChanged' => 'ParamSendChanged',
        'changePasswordWithOPTValidation' => 'changePasswordWithOPTValidation',
    ];

    protected $rules = [
        'oldPassword' => 'required',
        'newPassword' => ['required', 'regex:/[0-9]/'],
        'confirmedPassword' => 'required|same:newPassword'
    ];

    public function boot(UserService $userService, UserNotificationSettingsService $notificationSettingsService)
    {
        $this->userService = $userService;
        $this->notificationSettingsService = $notificationSettingsService;
    }

    public function mount(settingsManager $settingManager)
    {
        $authUser = $settingManager->getAuthUser();
        if (!is_null($authUser)) {
            $notSettings = $settingManager->getNotificationSetting($authUser->idUser)
                ->where('idNotification', '=', NotificationSettingEnum::change_pwd_sms->value)->first();
            $this->sendPassSMS = $notSettings->value ?? true;

            $this->soldeSms = $settingManager->getSoldeByAmount($authUser->idUser, BalanceEnum::SMS);
            $this->soldeSms = $this->soldeSms == null ? 0 : $this->soldeSms;

            if ($this->sendPassSMS && $this->soldeSms <= 0) {
                $this->sendPassSMS = false;
            }
        }

        $this->initSendPasswordChangeOPT($settingManager->getidCountryForSms(auth()->user()->id));
    }

    public function initSendPasswordChangeOPT($userContactActif)
    {
        $paramSendPassword = DB::table('settings')->where("ParameterName", "=", "SEND_PASSWORD_CHANGE_OPT")->first();
        $paramInvalidCountry = DB::table('settings')->where("ParameterName", "=", "INVALID_OPT_COUNTRIES")->first();

        if (!is_null($paramSendPassword)) {
            $this->sendPasswordChangeOPT = $paramSendPassword->IntegerValue == 1 ? true : false;
        } else {
            $this->sendPasswordChangeOPT = self::SEND_PASSWORD_CHANGE_OPT;
        }

        if ($this->sendPasswordChangeOPT) {
            if (!is_null($paramInvalidCountry)) {
                if (in_array($userContactActif->codeP, explode(',', $paramInvalidCountry->StringValue))) {
                    $this->sendPasswordChangeOPT = false;
                }
            }
        }
    }

    public function ParamSendChanged(settingsManager $settingManager)
    {
        $authUser = $settingManager->getAuthUser();
        if (!$authUser) return;

        $this->notificationSettingsService->updateNotificationSetting(
            $authUser->idUser,
            NotificationSettingEnum::change_pwd_sms->value,
            $this->sendPassSMS
        );
    }

    public function PreChangePass(settingsManager $settingManager)
    {
        $userAuth = $settingManager->getAuthUser();
        if (!$userAuth) return;

        if ($this->sendPassSMS) {
            $settingManager->getSoldeByAmount($userAuth->idUser, BalanceEnum::SMS);
        }

        if (!Hash::check($this->oldPassword, auth()->user()->password)) {
            return redirect()->route('change_password', app()->getLocale())->with('danger', Lang::get('Old_Password_invalid'));
        }

        if ($this->newPassword != $this->confirmedPassword) {
            $this->earnDebug('Edit password input confirmed password invalid : userid- ' . $userAuth->id . ' newPassword- ' . $this->newPassword . ' confirmedPassword- ' . $this->confirmedPassword);
            return redirect()->route("change_password", app()->getLocale())->with('danger', Lang::get('Password_not_Confirmed'));
        }

        if ($this->sendPasswordChangeOPT) {
            $this->sendActivationCodeValue($userAuth, $settingManager);
        } else {
            $this->changePassword($userAuth, $settingManager);
        }
    }

    public function sendActivationCodeValue($userAuth, settingsManager $settingManager)
    {
        $check_exchange = $settingManager->randomNewCodeOpt();
        User::where('id', $userAuth->id)->update(['activationCodeValue' => $check_exchange]);
        $settingManager->NotifyUser($userAuth->id, TypeEventNotificationEnum::OPTVerification, ['msg' => $check_exchange, 'type' => TypeNotificationEnum::SMS]);
        $userContactActif = $settingManager->getidCountryForSms($userAuth->id);
        $fullNumberSend = $userContactActif->fullNumber;
        $this->dispatch('OptChangePass', ['type' => 'warning', 'title' => "Opt", 'text' => '', 'mail' => $fullNumberSend]);
    }

    public function changePasswordWithOPTValidation($code, settingsManager $settingManager)
    {
        $userAuth = $settingManager->getAuthUser();
        $user = $settingManager->getUserById($userAuth->id);

        if ($code != $user->activationCodeValue) {
            $this->earnDebug('Edit password input opt code OPT invalid : userid- ' . $userAuth->id . ' code- ' . $code);
            return redirect()->route("change_password", app()->getLocale())->with('danger', Lang::get('Invalid OPT code'));
        }

        if ($this->newPassword != $this->confirmedPassword) {
            $this->earnDebug('Edit password input confirmed password invalid : userid- ' . $userAuth->id . ' newPassword- ' . $this->newPassword . ' confirmedPassword- ' . $this->confirmedPassword);
            return redirect()->route("change_password", app()->getLocale())->with('danger', Lang::get('Password_not_Confirmed'));
        }

        if (!Hash::check($this->oldPassword, auth()->user()->password)) {
            return redirect()->route('change_password', app()->getLocale())->with('danger', Lang::get('Old_Password_invalid'));
        }

        $this->changePassword($userAuth, $settingManager);
    }

    public function changePassword($userAuth, settingsManager $settingManager)
    {
        $new_pass = Hash::make($this->newPassword);
        $this->userService->updatePassword(auth()->user()->id, $new_pass);

        $sendSMS = $this->sendPassSMS == true ? 1 : 0;
        $settingManager->NotifyUser($userAuth->id, TypeEventNotificationEnum::SendNewSMS, ['msg' => $this->newPassword, 'canSendSMS' => $sendSMS]);

        return redirect()->route('change_password', app()->getLocale())->with('success', Lang::get('Password updated'));
    }

    public function render()
    {
        return view('livewire.change-password');
    }
}

