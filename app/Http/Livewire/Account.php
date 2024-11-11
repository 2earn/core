<?php

namespace App\Http\Livewire;

use App\Http\Traits\earnLog;
use App\Http\Traits\earnTrait;
use App\Models\User;
use Carbon\Carbon;
use Core\Enum\AmoutEnum;
use Core\Enum\NotificationSettingEnum;
use Core\Enum\StatusRequest;
use Core\Enum\TypeEventNotificationEnum;
use Core\Enum\TypeNotificationEnum;
use Core\Models\identificationuserrequest;
use Core\Models\metta_user;
use Core\Models\UserNotificationSettings;
use Core\Services\settingsManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;
use Livewire\WithFileUploads;

class Account extends Component
{
    use WithFileUploads;
    use earnTrait;
    use earnLog;


    const SEND_PASSWORD_CHANGE_OPT = false;

    public $nbrChild = 9;
    public $photoFront;
    public $photoBack;
    public $user;
    public $usermetta_info;
    public $numberActif;
    public $countryUser;
    public $states;
    public $paramIdUser;
    public $oldPassword;
    public $newPassword;
    public $confirmedPassword;
    public $sendPassSMS = true;
    public $imageProfil;
    public $soldeSms = 0;
    public $PercentComplete = 0;
    public $errors_array;
    public $disabled;
    public $dispalyedUserCred;
    public $sendPasswordChangeOPT;
    public $userProfileImage;
    public $userNationalFrontImage;
    public $userNationalBackImage;
    public $userInternationalImage;

    protected $listeners = [
        'PreChangePass' => 'PreChangePass',
        'changePassword' => 'changePassword',
        'ParamSendChanged' => 'ParamSendChanged',
        'sendVerificationMail' => 'sendVerificationMail',
        'saveVerifiedMail' => 'saveVerifiedMail',
        'SaveChangeEdit' => 'SaveChangeEdit',
        'sendIdentificationRequest' => 'sendIdentificationRequest',
        'saveUser' => 'saveUser',
        'deleteContact' => 'deleteContact',
        'EmailCheckUser' => 'EmailCheckUser',
        'checkUserEmail' => 'checkUserEmail',
        'cancelProcess' => 'cancelProcess',
    ];

    protected $rules = [
        'oldPassword' => 'required',
        'newPassword' => ['required', 'regex:/[0-9]/'],
        'confirmedPassword' => 'required|same:newPassword'
    ];

    public function mount(settingsManager $settingManager)
    {
        $theId = auth()->user()->idUser;
        if (!is_null(auth()->user())) {
            $notSetings = $settingManager->getNotificationSetting(auth()->user()->idUser)
                ->where('idNotification', '=', NotificationSettingEnum::change_pwd_sms->value)->first();
            $this->sendPassSMS = $notSetings->value;
        }

        if ($this->paramIdUser != null && $this->paramIdUser != "") {
            $userAuth = $settingManager->getAuthUserById($this->paramIdUser);
            $theId = $userAuth->idUser;
        }
        $this->userProfileImage = User::getUserProfileImage($theId);
        $this->userNationalFrontImage = User::getNationalFrontImage($theId);
        $this->userNationalBackImage = User::getNationalBackImage($theId);
        $this->userInternationalImage = User::getInternational($theId);

        $this->initSendPasswordChangeOPT($settingManager->getidCountryForSms(auth()->user()->id));
    }

    public function initSendPasswordChangeOPT($userContactActif)
    {

        $paramSendPassword = DB::table('settings')->where("ParameterName", "=", "SEND_PASSWORD_CHANGE_OPT")->first();
        $paramInavalidCountry = DB::table('settings')->where("ParameterName", "=", "INVALID_OPT_COUNTRIES")->first();
        if (!is_null($paramSendPassword)) {
            $this->sendPasswordChangeOPT = $paramSendPassword->IntegerValue == 1 ? true : false;
        } else {
            $this->sendPasswordChangeOPT = self::SEND_PASSWORD_CHANGE_OPT;
        }
        if ($this->sendPasswordChangeOPT) {
            if (!is_null($paramInavalidCountry)) {
                if (in_array($userContactActif->codeP, explode(',', $paramInavalidCountry->StringValue))) {
                    $this->sendPasswordChangeOPT = false;
                }
            }
        }
    }

