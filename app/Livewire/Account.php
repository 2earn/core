<?php

namespace App\Livewire;

use App\Enums\StatusRequest;
use App\Enums\TypeEventNotificationEnum;
use App\Enums\TypeNotificationEnum;
use App\Http\Traits\earnLog;
use App\Http\Traits\earnTrait;
use App\Models\User;
use App\Services\UserService;
use Carbon\Carbon;
use App\Models\identificationuserrequest;
use App\Models\metta_user;
use App\Services\settingsManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class Account extends Component
{
    use WithFileUploads;
    use earnTrait;
    use earnLog;

    protected UserService $userService;


    public $nbrChild = 9;
    public $photoFront;
    public $noteReject;
    public $photoBack;
    public $user;
    public $newMail;
    public $usermetta_info;
    public $numberActif;
    public $countryUser;
    public $states;
    public $paramIdUser;
    public $imageProfil;
    public $PercentComplete = 0;
    public $errors_array;
    public $disabled;
    public $dispalyedUserCred;
    public $userProfileImage;
    public $userNationalFrontImage;
    public $userNationalBackImage;
    public $userInternationalImage;
    public $personaltitles;
    public $genders;
    public $languages;
    public $activeTab = 'personalDetails';
    public $originalIsPublic;

    protected $listeners = [
        'sendVerificationMail' => 'sendVerificationMail',
        'saveVerifiedMail' => 'saveVerifiedMail',
        'SaveChangeEdit' => 'SaveChangeEdit',
        'sendIdentificationRequest' => 'sendIdentificationRequest',
        'saveUser' => 'saveUser',
        'deleteContact' => 'deleteContact',
        'EmailCheckUser' => 'EmailCheckUser',
        'checkUserEmail' => 'checkUserEmail',
        'cancelProcess' => 'cancelProcess',
        'saveProfileSettings' => 'saveProfileSettings',
    ];

    public function boot(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function mount(settingsManager $settingManager)
    {
        $theId = auth()->user()->idUser;

        if ($this->paramIdUser != null && $this->paramIdUser != "") {
            $userAuth = $settingManager->getAuthUserById($this->paramIdUser);
            $theId = $userAuth->idUser;
        }
        $this->userProfileImage = User::getUserProfileImage($theId);
        $this->userNationalFrontImage = User::getNationalFrontImage($theId);
        $this->userNationalBackImage = User::getNationalBackImage($theId);
        $this->userInternationalImage = User::getInternational($theId);

        $this->personaltitles = DB::table('personal_titles')->get();
        $this->genders = DB::table('genders')->get();

        $this->languages = DB::table('languages')->get();
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
        return redirect()->route('account', app()->getLocale())->with('success', Lang::get('Edit profile success'));
    }

    public function saveProfileSettings()
    {
        try {
            $user = User::find($this->user['id']);

            if (!is_null($this->imageProfil)) {
                User::saveProfileImage($user->idUser, $this->imageProfil);
                $this->userProfileImage = User::getUserProfileImage($user->idUser);
            }

            $user->is_public = $this->user['is_public'];
            $user->save();

            $this->originalIsPublic = $this->user['is_public'];

            $this->imageProfil = null;

            session()->flash('success', Lang::get('Profile settings saved successfully'));

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            session()->flash('danger', Lang::get('Error saving profile settings'));
        }
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
            return redirect()->route('account', app()->getLocale())->with('info', Lang::get('You cant update your profile when you have an identifiaction request in progress'));
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
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->route('account', app()->getLocale())->with('danger', Lang::get($exception->getMessage()));
        }

        if ($this->paramIdUser == "")
            return redirect()->route('account', app()->getLocale())->with('success', Lang::get('Edit profile success'));
        else {
            $settingsManager->validateIdentity($us->idUser);
            return redirect()->route('requests_identification', app()->getLocale());
        }
    }


    public function sendVerificationMail($mail, settingsManager $settingsManager)
    {
        $userAuth = $settingsManager->getAuthUser();
        if (!$userAuth) abort(404);
        if (!isValidEmailAdressFormat($mail)) {
            return redirect()->route('account', app()->getLocale())->with('danger', Lang::get('Not valid Email Format'));
        }
        if ($userAuth->email == $mail) {
            return redirect()->route('account', app()->getLocale())->with('danger', Lang::get('Same email Address'));
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
        $this->newMail = $mail;
        $this->dispatch('confirmOPTVerifMail', ['type' => 'warning', 'title' => "Opt", 'text' => '', 'numberActif' => $numberActif]);
    }

    public function cancelProcess($message)
    {
        return redirect()->route('account', app()->getLocale())->with('warning', Lang::get($message));
    }

    public function checkUserEmail($codeOpt = null, settingsManager $settingsManager)
    {
        $us = User::find($this->user['id']);
        if (is_null($codeOpt) || $codeOpt != $us->OptActivation) {
            return redirect()->route('account', app()->getLocale())->with('danger', Lang::get('Invalid OPT code'));
        }
        $check_exchange = $this->randomNewCodeOpt();
        User::where('id', auth()->user()->id)->update(['OptActivation' => $check_exchange]);
        $settingsManager->NotifyUser(auth()->user()->id, TypeEventNotificationEnum::NewContactNumber, ['canSendMail' => 1, 'msg' => $check_exchange, 'toMail' => $this->newMail, 'emailTitle' => "2Earn.cash"]);
        $this->dispatch('EmailCheckUser', ['emailValidation' => true, 'title' => trans('Opt code from your email'), 'html' => trans('We sent an opt code to your email') . ' : ' . $this->newMail . ' <br> ' . trans('Please fill it')]);
    }

    public function saveVerifiedMail($codeOpt = null)
    {
        $us = User::find($this->user['id']);
        if (is_null($codeOpt) || $codeOpt != $us->OptActivation) {
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
            return redirect()->route('account', app()->getLocale())->with('success', Lang::get('Identification send request success'));
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

        $usermetta_info = collect(DB::table('metta_users')->where('idUser', $userAuth->idUser)->first());
        if (is_null($usermetta_info->get('childrenCount'))) {
            $usermetta_info->put('childrenCount', 0);
        }
        $user = $this->userService->findByIdUser($userAuth->idUser);
        $this->countryUser = Lang::get($settingsManager->getCountrieById($user->idCountry)->name);
        $this->usermetta_info = $usermetta_info;
        $this->user = collect($user);
        $this->originalIsPublic = $user->is_public;
        $this->states = $settingsManager->getStatesContrie($user->id_phone);
        $this->CalculPercenteComplete();
        $hasRequest = $userAuth->hasIdentificationRequest();
        $this->disabled = in_array($user->status, [StatusRequest::InProgressNational->value, StatusRequest::InProgressInternational->value, StatusRequest::InProgressGlobal->value, StatusRequest::ValidNational->value, StatusRequest::ValidInternational->value]) ? true : false;

        $justExpired = $lessThanSixMonths = false;
        if (!is_null(auth()->user()->expiryDate)) {
            $daysNumber = getDiffOnDays(auth()->user()->expiryDate);
            $lessThanSixMonths = $daysNumber < 180 ? true : false;
            $justExpired = $daysNumber < 1 ? true : false;
        }

        return view('livewire.account', [
            'hasRequest' => $hasRequest,
            'errors_array' => $this->errors_array,
            'justExpired' => $justExpired,
            'lessThanSixMonths' => $lessThanSixMonths
        ])->extends('layouts.master')->section('content');
    }
}