    public function SaveChangeEdit()
    {
        $um = metta_user::find($this->usermetta_info['id']);
        $um->enLastName = $this->usermetta_info['enLastName'];
        $um->enFirstName = $this->usermetta_info['enFirstName'];
        $um->birthday = $this->usermetta_info['birthday'];
        $um->nationalID = $this->usermetta_info['nationalID'];
        $um->save();
        if (!is_null($this->photoFront) && gettype($this->photoFront) == "object") {
            $this->photoFront->storeAs('profiles', 'front-id-image' . $um->idUser . '.png', 'public2');
        }
        if (!is_null($this->photoBack) && gettype($this->photoBack) == "object") {
            $this->photoBack->storeAs('profiles', 'back-id-image' . $um->idUser . '.png', 'public2');
        }
        return redirect()->route('account', app()->getLocale())->with('success', Lang::get('Edit_profil_succes'));
    }


    public function ParamSendChanged(settingsManager $settingManager)
    {
        UserNotificationSettings::where('idUser', $settingManager->getAuthUser()->idUser)
            ->where('idNotification', NotificationSettingEnum::change_pwd_sms->value)
            ->update(['value' => $this->sendPassSMS]);
    }


    public function CalculPercenteComplete()
    {
        $this->errors_array = array();
        $this->PercentComplete = 0;
        if (isset($this->usermetta_info['enFirstName']) && trim($this->usermetta_info['enFirstName']) != "" && isset($this->usermetta_info['enLastName']) && trim($this->usermetta_info['enLastName']) != "") {

            $this->PercentComplete += 20;
        }

        if (!isset($this->usermetta_info['enFirstName']) || trim($this->usermetta_info['enFirstName']) == "") {
            array_push($this->errors_array, getProfileMsgErreur('enFirstName'));
        }
        if (!isset($this->usermetta_info['enLastName']) || trim($this->usermetta_info['enLastName']) == "") {
            array_push($this->errors_array, getProfileMsgErreur('enLastName'));
        }

        if (isset($this->usermetta_info['birthday'])) {
            $this->PercentComplete += 20;
        } else {
            array_push($this->errors_array, getProfileMsgErreur('birthday'));
        }

        if (isset($this->usermetta_info['nationalID']) && trim($this->usermetta_info['nationalID']) != "") {

            $this->PercentComplete += 20;
        } else {
            array_push($this->errors_array, getProfileMsgErreur('nationalID'));
        }
        if (User::getNationalFrontImage($this->usermetta_info['idUser'] != User::DEFAULT_NATIONAL_FRONT_URL)
            && User::getNationalBackImage($this->usermetta_info['idUser']) != User::DEFAULT_NATIONAL_BACK_URL) {

            $this->PercentComplete += 20;
        } else {
            array_push($this->errors_array, getProfileMsgErreur('photoIdentite'));
        }

        if (isset($this->user['email']) && trim($this->user['email']) != "") {

            $this->PercentComplete += 20;
        } else {
            array_push($this->errors_array, getProfileMsgErreur('email'));
        }

    }


    public function deleteContact($id, settingsManager $settingsManager)
    {
        $userC = $settingsManager->getUserContactsById($id);
        if (!$userC) return;
        $userC->delete();
        return redirect()->route('account', app()->getLocale());
    }

    public function saveUser($nbrChild, settingsManager $settingsManager)
    {
        $canModify = true;
        $us = User::find($this->user['id']);
        $um = metta_user::find($this->usermetta_info['id']);

        if ($this->paramIdUser == "" && $us->hasIdentificationRequest()) {
            $canModify = false;
        }

        if (!$canModify) {
            return redirect()->route('account', app()->getLocale())->with('info', 'You cant update your profile when you have an identifiaction request in progress');
        }
        if ($canModify) {

            $um->arLastName = $this->usermetta_info['arLastName'];
            $um->arFirstName = $this->usermetta_info['arFirstName'];
            $um->enLastName = $this->usermetta_info['enLastName'];
            $um->enFirstName = $this->usermetta_info['enFirstName'];
            if (!empty($this->usermetta_info['birthday'])) {
                $um->birthday = $this->usermetta_info['birthday'];
            } else {
                $um->birthday = null;
            }
            $um->adresse = $this->usermetta_info['adresse'];
            $um->nationalID = $this->usermetta_info['nationalID'];
        }
        if ($nbrChild < 0) {
            $nbrChild = 0;
        }
        if ($nbrChild > 20) {
            $nbrChild = 20;
        }
        $um->childrenCount = $nbrChild;
        if (!empty($this->usermetta_info['idState'])) {
            $um->idState = $this->usermetta_info['idState'];
        } else {
            $um->idState = null;
        }
        $um->gender = $this->usermetta_info['gender'];
        $um->personaltitle = $this->usermetta_info['personaltitle'];
        $um->idLanguage = $this->usermetta_info['idLanguage'];
        if ($this->paramIdUser != "") {
            $us->status = StatusRequest::InProgressNational->value;
        }
        $um->save();
        $um = metta_user::find($this->usermetta_info['id']);
        $us->is_public = $this->user['is_public'];
        $us->save();
        $us = User::find($this->user['id']);

        try {
            if (!is_null($this->imageProfil)) {
                User::saveProfileImage($us->idUser, $this->imageProfil);
            }
        } catch (\Exception $e) {
            return redirect()->route('account', app()->getLocale())->with('success', Lang::get($e->getMessage()));
        }

        if ($this->paramIdUser == "")
            return redirect()->route('account', app()->getLocale())->with('success', Lang::get('Edit_profil_succes'));
        else {
            $settingsManager->validateIdentity($us->idUser);
            return redirect()->route('requests_identification', app()->getLocale());
        }
    }

    public function PreChangePass(settingsManager $settingManager)
    {
        $userAuth = $settingManager->getAuthUser();
        if (!$userAuth) return;

        if ($this->sendPassSMS) {
            $settingManager->getSoldeByAmount($userAuth->idUser, AmoutEnum::Sms_Balance);
        }

        if (!Hash::check($this->oldPassword, auth()->user()->password)) {
            return redirect()->route('account', app()->getLocale())->with('danger', Lang::get('Old_Password_invalid'));
        }

        if ($this->newPassword != $this->confirmedPassword) {
            $this->earnDebug('Edit password input confirmed password invalide  : userid- ' . $userAuth->id . 'newPassword- ' . $this->newPassword . ' confirmedPassword- ' . $this->confirmedPassword);
            return redirect()->route("account", app()->getLocale())->with('danger', Lang::get('Password_not_Confirmed'));
        }
        if ($this->sendPasswordChangeOPT) {
            $this->sendActivationCodeValue($userAuth, $settingManager);
        } else {
            $this->changePassword($userAuth, $settingManager);
        }
    }

    public
    function sendActivationCodeValue($userAuth, settingsManager $settingManager)
    {
        $check_exchange = $settingManager->randomNewCodeOpt();
        User::where('id', $userAuth->id)->update(['activationCodeValue' => $check_exchange]);

        $settingManager->NotifyUser($userAuth->id, TypeEventNotificationEnum::OPTVerification, [
            'msg' => $check_exchange,
            'type' => TypeNotificationEnum::SMS
        ]);
        $userContactActif = $settingManager->getidCountryForSms($userAuth->id);
        $fullNumberSend = $userContactActif->fullNumber;

        $this->dispatchBrowserEvent('OptChangePass', ['type' => 'warning', 'title' => "Opt", 'text' => '', 'mail' => $fullNumberSend]);
    }

    public function changePasswordWithOPTValidation($code, settingsManager $settingManager)
    {
        $userAuth = $settingManager->getAuthUser();
        $user = $settingManager->getUserById($userAuth->id);
        if ($code != $user->activationCodeValue) {
            $this->earnDebug('Edit password input opt code OPT invalide  :  userid- ' . $userAuth->id . ' code- ' . $code);
            return redirect()->route("account", app()->getLocale())->with('danger', Lang::get('Invalid OPT code'));
        }
        if ($this->newPassword != $this->confirmedPassword) {
            $this->earnDebug('Edit password input confirmed password invalide  : userid- ' . $userAuth->id . 'newPassword- ' . $this->newPassword . ' confirmedPassword- ' . $this->confirmedPassword);
            return redirect()->route("account", app()->getLocale())->with('danger', Lang::get('Password_not_Confirmed'));
        }
        if (!Hash::check($this->oldPassword, auth()->user()->password)) {
            return redirect()->route('account', app()->getLocale())->with('danger', Lang::get('Old_Password_invalid'));
        }
        $this->changePassword($userAuth, $settingManager);

    }

    public function changePassword($userAuth, settingsManager $settingManager)
    {
        $new_pass = Hash::make($this->newPassword);
        DB::table('users')->where('id', auth()->user()->id)->update(['password' => $new_pass]);
        $sendSMS = $this->sendPassSMS == true ? 1 : 0;
        $settingManager->NotifyUser($userAuth->id, TypeEventNotificationEnum::SendNewSMS, ['msg' => $this->newPassword, 'canSendSMS' => $sendSMS]);
        return redirect()->route('account', app()->getLocale())->with('success', Lang::get('Password updated'));
    }

    public function sendVerificationMail($mail, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) abort(404);
        if (!isValidEmailAdressFormat($mail)) {
            return redirect()->route('account', app()->getLocale())->with('danger', Lang::get('Not valid Email Format'));
        }
        if ($userAuth->email == $mail) {
            return redirect()->route('account', app()->getLocale())->with('danger', Lang::get('Same email Adress'));
        }
        $userExisteMail = $settingsManager->getConditionalUser('email', $mail);
        if ($userExisteMail && $userExisteMail->idUser != $userAuth->idUser) {
            return redirect()->route('account', app()->getLocale())->with('danger', Lang::get('mail_used'));
        }
        $opt = $this->randomNewCodeOpt();
        $us = User::find($this->user['id']);
        $us->OptActivation = $opt;
        $us->OptActivation_at = Carbon::now();
        $us->save();
        $numberActif = $settingsManager->getNumberCOntactActif($userAuth->idUser)->fullNumber;
        $settingsManager->NotifyUser($userAuth->id, TypeEventNotificationEnum::VerifMail, ['msg' => $opt, 'type' => TypeNotificationEnum::SMS]);
        $this->dispatchBrowserEvent('confirmOPTVerifMail', ['type' => 'warning', 'title' => "Opt", 'text' => '', 'numberActif' => $numberActif]);
        $this->newMail = $mail;
    }

    public function cancelProcess($message)
    {
        return redirect()->route('account', app()->getLocale())->with('warning', Lang::get($message));
    }

    public function checkUserEmail($codeOpt, settingsManager $settingsManager)
    {
        $us = User::find($this->user['id']);
        if ($codeOpt != $us->OptActivation) {
            $this->dispatchBrowserEvent('EmailCheckUser', ['emailValidation' => false, 'title' => trans('Invalid OPT code')]);
            return;
        }
        $check_exchange = $this->randomNewCodeOpt();
        User::where('id', auth()->user()->id)->update(['OptActivation' => $check_exchange]);
        $settingsManager->NotifyUser(auth()->user()->id, TypeEventNotificationEnum::NewContactNumber, ['canSendMail' => 1, 'msg' => $check_exchange, 'toMail' => $this->newMail, 'emailTitle' => "2Earn.cash"]);
        $this->dispatchBrowserEvent('EmailCheckUser', ['emailValidation' => true, 'title' => trans('Opt code from your email'), 'html' => trans('We sent an opt code to your email') . ' : ' . $this->newMail . ' <br> ' . trans('Please fill it')]);
    }

    public function saveVerifiedMail($codeOpt)
    {
        $us = User::find($this->user['id']);
        if ($codeOpt != $us->OptActivation) {
            return redirect()->route('account', app()->getLocale())->with('danger', Lang::get('Change user email failed - Code OPT'));
        }
        $us->email_verified = 1;
        $us->email = $this->newMail;
        $us->email_verified_at = Carbon::now();
        $us->save();
        return redirect()->route('account', app()->getLocale())->with('success', Lang::get('User email change completed successfully'));
    }

    public function approuve($idUser, settingsManager $settingsManager)
    {
        $user = User::find($idUser);
        if ($user) {
            $settingsManager->validateIdentity($user->idUser);
            return redirect()->route('requests_identification', app()->getLocale())->with('success', Lang::get('User identification request approuved') . ' : ' . $user->email);
        }
    }

    public function reject($idUser, settingsManager $settingsManager)
    {
        $user = User::find($idUser);
        if ($user) {
            $settingsManager->rejectIdentity($user->idUser, $this->noteReject);
            return redirect()->route('requests_identification', app()->getLocale())->with('success', Lang::get('User identification request rejected') . ' : ' . $user->email);
        }
    }

    public
    function sendIdentificationRequest(settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        $hasRequest = $userAuth->hasIdentificationRequest();
        if ($hasRequest) {
            return redirect()->route('account', app()->getLocale())->with('danger', Lang::get('Identification request exist'));
        } else {
            identificationuserrequest::create(['idUser' => $userAuth->idUser, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'response' => 0, 'note' => '', 'status' => 1]);
            return redirect()->route('account', app()->getLocale())->with('success', Lang::get('Identification_send_succes'));
        }
    }


    public function render(settingsManager $settingsManager, Request $request)
    {
        $this->paramIdUser = $request->input('paramIdUser');
        if ($this->paramIdUser == null) $this->paramIdUser = "";
        if ($this->paramIdUser == null || $this->paramIdUser == "")
            $userAuth = $settingsManager->getAuthUser();
        else {
            $this->noteReject = Lang::get('Note_rejected');
            $userAuth = $settingsManager->getAuthUserById($this->paramIdUser);
        }
        $this->dispalyedUserCred = getUserDisplayedName($userAuth->idUser);

        if (!$userAuth)
            dd('not found page');
        $this->numberActif = $settingsManager->getidCountryForSms($userAuth->id)->fullNumber;
        $this->genders = DB::table('genders')->get();
        $this->personaltitles = DB::table('personal_titles')->get()->toArray();
        $this->languages = DB::table('languages')->get();
        $usermetta_info = collect(DB::table('metta_users')->where('idUser', $userAuth->idUser)->first());
        $user = DB::table('users')->where('idUser', $userAuth->idUser)->first();
        $this->countryUser = Lang::get($settingsManager->getCountrieById($user->idCountry)->name);
        $this->usermetta_info = $usermetta_info;
        $this->user = collect($user);
        $this->states = $settingsManager->getStatesContrie($user->id_phone);
        $this->soldeSms = $settingsManager->getSoldeByAmount($userAuth->idUser, AmoutEnum::Sms_Balance);
        $this->soldeSms = $this->soldeSms == null ? 0 : $this->soldeSms;
        if ($this->sendPassSMS) {
            if ($this->soldeSms <= 0) {
                $this->sendPassSMS = 0;
            }
        }
        $this->CalculPercenteComplete();
        $hasRequest = $userAuth->hasIdentificationRequest();
        $this->disabled = in_array($user->status, [StatusRequest::InProgressNational->value, StatusRequest::InProgressInternational->value, StatusRequest::InProgressGlobal->value, StatusRequest::ValidNational->value, StatusRequest::ValidInternational->value]) ? true : false;

        return view('livewire.account', ['hasRequest' => $hasRequest, 'errors_array' => $this->errors_array])->extends('layouts.master')->section('content');
    }


}
